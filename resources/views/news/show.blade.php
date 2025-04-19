@extends('layouts.app')

@section('content')
<article class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Article Header -->
            <header class="mb-8">
                <nav class="mb-8">
                    <a href="{{ route('news.index') }}" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to News
                    </a>
                </nav>

                @if($article->category)
                <div class="mb-4">
                    <span class="bg-primary-600 text-white px-3 py-1 rounded-full text-sm">
                        {{ $article->category->name }}
                    </span>
                </div>
                @endif

                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    {{ $article->title }}
                </h1>

                <div class="flex items-center space-x-4 mb-8">
                    <div class="flex items-center">
                        <img src="{{ $article->author->avatar }}" 
                             alt="{{ $article->author->name }}" 
                             class="h-10 w-10 rounded-full">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $article->author->name }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $article->author->role }}
                            </p>
                        </div>
                    </div>
                    <span class="text-gray-300 dark:text-gray-600">|</span>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <time datetime="{{ $article->created_at->toISOString() }}">
                            {{ $article->created_at->format('M d, Y') }}
                        </time>
                        <span class="mx-1">•</span>
                        <span>{{ $article->read_time }} min read</span>
                    </div>
                </div>

                @if($article->image)
                <div class="relative aspect-[21/9] mb-8 rounded-xl overflow-hidden">
                    <img src="{{ $article->image }}" 
                         alt="{{ $article->title }}" 
                         class="w-full h-full object-cover">
                </div>
                @endif
            </header>

            <!-- Article Content -->
            <div class="prose prose-lg dark:prose-invert max-w-none mb-12">
                {!! $article->content !!}
            </div>

            <!-- Social Sharing -->
            <div class="border-t border-gray-200 dark:border-gray-800 pt-8 mb-12">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Share this article
                </h3>
                <div class="flex space-x-4">
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 rounded-lg bg-[#1DA1F2] text-white hover:bg-opacity-90">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                        Twitter
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($article->title) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 rounded-lg bg-[#0A66C2] text-white hover:bg-opacity-90">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                        LinkedIn
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 rounded-lg bg-[#1877F2] text-white hover:bg-opacity-90">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        Facebook
                    </a>
                </div>
            </div>

            <!-- Related Articles -->
            @if(isset($relatedArticles) && $relatedArticles->count() > 0)
            <div class="border-t border-gray-200 dark:border-gray-800 pt-12">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">
                    Related Articles
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach($relatedArticles as $relatedArticle)
                    <a href="{{ route('news.show', $relatedArticle) }}" class="group">
                        <div class="relative aspect-[16/9] mb-4">
                            <img src="{{ $relatedArticle->image }}" 
                                 alt="{{ $relatedArticle->title }}" 
                                 class="w-full h-full object-cover rounded-lg">
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 mb-2">
                            {{ $relatedArticle->title }}
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $relatedArticle->created_at->format('M d, Y') }}
                        </p>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Comments Section -->
            @if(isset($comments) && $comments->count() > 0)
            <div class="border-t border-gray-200 dark:border-gray-800 pt-12">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">
                    Comments
                </h3>
                <div class="space-y-8">
                    @foreach($comments as $comment)
                    <div class="flex space-x-4">
                        <img src="{{ $comment->user->avatar }}" 
                             alt="{{ $comment->user->name }}" 
                             class="h-10 w-10 rounded-full">
                        <div class="flex-1">
                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $comment->user->name }}
                                    </div>
                                    <time class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </time>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300">
                                    {{ $comment->content }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @auth
            <!-- Comment Form -->
            <div class="border-t border-gray-200 dark:border-gray-800 pt-12">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Leave a comment
                </h3>
                <form action="{{ route('news.comment', $article) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <textarea name="content" 
                                  rows="4" 
                                  required
                                  placeholder="Share your thoughts..."
                                  class="form-textarea w-full rounded-lg"></textarea>
                    </div>
                    <button type="submit" class="btn-primary">
                        Post Comment
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </div>
</article>
@endsection
