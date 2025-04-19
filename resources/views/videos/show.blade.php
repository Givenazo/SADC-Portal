@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Navigation -->
        <div class="mb-8">
            <a href="{{ route('videos.index') }}" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Videos
            </a>
        </div>

        <!-- Video Player Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden mb-8">
            <div class="aspect-w-16 aspect-h-9">
                <video 
                    class="w-full h-full object-cover" 
                    controls 
                    poster="{{ $video->preview_thumbnail ? Storage::url($video->preview_thumbnail) : '' }}"
                >
                    <source src="{{ Storage::url($video->video_path) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>

        <!-- Video Information -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $video->title }}</h1>
                @if($video->category)
                <span class="bg-primary-100 text-primary-800 text-sm font-medium px-3 py-1 rounded-full">
                    {{ $video->category->name }}
                </span>
                @endif
            </div>

            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-6">
                <span>Uploaded {{ \Carbon\Carbon::parse($video->upload_date)->diffForHumans() }}</span>
                <span class="mx-2">•</span>
                <span>By {{ $video->uploader->name ?? 'Unknown User' }}</span>
            </div>

            <div class="prose dark:prose-invert max-w-none mb-6">
                <p>{{ $video->description }}</p>
            </div>

            @if($video->script_path || $video->voiceover_path)
            <div class="border-t dark:border-gray-700 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Additional Resources</h2>
                <div class="space-y-3">
                    @if($video->script_path)
                    <a 
                        href="{{ Storage::url($video->script_path) }}" 
                        class="inline-flex items-center text-sm text-primary-600 dark:text-primary-400 hover:underline"
                        target="_blank"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Download Script
                    </a>
                    @endif

                    @if($video->voiceover_path)
                    <a 
                        href="{{ Storage::url($video->voiceover_path) }}" 
                        class="inline-flex items-center text-sm text-primary-600 dark:text-primary-400 hover:underline"
                        target="_blank"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15.536a5 5 0 001.414 1.414m2.828-9.9a9 9 0 012.728-2.728"/>
                        </svg>
                        Download Voiceover
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>

        @if($video->comments_enabled)
        <!-- Comments Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Comments</h2>
            
            @auth
            <form action="{{ route('videos.comment', $video) }}" method="POST" class="mb-8">
                @csrf
                <div class="mb-4">
                    <textarea 
                        name="comment" 
                        rows="3" 
                        class="form-textarea w-full rounded-lg"
                        placeholder="Add a comment..."
                        required
                    ></textarea>
                </div>
                <button type="submit" class="btn-primary">
                    Post Comment
                </button>
            </form>
            @endauth

            <div class="space-y-6">
                @forelse($video->comments as $comment)
                <div class="flex space-x-4">
                    <div class="flex-shrink-0">
                        <img 
                            src="{{ $comment->user->avatar ?? asset('images/default-avatar.png') }}" 
                            alt="{{ $comment->user->name ?? 'User' }}" 
                            class="h-10 w-10 rounded-full"
                        >
                    </div>
                    <div class="flex-1">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ $comment->user->name ?? 'Anonymous User' }}
                                </span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $comment->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $comment->comment }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400 text-center">
                    No comments yet. Be the first to comment!
                </p>
                @endforelse
            </div>
        </div>
        @endif
    </div>
</div>
@endsection 