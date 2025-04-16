<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Subscription;
use App\Models\Video;
use Carbon\Carbon;

class AdminCountryController extends Controller
{
    // Block a country and broadcast event
    public function blockCountry($id)
    {
        $country = \App\Models\Country::findOrFail($id);
        $country->status = 'Blocked';
        $country->save();
        event(new \App\Events\CountryBlocked($country));
        return redirect()->back()->with('status', 'Country blocked and notification sent!');
    }
    public function index()
    {
        $countries = Country::all()->map(function ($country) {
            $subscription = Subscription::where('country_id', $country->id)->first();
            $videosThisMonth = Video::where('country_id', $country->id)
                ->whereMonth('upload_date', Carbon::now()->month)
                ->count();
            return (object) [
                'name' => $country->name,
                'status' => $country->status ?? 'Active',
                'subscription_status' => $subscription->status ?? 'No Data',
                'videos_this_month' => $videosThisMonth,
            ];
        });
        return view('admin.countries', compact('countries'));
    }
}
