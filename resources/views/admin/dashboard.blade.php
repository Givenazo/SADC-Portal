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
        <strong>Welcome to your analytics dashboard!</strong> Here, you can seamlessly monitor uploads, user activity, trending videos, and breaking news stories across the SADC portal. Use the interactive charts and dynamic widgets below to gain actionable insights, track performance, and keep your finger firmly on the pulse of everything happening in SADC.
    </div>
    <div class="row g-4 mb-4">
        <!-- Total Uploads Today Card -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow h-100 border-0" style="background:linear-gradient(135deg,#f85757 0%,#ff9800 100%);color:#fff;">
                <div class="card-body text-center">
                    <i class="bi bi-newspaper fs-1" style="filter:drop-shadow(0 2px 6px rgba(0,0,0,0.15));"></i>
                    <h6 class="mt-2 fw-bold" style="font-size:1.25rem;letter-spacing:0.01em;">Total Uploads<br><small style="font-weight:400;font-size:1rem;">(Today)</small></h6>
                    <span class="display-3 fw-bold" style="font-size:3.2rem;text-shadow:0 2px 10px rgba(0,0,0,0.08);">{{ isset($uploadsTodayPerCountry) ? collect($uploadsTodayPerCountry)->sum('uploads') : 0 }}</span>
                </div>
            </div>
        </div>
        <!-- Most Downloaded Video Card -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow h-100 border-0 bg-light">
                <div class="card-body text-center">
                    <i class="bi bi-star-fill fs-1 text-warning"></i>
                    <h6 class="mt-2">Most Downloaded Video<br><small>(This Month)</small></h6>
                    @if($mostDownloadedVideo)
                        <div class="mb-2">
                            <img src="{{ $mostDownloadedVideo->preview_thumbnail ? asset('storage/' . $mostDownloadedVideo->preview_thumbnail) : asset('images/video-placeholder.png') }}"
     onerror="this.onerror=null;this.src='{{ asset('images/video-placeholder.png') }}';"
     alt="Thumbnail" class="rounded mb-1 d-block mx-auto" style="width:80px;height:45px;object-fit:cover;">
                        </div>
                        <strong>{{ $mostDownloadedVideo->title }}</strong><br>
                        <small>By {{ $mostDownloadedVideo->uploader->name ?? 'Unknown' }}</small>
                    @else
                        @if(isset($paidPercentage) && ($paidCount + $unpaidCount) > 0)
    <span class="fw-bold" style="font-size:1.25rem;">{{ $paidPercentage }}% paid</span><br>
    <small style="font-size:1.1rem;">({{ $paidCount }}/{{ $paidCount + $unpaidCount }} countries)</small>
@else
    <span class="text-muted">No data</span>
@endif
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
                            <img src="{{ $breakingNews->preview_thumbnail ? asset('storage/' . $breakingNews->preview_thumbnail) : asset('images/video-placeholder.png') }}"
     onerror="this.onerror=null;this.src='{{ asset('images/video-placeholder.png') }}';"
     alt="Thumbnail" class="rounded mb-1 d-block mx-auto" style="width:80px;height:45px;object-fit:cover;">
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
                        @if(isset($paidPercentage) && ($paidCount + $unpaidCount) > 0)
    <span class="fw-bold" style="font-size:1.25rem;">{{ $paidPercentage }}% paid</span><br>
    <small style="font-size:1.1rem;">({{ $paidCount }}/{{ $paidCount + $unpaidCount }} countries)</small>
@else
    <span class="text-muted">No data</span>
@endif
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
                        @if(isset($paidPercentage) && ($paidCount + $unpaidCount) > 0)
    <span class="fw-bold" style="font-size:1.25rem;">{{ $paidPercentage }}% paid</span><br>
    <small style="font-size:1.1rem;">({{ $paidCount }}/{{ $paidCount + $unpaidCount }} countries)</small>
@else
    <span class="text-muted">No data</span>
@endif
                    @endif
                </div>
            </div>
        </div>
        <!-- Boomer of the Month Widget -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow h-100 border-0 bg-light">
                <div class="card-body text-center">
                    <i class="bi bi-emoji-frown fs-1 text-secondary"></i>
                    <h6 class="mt-2">Boomer of the Month<br><small>(Least Uploads)</small></h6>
                    @if($boomerOfMonth && $boomerOfMonth->uploader)
                        <strong>{{ $boomerOfMonth->uploader->name }}</strong><br>
                        <small>Uploads: {{ $boomerOfMonth->uploads }}</small>
                    @else
                        @if(isset($paidPercentage) && ($paidCount + $unpaidCount) > 0)
    <span class="fw-bold" style="font-size:1.25rem;">{{ $paidPercentage }}% paid</span><br>
    <small style="font-size:1.1rem;">({{ $paidCount }}/{{ $paidCount + $unpaidCount }} countries)</small>
@else
    <span class="text-muted">No data</span>
@endif
                    @endif
                </div>
            </div>
        </div>
        <!-- Best Category Widget -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow h-100 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-trophy fs-1" style="filter:drop-shadow(0 2px 6px rgba(0,0,0,0.13));color:#b8860b;"></i>
                    <h6 class="mt-2 fw-bold" style="font-size:1.22rem;letter-spacing:0.01em;">Best Category</h6>
                    @php
                        $bestCategory = null;
                        if(isset($videosPerCategory) && count($videosPerCategory)) {
                            $bestCategory = collect($videosPerCategory)->sortByDesc('total')->first();
                        }
                    @endphp
                    @if($bestCategory && isset($bestCategory['category']['name']))
                        <span class="fw-bold" style="font-size:1.25rem;">{{ $bestCategory['category']['name'] }}</span><br>
                        <small style="font-size:1.1rem;">Uploads: <span class="fw-semibold">{{ $bestCategory['total'] }}</span></small>
                    @else
                        @if(isset($paidPercentage) && ($paidCount + $unpaidCount) > 0)
    <span class="fw-bold" style="font-size:1.25rem;">{{ $paidPercentage }}% paid</span><br>
    <small style="font-size:1.1rem;">({{ $paidCount }}/{{ $paidCount + $unpaidCount }} countries)</small>
@else
    <span class="text-muted">No data</span>
@endif
                    @endif
                </div>
            </div>
        </div>
        <!-- Revenue Streams Widget -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow h-100 border-0 bg-light d-flex flex-column justify-content-between">
                <div class="card-body text-center">
                    <i class="bi bi-cash-stack fs-1 text-success"></i>
                    <h6 class="mt-2">Revenue Streams</h6>
                    @if(isset($paidPercentage) && ($paidCount + $unpaidCount) > 0)
    <span class="fw-bold" style="font-size:1.25rem;">{{ $paidPercentage }}% paid</span><br>
    <small style="font-size:1.1rem;">({{ $paidCount }}/{{ $paidCount + $unpaidCount }} countries)</small>
@else
    <span class="text-muted">No data</span>
@endif
                </div>
                <div class="card-footer bg-transparent border-0 text-center pt-0 pb-3">
                    <a href="{{ route('admin.payments') }}" class="btn btn-outline-primary btn-sm">More Data</a>
                </div>
            </div>
        </div>
    </div>


    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-card-list"></i> Payment Statuses</span>
    <span class="fw-normal" style="font-size:1rem;">
        {{ \Carbon\Carbon::now()->startOfMonth()->format('d') }} -
        {{ \Carbon\Carbon::now()->endOfMonth()->format('d F Y') }}
    </span>
                </div>
                <div class="card-body p-0">
                    <div style="max-height: 420px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr><th>Country</th><th>Status</th><th class="text-center">Payment Status</th><th class="text-center">Payment</th><th>Start</th><th>End</th></tr>
                            </thead>
                            <tbody>
                            @foreach($subscriptions as $sub)
                                <tr data-sub-id="{{ $sub->id }}">
    <td>{{ $sub->country->name }}</td>
    <td class="status-cell"><span class="badge bg-{{ $sub->status == 'Active' ? 'success' : ($sub->status == 'Suspended' ? 'warning' : 'danger') }}">{{ $sub->status }}</span></td>
    <td class="payment-status-cell text-center">
        <span class="badge bg-{{ $sub->payment_status == 'paid' ? 'success' : 'danger' }}">{{ ucfirst($sub->payment_status) }}</span>
    </td>
    <td class="text-center">
        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#payModal{{ $sub->id }}"
@if($sub->payment_status == 'paid' && \Carbon\Carbon::parse($sub->end_date)->isFuture()) disabled @endif>Pay</button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="payModal{{ $sub->id }}" tabindex="-1" aria-labelledby="payModalLabel{{ $sub->id }}" aria-hidden="true">
                                          <div class="modal-dialog">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="payModalLabel{{ $sub->id }}">Record Payment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                              </div>
                                              <div class="modal-body">
                                                <form>
                                                  <div class="mb-3">
                                                    <label for="paymentType{{ $sub->id }}" class="form-label">Payment Period</label>
                                                    <select class="form-select" id="paymentType{{ $sub->id }}">
  <option value="1 Month">1 Month</option>
  <option value="3 Months">3 Months</option>
  <option value="6 Months">6 Months</option>
  <option value="9 Months">9 Months</option>
  <option value="12 Months">12 Months</option>
</select>
                                                  </div>
                                                  <div class="mb-3">
                                                    <label for="startDate{{ $sub->id }}" class="form-label">Start Date</label>
                                                    <input type="date" class="form-control" id="startDate{{ $sub->id }}">
                                                  </div>
                                                  <div class="mb-3">
                                                    <label for="endDate{{ $sub->id }}" class="form-label">End Date</label>
                                                    <input type="date" class="form-control" id="endDate{{ $sub->id }}" readonly>
                                                  </div>
                                                </form>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary save-payment-btn" data-bs-id="{{ $sub->id }}" data-bs-dismiss="modal">Save</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                    </td>
                                    <td class="start-date-cell">{{ $sub->start_date }}</td>
                                    <td class="end-date-cell">{{ $sub->end_date }}</td>
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
                    <i class="bi bi-trophy"></i> Most Active Member (Per Month)
                </div>
                <div class="card-body p-0">
                    <div style="max-height: 420px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr><th>Country</th><th class="text-center">Uploads</th><th class="text-center">Downloads</th></tr>
                            </thead>
                            <tbody>
                            @foreach($uploadsByCountry as $row)
                                <tr>
                                    <td>{{ $row['name'] }}</td>
                                    <td class="text-center">{{ $row['uploads'] }}</td>
                                    <td class="text-center">{{ $row['downloads'] }}</td>
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
<!-- Auto-calculate end date based on payment period and start date -->
<script>
window.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[id^="paymentType"]').forEach(function(periodSelect) {
        var subId = periodSelect.id.replace('paymentType','');
        var startInput = document.getElementById('startDate' + subId);
        var endInput = document.getElementById('endDate' + subId);
        function calcEndDate() {
            var period = periodSelect.value;
            var start = startInput.value;
            if (!start) return;
            var days = 30;
            if (period === '3 Months') days = 90;
            else if (period === '6 Months') days = 180;
            else if (period === '9 Months') days = 270;
            else if (period === '12 Months') days = 360;
            var d = new Date(start);
            d.setDate(d.getDate() + days);
            var yyyy = d.getFullYear();
            var mm = String(d.getMonth()+1).padStart(2,'0');
            var dd = String(d.getDate()).padStart(2,'0');
            endInput.value = yyyy + '-' + mm + '-' + dd;
        }
        if (startInput && endInput) {
            startInput.addEventListener('change', calcEndDate);
            periodSelect.addEventListener('change', calcEndDate);
        }
    });
    document.querySelectorAll('.save-payment-btn').forEach(function(saveBtn) {
        saveBtn.addEventListener('click', function() {
            var subId = saveBtn.getAttribute('data-bs-id');
            var startDate = document.getElementById('startDate' + subId).value;
            var endDate = document.getElementById('endDate' + subId).value;
            var row = document.querySelector('tr[data-sub-id="' + subId + '"]');
            fetch('/admin/subscription/payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id: subId,
                    start_date: startDate,
                    end_date: endDate
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (row.querySelector('.status-cell')) row.querySelector('.status-cell').innerHTML = '<span class="badge bg-success">Active</span>';
if (row.querySelector('.payment-status-cell')) row.querySelector('.payment-status-cell').innerHTML = '<span class="badge bg-success">Paid</span>';
if (row.querySelector('.btn-outline-primary')) {
    // Only enable if endDate is in the past
    var today = new Date();
    var ed = new Date(endDate);
    if (ed > today) {
        row.querySelector('.btn-outline-primary').setAttribute('disabled', 'disabled');
    } else {
        row.querySelector('.btn-outline-primary').removeAttribute('disabled');
    }
}
if (row.querySelector('.start-date-cell')) row.querySelector('.start-date-cell').innerText = startDate;
if (row.querySelector('.end-date-cell')) row.querySelector('.end-date-cell').innerText = endDate;
// Update Revenue Streams widget
var revWidget = document.querySelector('.card-body.text-center i.bi-cash-stack')?.parentElement;
if (revWidget) {
    if (typeof data.paidPercentage !== 'undefined' && typeof data.paidCount !== 'undefined' && typeof data.unpaidCount !== 'undefined') {
        var total = data.paidCount + data.unpaidCount;
        if (total > 0) {
            revWidget.querySelectorAll('span, small').forEach(function(el) { el.remove(); });
            var percentSpan = document.createElement('span');
            percentSpan.className = 'fw-bold';
            percentSpan.style.fontSize = '1.25rem';
            percentSpan.innerText = data.paidPercentage + '% paid';
            var countSmall = document.createElement('small');
            countSmall.style.fontSize = '1.1rem';
            countSmall.innerText = '(' + data.paidCount + '/' + total + ' countries)';
            revWidget.appendChild(percentSpan);
            revWidget.appendChild(document.createElement('br'));
            revWidget.appendChild(countSmall);
        } else {
            revWidget.querySelectorAll('span, small').forEach(function(el) { el.remove(); });
            var noData = document.createElement('span');
            noData.className = 'text-muted';
            noData.innerText = 'No data';
            revWidget.appendChild(noData);
        }
    }
}
                } else {
                    alert('Error: ' + (data.message || 'Could not save payment.'));
                }
            })
            .catch(() => alert('Error: Could not save payment.'));
        });
    });
});
</script>
<noscript><style>.bi{display:none !important;}</style><span style='color:red'>Icons require JavaScript and CDN access.</span></noscript>
<script>window.addEventListener('DOMContentLoaded',function(){var test=document.createElement('i');test.className='bi bi-star-fill';document.body.appendChild(test);var comp=window.getComputedStyle(test,':before').content;if(!comp||comp==='none'){var link=document.createElement('link');link.rel='stylesheet';link.href='/css/bootstrap-icons.min.css';document.head.appendChild(link);}test.remove();});</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row mb-4" style="padding-left:1cm;padding-right:1cm;">
    <div class="col-lg-6 mb-4">
        <div class="card shadow border-0">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-bar-chart"></i> Today's Uploads Per Country</span>
    <span class="fw-normal" style="font-size:1rem;">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</span>
