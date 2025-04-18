@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/sadc-custom.css">
<div class="container py-4">
    <h1 class="mb-2 fw-bold sadc-header-darkblue d-flex align-items-center justify-content-between">
        <span><i class="bi bi-bar-chart-fill"></i> Admin Dashboard</span>
        <div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg me-2">
                <i class="bi bi-people"></i> User Management
            </a>
            <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-success btn-lg me-2 sadc-header-darkblue">
                <i class="bi bi-collection-play sadc-header-darkblue"></i> Uploaded Videos
            </a>
            <a href="{{ route('news.index') }}" class="btn btn-outline-primary btn-lg sadc-header-darkblue">
                <i class="bi bi-newspaper sadc-header-darkblue"></i> Manage News
            </a>
        </div>
    </h1>
    <div class="alert alert-info mb-4">
        <strong>Welcome to your analytics dashboard!</strong> Here you can monitor uploads, user activity, trending videos, and breaking news stories across the SADC portal. Use the charts and widgets below for actionable insights and to keep your finger on the pulse of the platform.
    </div>
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card shadow h-100 border-0 bg-light">
                <div class="card-body text-center">
                    <i class="bi bi-star-fill fs-1 text-warning"></i>
                    <h6 class="mt-2">Most Downloaded Video<br><small>(This Month)</small></h6>
                    @if($mostDownloadedVideo)
                        <div class="mb-2">
                            @if($mostDownloadedVideo->preview_thumbnail)
    <img src="{{ $mostDownloadedVideo->preview_thumbnail }}" alt="Thumbnail" class="rounded mb-1" style="width:80px;height:45px;object-fit:cover;">
@endif
                        </div>
                        <strong>{{ $mostDownloadedVideo->title }}</strong><br>
                        <small>By {{ $mostDownloadedVideo->uploader->name ?? 'Unknown' }}</small>
                    @else
                        <span class="text-muted">No data</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card shadow h-100 border-0 bg-light">
                <div class="card-body text-center">
                    <i class="bi bi-fire fs-1 text-danger"></i>
                    <h6 class="mt-2">Breaking News Story</h6>
                    @if($breakingNews)
                        <div class="mb-2">
                            @if($breakingNews->preview_thumbnail)
    <img src="{{ $breakingNews->preview_thumbnail }}" alt="Thumbnail" class="rounded mb-1" style="width:80px;height:45px;object-fit:cover;">
