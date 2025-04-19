@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Welcome back, {{ Auth::user()->name }}!
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Here's what's happening with your account today.
                </p>
            </div>

            <!-- Stats Overview -->
            <div class="dashboard-grid mb-8">
                <div class="stats-card">
                    <div class="stats-value">{{ $totalVideos ?? 0 }}</div>
                    <div class="stats-label">Total Videos</div>
                </div>
                <div class="stats-card">
                    <div class="stats-value">{{ $totalNews ?? 0 }}</div>
                    <div class="stats-label">News Articles</div>
                </div>
                <div class="stats-card">
                    <div class="stats-value">{{ $totalComments ?? 0 }}</div>
                    <div class="stats-label">Comments</div>
                </div>
                <div class="stats-card">
                    <div class="stats-value">{{ $totalSubscribers ?? 0 }}</div>
                    <div class="stats-label">Subscribers</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('videos.create') }}" class="modern-card hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Upload Video</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('news.create') }}" class="modern-card hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-500 flex items-center justify-center">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Create News</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="modern-card hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-500 flex items-center justify-center">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Edit Profile</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('subscriptions.index') }}" class="modern-card hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-500 flex items-center justify-center">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Manage Subscription</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Videos -->
                <div class="modern-card">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Videos</h2>
                    <div class="space-y-4">
                        @forelse($recentVideos ?? [] as $video)
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 h-16 w-16 rounded-lg bg-gray-200 dark:bg-gray-700">
                                <img src="{{ $video->thumbnail }}" alt="{{ $video->title }}" class="h-full w-full object-cover rounded-lg">
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ $video->title }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $video->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">No recent videos</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent News -->
                <div class="modern-card">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent News</h2>
                    <div class="space-y-4">
                        @forelse($recentNews ?? [] as $news)
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 h-16 w-16 rounded-lg bg-gray-200 dark:bg-gray-700">
                                <img src="{{ $news->image }}" alt="{{ $news->title }}" class="h-full w-full object-cover rounded-lg">
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ $news->title }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $news->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">No recent news</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
