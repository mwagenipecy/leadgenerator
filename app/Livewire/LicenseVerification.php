<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LicenseVerification extends Component
{
    public $licenseNumber = '';
    public $verificationResult = null;
    public $isLoading = false;
    public $error = null;
    public $showResult = false;
    public $rawResponse = '';

    protected $rules = [
        'licenseNumber' => 'required|string|min:8|max:20|regex:/^[A-Za-z0-9]+$/',
    ];

    protected $messages = [
        'licenseNumber.required' => 'License number is required.',
        'licenseNumber.min' => 'License number must be at least 8 characters.',
        'licenseNumber.max' => 'License number cannot exceed 20 characters.',
        'licenseNumber.regex' => 'License number must contain only letters and numbers.',
    ];

    public function mount()
    {
        // Initialize with empty state
        $this->resetForm();
    }

    public function updatedLicenseNumber()
    {
        // Real-time validation
        $this->validateOnly('licenseNumber');
    }

    public function verifyLicense()
    {
        $this->validate();
        
        $this->isLoading = true;
        $this->error = null;
        $this->verificationResult = null;
        $this->showResult = false;
        $this->rawResponse = '';

        try {
            $messageId = Str::uuid()->toString();
            $dataId = Str::uuid()->toString();
            
            // Get SOAP configuration (same as motor vehicle component)
            $soapUrl = config('services.soap.url') ?? env('SOAP_URL');
            $soapUsername = env('TRA_USER_NAME');
            $soapPassword = env('TRA_PASSWORD');
            $connectorId = env('SOAP_LICENCE_CONNECTOR_ID');
            
            // Validate configuration
            if (empty($soapUrl)) {
                throw new \Exception('SOAP_URL is not configured. Please check your .env file.');
            }
            
            if (empty($soapUsername) || empty($soapPassword)) {
                throw new \Exception('SOAP credentials are not configured. Please check your .env file.');
            }
            
            if (empty($connectorId)) {
                throw new \Exception('SOAP_CONNECTOR_ID is not configured. Please check your .env file.');
            }
            
            $soapRequest = $this->buildSoapRequest($messageId, $dataId, $this->licenseNumber, $soapUsername, $soapPassword, $connectorId);
            
            Log::info('TRA License: Sending SOAP request', [
                'license_number' => $this->licenseNumber,
                'message_id' => $messageId,
                'username' => $soapUsername,
                'connector_id' => $connectorId,
            ]);
            
            $response = Http::withHeaders([
                'Content-Type' => 'text/xml;charset=UTF-8',
                'SOAPAction' => 'http://creditinfo.com/schemas/2012/09/MultiConnector/MultiConnectorService/Query',
                'Authorization' => 'WSSE profile="UsernameToken"',
                'Username' => $soapUsername,
                'Password' => $soapPassword,
            ])->timeout(150)
              ->withBody($soapRequest, 'text/xml')
              ->post($soapUrl);

            $this->rawResponse = $response->body();

            // Log the response for debugging
            Log::info('TRA License: Response received', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'response_length' => strlen($response->body()),
                'license_number' => $this->licenseNumber,
            ]);
            
            Log::debug('TRA License: Full response body', [
                'response_body' => $response->body(),
            ]);

            if ($response->successful()) {
                $parsedResult = $this->parseResponse($response->body());
                
                if ($parsedResult) {
                    $this->verificationResult = $parsedResult;
                    $this->showResult = true;
                    Log::info('TRA License: Successfully parsed license data', [
                        'license_no' => $parsedResult['license_no'] ?? 'N/A',
                        'driver_name' => $parsedResult['driver_name'] ?? 'N/A',
                    ]);
                } else {
                    $this->error = 'No license information found for the provided number.';
                    Log::warning('TRA License: No license data found in response', [
                        'response_preview' => substr($response->body(), 0, 1000),
                    ]);
                }
            } else {
                $this->error = 'Failed to verify license. HTTP Status: ' . $response->status();
                Log::error('TRA License: HTTP error response', [
                    'status' => $response->status(),
                    'response_body' => $response->body(),
                ]);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $this->error = 'Connection error. Please check your internet connection and try again.';
        } catch (\Exception $e) {
            $this->error = 'An unexpected error occurred: ' . $e->getMessage();
            Log::error('TRA License: Exception occurred', [
                'license_number' => $this->licenseNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        } finally {
            $this->isLoading = false;
        }
    }

    private function buildSoapRequest($messageId, $dataId, $licenseNumber, $username, $password, $connectorId)
    {
        // Escape XML special characters
        $username = htmlspecialchars($username, ENT_XML1, 'UTF-8');
        $password = htmlspecialchars($password, ENT_XML1, 'UTF-8');
        $connectorId = htmlspecialchars($connectorId, ENT_XML1, 'UTF-8');
        $licenseNumberEscaped = htmlspecialchars($licenseNumber, ENT_XML1, 'UTF-8');
        
        // Use the same MultiConnector structure that works for Motor Vehicle
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mul="http://creditinfo.com/schemas/2012/09/MultiConnector" xmlns:req="http://creditinfo.com/schemas/2012/09/MultiConnector/Messages/Request">
   <soapenv:Header>
      <wsse:Security soapenv:mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
         <wsse:UsernameToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
            <wsse:Username>{$username}</wsse:Username>
            <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">{$password}</wsse:Password>
         </wsse:UsernameToken>
      </wsse:Security>
   </soapenv:Header>
   <soapenv:Body>
      <mul:Query>
         <mul:request>
            <mul:MessageId>{$messageId}</mul:MessageId>
            <mul:RequestXml>
               <mul:RequestXml>
                  <req:connector id="{$connectorId}">
                     <req:data id="{$dataId}">
                        <request xmlns="http://creditinfo.com/schemas/2012/09/MultiConnector/Connectors/TZA/TRAGetDrivingLicenseDetails/Request">
                           <DrivingLicenseNumber>{$licenseNumberEscaped}</DrivingLicenseNumber>
                        </request>
                     </req:data>
                  </req:connector>
               </mul:RequestXml>
            </mul:RequestXml>
         </mul:request>
      </mul:Query>
   </soapenv:Body>
</soapenv:Envelope>
XML;
    }

    private function parseResponse($xmlResponse)
    {
        try {
            // Log the response body being parsed
            Log::debug('TRA License: Parsing response', [
                'response_body_length' => strlen($xmlResponse),
                'response_preview' => substr($xmlResponse, 0, 500),
            ]);
            
            // Remove any BOM or invalid characters
            $xmlResponse = trim($xmlResponse);
            
            $xml = simplexml_load_string($xmlResponse);
            if ($xml === false) {
                throw new \Exception('Failed to parse XML response');
            }
            
            // Find the inner response node that contains the actual license data
            // The structure is: response > connector > data > response (inner one with license data)
            // We need to get the response node that's inside a data element
            $responseNodes = $xml->xpath('//*[local-name()="data"]/*[local-name()="response"]');
            
            // If that doesn't work, try getting all response nodes and take the last one (innermost)
            if (empty($responseNodes)) {
                $allResponseNodes = $xml->xpath('//*[local-name()="response"]');
                $licenseData = !empty($allResponseNodes) ? end($allResponseNodes) : null;
            } else {
                $licenseData = $responseNodes[0] ?? null;
            }
            
            Log::info('TRA License: Response parsing', [
                'found_inner_response_nodes' => count($responseNodes),
                'has_license_data' => $licenseData !== null,
            ]);
            
            if ($licenseData) {
                // Log a sample of the data to verify we got the right node
                Log::debug('TRA License: Sample parsed data', [
                    'license_no' => (string) ($licenseData->LicenseNo ?? 'not found'),
                    'driver_name' => (string) ($licenseData->DriverName ?? 'not found'),
                ]);
                $result = [
                    'license_no' => (string)$licenseData->LicenseNo ?? 'N/A',
                    'driver_name' => (string)$licenseData->DriverName ?? 'N/A',
                    'driver_age' => (string)$licenseData->DriverAge ?? 'N/A',
                    'is_expired' => filter_var($licenseData->IsExpired ?? false, FILTER_VALIDATE_BOOLEAN),
                    'license_categories' => (string)$licenseData->LicenseCategories ?? 'N/A',
                    'issue_date' => (string)$licenseData->IssueDate ?? 'N/A',
                    'driver_picture' => (string)$licenseData->DriverPicture ?? null,
                ];
                
                // Format the issue date if available
                if ($result['issue_date'] !== 'N/A') {
                    try {
                        $result['issue_date_formatted'] = Carbon::parse($result['issue_date'])->format('F d, Y');
                        $result['issue_date_iso'] = Carbon::parse($result['issue_date'])->toISOString();
                    } catch (\Exception $e) {
                        $result['issue_date_formatted'] = $result['issue_date'];
                        $result['issue_date_iso'] = null;
                    }
                } else {
                    $result['issue_date_formatted'] = 'N/A';
                    $result['issue_date_iso'] = null;
                }
                
                // Process license categories
                if ($result['license_categories'] !== 'N/A') {
                    $result['license_categories_array'] = array_filter(
                        array_map('trim', preg_split('/\s+/', $result['license_categories']))
                    );
                } else {
                    $result['license_categories_array'] = [];
                }
                
                // Validate base64 image
                if ($result['driver_picture']) {
                    // Check if it's valid base64
                    if (base64_decode($result['driver_picture'], true) === false) {
                        $result['driver_picture'] = null;
                    }
                }
                
                return $result;
            }
            
            Log::warning('TRA License: Could not find license data in XML', [
                'response_body_preview' => substr($xmlResponse, 0, 1000),
            ]);
            
            return null;
        } catch (\Exception $e) {
            Log::error('TRA License: Parse exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'response_body_preview' => substr($xmlResponse, 0, 1000),
            ]);
            throw new \Exception('Failed to parse response: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->licenseNumber = '';
        $this->verificationResult = null;
        $this->error = null;
        $this->showResult = false;
        $this->rawResponse = '';
        $this->resetErrorBag();
    }

    public function clearResults()
    {
        $this->verificationResult = null;
        $this->error = null;
        $this->showResult = false;
        $this->rawResponse = '';
    }

    public function downloadResult()
    {
        if (!$this->verificationResult) {
            return;
        }

        $content = "DRIVING LICENSE VERIFICATION REPORT\n";
        $content .= "==================================\n\n";
        $content .= "License Number: " . $this->verificationResult['license_no'] . "\n";
        $content .= "Driver Name: " . $this->verificationResult['driver_name'] . "\n";
        $content .= "Age: " . $this->verificationResult['driver_age'] . " years\n";
        $content .= "Status: " . ($this->verificationResult['is_expired'] ? 'Expired' : 'Valid') . "\n";
        $content .= "Issue Date: " . $this->verificationResult['issue_date_formatted'] . "\n";
        $content .= "License Categories: " . $this->verificationResult['license_categories'] . "\n\n";
        $content .= "Verification Date: " . now()->format('F d, Y \a\t g:i A') . "\n";

        return response()->streamDownload(
            fn () => print($content),
            'license-verification-' . $this->verificationResult['license_no'] . '.txt'
        );
    }

    public function render()
    {
        return view('livewire.license-verification');
    }
}