@endif
                        </div>
                        <strong>{{ $breakingNews->title }}</strong><br>
                        <small>By {{ $breakingNews->uploader->name ?? 'Unknown' }}</small>
                    @else
                        <span class="text-muted">No breaking news</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card shadow h-100 border-0 bg-light">
                <div class="card-body text-center">
                    <i class="bi bi-person-check fs-1 text-primary"></i>
                    <h6 class="mt-2">Active User<br><small>(This Month)</small></h6>
                    @if($activeUser)
                        <strong>{{ $activeUser->uploader->name ?? 'Unknown' }}</strong><br>
                        <small>Uploads: {{ $activeUser->uploads }}</small>
                    @else
                        <span class="text-muted">No data</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card shadow h-100 border-0 bg-light">
                <div class="card-body text-center">
                    <i class="bi bi-chat-square-text fs-1 text-info"></i>
                    <h6 class="mt-2">Most Commented Video</h6>
                    @if($mostCommentedVideo)
                        <strong>{{ $mostCommentedVideo->title }}</strong><br>
                        <small>Comments: {{ $mostCommentedVideo->comments_count }}</small>
                    @else
                        <span class="text-muted">No data</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card shadow h-100 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-upload fs-1 text-success"></i>
                    <h6 class="mt-2">Uploads Per Country<br><small>(7 Days)</small></h6>
                    <canvas id="uploadsPerCountryChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card shadow h-100 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-collection-play fs-1 text-info"></i>
                    <h6 class="mt-2">Videos Per Category</h6>
                    <canvas id="videosPerCategoryChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card shadow h-100 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-chat-dots fs-1 text-warning"></i>
                    <h6 class="mt-2">Comments Per Video</h6>
                    <canvas id="commentsPerVideoChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card shadow h-100 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-people-x fs-1 text-danger"></i>
                    <h6 class="mt-2">Overdue Countries</h6>
                    <ul class="list-unstyled mb-0">
                        @forelse($overdueCountries as $country)
                            <li>{{ $country->name }}</li>
                        @empty
                            <li class="text-muted">None</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-card-list"></i> Subscription Statuses
                </div>
                <div class="card-body p-0">
                    <div style="max-height: 420px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr><th>Country</th><th>Status</th><th>Payment</th><th>Start</th><th>End</th></tr>
                            </thead>
                            <tbody>
                            @foreach($subscriptions as $sub)
                                <tr>
                                    <td>{{ $sub->country->name }}</td>
                                    <td><span class="badge bg-{{ $sub->status == 'Active' ? 'success' : ($sub->status == 'Suspended' ? 'warning' : 'danger') }}">{{ $sub->status }}</span></td>
                                    <td>{{ $sub->payment_status }}</td>
                                    <td>{{ $sub->start_date }}</td>
                                    <td>{{ $sub->end_date }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-trophy"></i> Most Active Uploader by Country
                </div>
                <div class="card-body p-0">
                    <div style="max-height: 420px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr><th>Country</th><th>Uploads</th></tr>
                            </thead>
                            <tbody>
                            @foreach($uploadsByCountry as $row)
                                <tr>
                                    <td>{{ $row['name'] }}</td>
                                    <td>{{ $row['uploads'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<noscript><style>.bi{display:none !important;}</style><span style='color:red'>Icons require JavaScript and CDN access.</span></noscript>
<script>window.addEventListener('DOMContentLoaded',function(){var test=document.createElement('i');test.className='bi bi-star-fill';document.body.appendChild(test);var comp=window.getComputedStyle(test,':before').content;if(!comp||comp==='none'){var link=document.createElement('link');link.rel='stylesheet';link.href='/css/bootstrap-icons.min.css';document.head.appendChild(link);}test.remove();});</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row mb-4" style="padding-left:1cm;padding-right:1cm;">
    <div class="col-lg-6 mb-4">
        <div class="card shadow border-0">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-bar-chart"></i> Daily Uploads Per Country</span>
    <span class="fw-normal" style="font-size:1rem;">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</span>
</div>
            <div class="card-body p-0 equal-widget-height" style="min-height:340px;height:340px;overflow-y:auto;">
                <ul class="list-group list-group-flush">
                    @foreach($uploadsPerCountryChart as $row)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $row['country'] }}</span>
                            <span class="badge bg-success rounded-pill" style="font-size:1.1rem;">{{ array_sum((array) $row['data']) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card shadow border-0">
            <div class="card-header bg-info text-white"><i class="bi bi-graph-up"></i> Download Counts Per Video</div>
            <div class="card-body equal-widget-height" style="min-height:340px;height:340px;overflow-y:auto;">
    <canvas id="downloadsPerVideoChart"></canvas>
</div>
        </div>
    </div>

</div>

<script>
// --- Daily Uploads Per Country (Bar Chart) ---
const uploadsPerCountryData = @json($uploadsPerCountryChart);
const uploadsLabels = @json(collect(range(0, 6))->map(fn($i) => \Carbon\Carbon::now()->subDays($i)->format('M d'))->reverse()->values());
const uploadsDatasets = uploadsPerCountryData.map(row => ({
    label: row.country,
    data: row.data,
    backgroundColor: `rgba(${Math.floor(Math.random()*200)},${Math.floor(Math.random()*200)},${Math.floor(Math.random()*200)},0.6)`
}));
new Chart(document.getElementById('uploadsPerCountryChart'), {
    type: 'bar',
    data: {
        labels: uploadsLabels,
        datasets: uploadsDatasets
    },
    options: {responsive:true, plugins:{legend:{position:'top'}}}
});

// --- Download Counts Per Video (Line Chart) ---
const downloadsData = @json($downloadsPerVideoChart);
new Chart(document.getElementById('downloadsPerVideoChart'), {
    type: 'line',
    data: {
        labels: downloadsData.map(row => row.label),
        datasets: [{
            label: 'Downloads',
            data: downloadsData.map(row => row.count),
            fill: false,
            borderColor: 'rgba(54,162,235,1)',
            backgroundColor: 'rgba(54,162,235,0.2)',
            tension: 0.3
        }]
    },
    options: {responsive:true, plugins:{legend:{display:false}}}
});


const uploadsPerCountry = @json($uploadsPerCountry);
const countries = [...new Set(uploadsPerCountry.map(u => u.country.name))];
const dates = [...new Set(uploadsPerCountry.map(u => u.date))];
const uploadsData = countries.map(country => {
    return {
        label: country,
        data: dates.map(date => {
            const found = uploadsPerCountry.find(u => u.country.name === country && u.date === date);
            return found ? found.uploads : 0;
        }),
        fill: false,
        borderColor: '#' + Math.floor(Math.random()*16777215).toString(16),
    };
});
new Chart(document.getElementById('uploadsPerCountryChart'), {
    type: 'line',
    data: {
        labels: dates,
        datasets: uploadsData,
    },
    options: { plugins: { legend: { display: false } } }
});

// Videos Per Category Chart
const videosPerCategory = @json($videosPerCategory);
new Chart(document.getElementById('videosPerCategoryChart'), {
    type: 'bar',
    data: {
        labels: videosPerCategory.map(c => c.category.name),
        datasets: [{
            label: 'Videos',
            data: videosPerCategory.map(c => c.total),
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
        }],
    },
    options: { plugins: { legend: { display: false } } }
});

// Comments Per Video Chart
const commentsPerVideo = @json($commentsPerVideo);
new Chart(document.getElementById('commentsPerVideoChart'), {
    type: 'bar',
    data: {
        labels: commentsPerVideo.map(v => v.title),
        datasets: [{
            label: 'Comments',
            data: commentsPerVideo.map(v => v.comments_count),
            backgroundColor: 'rgba(255, 193, 7, 0.7)',
        }],
    },
    options: { plugins: { legend: { display: false } } }
});
</script>
@endsection
