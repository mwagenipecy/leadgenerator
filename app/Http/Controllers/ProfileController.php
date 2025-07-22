<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function viewProfile(){

        return view("pages.user.profile");
    }


    public function userSetting(){

        return view("pages.user.setting");
    }
    
}
