<?php

namespace App\Livewire\TraCheck;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use SimpleXMLElement;
use Exception;

class TaxpayerDetailsComponent extends Component
{
    public $taxpayerNumber = '';
    public $dateOfRegistration = '';
    public $isLoading = false;
    public $response = null;
    public $error = null;
    public $rawResponse = '';

    public function mount()
    {
        // Set default values for testing
        $this->taxpayerNumber = '123049241';
        $this->dateOfRegistration = '2014-02-03';
    }
    
    /**
     * Get SOAP URL from environment
     */
    private function getSoapUrl()
    {
        return env('SOAP_URL');
    }
    
    /**
     * Get SOAP username from environment
     */
    private function getSoapUsername()
    {
        return env('SOAP_USERNAME');
    }
    
    /**
     * Get SOAP password from environment
     */
    private function getSoapPassword()
    {
        return env('SOAP_PASSWORD');
    }
    
    /**
     * Get SOAP connector ID from environment
     */
    private function getSoapConnectorId()
    {
        return env('SOAP_CONNECTOR_ID');
    }
    
    public function getTaxpayerDetails()
    {
        $this->validate([
            'taxpayerNumber' => 'required|string|min:8',
            'dateOfRegistration' => 'required|date'
        ]);
        
        // Validate SOAP configuration
        $soapUrl = $this->getSoapUrl();
        $username = $this->getSoapUsername();
        $password = $this->getSoapPassword();
        $connectorId = $this->getSoapConnectorId();
        
        if (!$soapUrl || !$username || !$password || !$connectorId) {
            $this->isLoading = false;
            $this->error = 'SOAP configuration is incomplete. Please check your environment variables (SOAP_URL, SOAP_USERNAME, SOAP_PASSWORD, SOAP_CONNECTOR_ID).';
            return;
        }
        
        $this->isLoading = true;
        $this->error = null;
        $this->response = null;
        $this->rawResponse = '';
        
        try {
            $messageId = Str::uuid()->toString();
            $dataId = Str::uuid()->toString();
            
            $soapEnvelope = $this->buildSoapEnvelope($messageId, $dataId, $username, $password, $connectorId);
            
            $response = Http::withHeaders([
                'Content-Type' => 'text/xml;charset=UTF-8',
                'SOAPAction' => 'http://creditinfo.com/schemas/2012/09/MultiConnector/MultiConnectorService/Query',
                'Authorization' => 'WSSE profile="UsernameToken"',
                'Username' => $username,
                'Password' => $password,
            ])
            ->timeout(150)
            ->withBody($soapEnvelope, 'text/xml')
            ->post($soapUrl);


            //   ->send('POST', $this->soapUrl, [
            //     'body' => $soapEnvelope
            // ]);
            
            $this->rawResponse = $response->body();
            
            if ($response->successful()) {
                $this->parseResponse($response->body());
            } else {
                $this->error = "HTTP Error: " . $response->status() . " - " . $response->body();

            }
            
        } catch (Exception $e) {
            $this->error = "Error: " . $e->getMessage();
        }
        
        $this->isLoading = false;
    }
    
    private function buildSoapEnvelope($messageId, $dataId, $username, $password, $connectorId)
    {
        // Escape XML special characters
        $username = htmlspecialchars($username, ENT_XML1, 'UTF-8');
        $password = htmlspecialchars($password, ENT_XML1, 'UTF-8');
        $connectorId = htmlspecialchars($connectorId, ENT_XML1, 'UTF-8');
        $taxpayerNumber = htmlspecialchars($this->taxpayerNumber, ENT_XML1, 'UTF-8');
        
        return '<?xml version="1.0" encoding="UTF-8"?>
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mul="http://creditinfo.com/schemas/2012/09/MultiConnector" xmlns:req="http://creditinfo.com/schemas/2012/09/MultiConnector/Messages/Request">
           <soapenv:Header>
              <wsse:Security soapenv:mustUnderstand="0" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                 <wsse:UsernameToken>
                    <wsse:Username>' . $username . '</wsse:Username>
                    <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">' . $password . '</wsse:Password>
                 </wsse:UsernameToken>
              </wsse:Security>
           </soapenv:Header>
           <soapenv:Body>
              <mul:Query>
                 <mul:request>
                    <mul:MessageId>' . $messageId . '</mul:MessageId>
                    <mul:RequestXml>
                       <req:connector id="' . $connectorId . '">
                          <req:data id="' . $dataId . '">
                             <request xmlns="http://creditinfo.com/schemas/2012/09/MultiConnector/Connectors/TZA/TRAGetTaxpayerDetails/Request">
                                <TaxPayerNumber>' . $taxpayerNumber . '</TaxPayerNumber>
                             </request>
                          </req:data>
                       </req:connector>
                    </mul:RequestXml>
                 </mul:request>
              </mul:Query>
           </soapenv:Body>
        </soapenv:Envelope>';
    }


    
    private function parseResponse($responseBody)
    {
        try {
            // Remove namespaces for easier parsing
            $cleanXml = preg_replace('/xmlns[^=]*="[^"]*"/i', '', $responseBody);
            $xml = new SimpleXMLElement($cleanXml);
            
            // Navigate to the response data
            $responseData = $xml->xpath('//response')[0] ?? null;
            
            if ($responseData) {
                $this->response = [
                    'taxpayerId' => (string) $responseData->TaxpayerId,
                    'taxpayerName' => (string) $responseData->TaxpayerName,
                    'firstName' => (string) $responseData->FirstName,
                    'middleName' => (string) $responseData->MiddleName,
                    'lastName' => (string) $responseData->LastName,
                    'dateOfBirth' => (string) $responseData->DateOfBirth,
                    'dateOfRegistration' => (string) $responseData->DateOfRegistration,
                    'gender' => (string) $responseData->Gender,
                    'isPerson' => (string) $responseData->IsPerson,
                    'postalAddress' => (string) $responseData->PostalAddress,
                    'postalCity' => (string) $responseData->PostalCity,
                    'plotNumber' => (string) $responseData->PlotNumber,
                    'blockNumber' => (string) $responseData->BlockNumber,
                    'region' => (string) $responseData->Region,
                    'district' => (string) $responseData->District,
                    'street' => (string) $responseData->Street,
                    'tel1' => (string) $responseData->Tel1,
                    'tel2' => (string) $responseData->Tel2,
                    'mobile' => (string) $responseData->Mobile,
                    'fax' => (string) $responseData->Fax,
                    'email' => (string) $responseData->Email,
                    'numberOfEmployees' => (string) $responseData->NumberOfEmployees,
                ];
            } else {
                $this->error = "Could not parse response data";
            }
            
        } catch (Exception $e) {
            $this->error = "Parse Error: " . $e->getMessage();
        }
    }
    
    public function clearResults()
    {
        $this->response = null;
        $this->error = null;
        $this->rawResponse = '';
    }
    
    public function render()
    {
        return view('livewire.tra-check.taxpayer-details-component');
    }
}
