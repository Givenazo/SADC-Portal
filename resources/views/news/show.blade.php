@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-12">
            <div class="card shadow-sm">
                @if($news->image)
                    <img src="{{ asset('storage/' . $news->image) }}" class="card-img-top" style="object-fit:cover;max-height:350px;" alt="News image">
                @endif
                <div class="card-body">
                    <span class="badge bg-secondary mb-2">{{ $news->category }}</span>
                    <h2 class="card-title mb-3">{{ $news->title }}</h2>
                    <p class="text-muted mb-2">{{ $news->created_at->format('F j, Y') }}</p>
                    <p class="lead">{{ $news->summary }}</p>
                    <hr>
                    <div class="mb-2" style="white-space:pre-line;">{!! nl2br(e($news->body)) !!}</div>
                    <a href="{{ route('news.index') }}" class="btn btn-outline-primary mt-4">&larr; Back to News</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
