<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Subscription;

class SubscriptionSeeder extends Seeder
{
    public function run()
    {
        $countries = Country::all();
        foreach ($countries as $country) {
            // Only create if not exists
            Subscription::firstOrCreate([
                'country_id' => $country->id
            ], [
                'status' => 'Cancelled',
                'payment_status' => 'unpaid',
                'start_date' => 'N/A',
                'end_date' => 'N/A',
            ]);
        }
    }
}
