<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Finance_coa;
use Illuminate\Http\Request;
use App\Models\Finance_leasing;
use App\Models\Marketing_project;
use Illuminate\Support\Facades\Auth;
use App\Models\Finance_leasing_detail;

class FinanceLeasingController extends Controller
{
    function index(){
        $loan = Finance_leasing::where('company_id', Session::get('company_id'))
            ->orderBy('id', 'desc')
            ->get();
        $plan_date = array();
        $loan_det = Finance_leasing_detail::all();
//        dd($loan_det);
        foreach ($loan_det as $item) {
            $id_det[$item->id_leasing] = $item->id;
//            dd($id_det);
            if ($item->status == 'paid'){
                $plan_date[$item->id_leasing]['paid'][] = $item->plan_date;
            } else {
                $plan_date[$item->id_leasing]['planned'][] = $item->plan_date;
            }
        }

        $coa = Finance_coa::all();

        $projects = Marketing_project::where('company_id', Session::get('company_id'))->get();

        return view('finance.leasing.index', [
            'loans' => $loan,
            'plan_date' => $plan_date,
            'coa' => $coa,
            'projects' => $projects
        ]);
    }

    function add(Request $request){
//        dd($request);
        if (isset($request['edit'])){
            $loan = Finance_leasing::find($request['edit']);
            $loan->updated_by = Auth::user()->username;
        } else {
            $loan = new Finance_leasing();
            $loan->created_by = Auth::user()->username;
            $loan->company_id = Session::get('company_id');
        }
        $loan->subject = $request->subject;
        $loan->value = str_replace(",", "", $request->price);
        $loan->bunga = $request->int_percentage;
        $loan->start = $request->loan_start;
        $loan->period = $request->loan_duration;
        $loan->vendor = $request->vendor;
        $loan->currency = $request->currency;
        $loan->tc_id = $request->tc_id;
        $loan->project = $request->project;
        $loan->cicil_start = 0;
        $loan->dp = str_replace(",", "", $request->dp);
        $loan->adm = str_replace(",", "", $request->ac);
        $loan->insurance = str_replace(",", "", $request->ic);
        if($loan->save() && isset($request->tc_id)){
            // $coa = Finance_coa::find($request->tc_id);

            // $coa_code = $coa->code;

            // $coa = Finance_coa_history::where('paper_type', "LEASING")
            //     ->where('paper_id', $loan->id)
            //     ->first();

            // if(empty($coa)){
            //     $coa = new Finance_coa_history();
            //     $coa->paper_type = "LEASING";
            //     $coa->paper_id = $loan->id;
            //     $coa->description = "LEASING : ".$loan->subject;
            //     $coa->coa_date = date("Y-m-d");
            //     $coa->debit = $loan->value;
            //     $coa->currency = $loan->currency;
            //     $coa->company_id = $loan->company_id;
            //     $coa->created_by = Auth::user()->username;
            // } else {
            //     $coa->updated_by = Auth::user()->username;
            // }

            // $coa->no_coa = $coa_code;
            // $coa->save();
        }

        return redirect()->route('leasing.index');
    }



    function detail($id){
        $loan = Finance_leasing::where('id', $id)->first();

        $per_bayar = array();
        $bulan_now = date('m', strtotime($loan->start));
        $YY = date('Y', strtotime($loan->start));

        for ($i = 1; $i <= $loan->period; $i++) {
            if($bulan_now > 12) { $bulan_now = 1; $YY = $YY+1;}
            $DD = date('d', strtotime($loan->start));
            if ($DD >= 31){
                $DD = date('t', strtotime($YY."-".$bulan_now."-01"));
            } else {
                $DD = date('d', strtotime($loan->start));
            }
            // $per_bayar[$i] = $YY . "-" . str_pad($bulan_now, 2, "0", STR_PAD_LEFT) . "-" . str_pad($DD, 2, "0", STR_PAD_LEFT);
            $per_bayar[$i] = $DD . "-" . str_pad($bulan_now, 2, "0", STR_PAD_LEFT) . "-" . str_pad($YY, 2, "0", STR_PAD_LEFT);
            // $per_bayar[$i] = date('d M Y', strtotime($per_bayar[$i]));
            $bulan_now     = $bulan_now + 1;
        }

        $loan_det = Finance_leasing_detail::where('id_leasing', $id)
            ->orderBy('n_cicil')
            ->get();

        return view('finance.leasing.view', [
            'loan' => $loan,
            'perbayar' => $per_bayar,
            'loan_item' => $loan_det
        ]);
    }

    function save_plan(Request $request){
        $plan_date = $request->plan_date;
        $cicilan = $request->installment;
        $rate = $request->rate;
        $bunga = $request->interest;

        foreach ($plan_date as $key => $value){
            $loan_det = new Finance_leasing_detail();
            $loan_det->id_leasing = $request->loan;
            $loan_det->cicilan = $cicilan[$key];
            $loan_det->bunga_rate = $rate[$key];
            $loan_det->bunga = $bunga[$key];
            $loan_det->status = "planned";
            $loan_det->n_cicil = $key;
            $loan_det->plan_date = $value;
            $loan_det->created_by = Auth::user()->username;
            $loan_det->company_id = Session::get('company_id');
            $loan_det->save();
        }

        return redirect()->route('leasing.detail', $request->loan);
    }

    function update_plan(Request $request){
        $id_detail = $request->id_det;
        $plan_date = $request->plan_date;
        $cicilan = $request->installment;
        $rate = $request->rate;
        $bunga = $request->interest;
        foreach ($id_detail as $value){
            $loan_det = Finance_leasing_detail::find($value);
            $loan_det->cicilan = str_replace(",", "", $cicilan[$value]);
            $loan_det->bunga_rate = str_replace(",", "", $rate[$value]);
            $loan_det->bunga = str_replace(',', "", $bunga[$value]);
            $loan_det->updated_by = Auth::user()->username;
            $loan_det->save();
        }

        return redirect()->route('leasing.detail', $request->loan);
    }

    function edit_plan($id){
        $loan = Finance_leasing::where('id', $id)->first();

        $loan_det = Finance_leasing_detail::where('id_leasing', $id)->get();

        return view('finance.leasing.edit', [
            'loan' => $loan,
            'loan_item' => $loan_det
        ]);
    }

    function delete(Request $request){
        $id = $request->val;
        Finance_leasing::find($id)->delete();
        Finance_leasing_detail::where('id_leasing', $id)->delete();
        $data['error'] = 0;
        return json_encode($data);
    }
}
