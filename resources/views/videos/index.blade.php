@extends('layouts.app')

@section('content')
<!-- Add Bootstrap CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<div class="min-h-screen bg-gray-100">
    <div class="container mx-auto py-8 px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="flex items-center justify-center text-3xl font-bold text-[#003366] mb-2">
                <i class="bi bi-play-circle-fill me-2 text-4xl"></i>
                My Uploads
            </h1>
            <p class="text-gray-600">
                Manage all videos you have access to on the SADC News Portal.
            </p>
        </div>

        <!-- Search and Actions Bar -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <!-- Search -->
                <div class="flex-grow max-w-xl">
                    <input type="text" 
                           id="searchInput"
                           placeholder="Search videos..." 
                           class="flex-grow px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Upload Button -->
                @auth
                <a href="{{ route('videos.create') }}" class="inline-flex items-center px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="bi bi-plus-lg me-2"></i>
                    Upload Video
                </a>
                @endauth
            </div>
        </div>

        <!-- Videos Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-4 py-3 text-center" style="width: 50px">#</th>
                            <th class="px-4 py-3 text-left" style="width: 120px">Thumbnail</th>
                            <th class="px-6 py-3 text-left">Title</th>
                            <th class="px-6 py-3 text-left">Description</th>
                            <th class="px-6 py-3 text-left">Uploader</th>
                            <th class="px-6 py-3 text-left">Country</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Upload Date</th>
                            <th class="px-6 py-3 text-left">Expiry Date</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($videos as $video)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4 text-center">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4">
                                    @if(!empty($video->preview_thumbnail))
                                        <img src="{{ asset('storage/' . $video->preview_thumbnail) }}" 
                                             alt="{{ $video->title }}" 
                                             class="w-20 h-12 object-cover rounded">
                                    @else
                                        <div class="w-20 h-12 bg-gray-100 rounded flex items-center justify-center">
                                            <i class="bi bi-camera-video text-gray-400"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-medium">
                                    <button type="button"
                                            class="text-left hover:text-blue-600"
                                            data-bs-toggle="modal"
                                            data-bs-target="#titleModal"
                                            data-title="{{ $video->title }}"
                                            title="Click to view full title">
                                        {{ \Str::limit($video->title, 15) }}
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <button type="button" 
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-md transition-colors"
                                            data-bs-toggle="modal"
                                            data-bs-target="#descModal"
                                            data-description="{{ $video->description }}"
                                            title="Click to view description">
                                        <i class="bi bi-file-text me-1"></i>
                                        View
                                    </button>
                                </td>
                                <td class="px-6 py-4">{{ $video->uploader && $video->uploader->name ? $video->uploader->name : 'Unknown' }}</td>
                                <td class="px-6 py-4">{{ $video->country && $video->country->name ? $video->country->name : 'Unknown' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                                        {{ $video->status == 'Published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $video->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $video->upload_date ? \Carbon\Carbon::parse($video->upload_date)->format('Y-m-d') : '' }}</td>
                                <td class="px-6 py-4">{{ $video->upload_date ? \Carbon\Carbon::parse($video->upload_date)->addDays(30)->format('Y-m-d') : '' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('videos.show', $video) }}" 
                                           class="text-blue-600 hover:text-blue-800"
                                           title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($video->uploader && $video->uploader->id === Auth::id())
                                            <a href="{{ route('videos.edit', $video) }}"
                                               class="text-yellow-600 hover:text-yellow-800"
                                               title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('videos.destroy', $video->id) }}" 
                                                  method="POST" 
                                                  class="inline-block"
                                                  onsubmit="return confirm('Are you sure you want to delete this video?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800"
                                                        title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="bi bi-camera-video text-4xl mb-2"></i>
                                        <p class="text-lg font-medium mb-1">No videos found</p>
                                        <p class="text-sm mb-4">Get started by uploading your first video</p>
                                        @auth
                                            <a href="{{ route('videos.create') }}" 
                                               class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                                Upload Video
                                            </a>
                                        @endauth
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($videos->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $videos->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Description Modal -->
<div class="modal fade" id="descModal" tabindex="-1" aria-labelledby="descModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-lg shadow-lg">
            <div class="modal-header border-b border-gray-200">
                <h5 class="modal-title text-lg font-semibold" id="descModalLabel">Video Description</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p id="descModalContent" class="text-gray-700 whitespace-pre-wrap leading-relaxed"></p>
            </div>
        </div>
    </div>
</div>

<!-- Title Modal -->
<div class="modal fade" id="titleModal" tabindex="-1" aria-labelledby="titleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-lg shadow-lg">
            <div class="modal-header border-b border-gray-200">
                <h5 class="modal-title text-lg font-semibold" id="titleModalLabel">Video Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p id="titleModalContent" class="text-lg font-medium text-gray-800"></p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Description button click handlers
    document.querySelectorAll('button[data-bs-target="#descModal"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const description = this.getAttribute('data-description');
            const modalContent = document.getElementById('descModalContent');
            modalContent.textContent = description || 'No description available';
            new bootstrap.Modal(document.getElementById('descModal')).show();
        });
    });

    // Title button click handlers
    document.querySelectorAll('button[data-bs-target="#titleModal"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const title = this.getAttribute('data-title');
            const modalContent = document.getElementById('titleModalContent');
            modalContent.textContent = title;
            new bootstrap.Modal(document.getElementById('titleModal')).show();
        });
    });

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const videoRows = document.querySelectorAll('tbody tr');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        
        videoRows.forEach(row => {
            const title = row.querySelector('button[data-title]')?.getAttribute('data-title')?.toLowerCase() || '';
            const description = row.querySelector('button[data-description]')?.getAttribute('data-description')?.toLowerCase() || '';
            const uploader = row.querySelector('td:nth-child(5)')?.textContent?.toLowerCase() || '';
            const country = row.querySelector('td:nth-child(6)')?.textContent?.toLowerCase() || '';
            const status = row.querySelector('td:nth-child(7)')?.textContent?.toLowerCase() || '';

            const matches = title.includes(searchTerm) || 
                          description.includes(searchTerm) || 
                          uploader.includes(searchTerm) ||
                          country.includes(searchTerm) ||
                          status.includes(searchTerm);

            row.style.display = matches ? '' : 'none';
        });

        // Show/hide empty state message
        const visibleRows = Array.from(videoRows).filter(row => row.style.display !== 'none');
        const emptyMessage = document.querySelector('tr[data-empty-message]');
        
        if (visibleRows.length === 0 && !emptyMessage) {
            const tbody = document.querySelector('tbody');
            const emptyRow = document.createElement('tr');
            emptyRow.setAttribute('data-empty-message', 'true');
            emptyRow.innerHTML = `
                <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="bi bi-search text-4xl mb-2"></i>
                        <p class="text-lg font-medium mb-1">No matching videos found</p>
                        <p class="text-sm">Try adjusting your search terms</p>
                    </div>
                </td>
            `;
            tbody.appendChild(emptyRow);
        } else if (visibleRows.length > 0 && emptyMessage) {
            emptyMessage.remove();
        }
    });

    // Initialize tooltips if Bootstrap is loaded
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>
@endpush
@endsection
