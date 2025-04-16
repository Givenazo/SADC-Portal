@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4 text-primary"><i class="bi bi-flag"></i> Member Countries</h2>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Country</th>
                            <th>Status</th>
                            <th>Subscription</th>
                            <th>Videos Uploaded This Month</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($countries as $country)
                            <tr>
                                <td class="fw-semibold fs-6">
                                    {{ $country->name }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $country->status == 'Active' ? 'success' : 'danger' }} px-3 py-2" style="font-size:1rem;">{{ $country->status }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $country->subscription_status == 'Active' ? 'primary' : 'secondary' }} px-3 py-2" style="font-size:1rem;">{{ $country->subscription_status }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $country->videos_this_month }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
