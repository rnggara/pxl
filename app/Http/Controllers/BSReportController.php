<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BSReportController extends Controller
{
    function index()
    {
        return view('report.bs.index');
    }
}
