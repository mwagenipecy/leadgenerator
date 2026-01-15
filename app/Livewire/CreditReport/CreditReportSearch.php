<?php

namespace App\Livewire\CreditReport;

use Livewire\Component;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\ReportLog;
use App\Models\CreditReportSearchLog;
use App\Models\CreditInfoRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class CreditReportSearch extends Component
{
    use WithPagination;
    
    public $fullName = '';
    public $idNumber = '';
    public $idNumberType = 'NationalID';
    public $phoneNumber = '';
    
    public $isSearching = false;
    public $searchResults = [];
    public $errorMessage = '';
    
    public $selectedId = null;
    public $isLoadingReport = false;
    public $reportData = null;
    public $reportError = '';
    public $reportUrl = null;
    public $successMessage = '';
    
    public $currentPage = 1;
    public $perPage = 10;
    
    // Credit Info Requests properties
    public $creditInfoSearch = '';
    public $creditInfoStatusFilter = '';
    public $showCreditInfoModal = false;
    public $selectedCreditRequest = null;
    public $showCreditInfoJsonModal = false;
    public $creditInfoJsonData = '';
    public $creditInfoJsonTitle = '';
    
    // Cached user NIDA number
    protected $userNationalId = null;
    
    protected $rules = [
        'fullName' => 'required_without_all:idNumber,phoneNumber',
    ];
    
    protected $messages = [
        'fullName.required_without_all' => 'Please provide at least a full name, ID number, or phone number to search.',
    ];
    
    public function mount()
    {
        // Store pre-filled values before reset
        $prefilledName = '';
        $prefilledIdNumber = '';
        $prefilledPhone = '';
        
        // Pre-fill form with user profile data
        if (auth()->check()) {
            $user = auth()->user();
            
            // Pre-fill full name from user profile (first_name + last_name)
            if ($user->first_name || $user->last_name) {
                $prefilledName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
            }
            
            // Pre-fill NIDA number from user profile and cache it for credit info requests
            if ($user->nida_number) {
                $prefilledIdNumber = $user->nida_number;
                $this->userNationalId = $user->nida_number;
            }
            
            // Also check UserProfile for national_id if nida_number is not available
            if (!$this->userNationalId && $user->profile && $user->profile->national_id) {
                $this->userNationalId = $user->profile->national_id;
            }
            
            // Also check applications for the user's NIDA number
            if (!$this->userNationalId) {
                $application = \App\Models\Application::where('user_id', $user->id)
                    ->whereNotNull('national_id')
                    ->latest()
                    ->first();
                if ($application && $application->national_id) {
                    $this->userNationalId = $application->national_id;
                }
            }
            
            // Pre-fill phone number if available
            if ($user->phone) {
                $prefilledPhone = $user->phone;
            }
        }
        
        // Reset search state (but keep form fields)
        $this->resetSearchState();
        
        // Restore pre-filled values
        if (!empty($prefilledName)) {
            $this->fullName = $prefilledName;
        }
        if (!empty($prefilledIdNumber)) {
            $this->idNumber = $prefilledIdNumber;
            $this->idNumberType = 'NationalID';
        }
        if (!empty($prefilledPhone)) {
            $this->phoneNumber = $prefilledPhone;
        }
    }
    
    /**
     * Get user's NIDA number for filtering credit info requests
     */
    protected function getUserNationalId()
    {
        if ($this->userNationalId) {
            return $this->userNationalId;
        }
        
        if (!auth()->check()) {
            return null;
        }
        
        $user = auth()->user();
        
        // Try user's nida_number first
        if ($user->nida_number) {
            $this->userNationalId = $user->nida_number;
            return $this->userNationalId;
        }
        
        // Try user profile
        if ($user->profile && $user->profile->national_id) {
            $this->userNationalId = $user->profile->national_id;
            return $this->userNationalId;
        }
        
        // Try user's applications
        $application = \App\Models\Application::where('user_id', $user->id)
            ->whereNotNull('national_id')
            ->latest()
            ->first();
        if ($application && $application->national_id) {
            $this->userNationalId = $application->national_id;
            return $this->userNationalId;
        }
        
        return null;
    }
    
    /**
     * Reset pagination when filters change
     */
    public function updatingCreditInfoSearch()
    {
        $this->resetPage('creditInfoPage');
    }
    
    public function updatingCreditInfoStatusFilter()
    {
        $this->resetPage('creditInfoPage');
    }
    
    /**
     * View credit info request details
     */
    public function viewCreditInfoDetails($requestId)
    {
        $this->selectedCreditRequest = CreditInfoRequest::findOrFail($requestId);
        $this->showCreditInfoModal = true;
    }
    
    /**
     * Close credit info modal
     */
    public function closeCreditInfoModal()
    {
        $this->showCreditInfoModal = false;
        $this->selectedCreditRequest = null;
    }
    
    /**
     * View JSON data
     */
    public function viewCreditInfoJson($data, $title)
    {
        $this->creditInfoJsonData = is_array($data) ? json_encode($data, JSON_PRETTY_PRINT) : $data;
        $this->creditInfoJsonTitle = $title;
        $this->showCreditInfoJsonModal = true;
    }
    
    /**
     * Close JSON modal
     */
    public function closeCreditInfoJsonModal()
    {
        $this->showCreditInfoJsonModal = false;
        $this->creditInfoJsonData = '';
        $this->creditInfoJsonTitle = '';
    }
    
    /**
     * Get credit info requests for the logged-in user (filtered by NIDA number)
     */
    public function getCreditInfoRequestsProperty()
    {
        $nationalId = $this->getUserNationalId();
        
        if (!$nationalId) {
            // If user doesn't have a NIDA number, return empty collection
            return CreditInfoRequest::whereRaw('1 = 0')->paginate(10, ['*'], 'creditInfoPage');
        }
        
        $query = CreditInfoRequest::with('application')
            ->where('national_id', $nationalId);
        
        // Apply search filter
        if ($this->creditInfoSearch) {
            $query->where(function ($q) {
                $q->where('national_id', 'like', '%' . $this->creditInfoSearch . '%')
                  ->orWhere('first_name', 'like', '%' . $this->creditInfoSearch . '%')
                  ->orWhere('last_name', 'like', '%' . $this->creditInfoSearch . '%')
                  ->orWhere('full_name', 'like', '%' . $this->creditInfoSearch . '%')
                  ->orWhere('application_number', 'like', '%' . $this->creditInfoSearch . '%');
            });
        }
        
        // Apply status filter
        if ($this->creditInfoStatusFilter) {
            $query->where('status', $this->creditInfoStatusFilter);
        }
        
        return $query->latest()->paginate(10, ['*'], 'creditInfoPage');
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    
    public function updatedFullName()
    {
        $this->resetSearch();
    }
    
    public function updatedIdNumber()
    {
        $this->resetSearch();
    }
    
    public function updatedPhoneNumber()
    {
        $this->resetSearch();
    }
    
    public function updatedPerPage()
    {
        $this->currentPage = 1;
    }
    
    /**
     * Reset search state (results, errors, etc.) but keep form fields
     */
    private function resetSearchState()
    {
        $this->currentPage = 1;
        $this->searchResults = [];
        $this->selectedId = null;
        $this->reportUrl = null;
        $this->reportData = null;
        $this->reportError = '';
        $this->errorMessage = '';
        $this->successMessage = '';
    }
    
    /**
     * Reset everything including form fields (used when user clears form)
     */
    private function resetSearch()
    {
        $this->resetSearchState();
        // Note: Form fields are intentionally not cleared here to preserve pre-filled data
    }
    
    public function getSearchSummaryProperty()
    {
        $criteria = [];
        
        if (!empty($this->fullName)) {
            $criteria[] = "Name: {$this->fullName}";
        }
        
        if (!empty($this->idNumber)) {
            $criteria[] = "{$this->idNumberType}: {$this->idNumber}";
        }
        
        if (!empty($this->phoneNumber)) {
            $criteria[] = "Phone: {$this->phoneNumber}";
        }
        
        return implode(', ', $criteria);
    }
    
    private function checkUserPermissions()
    {
        if (!auth()->check()) {
            throw new \Exception('You must be logged in to perform this action.');
        }
        
        // Basic permission check - can be expanded based on your requirements
        // This is a placeholder - adjust based on your Account/ReportLog models
    }
    
    public function search()
    {
        try {
            // Check permissions before searching
            $this->checkUserPermissions();
            
            // Validate input
            $this->validate();
            
            // Reset state
            $this->isSearching = true;
            $this->errorMessage = '';
            $this->successMessage = '';
            $this->searchResults = [];
            $this->selectedId = null;
            $this->reportData = null;
            $this->reportUrl = null;
            $this->currentPage = 1;
            
            // Perform search
            $results = $this->searchIndividual();
            $this->searchResults = $results;
            
            // Log search to database
            $this->logSearchToDatabase(count($results), 'success');
            
            // Log search activity to file
            Log::info('Credit report search performed', [
                'user_id' => auth()->id(),
                'search_criteria' => $this->getSearchSummaryProperty(),
                'results_count' => count($results),
            ]);
            
            // Auto-select and generate report if only one result
            if (count($results) === 1 && isset($results[0]['CreditinfoId'])) {
                $this->successMessage = 'Found exactly one match. Generating report...';
                // Store success message before calling getReport (which clears it)
                $autoGenerateMessage = 'Found exactly one match. Generating report...';
                // Automatically generate report for single result
                $this->autoGenerateReport($results[0]['CreditinfoId'], $autoGenerateMessage);
            } elseif (count($results) > 1) {
                $this->successMessage = 'Found ' . count($results) . ' results. Please select one to generate a report.';
            } elseif (count($results) === 0) {
                $this->logSearchToDatabase(0, 'no_results');
                $this->errorMessage = 'No results found for the provided search criteria. Please try different search terms.';
            }

        } catch (\Exception $e) {
            // Log failed search to database
            $this->logSearchToDatabase(0, 'failed', $e->getMessage());
            
            Log::error('Search failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'search_criteria' => $this->getSearchSummaryProperty(),
            ]);
            $this->errorMessage = $this->formatUserFriendlyErrorMessage($e);
        } finally {
            $this->isSearching = false;
        }
    }
    
    /**
     * Log search operation to database
     */
    private function logSearchToDatabase($resultsCount, $status, $errorMessage = null)
    {
        try {
            if (!auth()->check()) {
                return;
            }

            // Prepare search criteria
            $searchCriteria = [
                'full_name' => $this->fullName,
                'id_number' => $this->idNumber,
                'id_number_type' => $this->idNumberType,
                'phone_number' => $this->phoneNumber,
            ];

            // Create search log entry
            CreditReportSearchLog::create([
                'user_id' => auth()->id(),
                'search_full_name' => $this->fullName,
                'search_id_number' => $this->idNumber,
                'search_phone_number' => $this->phoneNumber,
                'search_id_number_type' => $this->idNumberType,
                'search_criteria' => json_encode($searchCriteria),
                'results_count' => $resultsCount,
                'status' => $status,
                'error_message' => $errorMessage,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'searched_at' => now(),
            ]);

            Log::info('Search logged to database', [
                'user_id' => auth()->id(),
                'results_count' => $resultsCount,
                'status' => $status,
            ]);

        } catch (\Exception $e) {
            // Log error but don't fail the search
            Log::error('Failed to log search to database', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
        }
    }
    
    /**
     * Get SOAP endpoint from environment
     * For CB5 API, the endpoint is likely different from MultiConnector
     * Common CB5 endpoints:
     * - /CB5Service.svc
     * - /SearchService.svc
     * - /CB5/SearchService.svc
     * - Or a completely different base URL
     */
    private function getSoapEndpoint()
    {
        // First, check if a specific CB5 endpoint is configured
        $cb5Endpoint = env('CREDITINFO_CB5_ENDPOINT');
        if ($cb5Endpoint) {
            Log::info('Using configured CB5 endpoint', ['endpoint' => $cb5Endpoint]);
            return $cb5Endpoint;
        }
        
        $endpoint = env('CREDITINFO_ENDPOINT') ?: env('SOAP_URL');
        
        // If endpoint contains MultiConnector.svc, the CB5 API is likely on a different endpoint
        if ($endpoint && str_contains($endpoint, 'MultiConnector.svc')) {
            // Extract base URL
            $baseUrl = rtrim($endpoint, '/MultiConnector.svc');
            $baseUrl = rtrim($baseUrl, '/Web/MultiConnector.svc');
            
            // Common CB5 endpoint patterns to try
            $possibleEndpoints = [
                $baseUrl . '/CB5Service.svc',
                $baseUrl . '/Web/CB5Service.svc',
                $baseUrl . '/SearchService.svc',
                $baseUrl . '/Web/SearchService.svc',
                $baseUrl . '/CB5/SearchService.svc',
                $baseUrl . '/CB5Service.svc?wsdl',
                // Also try common production endpoints
                str_replace('idm-stage', 'idm', $baseUrl) . '/CB5Service.svc',
                str_replace('idm-stage', 'idm', $baseUrl) . '/Web/CB5Service.svc',
            ];
            
            Log::warning('MultiConnector endpoint detected - CB5 API likely needs different endpoint', [
                'current_endpoint' => $endpoint,
                'base_url' => $baseUrl,
                'possible_cb5_endpoints' => $possibleEndpoints,
                'instruction' => 'Please set CREDITINFO_CB5_ENDPOINT in .env file with the correct CB5 API endpoint URL'
            ]);
        }
        
        return $endpoint;
    }
    
    /**
     * Get SOAP username from environment
     */
    private function getSoapUsername()
    {
        return env('CREDITINFO_USERNAME') ?: env('SOAP_USERNAME') ?: 'payasyougo';
    }
    
    
    private function getSoapPassword()
    {
        return env('CREDITINFO_PASSWORD') ?: env('SOAP_PASSWORD') ?: 'pay2025';
    }
    
   
    
    private function getConnectorId()
    {
        return env('CREDITINFO_CONNECTOR_ID') ?: env('SOAP_CONNECTOR_ID');
    }
    
    /**
     * Get strategy ID from environment
     */
    private function getStrategyId()
    {
        return env('CREDITINFO_STRATEGY_ID');
    }
    
    private function searchIndividual()
    {
        try {
            $soapUrl = $this->getSoapEndpoint();
            $username = $this->getSoapUsername();
            $password = $this->getSoapPassword();
            
            if (!$soapUrl || !$username || !$password) {
                throw new \Exception('SOAP configuration is incomplete. Please check your environment variables (CREDITINFO_ENDPOINT, CREDITINFO_USERNAME, CREDITINFO_PASSWORD).');
            }
            
            // Build SOAP request for search
            $messageId = Str::uuid()->toString();
            $soapRequest = $this->buildSearchSoapRequest($messageId, $username, $password);
            
            // Use empty header with HTTP Basic Auth (as per user's specification)
            $soapRequest = $this->buildSearchSoapRequestEmptyHeader($messageId, $username, $password);

            // Log the FULL request for debugging
            Log::info('CreditInfo CB5 Search Request', [
                'url' => $soapUrl,
                'username' => $username,
                'soap_action' => 'http://creditinfo.com/CB5/ISearchService/SearchIndividual',
                'request_length' => strlen($soapRequest),
                'request' => $soapRequest
            ]);

            // Use HTTP Basic Auth with empty header SOAP envelope
            $response = Http::withBasicAuth($username, $password)
              ->withHeaders([
                  'Content-Type' => 'text/xml; charset=utf-8',
                  'SOAPAction' => 'http://creditinfo.com/CB5/ISearchService/SearchIndividual',
              ])->timeout(150)
              ->send('POST', $soapUrl, [
                  'body' => $soapRequest
              ]);

            // Log the response
            Log::info('CreditInfo CB5 Search Response', [
                'status' => $response->status(),
                'response_length' => strlen($response->body()),
                'response_preview' => substr($response->body(), 0, 1000)
            ]);
            
            // Check response status
            if ($response->successful()) {
                $results = $this->parseSearchResponse($response->body());
                return $results;
            } else {
                // Parse error response to get fault message
                $errorBody = $response->body();
                $errorMessage = $this->parseSoapFault($errorBody);
                
                // Enhanced error logging
                Log::error('CreditInfo CB5 API Error', [
                    'status' => $response->status(),
                    'endpoint' => $soapUrl,
                    'soap_action' => 'http://creditinfo.com/CB5/ISearchService/SearchIndividual',
                    'error_body' => $errorBody,
                    'parsed_error' => $errorMessage,
                    'note' => 'If this is a 500 error with Client fault, the endpoint might not support CB5 API. Check if CREDITINFO_CB5_ENDPOINT needs to be set to a different URL.'
                ]);
                
                // Provide more helpful error message
                if ($response->status() === 500) {
                    $faultCode = $this->extractFaultCode($errorBody);
                    if ($faultCode === 's:Client') {
                        // Check if we're using MultiConnector endpoint
                        $currentEndpoint = $soapUrl;
                        $isMultiConnector = str_contains($currentEndpoint, 'MultiConnector.svc');
                        
                        if ($isMultiConnector) {
                            $helpMessage = "The MultiConnector endpoint does not support CB5 API format. ";
                            $helpMessage .= "Please configure the CB5 API endpoint in your .env file:\n\n";
                            $helpMessage .= "CREDITINFO_CB5_ENDPOINT=https://idm-stage.creditinfo.co.tz/Web/CB5Service.svc\n\n";
                            $helpMessage .= "Or contact CreditInfo support to get the correct CB5 API endpoint URL for your account.\n\n";
                            $helpMessage .= "Error Code: " . (preg_match('/\[([a-f0-9-]+)\]/', $errorBody, $matches) ? $matches[1] : 'Unknown');
                            
                            throw new \Exception($helpMessage);
                        } else {
                            throw new \Exception('The CB5 API endpoint may not be configured correctly. Please verify the endpoint URL in your environment configuration. Error: ' . ($errorMessage ?: 'Invalid request format or endpoint.'));
                        }
                    }
                }
                
                if ($errorMessage) {
                    throw new \Exception($errorMessage);
                } else {
                    throw new \Exception('Failed to search credit information. API returned status: ' . $response->status());
                }
            }
            
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('CreditInfo search connection error: ' . $e->getMessage());
            throw new \Exception('Unable to connect to credit information service. Please try again later.');
        } catch (\Exception $e) {
            Log::error('Search individual failed: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Build SOAP request for individual search using CB5 API
     */
    private function buildSearchSoapRequest($messageId, $username, $password)
    {
        // Escape XML special characters
        $username = htmlspecialchars($username, ENT_XML1, 'UTF-8');
        $password = htmlspecialchars($password, ENT_XML1, 'UTF-8');
        
        // Build search criteria
        $fullName = htmlspecialchars(trim($this->fullName), ENT_XML1, 'UTF-8');
        $idNumber = htmlspecialchars(trim($this->idNumber), ENT_XML1, 'UTF-8');
        $phoneNumber = htmlspecialchars(trim($this->phoneNumber), ENT_XML1, 'UTF-8');
        $idNumberType = htmlspecialchars($this->idNumberType, ENT_XML1, 'UTF-8');
        
        // Build parameters section (only include non-empty fields)
        // Each parameter is optional and should be in separate lines
        $parametersSection = '';
        if (!empty($fullName)) {
            $parametersSection .= '
                    <!--Optional:-->
                    <sear:FullName>' . $fullName . '</sear:FullName>';
        }
        if (!empty($idNumber)) {
            $parametersSection .= '
                    <!--Optional:-->
                    <sear:IdNumber>' . $idNumber . '</sear:IdNumber>';
        }
        if (!empty($idNumberType)) {
            $parametersSection .= '
                    <!--Optional:-->
                    <sear:IdNumberType>' . $idNumberType . '</sear:IdNumberType>';
        }
        if (!empty($phoneNumber)) {
            $parametersSection .= '
                    <!--Optional:-->
                    <sear:PhoneNumber>' . $phoneNumber . '</sear:PhoneNumber>';
        }
        
        // Build the SOAP envelope using CB5 API structure
        // Try with WSSE Security header first (MultiConnector endpoint might require it)
        // If this doesn't work, we can try empty header with HTTP Basic Auth
        $soapRequest = '<?xml version="1.0" encoding="utf-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:cb5="http://creditinfo.com/CB5" xmlns:sear="http://creditinfo.com/CB5/v5.73/Search" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
   <soapenv:Header>
      <wsse:Security soapenv:mustUnderstand="1">
         <wsse:UsernameToken wsu:Id="UsernameToken-1">
            <wsse:Username>' . $username . '</wsse:Username>
            <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">' . $password . '</wsse:Password>
         </wsse:UsernameToken>
      </wsse:Security>
   </soapenv:Header>
   <soapenv:Body>
      <cb5:SearchIndividual>
         <!--Optional:-->
         <cb5:query>
            <!--Optional:-->
            <sear:Parameters>' . $parametersSection . '
            </sear:Parameters>
         </cb5:query>
      </cb5:SearchIndividual>
   </soapenv:Body>
</soapenv:Envelope>';
        
        return $soapRequest;
    }
    
    /**
     * Build SOAP request with empty header (for HTTP Basic Auth)
     */
    private function buildSearchSoapRequestEmptyHeader($messageId, $username, $password)
    {
        // Escape XML special characters
        $fullName = htmlspecialchars(trim($this->fullName), ENT_XML1, 'UTF-8');
        $idNumber = htmlspecialchars(trim($this->idNumber), ENT_XML1, 'UTF-8');
        $phoneNumber = htmlspecialchars(trim($this->phoneNumber), ENT_XML1, 'UTF-8');
        $idNumberType = htmlspecialchars($this->idNumberType, ENT_XML1, 'UTF-8');
        
        // Build parameters section
        $parametersSection = '';
        if (!empty($fullName)) {
            $parametersSection .= '
                    <!--Optional:-->
                    <sear:FullName>' . $fullName . '</sear:FullName>';
        }
        if (!empty($idNumber)) {
            $parametersSection .= '
                    <!--Optional:-->
                    <sear:IdNumber>' . $idNumber . '</sear:IdNumber>';
        }
        if (!empty($idNumberType)) {
            $parametersSection .= '
                    <!--Optional:-->
                    <sear:IdNumberType>' . $idNumberType . '</sear:IdNumberType>';
        }
        if (!empty($phoneNumber)) {
            $parametersSection .= '
                    <!--Optional:-->
                    <sear:PhoneNumber>' . $phoneNumber . '</sear:PhoneNumber>';
        }
        
        // Build the SOAP envelope with empty header (as per user's example)
        $soapRequest = '<?xml version="1.0" encoding="utf-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:cb5="http://creditinfo.com/CB5" xmlns:sear="http://creditinfo.com/CB5/v5.73/Search">
   <soapenv:Header/>
   <soapenv:Body>
      <cb5:SearchIndividual>
         <!--Optional:-->
         <cb5:query>
            <!--Optional:-->
            <sear:Parameters>' . $parametersSection . '
            </sear:Parameters>
         </cb5:query>
      </cb5:SearchIndividual>
   </soapenv:Body>
</soapenv:Envelope>';
        
        return $soapRequest;
    }
    
    /**
     * Extract fault code from SOAP fault response
     */
    private function extractFaultCode($xmlResponse)
    {
        try {
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($xmlResponse);
            
            if ($xml !== false) {
                $xml->registerXPathNamespace('s', 'http://schemas.xmlsoap.org/soap/envelope/');
                $faultCode = $xml->xpath('//s:faultcode') ?: $xml->xpath('//faultcode');
                
                if ($faultCode && count($faultCode) > 0) {
                    return trim((string)$faultCode[0]);
                }
            }
        } catch (\Exception $e) {
            // Ignore
        }
        
        return null;
    }
    
    /**
     * Parse SOAP fault message from error response
     */
    private function parseSoapFault($xmlResponse)
    {
        try {
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($xmlResponse);
            
            if ($xml !== false) {
                // Register namespaces
                $xml->registerXPathNamespace('s', 'http://schemas.xmlsoap.org/soap/envelope/');
                
                // Try to find faultstring
                $faultString = $xml->xpath('//s:faultstring') ?: $xml->xpath('//faultstring');
                
                if ($faultString && count($faultString) > 0) {
                    $errorMsg = trim((string)$faultString[0]);
                    
                    // Check if it's a technical support code error
                    if (str_contains($errorMsg, 'could not be completed') || str_contains($errorMsg, 'contact technical support')) {
                        // Extract the error code if present
                        if (preg_match('/\[([a-f0-9-]+)\]/', $errorMsg, $matches)) {
                            $errorCode = $matches[1];
                            Log::error('CreditInfo API Error Code', ['error_code' => $errorCode]);
                            return "The credit information service encountered an error (Code: {$errorCode}). This may indicate the CB5 API endpoint is not configured correctly. Please verify the endpoint URL in your environment configuration.";
                        }
                        return 'The credit information service encountered an error. Please verify the CB5 API endpoint configuration.';
                    }
                    
                    return $errorMsg;
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to parse SOAP fault', ['error' => $e->getMessage()]);
        }
        
        return null;
    }
    
    private function formatUserFriendlyErrorMessage(\Exception $e)
    {
        $message = $e->getMessage();
        
        // If the message is already user-friendly (from parseSoapFault), return it as is
        if (str_contains($message, 'credit information service') || 
            str_contains($message, 'contact support') ||
            str_contains($message, 'Please try again')) {
            return $message;
        }
        
        // Map technical errors to user-friendly messages
        if (str_contains($message, 'SOAP') || str_contains($message, 'connection')) {
            return 'Unable to connect to credit information service. Please try again later.';
        }
        
        if (str_contains($message, 'permission') || str_contains($message, 'logged in')) {
            return $message;
        }
        
        if (str_contains($message, 'status: 500')) {
            return 'The credit information service is temporarily unavailable. Please try again later.';
        }
        
        // Return the actual error message if it's descriptive, otherwise generic message
        if (strlen($message) > 50) {
            return $message;
        }
        
        return 'An error occurred while searching. Please try again.';
    }
    
    private function parseSearchResponse($xmlResponse)
    {
        try {
            // Clean the response
            $cleanXml = trim($xmlResponse);
            if (str_starts_with($cleanXml, '"') && str_ends_with($cleanXml, '"')) {
                $cleanXml = substr($cleanXml, 1, -1);
            }
            
            // Remove any BOM or invalid characters
            $cleanXml = trim($cleanXml);
            
            // Parse XML
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($cleanXml);
            
            if ($xml === false) {
                $errors = libxml_get_errors();
                libxml_clear_errors();
                Log::error('XML parsing failed', ['errors' => $errors]);
                throw new \Exception('Failed to parse XML response from credit information service.');
            }
            
            // Register namespaces for CB5 API
            $xml->registerXPathNamespace('s', 'http://schemas.xmlsoap.org/soap/envelope/');
            $xml->registerXPathNamespace('cb5', 'http://creditinfo.com/CB5');
            $xml->registerXPathNamespace('a', 'http://creditinfo.com/CB5/v5.73/Search');
            
            $results = [];
            
            // Extract SearchIndividualResult from response
            $searchResult = $xml->xpath('//cb5:SearchIndividualResult') ?: $xml->xpath('//SearchIndividualResult');
            
            if ($searchResult && count($searchResult) > 0) {
                $resultNode = $searchResult[0];
                
                // Get main CreditinfoId from SearchIndividualResult
                $mainCreditinfoId = (string)($resultNode->xpath('.//a:CreditinfoId')[0] ?? $resultNode->xpath('.//CreditinfoId')[0] ?? '');
                
                // Get Status
                $status = (string)($resultNode->xpath('.//a:Status')[0] ?? $resultNode->xpath('.//Status')[0] ?? '');
                
                Log::info('Search response status', ['status' => $status, 'creditinfo_id' => $mainCreditinfoId]);
                
                // Check if subject was found
                if ($status === 'SubjectFound') {
                    // Extract IndividualRecords
                    $individualRecords = $resultNode->xpath('.//a:SearchIndividualRecord') ?: $resultNode->xpath('.//SearchIndividualRecord');
                    
                    if ($individualRecords && count($individualRecords) > 0) {
                        $seenIds = []; // Track CreditinfoId to avoid duplicates
                        
                        foreach ($individualRecords as $record) {
                            $creditinfoId = (string)($record->xpath('.//a:CreditinfoId')[0] ?? $record->xpath('.//CreditinfoId')[0] ?? $mainCreditinfoId);
                            $fullName = (string)($record->xpath('.//a:FullName')[0] ?? $record->xpath('.//FullName')[0] ?? '');
                            $dateOfBirth = (string)($record->xpath('.//a:DateOfBirth')[0] ?? $record->xpath('.//DateOfBirth')[0] ?? '');
                            $nationalId = (string)($record->xpath('.//a:NationalID')[0] ?? $record->xpath('.//NationalID')[0] ?? '');
                            $address = (string)($record->xpath('.//a:Address')[0] ?? $record->xpath('.//Address')[0] ?? '');
                            $passportNumber = (string)($record->xpath('.//a:PassportNumber')[0] ?? $record->xpath('.//PassportNumber')[0] ?? '');
                            $taxNumber = (string)($record->xpath('.//a:TaxNumber')[0] ?? $record->xpath('.//TaxNumber')[0] ?? '');
                            
                            // Skip if we've already seen this CreditinfoId (duplicate detection)
                            if (!empty($creditinfoId) && in_array($creditinfoId, $seenIds)) {
                                continue;
                            }
                            
                            $result = [
                                'FullName' => $fullName,
                                'DateOfBirth' => $dateOfBirth,
                                'NationalID' => $nationalId,
                                'Address' => $address,
                                'CreditinfoId' => $creditinfoId,
                                'PassportNumber' => $passportNumber,
                                'TaxNumber' => $taxNumber,
                            ];
                            
                            // Only add if we have at least some identifying information
                            if (!empty($result['FullName']) || !empty($result['NationalID']) || !empty($result['CreditinfoId'])) {
                                $results[] = $result;
                                
                                // Track this ID to avoid duplicates
                                if (!empty($creditinfoId)) {
                                    $seenIds[] = $creditinfoId;
                                }
                            }
                        }
                    } else {
                        // If no individual records but status is SubjectFound, try to use main CreditinfoId
                        if (!empty($mainCreditinfoId)) {
                            // Try to get parameters to reconstruct the result
                            $parameters = $resultNode->xpath('.//a:Parameters')[0] ?? $resultNode->xpath('.//Parameters')[0];
                            if ($parameters) {
                                $fullName = (string)($parameters->xpath('.//a:FullName')[0] ?? $parameters->xpath('.//FullName')[0] ?? '');
                                $idNumber = (string)($parameters->xpath('.//a:IdNumber')[0] ?? $parameters->xpath('.//IdNumber')[0] ?? '');
                                
                                $results[] = [
                                    'FullName' => $fullName,
                                    'DateOfBirth' => '',
                                    'NationalID' => $idNumber,
                                    'Address' => '',
                                    'CreditinfoId' => $mainCreditinfoId,
                                    'PassportNumber' => '',
                                    'TaxNumber' => '',
                                ];
                            }
                        }
                    }
                } else {
                    // Status is not SubjectFound - check for errors
                    Log::warning('Search returned non-SubjectFound status', ['status' => $status]);
                    if ($status !== 'SubjectNotFound') {
                        throw new \Exception('Search returned status: ' . $status);
                    }
                }
                
                // If we have exact match by NIDA number and only one result matches the search criteria, prefer it
                if (count($results) > 1 && !empty($this->idNumber)) {
                    $exactMatches = array_filter($results, function($result) {
                        $searchId = str_replace(['-', ' '], '', strtoupper(trim($this->idNumber)));
                        $resultId = str_replace(['-', ' '], '', strtoupper(trim($result['NationalID'] ?? '')));
                        return !empty($result['NationalID']) && $resultId === $searchId;
                    });
                    
                    if (count($exactMatches) === 1) {
                        // Return only the exact match
                        $results = array_values($exactMatches);
                        Log::info('Filtered to exact NIDA match', ['nida' => $this->idNumber]);
                    }
                }
                
                // If we have exact match by full name and only one result, prefer it
                if (count($results) > 1 && !empty($this->fullName)) {
                    $searchName = strtoupper(trim($this->fullName));
                    $exactNameMatches = array_filter($results, function($result) use ($searchName) {
                        $resultName = strtoupper(trim($result['FullName'] ?? ''));
                        return !empty($resultName) && $resultName === $searchName;
                    });
                    
                    if (count($exactNameMatches) === 1) {
                        // Return only the exact match
                        $results = array_values($exactNameMatches);
                        Log::info('Filtered to exact name match', ['name' => $this->fullName]);
                    }
                }
                
            } else {
                // Try alternative parsing - check for error messages
                $faultString = $xml->xpath('//faultstring') ?: $xml->xpath('//s:Fault//faultstring');
                if ($faultString && count($faultString) > 0) {
                    $errorMsg = (string)$faultString[0];
                    Log::warning('CreditInfo API returned fault', ['fault' => $errorMsg]);
                    throw new \Exception('Credit information service returned an error: ' . $errorMsg);
                }
                
                // If no results found, it might be a valid empty result
                Log::info('No SearchIndividualResult found in response');
            }
            
            return $results;
            
        } catch (\Exception $e) {
            Log::error('Parse search response failed: ' . $e->getMessage(), [
                'response_preview' => substr($xmlResponse, 0, 1000)
            ]);
            throw $e;
        }
    }
    
    /**
     * Auto-generate report for single result (from search)
     */
    private function autoGenerateReport($creditinfoId, $initialMessage = '')
    {
        try {
            $this->checkUserPermissions();
            
            $this->isLoadingReport = true;
            $this->reportError = '';
            $this->selectedId = $creditinfoId;
            $this->reportUrl = null;
            
            // Keep the initial message if provided
            if (!empty($initialMessage)) {
                $this->successMessage = $initialMessage;
            }
            
            // Fetch PDF report
            $pdfUrl = $this->fetchPdfReport($creditinfoId);
            
            if ($pdfUrl) {
                $this->reportUrl = $pdfUrl;
                $this->successMessage = 'Credit report generated successfully! You can view or download it below.';
                // Log successful report retrieval
                $this->logReportRetrieval($creditinfoId);
            } else {
                $this->reportError = 'Failed to generate credit report. Please try again.';
                $this->successMessage = ''; // Clear success message on error
                // Log failed report retrieval
                $this->logReportRetrievalFailure($creditinfoId, 'Failed to generate PDF report');
            }
            
        } catch (\Exception $e) {
            Log::error('Auto-generate report failed: ' . $e->getMessage());
            $this->reportError = $this->getUserFriendlyErrorMessage($e);
            $this->successMessage = ''; // Clear success message on error
            // Log failed report retrieval with error
            $this->logReportRetrievalFailure($creditinfoId, $e->getMessage());
        } finally {
            $this->isLoadingReport = false;
        }
    }
    
    /**
     * Manually generate report (from user click)
     */
    public function getReport($creditinfoId)
    {
        try {
            $this->checkUserPermissions();
            
            $this->isLoadingReport = true;
            $this->reportError = '';
            $this->successMessage = '';
            $this->selectedId = $creditinfoId;
            $this->reportUrl = null;
            
            // Fetch PDF report
            $pdfUrl = $this->fetchPdfReport($creditinfoId);
            
            if ($pdfUrl) {
                $this->reportUrl = $pdfUrl;
                $this->successMessage = 'Credit report generated successfully! You can view or download it below.';
                // Log successful report retrieval
                $this->logReportRetrieval($creditinfoId);
            } else {
                $this->reportError = 'Failed to generate credit report. Please try again.';
                // Log failed report retrieval
                $this->logReportRetrievalFailure($creditinfoId, 'Failed to generate PDF report');
            }
            
        } catch (\Exception $e) {
            Log::error('Get report failed: ' . $e->getMessage());
            $this->reportError = $this->getUserFriendlyErrorMessage($e);
            // Log failed report retrieval with error
            $this->logReportRetrievalFailure($creditinfoId, $e->getMessage());
        } finally {
            $this->isLoadingReport = false;
        }
    }
    
    /**
     * Log failed report retrieval
     */
    private function logReportRetrievalFailure($creditinfoId, $errorMessage)
    {
        try {
            if (!auth()->check()) {
                return;
            }

            // Find the individual details from search results
            $individual = collect($this->searchResults)->firstWhere('CreditinfoId', $creditinfoId);
            
            // Prepare search criteria
            $searchCriteria = [
                'full_name' => $this->fullName,
                'id_number' => $this->idNumber,
                'id_number_type' => $this->idNumberType,
                'phone_number' => $this->phoneNumber,
            ];

            // Create report log entry for failure
            ReportLog::create([
                'user_id' => auth()->id(),
                'creditinfo_id' => $creditinfoId,
                'report_url' => null,
                'search_full_name' => $this->fullName,
                'search_id_number' => $this->idNumber,
                'search_phone_number' => $this->phoneNumber,
                'individual_full_name' => $individual['FullName'] ?? null,
                'individual_national_id' => $individual['NationalID'] ?? null,
                'individual_date_of_birth' => $individual['DateOfBirth'] ?? null,
                'search_criteria' => json_encode($searchCriteria),
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'status' => 'failed',
                'error_message' => $errorMessage,
                'retrieved_at' => now(),
            ]);

            Log::info('Failed report retrieval logged', [
                'user_id' => auth()->id(),
                'creditinfo_id' => $creditinfoId,
                'error' => $errorMessage,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to log report retrieval failure', [
                'error' => $e->getMessage(),
                'creditinfo_id' => $creditinfoId,
            ]);
        }
    }
    
    private function getUserFriendlyErrorMessage(\Exception $e)
    {
        $message = $e->getMessage();
        
        if (str_contains($message, 'SOAP')) {
            return 'Unable to connect to credit information service. Please try again later.';
        }
        
        if (str_contains($message, 'permission')) {
            return $message;
        }
        
        return 'An error occurred while fetching the report. Please try again.';
    }
    
    private function str_random($length = 10) 
    {
        return Str::random($length);
    }
    
    private function fetchPdfReport($creditinfoId)
    {
        try {
            $soapUrl = $this->getSoapEndpoint();
            $username = $this->getSoapUsername();
            $password = $this->getSoapPassword();
            
            if (!$soapUrl || !$username || !$password) {
                throw new \Exception('SOAP configuration is incomplete. Please check your environment variables (CREDITINFO_ENDPOINT, CREDITINFO_USERNAME, CREDITINFO_PASSWORD).');
            }
            
            // Build SOAP request for PDF report
            $messageId = Str::uuid()->toString();
            $soapRequest = $this->buildPdfReportSoapRequest($messageId, $creditinfoId, $username, $password);
            
            // Make API call to CB5 API
            // Use HTTP Basic Authentication instead of WSSE Security in SOAP header
            $response = Http::withBasicAuth($username, $password)
              ->withHeaders([
                  'Content-Type' => 'text/xml; charset=utf-8',
                  'SOAPAction' => 'http://creditinfo.com/CB5/ICustomReportService/GetPdfReport',
              ])->timeout(150)
              ->send('POST', $soapUrl, [
                  'body' => $soapRequest
              ]);
            
            if ($response->successful()) {
                $pdfUrl = $this->parsePdfResponse($response->body(), $creditinfoId);
                return $pdfUrl;
            } else {
                Log::error('CreditInfo PDF report API error', [
                    'status' => $response->status(),
                    'body' => substr($response->body(), 0, 500)
                ]);
                throw new \Exception('Failed to generate PDF report. API returned status: ' . $response->status());
            }
            
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('CreditInfo PDF report connection error: ' . $e->getMessage());
            throw new \Exception('Unable to connect to credit information service. Please try again later.');
        } catch (\Exception $e) {
            Log::error('Fetch PDF report failed: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Build SOAP request for PDF report generation using CB5 API
     */
    private function buildPdfReportSoapRequest($messageId, $creditinfoId, $username, $password)
    {
        // Escape XML special characters
        $creditinfoId = htmlspecialchars($creditinfoId, ENT_XML1, 'UTF-8');
        
        // Build SOAP envelope for PDF report request using CB5 API structure
        // Note: Header is empty as per API documentation - authentication is handled via HTTP Basic Auth
        $soapRequest = '<?xml version="1.0" encoding="utf-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:cb5="http://creditinfo.com/CB5" xmlns:cus="http://creditinfo.com/CB5/v5.73/CustomReport">
   <soapenv:Header/>
   <soapenv:Body>
      <cb5:GetPdfReport>
         <!--Optional:-->
         <cb5:parameters>
            <!--Optional:-->
            <cus:Consent>true</cus:Consent>
            <!--Optional:-->
            <cus:IDNumber>' . $creditinfoId . '</cus:IDNumber>
            <!--Optional:-->
            <cus:IDNumberType>CreditinfoId</cus:IDNumberType>
            <!--Optional:-->
            <cus:InquiryReason>ApplicationForCreditOrAmendmentOfCreditTerms</cus:InquiryReason>
            <!--Optional:-->
            <cus:InquiryReasonText>Credit Report Request</cus:InquiryReasonText>
            <!--Optional:-->
            <cus:LanguageCode>en-GB</cus:LanguageCode>
            <!--Optional:-->
            <cus:ReportName>CreditinfoReport</cus:ReportName>
            <!--Optional:-->
            <cus:SubjectType>Individual</cus:SubjectType>
         </cb5:parameters>
      </cb5:GetPdfReport>
   </soapenv:Body>
</soapenv:Envelope>';
        
        return $soapRequest;
    }
    
    private function parsePdfResponse($xmlResponse, $creditinfoId)
    {
        try {
            // Clean the response
            $cleanXml = trim($xmlResponse);
            if (str_starts_with($cleanXml, '"') && str_ends_with($cleanXml, '"')) {
                $cleanXml = substr($cleanXml, 1, -1);
            }
            
            // Check if response is directly base64 encoded PDF (not wrapped in XML)
            // Sometimes the API returns just the base64 string
            if (preg_match('/^[A-Za-z0-9+\/]+=*$/', $cleanXml) && strlen($cleanXml) > 100) {
                // This looks like base64 encoded data
                $pdfContent = base64_decode($cleanXml);
                if ($pdfContent !== false && substr($pdfContent, 0, 4) === '%PDF') {
                    // Valid PDF content
                    $filename = 'credit_report_' . $creditinfoId . '_' . time() . '.pdf';
                    $path = 'credit-reports/' . $filename;
                    
                    Storage::disk('public')->put($path, $pdfContent);
                    $url = Storage::disk('public')->url($path);
                    Log::info('PDF report saved from direct base64 response', ['path' => $path, 'creditinfo_id' => $creditinfoId]);
                    return $url;
                }
            }
            
            // Parse XML
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($cleanXml);
            
            if ($xml === false) {
                // If XML parsing fails, check if it's base64 encoded PDF directly
                $pdfContent = base64_decode($cleanXml);
                if ($pdfContent !== false && substr($pdfContent, 0, 4) === '%PDF') {
                    $filename = 'credit_report_' . $creditinfoId . '_' . time() . '.pdf';
                    $path = 'credit-reports/' . $filename;
                    Storage::disk('public')->put($path, $pdfContent);
                    $url = Storage::disk('public')->url($path);
                    Log::info('PDF report saved from base64 (XML parse failed)', ['path' => $path, 'creditinfo_id' => $creditinfoId]);
                    return $url;
                }
                
                $errors = libxml_get_errors();
                libxml_clear_errors();
                Log::error('PDF response XML parsing failed', ['errors' => $errors, 'response_preview' => substr($cleanXml, 0, 200)]);
                throw new \Exception('Failed to parse PDF response from credit information service.');
            }
            
            // Register namespaces for CB5 API
            $xml->registerXPathNamespace('s', 'http://schemas.xmlsoap.org/soap/envelope/');
            $xml->registerXPathNamespace('cb5', 'http://creditinfo.com/CB5');
            
            // Try to extract PDF from GetPdfReportResponse
            // The response might contain base64 encoded PDF in GetPdfReportResult
            $getPdfReportResult = $xml->xpath('//cb5:GetPdfReportResult') ?: $xml->xpath('//GetPdfReportResult');
            
            if ($getPdfReportResult && count($getPdfReportResult) > 0) {
                $resultNode = $getPdfReportResult[0];
                
                // Get all text content from the result node (might be base64 PDF)
                $resultText = trim((string)$resultNode);
                
                // If the text is long enough and looks like base64, try to decode it
                if (!empty($resultText) && strlen($resultText) > 100) {
                    // Remove any whitespace/newlines
                    $resultText = preg_replace('/\s+/', '', $resultText);
                    
                    // Try to decode as base64
                    $decoded = base64_decode($resultText, true);
                    if ($decoded !== false && substr($decoded, 0, 4) === '%PDF') {
                        // Valid PDF content
                        $filename = 'credit_report_' . $creditinfoId . '_' . time() . '.pdf';
                        $path = 'credit-reports/' . $filename;
                        Storage::disk('public')->put($path, $decoded);
                        $url = Storage::disk('public')->url($path);
                        Log::info('PDF report saved from GetPdfReportResult base64', ['path' => $path, 'creditinfo_id' => $creditinfoId]);
                        return $url;
                    }
                }
                
                // Try to find PDF in child nodes
                $childNodes = $resultNode->children();
                foreach ($childNodes as $child) {
                    $childText = trim((string)$child);
                    if (!empty($childText) && strlen($childText) > 100) {
                        $childText = preg_replace('/\s+/', '', $childText);
                        $decoded = base64_decode($childText, true);
                        if ($decoded !== false && substr($decoded, 0, 4) === '%PDF') {
                            $filename = 'credit_report_' . $creditinfoId . '_' . time() . '.pdf';
                            $path = 'credit-reports/' . $filename;
                            Storage::disk('public')->put($path, $decoded);
                            $url = Storage::disk('public')->url($path);
                            Log::info('PDF report saved from child node base64', ['path' => $path, 'creditinfo_id' => $creditinfoId]);
                            return $url;
                        }
                    }
                }
            }
            
            // Try alternative XPath patterns - look for any text content that might be base64 PDF
            $allTextNodes = $xml->xpath('//text()[string-length(normalize-space(.)) > 100]');
            if ($allTextNodes) {
                foreach ($allTextNodes as $textNode) {
                    $text = preg_replace('/\s+/', '', trim((string)$textNode));
                    if (!empty($text) && strlen($text) > 100) {
                        $decoded = base64_decode($text, true);
                        if ($decoded !== false && substr($decoded, 0, 4) === '%PDF') {
                            $filename = 'credit_report_' . $creditinfoId . '_' . time() . '.pdf';
                            $path = 'credit-reports/' . $filename;
                            Storage::disk('public')->put($path, $decoded);
                            $url = Storage::disk('public')->url($path);
                            Log::info('PDF report saved from text node base64', ['path' => $path, 'creditinfo_id' => $creditinfoId]);
                            return $url;
                        }
                    }
                }
            }
            
            // Try to extract PDF URL (if API returns URL instead of base64)
            $pdfUrl = $xml->xpath('//*[local-name()="Url"]') ?: $xml->xpath('//*[local-name()="URL"]');
            if ($pdfUrl && count($pdfUrl) > 0) {
                $url = (string)$pdfUrl[0];
                Log::info('PDF report URL retrieved', ['url' => $url, 'creditinfo_id' => $creditinfoId]);
                return $url;
            }
            
            // Check for error messages
            $faultString = $xml->xpath('//faultstring') ?: $xml->xpath('//s:Fault//faultstring');
            if ($faultString && count($faultString) > 0) {
                $errorMsg = (string)$faultString[0];
                Log::warning('CreditInfo PDF API returned fault', ['fault' => $errorMsg]);
                throw new \Exception('Credit information service returned an error: ' . $errorMsg);
            }
            
            // No PDF found in response - log the response for debugging
            Log::warning('No PDF data found in response', [
                'creditinfo_id' => $creditinfoId,
                'response_preview' => substr($cleanXml, 0, 500)
            ]);
            throw new \Exception('PDF report not found in API response.');
            
        } catch (\Exception $e) {
            Log::error('Parse PDF response failed: ' . $e->getMessage(), [
                'response_preview' => substr($xmlResponse, 0, 500),
                'creditinfo_id' => $creditinfoId
            ]);
            throw $e;
        }
    }
    
    private function logReportRetrieval($creditinfoId)
    {
        try {
            if (!auth()->check()) {
                Log::warning('Cannot log report retrieval: user not authenticated');
                return;
            }

            // Find the individual details from search results
            $individual = collect($this->searchResults)->firstWhere('CreditinfoId', $creditinfoId);
            
            // Prepare search criteria
            $searchCriteria = [
                'full_name' => $this->fullName,
                'id_number' => $this->idNumber,
                'id_number_type' => $this->idNumberType,
                'phone_number' => $this->phoneNumber,
            ];

            // Create report log entry
            ReportLog::create([
                'user_id' => auth()->id(),
                'creditinfo_id' => $creditinfoId,
                'report_url' => $this->reportUrl,
                'search_full_name' => $this->fullName,
                'search_id_number' => $this->idNumber,
                'search_phone_number' => $this->phoneNumber,
                'individual_full_name' => $individual['FullName'] ?? null,
                'individual_national_id' => $individual['NationalID'] ?? null,
                'individual_date_of_birth' => $individual['DateOfBirth'] ?? null,
                'search_criteria' => json_encode($searchCriteria),
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'status' => 'success',
                'retrieved_at' => now(),
            ]);

            Log::info('Report retrieval logged successfully', [
                'user_id' => auth()->id(),
                'creditinfo_id' => $creditinfoId,
                'report_url' => $this->reportUrl,
            ]);

        } catch (\Exception $e) {
            // Log error but don't fail the report generation
            Log::error('Failed to log report retrieval', [
                'error' => $e->getMessage(),
                'creditinfo_id' => $creditinfoId,
                'user_id' => auth()->id(),
            ]);
        }
    }
    
    private function processUsageDeduction($companyId, $creditinfoId)
    {
        // Process usage deduction if Account model exists
        // This is a placeholder - implement when Account model is available
        /*
        try {
            $account = Account::where('company_id', $companyId)
                ->where('status', 'active')
                ->where('remaining_reports', '>', 0)
                ->where('valid_until', '>', now())
                ->first();
                
            if ($account) {
                $account->decrement('remaining_reports');
                
                AccountUsageLog::create([
                    'account_id' => $account->id,
                    'creditinfo_id' => $creditinfoId,
                    'user_id' => auth()->id(),
                    'used_at' => now(),
                ]);
                
                if ($account->remaining_reports <= 0) {
                    $this->closeAccountDueToUsage($account);
                }
            }
        } catch (\Exception $e) {
            Log::error('Usage deduction failed: ' . $e->getMessage());
        }
        */
    }
    
    private function closeAccountDueToUsage($account)
    {
        // Close account when reports are exhausted
        // This is a placeholder
        // Uncomment when Account model is available:
        // $account->update(['status' => 'exhausted']);
    }
    
    private function checkAndUpdateExpiredAccounts($companyId)
    {
        // Check and update expired accounts
        // This is a placeholder
    }
    
    public function clearSelection()
    {
        $this->selectedId = null;
        $this->reportUrl = null;
        $this->reportData = null;
        $this->reportError = '';
    }
    
    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }
    
    public function nextPage()
    {
        if ($this->currentPage < $this->getTotalPagesProperty()) {
            $this->currentPage++;
        }
    }
    
    public function goToPage($page)
    {
        $totalPages = $this->getTotalPagesProperty();
        if ($page >= 1 && $page <= $totalPages) {
            $this->currentPage = $page;
        }
    }
    
    public function getTotalPagesProperty()
    {
        return ceil(count($this->searchResults) / $this->perPage);
    }
    
    public function getPaginatedResultsProperty()
    {
        $offset = ($this->currentPage - 1) * $this->perPage;
        return array_slice($this->searchResults, $offset, $this->perPage);
    }
    
    public function exportResults()
    {
        if (empty($this->searchResults)) {
            $this->addError('export', 'No search results to export.');
            return;
        }
        
        $filename = 'credit_search_results_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['Full Name', 'Date of Birth', 'National ID', 'Address', 'Creditinfo ID']);
            
            // Add data rows
            foreach ($this->searchResults as $result) {
                fputcsv($file, [
                    $result['FullName'] ?? 'N/A',
                    isset($result['DateOfBirth']) ? \Carbon\Carbon::parse($result['DateOfBirth'])->format('Y-m-d') : 'N/A',
                    $result['NationalID'] ?? 'N/A',
                    $result['Address'] ?? 'N/A',
                    $result['CreditinfoId'] ?? 'N/A',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function getCompanyStatsProperty()
    {
        if (!auth()->check()) {
            return null;
        }
        
        $userId = auth()->id();
        $user = auth()->user();
        
        // Get user-level stats
        $totalReports = ReportLog::where('user_id', $userId)
            ->where('status', 'success')
            ->distinct('creditinfo_id')
            ->count('creditinfo_id');
        
        $reportsThisMonth = ReportLog::where('user_id', $userId)
            ->where('status', 'success')
            ->whereMonth('retrieved_at', now()->month)
            ->whereYear('retrieved_at', now()->year)
            ->count();
        
        // Get company-level stats if user has company_id
        $companyStats = [
            'total_reports_generated' => $totalReports,
            'reports_this_month' => $reportsThisMonth,
        ];
        
        // If user has company_id, get company-wide stats
        if (isset($user->company_id) && $user->company_id) {
            $companyUsers = \App\Models\User::where('company_id', $user->company_id)->pluck('id');
            
            $companyTotalReports = ReportLog::whereIn('user_id', $companyUsers)
                ->where('status', 'success')
                ->distinct('creditinfo_id')
                ->count('creditinfo_id');
            
            $companyReportsThisMonth = ReportLog::whereIn('user_id', $companyUsers)
                ->where('status', 'success')
                ->whereMonth('retrieved_at', now()->month)
                ->whereYear('retrieved_at', now()->year)
                ->count();
            
            $companyStats['company_total_reports'] = $companyTotalReports;
            $companyStats['company_reports_this_month'] = $companyReportsThisMonth;
        }
        
        $companyStats['active_accounts'] = 0; // Placeholder - implement when Account model is available
        $companyStats['total_remaining_reports'] = 0; // Placeholder - implement when Account model is available
        
        return $companyStats;
    }
    
    public function render()
    {
        return view('livewire.credit-report.credit-report-search', [
            'companyStats' => $this->getCompanyStatsProperty(),
            'creditInfoRequests' => $this->creditInfoRequests,
        ]);
    }
}

