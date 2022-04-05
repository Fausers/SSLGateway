<?php

namespace App\Http\Controllers\CallHome;

use App\Http\Controllers\Controller;
use App\Imports\CellIdImport;
use App\Models\CallHome\CellId;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CellIdController extends Controller
{
    public function index($job)
    {
    }

    public function addCellID(Request $request)
    {
        $request['file'];

        Excel::import(new CellIdImport, $request['file']);
        return response('Got it')->header('Content-Type','application/json');
    }

    public function locateTower($data)
    {
        $tower = CellId::where('cell',$data)->first();
        return $tower;
    }
}
