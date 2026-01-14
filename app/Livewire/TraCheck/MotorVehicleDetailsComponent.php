<?php

namespace App\Livewire\TraCheck;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimpleXMLElement;
use Exception;

class MotorVehicleDetailsComponent extends Component
{
    public $vehicleRegistrationPlate = '';
    public $isLoading = false;
    public $response = null;
    public $error = null;
    public $rawResponse = '';
    
    public function mount()
    {
        // Set default values for testing
        $this->vehicleRegistrationPlate = '';
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
        return env('TRA_USER_NAME');
    }
    
    /**
     * Get SOAP password from environment
     */
    private function getSoapPassword()
    {
        return env('TRA_PASSWORD');
    }
    
    /**
     * Get SOAP connector ID from environment
     */
    private function getSoapConnectorId()
    {
        return env('SOAP_CONNECTOR_ID');
    }
    
    public function getVehicleDetails()
    {
        $this->validate([
            'vehicleRegistrationPlate' => 'required|string|min:3',
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

            
            $this->rawResponse = $response->body();
            
            // Log the response for debugging
            Log::info('TRA Motor Vehicle: Response received', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'response_length' => strlen($response->body()),
                'vehicle_plate' => $this->vehicleRegistrationPlate,
            ]);
            
            Log::debug('TRA Motor Vehicle: Full response body', [
                'response_body' => $response->body(),
            ]);
            
            if ($response->successful()) {
                $this->parseResponse($response->body());
            } else {
                $this->error = "HTTP Error: " . $response->status() . " - " . $response->body();
                Log::error('TRA Motor Vehicle: HTTP error response', [
                    'status' => $response->status(),
                    'response_body' => $response->body(),
                ]);
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
        $vehiclePlate = htmlspecialchars($this->vehicleRegistrationPlate, ENT_XML1, 'UTF-8');

        // Use the same MultiConnector structure that works for CreditInfoService,
        // only changing the inner request namespace and payload for TRA motor vehicle details.
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
                        <request xmlns="http://creditinfo.com/schemas/2012/09/MultiConnector/Connectors/TZA/TRAGetMotorVehicleDetails/Request">
                           <VehicleRegistrationPlate>{$vehiclePlate}</VehicleRegistrationPlate>
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
            Log::debug('TRA Motor Vehicle: Parsing response', [
                'response_body_length' => strlen($responseBody),
                'response_preview' => substr($responseBody, 0, 500),
            ]);
            
            // Parse XML with namespaces intact
            $xml = new SimpleXMLElement($responseBody);
            
            // Find the inner response node that contains the actual vehicle data
            // The structure is: response > connector > data > response (inner one with vehicle data)
            // We need to get the response node that's inside a data element
            $responseNodes = $xml->xpath('//*[local-name()="data"]/*[local-name()="response"]');
            
            // If that doesn't work, try getting all response nodes and take the last one (innermost)
            if (empty($responseNodes)) {
                $allResponseNodes = $xml->xpath('//*[local-name()="response"]');
                $responseData = !empty($allResponseNodes) ? end($allResponseNodes) : null;
            } else {
                $responseData = $responseNodes[0] ?? null;
            }
            
            Log::info('TRA Motor Vehicle: Response parsing', [
                'found_inner_response_nodes' => count($responseNodes),
                'has_response_data' => $responseData !== null,
            ]);
            
            if ($responseData) {
                // Log a sample of the data to verify we got the right node
                Log::debug('TRA Motor Vehicle: Sample parsed data', [
                    'registration_no' => (string) ($responseData->RegistrationNo ?? 'not found'),
                    'chassis_number' => (string) ($responseData->ChassisNumber ?? 'not found'),
                ]);
                $this->response = [
                    // Vehicle Basic Information
                    'registrationNo' => (string) $responseData->RegistrationNo,
                    'registrationCertificateNo' => (string) $responseData->RegistrationCertificateNo,
                    'chassisNumber' => (string) $responseData->ChassisNumber,
                    'engineNumber' => (string) $responseData->EngineNumber,
                    'registeredOn' => (string) $responseData->RegisteredOn,
                    'registrationPurpose' => (string) $responseData->RegistrationPurpose,
                    'registrationReason' => (string) $responseData->RegistrationReason,
                    
                    // Vehicle Specifications
                    'vehicleMake' => (string) $responseData->VehicleMake,
                    'vehicleModel' => (string) $responseData->VehicleModel,
                    'modelNo' => (string) $responseData->ModelNo,
                    'yearOfMake' => (string) $responseData->YearOfMake,
                    'bodyType' => (string) $responseData->BodyType,
                    'colour' => (string) $responseData->Colour,
                    'vehCategory' => (string) $responseData->VehCategory,
                    'vehicleUsage' => (string) $responseData->VehicleUsage,
                    
                    // Engine & Technical Details
                    'engineCubicCapacity' => (string) $responseData->EngineCubicCapacity,
                    'engineHpCapacity' => (string) $responseData->EngineHpCapacity,
                    'engineKwCapacity' => (string) $responseData->EngineKwCapacity,
                    'fuelType' => (string) $responseData->FuelType,
                    'transmissionBy' => (string) $responseData->TransmissionBy,
                    'propelledBy' => (string) $responseData->PropelledBy,
                    
                    // Weight & Capacity
                    'grossWeight' => (string) $responseData->GrossWeight,
                    'tareWeight' => (string) $responseData->TareWeight,
                    'seatingCapacity' => (string) $responseData->SeatingCapacity,
                    'numberOfAxles' => (string) $responseData->NumberOfAxles,
                    
                    // Axle Distances
                    'axleDistance1' => (string) $responseData->AxleDistance1,
                    'axleDistance2' => (string) $responseData->AxleDistance2,
                    'axleDistance3' => (string) $responseData->AxleDistance3,
                    'axleDistance4' => (string) $responseData->AxleDistance4,
                    
                    // Current Owner Information
                    'titleHolderName' => (string) $responseData->TitleHolderName,
                    'titleHolderCategory' => (string) $responseData->TitleHolderCategory,
                    'titleHolderDOB' => (string) $responseData->TitleHolderDOB,
                    'titleHolderGender' => (string) $responseData->TitleHolderGender,
                    'firstName' => (string) $responseData->FirstName,
                    'middleName' => (string) $responseData->MiddleName,
                    'lastName' => (string) $responseData->LastName,
                    'ownerIdentityNo' => (string) $responseData->OwnerIdentityNo,
                    'ownerIdentityNoType' => (string) $responseData->OwnerIdentityNoType,
                    'postalAddress' => (string) $responseData->PostalAddress,
                    
                    // Previous Owner Information
                    'previousOwnerName' => (string) $responseData->PreviousOwnerName,
                    'previousOwnerTin' => (string) $responseData->PreviousOwnerTin,
                    'changeOwnerDate' => (string) $responseData->ChangeOwnerDate,
                    'changeOwnerReason' => (string) $responseData->ChangeOwnerReason,
                    
                    // Additional Information
                    'controlNumber' => (string) $responseData->ControlNumber,
                    'customDutyExempted' => (string) $responseData->CustomDutyExempted,
                ];
                
                Log::info('TRA Motor Vehicle: Successfully parsed vehicle data', [
                    'registration_no' => $this->response['registrationNo'],
                    'vehicle_make' => $this->response['vehicleMake'],
                    'vehicle_model' => $this->response['vehicleModel'],
                ]);
            } else {
                $this->error = "Could not parse response data";
                Log::warning('TRA Motor Vehicle: Could not find response data in XML', [
                    'response_body_preview' => substr($responseBody, 0, 1000),
                ]);
            }
            
        } catch (Exception $e) {
            $this->error = "Parse Error: " . $e->getMessage();
            Log::error('TRA Motor Vehicle: Parse exception', [
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
        return view('livewire.tra-check.motor-vehicle-details-component');
    }

}



