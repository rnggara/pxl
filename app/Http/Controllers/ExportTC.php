<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Finance_treasury;
use App\Models\Finance_coa_history;
use Illuminate\Support\Facades\Session;
use App\Models\Finance_treasury_history;

class ExportTC extends Controller
{
    function export($type, $code){
        $file_name = $code;
        $history = Finance_coa_history::where('no_coa', $code)
            ->where('company_id', Session::get('company_id'))
            ->orderBy('coa_date', 'desc')
            ->get();
        $c_t = $history->pluck('id_treasure_history');
        // dd($coa_history);

        $t_his = Finance_treasury_history::whereIn('id', $c_t)->pluck('description', 'id');

        return view('export.tc', compact('type', 'file_name', 'history', 't_his'));
    }
}
