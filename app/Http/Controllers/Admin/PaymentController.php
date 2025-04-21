<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Country;
use App\Events\PaymentStatusUpdated;

class PaymentController extends Controller
{
    public function details(Request $request)
    {
        $countryId = $request->input('country_id');
        $year = $request->input('year');
        $payments = Subscription::with('country')
            ->where('country_id', $countryId)
            ->whereYear('start_date', $year)
            ->orderByDesc('start_date')
            ->get();
        $country = $payments->first() ? $payments->first()->country : \App\Models\Country::find($countryId);
        return view('admin.payments.partials.details_modal', compact('payments', 'country', 'year'))->render();
    }
    public function filter(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $query = Subscription::with('country');
        if ($year) {
            $query->whereYear('start_date', $year);
        }
        if ($month) {
            $query->whereMonth('start_date', $month);
        }
        $subscriptions = $query->orderByDesc('created_at')->get();
        return view('admin.payments.partials.tbody', compact('subscriptions'))->render();
    }
    public function index()
    {
        // Get latest subscription per country
        $latestSubs = Subscription::selectRaw('*, ROW_NUMBER() OVER (PARTITION BY country_id ORDER BY start_date DESC, created_at DESC) as rn')
            ->with('country')
            ->get()
            ->where('rn', 1);
        $subscriptions = $latestSubs;
        $pastSubscriptions = Subscription::with('country')
            ->where('end_date', '<', now())
            ->orderByDesc('end_date')
            ->get();
        $countries = Country::all();
        return view('admin.payments', compact('subscriptions', 'pastSubscriptions', 'countries'));
    }

    public function capture(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'amount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'payment_status' => 'required|in:paid,unpaid',
            'notes' => 'nullable|string'
        ]);

        $subscription = Subscription::create($request->all());

        // Broadcast the payment update
        event(new PaymentStatusUpdated($subscription));

        return redirect()->back()->with('success', 'Payment captured successfully');
    }

    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'payment_status' => 'required|in:paid,unpaid',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $subscription->update($request->all());

        // Broadcast the payment update
        event(new PaymentStatusUpdated($subscription));

        return response()->json(['message' => 'Payment updated successfully']);
    }
}
