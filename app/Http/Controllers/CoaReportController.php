<?php

namespace App\Http\Controllers;

use App\Models\Finance_coa;
use Illuminate\Http\Request;
use App\Models\Finance_coa_history;
use Illuminate\Support\Facades\Session;
use App\Models\Finance_treasury_history;

class CoaReportController extends Controller
{
    public function view($code){
        $coa_his = Finance_coa_history::where('no_coa', $code)
            ->where('company_id', Session::get('company_id'))
            ->get();

            $tre_hist = Finance_treasury_history::get()->pluck('description', 'id');

        $coa = Finance_coa::where('code', $code)->first();

        return view("report.coa.view", compact("coa_his", "coa", "tre_hist"));
    }
}
