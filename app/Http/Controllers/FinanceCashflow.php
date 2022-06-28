<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Models\Asset_po;
use App\Models\Asset_wo;
use App\Models\Finance_coa;
use Illuminate\Http\Request;
use App\Models\Finance_bl_files;
use App\Models\Finance_cf_lock;
use App\Models\Finance_treasury;
use App\Models\Marketing_project;
use App\Models\Finance_cf_setting;
use App\Models\Finance_invoice_in;
use App\Models\Finance_coa_history;
use App\Models\Finance_invoice_out;
use App\Models\Finance_leasing;
use App\Models\Finance_loan;
use App\Models\Finance_treasure_sp;
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
        $setting = [];
        $from = null;
        $to = null;

        $coa_his = Finance_coa_history::get();

        $projects = Marketing_project::where('company_id', Session::get('company_id'))
            ->get();
        $prj_selected = [];

        $data = [];

        $cash = ["cash_in", "cash_out"];

        $treasury = Finance_treasury::where("company_id", Session::get("company_id"))
            ->where('type', 'bank')
            ->orderBy('currency')
            ->get();

        $source = ["po", "wo", "invoice_out", "loan", "leasing",];

        $st = Finance_cf_setting::where('company_id', Session::get("company_id"))
            ->whereNull('parent')
            ->orderBy('order_num')
            ->orderBy('id')
            ->get();

        foreach($st as $item){
            $item->child = Finance_cf_setting::where('parent', $item->id)
                ->orderBy('order_num')
                ->orderBy('id')
                ->get();
            $setting[$item->parent_type][] = $item;
        }

        $data = [];

        $ynow = date("Y");

        if($request->search == 1){

            $hist_curr = Finance_treasury_history::where('company_id', Session::get('company_id'))
                    ->withTrashed()
                    ->get()
                    ->pluck('id_treasure', 'id');

            $st = Finance_cf_setting::where('company_id', Session::get("company_id"))->get();

            $sp = Finance_treasure_sp::where('company_id', Session::get("company_id"))
                // ->whereNull('num')
                ->get();

            $bank_curr = Finance_treasury::where('company_id', Session::get("company_id"))
                ->get()
                ->pluck('currency', 'id');


            $hist = Finance_coa_history::where("company_id", Session::get("company_id"))
                ->get();

            $_his = [];

            foreach ($hist as $key => $value) {
                $dkey = date("Y-m", strtotime($value->coa_date));
                $_his[$dkey][$value->no_coa][] = $value;
            }

            $opbl = Finance_treasury_history::where('company_id', Session::get('company_id'))
                ->where('date_input', 'like', $request->_year."-%")
                ->where('description', 'like', '%open%')
                ->get();
            $op = [];
            foreach($opbl as $item){
                $curr = $bank_curr[$item->id_treasure];
                $op[$curr][$item->id_treasure] = $item->IDR;
            }

            $coa_code = Finance_coa::all()->pluck('code', 'id');

            $pinbuk = $st->where('label', 'pinbuk');

            $ynow = $request->_year;

            for ($imonth=1; $imonth <= 12; $imonth++) {

                $locked = Finance_cf_lock::where('year', $request->_year)
                    ->where('month', $imonth)
                    ->first();
                if(empty($locked)){
                    $locked = new Finance_cf_lock();
                    $locked->year = $request->_year;
                    $locked->month = $imonth;
                    $locked->lock_status = 0;
                    $locked->company_id = Session::get('company_id');
                    $locked->created_by = Auth::user()->username;
                    $locked->save();
                }

                $cashType = ["begin", "cash_in", "cash_out", "end"];
                // get saldo awal
                $mnth = $request->_year."-".sprintf("%02d", $imonth)."-01";
                $mnthEnd = $request->_year."-".sprintf("%02d", ($imonth + 1))."-01";

                $beginBl = [];
                $bl = [];
                $endBl = [];
                $pnbkBl = [];

                $spOpen = $sp->where('date1', $mnth)->where('date2', $mnth);
                foreach ($spOpen as $key => $value) {
                    $beginBl[$value->bank] = (empty($value->saldo)) ? 0 : $value->saldo;
                    $currency = $bank_curr[$value->bank];
                    $bl["begin"][$currency][$value->bank] = (empty($value->saldo)) ? 0 : $value->saldo;
                }

                $spEnd = $sp->where('date1', $mnthEnd)->where('date2', $mnthEnd);

                foreach ($spEnd as $key => $value) {
                    $endBl[$value->bank] = (empty($value->saldo)) ? 0 : $value->saldo;
                    $currency = $bank_curr[$value->bank];
                    $bl["end"][$currency][$value->bank] = (empty($value->saldo)) ? 0 : $value->saldo;
                }

                $dkey = $request->_year."-".sprintf("%02d", $imonth);

                if(!empty($pinbuk)){
                    foreach($pinbuk as $pnbk){
                        if(!empty($pnbk->tc)){
                            $tc_pinbuk = json_decode($pnbk->tc, true);
                            foreach($tc_pinbuk as $coa_pnbk){
                                for ($i=0; $i < count($coa_pnbk); $i++) {
                                    if(isset($coa_code[$coa_pnbk[$i]])){
                                        $ccode = $coa_code[$coa_pnbk[$i]];
                                        if (isset($_his[$dkey])) {
                                            $hiskey = $_his[$dkey];
                                            if(isset($hiskey[$ccode])){
                                                foreach($hiskey[$ccode] as $val){
                                                    $id_bank = (isset($hist_curr[$val->id_treasure_history])) ? $hist_curr[$val->id_treasure_history] : 0;
                                                    if(!empty($val->currency)){
                                                        $curr = $val->currency;
                                                    } else {
                                                        $curr = (isset($bank_curr[$id_bank])) ? $bank_curr[$id_bank] : "";
                                                    }

                                                    $_row = [];
                                                    $_row['description'] = $val->description;
                                                    $_row['history'] = $val->id_treasure_history;
                                                    $_row['id_bank'] = $id_bank;
                                                    $_row['currency'] = $curr;
                                                    $_row['credit'] = abs($val->credit);
                                                    $_row['debit'] = abs($val->debit);

                                                    if ($locked->lock_status == 1) {
                                                        if($val->locked == 1){
                                                            $pnbkBl[$id_bank][$dkey][] = $_row;
                                                        }
                                                    } else {
                                                        $pnbkBl[$id_bank][$dkey][] = $_row;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $nonproject = ['11200000000', '21200000000', '31100000000', '41100000000'];

                foreach($st->whereNotNull('parent') as $item){
                    $tc_id = (!empty($item->tc)) ? json_decode($item->tc, true) : [];
                    $prj = (!empty($item->type)) ? json_decode($item->type, true) : [];


                    $amountIDR = [];
                    $amountUSD = [];
                    if(!empty($prj)){
                        for ($i=0; $i < count($prj); $i++) {
                            $tc_prj = $tc_id[$i];
                            foreach ($tc_id[$i] as $tc_prj) {
                                $_code = $coa_code[$tc_prj];
                                $coa_trim = rtrim($_code, 0);
                                if(substr($coa_trim, -2, 1) != 0){
                                    $coa_trim .= "0";
                                }

                                if (isset($_his[$dkey])) {
                                    $hiskey = $_his[$dkey];
                                    foreach($hiskey as $keyhiskey => $checkhiskey){
                                        $hisforeach = [];
                                        if(in_array($keyhiskey, $nonproject)){
                                            if($keyhiskey == $_code){
                                                $hisforeach = $checkhiskey;
                                            }
                                        } else {
                                            if($coa_trim == substr($keyhiskey, 0, strlen($coa_trim)) && !in_array($keyhiskey, $nonproject)){
                                                $hisforeach = $checkhiskey;
                                            }
                                        }
                                        if(!empty($hisforeach)){
                                            foreach ($hisforeach as $val) {
                                                if($val->project == $prj[$i]){
                                                    $id_bank = (isset($hist_curr[$val->id_treasure_history])) ? $hist_curr[$val->id_treasure_history] : 0;
                                                    if(!empty($val->currency)){
                                                        $curr = $val->currency;
                                                    } else {
                                                        $curr = (isset($bank_curr[$id_bank])) ? $bank_curr[$id_bank] : "";
                                                    }

                                                    $_row = [];
                                                    $_row['description'] = $val->description;
                                                    $_row['history'] = $val->id_treasure_history;
                                                    $_row['id_bank'] = $id_bank;
                                                    $_row['currency'] = $curr;
                                                    $_row['credit'] = abs($val->credit);
                                                    $_row['debit'] = abs($val->debit);

                                                    if ($locked->lock_status == 1) {
                                                        if($val->locked == 1 && $val->id_cf == $item->id){
                                                            if($curr == "IDR"){
                                                                $amountIDR[] = $_row;
                                                            } else {
                                                                $amountUSD[] = $_row;
                                                            }
                                                        }
                                                    } else {
                                                        if($curr == "IDR"){
                                                            $amountIDR[] = $_row;
                                                        } else {
                                                            $amountUSD[] = $_row;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    // if (isset($hiskey[$_code])) {
                                    //     foreach ($hiskey[$_code] as $val) {
                                    //         if($val->project == $prj[$i]){
                                    //             $id_bank = (isset($hist_curr[$val->id_treasure_history])) ? $hist_curr[$val->id_treasure_history] : 0;
                                    //             if(!empty($val->currency)){
                                    //                 $curr = $val->currency;
                                    //             } else {
                                    //                 $curr = (isset($bank_curr[$id_bank])) ? $bank_curr[$id_bank] : "";
                                    //             }

                                    //             $_row = [];
                                    //             $_row['description'] = $val->description;
                                    //             $_row['history'] = $val->id_treasure_history;
                                    //             $_row['id_bank'] = $id_bank;
                                    //             $_row['currency'] = $curr;
                                    //             $_row['credit'] = abs($val->credit);
                                    //             $_row['debit'] = abs($val->debit);

                                    //             if ($locked->lock_status == 1) {
                                    //                 if($val->locked == 1){
                                    //                     if($curr == "IDR"){
                                    //                         $amountIDR[] = $_row;
                                    //                     } else {
                                    //                         $amountUSD[] = $_row;
                                    //                     }
                                    //                 }
                                    //             } else {
                                    //                 if($curr == "IDR"){
                                    //                     $amountIDR[] = $_row;
                                    //                 } else {
                                    //                     $amountUSD[] = $_row;
                                    //                 }
                                    //             }
                                    //         }
                                    //     }
                                    // }
                                }
                            }

                        }
                    } else {
                        for ($i=0; $i < count($tc_id); $i++) {

                            foreach ($tc_id[$i] as $tc_prj) {
                                $_code = $coa_code[$tc_prj];
                                $coa_trim = rtrim($_code, 0);
                                if(substr($coa_trim, -2, 1) != 0){
                                    $coa_trim .= "0";
                                }

                                if (isset($_his[$dkey])) {
                                    $hiskey = $_his[$dkey];
                                    foreach($hiskey as $keyhiskey => $checkhiskey){
                                        if($coa_trim == substr($keyhiskey, 0, strlen($coa_trim))){
                                            foreach ($checkhiskey as $val) {
                                                $id_bank = (isset($hist_curr[$val->id_treasure_history])) ? $hist_curr[$val->id_treasure_history] : 0;
                                                if(!empty($val->currency)){
                                                    $curr = $val->currency;
                                                } else {
                                                    $curr = (isset($bank_curr[$id_bank])) ? $bank_curr[$id_bank] : "";
                                                }

                                                $_row = [];
                                                $_row['description'] = $val->description;
                                                $_row['history'] = $val->id_treasure_history;
                                                $_row['id_bank'] = $id_bank;
                                                $_row['currency'] = $curr;
                                                $_row['credit'] = abs($val->credit);
                                                $_row['debit'] = abs($val->debit);

                                                if ($locked->lock_status == 1) {
                                                    if($val->locked == 1 && $val->id_cf == $item->id){
                                                        if($curr == "IDR"){
                                                            $amountIDR[] = $_row;
                                                        } else {
                                                            $amountUSD[] = $_row;
                                                        }
                                                    }
                                                } else {
                                                    if($curr == "IDR"){
                                                        $amountIDR[] = $_row;
                                                    } else {
                                                        $amountUSD[] = $_row;
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    // if (isset($hiskey[$_code])) {
                                    //     foreach ($hiskey[$_code] as $val) {
                                    //         $id_bank = (isset($hist_curr[$val->id_treasure_history])) ? $hist_curr[$val->id_treasure_history] : 0;
                                    //         if(!empty($val->currency)){
                                    //             $curr = $val->currency;
                                    //         } else {
                                    //             $curr = (isset($bank_curr[$id_bank])) ? $bank_curr[$id_bank] : "";
                                    //         }

                                    //         $_row = [];
                                    //         $_row['description'] = $val->description;
                                    //         $_row['history'] = $val->id_treasure_history;
                                    //         $_row['id_bank'] = $id_bank;
                                    //         $_row['currency'] = $curr;
                                    //         $_row['credit'] = abs($val->credit);
                                    //         $_row['debit'] = abs($val->debit);

                                    //         if ($locked->lock_status == 1) {
                                    //             if($val->locked == 1){
                                    //                 if($curr == "IDR"){
                                    //                     $amountIDR[] = $_row;
                                    //                 } else {
                                    //                     $amountUSD[] = $_row;
                                    //                 }
                                    //             }
                                    //         } else {
                                    //             if($curr == "IDR"){
                                    //                 $amountIDR[] = $_row;
                                    //             } else {
                                    //                 $amountUSD[] = $_row;
                                    //             }
                                    //         }
                                    //     }
                                    // }
                                }
                            }
                        }
                    }

                    $bl[$item->parent_type]['IDR'][$item->id] = $amountIDR;
                    $bl[$item->parent_type]['USD'][$item->id] = $amountUSD;
                }


                $data[$imonth] = [
                    "locked" => $locked->lock_status,
                    "year" => $request->_year,
                    "period" => $imonth,
                    "data" => $bl,
                    "op" => $op,
                    "pinbuk" => $pnbkBl
                ];

            }

            if(isset($request->_pdf) && $request->_pdf == 1){
                $pdf = view('finance.cf.pdf', compact('cash', 'setting', 'data', 'mnth', 'treasury'));

                $mpdf = new Mpdf();
                $mpdf->WriteHTML($pdf);
                $file_name = 'media/reports/cashflow_'.date("Ymd", strtotime($from)).'_'.date("Ymd", strtotime($to)).'_'.date("Y_m_d_h_i").".pdf";
                $mpdf->Output($file_name, \Mpdf\Output\Destination::FILE);
                $bl_file = new Finance_bl_files();
                $bl_file->date_from = $mnth;
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

        // dd($data);

        return view('finance.cf.find', compact('coa', 'data', 'ynow', 'setting', 'coa_code', 'from', 'to', 'projects', 'prj_selected', 'cash', 'treasury', 'source'));
    }

    function lock_cf(Request $request){
        $st = Finance_cf_setting::where('company_id', Session::get("company_id"))->get();
        $coa_code = Finance_coa::all()->pluck('code', 'id');
        $period = $request->year."-".sprintf("%02d", $request->month);
        $pinbuk = $st->where("label", "pinbuk");
        foreach($pinbuk as $item){
            if(!empty($item->tc)){
                $js = json_decode($item->tc, true);
                foreach($js as $i){
                    foreach($i as $c){
                        if(isset($coa_code[$c])){
                            $his = Finance_coa_history::where('company_id', Session::get('company_id'))
                                ->where('no_coa', $coa_code[$c])
                                ->where('coa_date', 'like', "$period%")
                                ->get();
                            foreach ($his as $key => $value) {
                                if($request->lock == 0){
                                    $value->id_cf = $item->id;
                                    $value->locked = 1;
                                } else {
                                    if($value->id_cf == $item->id){
                                        $value->id_cf = null;
                                        $value->locked = 0;
                                    }
                                }

                                $value->save();
                            }
                        }
                    }
                }
            }
        }
        foreach($st->whereNotNull('parent') as $item){
            $tc_id = (!empty($item->tc)) ? json_decode($item->tc, true) : [];
            $prj = (!empty($item->type)) ? json_decode($item->type, true) : [];


            if(!empty($prj)){
                for ($i=0; $i < count($prj); $i++) {
                    $tc_prj = $tc_id[$i];
                    $his = Finance_coa_history::where('company_id', Session::get('company_id'))
                        ->where('project', $prj[$i])
                        ->where(function($query) use($tc_prj){
                            $coa_code = Finance_coa::all()->pluck('code', 'id');
                            foreach ($tc_prj as $tc) {
                                $nonproject = ['11200000000', '21200000000', '31100000000', '41100000000'];
                                if(isset($coa_code[$tc])){
                                    $query->orWhere('no_coa', $coa_code[$tc]);
                                    if(in_array($coa_code[$tc], $nonproject)){
                                        $query->orWhere('no_coa', $coa_code[$tc]);
                                    } else {
                                        $coa_trim = rtrim($coa_code[$tc], 0);
                                        if(substr($coa_trim, -2, 1) != 0){
                                            $coa_trim .= "0";
                                        }
                                        $query->orWhere('no_coa', 'like', "$coa_trim%");
                                        $query->WhereNotIn('no_coa', $nonproject);
                                    }
                                }
                            }
                        })
                        ->where('coa_date', 'like', "$period%");
                    foreach ($his->get() as $key => $value) {
                        $value->id_cf = $item->id;
                            $value->locked = 1;
                        if($request->lock == 0){
                            $value->id_cf = $item->id;
                            $value->locked = 1;
                        } else {
                            if($value->id_cf == $item->id){
                                $value->id_cf = null;
                                $value->locked = 0;
                            }
                        }

                        $value->save();
                    }
                }
            } else {
                for ($i=0; $i < count($tc_id); $i++) {
                    $tc_prj = $tc_id[$i];
                    $his = Finance_coa_history::where('company_id', Session::get('company_id'))
                        ->where(function($query) use($tc_prj){
                            $coa_code = Finance_coa::all()->pluck('code', 'id');
                            foreach ($tc_prj as $tc) {
                                if(isset($coa_code[$tc])){
                                    $nonproject = ['11200000000', '21200000000', '31100000000', '41100000000'];
                                    if(isset($coa_code[$tc])){
                                        // $query->orWhere('no_coa', $coa_code[$tc]);
                                        if(in_array($coa_code[$tc], $nonproject)){
                                            $query->orWhere('no_coa', $coa_code[$tc]);
                                        } else {
                                            $coa_trim = rtrim($coa_code[$tc], 0);
                                            if(substr($coa_trim, -2, 1) != 0){
                                                $coa_trim .= "0";
                                            }
                                            $query->orWhere('no_coa', 'like', "$coa_trim%");
                                            $query->WhereNotIn('no_coa', $nonproject);
                                        }
                                    }
                                }
                            }
                        })
                        ->where('coa_date', 'like', "$period%");
                    foreach ($his->get() as $key => $value) {
                        if($request->lock == 0){
                            $value->id_cf = $item->id;
                            $value->locked = 1;
                        } else {
                            $value->id_cf = null;
                            $value->locked = 0;
                        }

                        $value->save();
                    }
                }
            }
        }

        $lock_st = Finance_cf_lock::where('month', $request->month)
            ->where('year', $request->year)
            ->where('company_id', Session::get('company_id'))
            ->first();
        if(empty($lock_st)){
            $lock_st = new Finance_cf_lock();
            $lock_st->year = $request->year;
            $lock_st->month = $request->month;
            $lock_st->company_id = Session::get('company_id');
            $lock_st->created_by = Auth::user()->username;
        }

        $lock_st->lock_status = ($request->lock == 0) ? 1 : 0;
        if($lock_st->save()){
            $response = [
                "success" => true,
                "lock" => $lock_st->lock_status
            ];
        } else {
            $response = [
                "success" => false,
                "lock" => null
            ];
        }

        return json_encode($response);

    }

    function view($id, Request $request){
        $period = $request->period;

        $st = Finance_cf_setting::find($id);

        $expPeriod = explode("-", $period);

        $year = $expPeriod[0];
        $month = $expPeriod[1];

        $projects = (!empty($st->type)) ? json_decode($st->type, true) : [];

        $coa_code = Finance_coa::all()->pluck('code', 'id');

        $tcs = (!empty($st->tc)) ? json_decode(($st->tc)) : [];

        $data = [];

        if(!empty($projects)){
            foreach ($projects as $i => $prj) {
                if (isset($tcs[$i])) {
                    $tc_prj = $tcs[$i];
                    $hist = Finance_coa_history::where("company_id", Session::get("company_id"))
                        ->where(function($query) use($tc_prj){
                            $coa_code = Finance_coa::all()->pluck('code', 'id');
                            foreach ($tc_prj as $key => $tc) {
                                $nonproject = ['11200000000', '21200000000', '31100000000', '41100000000'];
                                if(isset($coa_code[$tc])){
                                    // $query->orWhere('no_coa', $coa_code[$tc]);
                                    if(in_array($coa_code[$tc], $nonproject)){
                                        $query->orWhere('no_coa', $coa_code[$tc]);
                                    } else {
                                        $coa_trim = rtrim($coa_code[$tc], 0);
                                        if(substr($coa_trim, -2, 1) != 0){
                                            $coa_trim .= "0";
                                        }
                                        $query->orWhere('no_coa', 'like', "$coa_trim%");
                                        $query->WhereNotIn('no_coa', $nonproject);
                                    }
                                }
                            }
                        })
                        // ->whereRaw('(description like "%['.$prj.']%" or project = '.$prj.')')
                        ->whereRaw('(project = '.$prj.')')
                        // ->whereRaw("(description like '%".[".$prj[$i]."]".%")")
                        ->where('coa_date', 'like', $year."-".sprintf("%02d", $month)."%")
                        ->get();
                    foreach($hist as $val){
                        if(!empty($val->currency)){
                            $curr = $val->currency;
                        } else {
                            if(isset($hist[$val->id_treasure_history])){
                                $curr = $hist[$val->id_treasure_history];
                            } else {
                                $curr = "IDR";
                            }
                        }

                        $amountIDR = 0;
                        $amountUSD = 0;

                        if($curr == "IDR"){
                            if($st->parent_type == "cash_in"){
                                $amountIDR = (empty($val->debit)) ? abs($val->credit) : abs($val->debit) * -1;
                            } else {
                                $amountIDR = (empty($val->debit)) ? abs($val->credit) * -1 : abs($val->debit);
                            }
                        } else {
                            if($st->parent_type == "cash_in"){
                                $amountUSD = (empty($val->debit)) ? abs($val->credit) : abs($val->debit) * -1;
                            } else {
                                $amountUSD = (empty($val->debit)) ? abs($val->credit) * -1 : abs($val->debit);
                            }
                        }

                        $row['id'] = $val->id;
                        $row['date'] = $val->coa_date;
                        $row['project'] = $val->project;
                        $row['no_coa'] = $val->no_coa;
                        $row['description'] = $val->description;
                        $row['IDR'] = $amountIDR;
                        $row['USD'] = $amountUSD;
                        if($val->locked == 1){
                            if($val->id_cf == $st->id){
                                $data[$val->id] = $row;
                            }
                        } else {
                            $data[$val->id] = $row;
                        }
                    }
                }
            }
        } else {
            for ($i=0; $i < count($tcs); $i++) {
                $tc_prj = $tcs[$i];
                $hist = Finance_coa_history::where("company_id", Session::get("company_id"))
                    ->where(function($query) use($tc_prj){
                        $coa_code = Finance_coa::all()->pluck('code', 'id');
                        foreach ($tc_prj as $key => $tc) {
                            $nonproject = ['11200000000', '21200000000', '31100000000', '41100000000'];
                            if(isset($coa_code[$tc])){
                                // $query->orWhere('no_coa', $coa_code[$tc]);
                                if(in_array($coa_code[$tc], $nonproject)){
                                    $query->orWhere('no_coa', $coa_code[$tc]);
                                } else {
                                    $coa_trim = rtrim($coa_code[$tc], 0);
                                    if(substr($coa_trim, -2, 1) != 0){
                                        $coa_trim .= "0";
                                    }
                                    $query->orWhere('no_coa', 'like', "$coa_trim%");
                                    $query->WhereNotIn('no_coa', $nonproject);
                                }
                            }
                        }
                    })
                    // ->whereRaw('(description like "%['.$prj.']%" or project = '.$prj.')')
                    // ->whereRaw("(description like '%".[".$prj[$i]."]".%")")
                    ->where('coa_date', 'like', $year."-".sprintf("%02d", $month)."%")
                    ->get();
                foreach($hist as $val){
                    if(!empty($val->currency)){
                        $curr = $val->currency;
                    } else {
                        if(isset($hist[$val->id_treasure_history])){
                            $curr = $hist[$val->id_treasure_history];
                        } else {
                            $curr = "IDR";
                        }
                    }

                    $amountIDR = 0;
                    $amountUSD = 0;

                    if($curr == "IDR"){
                        if($st->parent_type == "cash_in"){
                            $amountIDR = (empty($val->debit)) ? abs($val->credit) : abs($val->debit) * -1;
                        } else {
                            $amountIDR = (empty($val->debit)) ? abs($val->credit) * -1 : abs($val->debit);
                        }
                    } else {
                        if($st->parent_type == "cash_in"){
                            $amountUSD = (empty($val->debit)) ? abs($val->credit) : abs($val->debit) * -1;
                        } else {
                            $amountUSD = (empty($val->debit)) ? abs($val->credit) * -1 : abs($val->debit);
                        }
                    }

                    $row['id'] = $val->id;
                    $row['date'] = $val->coa_date;
                    $row['project'] = $val->project;
                    $row['no_coa'] = $val->no_coa;
                    $row['description'] = $val->description;
                    $row['IDR'] = $amountIDR;
                    $row['USD'] = $amountUSD;
                    if($val->locked == 1){
                        if($val->id_cf == $st->id){
                            $data[$val->id] = $row;
                        }
                    } else {
                        $data[$val->id] = $row;
                    }
                }
            }
        }


        return view('finance.cf.view', compact('data', 'month', 'year', 'st'));
    }

    function edit($id){
        $st = Finance_cf_setting::find($id);

        $projects = Marketing_project::where('company_id', Session::get('company_id'))->get();

        $num = 1;
        $tcs = (!empty($st->tc)) ? json_decode($st->tc, true) : [];
        $prj = [];
        if(!empty($st->type)){
            $prj = json_decode($st->type, true);
            $num = count($prj);
        } else {
            $num = count($tcs);
        }

        $coa = Finance_coa::all();

        return view('finance.cf._edit', compact('st', 'projects', 'coa', 'num', 'prj', 'tcs'));
    }

    function find_source(Request $request){
        if($request->ajax()){
            $src = $request->source;
            $q = $request->q;
            $result = [];
            if($src == "invoice_out"){
                $inv_out = Finance_invoice_out::where('company_id', Session::get("company_id"))
                    ->get()
                    ->pluck('id_project');
                $project = Marketing_project::whereIn("id", $inv_out)
                    ->where('company_id', Session::get('company_id'))
                    ->where('prj_name', 'like', "%$q%")
                    ->get();

                foreach($project as $item){
                    $row = [];
                    $row['id'] = $item->id;
                    $row['text'] = $item->prj_name;
                    $result[] = $row;
                }
            } elseif($src == "po"){
                $po = Asset_po::where('company_id', Session::get("company_id"))
                    ->where('po_date', 'like', date("Y")."%")
                    ->get();

                foreach ($po as $key => $value) {
                    $row = [];
                    $row['id'] = $value->id;
                    $row['text'] = $value->po_num;
                    $result[] = $row;
                }
            } elseif($src == "wo"){
                $wo = Asset_wo::where('company_id', Session::get("company_id"))
                    ->where('req_date', 'like', date("Y")."%")
                    ->get();

                foreach ($wo as $key => $value) {
                    $row = [];
                    $row['id'] = $value->id;
                    $row['text'] = $value->wo_num;
                    $result[] = $row;
                }
            } elseif($src == "loan"){
                $loan = Finance_loan::where('company_id', Session::get("company_id"))->get();

                foreach ($loan as $key => $value) {
                    $row = [];
                    $row['id'] = $value->id;
                    $row['text'] = $value->bank." - ".$value->description;
                    $result[] = $row;
                }
            } elseif($src == "leasing"){
                $leasing = Finance_leasing::where('company_id', Session::get("company_id"))->get();

                foreach ($leasing as $key => $value) {
                    $row = [];
                    $row['id'] = $value->id;
                    $row['text'] = $value->subject." - ".$value->vendor;
                    $result[] = $row;
                }
            }

            $response = [
                "results" => $result
            ];

            return json_encode($response);
        }
    }

    function list(){
        $list = Finance_bl_files::where('company_id', Session::get('company_id'))
            ->where('type', 'c')
            ->orderBy('id', 'desc')
            ->get();

        return view('finance.cf.list', compact('list'));
    }

    function settings(Request $request){

        if(isset($request->label)){
            if (isset($request->id_st)) {
                $setting = Finance_cf_setting::find($request->id_st);
                $strMode = "E";
            } else {
                $setting = new Finance_cf_setting();
                $strMode = "A";
            }

            if(isset($request->is_child) & $request->is_child == 1){
                if($strMode == "A"){
                    $parent = Finance_cf_setting::find($request->type_parent);
                    $setting->parent = $parent->id;
                    $setting->parent_type = $parent->parent_type;
                    $lastNum = Finance_cf_setting::where('parent_type', $parent->parent_type)
                        ->where('parent', $parent->id)
                        ->where('company_id', Session::get("company_id"))
                        ->orderBy('order_num', 'desc')
                        ->first();

                    if(!empty($lastNum)){
                        $num = $lastNum->order_num + 1;
                    } else {
                        $num = 1;
                    }
                    $setting->order_num = $num;
                }
                if($request->prj[0] != null){
                    $setting->type = json_encode($request->prj);
                    // $setting->paper_id = $request->paper_id;
                } else {
                    $setting->type = null;
                }
                $setting->tc = json_encode($request->tc);
            } else {
                if($strMode == "A"){
                    $lastNum = Finance_cf_setting::where('parent_type', $request->type_parent)
                        ->whereNull('parent')
                        ->where('company_id', Session::get("company_id"))
                        ->orderBy('order_num', 'desc')
                        ->first();

                    if(!empty($lastNum)){
                        $num = $lastNum->order_num + 1;
                    } else {
                        $num = 1;
                    }
                    $setting->order_num = $num;
                    $setting->parent_type = $request->type_parent;
                }
            }

            if(isset($request->tc)){
                $setting->tc = json_encode($request->tc);
            }

            $setting->label = $request->label;

            if($strMode == "A"){
                $setting->created_by = Auth::user()->username;
                $setting->company_id = Session::get("company_id");
            } else {
                $setting->updated_by = Auth::user()->username;
            }
            $setting->save();

            return redirect()->back();
        }

        $coa = Finance_coa::orderBy('code', 'asc')->get();
        $coa_code = $coa->pluck('code', 'id');
        $coa_desc = $coa->pluck('name', 'code');
        $data_setting = Finance_cf_setting::where('company_id', Session::get('company_id'))->get();
        $setting = [];
        $from = null;
        $to = null;

        $coa_his = Finance_coa_history::get();

        $projects = Marketing_project::where('company_id', Session::get('company_id'))
            ->get();
        $prj_selected = [];

        $data = [];

        $cash = ["cash_in", "cash_out"];

        $treasury = Finance_treasury::where("company_id", Session::get("company_id"))
            ->where('type', 'bank')
            ->orderBy('currency')
            ->get();

        $source = ["po", "wo", "invoice_out", "loan", "leasing",];

        $st = Finance_cf_setting::where('company_id', Session::get("company_id"))
            ->whereNull('parent')
            ->orderBy('order_num')
            ->orderBy('id')
            ->get();

        foreach($st as $item){
            $item->child = Finance_cf_setting::where('parent', $item->id)
                ->orderBy('order_num')
                ->orderBy('id')
                ->get();
            $setting[$item->parent_type][] = $item;
        }

        $pinbuk = Finance_cf_setting::where("label", 'pinbuk')
            ->where('company_id', Session::get("company_id"))
            ->first();

        return view('finance.cf.setting', compact('coa', 'pinbuk', 'data', 'setting', 'coa_code', 'from', 'to', 'projects', 'prj_selected', 'cash', 'treasury', 'source'));
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
                            ->where('company_id', Session::get('company_id'))
                            ->get();
                        if (!empty($hisCode)) {
                            if ($value->name == "saldo_awal") {
                                foreach ($hisCode as $key => $his) {
                                    $row = [];
                                    $multiplier = (isset($arrRates[$his->currency]) && $his->currency != "IDR") ? floatval(str_replace(",", "", $arrRates[$his->currency])) : 1;
                                    $row['description'] = $his->description;
                                    $amount = (!empty($his->debit)) ? $his->debit : $his->credit;
                                    $row['amount'] = abs($amount) * $multiplier;
                                    $data[$value->name][] = $row;
                                }
                            } else {
                                $group = [];
                                foreach ($hisCode as $key => $his) {
                                    $row = [];
                                    $multiplier = (isset($arrRates[$his->currency]) && $his->currency != "IDR") ? floatval(str_replace(",", "", $arrRates[$his->currency])) : 1;
                                    $amount = (!empty($his->debit)) ? $his->debit : $his->credit;
                                    $group[$his->no_coa][] = abs($amount) * $multiplier;
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

    function delete($id){
        $st = Finance_cf_setting::find($id);
        $child = Finance_cf_setting::where('parent', $id)->get();

        if(count($child) == 0){
            $st->delete();
            $return = 1;
        } else {
            $return = 0;
        }

        return json_encode($return);
    }

    function detail(Request $request){
        $t = explode("-", $request->t);

        $_t = $request->t;

        $coa_his = Finance_coa_history::where('company_id', Session::get("company_id"))
            ->where('coa_date', 'like', $t[0]."-".sprintf("%02d", $t[1])."%");

        $coa_code = Finance_coa::all()->pluck('code', 'id');

        $chis = [];

        $treasure = Finance_treasury::where("company_id", Session::get('company_id'))
            ->get()
            ->pluck('source', 'id');

        $his = Finance_treasury_history::where('company_id', Session::get('company_id'))
            ->get()
            ->pluck('id_treasure', 'id');

        foreach ($coa_his->get() as $key => $value) {
            $bank = (isset($his[$value->id_treasure_history])) ? $his[$value->id_treasure_history] : null;

            $value->bank = $bank;

            $chis[$value->no_coa][] = $value;
        }

        $cf_child = Finance_cf_setting::whereNotNull('parent')
            ->where("company_id", Session::get("company_id"))
            ->get();

        $childs = [];

        $cf = Finance_cf_setting::whereNull('parent')
            ->where("company_id", Session::get("company_id"))
            ->whereNotNull('parent_type')
            ->get();

        $cf_parent = $cf->pluck('label', 'id');

        foreach ($cf_child as $key => $value) {
            $row = [];

            $tcs = json_decode($value->tc, true);

            $project = (!empty($value->type)) ? json_decode($value->type, true) : [];

            if(!empty($project)){
                foreach ($project as $key => $prj) {
                    $tc_prj = $tcs[$key];
                    $row[] = Finance_coa_history::where("company_id", Session::get("company_id"))
                        ->where(function($query) use($tc_prj){
                            $coa_code = Finance_coa::all()->pluck('code', 'id');
                            foreach ($tc_prj as $key => $tc) {
                                if(isset($coa_code[$tc])){
                                    $query->orWhere('no_coa', $coa_code[$tc]);
                                }
                            }
                        })
                        // ->whereRaw('(description like "%['.$prj.']%" or project = '.$prj.')')
                        ->whereRaw('(project = '.$prj.')')
                        // ->whereRaw("(description like '%".[".$prj[$i]."]".%")")
                        ->where('coa_date', 'like', $t[0]."-".sprintf("%02d", $t[1])."%")
                        ->get();
                }
            } else {
                for ($i=0; $i < count($tcs); $i++) {
                    $tc_prj = $tcs[$i];
                    $row[] = Finance_coa_history::where("company_id", Session::get("company_id"))
                        ->where(function($query) use($tc_prj){
                            $coa_code = Finance_coa::all()->pluck('code', 'id');
                            foreach ($tc_prj as $key => $tc) {
                                if(isset($coa_code[$tc])){
                                    $query->orWhere('no_coa', $coa_code[$tc]);
                                }
                            }
                        })
                        // ->whereRaw('(description like "%['.$prj.']%" or project = '.$prj.')')
                        // ->whereRaw("(description like '%".[".$prj[$i]."]".%")")
                        ->where('coa_date', 'like', $t[0]."-".sprintf("%02d", $t[1])."%")
                        ->get();
                }
            }
            $childs[$value->parent_type][$cf_parent[$value->parent]][$value->label] = $row;
        }

        return view("finance.cf.detail", compact('childs', 'treasure', 'his', '_t'));
    }
}
