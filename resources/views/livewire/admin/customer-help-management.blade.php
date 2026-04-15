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

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-left text-gray-600">
                        <th class="px-5 py-3 font-semibold">Name</th>
                        <th class="px-5 py-3 font-semibold">Email</th>
                        <th class="px-5 py-3 font-semibold">Phone</th>
                        <th class="px-5 py-3 font-semibold">Message</th>
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
                            <td colspan="7" class="px-5 py-10 text-center text-gray-500">
                                No customer help requests yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($requests->hasPages())
        <div class="mt-6">
            {{ $requests->links() }}
        </div>
    @endif
</div>
