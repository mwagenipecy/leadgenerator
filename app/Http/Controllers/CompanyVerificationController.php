<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class CompanyVerificationController extends Controller
{
    public function index()
    {
        return view('pages.company-verification.index');
    }

    public function show(User $user)
    {
        // Load related documents
        $user->load('companyVerificationDocuments');
        
        return view('pages.company-verification.show', [
            'company' => $user
        ]);
    }
}
