<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Update the status of a subscription (admin only).
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Active,Suspended,Cancelled',
        ]);

        $subscription = Subscription::findOrFail($id);
        $subscription->status = $validated['status'];
        $subscription->save();

        return response()->json(['subscription' => $subscription]);
    }

    /**
     * Update the payment status of a subscription (admin only).
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'payment_status' => 'required|string',
        ]);

        $subscription = Subscription::findOrFail($id);
        $subscription->payment_status = $validated['payment_status'];
        $subscription->save();

        return response()->json(['subscription' => $subscription]);
    }
}
