<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionAnalysisController;





Route::post('transaction-analysis', [TransactionAnalysisController::class, 'store']);

