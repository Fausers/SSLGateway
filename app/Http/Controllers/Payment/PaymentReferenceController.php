<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment\PaymentReference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PaymentReferenceController extends Controller
{
    public function create(Request $request)
    {
        if (is_array($request['payment_reference'])){
            foreach ($request['payment_reference'] as $payment_reference){
                PaymentReference::firstOrCreate(
                    ['payment_reference' => $payment_reference]
                );
            }
        }else{
            PaymentReference::firstOrCreate(
                    ['payment_reference' => $request['payment_reference']]
            );
        }


        return response('Payment Reference Updated','201')->header('Content-Type','application/json');

    }
}
