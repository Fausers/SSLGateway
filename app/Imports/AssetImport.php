<?php

namespace App\Imports;

use App\Models\CallHome\AssetStatus;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class AssetImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
                AssetStatus::insertOrIgnore([
                'ip' => $row[2],
                'asset_id' => $row[3],
                'box_id' => $row[0],
                'power' => $row[5],
                'country' => $row[6],
                'access_token' => $row[7],
            ]);
        }
    }
}
