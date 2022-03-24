<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
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

        $response = Http::post('https://openapiuat.airtel.africa/auth/oauth2/token', [
            'name' => 'Steve',
            'role' => 'Network Administrator',
        ]);


        return $response;
        
        $client = new GuzzleHttpClient();
        // Define array of request body.
        $request_body = array();
        $request_body = [
                "client_id"=> "*****************************",
                "client_secret"=> "*****************************",
                "grant_type"=> "client_credentials"
        ];
          
        try {
            $response = $client->request('POST','https://openapiuat.airtel.africa/auth/oauth2/token', array(
                'headers' => $headers,
                'json' => json_encode($request_body),
            )
            );
            print_r($response->getBody()->getContents());
        }
        catch (GuzzleHttpExceptionBadResponseException $e) {
            // handle exception or api errors.
            print_r($e->getMessage());
        }
        // ...
    }
}
