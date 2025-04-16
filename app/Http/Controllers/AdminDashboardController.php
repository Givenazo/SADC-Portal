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
        // Number of uploads per country daily (last 7 days)
        $uploadsPerCountry = Video::select(DB::raw('DATE(upload_date) as date'), 'country_id', DB::raw('count(*) as uploads'))
            ->where('status', 'Published')
            ->where('upload_date', '>=', Carbon::now()->subDays(7))
            ->groupBy('date', 'country_id')
            ->with('country:id,name')
            ->get();

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
        $uploadsByCountry = \App\Models\Country::withCount(['videos' => function($query) {
            $query->whereMonth('upload_date', \Carbon\Carbon::now()->month);
        }])->get()->map(function($country) {
            return [
                'name' => $country->name,
                'uploads' => $country->videos_count
            ];
        });

        return view('admin.dashboard', [
            'uploadsPerCountryChart' => $uploadsPerCountryChart,
            'downloadsPerVideoChart' => $downloadsPerVideoChart,
            'countryStatusChart' => $countryStatusChart,
            'uploadsPerCountry' => $uploadsPerCountry,
            'videosPerCategory' => $videosPerCategory,
            'commentsPerVideo' => $commentsPerVideo,
            'overdueCountries' => $overdueCountries,
            'subscriptions' => $subscriptions,
            'mostDownloadedVideo' => $mostDownloadedVideo,
            'breakingNews' => $breakingNews,
            'activeUser' => $activeUser,
            'mostCommentedVideo' => $mostCommentedVideo,
            'uploadsByCountry' => $uploadsByCountry,
            'mostActiveUploader' => $mostActiveUploader,
        ]);
// Removed stray ->first();

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
