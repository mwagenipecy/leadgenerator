<?php

namespace App\Services;

use App\Models\CommissionTransaction;
use App\Models\LenderCommissionSetting;
use App\Models\SystemSetting;
use App\Models\Application;
use Carbon\Carbon;

class CommissionService
{
    /**
     * Create commission transaction when loan is approved
     */
    public function createCommissionTransaction(Application $application)
    {
        if (!$application->lender_id) {
            throw new \Exception('Application must have a lender assigned');
        }

        // Check if commission already exists
        $existingCommission = CommissionTransaction::where('application_id', $application->id)->first();
        if ($existingCommission) {
            return $existingCommission;
        }

        $commissionAmount = $this->calculateCommission($application->lender_id, $application->requested_amount);
        $lenderSetting = LenderCommissionSetting::getForLender($application->lender_id);
        $defaults = LenderCommissionSetting::getDefaultSettings();

        $commissionType = $lenderSetting ? $lenderSetting->commission_type : $defaults['commission_type'];
        $commissionRate = null;

        if ($commissionType === 'percentage') {
            $commissionRate = $lenderSetting ? $lenderSetting->commission_percentage : $defaults['commission_percentage'];
        }

        $dueDays = (int) SystemSetting::getValue('payment_due_days', 30);

        return CommissionTransaction::create([
            'transaction_number' => CommissionTransaction::generateTransactionNumber(),
            'application_id' => $application->id,
            'lender_id' => $application->lender_id,
            'loan_amount' => $application->requested_amount,
            'commission_amount' => $commissionAmount,
            'commission_type' => $commissionType,
            'commission_rate' => $commissionRate,
            'status' => 'pending',
            'due_date' => now()->addDays($dueDays),
            'created_by' => auth()->id(),
            'metadata' => [
                'application_number' => $application->application_number,
                'loan_tenure' => $application->requested_tenure_months,
                'created_at_approval' => now()->toISOString(),
            ],
        ]);
    }

    /**
     * Calculate commission for a lender and loan amount
     */
    public function calculateCommission($lenderId, $loanAmount)
    {
        return LenderCommissionSetting::calculateForLender($lenderId, $loanAmount);
    }

    /**
     * Process overdue penalties
     */
    public function processOverduePenalties()
    {
        $overdueTransactions = CommissionTransaction::getOverdueTransactions()->get();
        $updated = 0;

        foreach ($overdueTransactions as $transaction) {
            $newPenalty = $transaction->calculatePenalty();
            
            if ($newPenalty != $transaction->penalty_amount) {
                $transaction->update([
                    'penalty_amount' => $newPenalty,
                    'status' => 'overdue'
                ]);
                $updated++;
            }
        }

        return $updated;
    }

    /**
     * Get commission summary for a lender
     */
    public function getLenderCommissionSummary($lenderId, $startDate = null, $endDate = null)
    {
        $query = CommissionTransaction::where('lender_id', $lenderId);

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $transactions = $query->get();

        return [
            'total_transactions' => $transactions->count(),
            'total_commission' => $transactions->sum('commission_amount'),
            'total_penalties' => $transactions->sum('penalty_amount'),
            'paid_amount' => $transactions->where('status', 'paid')->sum('commission_amount'),
            'pending_amount' => $transactions->where('status', 'pending')->sum('commission_amount'),
            'overdue_amount' => $transactions->where('status', 'overdue')->sum('commission_amount'),
            'transactions_by_status' => $transactions->groupBy('status')->map->count(),
        ];
    }

    /**
     * Get system commission summary
     */
    public function getSystemCommissionSummary($startDate = null, $endDate = null)
    {
        $query = CommissionTransaction::query();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $transactions = $query->get();

        return [
            'total_transactions' => $transactions->count(),
            'total_commission' => $transactions->sum('commission_amount'),
            'total_penalties' => $transactions->sum('penalty_amount'),
            'total_revenue' => $transactions->sum('commission_amount') + $transactions->sum('penalty_amount'),
            'paid_amount' => $transactions->where('status', 'paid')->sum('commission_amount'),
            'pending_amount' => $transactions->where('status', 'pending')->sum('commission_amount'),
            'overdue_amount' => $transactions->where('status', 'overdue')->sum('commission_amount'),
            'lenders_count' => $transactions->pluck('lender_id')->unique()->count(),
            'average_commission' => $transactions->avg('commission_amount'),
        ];
    }

    /**
     * Send commission notifications
     */
    public function sendCommissionNotifications()
    {
        // Implementation would depend on your notification system
        // This could send emails, SMS, or in-app notifications
        
        $upcomingDue = CommissionTransaction::where('status', 'pending')
            ->whereBetween('due_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
            ->with('lender')
            ->get();

        $overdue = CommissionTransaction::where('status', 'overdue')
            ->with('lender')
            ->get();

        // Send notifications logic here
        return [
            'upcoming_due_sent' => $upcomingDue->count(),
            'overdue_sent' => $overdue->count(),
        ];
    }
}