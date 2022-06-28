<?php

namespace App\Http\Controllers;

use App\Models\Finance_coa;
use App\Models\Finance_coa_history;
use App\Models\Finance_treasury;
use App\Models\Finance_treasury_history;
use Illuminate\Http\Request;
use Session;

class ReportTrialBalance extends Controller
{
    function index(Request $request){

        if($request->ajax()){
            $treasury = Finance_treasury::where('type', 'bank')
                ->where("company_id", Session::get("company_id"))
                ->orderBy('currency', 'asc')
                ->get();

            $history = Finance_treasury_history::whereIn('id_treasure', $treasury->pluck('id'))
                ->where('date_input', 'like', $request->year."-".sprintf("%02d", $request->month)."%")
                ->get();

            $period = date("F Y", strtotime( $request->year."-".sprintf("%02d", $request->month)));

            $tre_debit = [];
            $tre_credit = [];
            foreach ($history as $key => $value) {
                if($value->IDR > 0){
                    $tre_credit[$value->id_treasure][] = $value->IDR;
                }

                if($value->IDR < 0){
                    $tre_debit[$value->id_treasure][] = $value->IDR;
                }
            }

            $historyYTD = Finance_treasury_history::whereIn('id_treasure', $treasury->pluck('id'))
                ->where('date_input', "like", $request->year.'-%')
                ->where('date_input', '<=', date("Y-m-t", strtotime($request->year."-".sprintf("%02d", $request->month))))
                ->get();

            $tre_debitYTD = [];
            $tre_creditYTD = [];
            foreach ($historyYTD as $key => $value) {
                if($value->IDR > 0){
                    $tre_creditYTD[$value->id_treasure][] = $value->IDR;
                }

                if($value->IDR < 0){
                    $tre_debitYTD[$value->id_treasure][] = $value->IDR;
                }
            }

            $coa = Finance_coa::whereRaw("(status = 1 or status is null)")
                ->orderBy('code')
                ->get();

            $coaHis = Finance_coa_history::where("company_id", Session::get("company_id"))
                ->where('coa_date', 'like', $request->year."-".sprintf("%02d", $request->month)."%")
                ->get();

            $coa_debit = [];
            $coa_credit = [];
            foreach ($coaHis as $key => $value) {
                $coa_debit[$value->no_coa][] = $value->debit;
                $coa_credit[$value->no_coa][] = $value->credit;
            }

            $coaHisYTD = Finance_coa_history::where("company_id", Session::get("company_id"))
                ->where('coa_date', '<', date("Y-m-t", strtotime($request->year."-".sprintf("%02d", $request->month))))
                ->get();

            $coa_debitYTD = [];
            $coa_creditYTD = [];
            foreach ($coaHisYTD as $key => $value) {
                $coa_debitYTD[$value->no_coa][] = $value->debit;
                $coa_creditYTD[$value->no_coa][] = $value->credit;
            }

            return view("report.tb._search", compact('period', 'coa_debitYTD', 'coa_creditYTD', 'coa_debit', 'coa_credit', "treasury", "coa", "tre_debit", "tre_credit", "tre_debitYTD", "tre_creditYTD"));
        }

        return view("report.tb.index");
    }
}
