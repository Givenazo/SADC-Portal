@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
    .video-card {
        border-radius: 1rem;
        box-shadow: 0 2px 16px rgba(0,0,0,0.06);
        transition: transform 0.15s;
        overflow: hidden;
    }
    .video-card:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 4px 28px rgba(0,0,0,0.10);
    }
    .video-thumb {
        width: 100%;
        height: 180px;
        object-fit: cover;
        background: #e9ecef;
    }
    .video-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #185a9d;
        margin-bottom: 0.25rem;
    }
    .video-meta {
        color: #6c757d;
        font-size: 0.95rem;
    }
</style>
<div class="container py-4">
    <h2 class="fw-bold mb-4 text-success">My Uploaded Videos</h2>
    @if($videos->count())
        <div class="row g-4">
            @foreach($videos as $video)
                <div class="col-md-6 col-lg-4 video-row" id="video-row-{{ $video->id }}">
                    <div class="card video-card h-100">
                        @if($video->preview_thumbnail)
                            <img src="{{ asset('storage/' . $video->preview_thumbnail) }}" class="video-thumb" alt="Thumbnail">
                        @else
                            <div class="video-thumb d-flex align-items-center justify-content-center text-muted">
                                <i class="bi bi-camera-video" style="font-size:2.5rem;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="video-title">{{ $video->title }}</div>
                            <div class="video-meta mb-2">Uploaded: {{ \Carbon\Carbon::parse($video->upload_date)->format('M d, Y') }}</div>
                            <div class="video-meta">Status: <span class="badge bg-{{ $video->status == 'Published' ? 'success' : ($video->status == 'Blocked' ? 'danger' : 'secondary') }}">{{ $video->status }}</span></div>
                        </div>
                        <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-video-{{ $video->id }}">
    <i class="bi bi-eye"></i> View
</button>
                            <a href="#" class="btn btn-outline-secondary btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                            <form action="{{ route('videos.destroy', $video->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this video?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Video Play Modals --}}
            @foreach($videos as $video)
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
        </div>
        <div class="mt-4">
            {{ $videos->links() }}
        </div>
    @else
        <div class="alert alert-info">You haven't uploaded any videos yet.</div>
    @endif
</div>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteVideoModal" tabindex="-1" aria-labelledby="deleteVideoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger" id="deleteVideoModalLabel"><i class="bi bi-exclamation-triangle me-2"></i>Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this video? This will also remove associated script and voiceover files if they exist.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteVideo">Delete</button>
      </div>
    </div>
  </div>
</div>
<div id="video-delete-alerts"></div>

<script>
let deleteVideoId = null;
let deleteVideoUrl = null;

// Show modal on delete button click
Array.from(document.querySelectorAll('.delete-video-btn')).forEach(function(btn) {
    btn.addEventListener('click', function() {
        deleteVideoId = btn.getAttribute('data-video-id');
        deleteVideoUrl = btn.getAttribute('data-delete-url');
        var modal = new bootstrap.Modal(document.getElementById('deleteVideoModal'));
        modal.show();
    });
});

// Confirm delete
const confirmBtn = document.getElementById('confirmDeleteVideo');
if (confirmBtn) {
    confirmBtn.addEventListener('click', function() {
        if (!deleteVideoId || !deleteVideoUrl) return;
        fetch(deleteVideoUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
        }).then(function(response) {
            if (response.ok) {
                // Remove card
                const row = document.getElementById('video-row-' + deleteVideoId);
                if (row) row.remove();
                showAlert('Video and associated resources deleted successfully.', 'success');
            } else {
                response.json().then(function(data) {
                    showAlert(data.message || 'Failed to delete video or associated files.', 'danger');
                }).catch(function() {
                    showAlert('Failed to delete video or associated files.', 'danger');
                });
            }
        }).catch(function() {
            showAlert('Failed to delete video or associated files.', 'danger');
        });
        var modalEl = document.getElementById('deleteVideoModal');
        var modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
    });
}

function showAlert(msg, type) {
    const alerts = document.getElementById('video-delete-alerts');
    if (!alerts) return;
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show mt-3`;
    alert.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${msg}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
    alerts.appendChild(alert);
    setTimeout(() => { alert.remove(); }, 4000);
}
</script>
@endsection
