<?php

namespace App\Http\Controllers\AirLink;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessDevices;
use App\Models\AirLink\AirLinkData;
use App\Models\Payment\AirtelData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PhpMqtt\Client\Facades\MQTT;

class AirLinkController extends Controller
{


    public function mqtt()
    {
        return MQTT::publish('v1/devices/me/telemetry', '"temperature":10');
    }

    public function login()
    {
        $air_link = AirLinkData::first();
        if ($air_link == null){
            $air_link = new AirLinkData;
            $air_link->app_url = "http://airlink.enaccess.org/api/auth/login";
            $air_link->username = "callhome@simusolar.com";
            $air_link->password = "Mwakampya_2022";
            $air_link->save();
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($air_link->app_url, [
            'username' => $air_link->username,
            'password' => $air_link->password,
        ]);

        $air_link->token = $response['token'];
        $air_link->refresh_token = $response['refreshToken'];

        $air_link->save();
    }

    public function addDevice($asset_id,$ip)
    {
        a:
        $air_link = AirLinkData::first();
        $response = Http::withHeaders([
            'X-Authorization' => 'Bearer '.$air_link->token,
            'Content-Type' => 'application/json'
        ])->post('http://airlink.enaccess.org/api/device?accessToken='.$ip.'&entityGroupId=2616a100-b3c1-11ec-bf4c-4171b9f48dd2', [
            'name' => $asset_id,
            'deviceProfileId' => [
                'id' => '91f46390-f46c-11eb-9d49-c737788b5399',
                'entityType' => 'DEVICE_PROFILE',
            ],
        ]);


        if ($response->status() == 401){
            $this->login();
            goto a;
        }
        return $response;

    }

    public function addTelemetry($asset,$data)
    {
        $count = 0;
        a:
        $count++;
        $air_link = AirLinkData::first();
        $response = Http::withHeaders([
            'X-Authorization' => 'Bearer '.$air_link->token,
            'Content-Type' => 'application/json'
        ])->post('http://airlink.enaccess.org/api/v1/'.$asset->ip.'/telemetry', $data);

        if ($response->status() == 401 && $count < 2){
            $this->login();
            goto a;
        }

//        if ($response->status() == 401 && $count < 3){
//            $this->addDevice($asset->asset_id,$asset->ip);
//            goto a;
//        }

    }

}
