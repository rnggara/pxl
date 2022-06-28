<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinanceFillingDocumentController extends Controller
{
    function index(){
        return view('finance.filling_document.index');
    }
}
