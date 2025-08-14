<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function viewLead($id){



        return view('pages.application.view-application',['leadId'=>$id]);
    }
}
