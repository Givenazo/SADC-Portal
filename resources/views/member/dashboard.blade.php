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
    <div class="member-hero mb-5">
        <div class="d-flex align-items-center mb-3">
            <div style="width:64px;height:64px;" class="d-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle me-3">
                <i class="bi bi-person-circle text-success" style="font-size:2.5rem;"></i>
            </div>
            <div>
                <h2 class="fw-bold mb-1 text-success">Welcome, {{ Auth::user()->name }}!</h2>
                <div class="text-muted">Your Member Dashboard</div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-3 bg-white rounded shadow-sm text-center d-flex flex-column align-items-center justify-content-between h-100">
    <i class="bi bi-collection-play text-primary action-icon"></i>
    <div class="fw-bold">My Uploads</div>
    <div class="text-muted small mb-3">Manage your submitted videos</div>
    <div class="mt-auto pt-2 w-100">
        <a href="{{ route('videos.index') }}" class="gradient-btn w-100">View Uploads</a>
    </div>
</div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-white rounded shadow-sm text-center d-flex flex-column align-items-center justify-content-between h-100">
    <i class="bi bi-upload text-success action-icon"></i>
    <div class="fw-bold">Upload a Video</div>
    <div class="text-muted small mb-3">Share new content with SADC</div>
    <div class="mt-auto pt-2 w-100">
        <a href="{{ route('videos.create') }}" class="gradient-btn w-100">Upload a Video</a>
    </div>
</div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-white rounded shadow-sm text-center d-flex flex-column align-items-center justify-content-between h-100">
    <i class="bi bi-newspaper text-info action-icon"></i>
    <div class="fw-bold">Add News Article</div>
    <div class="text-muted small mb-3">Publish news for your country</div>
    <div class="mt-auto pt-2 w-100">
        <a href="{{ route('news.create') }}" class="gradient-btn w-100">Add News</a>
    </div>
</div>
            </div>
        </div>
    </div>
    <!-- You can add more quick stats or info cards here if desired -->
</div>

@if(isset($activeVideos) && $activeVideos->count())
<div class="container mb-5">
    <h3 class="fw-bold mt-5 mb-3 text-primary">All Active Videos</h3>
    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white rounded shadow-sm">
            <thead class="table-light">
                <tr>
                    <th>Video Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Uploader</th>
                    <th>Upload Date</th>
                    <th>Expiry Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeVideos as $video)
                    <tr>
                        <td>{{ $video->title }}</td>
                        <td style="max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $video->description }}</td>
<td>{{ $video->category ? $video->category->name : 'Unknown' }}</td>
                        <td>{{ $video->uploader ? $video->uploader->name : 'Unknown' }}</td>
                        <td>{{ \Carbon\Carbon::parse($video->upload_date)->format('M d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($video->upload_date)->addDays(30)->format('M d, Y') }}</td>
                        <td>
                            <button type="button" class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modal-video-{{ $video->id }}">
    <i class="bi bi-eye"></i> View
</button>
                            <a href="{{ asset('storage/' . $video->script_path) }}" class="btn btn-outline-info btn-sm me-1" download><i class="bi bi-file-earmark-text"></i> Transcript</a>
@if($video->voiceover_path)
    <a href="{{ asset('storage/' . $video->voiceover_path) }}" class="btn btn-outline-warning btn-sm me-1" download><i class="bi bi-mic"></i> Voice Over</a>
@else
    <button class="btn btn-outline-warning btn-sm me-1" disabled><i class="bi bi-mic"></i> Voice Over</button>
@endif
<a href="{{ asset('storage/' . $video->video_path) }}" class="btn btn-outline-success btn-sm" download><i class="bi bi-download"></i> Download</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

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
