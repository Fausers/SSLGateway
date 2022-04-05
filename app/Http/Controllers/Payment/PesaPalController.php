<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;


class PesaPalController extends Controller
{
    public function index(Request $request)
    {

        $payload = simplexml_load_string($request->getContent());

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, route('pesapal_save'));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Connection: Keep-Alive'
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_exec($ch);

        $data = json_encode($payload);
        $jdata =  json_decode($data,true);


        //        Check Existence Of account  Redis
        $values = Redis::sismember('pay_ref', $jdata['CUSTOMERREFERENCEID']);

        $RESULT = null;
        $serviceStatus = null;
        $code = null;

        if (json_encode($values) == 0){
            $serviceStatus = 'Invalid Customer Reference Number';
            $code = '010';
            $RESULT = 'TF';
        }else{
            $serviceStatus = 'SUCCESSFUL';
            $code = '000';
            $RESULT = 'TS';
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

         $response = $this->createResponse($initial_response);

        return response($response,'201')->header('Content-Type','application/xml');
    }

    public function save(Request $request)
    {
        $myfile = fopen('log/pesapal/'.date('m_d_i_s',strtotime(now())).'.json', "w") or die("Unable to open file!");
        $txt = $request->getContent();
        fwrite($myfile, $txt);
        fclose($myfile);

        echo "Done";
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
        $root->appendChild($dom->createElement('TXNID', $responseData['TXNID']));
        $root->appendChild($dom->createElement('RESULT', $responseData['RESULT']));
        $root->appendChild($dom->createElement('ERRORCODE', $responseData['ERRORCODE']));
        $root->appendChild($dom->createElement('ERRORDESC', $responseData['ERRORDESC']));
        $root->appendChild($dom->createElement('MSISDN', $responseData['MSISDN']));
        $root->appendChild($dom->createElement('AGTXNID', $responseData['AGTXNID']));

        $output=$dom->saveXML();
        return $output;
    }
}
