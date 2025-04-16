@extends('layouts.admin')
@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4 text-primary"><i class="bi bi-pencil-square"></i> Edit News Article</h2>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('news.update', $news) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $news->title) }}" required>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" class="form-control" id="category" name="category" value="{{ old('category', $news->category) }}">
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    @if($news->image)
                        <div class="mb-2"><img src="{{ asset('storage/' . $news->image) }}" alt="Current image" style="max-height:80px;"></div>
                    @endif
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
                <div class="mb-3">
                    <label for="summary" class="form-label">Summary</label>
                    <textarea class="form-control" id="summary" name="summary" rows="2" required>{{ old('summary', $news->summary) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="body" class="form-label">Body</label>
                    <textarea class="form-control" id="body" name="body" rows="6" required>{{ old('body', $news->body) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
