<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    
    public function webhookIntegration(){

        return view('pages.integrations.webhook-integration');
    }
}
