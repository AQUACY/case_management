<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Payment; // Assuming you have a Payment model to track payments
use Exception;

class PaymentController extends Controller
{
    public function getPaymentsByCaseId($caseId)
    {
        try {
            $payments = Payment::getPaymentsByCaseId($caseId);

            if ($payments->isEmpty()) {
                return response()->json(['message' => 'No payments found for this case ID'], 404);
            }

            return response()->json(['payments' => $payments], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching payments', 'error' => $e->getMessage()], 500);
        }
    }


    public function processPayment(Request $request)
{
    try{
    Stripe::setApiKey(env('STRIPE_SECRET_KEY_TEST'));

    $request->validate([
        'amount' => 'required|numeric|min:1',
        'case_id' => 'required|integer|exists:cases,id',
        'note' => 'nullable|string',
    ]);

    $amount = $request->input('amount') * 100;
    $note = $request->input('note');

    try {
        // Create a PaymentIntent
        $paymentIntent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
        ]);

        // Store the payment details in the database
        $payment = Payment::create([
            'case_id' => $request->input('case_id'),
            'amount' => $amount / 100,
            'status' => 'pending',
            'payment_date' => now(),
            'note' => $note,
            'transaction_id' => $paymentIntent->id, // Use Stripe's PaymentIntent ID as transaction ID
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
            'message' => 'Payment initiated',
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
} catch (\Exception $e) {
    return response()->json(['error' => $e->getMessage()], 500);
}
}

public function paymentCallback(Request $request)
{
    try{

    Stripe::setApiKey(env('STRIPE_SECRET_KEY_TEST'));

    $paymentIntentId = $request->input('payment_intent_id');

    try {
        $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

        // Update the payment record
        $payment = Payment::where('transaction_id', $paymentIntentId)->firstOrFail();
        $payment->update([
            'status' => $paymentIntent->status,
            'payment_date' => now(), // Update to the actual payment date
        ]);

        return response()->json(['message' => 'Payment updated successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
} catch (\Exception $e) {
    return response()->json(['error' => $e->getMessage()], 500);
}
}


    public function success()
    {
        return response()->json(['message' => 'Payment successful']);
    }

    public function fail()
    {
        return response()->json(['message' => 'Payment failed']);
    }
}
