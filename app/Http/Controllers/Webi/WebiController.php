<?php

namespace App\Http\Controllers\Webi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebiController extends Controller
{
    public function updateSMS(Request $request)
    {
        $url = "https://api.ninox.com/v1/teams/tBEzT47PPxBqkK3n2/databases/s09bhyujje50/tables/P/records/".$request["weni_id"];

        $data = [
            'fields'=>[
                    'Delivered' => $request["sms_status"]
            ],
        ];

        return $response = Http::withHeaders([
            'Authorization' => 'Bearer 24f44360-8656-11ec-adbe-11a9b089aec7',
            'Content-Type' => 'application/json'
        ])->post($url, [
            $data
        ]);
    }
}
