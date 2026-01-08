<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $lender->company_name }}</h1>
                        <p class="text-gray-600">Lender Details and Associated Users</p>
                    </div>
                    <a href="{{ route('lenders.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                        ← Back to List
                    </a>
                </div>
            </div>

            <!-- Flash Messages -->
            @if (session()->has('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl" role="alert"
                     x-data="{ show: true }" x-show="show" x-transition 
                     x-init="setTimeout(() => show = false, 5000)">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ session('success') }}
                        <button @click="show = false" class="ml-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl" role="alert"
                     x-data="{ show: true }" x-show="show" x-transition 
                     x-init="setTimeout(() => show = false, 8000)">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ session('error') }}
                        <button @click="show = false" class="ml-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl" role="alert">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <strong>Validation Errors:</strong>
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Company Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Company Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Company Name</label>
                                <p class="text-gray-900 font-semibold">{{ $lender->company_name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">License Number</label>
                                <p class="text-gray-900">{{ $lender->license_number ?: 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Contact Person</label>
                                <p class="text-gray-900">{{ $lender->contact_person }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Email</label>
                                <p class="text-gray-900">{{ $lender->email }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Phone</label>
                                <p class="text-gray-900">{{ $lender->phone }}</p>
                            </div>
                            @if($lender->website)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Website</label>
                                <p class="text-gray-900">
                                    <a href="{{ $lender->website }}" target="_blank" class="text-blue-600 hover:underline">{{ $lender->website }}</a>
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Location</h2>
                        <div class="space-y-2">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Address</label>
                                <p class="text-gray-900">{{ $lender->address }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">City</label>
                                    <p class="text-gray-900">{{ $lender->city }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Region</label>
                                    <p class="text-gray-900">{{ $lender->region }}</p>
                                </div>
                            </div>
                            @if($lender->postal_code)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Postal Code</label>
                                <p class="text-gray-900">{{ $lender->postal_code }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($lender->description)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Description</h2>
                        <p class="text-gray-700">{{ $lender->description }}</p>
                    </div>
                    @endif

                    <!-- Associated Users -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-gray-900">Associated Users</h2>
                            @if($lender->isApproved())
                                <button onclick="document.getElementById('add-user-modal').style.display='block'" 
                                        class="bg-sidebar-green text-white px-4 py-2 rounded-lg font-semibold hover:bg-sidebar-green-light transition-colors flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add User
                                </button>
                            @endif
                        </div>
                        @if($users->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Created</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($users as $user)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-sidebar-green to-sidebar-green rounded-full flex items-center justify-center text-white font-bold">
                                                        {{ substr($user->name ?? $user->email, 0, 2) }}
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm font-medium text-gray-900">{{ $user->name ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ $user->email }}</td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ ucfirst($user->role ?? 'user') }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($user->is_active)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-500">
                                                {{ $user->created_at->format('M d, Y') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <p class="text-gray-500">No users associated with this lender yet.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Status</h3>
                        @if($lender->status === 'pending')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                Pending
                            </span>
                        @elseif($lender->status === 'approved')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-800 border border-green-200">
                                Approved
                            </span>
                        @elseif($lender->status === 'rejected')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-red-100 text-red-800 border border-red-200">
                                Rejected
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                Suspended
                            </span>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>
                        <div class="space-y-3">
                            @if($lender->isPending())
                                <button onclick="document.getElementById('approve-modal').style.display='block'" 
                                        class="w-full bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition-colors flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Approve Lender
                                </button>
                                
                                <button onclick="document.getElementById('reject-modal').style.display='block'" 
                                        class="w-full bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors flex items-center justify-center bg-red-600">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Reject Application
                                </button>
                            @endif

                            @if($lender->isApproved())
                                <form action="{{ route('lenders.suspend', $lender->id) }}" method="POST" class="inline-block w-full" onsubmit="return confirm('Are you sure you want to suspend this lender? This will also deactivate their user account.');">
                                    @csrf
                                    <button type="submit" class="w-full bg-yellow-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-yellow-700 transition-colors flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Suspend Lender
                                    </button>
                                </form>
                            @endif

                            @if($lender->isSuspended())
                                <form action="{{ route('lenders.reactivate', $lender->id) }}" method="POST" class="inline-block w-full" onsubmit="return confirm('Are you sure you want to reactivate this lender?');">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition-colors flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Reactivate Lender
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('lenders.delete', $lender->id) }}" method="POST" class="inline-block w-full" onsubmit="return confirm('Are you sure you want to permanently delete this lender? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete Lender
                                </button>
                            </form>
                        </div>

                        <!-- Approve Modal -->
                        <div id="approve-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" style="display: none;">
                            <div class="flex items-center justify-center min-h-screen p-4">
                                <div class="bg-white rounded-xl p-6 w-full max-w-md">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-bold text-gray-900">Approve Lender Application</h3>
                                        <button onclick="document.getElementById('approve-modal').style.display='none'" 
                                                class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-green-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-green-800">Important Notice</p>
                                                <p class="text-sm text-green-700 mt-1">Approving this lender will create a user account for the contact person. Login credentials will be sent via email.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <form action="{{ route('lenders.approve', $lender->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Approval Notes (Optional)</label>
                                            <textarea name="approval_notes" rows="3"
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                                      placeholder="Add any notes or comments about this approval..."></textarea>
                                        </div>
                                        <div class="flex justify-end space-x-3">
                                            <button type="button" onclick="document.getElementById('approve-modal').style.display='none'" 
                                                    class="px-4 py-2 text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                                                Cancel
                                            </button>
                                            <button type="submit" 
                                                    class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                                                Confirm Approval
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Reject Modal -->
                        <div id="reject-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" style="display: none;">
                            <div class="flex items-center justify-center min-h-screen p-4">
                                <div class="bg-white rounded-xl p-6 w-full max-w-md">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-bold text-gray-900">Reject Lender Application</h3>
                                        <button onclick="document.getElementById('reject-modal').style.display='none'" 
                                                class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <form action="{{ route('lenders.reject', $lender->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                                            <textarea name="rejection_reason" rows="3" required
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                                                      placeholder="Please provide a reason for rejection..."></textarea>
                                        </div>
                                        <div class="flex justify-end space-x-3">
                                            <button type="button" onclick="document.getElementById('reject-modal').style.display='none'" 
                                                    class="px-4 py-2 text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                                                Cancel
                                            </button>
                                            <button type="submit" 
                                                    class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                                                Reject
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full mt-2"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Created</p>
                                    <p class="text-xs text-gray-500">{{ $lender->created_at->format('M d, Y g:i A') }}</p>
                                    @if($lender->createdBy)
                                        <p class="text-xs text-gray-400">By: {{ $lender->createdBy->name ?? $lender->createdBy->email ?? 'N/A' }}</p>
                                    @else
                                        <p class="text-xs text-gray-400">By: System</p>
                                    @endif
                                </div>
                            </div>

                            @if($lender->updated_at != $lender->created_at)
                            <div class="flex items-start space-x-3">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mt-2"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                    <p class="text-xs text-gray-500">{{ $lender->updated_at->format('M d, Y g:i A') }}</p>
                                    @if($lender->updatedBy)
                                        <p class="text-xs text-gray-400">By: {{ $lender->updatedBy->name ?? $lender->updatedBy->email ?? 'N/A' }}</p>
                                    @else
                                        <p class="text-xs text-gray-400">By: System</p>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($lender->approved_at)
                            <div class="flex items-start space-x-3">
                                <div class="w-3 h-3 bg-gray-600 rounded-full mt-2"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Approved</p>
                                    <p class="text-xs text-gray-500">{{ $lender->approved_at->format('M d, Y g:i A') }}</p>
                                    @if($lender->approvedBy)
                                        <p class="text-xs text-gray-400">By: {{ $lender->approvedBy->name ?? $lender->approvedBy->email ?? 'N/A' }}</p>
                                    @else
                                        <p class="text-xs text-gray-400">By: System</p>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($lender->isRejected() && $lender->rejection_reason)
                            <div class="flex items-start space-x-3">
                                <div class="w-3 h-3 bg-red-500 rounded-full mt-2"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Rejected</p>
                                    <p class="text-xs text-gray-500">{{ $lender->updated_at->format('M d, Y g:i A') }}</p>
                                    <p class="text-sm text-red-600 mt-1">{{ $lender->rejection_reason }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Documents -->
                    @if($lender->documents && count($lender->documents) > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Documents</h3>
                        <div class="space-y-2">
                            @foreach($lender->documents as $type => $path)
                            <a href="{{ \Illuminate\Support\Facades\Storage::url($path) }}" target="_blank" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <span class="text-sm text-gray-900">{{ ucwords(str_replace('_', ' ', $type)) }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="add-user-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl p-6 w-full max-w-md">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Add User to Lender</h3>
                    <button onclick="document.getElementById('add-user-modal').style.display='none'" 
                            class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('lenders.add-user', $lender->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                            <input type="text" name="name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green"
                                   placeholder="Enter full name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green"
                                   placeholder="Enter email address">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" name="phone"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green"
                                   placeholder="Enter phone number">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                            <select name="role" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                <option value="lender">Lender</option>
                            </select>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked
                                   class="w-4 h-4 text-sidebar-green border-gray-300 rounded focus:ring-sidebar-green">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="document.getElementById('add-user-modal').style.display='none'" 
                                class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-sidebar-green text-white rounded-lg hover:bg-sidebar-green-light transition-colors">
                            Add User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
