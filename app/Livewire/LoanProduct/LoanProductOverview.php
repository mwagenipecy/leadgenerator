<?php

namespace App\Livewire\LoanProduct;

use App\Models\LoanProduct;
use App\Models\Application;
use Livewire\Component;

class LoanProductOverview extends Component
{
    public $productId;
    public $product;
    
    // Modal states
    public $showDeleteModal = false;
    public $showActivateModal = false;

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->product = LoanProduct::findOrFail($productId);
    }

    public function render()
    {
        // Get product statistics   app/Livewire/LoanProduct/LoanProductOverview.php
        $applicationStats = $this->getApplicationStats();
        $performanceMetrics = $this->getPerformanceMetrics();
        
        return view('livewire.loan-product.loan-product-overview', [
            'product' => $this->product,
            'applicationStats' => $applicationStats,
            'performanceMetrics' => $performanceMetrics,
            'documentTypes' => LoanProduct::getAvailableDocumentTypes(),
            'collateralTypes' => LoanProduct::getAvailableCollateralTypes(),
            'businessSectors' => LoanProduct::getAvailableBusinessSectors(),
        ]);
    }

    private function getApplicationStats()
    {
        $applications = Application::where('loan_product_id', $this->productId);
        
        return [
            'total_applications' => $applications->count(),
            'pending_applications' => $applications->where('status', 'submitted')->count(),
            'approved_applications' => $applications->where('status', 'approved')->count(),
            'rejected_applications' => $applications->where('status', 'rejected')->count(),
            'disbursed_applications' => $applications->where('status', 'disbursed')->count(),
            'total_disbursed_amount' => $applications->where('status', 'disbursed')->sum('requested_amount'),
            'average_loan_amount' => $applications->where('status', 'disbursed')->avg('requested_amount'),
            'average_processing_time' => $this->getAverageProcessingTime(),
            'success_rate' => $this->getSuccessRate(),
        ];
    }

    private function getPerformanceMetrics()
    {
        $applications = Application::where('loan_product_id', $this->productId);
        $totalApplications = $applications->count();
        
        if ($totalApplications == 0) {
            return [
                'success_rate' => 0,
                'average_amount' => 0,
                'total_volume' => 0,
                'monthly_trend' => [],
                'age_distribution' => [],
                'employment_distribution' => [],
                'rating' => 0
            ];
        }

        return [
            'success_rate' => round(($applications->whereIn('status', ['approved', 'disbursed'])->count() / $totalApplications) * 100, 1),
            'average_amount' => $applications->where('status', 'disbursed')->avg('requested_amount') ?? 0,
            'total_volume' => $applications->where('status', 'disbursed')->sum('requested_amount') ?? 0,
            'monthly_trend' => $this->getMonthlyTrend(),
            'age_distribution' => $this->getAgeDistribution(),
            'employment_distribution' => $this->getEmploymentDistribution(),
            'rating' => $this->calculateProductRating()
        ];
    }

    private function getAverageProcessingTime()
    {
        $applications = Application::where('loan_product_id', $this->productId)
            ->whereNotNull('submitted_at')
            ->whereNotNull('reviewed_at');

        if ($applications->count() == 0) {
            return 0;
        }

        $totalDays = 0;
        foreach ($applications->get() as $app) {
            $totalDays += $app->submitted_at->diffInDays($app->reviewed_at);
        }

        return round($totalDays / $applications->count(), 1);
    }

    private function getSuccessRate()
    {
        $total = Application::where('loan_product_id', $this->productId)->count();
        if ($total == 0) return 0;
        
        $approved = Application::where('loan_product_id', $this->productId)
            ->whereIn('status', ['approved', 'disbursed'])->count();
            
        return round(($approved / $total) * 100, 1);
    }

    private function getMonthlyTrend()
    {
        // Get last 6 months of application data
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Application::where('loan_product_id', $this->productId)
                ->whereYear('submitted_at', $date->year)
                ->whereMonth('submitted_at', $date->month)
                ->count();
                
            $months[] = [
                'month' => $date->format('M'),
                'applications' => $count
            ];
        }
        
        return $months;
    }

    private function getAgeDistribution()
    {
        $applications = Application::where('loan_product_id', $this->productId)
            ->whereNotNull('date_of_birth')
            ->get();

        $distribution = [
            '18-25' => 0,
            '26-35' => 0,
            '36-45' => 0,
            '46-55' => 0,
            '55+' => 0
        ];

        foreach ($applications as $app) {
            $age = now()->diffInYears($app->date_of_birth);
            
            if ($age <= 25) $distribution['18-25']++;
            elseif ($age <= 35) $distribution['26-35']++;
            elseif ($age <= 45) $distribution['36-45']++;
            elseif ($age <= 55) $distribution['46-55']++;
            else $distribution['55+']++;
        }

        return $distribution;
    }

    private function getEmploymentDistribution()
    {
        $applications = Application::where('loan_product_id', $this->productId)->get();
        
        $distribution = [
            'employed' => 0,
            'self_employed' => 0,
            'unemployed' => 0,
            'other' => 0
        ];

        foreach ($applications as $app) {
            if (isset($distribution[$app->employment_status])) {
                $distribution[$app->employment_status]++;
            } else {
                $distribution['other']++;
            }
        }

        return $distribution;
    }

    private function calculateProductRating()
    {
        $successRate = $this->getSuccessRate();
        $avgProcessingTime = $this->getAverageProcessingTime();
        $totalApplications = Application::where('loan_product_id', $this->productId)->count();
        
        // Rating algorithm (out of 5)
        $rating = 0;
        
        // Success rate component (40% weight)
        if ($successRate >= 80) $rating += 2;
        elseif ($successRate >= 60) $rating += 1.5;
        elseif ($successRate >= 40) $rating += 1;
        elseif ($successRate >= 20) $rating += 0.5;
        
        // Processing time component (30% weight)
        if ($avgProcessingTime <= 2) $rating += 1.5;
        elseif ($avgProcessingTime <= 5) $rating += 1;
        elseif ($avgProcessingTime <= 10) $rating += 0.5;
        
        // Volume component (30% weight)
        if ($totalApplications >= 100) $rating += 1.5;
        elseif ($totalApplications >= 50) $rating += 1;
        elseif ($totalApplications >= 10) $rating += 0.5;
        
        return min(5, round($rating, 1));
    }

    public function editProduct()
    {
        return redirect()->route('loan-products.edit', $this->productId);
    }

    public function backToList()
    {
        return redirect()->route('loan.product.index');
    }

    // Modal actions
    public function confirmDelete()
    {
        $this->showDeleteModal = true;
    }

    public function confirmActivate()
    {
        $this->showActivateModal = true;
    }

    public function deleteProduct()
    {
        $this->product->delete();
        session()->flash('message', 'Product deleted successfully!');
        return redirect()->route('loan.product.index');
    }

    public function toggleProductStatus()
    {
        $this->product->update(['is_active' => !$this->product->is_active]);
        $status = $this->product->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "Product {$status} successfully!");
        $this->closeActivateModal();
        
        // Refresh product data
        $this->product = $this->product->fresh();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
    }

    public function closeActivateModal()
    {
        $this->showActivateModal = false;
    }
}