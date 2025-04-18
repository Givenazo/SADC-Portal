@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
    .video-card {
        border-radius: 0.7rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: transform 0.12s;
        overflow: hidden;
        padding: 0.6rem 0.6rem 0.2rem 0.6rem;
        min-height: 270px;
    }
    .video-card:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 4px 28px rgba(0,0,0,0.10);
    }
    .video-thumb {
        width: 100%;
        height: 110px;
        object-fit: cover;
        background: #e9ecef;
        border-radius: 0.5rem;
    }
    .video-title {
        font-size: 0.97rem;
        font-weight: 600;
        color: #185a9d;
        margin-bottom: 0.18rem;
        line-height: 1.15;
    }
    .video-meta {
        color: #6c757d;
        font-size: 0.85rem;
        margin-bottom: 0.15rem;
    }
</style>
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-success mb-0">My Uploaded Videos</h2>
    <div style="max-width:300px;" class="ms-3">
        <input type="text" id="videoSearchInput" class="form-control" placeholder="Search videos...">
    </div>
</div>
    @if($videos->count())
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
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
                        <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center p-2 pt-1">
                            <button type="button" class="btn btn-outline-primary btn-xs px-2 py-1" style="font-size:0.85rem;" data-bs-toggle="modal" data-bs-target="#modal-video-{{ $video->id }}">
    <i class="bi bi-eye"></i> View
</button>
                            <button type="button" class="btn btn-outline-secondary btn-xs px-2 py-1 edit-video-btn" style="font-size:0.85rem;" 
    data-bs-toggle="modal" 
    data-bs-target="#editVideoModal"
    data-id="{{ $video->id }}"
    data-title="{{ htmlspecialchars($video->title, ENT_QUOTES) }}"
    data-description="{{ htmlspecialchars($video->description, ENT_QUOTES) }}"
    data-category="{{ $video->category_id }}"
    data-thumbnail="{{ $video->preview_thumbnail ? asset('storage/' . $video->preview_thumbnail) : '' }}">
    <i class="bi bi-pencil"></i> Edit
</button>
                            <form action="{{ route('videos.destroy', $video->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this video?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-xs px-2 py-1" style="font-size:0.85rem;"><i class="bi bi-trash"></i> Delete</button>
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
        <div class="d-flex justify-content-center mt-5 mb-4">
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('videoSearchInput');
    if (!searchInput) return;
    searchInput.addEventListener('input', function() {
        const query = searchInput.value.toLowerCase();
        document.querySelectorAll('.video-row').forEach(function(row) {
            const text = row.innerText.toLowerCase();
            if (text.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
<!-- Edit Video Modal -->
<div class="modal fade" id="editVideoModal" tabindex="-1" aria-labelledby="editVideoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editVideoModalLabel"><i class="bi bi-pencil-square"></i> Edit Video</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editVideoForm" method="POST" enctype="multipart/form-data" action="">
  @csrf
  @method('PUT')
  <input type="hidden" name="video_id" id="edit-video-id">
          <div class="mb-3">
            <label for="edit-title" class="form-label">Title</label>
            <input type="text" class="form-control" id="edit-title" name="title" required>
          </div>
          <div class="mb-3">
            <label for="edit-description" class="form-label">Description</label>
            <textarea class="form-control" id="edit-description" name="description" rows="2" required></textarea>
          </div>
          <div class="mb-3">
            <label for="edit-category" class="form-label">Category</label>
            <select class="form-control" id="edit-category" name="category_id" required>
    @foreach($categories as $category)
      <option value="{{ $category->id }}">{{ $category->name }}</option>
    @endforeach
</select>
          </div>
          <div class="mb-3">
            <label for="edit-video_file" class="form-label">Video File (MP4, AVI, MOV, WMV)</label>
            <input type="file" class="form-control" id="edit-video_file" name="video_file" accept="video/mp4,video/avi,video/mov,video/wmv">
            <div class="form-text" id="edit-video-file-name"></div>
          </div>
          <div class="mb-3">
            <label for="edit-script_file" class="form-label">Script/Transcript File (PDF, DOC, DOCX, TXT)</label>
            <input type="file" class="form-control" id="edit-script_file" name="script_file" accept=".pdf,.doc,.docx,.txt">
            <div class="form-text" id="edit-script-file-name"></div>
          </div>
          <div class="mb-3">
            <label for="edit-voiceover_file" class="form-label">Voiceover File (MP3, WAV) <span class="text-muted small">(optional)</span></label>
            <input type="file" class="form-control" id="edit-voiceover_file" name="voiceover_file" accept="audio/mp3,audio/wav">
            <div class="form-text" id="edit-voiceover-file-name"></div>
          </div>
          <div class="mb-3">
            <label for="edit-preview_thumbnail" class="form-label">Preview Thumbnail (optional)</label>
            <div id="edit-thumbnail-preview-wrapper" style="margin-bottom:0.7rem;">
              <img id="edit-thumbnail-preview-img" src="https://placehold.co/120x80?text=No+Image" alt="Thumbnail Preview" style="width:120px;height:80px;object-fit:cover;border-radius:0.5rem;background:#f3f4f6;display:block;" />
            </div>
            <input type="file" class="form-control" id="edit-preview_thumbnail" name="preview_thumbnail" accept="image/*">
          </div>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Fill edit modal fields
  document.querySelectorAll('.edit-video-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const videoId = btn.getAttribute('data-id');
      document.getElementById('edit-video-id').value = videoId;
      // Set the form action to the update route
      document.getElementById('editVideoForm').action = `/videos/${videoId}`;
      document.getElementById('edit-title').value = btn.getAttribute('data-title');
      document.getElementById('edit-description').value = btn.getAttribute('data-description');
      // Set category dropdown
      const catSel = document.getElementById('edit-category');
      const catVal = btn.getAttribute('data-category');
      if (catSel && catVal) {
        catSel.value = catVal;
      }
      const thumbUrl = btn.getAttribute('data-thumbnail');
      document.getElementById('edit-thumbnail-preview-img').src = thumbUrl || 'https://placehold.co/120x80?text=No+Image';
    });
  });
  // Live preview for thumbnail in edit modal
  const thumbInput = document.getElementById('edit-preview_thumbnail');
  const thumbImg = document.getElementById('edit-thumbnail-preview-img');
  if (thumbInput && thumbImg) {
    thumbInput.addEventListener('change', function(e) {
      if (thumbInput.files && thumbInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          thumbImg.src = e.target.result;
        }
        reader.readAsDataURL(thumbInput.files[0]);
      }
    });
  }
});
</script>
@endsection
