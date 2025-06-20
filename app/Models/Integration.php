<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;

class Integration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'api_name',
        'description',
        'webhook_url',
        'http_method',
        'auth_type',
        'auth_username',
        'auth_password',
        'auth_token',
        'api_key_header',
        'api_key_value',
        'headers',
        'field_mappings',
        'trigger_conditions',
        'is_active',
        'timeout_seconds',
        'retry_attempts',
        'verify_ssl',
        'content_type',
    ];

    protected $casts = [
        'headers' => 'array',
        'field_mappings' => 'array',
        'trigger_conditions' => 'array',
        'is_active' => 'boolean',
        'verify_ssl' => 'boolean',
        'timeout_seconds' => 'integer',
        'retry_attempts' => 'integer',
    ];

    protected $hidden = [
        'auth_password',
        'auth_token',
        'api_key_value',
    ];

    /**
     * Get the user that owns the integration
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the integration logs
     */
    public function logs()
    {
        return $this->hasMany(IntegrationLog::class);
    }

    /**
     * Get decrypted auth password
     */
    public function getDecryptedPasswordAttribute()
    {
        return $this->auth_password ? Crypt::decryptString($this->auth_password) : null;
    }

    /**
     * Get decrypted auth token
     */
    public function getDecryptedTokenAttribute()
    {
        return $this->auth_token ? Crypt::decryptString($this->auth_token) : null;
    }

    /**
     * Get decrypted API key
     */
    public function getDecryptedApiKeyAttribute()
    {
        return $this->api_key_value ? Crypt::decryptString($this->api_key_value) : null;
    }

    /**
     * Set encrypted auth password
     */
    public function setAuthPasswordAttribute($value)
    {
        $this->attributes['auth_password'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Set encrypted auth token
     */
    public function setAuthTokenAttribute($value)
    {
        $this->attributes['auth_token'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Set encrypted API key
     */
    public function setApiKeyValueAttribute($value)
    {
        $this->attributes['api_key_value'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Get available application fields for mapping
     */
    public static function getAvailableFields()
    {
        return [
            'application_number' => 'Application Number',
            'status' => 'Application Status',
            'requested_amount' => 'Requested Amount',
            'requested_tenure_months' => 'Requested Tenure (Months)',
            'loan_purpose' => 'Loan Purpose',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'middle_name' => 'Middle Name',
            'date_of_birth' => 'Date of Birth',
            'gender' => 'Gender',
            'marital_status' => 'Marital Status',
            'national_id' => 'National ID',
            'phone_number' => 'Phone Number',
            'email' => 'Email Address',
            'current_address' => 'Current Address',
            'current_city' => 'Current City',
            'current_region' => 'Current Region',
            'employment_status' => 'Employment Status',
            'employer_name' => 'Employer Name',
            'job_title' => 'Job Title',
            'monthly_salary' => 'Monthly Salary',
            'monthly_expenses' => 'Monthly Expenses',
            'credit_score' => 'Credit Score',
            'bank_name' => 'Bank Name',
            'account_number' => 'Account Number',
            'created_at' => 'Application Date',
            'submitted_at' => 'Submission Date',
            'approved_at' => 'Approval Date',
            'lender.company_name' => 'Lender Company Name',
            'loanProduct.name' => 'Loan Product Name',
            'user.name' => 'Applicant Full Name',
        ];
    }

    /**
     * Check if integration should trigger for given conditions
     */
    public function shouldTrigger($triggerEvent, $application)
    {
        if (!$this->is_active) {
            return false;
        }

        $conditions = $this->trigger_conditions ?? [];
        
        // Default trigger for offer accepted
        if (empty($conditions)) {
            return $triggerEvent === 'offer_accepted';
        }

        // Check specific trigger conditions
        foreach ($conditions as $condition) {
            if ($this->evaluateCondition($condition, $triggerEvent, $application)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Evaluate a single trigger condition
     */
    private function evaluateCondition($condition, $triggerEvent, $application)
    {
        $field = $condition['field'] ?? null;
        $operator = $condition['operator'] ?? '=';
        $value = $condition['value'] ?? null;
        $event = $condition['event'] ?? 'offer_accepted';

        // Check if event matches
        if ($triggerEvent !== $event) {
            return false;
        }

        // If no field condition, just check event
        if (!$field) {
            return true;
        }

        $fieldValue = data_get($application, $field);

        switch ($operator) {
            case '=':
                return $fieldValue == $value;
            case '!=':
                return $fieldValue != $value;
            case '>':
                return $fieldValue > $value;
            case '>=':
                return $fieldValue >= $value;
            case '<':
                return $fieldValue < $value;
            case '<=':
                return $fieldValue <= $value;
            case 'contains':
                return str_contains(strtolower($fieldValue), strtolower($value));
            case 'starts_with':
                return str_starts_with(strtolower($fieldValue), strtolower($value));
            case 'ends_with':
                return str_ends_with(strtolower($fieldValue), strtolower($value));
            default:
                return false;
        }
    }
}
