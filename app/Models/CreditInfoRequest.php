<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditInfoRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'application_number',
        'national_id',
        'first_name',
        'last_name',
        'full_name',
        'date_of_birth',
        'phone_number',
        'message_id',
        'strategy_id',
        'status',
        'request_payload',
        'response_payload',
        'error_message',
        'requested_at',
        'responded_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'request_payload' => 'array',
        'response_payload' => 'array',
        'requested_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'loan_id');
    }

    // Accessor methods to extract data from JSON response
    public function getCipScoreAttribute()
    {
        return $this->response_payload['s:Envelope']['s:Body']['QueryResponse']['QueryResult']['ResponseXml']['response']['connector']['data']['response']['Extract']['CIPScore'] ?? null;
    }

    public function getCipGradeAttribute()
    {
        return $this->response_payload['s:Envelope']['s:Body']['QueryResponse']['QueryResult']['ResponseXml']['response']['connector']['data']['response']['Extract']['CIPGrade'] ?? null;
    }

    public function getMobileScoreAttribute()
    {
        return $this->response_payload['s:Envelope']['s:Body']['QueryResponse']['QueryResult']['ResponseXml']['response']['connector']['data']['response']['Extract']['MobileScore'] ?? null;
    }

    public function getMobileGradeAttribute()
    {
        return $this->response_payload['s:Envelope']['s:Body']['QueryResponse']['QueryResult']['ResponseXml']['response']['connector']['data']['response']['Extract']['MobileGrade'] ?? null;
    }

    public function getDecisionAttribute()
    {
        return $this->response_payload['s:Envelope']['s:Body']['QueryResponse']['QueryResult']['ResponseXml']['response']['connector']['data']['response']['Extract']['Decision'] ?? null;
    }

    public function getReferenceNumberAttribute()
    {
        return $this->response_payload['s:Envelope']['s:Body']['QueryResponse']['QueryResult']['ResponseXml']['response']['connector']['data']['response']['TzaCb5_data']['ReportInfo']['ReferenceNumber'] ?? null;
    }

    public function getTotalPastDueAmountAttribute()
    {
        return $this->response_payload['s:Envelope']['s:Body']['QueryResponse']['QueryResult']['ResponseXml']['response']['connector']['data']['response']['TzaCb5_data']['Dashboard']['PaymentsProfile']['PastDueAmountSum']['Value'] ?? null;
    }

    public function getWorstPastDueDaysAttribute()
    {
        return $this->response_payload['s:Envelope']['s:Body']['QueryResponse']['QueryResult']['ResponseXml']['response']['connector']['data']['response']['TzaCb5_data']['Dashboard']['PaymentsProfile']['WorstPastDueDaysCurrent'] ?? null;
    }

    public function getOpenContractsAttribute()
    {
        return $this->response_payload['s:Envelope']['s:Body']['QueryResponse']['QueryResult']['ResponseXml']['response']['connector']['data']['response']['TzaCb5_data']['ContractSummary']['Debtor']['OpenContracts'] ?? null;
    }

    public function getClosedContractsAttribute()
    {
        return $this->response_payload['s:Envelope']['s:Body']['QueryResponse']['QueryResult']['ResponseXml']['response']['connector']['data']['response']['TzaCb5_data']['ContractSummary']['Debtor']['ClosedContracts'] ?? null;
    }

    public function getContractDetailsAttribute()
    {
        $contracts = $this->response_payload['s:Envelope']['s:Body']['QueryResponse']['QueryResult']['ResponseXml']['response']['connector']['data']['response']['TzaCb5_data']['Contracts']['ContractList'] ?? null;
        
        if (!$contracts) {
            return [];
        }
        
        // Handle both single contract and multiple contracts
        if (isset($contracts['Contract'])) {
            return is_array($contracts['Contract']) && isset($contracts['Contract'][0]) 
                ? $contracts['Contract'] 
                : [$contracts['Contract']];
        }
        
        return [];
    }

    public function getPersonalInfoAttribute()
    {
        return $this->response_payload['s:Envelope']['s:Body']['QueryResponse']['QueryResult']['ResponseXml']['response']['connector']['data']['response']['TzaCb5_data']['Individual'] ?? null;
    }

    public function isSuccessful(): bool
    {
        return $this->status === 'success' && $this->response_payload !== null;
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}