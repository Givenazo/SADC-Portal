@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 fw-bold text-primary"><i class="bi bi-globe"></i> SADC Member Countries</h1>
    @php
        $totalActive = $countries->where('status', 'Active')->count();
        $totalBlocked = $countries->where('status', 'Blocked')->count();
        $totalUploads = $countries->sum('videos_this_month');
    @endphp
    <div class="d-flex justify-content-center">
        <div class="bg-white rounded shadow-sm p-5 mb-4" style="max-width: 900px; width: 100%;">
            <h2 class="fw-bold text-primary mb-5 text-center" style="font-size:2.2rem;"><i class="bi bi-globe"></i> SADC Member Countries</h2>
            <div class="d-flex flex-wrap justify-content-center gap-5 mb-4">
                <button class="btn btn-success btn-xl px-5 py-4 d-flex flex-column align-items-center justify-content-center" style="font-size:1.5rem;">
                    <i class="bi bi-check-circle mb-2" style="font-size:2.7rem;"></i>
                    <span class="mb-1">Active</span>
                    <span class="fw-bold display-4">{{ $totalActive }}</span>
                </button>
                <button class="btn btn-danger btn-xl px-5 py-4 d-flex flex-column align-items-center justify-content-center" style="font-size:1.5rem;">
                    <i class="bi bi-x-circle mb-2" style="font-size:2.7rem;"></i>
                    <span class="mb-1">Blocked</span>
                    <span class="fw-bold display-4">{{ $totalBlocked }}</span>
                </button>
                <button class="btn btn-primary btn-xl px-5 py-4 d-flex flex-column align-items-center justify-content-center" style="font-size:1.5rem;">
                    <i class="bi bi-collection-play mb-2" style="font-size:2.7rem;"></i>
                    <span class="mb-1">Uploaded This Month</span>
                    <span class="fw-bold display-4">{{ $totalUploads }}</span>
                </button>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('countryDonut').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Active', 'Blocked'],
                    datasets: [{
                        data: [{{ $totalActive }}, {{ $totalBlocked }}],
                        backgroundColor: ['#198754', '#dc3545'],
                        borderWidth: 2
                    }]
                },
                options: {
                    cutout: '70%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
    <div class="card shadow-lg border-0 p-4 bg-light">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle rounded overflow-hidden" style="min-width: 700px; font-size:1.1rem;">
                        <thead class="table-light">
                            <tr style="font-size:1.15rem;">
                                <th class="py-3">Country</th>
                                <th class="py-3">Flag</th>
                                <th class="py-3">Status</th>
                                <th class="py-3">Subscription</th>
                                <th class="py-3">Videos Uploaded (This Month)</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $countryIsoCodes = [
                                'Angola' => 'ao',
                                'Botswana' => 'bw',
                                'Comoros' => 'km',
                                'DRC (Congo)' => 'cd', // Democratic Republic of the Congo
                                'Eswatini' => 'sz',
                                'Lesotho' => 'ls',
                                'Madagascar' => 'mg',
                                'Malawi' => 'mw',
                                'Mauritius' => 'mu',
                                'Mozambique' => 'mz',
                                'Namibia' => 'na',
                                'Seychelles' => 'sc',
                                'South Africa' => 'za',
                                'Tanzania' => 'tz',
                                'Zambia' => 'zm',
                                'Zimbabwe' => 'zw',
                            ];
                        @endphp
                        @foreach($countries as $country)
                            <tr>
                                <td class="py-3">
                                    <span class="fw-semibold fs-5">{{ $country->name }}</span>
                                </td>
                                <td class="py-3 text-center">
                                    @php $iso = $countryIsoCodes[$country->name] ?? null; @endphp
                                    @if($iso)
                                        <img src="https://flagcdn.com/32x24/{{ $iso }}.png" alt="flag" class="rounded border" width="32" height="24">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $country->status == 'Active' ? 'success' : 'danger' }} px-3 py-2" style="font-size:1rem;">{{ $country->status }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $country->subscription_status == 'Active' ? 'success' : ($country->subscription_status == 'Suspended' ? 'warning' : 'secondary') }} px-3 py-2" style="font-size:1rem;">{{ $country->subscription_status ?? 'No Data' }}</span>
                                </td>
                                <td><span class="fw-bold fs-5">{{ $country->videos_this_month }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<style>
.table-striped > tbody > tr:nth-of-type(odd) {
    --bs-table-accent-bg: #f6fafd;
}
.table-striped > tbody > tr {
    transition: background 0.13s;
}
.table > :not(:last-child) > :last-child > * {
    border-bottom-color: #e6e6e6;
}
</style>
</div>
<style>
.table-hover tbody tr:hover {
    background-color: #f7faff;
    transition: background 0.13s;
}
</style>
@endsection