</div>
            <div class="card-body p-0 equal-widget-height" style="min-height:340px;height:340px;overflow-y:auto;">
                <ul class="list-group list-group-flush">
                    @if(isset($uploadsTodayPerCountry))
    @foreach($uploadsTodayPerCountry as $row)
        <li class="list-group-item d-flex justify-content-between align-items-center">
    <span>{{ $row['country'] }}</span>
    <span class="badge rounded-pill" style="font-size:1.1rem;background-color:{{ $row['uploads'] >= 3 ? '#28a745' : '#dc3545' }};color:white;">{{ $row['uploads'] }}</span>
</li>
    @endforeach
@else
    <li class="list-group-item text-danger">uploadsTodayPerCountry is not set!<br>Debug: <pre>{{ print_r(array_keys(get_defined_vars()), true) }}</pre></li>
@endif
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card shadow border-0">
            <div class="card-header bg-info text-white"><i class="bi bi-graph-up"></i> Overall Uploads Presentation</div>
            <div class="card-body" style="min-height:340px;height:340px;overflow-y:auto;">
    <div class="mt-3">
        <h6 class="fw-bold text-center">Uploads Distribution by Country</h6>
        @php
            $hasUploadsPie = isset($uploadsOverallPerCountry) && collect($uploadsOverallPerCountry)->sum('uploads') > 0;
        @endphp
        @if($hasUploadsPie)
            <canvas id="uploadsPieChart"></canvas>
        @else
            <div class="text-center text-muted my-4">No upload data available for pie chart.</div>
        @endif
    </div>
