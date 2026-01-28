<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 lg:px-8">
        <!-- Back Button -->
        <a href="{{ route('blog.index') }}" class="inline-flex items-center text-brand-red hover:text-brand-red/80 mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Blog
        </a>

        <!-- Blog Post -->
        <article class="bg-white rounded-2xl shadow-sm overflow-hidden mb-8">
            @if($post->featured_image)
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" 
                     class="w-full h-96 object-cover">
            @endif
            <div class="p-8">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <span>{{ $post->published_at->format('F d, Y') }}</span>
                        <span>•</span>
                        <span>By {{ $post->author->name ?? 'Admin' }}</span>
                    </div>
                    @if($post->is_featured)
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">Featured</span>
                    @endif
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>
                @if($post->excerpt)
                    <p class="text-xl text-gray-600 mb-6">{{ $post->excerpt }}</p>
                @endif
                <div class="prose max-w-none mb-8">
                    {!! nl2br(e($post->content)) !!}
                </div>
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div class="flex items-center space-x-6 text-sm text-gray-500">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ number_format($post->views_count) }} views
                        </span>
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            {{ number_format($post->comments_count) }} comments
                        </span>
                    </div>
                    @auth
                        <button wire:click="toggleLike" 
                                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ $isLiked ? 'bg-brand-red text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <svg class="w-5 h-5 mr-2 {{ $isLiked ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            {{ number_format($post->likes_count) }}
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            {{ number_format($post->likes_count) }}
                        </a>
                    @endauth
                </div>
            </div>
        </article>

        <!-- Comments Section -->
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Comments ({{ number_format($post->comments_count) }})</h2>
            
            @auth
                <!-- Add Comment Form -->
                <form wire:submit.prevent="addComment" class="mb-8">
                    <textarea wire:model="commentContent" rows="4" 
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-red focus:border-brand-red mb-3"
                              placeholder="Write your comment..."></textarea>
                    @error('commentContent') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    <button type="submit" class="px-6 py-2 bg-brand-red text-white rounded-lg hover:bg-brand-red/90 transition-colors">
                        Post Comment
                    </button>
                </form>
            @else
                <div class="mb-8 p-4 bg-gray-50 rounded-lg text-center">
                    <p class="text-gray-600 mb-2">Please <a href="{{ route('login') }}" class="text-brand-red hover:underline">login</a> to comment.</p>
                </div>
            @endauth

            <!-- Comments List -->
            <div class="space-y-6">
                @forelse($post->comments as $comment)
                    <div class="border-b border-gray-200 pb-6 last:border-0">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-brand-red rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr($comment->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $comment->user->name ?? 'Anonymous' }}</h4>
                                        <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <p class="text-gray-700 mb-3">{{ $comment->content }}</p>
                                @auth
                                    <button wire:click="setReplyMode('{{ $comment->id }}')" 
                                            class="text-sm text-brand-red hover:text-brand-red/80">
                                        Reply
                                    </button>
                                    @if($parentCommentId === $comment->id)
                                        <form wire:submit.prevent="replyToComment('{{ $comment->id }}')" class="mt-3">
                                            <textarea wire:model="replyContent" rows="3" 
                                                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-brand-red focus:border-brand-red mb-2"
                                                      placeholder="Write your reply..."></textarea>
                                            <div class="flex space-x-2">
                                                <button type="submit" class="px-4 py-2 bg-brand-red text-white rounded-lg hover:bg-brand-red/90 text-sm">Post Reply</button>
                                                <button type="button" wire:click="cancelReply" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm">Cancel</button>
                                            </div>
                                        </form>
                                    @endif
                                @endauth
                                <!-- Replies -->
                                @if($comment->replies->count() > 0)
                                    <div class="mt-4 ml-8 space-y-4 border-l-2 border-gray-200 pl-4">
                                        @foreach($comment->replies as $reply)
                                            <div>
                                                <div class="flex items-start space-x-3">
                                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-gray-700 text-xs font-semibold">
                                                        {{ substr($reply->user->name ?? 'U', 0, 1) }}
                                                    </div>
                                                    <div class="flex-1">
                                                        <h5 class="font-semibold text-sm text-gray-900">{{ $reply->user->name ?? 'Anonymous' }}</h5>
                                                        <p class="text-xs text-gray-500 mb-1">{{ $reply->created_at->diffForHumans() }}</p>
                                                        <p class="text-sm text-gray-700">{{ $reply->content }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No comments yet. Be the first to comment!</p>
                @endforelse
            </div>
        </div>

        <!-- Related Posts -->
        @if($relatedPosts->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Posts</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $related)
                        <a href="{{ route('blog.show', $related->slug) }}" class="block hover:opacity-75 transition-opacity">
                            @if($related->featured_image)
                                    <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" 
                                         class="w-full h-40 object-cover rounded-lg mb-3">
                            @endif
                            <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">{{ $related->title }}</h3>
                            <p class="text-xs text-gray-500">{{ $related->published_at->format('M d, Y') }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
