<?php

namespace App\Livewire\TraCheck;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
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
        $this->vehicleRegistrationPlate = 'T115DYF';
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
        $vehiclePlate = htmlspecialchars($this->vehicleRegistrationPlate, ENT_XML1, 'UTF-8');
        
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
                         <request xmlns="http://creditinfo.com/schemas/2012/09/MultiConnector/Connectors/TZA/TRAGetMotorVehicleDetails/Request">
                            <VehicleRegistrationPlate>' . $vehiclePlate . '</VehicleRegistrationPlate>
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
        return view('livewire.tra-check.motor-vehicle-details-component');
    }

}



