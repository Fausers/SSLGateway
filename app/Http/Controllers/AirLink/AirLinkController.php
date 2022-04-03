<?php

namespace App\Http\Controllers\AirLink;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AirLinkController extends Controller
{
     public function addDevice($data = null)
    {
        return $response = Http::withHeaders([
            'X-Authorization' => 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJ3ZWJpQHNpbXVzb2xhci5jb20iLCJzY29wZXMiOlsiVEVOQU5UX0FETUlOIl0sInVzZXJJZCI6ImUwYjJjYjMwLTg5YzEtMTFlYy05NWI2LTExYjU4NjZkMGMyMiIsImZpcnN0TmFtZSI6IldlYmkiLCJlbmFibGVkIjp0cnVlLCJpc1B1YmxpYyI6ZmFsc2UsInRlbmFudElkIjoiOTFlYjE0YzAtZjQ2Yy0xMWViLTlkNDktYzczNzc4OGI1Mzk5IiwiY3VzdG9tZXJJZCI6IjEzODE0MDAwLTFkZDItMTFiMi04MDgwLTgwODA4MDgwODA4MCIsImlzcyI6InRoaW5nc2JvYXJkLmlvIiwiaWF0IjoxNjQ5MDEzNjczLCJleHAiOjE2NDkwMjI2NzN9.w0ew42XVPRzhdGU5qybMEIQnYwYG5sXDrqcKsz2e7j9hFUbPWA-EWBkmf6eV8fR__oCG69T7FCVUxcqfFTyU_A',
            'X-Second' => 'bar'
        ])->post('https://airlink.enaccess.org/api/device?accessToken=fafda', [
            'name' => 'New Pump',
            'deviceProfileId' => [
                'id' => 'fc357050-89d6-11ec-95b6-11b5866d0c22',
                'entityType' => 'DEVICE_PROFILE',
            ],
        ]);

    }

}
