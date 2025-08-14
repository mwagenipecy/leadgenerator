<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class LoanApplicationController extends Controller
{
    public function index(){

        return view('pages.application.loan-application');
    }

    public function applicationList(){

        return view('pages.application.application-list');
    }

    public function applicationView($id){


        return view('pages.application.application-view', ['application' => Application::find($id)]);
        
    }


    public function createApplication(){
        return view('pages.application.create-application');
    }


    public function updateProfile(){
        return view('pages.application.profile');
    }

    public function completedApplications(){

        return view('pages.application.completed-applications');
    }

}
