<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LenderManagementController extends Controller
{
    public function index(){

        return view('pages.lenderManagement.index');
    }

    public function viewLender($lender){

        return view('pages.lenderManagement.viewLender', [
            'lender' => $lender
        ]);
    }
}
