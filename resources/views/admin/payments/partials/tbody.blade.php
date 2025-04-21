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
        <button type="button" class="inline-flex items-center px-3 py-1.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 view-details-btn"
                data-bs-toggle="modal" 
                data-bs-target="#detailsModal" 
                data-country-id="{{ $sub->country_id }}">
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
