<div>
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ route('admin.blog.management') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Blog Management
                    </a>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Create Blog Post</h1>
                    <p class="text-gray-600 text-lg">Add a new blog post to your website</p>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6 flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form wire:submit.prevent="store" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                        <input wire:model="title" wire:keyup="generateSlug" type="text" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green" 
                               placeholder="Enter blog post title" required>
                        @error('title') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
                        <input wire:model="slug" type="text" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green" 
                               placeholder="blog-post-slug" required>
                        @error('slug') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">URL-friendly version of the title</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
                        <textarea wire:model="excerpt" rows="3" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green"
                                  placeholder="Brief summary of the post"></textarea>
                        @error('excerpt') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                        <textarea wire:model="content" rows="15" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green" 
                                  placeholder="Write your blog post content here..." required></textarea>
                        @error('content') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                        <input wire:model="featured_image" type="file" accept="image/*" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        @if($featured_image)
                            <img src="{{ $featured_image->temporaryUrl() }}" class="mt-2 h-48 w-auto rounded-lg object-cover">
                        @endif
                        @error('featured_image') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Max size: 2MB</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select wire:model="status" 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                        @error('status') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Published At</label>
                        <input wire:model="published_at" type="datetime-local" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                        @error('published_at') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center">
                            <input wire:model="is_featured" type="checkbox" 
                                   class="h-4 w-4 text-sidebar-green focus:ring-sidebar-green border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Mark as Featured Post</span>
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title (SEO)</label>
                        <input wire:model="meta_title" type="text" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green"
                               placeholder="SEO title for search engines">
                        @error('meta_title') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description (SEO)</label>
                        <textarea wire:model="meta_description" rows="3" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green"
                                  placeholder="SEO description for search engines"></textarea>
                        @error('meta_description') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.blog.management') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-sidebar-green text-white rounded-lg hover:bg-sidebar-green-light transition-colors">
                        Create Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
