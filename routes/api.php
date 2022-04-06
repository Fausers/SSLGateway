<?php

use App\Http\Controllers\AirLink\AirLinkController;
use App\Http\Controllers\CallHome\CallHomeController;
use App\Http\Controllers\CallHome\CellIdController;
use App\Http\Controllers\Payment\PaymentReferenceController;
use App\Http\Controllers\Payment\PesaPalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payment\AirtelController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function(){
    Route::prefix('airtel')->group(function(){
        Route::post('/', [AirtelController::class, 'index'])->name('index');
        Route::get('/airtel_login', [AirtelController::class, 'loginToAirtel'])->name('airtel_login');

        Route::post('/requestpush', [AirtelController::class, 'createPush'])->name('request_push');
    });

    Route::prefix('pesapal')->group(function(){
        Route::post('/', [PesaPalController::class, 'index'])->name('index');
    });

    Route::prefix('callhome')->group(function(){
       Route::post('/', [CallHomeController::class, 'index'])->name('index');
       Route::get('/migrate', [CallHomeController::class, 'updateStatus'])->name('migrate');
       Route::post('/update_asset', [CallHomeController::class, 'updateAsset'])->name('update_asset');
    });

    Route::prefix('cell_id')->group(function(){
       Route::post('/', [CellIdController::class, 'index'])->name('index');
       Route::post('/update', [CellIdController::class, 'addCellID'])->name('update');
       Route::post('/locate', [CellIdController::class, 'locateTower'])->name('locate');
    });

    Route::prefix('air_link')->group(function(){
       Route::post('/', [AirLinkController::class, 'index'])->name('index');
       Route::post('/mqtt', [AirLinkController::class, 'mqtt'])->name('mqtt');
       Route::get('/server_login', [AirLinkController::class, 'login'])->name('server_login');
       Route::post('/add_device', [AirLinkController::class, 'addDevice'])->name('add_device');
    });


    Route::prefix('payment_reference')->group(function(){
       Route::post('/', [PaymentReferenceController::class, 'index'])->name('index');
       Route::post('/create', [PaymentReferenceController::class, 'create'])->name('create');
    });


});


