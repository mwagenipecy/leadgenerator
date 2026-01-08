<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoanProduct;

class LoanProductManagementController extends Controller
{
    public function index(){
        $user = Auth::user();
        
        // Check if user is a lender
        if (!$user->isLender() && !$user->hasRole('lender')) {
            abort(403, 'Only lenders can access loan products.');
        }
        
        // Check if user has a lender association
        if (!$user->lender) {
            abort(403, 'You must be associated with a lender to access loan products.');
        }
        
        return view('pages.loanProduct.index');
    }

    public function createProduct(){
        $user = Auth::user();
        
        // Check if user is a lender
        if (!$user->isLender() && !$user->hasRole('lender')) {
            abort(403, 'Only lenders can create loan products.');
        }
        
        // Check if user has a lender association
        if (!$user->lender) {
            abort(403, 'You must be associated with a lender to create loan products.');
        }
        
        return view('pages.loanProduct.create');
    }


    public function showProduct($id){
        $user = Auth::user();
        
        // Check if user is a lender
        if (!$user->isLender() && !$user->hasRole('lender')) {
            abort(403, 'Only lenders can view loan products.');
        }
        
        // Check if user has a lender association
        if (!$user->lender) {
            abort(403, 'You must be associated with a lender to view loan products.');
        }
        
        // Check if the product belongs to the user's lender
        $product = LoanProduct::where('id', $id)
            ->where('lender_id', $user->lender->id)
            ->where('status', '!=', 'deleted')
            ->firstOrFail();
        
        return view('pages.loanProduct.show', ['productId' => $id]);
    }


    public function editProduct($id){
        $user = Auth::user();
        
        // Check if user is a lender
        if (!$user->isLender() && !$user->hasRole('lender')) {
            abort(403, 'Only lenders can edit loan products.');
        }
        
        // Check if user has a lender association
        if (!$user->lender) {
            abort(403, 'You must be associated with a lender to edit loan products.');
        }
        
        // Check if the product belongs to the user's lender
        $product = LoanProduct::where('id', $id)
            ->where('lender_id', $user->lender->id)
            ->where('status', '!=', 'deleted')
            ->firstOrFail();
        
        // Check if lender has products (at least one product exists)
        $hasProducts = LoanProduct::where('lender_id', $user->lender->id)
            ->where('status', '!=', 'deleted')
            ->exists();
        
        if (!$hasProducts) {
            abort(403, 'You must have at least one product to perform this operation.');
        }
        
        return view('pages.loanProduct.edit', ['productId' => $id]);
    }

}
