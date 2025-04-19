@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
    .member-hero {
        background: linear-gradient(90deg, #e0f7fa 0%, #e3ffe6 100%);
        border-radius: 1.5rem;
        box-shadow: 0 2px 24px rgba(0,0,0,0.08);
        padding: 2.5rem 2rem 2rem 2rem;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
    }
    .action-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        transition: transform 0.15s;
    }
    .action-card:hover {
        transform: translateY(-4px) scale(1.03);
        box-shadow: 0 4px 24px rgba(0,0,0,0.10);
    }
    .action-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
    .gradient-btn {
        background: linear-gradient(90deg, #43cea2 0%, #185a9d 100%);
        color: #fff;
        border: none;
        font-weight: 600;
        border-radius: 0.5rem;
        padding: 0.75rem 2rem;
        transition: background 0.2s;
    }
    .gradient-btn:hover {
        background: linear-gradient(90deg, #185a9d 0%, #43cea2 100%);
        color: #fff;
    }
</style>
<div class="container py-4">
    <h1 class="mb-2 fw-bold sadc-header-darkblue d-flex align-items-center justify-content-between">
        <span><i class="bi bi-person-circle"></i> Member Dashboard</span>
        <div>
            <a href="{{ route('videos.index') }}" class="btn btn-outline-success btn-lg me-2 sadc-header-darkblue">
    <i class="bi bi-collection-play"></i> My Uploads
</a>
<a href="#active-videos" class="btn btn-outline-secondary btn-lg me-2 sadc-header-darkblue">
    <i class="bi bi-play-circle"></i> Uploaded Videos
</a>

            <span class="btn btn-outline-info btn-lg sadc-header-darkblue disabled" style="pointer-events: none; opacity: 0.6;">
    <i class="bi bi-newspaper"></i> Add News
</span>
        </div>
    </h1>
    <div class="alert alert-info mb-4">
        <strong>Welcome to your member dashboard!</strong> Here you can manage your uploads, add news articles, and view your activity on the SADC portal. Use the cards and table below for quick actions and insights.
    </div>
    <div class="row g-4 mb-4">
        <div class="col-lg-4 col-md-6">
            <div class="card shadow h-100 border-0 bg-light">
                <div class="card-body text-center">
                    <i class="bi bi-collection-play fs-1 text-primary"></i>
                    <h6 class="mt-2">My Uploads</h6>
                    <div class="mb-2">
                        <span class="fw-bold fs-4">{{ $myUploadsCount ?? '0' }}</span>
                    </div>
                    <a href="{{ route('videos.index') }}" class="btn btn-outline-success btn-sm">View Uploads</a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card shadow h-100 border-0 bg-light">
                <div class="card-body text-center">
                    <i class="bi bi-upload fs-1 text-success"></i>
                    <h6 class="mt-2">Upload a Video</h6>
                    <div class="mb-2">
                        <span class="text-muted">Share new content with SADC</span>
                    </div>
                    <a href="{{ route('videos.create') }}" class="btn btn-outline-primary btn-sm">Upload a Video</a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card shadow h-100 border-0 bg-light">
                <div class="card-body text-center">
                    <i class="bi bi-newspaper fs-1 text-info"></i>
                    <h6 class="mt-2">Add News Article</h6>
                    <div class="mb-2">
                        <span class="text-muted">Publish news for your country</span>
                    </div>
                    <a href="{{ route('news.create') }}" class="btn btn-outline-info btn-sm">Add News</a>
                </div>
            </div>
        </div>
    </div>


@if(isset($activeVideos) && $activeVideos->count())
<div id="active-videos" class="container mb-5">
    <div class="d-flex align-items-center justify-content-between mt-5 mb-3">
  <h3 class="fw-bold text-primary mb-0">Active Videos</h3>
  <div style="max-width:260px; width:100%;">
    <input id="video-search" type="text" class="form-control form-control-sm" placeholder="Search videos..." />
  </div>
</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Video Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Uploader</th>
                    <th>Upload Date</th>
                    <th>Expiry Date</th>
                    <th class="text-end" style="min-width:180px;">
    <div class="d-flex justify-content-end align-items-center" style="gap:0.5rem;">
        <label for="show-today-only" class="mb-0" style="font-size:0.98rem; cursor:pointer;">Only show today's uploads</label>
        <input type="checkbox" id="show-today-only" style="margin-left:0.25em;">
    </div>
</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeVideos as $video)
                    <tr>
                        <td>
    <span data-bs-toggle="modal" data-bs-target="#title-modal-{{ $video->id }}" title="View full title" style="cursor:pointer; text-decoration:none; color:inherit;">
    {{ \Illuminate\Support\Str::limit($video->title, 15, '...') }}
</span>
    <!-- Title Modal -->
    <div class="modal fade" id="title-modal-{{ $video->id }}" tabindex="-1" aria-labelledby="titleModalLabel-{{ $video->id }}" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="titleModalLabel-{{ $video->id }}">Full Video Title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            {{ $video->title }}
          </div>
        </div>
      </div>
    </div>
</td>
                        <td>
    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#desc-modal-{{ $video->id }}">
        View Description
    </button>
    <!-- Description Modal -->
    <div class="modal fade" id="desc-modal-{{ $video->id }}" tabindex="-1" aria-labelledby="descModalLabel-{{ $video->id }}" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="descModalLabel-{{ $video->id }}">Video Description</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            {{ $video->description }}
          </div>
        </div>
      </div>
    </div>
</td>
<td>{{ $video->category ? $video->category->name : 'Unknown' }}</td>
                        <td>{{ $video->uploader ? $video->uploader->name : 'Unknown' }}</td>
                        <td>{{ \Carbon\Carbon::parse($video->upload_date)->format('M d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($video->upload_date)->addDays(30)->format('M d, Y') }}</td>
                        <td>
                            <button type="button" class="btn btn-outline-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#info-modal-{{ $video->id }}" title="Video Info">
    <i class="bi bi-info-circle"></i>
</button>

<!-- Info Modal -->
<div class="modal fade" id="info-modal-{{ $video->id }}" tabindex="-1" aria-labelledby="infoModalLabel-{{ $video->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="infoModalLabel-{{ $video->id }}">Video Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4 text-center mb-3 mb-md-0">
            @if(!empty($video->preview_thumbnail))
              <img src="{{ asset('storage/' . $video->preview_thumbnail) }}" alt="{{ $video->title }} preview" style="width:100%; max-width:180px; border-radius:0.6rem; object-fit:cover; background:#f3f4f6;" />
            @else
              <div style="width:100%; max-width:180px; height:120px; background:#e5e7eb; border-radius:0.6rem; display:flex; align-items:center; justify-content:center; color:#888; font-size:1.1rem; margin:auto;">No Preview</div>
            @endif
            <div class="mt-2 text-start">
              <div><strong>Format:</strong> {{ pathinfo($video->video_path, PATHINFO_EXTENSION) ?: 'Unknown' }}</div>
              <div><strong>Size:</strong> {{ isset($video->video_path) && file_exists(storage_path('app/public/' . $video->video_path)) ? number_format(filesize(storage_path('app/public/' . $video->video_path)) / 1048576, 2) . ' MB' : 'Unknown' }}</div>
              <div><strong>Duration:</strong> 
@if(isset($video->duration) && is_numeric($video->duration) && $video->duration > 0)
    {{ floor($video->duration / 60) }}:{{ str_pad($video->duration % 60, 2, '0', STR_PAD_LEFT) }} minutes
@else
    Unknown
@endif
</div>
            </div>
          </div>
          <div class="col-md-8">
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><strong>Title:</strong> {{ $video->title }}</li>
              <li class="list-group-item"><strong>Description:</strong> {{ $video->description }}</li>
              <li class="list-group-item"><strong>Uploader:</strong> {{ $video->uploader ? $video->uploader->name : 'Unknown' }}</li>
              <li class="list-group-item"><strong>Country:</strong> {{ $video->country ? $video->country->name : 'Unknown' }}</li>
              <li class="list-group-item"><strong>Status:</strong> {{ $video->status }}</li>
              <li class="list-group-item"><strong>Video Format:</strong> {{ pathinfo($video->video_path, PATHINFO_EXTENSION) ?: 'Unknown' }}</li>
              <li class="list-group-item"><strong>Duration:</strong> 
@if(isset($video->duration) && is_numeric($video->duration) && $video->duration > 0)
    {{ floor($video->duration / 60) }}:{{ str_pad($video->duration % 60, 2, '0', STR_PAD_LEFT) }} minutes
@else
    Unknown
@endif
</li>
              <li class="list-group-item"><strong>Upload Date:</strong> {{ \Carbon\Carbon::parse($video->upload_date)->format('M d, Y') }}</li>
              <li class="list-group-item"><strong>Expiry Date:</strong> {{ \Carbon\Carbon::parse($video->upload_date)->addDays(30)->format('M d, Y') }}</li>
            </ul>
          </div>
        </div>
          </div>
          <div class="mt-4 text-end">
            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#play-modal-{{ $video->id }}">
              <i class="bi bi-play-circle me-1"></i> Play Video
            </button>
            <a href="{{ asset('storage/' . $video->video_path) }}" class="btn btn-success" download>
              <i class="bi bi-download me-1"></i> Download Video
            </a>
          </div>
          <!-- Play Video Modal -->
          <div class="modal fade" id="play-modal-{{ $video->id }}" tabindex="-1" aria-labelledby="playModalLabel-{{ $video->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="playModalLabel-{{ $video->id }}">Play Video</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                  <video controls style="width:100%; max-height:60vh;">
                    <source src="{{ asset('storage/' . $video->video_path) }}" type="video/{{ pathinfo($video->video_path, PATHINFO_EXTENSION) }}">
                    Your browser does not support the video tag.
                  </video>
                </div>
              </div>
            </div>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<button type="button" class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modal-video-{{ $video->id }}">
    <i class="bi bi-play-circle"></i> Play Video
</button>
<a href="{{ asset('storage/' . $video->script_path) }}" class="btn btn-outline-info btn-sm me-1" download><i class="bi bi-file-earmark-text"></i> Transcript</a>
@if($video->voiceover_path)
    <a href="{{ asset('storage/' . $video->voiceover_path) }}" class="btn btn-outline-warning btn-sm me-1" download><i class="bi bi-mic"></i> Voice Over</a>
@else
    <button class="btn btn-outline-warning btn-sm me-1" disabled><i class="bi bi-mic"></i> Voice Over</button>
@endif
<a href="{{ asset('storage/' . $video->video_path) }}" class="btn btn-outline-success btn-sm" download><i class="bi bi-download"></i> Download</a>
@if(auth()->check() && isset($video->uploader) && $video->uploader->name === auth()->user()->name)
    <form action="{{ route('videos.destroy', $video->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm ms-1" title="Delete Video" onclick="return confirm('Are you sure you want to delete this video?');">
            <i class="bi bi-trash"></i>
        </button>
    </form>
@else
    <button type="button" class="btn btn-outline-danger btn-sm ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="You can only delete your own videos" style="opacity:0.5; pointer-events:none;">
        <i class="bi bi-trash"></i>
    </button>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>


                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('show-today-only');
    if (!checkbox) return;
    checkbox.addEventListener('change', function() {
        const today = new Date();
        const month = today.toLocaleString('en-US', { month: 'short' });
        const day = today.getDate(); // NOT zero-padded
        const year = today.getFullYear();
        const todayFormatted = `${month} ${day}, ${year}`;
        document.querySelectorAll('#active-videos tbody tr').forEach(function(row) {
            const uploadDateCell = row.querySelector('td:nth-child(5)');
            if (!uploadDateCell) return;
            const uploadDate = uploadDateCell.textContent.trim();
            if (checkbox.checked) {
                if (uploadDate !== todayFormatted) {
                    row.style.display = 'none';
                } else {
                    row.style.display = '';
                }
            } else {
                row.style.display = '';
            }
        });
    });
});
</script>
    </div>
    <!-- Pagination and per-page selector -->
    <div class="d-flex align-items-center justify-content-between flex-wrap mt-2">
      <div>
        <select class="form-select form-select-sm d-inline-block" style="width:70px;" id="perPageSelect">
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
        <span class="ms-2" id="table-record-info">
          @if($activeVideos instanceof \Illuminate\Pagination\LengthAwarePaginator || $activeVideos instanceof \Illuminate\Pagination\Paginator)
            Showing {{ ($activeVideos->firstItem() ?? 1) }} to {{ ($activeVideos->lastItem() ?? $activeVideos->count()) }} of {{ $activeVideos->total() }} records
          @else
            Showing 1 to {{ $activeVideos->count() }} of {{ $activeVideos->count() }} records
          @endif
        </span>
      </div>
      @if(method_exists($activeVideos, 'links'))
        <div>
          {{ $activeVideos->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const perPageSelect = document.getElementById('perPageSelect');
  if (perPageSelect) {
    perPageSelect.addEventListener('change', function() {
      const url = new URL(window.location.href);
      url.searchParams.set('per_page', this.value);
      window.location.href = url.toString();
    });
    // Optionally auto-select current per_page from query string
    const params = new URLSearchParams(window.location.search);
    const currentPerPage = params.get('per_page');
    if (currentPerPage) perPageSelect.value = currentPerPage;
  }
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('video-search');
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      const filter = this.value.toLowerCase();
      document.querySelectorAll('table tbody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
      });
    });
  }
});
</script>


{{-- Video Play Modals --}}
@foreach($activeVideos as $video)
    <div class="modal fade" id="modal-video-{{ $video->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $video->id }}" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel-{{ $video->id }}">{{ $video->title }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <video width="100%" height="auto" controls poster="{{ $video->preview_thumbnail ? asset('storage/' . $video->preview_thumbnail) : '' }}">
              <source src="{{ asset('storage/' . $video->video_path) }}" type="video/mp4">
              Your browser does not support the video tag.
            </video>
            <div class="mt-3">
              <strong>Description:</strong> {{ $video->description }}
            </div>
          </div>
        </div>
      </div>
    </div>
@endforeach

@endif
@endsection
