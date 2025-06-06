<?php

namespace App\Services;

use App\Models\CreditInfoRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class CreditInfoService
{
    private const ENDPOINT = 'https://idm-stage.creditinfo.co.tz/Web/MultiConnector.svc';
    private const USERNAME = 'scheme';
    private const PASSWORD = 'Scheme2025';
    private const STRATEGY_ID = '2e1a9e93-0489-40e7-8fc2-185a21ae171a';
    private const CONNECTOR_ID = '1C8F01F8-71A2-4C99-98A1-8BD1D85C4F63';

    public function checkCreditInfo(array $data): CreditInfoRequest
    {
        $messageId = Str::uuid()->toString();
        
        // Create initial record
        $creditRequest = CreditInfoRequest::create([
            'loan_id' => $data['loan_id'] ?? null,
            'application_number' => $data['application_number'] ?? null,
            'national_id' => $data['national_id'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'full_name' => $data['full_name'],
            'date_of_birth' => $data['date_of_birth'],
            'phone_number' => $data['phone_number'],
            'message_id' => $messageId,
            'strategy_id' => self::STRATEGY_ID,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        try {
            // Check if we already have a recent successful request for this national ID
            $existingRequest = $this->getExistingRequest($data['national_id']);
            
            if ($existingRequest) {
                // Update current request with existing data
                $creditRequest->update([
                    'status' => 'success',
                    'response_payload' => $existingRequest->response_payload,
                    'responded_at' => now(),
                ]);
                
                Log::info('Credit info retrieved from database cache', [
                    'national_id' => $data['national_id'],
                    'existing_request_id' => $existingRequest->id
                ]);
                
                return $creditRequest;
            }

            // Build SOAP request
            $soapRequest = $this->buildSoapRequest($messageId, $data);
            
            // Store request payload
            $creditRequest->update([
                'request_payload' => $this->parseXmlToArray($soapRequest)
            ]);

            // Make API call
            $response = $this->makeApiCall($soapRequest);
            
            if ($response->successful()) {
                $responseArray = $this->parseXmlToArray($response->body());
                
                $creditRequest->update([
                    'status' => 'success',
                    'response_payload' => $responseArray,
                    'responded_at' => now(),
                ]);
                
                Log::info('Credit info API call successful', [
                    'national_id' => $data['national_id'],
                    'request_id' => $creditRequest->id
                ]);
            } else {
                throw new Exception('API call failed with status: ' . $response->status());
            }

        } catch (Exception $e) {
            $creditRequest->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'responded_at' => now(),
            ]);
            
            Log::error('Credit info API call failed', [
                'national_id' => $data['national_id'],
                'error' => $e->getMessage(),
                'request_id' => $creditRequest->id
            ]);
        }

        return $creditRequest;
    }

    private function getExistingRequest(string $nationalId): ?CreditInfoRequest
    {
        return CreditInfoRequest::where('national_id', $nationalId)
            ->where('status', 'success')
            ->where('created_at', '>=', now()->subHours(24)) // Cache for 24 hours
            ->latest()
            ->first();
    }

    private function buildSoapRequest(string $messageId, array $data): string
    {
        $dateOfBirth = date('Y-m-d\TH:i:s', strtotime($data['date_of_birth']));
        
        return '<?xml version="1.0" encoding="utf-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mul="http://creditinfo.com/schemas/2012/09/MultiConnector" xmlns:req="http://creditinfo.com/schemas/2012/09/MultiConnector/Messages/Request">
   <soapenv:Header>
      <wsse:Security soapenv:mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
         <wsse:UsernameToken wsu:Id="UsernameToken-1" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
            <wsse:Username>' . self::USERNAME . '</wsse:Username>
            <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">' . self::PASSWORD . '</wsse:Password>
         </wsse:UsernameToken>
      </wsse:Security>
   </soapenv:Header>
   <soapenv:Body>
      <mul:Query>
         <mul:request>
            <mul:MessageId>' . $messageId . '</mul:MessageId>
            <mul:RequestXml>
               <mul:RequestXml>
                  <req:connector id="' . self::CONNECTOR_ID . '">
                     <req:data id="' . $messageId . '">
                        <request xmlns="http://creditinfo.com/schemas/2012/09/MultiConnector/Connectors/INT/IdmStrategy/Request">
                           <Strategy>
                              <Id>' . self::STRATEGY_ID . '</Id>
                           </Strategy>
                           <ConnectorRequest>
                              <query>
                                 <DateOfBirth>' . $dateOfBirth . '</DateOfBirth>
                                 <FirstName>' . htmlspecialchars($data['first_name']) . '</FirstName>
                                 <FullName>' . htmlspecialchars($data['full_name']) . '</FullName>
                                 <IdNumbers>
                                    <IdNumberPairIndividual>
                                       <IdNumber>' . htmlspecialchars($data['national_id']) . '</IdNumber>
                                       <IdNumberType>NationalID</IdNumberType>
                                    </IdNumberPairIndividual>
                                 </IdNumbers>
                                 <PhoneNumbers>
                                    <string>' . htmlspecialchars($data['phone_number']) . '</string>
                                 </PhoneNumbers>
                                 <PresentSurname>' . htmlspecialchars($data['last_name']) . '</PresentSurname>
                              </query>
                           </ConnectorRequest>
                           <Consent>true</Consent>
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

    private function makeApiCall(string $soapRequest)
    {
        return Http::withHeaders([
            'Content-Type' => 'text/xml; charset=utf-8',
            'SOAPAction' => 'http://creditinfo.com/schemas/2012/09/MultiConnector/MultiConnectorService/Query',
        ])->timeout(30)->send('POST', self::ENDPOINT, [
            'body' => $soapRequest
        ]);
    }

    private function parseXmlToArray(string $xml): array
    {
        try {
            $xmlObject = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
            return json_decode(json_encode($xmlObject), true);
        } catch (Exception $e) {
            Log::error('XML parsing failed', ['error' => $e->getMessage()]);
            return ['raw_xml' => $xml, 'parse_error' => $e->getMessage()];
        }
    }
}