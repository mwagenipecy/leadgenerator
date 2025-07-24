<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoanProductManagementController extends Controller
{
    public function index(){
        return view('pages.loanProduct.index');
    }

    public function createProduct(){
        return view('pages.loanProduct.create');
    }


    public function showProduct($id){
        return view('pages.loanProduct.show', ['productId' => $id]);
    }


    public function editProduct($id){
        return view('pages.loanProduct.edit', ['productId' => $id]);
    }

}
