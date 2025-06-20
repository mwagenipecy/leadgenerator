<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransactionAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TransactionAnalysisController extends Controller
{
    /**
     * Store transaction analysis data
     */
    public function store(Request $request)
    {

        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'profile' => 'required|array',
                'profile.account' => 'required|string',
                '1d_analysis' => 'required|array',
                '2d_analysis' => 'required|array',
                '3d_analysis' => 'required|array',
                'affordability_scores' => 'required|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Extract account number
            $accountNumber = $request->input('profile.account');

            // Create transaction analysis record
            $transactionAnalysis = TransactionAnalysis::create([
                'account_number' => $accountNumber,
                'status' => 'success',
                'profile_data' => $request->input('profile'),
                'analysis_1d' => $request->input('1d_analysis'),
                'analysis_2d' => $request->input('2d_analysis'),
                'analysis_3d' => $request->input('3d_analysis'),
                'affordability_scores' => $request->input('affordability_scores'),
                'full_response' => $request->all()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Transaction analysis saved successfully',
                'data' => [
                    'id' => $transactionAnalysis->id,
                    'account_number' => $transactionAnalysis->account_number,
                    'created_at' => $transactionAnalysis->created_at
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Transaction Analysis Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // Save with failed status
            try {
                TransactionAnalysis::create([
                    'account_number' => $request->input('profile.account', 'unknown'),
                    'status' => 'failed',
                    'full_response' => $request->all()
                ]);
            } catch (\Exception $saveException) {
                Log::error('Failed to save error record: ' . $saveException->getMessage());
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process transaction analysis',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get transaction analysis by account number
     */
    public function show($accountNumber)
    {
        try {
            $analysis = TransactionAnalysis::where('account_number', $accountNumber)
                ->latest()
                ->first();

            if (!$analysis) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaction analysis not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $analysis
            ]);

        } catch (\Exception $e) {
            Log::error('Fetch Analysis Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch transaction analysis'
            ], 500);
        }
    }
}