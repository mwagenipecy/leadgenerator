<div>
{{-- resources/views/livewire/admin/dashboard.blade.php --}}
<div>
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
                    <p class="text-gray-600 text-lg">Manage lenders, applications, and monitor system performance</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2 bg-green-50 px-4 py-2 rounded-full">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-green-700">All Systems Operational</span>
                    </div>
                    <button class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                        Last 30 days
                    </button>
                </div>
            </div>
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-3xl mb-6 flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        <!-- Key Performance Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Lenders Card -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-blue-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Total Lenders</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ number_format($totalLenders) }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 text-orange-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-semibold">{{ $pendingLenders }} pending</span>
                    </div>
                    <span class="text-sm text-gray-500">{{ $approvedLenders }} approved</span>
                </div>
            </div>

            <!-- Total Applications Card -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-red-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center group-hover:bg-red-200 transition-colors">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Total Applications</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-red-600 transition-colors">{{ number_format($totalApplications) }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 text-green-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-sm font-semibold">{{ $approvedApplications }} approved</span>
                    </div>
                    <span class="text-sm text-gray-500">{{ $conversionRate }}% rate</span>
                </div>
            </div>

            <!-- Total Borrowers Card -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-green-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Total Borrowers</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-green-600 transition-colors">{{ number_format($totalBorrowers) }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 text-green-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <span class="text-sm font-semibold">+8.2%</span>
                    </div>
                    <span class="text-sm text-gray-500">this month</span>
                </div>
            </div>

            <!-- Revenue Card -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-purple-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-purple-600 transition-colors">TSh {{ number_format($totalRevenue/1000000, 1) }}M</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 text-green-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <span class="text-sm font-semibold">+15.3%</span>
                    </div>
                    <span class="text-sm text-gray-500">vs last month</span>
                </div>
            </div>
        </div>

        <!-- Charts and Activity Section -->
        <div class="grid grid-cols-1 lg:grid-cols-7 gap-6 mb-8">
            <!-- Applications Status Chart -->
            <div class="lg:col-span-5 bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Application Status Distribution</h3>
                        <p class="text-gray-600">Monitor loan application processing pipeline</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button class="bg-red-600 text-white px-4 py-2 rounded-xl font-semibold text-sm shadow-lg shadow-red-600/25">Live View</button>
                        <button class="bg-gray-100 text-gray-600 px-4 py-2 rounded-xl font-medium text-sm hover:bg-gray-200 transition-colors">Export</button>
                    </div>
                </div>
                <div class="space-y-4">
                    @foreach($applicationsByStatus as $status => $count)
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 rounded-full 
                                    @if($status === 'approved') bg-green-500
                                    @elseif($status === 'rejected') bg-red-500
                                    @elseif($status === 'under_review') bg-yellow-500
                                    @elseif($status === 'submitted') bg-blue-500
                                    @else bg-gray-500
                                    @endif">
                                </div>
                                <span class="text-sm font-semibold text-gray-700">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full 
                                        @if($status === 'approved') bg-green-500
                                        @elseif($status === 'rejected') bg-red-500
                                        @elseif($status === 'under_review') bg-yellow-500
                                        @elseif($status === 'submitted') bg-blue-500
                                        @else bg-gray-500
                                        @endif" 
                                        style="width: {{ $totalApplications > 0 ? ($count / $totalApplications) * 100 : 0 }}%">
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-gray-900 w-12 text-right">{{ $count }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Activity Feed -->
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Live Activity</h3>
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-xs font-semibold text-green-600">LIVE</span>
                    </div>
                </div>
                <div class="space-y-4 max-h-80 overflow-y-auto">
                    @foreach($recentActivity as $activity)
                        <div class="flex items-start space-x-3 p-3 rounded-2xl hover:bg-gray-50 transition-colors group">
                            <div class="w-10 h-10 bg-{{ $activity['color'] }}-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-{{ $activity['color'] }}-200 transition-colors">
                                <div class="w-3 h-3 bg-{{ $activity['color'] }}-500 rounded-full 
                                    @if($activity['color'] === 'brand-red') animate-pulse @endif">
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900">{{ $activity['message'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $activity['details'] }}</p>
                                <p class="text-xs text-gray-400 mt-2">{{ $activity['time'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Pending Lender Approvals -->
        @if($pendingLenders > 0)
            <div class="mb-8 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-1">Pending Lender Approvals</h3>
                            <p class="text-gray-600">Review and approve new lender registrations</p>
                        </div>
                        <div class="flex items-center space-x-2 bg-orange-100 px-4 py-2 rounded-full">
                            <div class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></div>
                            <span class="text-sm font-medium text-orange-700">{{ $pendingLenders }} pending</span>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lender Information</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Contact Details</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Registration Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">License</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($pendingLendersList as $lender)
                                <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-md">
                                                <span class="text-white text-sm font-bold">{{ substr($lender->company_name, 0, 2) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $lender->company_name }}</div>
                                                <div class="text-xs text-gray-500">Contact: {{ $lender->contact_person }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $lender->email }}</div>
                                        <div class="text-sm text-gray-500">{{ $lender->phone }}</div>
                                    </td>
                                    <td class="px-6 py-6 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $lender->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $lender->created_at->format('g:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-6 whitespace-nowrap">
                                        @if($lender->license_number)
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                                {{ $lender->license_number }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                                No License
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-6 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <button wire:click="approveLender({{ $lender->id }})" 
                                                class="bg-green-600 text-white px-4 py-2 rounded-xl font-medium text-sm hover:bg-green-700 transition-colors">
                                                Approve
                                            </button>
                                            <button wire:click="rejectLender({{ $lender->id }})" 
                                                class="bg-red-600 text-white px-4 py-2 rounded-xl font-medium text-sm hover:bg-red-700 transition-colors">
                                                Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Recent Applications Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-1">Recent Applications</h3>
                        <p class="text-gray-600">Latest loan applications and their processing status</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button class="bg-gray-100 text-gray-600 px-4 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                            Filter
                        </button>
                        <button class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-red-700 transition-all duration-200 shadow-lg shadow-red-600/25">
                            View All Applications
                        </button>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Applicant</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lender</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($recentApplications as $application)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center shadow-md">
                                            <span class="text-white text-sm font-bold">{{ substr($application->first_name, 0, 1) }}{{ substr($application->last_name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900 group-hover:text-red-600 transition-colors">{{ $application->first_name }} {{ $application->last_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $application->email }}</div>
                                            <div class="text-xs text-blue-600 font-medium mt-1">#{{ $application->application_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">TSh {{ number_format($application->requested_amount) }}</div>
                                    <div class="text-xs text-gray-500">{{ $application->requested_tenure_months }} months</div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    @if($application->lender)
                                        <div class="text-sm text-gray-900">{{ $application->lender->company_name }}</div>
                                    @else
                                        <span class="text-xs text-gray-400">Pending Assignment</span>
                                    @endif
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold 
                                        @if($application->status === 'approved') bg-green-100 text-green-800 border border-green-200
                                        @elseif($application->status === 'rejected') bg-red-100 text-red-800 border border-red-200
                                        @elseif($application->status === 'under_review') bg-yellow-100 text-yellow-800 border border-yellow-200
                                        @elseif($application->status === 'submitted') bg-blue-100 text-blue-800 border border-blue-200
                                        @else bg-gray-100 text-gray-800 border border-gray-200
                                        @endif">
                                        @if($application->status === 'approved')
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @elseif($application->status === 'under_review')
                                            <svg class="w-3 h-3 mr-1.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $application->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $application->created_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <button class="text-red-600 hover:text-red-700 p-2 rounded-xl hover:bg-red-50 transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        <button class="text-gray-400 hover:text-gray-600 p-2 rounded-xl hover:bg-gray-100 transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-12 text-center">
                                    <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">No Applications Found</h4>
                                    <p class="text-gray-500">There are no loan applications to display at this time.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</div>