</div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function() {
    // --- Daily Uploads Per Country (Bar Chart) ---
    const uploadsTodayPerCountryBar = @json(isset($uploadsTodayPerCountry) ? $uploadsTodayPerCountry : []);
    const uploadsBarLabels = uploadsTodayPerCountryBar.map(row => row.country);
    const uploadsBarData = uploadsTodayPerCountryBar.map(row => row.uploads);
    const uploadsBarCanvas = document.getElementById('uploadsPerCountryChart');
    if (uploadsBarCanvas) {
        new Chart(uploadsBarCanvas, {
            type: 'bar',
            data: {
                labels: uploadsBarLabels,
                datasets: [{
                    label: 'Uploads Today',
                    data: uploadsBarData,
                    backgroundColor: 'rgba(40,167,69,0.7)',
                }]
            },
            options: {responsive:true, plugins:{legend:{display:false}}}
        });
    }
})();

(function() {
    // --- Download Counts Per Video (Line Chart) ---
    const downloadsLineData = @json($downloadsPerVideoChart);
    const downloadsLineCanvas = document.getElementById('downloadsPerVideoChart');
    if (downloadsLineCanvas) {
        new Chart(downloadsLineCanvas, {
            type: 'line',
            data: {
                labels: downloadsLineData.map(row => row.label),
                datasets: [{
                    label: 'Downloads',
                    data: downloadsLineData.map(row => row.count),
                    backgroundColor: 'rgba(23,162,184,0.7)',
                    borderColor: 'rgba(23,162,184,1)',
                    fill: false,
                    tension: 0.3
                }]
            },
            options: {responsive:true, plugins:{legend:{display:false}}}
        });
    }
})();

