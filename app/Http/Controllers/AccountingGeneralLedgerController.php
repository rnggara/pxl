<?php

namespace App\Http\Controllers;

use App\Models\Finance_coa;
use App\Models\Finance_coa_history;
use App\Models\Finance_treasury_history;
use App\Models\Report_exchange_rate;
use Illuminate\Http\Request;
use Session;

class AccountingGeneralLedgerController extends Controller
{
    function index(Request $request){
        $start_date = date('Y')."-01-01";
        $end_date = date('Y')."-12-31";
        if (isset($request->from_date)){
            $start_date = $request->from_date;
            $end_date = $request->to_date;
        }
        $list_coa = Finance_coa::where('status', 1)->get();
        $hist = Finance_coa_history::where('company_id', Session::get('company_id'))
            ->whereBetween('coa_date', [$start_date, $end_date])
            ->orderBy('coa_date')
            ->get();
        $tre_hist = Finance_treasury_history::where('company_id', Session::get('company_id'))->get();

        $data_tre = array();
        foreach ($tre_hist as $item){
            $data_tre[$item->id] = $item;
        }

        $exchange = Report_exchange_rate::orderBy('date_rate', 'desc')->first();
        $rates = [];
        if(!empty($exchange->rates)){
            $js = json_decode($exchange->rates, true);
        }

        $data_hist = array();
        foreach ($hist as $key => $value){
            $data['id'] = $value->id;
            $data['date'] = $value->coa_date;
            if ($value->description == null){
                $data['desc'] = (isset($data_tre[$value->id_treasure_history])) ? $data_tre[$value->id_treasure_history]->description : "N/A";
                $data['ref_id'] = $value->id_treasure_history;
                $data['source'] = "B";
            } else {
                $data['desc'] = $value->description;
                $data['ref_id'] = $value->md5;
                $data['source'] = "J";
            }

            $multiply = 1;
            if($value->currency != "IDR" || $value->currency != ""){
                if(isset($js[$value->currency])){
                    $multiply = str_replace(",", "", $js[$value->currency]);
                }
            }

            $data['project'] = $value->project;
            $data['debit'] = $value->debit * $multiply;
            $data['credit'] = $value->credit * $multiply;
            $data_hist[$value->no_coa][] = $data;
        }

        return view(
            'finance.general_ledger.index',
            [
                'list_coa' => $list_coa,
                'data_history' => $data_hist
            ]
        );
    }

    function edit(Request $request){
//        dd($request);

        $coa = Finance_coa_history::find($request->id);
        $coa->debit = ($request->debit == 0) ? null : $request->debit;
        $coa->credit = ($request->credit == 0) ? null : $request->credit;

        if ($coa->save()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

}
