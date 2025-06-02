<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoanProductManagementController extends Controller
{
    public function index(){
        return view('pages.loanProduct.index');
    }
}
