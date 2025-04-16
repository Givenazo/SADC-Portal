@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4 text-primary"><i class="bi bi-newspaper"></i> SADC News</h2>
    <div class="row g-4">
        @forelse($news as $article)
            <div class="col-md-4 col-12">
                <div class="card h-100 shadow-sm">
                    @if($article->image)
                        <img src="{{ asset('storage/' . $article->image) }}" class="card-img-top" style="object-fit:cover;height:200px;" alt="News image">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-secondary mb-2">{{ $article->category }}</span>
                        <h5 class="card-title">{{ $article->title }}</h5>
                        <p class="card-text">{{ $article->summary }}</p>
                        <a href="#" class="btn btn-outline-primary mt-auto">Read More</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No news articles available.</div>
            </div>
        @endforelse
    </div>
    <div class="mt-4">
        {{ $news->links() }}
    </div>
</div>
@endsection