(function() {
    // --- Videos Per Category (Bar Chart) ---
    const videosPerCategoryBar = @json($videosPerCategory);
    const videosPerCategoryCanvas = document.getElementById('videosPerCategoryChart');
    if (videosPerCategoryCanvas) {
        new Chart(videosPerCategoryCanvas, {
            type: 'bar',
            data: {
                labels: videosPerCategoryBar.map(c => c.category.name),
                datasets: [{
                    label: 'Videos',
                    data: videosPerCategoryBar.map(c => c.total),
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                }],
            },
            options: { plugins: { legend: { display: false } } }
        });
    }
})();

(function() {
    // --- Comments Per Video Chart ---
    const commentsPerVideoBar = @json($commentsPerVideo);
    const commentsPerVideoCanvas = document.getElementById('commentsPerVideoChart');
    if (commentsPerVideoCanvas) {
        new Chart(commentsPerVideoCanvas, {
            type: 'bar',
            data: {
                labels: commentsPerVideoBar.map(v => v.title),
                datasets: [{
                    label: 'Comments',
                    data: commentsPerVideoBar.map(v => v.comments_count),
                    backgroundColor: 'rgba(255, 193, 7, 0.7)',
                }],
            },
            options: { plugins: { legend: { display: false } } }
        });
    }
})();

