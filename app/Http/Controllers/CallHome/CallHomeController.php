<?php

namespace App\Http\Controllers\CallHome;

use App\Http\Controllers\Controller;
use App\Models\CallHome;
use App\Models\CallHome\AssetStatus;
use App\Models\CallHomeData;
use App\Models\DataMigrator;
use App\Models\DevelopmentAssetStatus;
use Illuminate\Http\Request;

class CallHomeController extends Controller
{
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
            $response = "SimuSolar:  OFF";
        }else{
            $response = "SimuSolar:  ON";
        }

        $request['status'] = $request['power'];
        $request['comm_type'] = $request['comm type'];
        $request['command'] = $response;
        $request['raw_data'] = json_encode($request->all());

//        return $request->all();
        $call_home = CallHome::create($request->all());

        foreach ($request['data'] as $data){
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
            ];

            $info = CallHomeData::create($call_data);
//            return $data;
        }



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
        return $asset->power = $request['system_id'];

        if ($asset->ip == null){
            $asset->ip = "Asset from WEBI";
            $response = "Created Has no IP";
        }else{
            $response = "Updated";
        }
        $asset->save();

        return response($response,'200')->header('Content-Type','application/json');

    }
}
