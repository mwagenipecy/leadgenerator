<div>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-semibold text-black">Uploaded Documents</h3>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-sidebar-green-100 text-sidebar-green-800">
            {{ $application->documents->count() ?? 0 }} Documents
        </span>
    </div>




    @if($isAvailable)
        <!-- Protected view for available leads -->
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="text-center py-12">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Documents Protected</h3>
                <p class="text-sm text-gray-500 mb-6">Book this lead to view and download application documents.</p>
                
                <!-- Document preview cards (blurred) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                    @for($i = 0; $i < 3; $i++)
                        <div class="bg-white rounded-lg p-4 border border-gray-200 blur-sm">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 bg-sidebar-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Document {{ $i + 1 }}</p>
                                    <p class="text-xs text-gray-500">***.pdf</p>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    @else
        <!-- Full document view for booked leads -->
        @if($application->documents && $application->documents->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($application->documents as $document)
                    <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-200 cursor-pointer">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                @php
                                    $extension = pathinfo($document->file_name, PATHINFO_EXTENSION);
                                @endphp
                                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                    <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @elseif($extension === 'pdf')
                                    <div class="h-12 w-12 bg-sidebar-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @elseif(in_array($extension, ['doc', 'docx']))
                                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-black truncate">{{ $document->document_type ?? 'Document' }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ $document->file_name }}</p>
                                <p class="text-xs text-gray-400">{{ number_format($document->file_size / 1024, 1) }} KB</p>
                            </div>
                        </div>


                        
                        <div class="mt-4 space-y-3">
                            <!-- Document Status -->
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $document->verification_status === 'verified' ? 'bg-green-100 text-green-800' : 
                                       ($document->verification_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-sidebar-green-100 text-sidebar-green-800') }}">
                                    {{ ucfirst($document->verification_status ?? 'pending') }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $document->created_at ? $document->created_at->format('M d, Y') : 'N/A' }}
                                </span>
                            </div>
                            
                            <!-- Document Actions -->
                            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                <button wire:click="downloadDocument({{ $document->id }})" 
                                        class="text-sidebar-green hover:text-sidebar-green-800 text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Download
                                </button>
                                
                                @if($document->verification_status !== 'verified')
                                    <button class="text-green-600 hover:text-green-800 text-sm font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Verify
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Document Summary -->
          
        @else
            <!-- No documents state -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-black">No documents uploaded</h3>
                <p class="mt-1 text-sm text-gray-500">Documents will appear here once uploaded by the applicant.</p>
            </div>
        @endif
    @endif
</div>

</div>
