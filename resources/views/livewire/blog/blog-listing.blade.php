<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Blog & News</h1>
            <p class="text-gray-600 text-lg">Stay updated with the latest news and insights</p>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input wire:model.live="search" type="text" placeholder="Search blog posts..." 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                </div>
                <div class="flex items-center">
                    <label class="flex items-center cursor-pointer">
                        <input wire:model.live="featuredOnly" type="checkbox" class="h-4 w-4 text-brand-red focus:ring-brand-red border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Featured Only</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($posts as $post)
                        <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow">
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
                                    <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-brand-red transition-colors">{{ $post->title }}</h3>
                                </a>
                                @if($post->excerpt)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $post->excerpt }}</p>
                                @endif
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>By {{ $post->author->name ?? 'Admin' }}</span>
                                    <div class="flex items-center space-x-4">
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
                            </div>
                        </div>
                    @empty
                        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-12 text-center">
                            <p class="text-gray-500 text-lg">No blog posts found.</p>
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
