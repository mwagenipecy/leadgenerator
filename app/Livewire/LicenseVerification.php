<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LicenseVerification extends Component
{
    public $licenseNumber = '';
    public $verificationResult = null;
    public $isLoading = false;
    public $error = null;
    public $showResult = false;

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

        try {
            $messageId = Str::uuid()->toString();
            $dataId = Str::uuid()->toString();
            
            $soapRequest = $this->buildSoapRequest($messageId, $dataId, $this->licenseNumber);
            
            $response = Http::withHeaders([
                'Content-Type' => 'text/xml;charset=UTF-8',
                'SOAPAction' => 'http://creditinfo.com/schemas/2012/09/MultiConnector/MultiConnectorService/Query',
                'Authorization' => 'WSSE profile="UsernameToken"',
                'Username' => 'lead.gen',
                'Password' => 'leadGen@2025',
            ])->timeout(80)
              ->withBody($soapRequest, 'text/xml')
              ->post('https://mc-uat.creditinfo.co.tz/MultiConnector.svc');

            if ($response->successful()) {
                $parsedResult = $this->parseResponse($response->body());
                
                if ($parsedResult) {
                    $this->verificationResult = $parsedResult;
                    $this->showResult = true;
                } else {

                    // dd($response->body());
                    $this->error = 'No license information found for the provided number.';
                }
            } else {
                $this->error = 'Failed to verify license. Please check your connection and try again.';
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $this->error = 'Connection error. Please check your internet connection and try again.';
        } catch (\Exception $e) {
            $this->error = 'An unexpected error occurred. Please try again later.';
            \Log::error('License verification error: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    private function buildSoapRequest($messageId, $dataId, $licenseNumber)
    {
        $licenseNumber = '4002014677'; // htmlspecialchars($licenseNumber, ENT_XML1, 'UTF-8');
        
        return '<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mul="http://creditinfo.com/schemas/2012/09/MultiConnector" xmlns:req="http://creditinfo.com/schemas/2012/09/MultiConnector/Messages/Request">
   <soapenv:Header>
      <wsse:Security soapenv:mustUnderstand="0" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
         <wsse:UsernameToken>
            <wsse:Username>lead.gen</wsse:Username>
            <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">leadGen@2025</wsse:Password>
         </wsse:UsernameToken>
      </wsse:Security>
   </soapenv:Header>
   <soapenv:Body>
      <mul:Query>
         <mul:request>
            <mul:MessageId>' . $messageId . '</mul:MessageId>
            <mul:RequestXml>
               <mul:RequestXml>
                  <req:connector id="8ebd1a52-7962-4999-ac66-543a5d423612">
                     <req:data id="' . $dataId . '">
                        <request xmlns="http://creditinfo.com/schemas/2012/09/MultiConnector/Connectors/TZA/TRAGetDrivingLicenseDetails/Request">
                           <DrivingLicenseNumber>' . $licenseNumber . '</DrivingLicenseNumber>
                        </request>
                     </req:data>
                  </req:connector>
               </mul:RequestXml>
            </mul:RequestXml>
         </mul:request>
      </mul:Query>
   </soapenv:Body>
</soapenv:Envelope>';
    }

    private function parseResponse($xmlResponse)
    {
        try {
            // Remove any BOM or invalid characters
            $xmlResponse = trim($xmlResponse);
            
            $xml = simplexml_load_string($xmlResponse);
            if ($xml === false) {
                throw new \Exception('Failed to parse XML response');
            }
            
            $xml->registerXPathNamespace('s', 'http://schemas.xmlsoap.org/soap/envelope/');
            $xml->registerXPathNamespace('mc', 'http://creditinfo.com/schemas/2012/09/MultiConnector');
            $xml->registerXPathNamespace('resp', 'http://creditinfo.com/schemas/2012/09/MultiConnector/Messages/Response');
            $xml->registerXPathNamespace('license', 'http://creditinfo.com/schemas/2012/09/MultiConnector/Connectors/TZA/TRAGetDrivingLicenseDetails/Response');

            $licenseData = $xml->xpath('//license:response')[0] ?? null;
            
            if ($licenseData) {
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
            
            return null;
        } catch (\Exception $e) {
            \Log::error('XML parsing error: ' . $e->getMessage());
            throw new \Exception('Failed to parse response: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->licenseNumber = '';
        $this->verificationResult = null;
        $this->error = null;
        $this->showResult = false;
        $this->resetErrorBag();
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