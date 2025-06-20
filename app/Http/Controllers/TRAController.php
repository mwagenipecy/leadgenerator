<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TRAController extends Controller
{
    
    public function lincenseVerification(){
        return view('pages.verification.lincense');
    }



    public function taxpayerVerification(){
        return view('pages.verification.taxpayer');
    }


    public function motorVehicleVerification(){
        return view('pages.verification.motor-vehicle');
    }

    
}
