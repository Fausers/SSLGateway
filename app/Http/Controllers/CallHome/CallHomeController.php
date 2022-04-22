<?php

namespace App\Http\Controllers\CallHome;

use App\Http\Controllers\AirLink\AirLinkController;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessDevices;
use App\Models\CallHome;
use App\Models\CallHome\AssetStatus;
use App\Models\CallHomeData;
use App\Models\DataMigrator;
use App\Models\DevelopmentAssetStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PhpMqtt\Client\Facades\MQTT;

class CallHomeController extends Controller
{

    public function disp()
    {
        ProcessDevices::dispatch("Job A",'192.168.22.1');
        return "s";
    }

    public function index(Request $request)
    {
        if ($request['ip'] == null)
            $request['ip'] = $request->ip();

        $asset = AssetStatus::firstOrNew(
            ['ip' => $request['ip']]
        );

        if ($asset->asset_id == null){
            $asset->asset_id = "Unregistered";
            $asset->power = "Unregistered";
            $asset->save();
        }

        if ($asset->power == "Disabled"){
            $response = "SimuSolar: OFF";
        }else{
            $response = "SimuSolar:  ON";
        }

        $request['status'] = $request['power'];
        $request['comm_type'] = $request['comm type'];
        $request['command'] = $response;
        if (!is_array($request['data'])){
            $request['raw_data'] = $request;
        }else{
            $request['raw_data'] = json_encode($request->all());
        }


        $call_home = CallHome::create($request->all());

        $asset_data = [
            'ip' => $request['ip']
        ];


//        (new AirLinkController)->addDevice($asset->asset_id,$request['ip']);

        if (is_array($request['data']))
        foreach ($request['data'] as $data){
            $lat = $lon = null;
            $cell_data = (new CellIdController)->locateTower($data[8]);
            $request['lat'] = 99;
            if (isset($cell_data)){
                $lon = $cell_data->lon;
                $lat = $cell_data->lat;
            }

            $call_data = [
                'call_home_id'=>$call_home->id,
                'realtime'=>$data[0],
                'total_uptime'=>$data[1],
                'session_uptime'=>$data[2],
                'vpanel'=>$data[3],
                'vout'=>$data[4],
                'isns'=>$data[5],
                'carrier'=>$data[6],
                'lac'=>$data[7],
                'ci'=>$data[8],
                'rssi'=>$data[9],
                'ber'=>$data[10],
                'lat'=> $lat,
                'lon'=> $lon,
                'latitude'=> $lat,
                'longitude'=> $lon,
                'status' => $response
            ];

            $tel = [
                'ts' => $data[0].'000',
                'values' => $call_data
            ];

            $dd[] = $tel;

            $info = CallHomeData::create($call_data);
        }


        (new AirLinkController)->addTelemetry($asset,$dd);

        if ($request['ip'] == "192.99.23.34")
            return "";

        return response($response,'200')->header('Content-Type','application/json');
    }


    public function updateStatus()
    {
        $data_migrator = DataMigrator::where('ip_address','!=',null)->get();

        foreach ($data_migrator as $data){
            $development = DevelopmentAssetStatus::where('system_id',$data->system_id)->first();
            $asset = AssetStatus::firstOrCreate(
                ['asset_id' => $data->system_id]
            );
            if (isset($development))
                $asset->power = $development->asset_status;

            $asset->ip = $data->ip_address;
            $asset->save();
        }

        return "Done";
    }

    public function updateAsset(Request $request)
    {
        $asset = AssetStatus::firstOrNew(
                ['asset_id' => $request['system_id']]
            );

        if ($asset->ip == null){
            $asset->ip = "Asset from WEBI";
            $response = "Created Has no IP";
        }else{
            $response = "Updated";
            $asset->power = $request['power'];
        }
        $asset->save();

        return response(json_encode($response),'200')->header('Content-Type','application/json');
    }
}
