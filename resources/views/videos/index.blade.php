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
                <div class="col-md-6 col-lg-4">
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
@endsection
