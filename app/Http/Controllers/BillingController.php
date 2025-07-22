<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function billingSection(){
        return view('pages.billing.billing-section');
    }
}
