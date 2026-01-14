<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CreditInfoService
{
    protected string $endpoint;
    protected string $username;
    protected string $password;
    protected string $strategyId;
    protected string $connectorId;

    public function __construct()
    {
        $endpoint = config('services.creditinfo.endpoint');
        $username = config('services.creditinfo.username');
        $password = config('services.creditinfo.password');
        $strategyId = config('services.creditinfo.strategy_id');
        $connectorId = config('services.creditinfo.connector_id');

        // Validate configuration
        if (empty($endpoint) || empty($username) || empty($password) || 
            empty($strategyId) || empty($connectorId)) {
            throw new \Exception('CreditInfo service is not properly configured. Please check your .env file for CREDITINFO_* variables.');
        }

        // Assign after validation
        $this->endpoint = $endpoint;
        $this->username = $username;
        $this->password = $password;
        $this->strategyId = $strategyId;
        $this->connectorId = $connectorId;
    }

    /**
     * Fetch credit score for a user using NIDA number
     */
    public function fetchCreditScore(string $nidaNumber, string $firstName, string $lastName, string $fullName, ?string $dateOfBirth, ?string $phoneNumber): ?array
    {
        try {
            // Generate UUIDs for message and data
            $messageId = \Illuminate\Support\Str::uuid()->toString();
            $dataId = \Illuminate\Support\Str::uuid()->toString();

            // Format date of birth (if provided)
            $dobFormatted = $dateOfBirth ? date('Y-m-d\TH:i:s', strtotime($dateOfBirth)) : '1980-01-01T00:00:00';

            // Build SOAP request XML
            $soapRequest = $this->buildSoapRequest($messageId, $dataId, $nidaNumber, $firstName, $lastName, $fullName, $dobFormatted, $phoneNumber);

            Log::info('CreditInfo: Sending SOAP request', [
                'nida_number' => $nidaNumber,
                'message_id' => $messageId,
            ]);

            // Log the SOAP request for debugging (without sensitive data)
            Log::debug('CreditInfo: SOAP Request', [
                'endpoint' => $this->endpoint,
                'message_id' => $messageId,
                'request_length' => strlen($soapRequest),
            ]);

            // Build WSSE Authorization header
            $wsseAuth = 'WSSE profile="UsernameToken"';
            
            // Send SOAP request with correct headers
            $response = Http::withHeaders([
                'Content-Type' => 'text/xml;charset=UTF-8',
                'SOAPAction' => 'http://creditinfo.com/schemas/2012/09/MultiConnector/MultiConnectorService/Query',
                'Authorization' => $wsseAuth,
                'Username' => $this->username,
                'Password' => $this->password,
            ])->withBody($soapRequest, 'text/xml')
              ->timeout(60)
              ->post($this->endpoint);

              Log::info('CreditInfo: Response', [
                'response' => json_encode($response->body()),
              ]);

            // Log response details
            Log::info('CreditInfo: Response received', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'response_length' => strlen($response->body()),
                'response_body' => json_encode($response->body()),
            ]);
            
            // Save response to file for debugging (first 2000 chars)
            if (config('app.debug')) {
                $responsePreview = substr($response->body(), 0, 2000);
                Log::debug('CreditInfo: Response preview', [
                    'preview' => $responsePreview,
                ]);
            }

            if (!$response->successful()) {
                // Try to parse SOAP fault
                $faultDetails = $this->parseSoapFault($response->body());
                
                Log::error('CreditInfo: Request failed', [
                    'status' => $response->status(),
                    'fault_code' => $faultDetails['code'] ?? null,
                    'fault_string' => $faultDetails['string'] ?? null,
                    'body_preview' => substr($response->body(), 0, 500),
                ]);
                return null;
            }

            // Parse SOAP response into normalized structure
            $result = $this->parseSoapResponse($response->body());

            Log::info('CreditInfo: Response received', [
                'nida_number' => $nidaNumber,
                'has_score' => isset($result['cip_score']),
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error('CreditInfo: Exception occurred', [
                'nida_number' => $nidaNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Build SOAP request XML
     */
    protected function buildSoapRequest(string $messageId, string $dataId, string $nidaNumber, string $firstName, string $lastName, string $fullName, string $dateOfBirth, ?string $phoneNumber): string
    {
        // Escape XML special characters
        $escapedUsername = htmlspecialchars($this->username, ENT_XML1, 'UTF-8');
        $escapedPassword = htmlspecialchars($this->password, ENT_XML1, 'UTF-8');
        $escapedFirstName = htmlspecialchars($firstName, ENT_XML1, 'UTF-8');
        $escapedLastName = htmlspecialchars($lastName, ENT_XML1, 'UTF-8');
        $escapedFullName = htmlspecialchars($fullName, ENT_XML1, 'UTF-8');
        $escapedNidaNumber = htmlspecialchars($nidaNumber, ENT_XML1, 'UTF-8');
        
        $phoneNumbersXml = '';
        if ($phoneNumber) {
            $escapedPhone = htmlspecialchars($phoneNumber, ENT_XML1, 'UTF-8');
            $phoneNumbersXml = "<string>{$escapedPhone}</string>";
        }

        return <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mul="http://creditinfo.com/schemas/2012/09/MultiConnector" xmlns:req="http://creditinfo.com/schemas/2012/09/MultiConnector/Messages/Request">
   <soapenv:Header>
      <wsse:Security soapenv:mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
         <wsse:UsernameToken wsu:Id="UsernameToken-1" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
            <wsse:Username>{$escapedUsername}</wsse:Username>
            <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">{$escapedPassword}</wsse:Password>
         </wsse:UsernameToken>
      </wsse:Security>
   </soapenv:Header>
   <soapenv:Body>
      <mul:Query>
         <mul:request>
            <mul:MessageId>{$messageId}</mul:MessageId>
            <mul:RequestXml>
               <mul:RequestXml>
                  <req:connector id="{$this->connectorId}">
                     <req:data id="{$dataId}">
                        <request xmlns="http://creditinfo.com/schemas/2012/09/MultiConnector/Connectors/INT/IdmStrategy/Request">
                           <Strategy>
                              <Id>{$this->strategyId}</Id>
                           </Strategy>
                           <ConnectorRequest>
                              <query>
                                 <DateOfBirth>{$dateOfBirth}</DateOfBirth>
                                 <FirstName>{$escapedFirstName}</FirstName>
                                 <FullName>{$escapedFullName}</FullName>
                                 <IdNumbers>
                                    <IdNumberPairIndividual>
                                       <IdNumber>{$escapedNidaNumber}</IdNumber>
                                       <IdNumberType>NationalID</IdNumberType>
                                    </IdNumberPairIndividual>
                                 </IdNumbers>
                                 <PhoneNumbers>
                                    {$phoneNumbersXml}
                                 </PhoneNumbers>
                                 <PresentSurname>{$escapedLastName}</PresentSurname>
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
</soapenv:Envelope>
XML;
    }

    /**
     * Parse SOAP response and extract credit score
     */
    protected function parseSoapResponse(string $xmlResponse): array
    {
        try {
            // Convert XML to array
            $xml = simplexml_load_string($xmlResponse);
            if ($xml === false) {
                throw new \Exception('Failed to parse XML response');
            }

            // Default values
            $cipScore = null;
            $rating = null;

            // Use XPath with local-name() to ignore namespaces
            $xml->registerXPathNamespace('s', 'http://schemas.xmlsoap.org/soap/envelope/');
            
            // Try to find ScoringAnalysis using XPath (ignoring namespaces)
            $scoringNodes = $xml->xpath('//*[local-name()="ScoringAnalysis"]');
            
            if (!empty($scoringNodes)) {
                $scoringNode = $scoringNodes[0];
                $cipScoreRaw = (string) $scoringNode->CIPScore;
                $ratingRaw = (string) $scoringNode->CIPRiskGrade;
                
                Log::info('CreditInfo: Found and extracted score via XPath', [
                    'cip_score' => $cipScoreRaw,
                    'rating' => $ratingRaw,
                ]);
                
                if (!empty($cipScoreRaw) && is_numeric($cipScoreRaw)) {
                    $cipScore = (int) $cipScoreRaw;
                    $rating = !empty($ratingRaw) ? $ratingRaw : null;
                }
            } else {
                Log::warning('CreditInfo: XPath could not find ScoringAnalysis node');
            }

            // Convert full XML to array for raw_parsed (do this first to preserve everything)
            $array = json_decode(json_encode((array) $xml), true);

            // Use XPath to directly find the response node (ignoring namespaces)
            $xml->registerXPathNamespace('s', 'http://schemas.xmlsoap.org/soap/envelope/');
            $xml->registerXPathNamespace('mul', 'http://creditinfo.com/schemas/2012/09/MultiConnector');
            $xml->registerXPathNamespace('req', 'http://creditinfo.com/schemas/2012/09/MultiConnector/Messages/Response');
            $xml->registerXPathNamespace('conn', 'http://creditinfo.com/schemas/2012/09/MultiConnector/Connectors/INT/IdmStrategy/Response');
            
            // Try to find the response node using XPath
            // Look for response node that has status and hitcount children
            $responseNodes = $xml->xpath('//*[local-name()="response"][*[local-name()="status"]][*[local-name()="hitcount"]]');
            
            $responsePath = null;
            if (!empty($responseNodes)) {
                // Get the innermost response node (the actual data response)
                $responseNode = end($responseNodes);
                // Convert SimpleXMLElement to array properly
                $responsePath = json_decode(json_encode((array) $responseNode), true);
                
                // If conversion resulted in numeric keys, try to get the actual data
                if (is_array($responsePath) && isset($responsePath[0]) && is_array($responsePath[0])) {
                    $responsePath = $responsePath[0];
                }
            }

            // Log what we found
            if ($responsePath && is_array($responsePath)) {
                Log::info('CreditInfo: Found response path via XPath', [
                    'response_keys' => array_keys($responsePath),
                    'has_status' => isset($responsePath['status']),
                    'has_general_info' => isset($responsePath['GeneralInformation']),
                    'has_tza_cb5' => isset($responsePath['TzaCb5_data']),
                ]);
            } else {
                // Fallback: try to find response in array structure
                Log::warning('CreditInfo: XPath did not find response, trying array traversal');
                
                // Try recursive search for response node
                $findResponse = function($arr, $depth = 0) use (&$findResponse) {
                    if ($depth > 15 || !is_array($arr)) return null;
                    
                    // Check if this looks like the response node
                    if (isset($arr['status']) && (isset($arr['hitcount']) || isset($arr['GeneralInformation']))) {
                        return $arr;
                    }
                    
                    foreach ($arr as $value) {
                        if (is_array($value)) {
                            $result = $findResponse($value, $depth + 1);
                            if ($result) return $result;
                        }
                    }
                    return null;
                };
                
                $responsePath = $findResponse($array);
                
                if ($responsePath) {
                    Log::info('CreditInfo: Found response via recursive search');
                } else {
                    Log::error('CreditInfo: Could not find response path in XML structure');
                }
            }

            // Initialize credit_data with all possible sections
            $creditData = [
                'status' => null,
                'hitcount' => null,
                'infomsg' => null,
                'currency' => null,
                'general_information' => null,
                'personal_information' => null,
                'scoring_analysis' => null,
                'inquiries_analysis' => null,
                'current_contracts' => null,
                'past_due_information' => null,
                'repayment_information' => null,
                'policy_rules' => null,
                'tza_cb5_data' => null,
                'extract' => null,
                'strategy' => null,
            ];

            if ($responsePath && is_array($responsePath)) {
                Log::debug('CreditInfo: Found response path', [
                    'response_keys' => array_keys($responsePath),
                    'sample_data' => json_encode(array_slice($responsePath, 0, 5, true)),
                ]);

                // Helper to extract value (handles both direct values and _value wrapper)
                $extractValue = function($data, $key) {
                    if (!isset($data[$key])) return null;
                    $value = $data[$key];
                    if (is_array($value)) {
                        // Check for _value wrapper
                        if (isset($value['_value'])) {
                            return $value['_value'];
                        }
                        // Check for @attributes (XML attributes)
                        if (isset($value['@attributes'])) {
                            return $value;
                        }
                        // Return array as-is
                        return $value;
                    }
                    return $value;
                };

                // Extract top-level fields
                $creditData['status'] = $extractValue($responsePath, 'status');
                $creditData['hitcount'] = $extractValue($responsePath, 'hitcount');
                $creditData['infomsg'] = $extractValue($responsePath, 'infomsg');
                $creditData['currency'] = $extractValue($responsePath, 'Currency') ?? $extractValue($responsePath, 'currency');

                // Map all sections into normalized credit_data structure
                // Try multiple key variations for each section
                $creditData['general_information'] = $extractValue($responsePath, 'GeneralInformation') 
                    ?? $extractValue($responsePath, 'generalInformation')
                    ?? $extractValue($responsePath, 'general_information');
                    
                $creditData['personal_information'] = $extractValue($responsePath, 'PersonalInformation')
                    ?? $extractValue($responsePath, 'personalInformation')
                    ?? $extractValue($responsePath, 'personal_information');
                    
                $creditData['scoring_analysis'] = $extractValue($responsePath, 'ScoringAnalysis')
                    ?? $extractValue($responsePath, 'scoringAnalysis')
                    ?? $extractValue($responsePath, 'scoring_analysis');
                    
                $creditData['inquiries_analysis'] = $extractValue($responsePath, 'InquiriesAnalysis')
                    ?? $extractValue($responsePath, 'inquiriesAnalysis')
                    ?? $extractValue($responsePath, 'inquiries_analysis');
                    
                $creditData['current_contracts'] = $extractValue($responsePath, 'CurrentContracts')
                    ?? $extractValue($responsePath, 'currentContracts')
                    ?? $extractValue($responsePath, 'current_contracts');
                    
                $creditData['past_due_information'] = $extractValue($responsePath, 'PastDueInformation')
                    ?? $extractValue($responsePath, 'pastDueInformation')
                    ?? $extractValue($responsePath, 'past_due_information');
                    
                $creditData['repayment_information'] = $extractValue($responsePath, 'RepaymentInformation')
                    ?? $extractValue($responsePath, 'repaymentInformation')
                    ?? $extractValue($responsePath, 'repayment_information');
                    
                $creditData['policy_rules'] = $extractValue($responsePath, 'PolicyRules')
                    ?? $extractValue($responsePath, 'policyRules')
                    ?? $extractValue($responsePath, 'policy_rules');
                    
                $creditData['tza_cb5_data'] = $extractValue($responsePath, 'TzaCb5_data')
                    ?? $extractValue($responsePath, 'tzaCb5_data')
                    ?? $extractValue($responsePath, 'TzaCb5Data')
                    ?? $extractValue($responsePath, 'tzaCb5Data');
                    
                $creditData['extract'] = $extractValue($responsePath, 'Extract')
                    ?? $extractValue($responsePath, 'extract');
                    
                $creditData['strategy'] = $extractValue($responsePath, 'Strategy')
                    ?? $extractValue($responsePath, 'strategy');

                // Extract scoring analysis for score extraction
                $scoringAnalysis = $creditData['scoring_analysis'];
                $extract = $creditData['extract'];

                // Try to get CIP Score from ScoringAnalysis
                if ($scoringAnalysis) {
                    $cipScore = $scoringAnalysis['CIPScore'] ?? $scoringAnalysis['CipScore'] ?? $scoringAnalysis['cipScore'] ?? null;
                    if ($cipScore !== null) {
                        $cipScore = (int) $cipScore;
                    }
                    
                    $rating = $scoringAnalysis['CIPRiskGrade'] ?? $scoringAnalysis['CipRiskGrade'] ?? $scoringAnalysis['cipRiskGrade'] ?? null;
                }

                // Fallback: Try Extract section
                if ($cipScore === null) {
                    $extract = $responsePath['Extract'] ?? $responsePath['extract'] ?? null;
                    if ($extract) {
                        $cipScore = $extract['CIPScore'] ?? $extract['CipScore'] ?? $extract['cipScore'] ?? null;
                        if ($cipScore !== null) {
                            $cipScore = (int) $cipScore;
                        }
                        
                        if ($rating === null) {
                            $rating = $extract['CIPGrade'] ?? $extract['CipGrade'] ?? $extract['cipGrade'] ?? null;
                        }
                    }
                }
                
                // Try Dashboard section
                if ($cipScore === null) {
                    $dashboard = $responsePath['Dashboard'] ?? $responsePath['dashboard'] ?? null;
                    if ($dashboard) {
                        $cip = $dashboard['CIP'] ?? $dashboard['Cip'] ?? $dashboard['cip'] ?? null;
                        if ($cip) {
                            $cipScore = $cip['Score'] ?? $cip['score'] ?? null;
                            if ($cipScore !== null) {
                                $cipScore = (int) $cipScore;
                            }
                            $rating = $cip['Grade'] ?? $cip['grade'] ?? null;
                        }
                    }
                }
            } else {
                Log::warning('CreditInfo: Could not find response path in XML structure');
            }

            return [
                'success' => $cipScore !== null,
                'cip_score' => $cipScore,
                'rating' => $rating,
                'credit_data' => $creditData,
                'raw_parsed' => $array,
            ];

        } catch (\Exception $e) {
            Log::error('CreditInfo: Failed to parse response', [
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'cip_score' => null,
                'rating' => null,
                'credit_data' => null,
                'raw_parsed' => null,
            ];
        }
    }

    /**
     * Parse SOAP fault from error response
     */
    protected function parseSoapFault(string $xmlResponse): array
    {
        try {
            $xml = simplexml_load_string($xmlResponse);
            if ($xml === false) {
                return ['code' => null, 'string' => null];
            }

            $array = json_decode(json_encode($xml), true);
            
            $fault = $array['s:Body']['s:Fault'] ?? null;
            if ($fault) {
                return [
                    'code' => $fault['faultcode'] ?? null,
                    'string' => $fault['faultstring'] ?? null,
                ];
            }

            return ['code' => null, 'string' => null];
        } catch (\Exception $e) {
            Log::error('CreditInfo: Failed to parse SOAP fault', [
                'error' => $e->getMessage(),
            ]);
            return ['code' => null, 'string' => null];
        }
    }

    /**
     * Get credit score rating label
     */
    public function getRatingLabel(?int $score): string
    {
        if ($score === null) {
            return 'Waiting';
        }

        if ($score >= 750) {
            return 'Excellent';
        } elseif ($score >= 650) {
            return 'Good';
        } elseif ($score >= 550) {
            return 'Fair';
        } else {
            return 'Poor';
        }
    }
}
