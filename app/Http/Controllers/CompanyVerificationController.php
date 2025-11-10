<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyVerificationController extends Controller
{
    public function index()
    {
        return view('pages.company-verification.index');
    }
}