(function() {
    // --- Uploads Distribution Pie Chart ---
    const uploadsOverallPie = @json($uploadsOverallPerCountry ?? []);
    const uploadsPieLabels = uploadsOverallPie.map(row => row.country);
    const uploadsPieData = uploadsOverallPie.map(row => row.uploads);
    const uploadsPieColors = uploadsPieLabels.map((_,i) => `hsl(${i*360/uploadsPieLabels.length},70%,60%)`);
    const uploadsPieCanvas = document.getElementById('uploadsPieChart');
    if (uploadsPieData.reduce((a,b) => a+b, 0) > 0 && uploadsPieCanvas) {
        new Chart(uploadsPieCanvas, {
            type: 'pie',
            data: {
                labels: uploadsPieLabels,
                datasets: [{
                    label: 'Uploads',
                    data: uploadsPieData,
                    backgroundColor: uploadsPieColors,
                }],
            },
            options: { plugins: { legend: { display: true, position: 'bottom' } } }
        });
    }
})();
</script>

@push('scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    // Initialize Pusher
    const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
        cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}'
    });

    // Subscribe to the payments channel
    const channel = pusher.subscribe('payments');

    // Listen for payment status updates
    channel.bind('PaymentStatusUpdated', function(data) {
        // Update the dashboard payment status row
        const row = document.querySelector(`tr[data-sub-id="${data.id}"]`);
        if (row) {
            // Update payment status badge
            const statusBadge = row.querySelector('.payment-status-cell .badge');
            if (statusBadge) {
                statusBadge.className = `badge bg-${data.payment_status === 'paid' ? 'success' : 'danger'}`;
                statusBadge.textContent = data.payment_status.charAt(0).toUpperCase() + data.payment_status.slice(1);
            }

            // Update dates
            const startDateCell = row.querySelector('.start-date-cell');
            const endDateCell = row.querySelector('.end-date-cell');
            if (startDateCell) startDateCell.textContent = data.start_date;
            if (endDateCell) endDateCell.textContent = data.end_date;

            // Update pay button state
            const payButton = row.querySelector('button[data-bs-toggle="modal"]');
            if (payButton) {
                if (data.payment_status === 'paid') {
                    payButton.disabled = true;
                } else {
                    payButton.disabled = false;
                }
            }
        }

        // Update the Revenue Streams widget
        updateRevenueWidget();
    });

    function updateRevenueWidget() {
        // Make an AJAX call to get updated revenue statistics
        fetch('{{ route('admin.revenue.stats') }}')
            .then(response => response.json())
            .then(data => {
                const revenueWidget = document.querySelector('.revenue-widget');
                if (revenueWidget && data.paidPercentage !== undefined) {
                    const percentageSpan = revenueWidget.querySelector('.fw-bold');
                    const countSpan = revenueWidget.querySelector('small');
                    
                    if (percentageSpan) {
                        percentageSpan.textContent = `${data.paidPercentage}% paid`;
                    }
                    if (countSpan) {
                        countSpan.textContent = `(${data.paidCount}/${data.paidCount + data.unpaidCount} countries)`;
                    }
                }
            })
            .catch(error => console.error('Error updating revenue widget:', error));
    }
</script>
@endpush
@endsection
