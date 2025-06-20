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
            'national_id' => $data['national_id'] ?? null,
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'full_name' => $data['full_name'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'phone_number' => $data['phone_number'] ?? null,
            'message_id' => $messageId,
            'strategy_id' => self::STRATEGY_ID,
            'status' => 'pending',
            'requested_at' => now(),
        ]);




        try {
            // Check if we already have a recent successful request for this national ID
            $existingRequest = $this->getExistingRequest($data['national_id']);
            


            if (!$existingRequest) { 
                
                // remove ! after testing 
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

                // Process the response
                $processedResponse = $this->processApiResponse($response->body());
                
                // Log the processed response for debugging
                Log::info('API Response Processed', [
                    'national_id' => $data['national_id'],
                    'processed_response' => $processedResponse,
                    'raw_response_preview' => substr($response->body(), 0, 500)
                ]);
                
                $creditRequest->update([
                    'status' => 'success',
                    'response_payload' => $processedResponse,
                    'responded_at' => now(),
                ]);
                
                Log::info('Credit info API call successful', [
                    'national_id' => $data['national_id'],
                    'request_id' => $creditRequest->id
                ]);
            } else {
                throw new Exception('API call failed with status: ' . $response->status() . ' Body: ' . $response->body());
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
            "X-WSSE"=> 'WSSE profile="UsernameToken"',
            "Username"=>self::USERNAME,
            "Password"=>self::PASSWORD,
            'SOAPAction' => 'http://creditinfo.com/schemas/2012/09/MultiConnector/MultiConnectorService/Query',
        ])->timeout(30)->send('POST', self::ENDPOINT, [
            'body' => $soapRequest
        ]);
    }

    /**
     * Process the API response and extract meaningful data
     */
    private function processApiResponse(string $xmlResponse): array
    {
        try {
            // Clean the response if it has quotes around it
            $cleanXml = trim($xmlResponse);
            if (str_starts_with($cleanXml, '"') && str_ends_with($cleanXml, '"')) {
                $cleanXml = substr($cleanXml, 1, -1);
            }

            // Log the raw response for debugging
            Log::info('Raw API Response', [
                'response_length' => strlen($cleanXml),
                'response_preview' => substr($cleanXml, 0, 1000),
                'response_ending' => substr($cleanXml, -500)
            ]);

            // Parse XML to array
            $parsedXml = $this->parseXmlToArray($cleanXml);
            
            if (isset($parsedXml['parse_error'])) {
                Log::error('XML parsing failed', ['error' => $parsedXml['parse_error']]);
                return $parsedXml;
            }

            // Log the parsed structure
            Log::info('Parsed XML structure', [
                'top_level_keys' => array_keys($parsedXml),
                'structure' => $this->getArrayStructure($parsedXml, 3) // 3 levels deep
            ]);

            // Extract the credit response data from the nested structure
            $creditData = $this->extractCreditResponseData($parsedXml);
            
            return [
                'success' => true,
                'credit_data' => $creditData,
                'raw_parsed' => $parsedXml, // Keep for debugging
                'processed_at' => now()->toISOString()
            ];

        } catch (Exception $e) {
            Log::error('Response processing failed', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'raw_response' => substr($xmlResponse, 0, 2000) // First 2000 chars for debugging
            ];
        }
    }

    /**
     * Extract credit response data from parsed XML array
     */
    private function extractCreditResponseData(array $parsedXml): array
    {
        try {
            $body = null;
    
            // Dynamically find the "Body" element regardless of prefix (e.g., s:Body, soapenv:Body, etc.)
            foreach ($parsedXml as $key => $val) {
                if ($key === 'Body' || str_ends_with($key, ':Body')) {
                    $body = $val;
                    break;
                }
            }
    
            // Log structure for debugging
            Log::info('Parsed XML structure keys', [
                'top_level_keys' => array_keys($parsedXml),
                'body_found' => $body !== null
            ]);
    
            if (!$body) {
                throw new Exception('SOAP Body not found in response. Available keys: ' . implode(', ', array_keys($parsedXml)));
            }
    
            // Continue with existing logic
            $queryResponse = $body['QueryResponse'] ?? null;
            if (!$queryResponse) {
                throw new Exception('QueryResponse not found in SOAP Body');
            }
    
            $queryResult = $queryResponse['QueryResult'] ?? null;
            if (!$queryResult) {
                throw new Exception('QueryResult not found in QueryResponse');
            }
    
            $responseXml = $queryResult['ResponseXml'] ?? null;
            if (!$responseXml) {
                throw new Exception('ResponseXml not found in QueryResult');
            }
    
            $response = $responseXml['response'] ?? null;
            if (!$response) {
                throw new Exception('Response not found in ResponseXml');
            }
    
            $connector = $response['connector'] ?? null;
            if (!$connector) {
                throw new Exception('Connector not found in response');
            }
    
            $data = $connector['data'] ?? null;
            if (!$data) {
                throw new Exception('Data not found in connector');
            }
    
            $creditResponse = $data['response'] ?? null;
            if (!$creditResponse) {
                throw new Exception('Credit response not found in data');
            }
    
            // Return structured credit response
            return [
                'message_id' => $queryResult['MessageId'] ?? null,
                'timestamp' => $queryResult['Timestamp'] ?? null,
                'status' => $creditResponse['status'] ?? 'unknown',
                'hit_count' => (int)($creditResponse['hitcount'] ?? 0),
                'currency' => $creditResponse['Currency'] ?? 'TZS',
                'general_information' => $this->cleanArray($creditResponse['GeneralInformation'] ?? []),
                'personal_information' => $this->cleanArray($creditResponse['PersonalInformation'] ?? []),
                'scoring_analysis' => $this->cleanArray($creditResponse['ScoringAnalysis'] ?? []),
                'inquiries_analysis' => $this->cleanArray($creditResponse['InquiriesAnalysis'] ?? []),
                'current_contracts' => $this->cleanArray($creditResponse['CurrentContracts'] ?? []),
                'past_due_information' => $this->cleanArray($creditResponse['PastDueInformation'] ?? []),
                'repayment_information' => $this->cleanArray($creditResponse['RepaymentInformation'] ?? []),
                'policy_rules' => $this->cleanArray($creditResponse['PolicyRules'] ?? []),
                'extract' => $this->cleanArray($creditResponse['Extract'] ?? []),
                'strategy' => $this->cleanArray($creditResponse['Strategy'] ?? []),
            ];
    
        } catch (Exception $e) {
            Log::error('Credit data extraction failed', ['error' => $e->getMessage()]);
            throw new Exception('Failed to extract credit data: ' . $e->getMessage());
        }
    }

    


    /**
     * Clean array by converting numeric strings to integers/floats where appropriate
     */
    private function cleanArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->cleanArray($value);
            } elseif (is_string($value) && is_numeric($value)) {
                // Convert numeric strings to proper numbers
                $data[$key] = str_contains($value, '.') ? (float)$value : (int)$value;
            }
        }
        return $data;
    }

    private function parseXmlToArray(string $xml): array
    {
        try {
            if (empty(trim($xml))) {
                throw new Exception('Empty XML string provided');
            }
    
            $xmlObject = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    
            if ($xmlObject === false) {
                $errors = libxml_get_errors();
                $errorMessages = array_map(fn($error) => $error->message, $errors);
                throw new Exception('XML parsing failed: ' . implode(', ', $errorMessages));
            }
    
            return $this->xmlToArrayPreserveNamespaces($xmlObject);
        } catch (Exception $e) {
            Log::error('XML parsing failed', [
                'error' => $e->getMessage(),
                'xml_preview' => substr($xml, 0, 500)
            ]);
            return ['raw_xml' => $xml, 'parse_error' => $e->getMessage()];
        }
    }

    
    private function xmlToArrayPreserveNamespaces(\SimpleXMLElement $xml): array
    {
        $namespaces = $xml->getNamespaces(true);
        $data = [];
    
        // Recursively parse child elements
        foreach ($xml->children() as $child) {
            $tag = $child->getName();
            $data[$tag] = $this->xmlToArrayPreserveNamespaces($child);
        }
    
        foreach ($namespaces as $prefix => $ns) {
            foreach ($xml->children($ns) as $child) {
                $tag = ($prefix ? "$prefix:" : '') . $child->getName();
                $data[$tag] = $this->xmlToArrayPreserveNamespaces($child);
            }
        }
    
        // Parse attributes
        foreach ($xml->attributes() as $attrName => $attrValue) {
            $data['@attributes'][$attrName] = (string)$attrValue;
        }
    
        foreach ($namespaces as $prefix => $ns) {
            foreach ($xml->attributes($ns) as $attrName => $attrValue) {
                $data['@attributes'][($prefix ? "$prefix:" : '') . $attrName] = (string)$attrValue;
            }
        }
    
        // If no children or attributes, return the scalar as value in array
        if (empty($data)) {
            return ['_value' => (string)$xml];
        }
    
        return $data;
    }

    



    /**
     * Get array structure for debugging (recursive)
     */
    private function getArrayStructure(array $array, int $maxDepth = 2, int $currentDepth = 0): array
    {
        if ($currentDepth >= $maxDepth) {
            return ['...'];
        }

        $structure = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $structure[$key] = $this->getArrayStructure($value, $maxDepth, $currentDepth + 1);
            } else {
                $structure[$key] = gettype($value);
            }
        }
        return $structure;
    }

    /**
     * Get specific credit score information from the processed response
     */
    public function getCreditScores(array $responsePayload): array
    {
        if (!$responsePayload['success'] ?? false) {
            return ['error' => 'Invalid response payload'];
        }

        $creditData = $responsePayload['credit_data'] ?? [];
        $scoringAnalysis = $creditData['scoring_analysis'] ?? [];
        $extract = $creditData['extract'] ?? [];

        return [
            'cip_score' => $scoringAnalysis['CIPScore'] ?? $extract['CIPScore'] ?? null,
            'cip_risk_grade' => $scoringAnalysis['CIPRiskGrade'] ?? $extract['CIPGrade'] ?? null,
            'mobile_score' => $scoringAnalysis['MobileScore'] ?? $extract['MobileScore'] ?? null,
            'mobile_risk_grade' => $scoringAnalysis['MobileScoreRiskGrade'] ?? $extract['MobileGrade'] ?? null,
            'recommended_decision' => $creditData['general_information']['RecommendedDecision'] ?? $extract['Decision'] ?? null,
            'total_balance' => $extract['MobileTotalBalance'] ?? 0,
            'broken_rules' => $creditData['general_information']['BrokenRules'] ?? 0
        ];
    }
}