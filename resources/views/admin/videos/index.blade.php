@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 fw-bold text-success"><i class="bi bi-collection-play"></i> All Uploaded Videos</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Uploader</th>
                    <th>Country</th>
                    <th>Status</th>
                    <th>Upload Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($videos as $video)
                    <tr>
                        <td>{{ $loop->iteration + ($videos->currentPage() - 1) * $videos->perPage() }}</td>
                        <td>{{ $video->title }}</td>
                        <td>{{ $video->uploader->name ?? 'Unknown' }}</td>
                        <td>{{ $video->country->name ?? 'N/A' }}</td>
                        <td><span class="badge bg-{{ $video->status == 'Published' ? 'success' : 'secondary' }}">{{ $video->status }}</span></td>
                        <td>{{ $video->upload_date ? \Carbon\Carbon::parse($video->upload_date)->format('Y-m-d') : '' }}</td>
                        <td>
                            <a href="{{ route('videos.show', $video->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No videos found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $videos->links() }}
    </div>
</div>
@endsection
