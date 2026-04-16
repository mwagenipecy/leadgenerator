<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Blog & News</h1>
            <p class="text-gray-600 text-lg">Stay updated with the latest news and insights from our team.</p>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                <div class="flex-1 relative">
                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.35-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="Search by title, summary, or content..."
                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    >
                </div>
                <div class="flex items-center gap-4">
                    <label class="flex items-center cursor-pointer">
                        <input wire:model.live="featuredOnly" type="checkbox" class="h-4 w-4 text-brand-red focus:ring-brand-red border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Featured Only</span>
                    </label>
                    @if($search || $featuredOnly)
                        <button
                            type="button"
                            wire:click="$set('search', ''); $set('featuredOnly', false)"
                            class="text-sm font-medium text-red-700 hover:text-red-800 underline underline-offset-2">
                            Clear filters
                        </button>
                    @endif
                </div>
            </div>

            <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm text-gray-600">
                <p>
                    Showing <span class="font-semibold text-gray-900">{{ $posts->count() }}</span>
                    of <span class="font-semibold text-gray-900">{{ $posts->total() }}</span> posts
                </p>
                @if($search)
                    <p>Search term: <span class="font-semibold text-gray-900">"{{ $search }}"</span></p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($posts as $post)
                        <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow">
                            @if($post->featured_image)
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" 
                                         class="w-full h-48 object-cover">
                                </a>
                            @endif
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs text-gray-500">{{ $post->published_at->format('M d, Y') }}</span>
                                    @if($post->is_featured)
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded">Featured</span>
                                    @endif
                                </div>
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-red-700 transition-colors line-clamp-2">{{ $post->title }}</h3>
                                </a>
                                @if($post->excerpt)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $post->excerpt }}</p>
                                @endif
                                <div class="flex items-center justify-between text-sm text-gray-500 border-t border-gray-100 pt-4">
                                    <span class="truncate pr-3">By {{ $post->author->name ?? 'Admin' }}</span>
                                    <div class="flex items-center space-x-3">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            {{ number_format($post->views_count) }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                            {{ number_format($post->likes_count) }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                            {{ number_format($post->comments_count) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center text-sm font-semibold text-red-700 hover:text-red-800">
                                        Read article
                                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100">
                            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-red-50 text-red-700 mb-4">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.35-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <p class="text-gray-700 text-lg font-semibold">No posts match your search.</p>
                            <p class="text-gray-500 text-sm mt-2">Try different keywords or clear filters to browse all posts.</p>
                        </div>
                    @endforelse
                </div>
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Featured Posts</h3>
                    <div class="space-y-4">
                        @forelse($featuredPosts as $featured)
                            <a href="{{ route('blog.show', $featured->slug) }}" class="block hover:opacity-75 transition-opacity">
                                @if($featured->featured_image)
                                    <img src="{{ asset('storage/' . $featured->featured_image) }}" alt="{{ $featured->title }}" 
                                         class="w-full h-32 object-cover rounded-lg mb-2">
                                @endif
                                <h4 class="text-sm font-semibold text-gray-900 line-clamp-2">{{ $featured->title }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $featured->published_at->format('M d, Y') }}</p>
                            </a>
                        @empty
                            <p class="text-sm text-gray-500">No featured posts yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
