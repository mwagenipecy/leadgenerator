<div class="p-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Customer Help Requests</h1>
        <p class="text-gray-600 text-lg">Review and attend support requests from landing page visitors</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden xl:col-span-2">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-left text-gray-600">
                        <th class="px-5 py-3 font-semibold">Name</th>
                        <th class="px-5 py-3 font-semibold">Email</th>
                        <th class="px-5 py-3 font-semibold">Phone</th>
                        <th class="px-5 py-3 font-semibold">Message</th>
                        <th class="px-5 py-3 font-semibold">Last Update</th>
                        <th class="px-5 py-3 font-semibold">Date</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requests as $helpRequest)
                        <tr class="hover:bg-gray-50/80">
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $helpRequest->name }}</td>
                            <td class="px-5 py-3 text-gray-700">{{ $helpRequest->email }}</td>
                            <td class="px-5 py-3 text-gray-700">{{ $helpRequest->phone ?: '-' }}</td>
                            <td class="px-5 py-3 text-gray-700 max-w-xs">{{ \Illuminate\Support\Str::limit($helpRequest->message, 90) }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ optional($helpRequest->messages->last())->created_at?->format('d M Y, H:i') ?? '-' }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $helpRequest->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-5 py-3">
                                @if($helpRequest->status === 'attended')
                                    <span class="inline-flex items-center rounded-full bg-green-100 text-green-700 px-2.5 py-1 text-xs font-semibold">Attended</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 text-yellow-700 px-2.5 py-1 text-xs font-semibold">New</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end">
                                    <button wire:click="selectRequest({{ $helpRequest->id }})" class="px-3 py-1.5 text-xs rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                                        View
                                    </button>
                                    @if($helpRequest->status === 'attended')
                                        <button wire:click="markAsNew({{ $helpRequest->id }})" class="px-3 py-1.5 text-xs rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                                            Mark New
                                        </button>
                                    @else
                                        <button wire:click="markAsAttended({{ $helpRequest->id }})" class="px-3 py-1.5 text-xs rounded-lg bg-sidebar-green text-white hover:bg-sidebar-green-light">
                                            Mark Attended
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-gray-500">
                                No customer help requests yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        @if($activeRequest)
            <h3 class="text-base font-semibold text-gray-900 mb-2">Conversation #{{ $activeRequest->id }}</h3>
            <div class="text-xs text-gray-500 mb-4">{{ $activeRequest->name }} · {{ $activeRequest->email }}</div>
            <div class="space-y-3 max-h-96 overflow-y-auto bg-gray-50 rounded-lg p-3">
                @foreach($activeRequest->messages as $msg)
                    <div class="{{ $msg->sender_type === 'admin' ? 'text-right' : 'text-left' }}">
                        <div class="inline-block max-w-[90%] rounded-lg px-3 py-2 text-sm {{ $msg->sender_type === 'admin' ? 'bg-sidebar-green text-white' : 'bg-white text-gray-700 border border-gray-200' }}">
                            {{ $msg->message }}
                        </div>
                        <div class="text-[11px] text-gray-400 mt-1">
                            {{ $msg->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-3">
                <textarea wire:model="adminReply" rows="3" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" placeholder="Write a reply..."></textarea>
                @error('adminReply') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                <button wire:click="sendAdminReply" class="mt-2 px-4 py-2 text-sm rounded-lg bg-sidebar-green text-white hover:bg-sidebar-green-light">
                    Send Reply
                </button>
            </div>
        @else
            <div class="text-sm text-gray-500">Select a request to view conversation.</div>
        @endif
    </div>
    </div>

    @if($requests->hasPages())
        <div class="mt-6">
            {{ $requests->links() }}
        </div>
    @endif
</div>
