<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment\Payment;
use App\Models\Payment\PaymentReference;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;


class PesaPalController extends Controller
{
    public function index(Request $request)
    {

        $payload = simplexml_load_string($request->getContent());

        $data = json_encode($payload);
        $jdata =  json_decode($data,true);

        //        Check Existence Of account  Redis
        $values = PaymentReference::where('payment_reference',$jdata['CUSTOMERREFERENCEID'])->first();

        $RESULT = null;
        $serviceStatus = null;
        $code = null;

        if (isset($values)){
            $payment = Payment::where('trans_id',$jdata['TXNID'])->first();
            if(isset($payment)){
                $serviceStatus = 'Duplicate payment';
                $code = '015';
                $RESULT = 'TF';
            }else{
                $serviceStatus = 'SUCCESSFUL';
                $code = '000';
                $RESULT = 'TS';
            }
        }else{
            $serviceStatus = 'Invalid Customer Reference Number';
            $code = '010';
            $RESULT = 'TF';
        }

        $initial_response =  array(
            'TYPE' => $jdata['COMPANYNAME'],
            'REFID' => $jdata['CUSTOMERREFERENCEID'],
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
        $payment = new Payment();
           $payment->service_id = $data['COMPANYNAME'];
           $payment->trans_id = $data['TXNID'];
           $payment->amount = $data['AMOUNT'];
           $payment->payment_status = $status;
           $payment->reference_no = $data['CUSTOMERREFERENCEID'];
           $payment->payment_receipt = $data['CUSTOMERREFERENCEID'];
           $payment->msnid = $data['MSISDN'];
//           $payment->trans_date = $data['TYPE'];
           $payment->opco = "UG";
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
}
