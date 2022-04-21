<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment\AirtelPushDevices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Payment\AirtelData;
use App\Models\Payment\AirtelPush;
use GuzzleHttpClient;

class AirtelController extends Controller
{
    public function index(Request $request)
    {
        $myfile = fopen('log/airtel/'.date('m_d_h_i',strtotime(now())).'.json', "w") or die("Unable to open file!");
        $txt = $request->getContent();
        fwrite($myfile, $txt);
        fclose($myfile);

        $response = ['data'=>["first_name"=> "Dealer","first_name"=> "Dealer"]];
        Log::info($request->getContent());

        return response('success','200')->header('Content-Type','application/json');
    }

    public function loginToAirtel()
    {

        $airtel_credentials = AirtelData::first();

        if ($airtel_credentials == null){
            $airtel_credentials = new AirtelData;
            $airtel_credentials->client_id = "6b6d194e-5440-4834-bc4c-26531d2d45dc";
            $airtel_credentials->client_secret = "0556421c-582e-4f8a-b1b8-e1ceda6a0921";
            $airtel_credentials->grant_type = "client_credentials";
        }

        $response = Http::post('https://openapi.airtel.africa/auth/oauth2/token', [
            "client_id"=> "6b6d194e-5440-4834-bc4c-26531d2d45dc",
            "client_secret"=> "0556421c-582e-4f8a-b1b8-e1ceda6a0921",
            "grant_type"=> "client_credentials"
        ]);


        $airtel_credentials->token_type = $response['token_type'];
        $airtel_credentials->access_token = $response['access_token'];


        $airtel_credentials->save();

//        return $airtel_credentials;

    }


    public function push($request)
    {
        a:
        $url = "https://openapi.airtel.africa/merchant/v1/payments/";
        $airtel_credentials = AirtelData::first();

        $body = [
            "reference"=>$request->reference,
            "subscriber"=>[
                "country"=>"$request->country",
                "currency"=>"$request->currency",
                "msisdn"=>$request->msnid
            ],
            "transaction"=>[
                "amount"=> $request->amount,
                "country"=>$request->country,
                "currency"=>$request->currency,
                "id"=>$request->ssl_id
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '. $airtel_credentials->access_token,
            'Content-Type' => 'application/json'
        ])->post($url, [
            $body
        ]);


        if ($response->status() == 401){
            $this->loginToAirtel();
            goto a;
        }

        return $response;
    }


    public function createPush(Request $request)
    {
        $duplicate = AirtelPush::where('reference',$request['reference'])->first();
        if (isset($duplicate))
            return response('Duplicate Reference','400')->header('Content-Type','application/json');

        $push = AirtelPush::create($request->all());
        if (isset($request['devices'])){
            foreach ($request['devices'] as $dev){
                $device = new AirtelPushDevices;
                $device->push_id = $push->id;
                $device->amount = $dev['amount'];
                $device->device_id = $dev['device_id'];
                $device->refrence = $dev['refrence'];
                $device->save();
            }
        }else{
            return response('No devices Set','400')->header('Content-Type','application/json');
        }

        return $this->push($push);

        return response('success','200')->header('Content-Type','application/json');
    }
}
