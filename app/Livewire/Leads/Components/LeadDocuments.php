<?php

namespace App\Livewire\Leads\Components;

use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class LeadDocuments extends Component
{
    public $application;
    public $isAvailable;

    public function mount($application, $isAvailable = false)
    {
        $this->application = $application;
        $this->isAvailable = $isAvailable;
    }

    public function downloadDocument($documentId)
    {
        // Check if user has access to download documents
        if ($this->isAvailable) {
            session()->flash('error', 'You must book this lead to download documents.');
            return;
        }
    
        try {
            // Find the document
            $document = ApplicationDocument::find($documentId);
            
            if (!$document) {
                session()->flash('error', 'Document not found.');
                return;
            }
    
            // Verify user has permission to download this document
            $application = Application::find($document->application_id);
            
            // if (!$application || $application->lender_id !== Auth::user()->lender_id || $application->booking_status !== 'booked') {
            //     session()->flash('error', 'You do not have permission to download this document.');
            //     return;
            // }
    
            // Extract the relative path from file_path (remove 'application-documents/' prefix if present)
            $relativePath = $document->file_path;
            if (str_starts_with($relativePath, 'application-documents/')) {
                $relativePath = $relativePath;
            } else {
                // If the path doesn't include the folder, add it
                $relativePath = 'application-documents/' . basename($relativePath);
            }
    
            // Check if file exists using public disk
            if (!Storage::disk('public')->exists($relativePath)) {
                // Try alternative path structures
                $alternativePaths = [
                    $relativePath,
                    'application-documents/' . basename($document->file_path),
                    basename($document->file_path)
                ];
                
                $fileFound = false;
                foreach ($alternativePaths as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        $relativePath = $path;
                        $fileFound = true;
                        break;
                    }
                }
                
                if (!$fileFound) {
                    session()->flash('error', 'Document file not found on server.');
                   
                    return;
                }
            }
    
            // Get the full system path
            $fullPath = Storage::disk('public')->path($relativePath);
            
            // Verify file exists on filesystem
            if (!file_exists($fullPath)) {
                session()->flash('error', 'Document file not accessible.');
                return;
            }
    
            // Create safe filename for download
            $documentType = str_replace('_', '-', $document->document_type);
            $originalName = pathinfo($document->document_name, PATHINFO_FILENAME);
            $extension = $document->file_type;
            
            // Clean the original name
            $safeName = preg_replace('/[^a-zA-Z0-9\-_\.]/', '_', $originalName);
            $safeName = trim($safeName, '_');
            
            // If safe name is empty, use document type
            if (empty($safeName)) {
                $safeName = $documentType;
            }
            
            $downloadFilename = $documentType . '_' . $safeName . '.' . $extension;
    
            // Use Laravel's download response for better performance
            return Storage::disk('public')->download($relativePath, $downloadFilename, [
                'Content-Type' => $document->mime_type,
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
            
        } catch (\Exception $e) {
           
            
            session()->flash('error', 'Failed to download document. Please try again or contact support.');
            return;
        }
    }




    public function render()
    {
        return view('livewire.leads.components.lead-documents');
    }
}