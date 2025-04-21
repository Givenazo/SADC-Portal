@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal.show {
        display: block;
    }

    .modal-dialog {
        position: relative;
        width: auto;
        margin: 1.75rem auto;
        max-width: 500px;
    }

    .modal-content {
        position: relative;
        background-color: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .dark .modal-content {
        background-color: #1f2937;
        color: #fff;
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .dark .modal-header {
        border-color: #374151;
    }

    .modal-body {
        padding: 1rem;
    }

    .modal-footer {
        padding: 1rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }

    .dark .modal-footer {
        border-color: #374151;
    }

    body.modal-open {
        overflow: hidden;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        background-color: #fff;
    }

    .dark .form-control {
        background-color: #374151;
        border-color: #4b5563;
        color: #fff;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #3b82f6;
        color: #fff;
        border: none;
    }

    .btn-secondary {
        background-color: #6b7280;
        color: #fff;
        border: none;
    }
</style>
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mx-auto my-4" style="max-width:600px;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Capture Payment Button -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Payments & Subscriptions
            </h1>
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <button type="button"
                        data-bs-toggle="modal" 
                        data-bs-target="#capturePaymentModal"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 text-base font-medium rounded-md shadow-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Capture Payment
                </button>
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white dark:bg-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Payments Table Card -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-primary-600">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Payment Records
                    </h2>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2">
                            <label for="year-filter" class="text-sm font-medium text-white">Year:</label>
                            <select id="year-filter" class="block w-28 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @php
                                    $currentYear = (int)date('Y');
                                    $years = range($currentYear, $currentYear - 2);
                                @endphp
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $year === $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="month-filter" class="text-sm font-medium text-white">Month:</label>
                            <select id="month-filter" class="block w-32 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    @php $currentMonth = (int)date('n'); @endphp
    <option value="1" {{ $currentMonth === 1 ? 'selected' : '' }}>January</option>
    <option value="2" {{ $currentMonth === 2 ? 'selected' : '' }}>February</option>
    <option value="3" {{ $currentMonth === 3 ? 'selected' : '' }}>March</option>
    <option value="4" {{ $currentMonth === 4 ? 'selected' : '' }}>April</option>
    <option value="5" {{ $currentMonth === 5 ? 'selected' : '' }}>May</option>
    <option value="6" {{ $currentMonth === 6 ? 'selected' : '' }}>June</option>
    <option value="7" {{ $currentMonth === 7 ? 'selected' : '' }}>July</option>
    <option value="8" {{ $currentMonth === 8 ? 'selected' : '' }}>August</option>
    <option value="9" {{ $currentMonth === 9 ? 'selected' : '' }}>September</option>
    <option value="10" {{ $currentMonth === 10 ? 'selected' : '' }}>October</option>
    <option value="11" {{ $currentMonth === 11 ? 'selected' : '' }}>November</option>
    <option value="12" {{ $currentMonth === 12 ? 'selected' : '' }}>December</option>
                            </select>
                        </div>
                        <button id="reset-filter" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-primary-600 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Country
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Payment Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Amount
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Start
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                End
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="payments-tbody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
    @include('admin.payments.partials.tbody', ['subscriptions' => $subscriptions])
                        @forelse($subscriptions as $sub)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700" data-sub-id="{{ $sub->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $sub->country->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $sub->status == 'Active' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' }}">
                                    {{ $sub->status ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $sub->payment_status == 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                    {{ ucfirst($sub->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                                {{ $sub->amount ? number_format($sub->amount, 2) : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $sub->start_date ? date('d M Y', strtotime($sub->start_date)) : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $sub->end_date ? date('d M Y', strtotime($sub->end_date)) : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button class="inline-flex items-center px-3 py-1.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#viewSubscriptionModal{{ $sub->id }}">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View Details
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No payment records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Details Modal for Payment History -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" id="details-modal-content">
      <!-- AJAX content will be loaded here -->
    </div>
  </div>
</div>

<!-- Bootstrap Modal for Capture Payment (form only in modal, not inline) -->
<div class="modal fade" id="capturePaymentModal" tabindex="-1" aria-labelledby="capturePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="capturePaymentModalLabel">Capture Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.payments.capture') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="country_id" class="form-label">Country</label>
                        <select id="country_id" name="country_id" class="form-select" required>
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="amount" name="amount" required step="0.01">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="payment_period" class="form-label">Payment Period</label>
                        <select id="payment_period" name="payment_period" class="form-select" required>
                            <option value="">Select Period</option>
                            <option value="1">1 Month</option>
                            <option value="3">3 Months</option>
                            <option value="6">6 Months</option>
                            <option value="12">12 Months</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select id="payment_status" name="payment_status" class="form-select" required>
                            <option value="paid">Paid</option>
                            <option value="unpaid">Unpaid</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const periodSelect = document.getElementById('payment_period');

        function calculateEndDate() {
            const startDate = new Date(startDateInput.value);
            const period = parseInt(periodSelect.value);
            
            if (!isNaN(startDate.getTime()) && period) {
                const endDate = new Date(startDate.getTime());
                endDate.setMonth(endDate.getMonth() + period);
                endDate.setDate(endDate.getDate() - 1);
                
                const year = endDate.getFullYear();
                const month = String(endDate.getMonth() + 1).padStart(2, '0');
                const day = String(endDate.getDate()).padStart(2, '0');
                
                endDateInput.value = `${year}-${month}-${day}`;
            }
        }

        startDateInput.addEventListener('change', calculateEndDate);
        periodSelect.addEventListener('change', calculateEndDate);

        // Set default date to today
        const today = new Date();
        startDateInput.value = today.toISOString().split('T')[0];
        calculateEndDate();
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const yearFilter = document.getElementById('year-filter');
    const monthFilter = document.getElementById('month-filter');
    const resetBtn = document.getElementById('reset-filter');
    const tbody = document.getElementById('payments-tbody');

    function fetchFilteredPayments() {
        const year = yearFilter.value;
        const month = monthFilter.value;
        const params = new URLSearchParams();
        if (year) params.append('year', year);
        if (month) params.append('month', month);
        fetch(`/admin/payments/filter?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.text())
        .then(html => {
            tbody.innerHTML = html;
        });
    }

    yearFilter.addEventListener('change', fetchFilteredPayments);
    monthFilter.addEventListener('change', fetchFilteredPayments);
    resetBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const now = new Date();
        const currentYear = now.getFullYear();
        const currentMonth = now.getMonth() + 1;
        // Set year
        for (let i = 0; i < yearFilter.options.length; i++) {
            if (parseInt(yearFilter.options[i].value) === currentYear) {
                yearFilter.selectedIndex = i;
                break;
            }
        }
        // Set month
        for (let i = 0; i < monthFilter.options.length; i++) {
            if (parseInt(monthFilter.options[i].value) === currentMonth) {
                monthFilter.selectedIndex = i;
                break;
            }
        }
        fetchFilteredPayments();
    });
    // On page load, filter by current month
    fetchFilteredPayments();

    // Handle View Details button click
    document.body.addEventListener('click', function(e) {
        if (e.target.closest('.view-details-btn')) {
            const btn = e.target.closest('.view-details-btn');
            const countryId = btn.getAttribute('data-country-id');
            const year = document.getElementById('year-filter').value;
            const modalContent = document.getElementById('details-modal-content');
            modalContent.innerHTML = '<div class="modal-body"><div class="text-center py-4">Loading...</div></div>';
            fetch(`/admin/payments/details?country_id=${countryId}&year=${year}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.text())
            .then(html => {
                modalContent.innerHTML = html;
                // Explicitly show the modal using Bootstrap JS API
                if (window.bootstrap && bootstrap.Modal) {
                    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('detailsModal'));
                    modal.show();
                } else if (window.$ && $.fn.modal) {
                    // Fallback for jQuery Bootstrap
                    $('#detailsModal').modal('show');
                }
            });
        }
    });
});
</script>
@endpush
