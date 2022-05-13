<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment\Payment;
use App\Models\Payment\PaymentReference;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;


class PesaPalController extends Controller
{
    public function index(Request $request)
    {

        $payload = simplexml_load_string($request->getContent());

        $data = json_encode($payload);
        $jdata =  json_decode($data,true);

//        $phone = preg_replace('/[^A-Za-z0-9\-]/', '', $jdata['MSISDN']);
        if (strpos($phone,"256") !== false) {
            if (strpos($jdata['CUSTOMERREFERENCEID'], "TULI") !== false) {
                $jdata['paymentReference'] = $jdata['CUSTOMERREFERENCEID'];
            } else {
                $jdata['paymentReference'] = "TULI" . $jdata['CUSTOMERREFERENCEID'];
            }
//        }

        //        Check Existence Of account  Redis
        $values = PaymentReference::where('payment_reference',$jdata['paymentReference'])->first();

        $RESULT = null;
        $serviceStatus = null;
        $code = null;

        if (isset($values)){
            $payment = Payment::where('trans_id',$jdata['TXNID'])->first();
            if(isset($payment)){
                $serviceStatus = 'DUPLICATE';
                $code = '015';
                $RESULT = 'TF';
            }else{
                $this->sendToWebi($jdata);
                $serviceStatus = 'SUCCESSFUL';
                $code = '000';
                $RESULT = 'TS';
            }
        }else{
            $this->sendToWebi($jdata);
            $serviceStatus = 'INVALID REFERENCE';
            $code = '010';
            $RESULT = 'TF';
        }

        $initial_response =  array(
            'TYPE' => $jdata['COMPANYNAME'],
            'REFID' => $jdata['paymentReference'],
            'TXNID' => $jdata['AGTXNID'],
            'RESULT' => $RESULT,
            'ERRORCODE' => $code,
            'ERRORDESC' => $serviceStatus,
            'MSISDN' => $jdata['MSISDN'],
            'AGTXNID' => $jdata['AGTXNID']);

        $this->save($jdata,$serviceStatus,$RESULT);
        $response = $this->createResponse($initial_response);

        return response($response,'201')->header('Content-Type','application/xml');
    }

    public function save($data,$status,$RESULT)
    {
        $phone = preg_replace('/[^A-Za-z0-9\-]/', '', $data['MSISDN']);
        if (strpos($phone,"256") !== false){
            $country = "UG";
        }else{
            $country = "TZ";
        }

        $payment = new Payment();
           $payment->service_id = $data['COMPANYNAME'];
           $payment->trans_id = $data['TXNID'];
           $payment->amount = $data['AMOUNT'];
           $payment->payment_status = $status;
           $payment->reference_no = $data['paymentReference'];
           $payment->payment_receipt = $data['paymentReference'];
           $payment->msnid = $phone;
//           $payment->trans_date = $data['TYPE'];
           $payment->opco = $country;
           $payment->payment_status_desc = $RESULT;

       return $payment->save();
    }

    function createResponse($responseData=null){

        $dom = new DOMDocument('1.0','UTF-8');
        $dom->formatOutput = true;
        $version="1.0";


        $namespaceuri= "http://infowise.co.tz/broker/";

        $root = $dom->createElementNS($namespaceuri,'COMMAND'); //append namespace to root
        $root->appendChild($dom->createAttribute('version'))->appendChild($dom->createTextNode($version)); //append version 2.0
        $dom->appendChild($root);

        $root->appendChild($dom->createElement('TYPE', $responseData['TYPE']));
        $root->appendChild($dom->createElement('REFID', $responseData['REFID']));
//        $root->appendChild($dom->createElement('TXNID', $responseData['TXNID']));
        $root->appendChild($dom->createElement('RESULT', $responseData['RESULT']));
        $root->appendChild($dom->createElement('ERRORCODE', $responseData['ERRORCODE']));
        $root->appendChild($dom->createElement('ERRORDESC', $responseData['ERRORDESC']));
        $root->appendChild($dom->createElement('MSISDN', $responseData['MSISDN']));
//        $root->appendChild($dom->createElement('AGTXNID', $responseData['AGTXNID']));

        $output=$dom->saveXML();
        return $output;
    }

    public function sendToWebi($data)
    {
        $phone = preg_replace('/[^A-Za-z0-9\-]/', '', $data['MSISDN']);
        if (strpos($phone,"256") !== false){
            $currency = "UGX";
            $country = "UGANDA";
        }else{
            $currency = "TZS";
            $country = "TANZANIA";
        }

        $url = "https://api.ninox.com/v1/teams/tBEzT47PPxBqkK3n2/databases/s09bhyujje50/tables/B/records/";

        $data = [
            'fields'=>[
                'paymentReference' => $data['paymentReference'],
                "amount" => $data['AMOUNT'],
                "currency" => $currency,
                "ssl_transaction_id" => $data['TXNID'],
                "country" => $country,
                "financialServiceProvider" => $data['COMPANYNAME'],
                "payer" => $phone,
                "date" => date('d/m/Y',strtotime(now())),
                "transactionId" => $data['TXNID']
            ],
        ];

        return $response = Http::withHeaders([
            'Authorization' => 'Bearer 24f44360-8656-11ec-adbe-11a9b089aec7',
            'Content-Type' => 'application/json'
        ])->post($url, [
            $data
        ]);

    }

    public function checkTuli()
    {
        if (strpos($jdata['CUSTOMERREFERENCEID'],"TULI") !== false){
            $tuli = $jdata['paymentReference'] = $jdata['CUSTOMERREFERENCEID'];
        }else{
            $tuli =$jdata['paymentReference'] = "TULI".$jdata['CUSTOMERREFERENCEID'];
        }

        return  $tuli;
    }
}
