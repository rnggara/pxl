<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Models\Finance_coa;
use Illuminate\Http\Request;
use App\Models\Finance_bl_files;
use App\Models\Finance_treasury;
use App\Models\Marketing_project;
use App\Models\Finance_cf_setting;
use App\Models\Finance_coa_history;
use App\Models\Report_exchange_rate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Finance_treasury_history;

class FinanceCashflow extends Controller
{
    function index(Request $request){
        $coa = Finance_coa::orderBy('code', 'asc')->get();
        $coa_code = $coa->pluck('code', 'id');
        $coa_desc = $coa->pluck('name', 'code');
        $data_setting = Finance_cf_setting::where('company_id', Session::get('company_id'))->get();
        $setting = $data_setting->pluck('tc', 'name');
        $from = null;
        $to = null;

        $coa_his = Finance_coa_history::get();

        $projects = Marketing_project::where('company_id', Session::get('company_id'))
            ->get();
        $prj_selected = [];

        $data = [];

        if(isset($request->from_date)){
            $from = $request->from_date;
            $to = $request->to_date;
            $prj_selected = $request->project;
            $wherePrjCoa = " 1";
            $wherePrjHis = " 1";
            if (!empty($prj_selected)) {
                $wherePrjCoa = "(";
                foreach ($prj_selected as $key => $value) {
                    $wherePrjCoa .= " description like '%[$value]%' or";
                }
                $wherePrjCoa = substr($wherePrjCoa, 0, -2);
                $wherePrjCoa .= ")";
            }

            $tre_hist = Finance_treasury_history::get()->pluck('description', 'id');
            $tre_id = Finance_treasury_history::get()->pluck('id_treasure', 'id');
            $tre_name = Finance_treasury::get()->pluck('source', 'id');

            $rates = Report_exchange_rate::orderBy('id', 'desc')->first();
            $arrRates = [];
            if(!empty($rates)){
                $arrRates = json_decode($rates->rates, true);
            }

            foreach ($data_setting as $key => $value) {
                if (!empty($value->tc)) {
                    $tc = json_decode($value->tc);
                    foreach ($tc as $item) {
                        if (isset($coa_code[$item])) {
                            $code = $coa_code[$item];
                            $whereCode = rtrim($code, 0);
                            $hisCode = Finance_coa_history::where('no_coa', 'like', "$whereCode%")
                                ->whereRaw($wherePrjCoa)
                                ->where('company_id', Session::get("company_id"))
                                ->get();
                            if (!empty($hisCode)) {
                                if ($value->name == "saldo_awal") {
                                    foreach ($hisCode as $key => $his) {
                                        $row = [];
                                        $multiplier = (isset($arrRates[$his->currency]) && $his->currency != "IDR") ? floatval(str_replace(",", "", $arrRates[$his->currency])) : 1;
                                        $id_tre = (isset($tre_id[$his->id_treasure_history])) ? $tre_id[$his->id_treasure_history] : null;
                                        $trName = "";
                                        if(!empty($id_tre)){
                                            $trName = $tre_name[$id_tre];
                                        }
                                        $dsc = isset($tre_hist[$his->id_treasure_history]) ? $tre_hist[$his->id_treasure_history] : $his->description;
                                        $row['description'] = $dsc ." - ". $trName;
                                        $amount = (!empty($his->debit)) ? abs($his->debit) : abs($his->credit) * -1;
                                        $row['amount'] = $amount * $multiplier;
                                        $data[$value->name][] = $row;
                                    }
                                } else {
                                    $group = [];
                                    foreach ($hisCode as $key => $his) {
                                        $row = [];
                                        $multiplier = (isset($arrRates[$his->currency]) && $his->currency != "IDR") ? floatval(str_replace(",", "", $arrRates[$his->currency])) : 1;
                                        $amount = (!empty($his->debit)) ? abs($his->debit) : abs($his->credit) * -1;
                                        $group[$his->no_coa][] = $amount * $multiplier;
                                    }

                                    foreach ($group as $grKey => $gr) {
                                        if(isset($coa_desc[$grKey])){
                                            $row = [];
                                            $row['description'] = "<a href='".route('report.coa.view',  $grKey)."'> [$grKey] $coa_desc[$grKey] </a>";
                                            $row['amount'] = array_sum($gr);
                                            $data[$value->name][] = $row;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $tre_his = Finance_treasury_history::whereRaw("(approval_status = 0 or approval_status is null)")
                ->whereRaw($wherePrjCoa)
                ->get();
            $t_his = [];
            foreach ($tre_his as $key => $value) {
                $t_his[$value->id_treasure][] = $value->IDR;
            }
            $tre = Finance_treasury::where('company_id', Session::get('company_id'))->where('currency', 'IDR')
                ->get();
            foreach($tre as $item){
                $row = [];
                $row['description'] = $item->source;
                $amount = (isset($t_his[$item->id])) ? array_sum($t_his[$item->id]) : 0;
                $row['amount'] = $amount;
                $data['saldo_akhir'][] = $row;
            }
        }

        // $rate = Report_exchange_rate::orderBy('id')->first();
        // $rateUsd = 0;
        // if(!empty($rate)){
        //     $jsRate = (!empty($rate->rates)) ? json_decode($rate->rates, true) : [];
        //     if(!empty($jsRate) && isset($jsRate['USD'])){
        //         $rateUsd = str_replace(",", "", $jsRate['USD']);
        //     }
        // }

        return view('finance.cf.index', compact('coa', 'setting', 'coa_code', 'data', 'from', 'to', 'projects', 'prj_selected'));
    }

    function list(){
        $list = Finance_bl_files::where('company_id', Session::get('company_id'))
            ->where('type', 'c')
            ->orderBy('id', 'desc')
            ->get();

        return view('finance.cf.list', compact('list'));
    }

    function settings(Request $request){
        $setting = Finance_cf_setting::where('name', $request->type)
            ->where('company_id', Session::get('company_id'))
            ->first();
        if(empty($setting)){
            $setting = new Finance_cf_setting();
            $setting->name = $request->type;
            $setting->company_id = Session::get('company_id');
            $setting->created_by = Auth::user()->username;
        } else {
            $setting->updated_by = Auth::user()->username;
        }

        if(isset($request->tc)){
            if(!empty($request->tc)){
                $setting->tc = json_encode($request->tc);
            }
        } else {
            $setting->tc = null;
        }

        $setting->save();

        return redirect()->back();
    }

    function pdf(Request $request){
        $from = $request->from_date;
        $to = $request->to_date;

        $coa = Finance_coa::orderBy('code', 'asc')->get();
        $coa_code = $coa->pluck('code', 'id');
        $coa_desc = $coa->pluck('name', 'code');
        $data_setting = Finance_cf_setting::where('company_id', Session::get('company_id'))->get();
        $setting = $data_setting->pluck('tc', 'name');

        $coa_his = Finance_coa_history::get();

        $wherePrjCoa = " 1";
        if (!empty($request->projects)) {
            $wherePrjCoa = "(";
            foreach ($request->projects as $key => $value) {
                $wherePrjCoa .= " description like '%[$value]%' or";
            }
            $wherePrjCoa = substr($wherePrjCoa, 0, -2);
            $wherePrjCoa .= ")";
        }

        $data = [];

        $rates = Report_exchange_rate::orderBy('id', 'desc')->first();
        $arrRates = [];
        if(!empty($rates)){
            $arrRates = json_decode($rates->rates, true);
        }

        $tre_hist = Finance_treasury_history::get()->pluck('description', 'id');
        $tre_id = Finance_treasury_history::get()->pluck('id_treasure', 'id');
        $tre_name = Finance_treasury::get()->pluck('source', 'id');

        foreach ($data_setting as $key => $value) {
            if (!empty($value->tc)) {
                $tc = json_decode($value->tc);
                foreach ($tc as $item) {
                    if (isset($coa_code[$item])) {
                        $code = $coa_code[$item];
                        $whereCode = rtrim($code, 0);
                        $hisCode = Finance_coa_history::where('no_coa', 'like', "$whereCode%")
                            ->whereRaw($wherePrjCoa)
                            ->where('company_id', Session::get('company_id'))
                            ->get();
                        if (!empty($hisCode)) {
                            if ($value->name == "saldo_awal") {
                                foreach ($hisCode as $key => $his) {
                                    $row = [];
                                    $multiplier = (isset($arrRates[$his->currency]) && $his->currency != "IDR") ? floatval(str_replace(",", "", $arrRates[$his->currency])) : 1;
                                    $id_tre = (isset($tre_id[$his->id_treasure_history])) ? $tre_id[$his->id_treasure_history] : null;
                                    $trName = "";
                                    if(!empty($id_tre)){
                                        $trName = $tre_name[$id_tre];
                                    }
                                    $dsc = isset($tre_hist[$his->id_treasure_history]) ? $tre_hist[$his->id_treasure_history] : $his->description;
                                    $row['description'] = $dsc ." - ". $trName;
                                    $amount = (!empty($his->debit)) ? abs($his->debit) : abs($his->credit) * -1;
                                    $row['amount'] = $amount * $multiplier;
                                    $data[$value->name][] = $row;
                                }
                            } else {
                                $group = [];
                                foreach ($hisCode as $key => $his) {
                                    $row = [];
                                    $multiplier = (isset($arrRates[$his->currency]) && $his->currency != "IDR") ? floatval(str_replace(",", "", $arrRates[$his->currency])) : 1;
                                    $amount = (!empty($his->debit)) ? abs($his->debit) : abs($his->credit) * -1;
                                    $group[$his->no_coa][] = $amount * $multiplier;
                                }

                                foreach ($group as $grKey => $gr) {
                                    if(isset($coa_desc[$grKey])){
                                        $row = [];
                                        $row['description'] = "[$grKey] $coa_desc[$grKey]";
                                        $row['amount'] = array_sum($gr);
                                        $data[$value->name][] = $row;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $tre_his = Finance_treasury_history::whereRaw("(approval_status = 0 or approval_status is null)")
            ->whereRaw($wherePrjCoa)
            ->get();
        $t_his = [];
        foreach ($tre_his as $key => $value) {
            $t_his[$value->id_treasure][] = $value->IDR;
        }
        $tre = Finance_treasury::where('company_id', Session::get('company_id'))->where('currency', 'IDR')
            ->get();
        foreach($tre as $item){
            $row = [];
            $row['description'] = $item->source;
            $amount = (isset($t_his[$item->id])) ? array_sum($t_his[$item->id]) : 0;
            $row['amount'] = $amount;
            $data['saldo_akhir'][] = $row;
        }

        $pdf = view('finance.cf.pdf', compact('from', 'to', 'data'));

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($pdf);
        $file_name = 'media/reports/cashflow_'.date("Ymd", strtotime($from)).'_'.date("Ymd", strtotime($to)).'_'.date("Y_m_d_h_i").".pdf";
        $mpdf->Output($file_name, \Mpdf\Output\Destination::FILE);
        $bl_file = new Finance_bl_files();
        $bl_file->date_from = $from;
        $bl_file->date_to = $to;
        $bl_file->file = $file_name;
        $bl_file->type = "c";
        $bl_file->created_by = Auth::user()->username;
        $bl_file->company_id = Session::get('company_id');

        $return = 0;

        if ($bl_file->save()) {
            $return = 1;
        }

        return json_encode($return);

    }
}
