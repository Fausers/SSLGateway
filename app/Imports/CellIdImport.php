<?php
namespace App\Imports;

use App\Models\CallHome\CellId;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CellIdImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
                CellId::insertOrIgnore([
                'radio' => $row[0],
                'mcc' => $row[1],
                'net' => $row[2],
                'area' => $row[3],
                'cell' => $row[4],
                'unit' => $row[5],
                'lon' => $row[6],
                'lat' => $row[7],
                'range' => $row[8],
                'samples' => $row[9],
                'changeable' => $row[10],
                'created' => $row[11],
                'updated' => $row[12],
//                'average_signal' => $row[13],
            ]);
        }
    }
}
