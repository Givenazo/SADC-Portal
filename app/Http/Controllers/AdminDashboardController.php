<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Country;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function saveSubscriptionPayment(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
        $subscription = \App\Models\Subscription::find($validated['id']);
        if (!$subscription) {
            return response()->json(['success' => false, 'message' => 'Subscription not found'], 404);
        }
        $subscription->start_date = $validated['start_date'];
        $subscription->end_date = $validated['end_date'];
        $subscription->status = 'Active';
        $subscription->payment_status = 'paid';
        $subscription->save();
        // Calculate updated revenue stats
        $paidCount = Subscription::where('payment_status', 'paid')->count();
        $unpaidCount = Subscription::where('payment_status', '!=', 'paid')->count();
        $totalSubs = $paidCount + $unpaidCount;
        $paidPercentage = $totalSubs > 0 ? round(($paidCount / $totalSubs) * 100, 1) : 0;
        return response()->json([
            'success' => true,
            'paidCount' => $paidCount,
            'unpaidCount' => $unpaidCount,
            'paidPercentage' => $paidPercentage
        ]);
    }

    public function index()
    {
        \Log::info('AdminDashboardController@index user', [
            'user' => auth()->user(),
            'role' => optional(auth()->user()->role)->name
        ]);

        // --- Daily uploads per country (bar chart) ---
        $last7Days = collect(range(0, 6))->map(function ($i) {
            return Carbon::now()->subDays($i)->toDateString();
        })->reverse()->values();
        $countries = Country::all(['id', 'name']);
        $uploads = Video::select(DB::raw('DATE(upload_date) as date'), 'country_id', DB::raw('count(*) as uploads'))
            ->where('status', 'Published')
            ->where('upload_date', '>=', $last7Days->first())
            ->groupBy('date', 'country_id')
            ->get();
        $uploadsPerCountryChart = $countries->map(function ($country) use ($last7Days, $uploads) {
            $data = $last7Days->map(function ($date) use ($uploads, $country) {
    $row = $uploads->where('country_id', $country->id)->where('date', $date)->first();
    return (int) ($row ? $row['uploads'] : 0);
});
            return [
                'country' => $country->name,
                'data' => $data,
            ];
        });

        // --- Download counts per video (line chart, from audit_logs) ---
        $downloads = DB::table('audit_logs')
            ->select('details', DB::raw('count(*) as count'))
            ->where('action_type', 'download')
            ->groupBy('details')
            ->get();
        $downloadsPerVideoChart = $downloads->map(function ($row) {
            // Assume details field contains video title or id
            return [
                'label' => $row->details,
                'count' => $row->count
            ];
        });

        // --- Active vs Blocked countries (pie chart) ---
        $activeCount = Country::where('status', 'Active')->count();
        $blockedCount = Country::where('status', 'Blocked')->count();
        $countryStatusChart = [
            'Active' => $activeCount,
            'Blocked' => $blockedCount,
        ];

        // (Keep old dashboard data for other widgets)
        // Number of uploads per country for today
        $uploadsToday = Video::select('country_id', DB::raw('count(*) as uploads'))
            ->where('status', 'Published')
            ->whereDate('upload_date', Carbon::today())
            ->groupBy('country_id')
            ->get()
            ->keyBy('country_id');
        $allCountries = Country::all(['id', 'name']);
        $uploadsTodayPerCountry = $allCountries->map(function ($country) use ($uploadsToday) {
            return [
                'country' => $country->name,
                'uploads' => (int) ($uploadsToday[$country->id]->uploads ?? 0),
            ];
        });
        // Overall uploads per country (for pie chart)
        $uploadsOverallPerCountry = $allCountries->map(function ($country) {
            $uploads = $country->videos()->where('status', 'Published')->count();
            return [
                'country' => $country->name,
                'uploads' => $uploads,
            ];
        });

        // Total videos per category
        $videosPerCategory = Video::select('category_id', DB::raw('count(*) as total'))
            ->groupBy('category_id')
            ->with('category:id,name')
            ->get();

        // Number of comments per video (top 10 videos)
        $commentsPerVideo = Video::withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->take(10)
            ->get(['id', 'title', 'comments_count']);

        // Countries with overdue uploads (no uploads in last 7 days)
        $countriesWithUploads = Video::where('upload_date', '>=', Carbon::now()->subDays(7))
            ->pluck('country_id')
            ->unique();
        $overdueCountries = Country::whereNotIn('id', $countriesWithUploads)->get(['id', 'name']);

        // Subscription statuses: Merge all SADC countries with their subscription (if any)
        $allCountries = Country::all(['id', 'name']);
        $subscriptionsRaw = Subscription::with('country:id,name')->get(['id', 'country_id', 'status', 'payment_status', 'start_date', 'end_date']);
        $subscriptions = $allCountries->map(function ($country) use ($subscriptionsRaw) {
            $sub = $subscriptionsRaw->firstWhere('country_id', $country->id);
            return (object) [
                'id' => $sub->id ?? ('country_' . $country->id),
                'country' => $country,
                'status' => $sub->status ?? 'No Data',
                'payment_status' => $sub->payment_status ?? 'No Data',
                'start_date' => $sub->start_date ?? 'No Data',
                'end_date' => $sub->end_date ?? 'No Data',
            ];
        });

        // Most Downloaded Video of the Month (placeholder: most recent video)
        $mostDownloadedVideo = Video::whereMonth('upload_date', Carbon::now()->month)
            ->orderByDesc('id') // Replace with ->orderByDesc('downloads') if field exists
            ->with('uploader:id,name')
            ->first();

        // Most Active Uploader (user with most uploads this month)
        $mostActiveUploader = Video::whereMonth('upload_date', Carbon::now()->month)
            ->select('uploaded_by', DB::raw('count(*) as uploads'))
            ->groupBy('uploaded_by')
            ->orderByDesc('uploads')
            ->with('uploader:id,name')
            ->first();

        // Boomer of the Month (user with the least uploads this month, but at least 1)
        $boomerOfMonth = Video::whereMonth('upload_date', Carbon::now()->month)
            ->select('uploaded_by', DB::raw('count(*) as uploads'))
            ->groupBy('uploaded_by')
            ->orderBy('uploads', 'asc')
            ->with('uploader:id,name')
            ->first();

        // Pass new chart variables to the view
        // Placeholder: Use most recent video as breaking news if no News model exists
        $breakingNews = $mostDownloadedVideo;
        // Placeholder: User with most uploads this month
        $activeUser = Video::whereMonth('upload_date', Carbon::now()->month)
            ->select('uploaded_by', DB::raw('count(*) as uploads'))
            ->groupBy('uploaded_by')
            ->orderByDesc('uploads')
            ->with('uploader:id,name')
            ->first();
        // Placeholder: Video with most comments
        $mostCommentedVideo = Video::withCount('comments')
            ->orderByDesc('comments_count')
            ->with('uploader:id,name')
            ->first();

        // Fallbacks for any missing variables
        $mostDownloadedVideo = $mostDownloadedVideo ?? null;
        $breakingNews = $breakingNews ?? null;
        $activeUser = $activeUser ?? null;
        $mostCommentedVideo = $mostCommentedVideo ?? null;
        $overdueCountries = $overdueCountries ?? collect();
        $uploadsPerCountryChart = $uploadsPerCountryChart ?? collect();
        $downloadsPerVideoChart = $downloadsPerVideoChart ?? collect();
        $countryStatusChart = $countryStatusChart ?? ['Active'=>0,'Blocked'=>0];
        $uploadsPerCountry = $uploadsPerCountry ?? collect();
        $videosPerCategory = $videosPerCategory ?? collect();
        $commentsPerVideo = $commentsPerVideo ?? collect();
        $subscriptions = $subscriptions ?? collect();

        // Most Active Uploader by Country (for table)
        // For each country, get uploads and downloads (downloads = total downloads of videos uploaded by members from that country this month)
        $uploadsByCountry = \App\Models\Country::with(['videos' => function($query) {
            $query->whereMonth('upload_date', \Carbon\Carbon::now()->month);
        }])->get()->map(function($country) {
            $videoIds = $country->videos->pluck('id');
            $downloads = 0;
            if ($videoIds->count()) {
                $downloads = \DB::table('audit_logs')
                    ->where('action_type', 'download')
                    ->whereIn('details', $videoIds)
                    ->count();
            }
            return [
                'name' => $country->name,
                'uploads' => $country->videos->count(),
                'downloads' => $downloads
            ];
        })->sortByDesc('uploads')->values();

        // Revenue Streams: calculate percentage of paid subscriptions
        $paidCount = $subscriptions->where('payment_status', 'paid')->count();
        $unpaidCount = $subscriptions->where('payment_status', '!=', 'paid')->count();
        $totalSubs = $paidCount + $unpaidCount;
        $paidPercentage = $totalSubs > 0 ? round(($paidCount / $totalSubs) * 100, 1) : 0;
        // --- All data prep above ---
        return view('admin.dashboard', compact(
            'uploadsPerCountryChart',
            'downloadsPerVideoChart',
            'countryStatusChart',
            'uploadsPerCountry',
            'videosPerCategory',
            'commentsPerVideo',
            'overdueCountries',
            'subscriptions',
            'mostDownloadedVideo',
            'breakingNews',
            'activeUser',
            'mostCommentedVideo',
            'uploadsTodayPerCountry',
            'uploadsByCountry',
            'boomerOfMonth',
            'paidCount',
            'unpaidCount',
            'paidPercentage',
            'uploadsOverallPerCountry',
        ));

        // Most Commented Video
        $mostCommentedVideo = Video::withCount('comments')
            ->orderByDesc('comments_count')
            ->with('uploader:id,name')
            ->first();

        // Active User (placeholder: most uploads this month)
        $activeUser = Video::select('uploaded_by', DB::raw('count(*) as uploads'))
            ->whereMonth('upload_date', Carbon::now()->month)
            ->groupBy('uploaded_by')
            ->orderByDesc('uploads')
            ->with('uploader:id,name')
            ->first();

        // Breaking News Story (placeholder: latest published video)
        $breakingNews = Video::where('status', 'Published')
            ->orderByDesc('upload_date')
            ->with('uploader:id,name')
            ->first();

        // Most active uploader (legacy)
        $mostActiveUploader = Video::select('uploaded_by', DB::raw('count(*) as uploads'))
            ->groupBy('uploaded_by')
            ->orderByDesc('uploads')
            ->with('uploader:id,name')
            ->first();

        // Most Active Country by Uploads (for scrollable widget)
        $uploadsByCountry = Country::withCount(['videos' => function($query) {
            $query->where('status', 'Published');
        }])->get()->map(function($country) {
            return [
                'name' => $country->name,
                'uploads' => $country->videos_count,
            ];
        })->sortByDesc('uploads')->values();

        return view('admin.dashboard', compact(
            'uploadsPerCountry',
            'uploadsTodayPerCountry',
            'videosPerCategory',
            'commentsPerVideo',
            'overdueCountries',
            'subscriptions',
            'mostActiveUploader',
            'mostDownloadedVideo',
            'mostCommentedVideo',
            'activeUser',
            'breakingNews',
            'uploadsByCountry',
        ));
    }
}
