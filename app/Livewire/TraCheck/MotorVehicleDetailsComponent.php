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
    public $dateOfRegistration = '';
    public $isLoading = false;
    public $response = null;
    public $error = null;
    public $rawResponse = '';
    
    // SOAP service configuration
    private $soapUrl = 'https://mc-uat.creditinfo.co.tz/MultiConnector.svc'; // Replace with actual URL
    private $username = 'lead.gen';
    private $password = 'leadGen@2025';
    private $connectorId = 'connector-id'; // Replace with actual connector ID
    
    public function mount()
    {
        // Set default values for testing
        $this->vehicleRegistrationPlate = 'T115DYF';
        $this->dateOfRegistration = '2021-12-21';
    }
    
    public function getVehicleDetails()
    {
        $this->validate([
            'vehicleRegistrationPlate' => 'required|string|min:3',
            'dateOfRegistration' => 'required|date'
        ]);
        
        $this->isLoading = true;
        $this->error = null;
        $this->response = null;
        $this->rawResponse = '';
        
        try {
            $messageId = Str::uuid()->toString();
            $dataId = Str::uuid()->toString();
            
            $soapEnvelope = $this->buildSoapEnvelope($messageId, $dataId);
            
            $response = Http::withHeaders([
                'Content-Type' => 'text/xml;charset=UTF-8',
                'SOAPAction' => 'http://creditinfo.com/schemas/2012/09/MultiConnector/MultiConnectorService/Query',
                'Authorization' => 'WSSE profile="UsernameToken"',
                'Username' => $this->username,
                'Password' => $this->password,
            ])
            
            // ->send('POST', $this->soapUrl, [
            //     'body' => $soapEnvelope
            // ]);
            ->timeout(150)
            ->withBody($soapEnvelope, 'text/xml')
            ->post($this->soapUrl);

            
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
    
    private function buildSoapEnvelope($messageId, $dataId)
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mul="http://creditinfo.com/schemas/2012/09/MultiConnector" xmlns:req="http://creditinfo.com/schemas/2012/09/MultiConnector/Messages/Request">
       <soapenv:Header>
          <wsse:Security soapenv:mustUnderstand="0" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
             <wsse:UsernameToken>
                <wsse:Username>' . $this->username . '</wsse:Username>
                <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">' . $this->password . '</wsse:Password>
             </wsse:UsernameToken>
          </wsse:Security>
       </soapenv:Header>
       <soapenv:Body>
          <mul:Query>
             <mul:request>
                <mul:MessageId>' . $messageId . '</mul:MessageId>
                <mul:RequestXml>
                   <req:connector id="' . $this->connectorId . '">
                      <req:data id="' . $dataId . '">
                         <request xmlns="http://creditinfo.com/schemas/2012/09/MultiConnector/Connectors/TZA/TRAGetMotorVehicleDetails/Request">
                            <VehicleRegistrationPlate>' . $this->vehicleRegistrationPlate . '</VehicleRegistrationPlate>
                            <DateOfRegistration>' . $this->dateOfRegistration . '</DateOfRegistration>
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



