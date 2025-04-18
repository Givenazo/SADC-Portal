@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="/css/sadc-custom.css">
<div class="container mx-auto py-10 px-6">
    <div class="mb-8">
    <div class="text-center mb-6">
    <div class="bg-light py-3 px-2 mb-2 text-center" style="border-radius:0.5rem;">
      <h1 class="fw-bold d-flex flex-column align-items-center justify-content-center sadc-header-darkblue" style="font-size:3rem;margin-bottom:0;font-weight:bold;text-align:center;">
        <span><i class="bi bi-collection-play-fill me-2 sadc-header-darkblue" style="font-size:2.5rem;"></i></span>
        Uploaded Videos
      </h1>
      <span class="text-gray-600 text-lg" style="font-size:1.15rem;display:block;margin-top:0;text-align:center;margin-left:1cm;">Manage all videos you have access to on the SADC News Portal.</span>
    </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6">
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <div style="display:flex; gap:0.5rem; align-items:center; width:100%; max-width:420px;">
            <input type="text" id="video-search" class="form-control" style="max-width:320px; display:inline-block;" placeholder="Search for Videos...">
            <button id="video-search-clear" type="button" class="btn btn-outline-secondary" style="height:38px; display:none;">Clear</button>
            <button id="video-search-refresh" type="button" class="btn btn-outline-primary" style="height:38px; display:inline-flex; align-items:center; gap:0.3rem; border:2px solid #1677fa; border-radius:2rem; padding:0 1rem;">
                <span class="d-none d-sm-inline">Refresh</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 1 0-.908-.418A6 6 0 1 0 8 2v1z"/>
                  <path d="M8 1.5a.5.5 0 0 1 .5.5v3.707l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 .708-.708L7.5 5.707V2a.5.5 0 0 1 .5-.5z"/>
                </svg>
            </button>
        </div>
    </div>
        <div>
            <table class="min-w-full divide-y divide-gray-200" style="border-collapse: separate; border-spacing: 0; font-size:0.95rem;">
                <thead class="bg-[#f8fafc]" style="display:table; width:100%; table-layout:fixed;">
                    <tr>
                        <th class="px-2 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="width:3.5em; min-width:3.5em; max-width:3.5em; text-align:center; font-weight:700;">#</th>
                        <th class="px-4 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="font-weight:700; width:70px; min-width:70px; max-width:70px;">Preview</th>
                        <th class="px-6 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="font-weight:700;">Title</th>
                        <th class="px-6 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="font-weight:700;">Description</th>
                        <th class="px-6 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="font-weight:700;">Uploader</th>
                        <th class="px-6 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="font-weight:700;">Country</th>
                        <th class="px-6 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="font-weight:700;">Status</th>
                        <th class="px-6 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="font-weight:700;">Upload Date</th>
                        <th class="px-6 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="font-weight:700;">Expire Date</th>
                        <th class="px-6 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="font-weight:700;">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100" style="display:block; max-height:400px; overflow-y:auto; width:100%;">
                    @php $isOdd = false; $userId = Auth::id(); @endphp
                    @forelse($videos as $video)
                        @php $isOdd = !$isOdd; @endphp
                        <tr id="video-row-{{ $video->id }}" class="transition hover:bg-blue-50" style="border-bottom:1px solid #e5e7eb;{{ $isOdd ? 'background:#f9fafb;' : '' }} display:table; width:100%; table-layout:fixed;">
                            <td class="px-2 py-5 text-center align-middle" style="width:3.5em; min-width:3.5em; max-width:3.5em;">{{ $loop->iteration + ($videos->currentPage() - 1) * $videos->perPage() }}</td>
                            <td class="px-4 py-5 text-left align-middle" style="width:70px; min-width:70px; max-width:70px;">
                                @if(!empty($video->preview_thumbnail))
    <img src="{{ asset('storage/' . $video->preview_thumbnail) }}" alt="{{ $video->title }}" style="width:56px; height:36px; object-fit:cover; border-radius:0.4rem; background:#f3f4f6;" />
@else
    <div style="width:56px; height:36px; background:#e5e7eb; border-radius:0.4rem; display:flex; align-items:center; justify-content:center; color:#888; font-size:1.1rem;">—</div>
@endif
                            </td>
                            <td class="px-6 py-5 text-left align-middle">{{ $video->title }}</td>
                            <td class="px-6 py-5 text-left align-middle">
    <button type="button" class="desc-btn bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1 rounded shadow-sm border border-blue-100" data-bs-toggle="modal" data-bs-target="#descModal" data-description="{{ $video->description ? e($video->description) : 'No description available' }}">
        Description
    </button>
</td>
<td class="px-6 py-5 text-left align-middle">{{ $video->uploader && $video->uploader->name ? $video->uploader->name : 'Unknown' }}</td>
<td class="px-6 py-5 text-left align-middle">{{ $video->country && $video->country->name ? $video->country->name : 'Unknown' }}</td>
<td class="px-6 py-5 text-left align-middle">
    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $video->status == 'Published' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600' }}">
        {{ $video->status }}
    </span>
</td>
<td class="px-6 py-5 text-left align-middle">{{ $video->upload_date ? \Carbon\Carbon::parse($video->upload_date)->format('Y-m-d') : '' }}</td>
<td class="px-6 py-5 text-left align-middle">{{ $video->upload_date ? \Carbon\Carbon::parse($video->upload_date)->addDays(30)->format('Y-m-d') : '' }}</td>
                            <td class="px-6 py-5 text-left align-middle">
                                <div class="action-icons-row">
                                    <a href="#" class="action-icon-btn text-blue-600 hover:bg-blue-50 video-info-btn" tabindex="0"
                                    data-title="{{ e($video->title) }}"
                                    data-description="{{ e($video->description) }}"
                                    data-thumbnail="{{ $video->preview_thumbnail ? asset('storage/' . $video->preview_thumbnail) : '' }}"
                                    data-videopath="{{ asset('storage/' . $video->video_path) }}"
                                    data-scriptpath="{{ $video->script_path ? asset('storage/' . $video->script_path) : '' }}"
                                    data-voiceoverpath="{{ $video->voiceover_path ? asset('storage/' . $video->voiceover_path) : '' }}"
                                    data-uploader="{{ $video->uploader ? $video->uploader->name : 'Unknown' }}"
                                    data-country="{{ $video->country ? $video->country->name : 'Unknown' }}">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    @if($video->uploader && $video->uploader->id === $userId)
                                    <a href="#" class="action-icon-btn text-danger delete-video-btn" data-video-id="{{ $video->id }}" tabindex="0">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr style="display:table; width:100%; table-layout:fixed;">
                            <td colspan="10" class="px-6 py-8 text-center text-gray-400" style="font-size:1.08rem;">No videos found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $videos->links() }}
            </div>
        </div>
    </div>
</div>
<!-- Description Modal -->
<div class="modal fade" id="descModal" tabindex="-1" aria-labelledby="descModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="descModalLabel">Video Description</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="descModalBody">
        <!-- Description will be injected here -->
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.desc-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var desc = btn.getAttribute('data-description');
                document.getElementById('descModalBody').textContent = desc;
            });
        });
    });
</script>
@endsection
