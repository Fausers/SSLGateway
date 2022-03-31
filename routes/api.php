<?php

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

Route::middleware('auth:sanctum')->prefix('airtel')->group(function(){
    Route::post('/', [AirtelController::class, 'index'])->name('index');
    Route::get('/login', [AirtelController::class, 'loginToAirtel'])->name('login');

    Route::post('/requestpush', [AirtelController::class, 'createPush'])->name('request_push');
});
