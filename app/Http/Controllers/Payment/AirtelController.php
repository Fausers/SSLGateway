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
        $myfile = fopen('log/airtel/'.date('m_d_i_s',strtotime(now())).'.json', "w") or die("Unable to open file!");
        $txt = $request->getContent();
        fwrite($myfile, $txt);
        fclose($myfile);

        $response = ['data'=>["first_name"=> "Dealer","first_name"=> "Dealer"]];
        Log::info($request->getContent());
    
        return response('success','200')->header('Content-Type','application/json');
    }

    public function loginToAirtel()
    {
        $headers = array(
            'Content-Type' => 'application/json',
        );

        // Define array of request body.
        $request_body = [
            "client_id"=> "c3d4607a-2be4-4c6e-bc48-c0296181782b",
            "client_secret"=> "1695c07b-ca35-44e1-bb23-fe02b214daed",
            "grant_type"=> "client_credentials"
        ];

        $response = Http::withHeaders(['Content-Type' => 'application/json',])
            ->withBody($request_body)
            ->post('https://openapiuat.airtel.africa/auth/oauth2/token');


        $data = $response->body;

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
