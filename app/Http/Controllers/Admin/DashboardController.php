<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getRevenueStats()
    {
        // Only use the latest payment per country
        $latestSubs = Subscription::selectRaw('*, ROW_NUMBER() OVER (PARTITION BY country_id ORDER BY start_date DESC, created_at DESC) as rn')
            ->with('country')
            ->get()
            ->where('rn', 1);
        $paidCount = $latestSubs->where('payment_status', 'paid')->count();
        $totalCount = $latestSubs->count();
        $unpaidCount = $totalCount - $paidCount;
        $paidPercentage = $totalCount > 0 ? round(($paidCount / $totalCount) * 100) : 0;
        return response()->json([
            'paidCount' => $paidCount,
            'unpaidCount' => $unpaidCount,
            'paidPercentage' => $paidPercentage
        ]);
    }
} 