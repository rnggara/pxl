<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Asset_po;
use App\Models\Asset_wo;
use App\Models\Finance_coa;
use App\Models\Finance_loan;
use App\Models\Hrd_employee;
use App\Models\Marketing_bp;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\Finance_leasing;
use App\Models\Pref_tax_config;
use App\Models\General_cashbond;
use App\Models\Hrd_employee_loan;
use App\Models\Marketing_project;
use App\Models\Finance_coa_source;
use App\Models\Finance_invoice_in;
use App\Models\Finance_ts_setting;
use App\Models\Finance_coa_history;
use App\Models\Finance_invoice_out;
use App\Models\Finance_loan_detail;
use App\Models\Marketing_bp_detail;
use Illuminate\Support\Facades\Auth;
use App\Models\Finance_invoice_in_pay;
use App\Models\Finance_leasing_detail;
use App\Models\General_cashbond_detail;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Hrd_employee_loan_payment;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use App\Models\Finance_invoice_out_detail;

class FinanceTransactionSummary extends Controller
{
    function index(){
        $src = Finance_coa_source::where('insert', 1)->get();
        $src_desc = $src->pluck('description', 'id');
        $src_name = $src->pluck('name', 'id');
        $coa = Finance_coa::where(function($query) use($src){
            foreach($src as $value){
                $query->orWhere('source', 'like', '%"'.$value->id.'"%');
            }
        })->get();
        $coa_id = $coa->pluck('id');

        $history = Finance_coa_history::where(function($query) use($coa){
            foreach ($coa as $value) {
                $query->orWhere('no_coa', $value->code);
            }
        })
        ->where('company_id', Session::get('company_id'))
        ->orderBy('coa_date')
        ->get();

        $coa_his = [];
        foreach($history as $item){
            $amount = (empty($item->debit)) ? $item->credit : $item->debit;
            $coa_his[$item->no_coa]['date'] = $item->updated_at;
            $coa_his[$item->no_coa]['amount'][] = $amount;
        }

        $project_all = Marketing_project::where('company_id', Session::get('company_id'))->get();

        $project_cost = $project_all->where('category', 'cost');
        $project_sales = $project_all->where('category', 'sales');

        $ts_setting = Finance_ts_setting::where('company_id', Session::get('company_id'))->get();
        $ts = [];
        foreach ($ts_setting as $key => $value) {
            $ts[$value->id_src] = $value;
        }

        return view('finance.ts.index', compact('src', 'src_desc', 'src_name', 'coa_his', 'coa', 'project_cost', 'project_sales', 'ts'));
    }

