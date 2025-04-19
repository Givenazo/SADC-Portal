@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Latest News
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Stay updated with the latest news and announcements
                    </p>
                </div>
                @auth
                    @if(auth()->user()->role && auth()->user()->role->name === 'Admin')
                    <a href="{{ route('news.create') }}" class="btn-primary mt-4 md:mt-0 inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Create News Article
                    </a>
                    @endif
                @endauth
            </div>

            <!-- Search and Filter Section -->
            <div class="mb-8">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <form action="{{ route('news.index') }}" method="GET" class="flex gap-4">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search news..." 
                                   class="form-input flex-1">
                            <button type="submit" class="btn-secondary">
                                Search
                            </button>
                        </form>
                    </div>
                    <div class="flex gap-4">
                        <select name="category" class="form-input" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <select name="sort" class="form-input" onchange="this.form.submit()">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Featured News -->
            @if(isset($featuredNews) && $featuredNews)
            <div class="mb-8">
                <div class="news-card group">
                    <a href="{{ route('news.show', $featuredNews) }}" class="block">
                        <div class="relative aspect-[21/9]">
                            <img src="{{ $featuredNews->image }}" 
                                 alt="{{ $featuredNews->title }}" 
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent">
                                <div class="absolute bottom-6 left-6 right-6">
                                    <div class="flex items-center space-x-2 text-white mb-2">
                                        <span class="bg-primary-600 px-2 py-1 rounded-full text-xs">Featured</span>
                                        <span>{{ $featuredNews->category->name }}</span>
                                    </div>
                                    <h2 class="text-2xl font-bold text-white mb-2">{{ $featuredNews->title }}</h2>
                                    <p class="text-gray-200 line-clamp-2">{{ $featuredNews->excerpt }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endif

            <!-- News Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($news as $article)
                <div class="news-card group">
                    <a href="{{ route('news.show', $article) }}" class="block">
                        <div class="relative aspect-[16/9]">
                            <img src="{{ $article->image }}" 
                                 alt="{{ $article->title }}" 
                                 class="w-full h-full object-cover rounded-t-xl">
                            @if($article->category)
                            <div class="absolute top-4 left-4">
                                <span class="bg-primary-600 text-white px-3 py-1 rounded-full text-sm">
                                    {{ $article->category->name }}
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                <span>{{ $article->created_at->format('M d, Y') }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $article->read_time }} min read</span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                {{ $article->title }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 line-clamp-2">
                                {{ $article->excerpt }}
                            </p>
                            <div class="mt-4 flex items-center">
                                <img src="{{ $article->author->avatar }}" 
                                     alt="{{ $article->author->name }}" 
                                     class="h-8 w-8 rounded-full">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $article->author->name }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $article->author->role }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No news articles found</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Get started by creating a new article.
                    </p>
                    @auth
                        @if(auth()->user()->role && auth()->user()->role->name === 'Admin')
                        <div class="mt-6">
                            <a href="{{ route('news.create') }}" class="btn-primary">
                                Create News Article
                            </a>
                        </div>
                        @endif
                    @endauth
                </div>
                @endforelse
            </div>

            <!-- Load More -->
            @if($news->hasMorePages())
            <div class="mt-8 text-center">
                <button id="load-more" 
                        class="btn-secondary"
                        data-page="{{ $news->currentPage() }}"
                        data-last-page="{{ $news->lastPage() }}">
                    Load More Articles
                </button>
            </div>
            @endif

            <!-- Newsletter Subscription -->
            <div class="mt-16 bg-primary-600 rounded-2xl p-8 md:p-12">
                <div class="max-w-2xl mx-auto text-center">
                    <h2 class="text-2xl font-bold text-white mb-4">
                        Stay Updated
                    </h2>
                    <p class="text-primary-100 mb-6">
                        Subscribe to our newsletter to receive the latest news and updates directly in your inbox.
                    </p>
                    @if(session('success'))
                    <div class="mb-6 bg-green-500 text-white px-4 py-2 rounded-lg">
                        {{ session('success') }}
                    </div>
                    @endif
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                        @csrf
                        <input type="email" 
                               name="email" 
                               required
                               placeholder="Enter your email" 
                               class="form-input flex-1 @error('email') border-red-500 @enderror">
                        @error('email')
                        <p class="mt-1 text-sm text-red-200">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="btn-secondary bg-white text-primary-600 hover:bg-primary-50">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle filter changes
    const filterForms = document.querySelectorAll('select[onchange="this.form.submit()"]');
    filterForms.forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });

    // Handle load more
    const loadMoreBtn = document.getElementById('load-more');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', async function() {
            const page = parseInt(this.dataset.page) + 1;
            const lastPage = parseInt(this.dataset.lastPage);
            
            try {
                const response = await fetch(`{{ route('news.index') }}?page=${page}`);
                const data = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(data, 'text/html');
                const newArticles = doc.querySelectorAll('.news-card');
                
                const newsGrid = document.querySelector('.grid');
                newArticles.forEach(article => {
                    newsGrid.appendChild(article);
                });

                this.dataset.page = page;
                if (page >= lastPage) {
                    this.remove();
                }
            } catch (error) {
                console.error('Error loading more articles:', error);
            }
        });
    }
});
</script>
@endpush
