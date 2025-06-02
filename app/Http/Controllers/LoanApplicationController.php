<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoanApplicationController extends Controller
{
    public function index(){

        return view('pages.application.loan-application');
    }

    public function applicationList(){

        return view('pages.application.application-list');
    }

}
