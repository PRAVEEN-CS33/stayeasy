<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentsRequest;
use App\Http\Resources\PaymentsResource;
use App\Models\Payments;


class PaymentsController extends Controller
{
    public function index()
    {
        $payments = Payments::getPayments();
        $payments = PaymentsResource::collection($payments);

        return response()->json($payments);
    }
    public function create(StorePaymentsRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['payment_date'] = now();
            Payments::makePayment($validated);
            return response()->json([
                'message'=>'Payment successful'
            ], 201);
        }catch (\Exception $e) {
            return response()->json([
                'message'=>'Payment failed. Please try again later.'
            ], 500);
        }

    }
}