    function update_data(Request $request){
        $project = [];

        if(isset($request->sales)){
            foreach ($request->sales as $key => $value) {
                $project[] = $value;
            }
        }

        if(isset($request->cost)){
            foreach ($request->cost as $key => $value) {
                $project[] = $value;
            }
        }

        $whereBetween = [$request->start_date, $request->end_date];

        // dd($request, $project);
        $src = Finance_coa_source::where('insert', 1)->get();
        $type = "All";

        if(!empty($request->name)){
            $src = Finance_coa_source::where('insert', 1)
                ->where('name', $request->name)
                ->get();
            $src_name = Finance_coa_source::where('name', $request->name)->first();
            $type = ucwords($src_name->description);
        }

        $comp_tag = ConfigCompany::all()->pluck('tag', 'id');

        foreach($src as $item){
            $sumAmount = 0;
            $desc = $item->description;
            $entry = [];
            if($item->name == "ac"){
                // get data cashbond
                $cb = General_cashbond::where('user', 'open')
                    ->whereNull('dir_appr_date')
                    ->whereIn('project', $project)
                    ->where('company_id', Session::get('company_id'))
                    ->get();
                $cb_id = $cb->pluck('id');
                $cb_detail = General_cashbond_detail::whereIn('id_cashbond', $cb_id)
                    ->where('cashin', '>', '0')
                    ->whereBetween('tanggal', $whereBetween)
                    ->get();
                $amount = (!empty($cb_detail)) ? $cb_detail->sum('cashin') : 0;
                $sumAmount += $amount;
                foreach($cb as $item_det){
                    $tag = $comp_tag[$item_det->company_id];
                    $m = date("m", strtotime($item_det->input_date));
                    $y = date("y", strtotime($item_det->input_date));
                    $row = [];
                    $row['entry'] = sprintf("%03d", $item_det->id)."/$tag/$m/$y";
                    $row['date'] = $item_det->input_date;
                    $row['amount'] = $cb_detail->where('id_cashbond', $item_det->id)->sum('cashin');
                    $entry[] = $row;
                }
            } elseif($item->name == 'pio'){
                // get invoice out
                $inv_out = Finance_invoice_out::where('company_id', Session::get('company_id'))
                    ->whereIn('id_project', $project)
                    ->get();
                $id_inv = $inv_out->pluck('id_inv');
                $inv_detail = Finance_invoice_out_detail::whereIn('id_inv', $id_inv)
                    ->whereNull('ceo_app_date')
                    ->whereBetween('date', $whereBetween)
                    ->get();
                $amount = (!empty($inv_detail)) ? $inv_detail->sum('value_d') : 0;
                $sumAmount = $amount;
                foreach($inv_out as $item_det){
                    $row = [];
                    $row['entry'] = (empty($item_det->no)) ? $item_det->tag : $item_det->no;
                    $row['date'] = $item_det->created_at;
                    $row['amount'] = $inv_detail->where('id_inv', $item_det->id_inv)->whereNull('ceo_app_date')->sum('value_d');
                    $entry[] = $row;
                }
            } elseif($item->name == "ple"){
                // get emp
                $emp = Hrd_employee::whereNull('expel')
                    ->where('company_id', Session::get('company_id'))
                    ->get();
                $emp_id = $emp->pluck('id');
                $emp_name = $emp->pluck('emp_name', 'id');

                $year = date("Y");
                $m = date("m");
                if(date("d") >= 28){
                    $bln = intval($m);
                    $bln++;
                    $m = sprintf("%02d", $bln);
                    if($bln > 12){
                        $year++;
                        $m = sprintf("%02d", 1);
                    }
                }

                $limit_date = "$year-$m";

                $emp_loan = Hrd_employee_loan::whereIn('emp_id', $emp_id)->get();
                $emp_loan_id = $emp_loan->pluck('id');
                $loan_detail = Hrd_employee_loan_payment::whereIn('loan_id', $emp_loan_id)
                    ->where('date_of_payment', 'like', "$limit_date%")
                    ->get();
                $amount = (!empty($loan_detail)) ? $loan_detail->sum('amount') : 0;
                $sumAmount += $amount;
                foreach($emp_loan as $item_det){
                    $row['entry'] = $emp_name[$item_det->emp_id]." - ".$item_det->loan_id;
                    $row['date'] = $item_det->given_time;
                    $row['amount'] = $loan_detail->where('loan_id', $item_det->id)->sum('amount');
                    $entry[] = $row;
                }
            } elseif($item->name == "abg"){
                // get BP
                $bp = Marketing_bp::where('company_id', Session::get('company_id'))
                    ->whereIn('prj_code', $project)
                    ->whereBetween('input_date', $whereBetween)
                    ->get();
                $bp_id = $bp->pluck('id');
                $bp_detail = Marketing_bp_detail::whereIn('id_main', $bp_id)
                    ->get();
                $amount = 0;
                $am = [];
                foreach($bp_detail as $value){
                    $ac_amount = ($value->actual_amount > 0) ? $value->actual_amount : $value->request_amount;
                    $amount += $ac_amount;
                    $am[$value->id_main][] = $ac_amount;
                }
                $sumAmount += $amount;
                foreach($bp as $item_det){
                    $row['entry'] = $item_det->prj_name;
                    $row['date'] = $item_det->input_date;
                    $row['amount'] = (isset($am[$item_det->id])) ? array_sum($am[$item_det->id]) : 0;
                    $entry[] = $row;
                }
            } elseif($item->name == "hii"){
                // get INV IN
                $inv_in = Finance_invoice_in::where('company_id', Session::get('company_id'))
                    ->whereIn('project', $project)
                    ->get();
                $inv_in_id = $inv_in->pluck('id');
                $inv_in_pay = Finance_invoice_in_pay::whereIn('inv_id', $inv_in_id)
                    ->whereBetween('pay_date', $whereBetween)
                    ->where('paid', 0)
                    ->get();
                $amount = (!empty($inv_in_pay)) ? $inv_in_pay->sum('amount') : 0;
                $sumAmount += $amount;
                foreach($inv_in as $item_det){
                    if($item_det->paper_type == "PO"){
                        $i = Asset_po::find($item_det->paper_id);
                        $entry_text = $i->po_num;
                        $date = $i->po_date;
                    } else {
                        $i = Asset_wo::find($item_det->paper_id);
                        $entry_text = $i->wo_num;
                        $date = $i->req_date;
                    }
                    $row = [];
                    $row['entry'] = $entry_text;
                    $row['date'] = $date;
                    $row['amount'] = $inv_in_pay->where('inv_id', $item_det->id)->sum('amount');
                    $entry[] = $row;
                }
            } elseif($item->name == "hp"){
                // get invoice out
                $inv_out = Finance_invoice_out::where('company_id', Session::get('company_id'))
                    ->whereIn('id_project', $project)
                    ->get();
                $id_inv = $inv_out->pluck('id_inv');
                $inv_detail = Finance_invoice_out_detail::whereIn('id_inv', $id_inv)
                    ->whereNull('ceo_app_date')
                    ->whereBetween('date', $whereBetween)
                    ->whereNotNull('taxes')
                    ->get();

                $ppn = Pref_tax_config::where('is_wapu', 1)->get()->pluck('formula', 'id');

                $amount = 0;
                $am_tax = [];
                foreach($inv_detail as $value){
                    $tax = json_decode($value->taxes, true);
                    if(is_array($tax)){
                        for ($i=0; $i < count($tax); $i++) {
                            if (isset($ppn[$tax[$i]])) {
                                $sum = $value->value_d;
                                $id_tax = $tax[$i];
                                $eval = eval("return $ppn[$id_tax];");
                                $amount += $eval;
                                $am_tax[$value->id_inv][] = $eval;
                            }
                        }
                    }
                }

                foreach($inv_out as $item_det){
                    $row['entry'] = (empty($item_det->no)) ? $item_det->tag : $item_det->no;
                    $row['date'] = $item_det->created_at;
                    $row['amount'] = (isset($am_tax[$item_det->id_inv])) ? array_sum($am_tax[$item_det->id_inv]) : 0;
                    $entry[] = $row;
                }

                // pph 21
                $emp = 0;
                if(date("d") > 0){
                    $emp_data = Hrd_employee::whereNull('expel')
                        ->where('company_id', Session::get('company_id'))
                        ->get();
                    $emp = $emp_data->sum('deduc_pph21');
                    foreach($emp_data as $item_det){
                        $row['entry'] = $item_det->emp_name;
                        $row['date'] = "-";
                        $row['amount'] = (!empty($item_det->deduc_pph21)) ? $item_det->deduc_pph21 : 0;
                        $entry[] = $row;
                    }
                }
                $amount += $emp;
                // $amount = (!empty($inv_detail)) ? $inv_detail : 0;
                $sumAmount += $amount;
            } elseif($item->name == "hb"){
                // get loan
                $loan = Finance_loan::where('company_id', Session::get('company_id'))->get();
                $loan_id = $loan->pluck('id');
                $loan_detail = Finance_loan_detail::whereIn('id_loan', $loan_id)
                    ->whereBetween('plan_date', $whereBetween)
                    ->where('status', 'Planned')
                    ->get();
                $amount = 0;
                $am = [];
                foreach ($loan_detail as $key => $value) {
                    $amount += $value->cicilan + $value->bunga;
                    $am[$value->id_loan][] = $value->cicilan + $value->bunga;
                }
                $sumAmount += $amount;

                foreach($loan as $item_det){
                    $row['entry'] = $item_det->bank;
                    $row['date'] = $item->start;
                    $row['amount'] = (isset($am[$item_det->id])) ? array_sum($am[$item_det->id]) : 0;
                    $entry[] = $row;
                }
            } elseif($item->name == "hs"){
                // get leasing
                $leasing = Finance_leasing::where('company_id', Session::get('company_id'))->get();
                $leasing_id = $leasing->pluck('id');
                $leasing_detail = Finance_leasing_detail::whereIn('id_leasing', $leasing_id)
                    ->whereBetween('plan_date', $whereBetween)
                    ->where('status', 'Planned')
                    ->get();
                $amount = 0;
                $am = [];
                foreach ($leasing_detail as $key => $value) {
                    $amount += $value->cicilan + $value->bunga;
                    $am[$value->id_leasing][] = $value->cicilan + $value->bunga;
                }
                $sumAmount += $amount;
                foreach($leasing as $item_det){
                    $row['entry'] = $item_det->subject;
                    $row['date'] = $item->start;
                    $row['amount'] = (isset($am[$item_det->id])) ? array_sum($am[$item_det->id]) : 0;
                    $entry[] = $row;
                }
            }

            $ts_setting = Finance_ts_setting::where('id_src', $item->id)
                ->where('company_id', Session::get('company_id'))
                ->first();
            $date = date("YmdHi");
            $_file = "$tag-".$desc."_$date.xlsx";
            if(empty($ts_setting)){
                $ts_setting = new Finance_ts_setting();
                $ts_setting->created_by = Auth::user()->username;
                $ts_setting->company_id = Session::get('company_id');
                $ts_setting->id_src = $item->id;
            }

            $ts_setting->_file = $_file;

            $ts_setting->project_sales = json_encode($request->sales);
            $ts_setting->project_cost = json_encode($request->cost);
            $ts_setting->start_date = $request->start_date;
            $ts_setting->end_date = $request->end_date;
            $ts_setting->updated_by = Auth::user()->username;
            $ts_setting->save();

            $coa_his = Finance_coa_history::where('description', $item->description)
                ->where('company_id', Session::get('company_id'))
                ->first();

            $tc_code = Finance_coa::where('source', 'like', '%"'.$item->id.'"%')->first();

            if(!empty($coa_his)){
                $coa_his->debit = $sumAmount;
                $coa_his->coa_date = date("Y-m-d");
                $coa_his->save();
            } else {
                $c_his = new Finance_coa_history();
                $c_his->description = $item->description;
                $c_his->debit = $sumAmount;
                $c_his->coa_date = date("Y-m-d");
                $c_his->no_coa = $tc_code->code;
                $c_his->currency = "IDR";
                $c_his->created_by = Auth::user()->username;
                $c_his->company_id = Session::get('company_id');
                $c_his->save();
            }

            $comp_tag = Session::get('company_tag');
            $this->create_excel($desc, $entry, $comp_tag, $request->start_date, $request->end_date, $date);
        }

        return redirect()->back()->with('msg', $type);
    }

    function create_excel($filename, $entry, $tag, $from, $to, $date){
        $path = public_path("media/summary/$tag-".$filename."_$date.xlsx");
        $view = view('finance.ts.export', [
            "title" => $filename,
            "entry" => $entry,
            "from" => $from,
            "to" => $to
        ]);
        $arrayData = [
            ['Q1',   12,   15,   21],
            ['Q2',   56,   73,   86],
            ['Q3',   52,   61,   69],
            ['Q4',   30,   32,    0],
        ];

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $spreadsheet = $reader->loadFromString($view);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save($path);
    }
}
