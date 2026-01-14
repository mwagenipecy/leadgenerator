<?php

namespace App\Livewire\TraCheck;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
     * Get SOAP username from environment (same as license verification)
     */
    private function getSoapUsername()
    {
        return env('TRA_USER_NAME');
    }

    /**
     * Get SOAP password from environment (same as license verification)
     */
    private function getSoapPassword()
    {
        return env('TRA_PASSWORD');
    }

    /**
     * Get SOAP connector ID from environment (same as license verification)
     */
    private function getSoapConnectorId()
    {
        return env('SOAP_TIN_CONNECTOR_ID');
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
            $this->error = 'SOAP configuration is incomplete. Please check your environment variables (SOAP_URL, TRA_USER_NAME, TRA_PASSWORD, SOAP_LICENCE_CONNECTOR_ID).';
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

            Log::info('TRA Taxpayer: Sending SOAP request', [
                'taxpayer_number' => $this->taxpayerNumber,
                'message_id' => $messageId,
                'username' => $username,
                'connector_id' => $connectorId,
            ]);

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

            $this->rawResponse = $response->body();

            // Log the response for debugging
            Log::info('TRA Taxpayer: Response received', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'response_length' => strlen($response->body()),
                'taxpayer_number' => $this->taxpayerNumber,
            ]);

            Log::debug('TRA Taxpayer: Full response body', [
                'response_body' => $response->body(),
            ]);

            if ($response->successful()) {
                $this->parseResponse($response->body());
            } else {
                $this->error = "HTTP Error: " . $response->status() . " - " . $response->body();
                Log::error('TRA Taxpayer: HTTP error response', [
                    'status' => $response->status(),
                    'response_body' => $response->body(),
                ]);
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $this->error = 'Connection error. Please check your internet connection and try again.';
            Log::error('TRA Taxpayer: Connection exception', [
                'taxpayer_number' => $this->taxpayerNumber,
                'error' => $e->getMessage(),
            ]);
        } catch (Exception $e) {
            $this->error = "Error: " . $e->getMessage();
            Log::error('TRA Taxpayer: Exception occurred', [
                'taxpayer_number' => $this->taxpayerNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
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

        // Use the same MultiConnector structure that works for License/Motor Vehicle
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
                        <request xmlns="http://creditinfo.com/schemas/2012/09/MultiConnector/Connectors/TZA/TRAGetTaxpayerDetails/Request">
                           <TaxPayerNumber>{$taxpayerNumber}</TaxPayerNumber>
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


    
    private function parseResponse($responseBody)
    {
        try {
            // Log the response body being parsed
            Log::debug('TRA Taxpayer: Parsing response', [
                'response_body_length' => strlen($responseBody),
                'response_preview' => substr($responseBody, 0, 500),
            ]);

            // Parse XML with namespaces intact
            $xml = simplexml_load_string($responseBody);
            if ($xml === false) {
                throw new \Exception('Failed to parse XML response');
            }

            // Find the inner response node that contains the actual taxpayer data
            // The structure is: response > connector > data > response (inner one with taxpayer data)
            $responseNodes = $xml->xpath('//*[local-name()="data"]/*[local-name()="response"]');

            // If that doesn't work, try getting all response nodes and take the last one (innermost)
            if (empty($responseNodes)) {
                $allResponseNodes = $xml->xpath('//*[local-name()="response"]');
                $responseData = !empty($allResponseNodes) ? end($allResponseNodes) : null;
            } else {
                $responseData = $responseNodes[0] ?? null;
            }

            Log::info('TRA Taxpayer: Response parsing', [
                'found_inner_response_nodes' => count($responseNodes),
                'has_response_data' => $responseData !== null,
            ]);

            if ($responseData) {
                // Log a sample of the data to verify we got the right node
                Log::debug('TRA Taxpayer: Sample parsed data', [
                    'taxpayer_id' => (string) ($responseData->TaxpayerId ?? 'not found'),
                    'taxpayer_name' => (string) ($responseData->TaxpayerName ?? 'not found'),
                ]);
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

                Log::info('TRA Taxpayer: Successfully parsed taxpayer data', [
                    'taxpayer_id' => $this->response['taxpayerId'],
                    'taxpayer_name' => $this->response['taxpayerName'],
                ]);
            } else {
                $this->error = "Could not parse response data";
                Log::warning('TRA Taxpayer: Could not find response data in XML', [
                    'response_body_preview' => substr($responseBody, 0, 1000),
                ]);
            }

        } catch (Exception $e) {
            $this->error = "Parse Error: " . $e->getMessage();
            Log::error('TRA Taxpayer: Parse exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'response_body_preview' => substr($responseBody, 0, 1000),
            ]);
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
