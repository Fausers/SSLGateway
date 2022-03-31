<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
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


         $response = Http::post('https://openapi.airtel.africa/auth/oauth2/token', [
            "client_id"=> "6b6d194e-5440-4834-bc4c-26531d2d45dc",
            "client_secret"=> "0556421c-582e-4f8a-b1b8-e1ceda6a0921",
            "grant_type"=> "client_credentials"
        ]);



        return $data = $response->token_type;

        $airtel_credentials = AirtelData::first();

        $airtel_credentials->access_toekn = $data->access_token;

        $airtel_credentials->save();

    }


    public function push(Request $request)
    {
        $body = ["reference"=>$request->reference,
                "subscriber"=>[
                    "country"=>"$request->opco",
                    "currency"=>"$request->opco",
                    "msisdn"=>$request->msnid
                ],
                "transaction"=>[
                    "amount"=> $request->amount,
                    "country"=>$request->opco,
                    "currency"=>$request->opco,
                    "id"=>$request->trans_id
                ]
        ];
    }


    public function createPush(Request $request)
    {
        return 'a';
        return $push = AirtelPush::create($request->all());
        return response('success','200')->header('Content-Type','application/json');
    }
}
