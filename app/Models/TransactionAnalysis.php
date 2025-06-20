<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number',
        'status',
        'profile_data',
        'analysis_1d',
        'analysis_2d',
        'analysis_3d',
        'affordability_scores',
        'full_response'
    ];

    protected $casts = [
        'profile_data' => 'array',
        'analysis_1d' => 'array',
        'analysis_2d' => 'array',
        'analysis_3d' => 'array',
        'affordability_scores' => 'array',
        'full_response' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get formatted currency amount
     */
    public function formatCurrency($amount)
    {
        return 'TZS ' . number_format($amount, 0, '.', ',');
    }

    /**
     * Get total turnover formatted
     */
    public function getTotalTurnoverAttribute()
    {
        return $this->analysis_1d['customer_profile']['total_turnover'] ?? 0;
    }

    /**
     * Get wallet balance formatted
     */
    public function getWalletBalanceAttribute()
    {
        return $this->analysis_1d['customer_profile']['wallet_balance'] ?? 0;
    }

    /**
     * Get total transactions
     */
    public function getTotalTransactionsAttribute()
    {
        return $this->analysis_1d['customer_profile']['total_transactions'] ?? 0;
    }

    /**
     * Get affordability rank
     */
    public function getAffordabilityRankAttribute()
    {
        return $this->affordability_scores['rank'] ?? 'N/A';
    }

    /**
     * Get company name
     */
    public function getCompanyAttribute()
    {
        return $this->profile_data['company'] ?? 'Unknown';
    }

    /**
     * Get analysis period
     */
    public function getAnalysisPeriodAttribute()
    {
        $startDate = $this->profile_data['start_date'] ?? null;
        $endDate = $this->profile_data['end_date'] ?? null;
        
        if ($startDate && $endDate) {
            return [
                'start' => \Carbon\Carbon::parse($startDate)->format('d M Y'),
                'end' => \Carbon\Carbon::parse($endDate)->format('d M Y')
            ];
        }
        
        return null;
    }
}