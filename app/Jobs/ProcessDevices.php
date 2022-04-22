<?php

namespace App\Jobs;

use App\Http\Controllers\AirLink\AirLinkController;
use App\Models\AirLink\AirLinkData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessDevices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var AirLinkData
     */
    protected $airLinkData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AirLinkData $airLinkData,AirLinkController $airLinkController)
    {
        $this->airLinkData = $airLinkData;
        $this->airLinkData = $airLinkController;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle($asset_id,$ip)
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
    }
}
