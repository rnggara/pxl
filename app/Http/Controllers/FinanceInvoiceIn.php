<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Asset_po;
use App\Models\Asset_wo;
use App\Models\Asset_item;
use App\Models\Finance_coa;
use Illuminate\Http\Request;
use App\Models\Asset_po_detail;
use App\Models\Asset_wo_detail;
use App\Models\Pref_tax_config;
use App\Models\Marketing_project;
use App\Models\Finance_coa_source;
use App\Models\Finance_invoice_in;
use App\Models\Procurement_vendor;
use App\Models\Finance_coa_history;
use App\Models\Finance_invoice_in_pay;

class FinanceInvoiceIn extends Controller
{

    private $id_companies = array();
    function __construct()
    {
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $this->id_companies[] = $item->id;
            }
            array_push($this->id_companies, Session::get('company_id'));
        } else {
            array_push($this->id_companies, Session::get('company_id'));
        }

    }

    function index(){
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $this->id_companies[] = $item->id;
            }
            array_push($this->id_companies, Session::get('company_id'));
        } else {
            array_push($this->id_companies, Session::get('company_id'));
        }
        $inv_in = Finance_invoice_in::where('company_id', Session::get('company_id'))
            // ->where('amount_left', '>', 0)
            ->orderBy('id', 'desc')
            ->get();
        $vendor = Procurement_vendor::whereIn('company_id', $this->id_companies)->get();
        $data = array();
        $paper = array();
        $supplier = array();
        foreach ($vendor as $value){
            $data['id'][] = $value->id;
            $data['name'][$value->id] = $value->name;
            $data['address'][$value->id] = preg_replace(['/\n/', '/\r/'], ['', ' '], $value->address);
            $data['telephone'][$value->id] = $value->telephone;
            $data['bank_acct'][$value->id] = $value->bank_acct;
            $data['web'][$value->id] = $value->web;
            $data['pic'][$value->id] = $value->pic;
            $supplier[$value->id]['name'] = $value->name;
            $supplier[$value->id]['bank_acct'] = $value->bank_acct;
        }

        $po = Asset_po::where('company_id', Session::get('company_id'))->get();
        $wo = Asset_wo::where('company_id', Session::get('company_id'))->get();
        foreach ($po as $value){
            $paper['paper_num']['PO'][$value->id] = $value->po_num;
            $paper['supplier']['PO'][$value->id] = $value->supplier_id;
            $paper['currency']['PO'][$value->id] = $value->currency;
            $paper['gr_date']['PO'][$value->id] = $value->gr_date;
        }

        foreach ($wo as $value){
            $paper['paper_num']['WO'][$value->id]= $value->wo_num;
            $paper['supplier']['WO'][$value->id] = $value->supplier_id;
            $paper['currency']['WO'][$value->id] = $value->currency;
            $paper['gr_date']['WO'][$value->id] = $value->gr_date;
        }

        $project = Marketing_project::where('company_id', Session::get('company_id'))->get();
        $prj_name = array();
        foreach ($project as $value){
            $prj_name[$value->id] = $value->prj_name;
        }

        $inv_pay = Finance_invoice_in_pay::where('company_id', Session::get('company_id'))
            ->orderBy('id')
            ->get();
        $pay = array();
        foreach ($inv_pay as $item){
            $pay[$item->inv_id][] = $item;
        }

        // dd($pay[573]);

        return view('finance.inv_in.index', [
            'jsonvendor' => json_encode($data),
            'jsonprjname' => json_encode($prj_name),
            'inv_in' => $inv_in,
            'paper' => $paper,
            'supplier' => $supplier,
            'pay' => $pay
        ]);
    }

    function search_paper(Request $request){
        $paper = explode("/", $request->key);
        if (isset($paper[2])){
            if ($paper[2] == "WO" || $paper[2] == "PO"){
                $tax = Pref_tax_config::all();
                foreach ($tax as $value){
                    $tax_name[$value->id] = $value->tax_name;
                    $tax_formula[$value->id] = $value->formula;
                }
                if ($paper[2] == "WO"){
                    $data = Asset_wo::where('wo_num', $request->key)->first();
                    $detail = Asset_wo_detail::where('wo_id', $data->id)->get();
                    $subtotal = 0;
                    if (!empty($data->ppn)){
                        $ppn = json_decode($data->ppn);
                        if (is_array($ppn)) {
                            $cPPn = count($ppn);
                        } else {
                            $cPPn = 0;
                        }
                    } else {
                        $ppn = array();
                        $cPPn = 0;
                    }
                    $val['table'] = "<table class='table table-bordered display' style=\"width: 100%\"><thead><tr><th class='text-center'>No</th><th>Job Description</th><th class='text-center'>Qty</th><th class='text-right'>Unit Price</th><th class='text-right'>Amount</th></tr></thead>";
                    foreach ($detail as $key => $value){
                        $amount = $value->qty * $value->unit_price;
                        $subtotal += $amount;
                        $val['table'] .= "<tbody><tr><td align='center'>".($key + 1)."</td><td >".$value->job_desc."</td><td align='center'>".$value->qty."</td><td align='right'>".$value->unit_price."</td><td align='right'>".$amount."</td></tr></tbody>";
                    }
                    $val['table'] .= "<tfoot><tr>";
                    $val['table'] .= "<td rowspan='".(6 + $cPPn)."'></td><td rowspan='".(6 + $cPPn)."' colspan='2'><b>Requirements for payment, please attach:</b><ol><li>Original Work Order that has been signed and stamped by the company.</li><li>Bank account number for payment</li><li>Minutes of handover / Timesheets of work & tool usage</li><li>Original Tax Invoice</li></ol><br><b>A. Term Condition</b><ul><li>".strip_tags($data->terms)."</li></ul><br><b>B. Term of Payment</b><ul><li>".strip_tags($data->terms_payment)."</li></ul></td>";
                    $val['table'] .= "<td align='right'>SUB TOTAL</td>";
                    $val['table'] .= "<td align='right'>".number_format($subtotal, 2)."</td></tr>";
                    $val['table'] .= "<tr><td align='right'>DISCOUNT</td><td align='right'>".number_format($data->discount, 2)."</td></tr>";
                    $net = $subtotal - $data->discount;
                    $val['table'] .= "<tr><td align='right'>NET INCLUDE DISCOUNT</td><td align='right'>".number_format($net, 2)."</td></tr>";
                    //Tax
                    $ppn_sum = 0;
                    if($cPPn > 0){
                        foreach ($ppn as $p){
                            $sum = $net;
                            $pval = eval('return '.$tax_formula[$p].';');
                            $ppn_sum += $pval;
                            $val['table'] .= "<tr><td align='right'>".$tax_name[$p]."</td><td align='right'>".number_format($pval, 2)."</td></tr>";
                        }
                    }

                    $net_tax = $net + $ppn_sum;
                    $val['table'] .= "<tr><td align='right'>TOTAL AFTER TAX</td><td align='right'>".number_format($net_tax, 2)."</td></tr>";
                    $val['table'] .= "<tr><td align='right'>DOWN PAYMENT</td><td align='right'>".number_format($data->dp, 2)."</td></tr>";
                    $total_due = $net_tax - $data->dp;
                    $val['table'] .= "<tr><td align='right'>TOTAL DUE</td><td align='right'>".number_format($total_due, 2)."</td></tr>";
                    $val['table'] .= "</tfoot>";
                    $val['table'] .= "</table>";
                    $val['amount'] = $net_tax;
                    $val['type'] = "WO";
                } else {
                    $data = Asset_po::where('po_num', $request->key)->first();
                    $detail = Asset_po_detail::where('po_num', $data->id)->get();
                    $item = Asset_item::all();
                    foreach ($item as $valItem){
                        $item_name[$valItem->item_code] = "[".$valItem->item_code."] ".$valItem->name;
                        $item_uom[$valItem->item_code] = $valItem->uom;
                    }
                    $subtotal = 0;
                    if (!empty($data->ppn)){
                        $ppn = json_decode($data->ppn);
                    } else {
                        $ppn = array();
                    }
                    $iName = (isset($item_name[$value->item_id])) ? $item_name[$value->item_id] : "";
                    $iUom = (isset($item_uom[$value->item_id])) ? $item_uom[$value->item_id] : "";
                    $val['table'] = "<table class='table table-bordered display' style=\"width: 100%\"><thead><tr><th class='text-center'>No</th><th>Item</th><th class='text-center'>UoM</th><th class='text-center'>Qty</th><th class='text-right'>Unit Price</th><th class='text-right'>Amount</th></tr></thead>";
                    foreach ($detail as $key => $value){
                        $amount = $value->qty * $value->price;
                        $subtotal += $amount;
                        $val['table'] .= "<tbody><tr><td align='center'>".($key + 1)."</td><td >".$iName."</td><td align='center'>".$iUom."</td><td align='center'>".$value->qty."</td><td align='right'>".$value->price."</td><td align='right'>".$amount."</td></tr></tbody>";
                    }
                    $val['table'] .= "<tfoot><tr>";
                    $val['table'] .= "<td rowspan='".(6 + count($ppn))."'></td><td rowspan='".(6 + count($ppn))."' colspan='3'><b>Requirements for payment, please attach:</b><ol><li>Original Purchase Order that has been signed and stamped by the company.</li><li>Bank account number for payment</li><li>Minutes of handover / Timesheets of work & tool usage</li><li>Original Tax Invoice</li></ol><br><b>A. Term Condition</b><ul><li>".strip_tags($data->terms)."</li></ul><br><b>B. Term of Payment</b><ul><li>".strip_tags($data->payment_term)."</li></ul></td>";
                    $val['table'] .= "<td align='right'>SUB TOTAL</td>";
                    $val['table'] .= "<td align='right'>".number_format($subtotal, 2)."</td></tr>";
                    $val['table'] .= "<tr><td align='right'>DISCOUNT</td><td align='right'>".number_format($data->discount, 2)."</td></tr>";
                    $net = $subtotal - $data->discount;
                    $val['table'] .= "<tr><td align='right'>NET INCLUDE DISCOUNT</td><td align='right'>".number_format($net, 2)."</td></tr>";
                    //Tax
                    $ppn_sum = 0;
                    foreach ($ppn as $p){
                        $sum = $net;
                        $pval = eval('return '.$tax_formula[$p].';');
                        $ppn_sum += $pval;
                        $val['table'] .= "<tr><td align='right'>".$tax_name[$p]."</td><td align='right'>".number_format($pval, 2)."</td></tr>";
                    }
                    $net_tax = $net + $ppn_sum;
                    $val['table'] .= "<tr><td align='right'>TOTAL AFTER TAX</td><td align='right'>".number_format($net_tax, 2)."</td></tr>";
                    $val['table'] .= "<tr><td align='right'>DOWN PAYMENT</td><td align='right'>".number_format($data->dp, 2)."</td></tr>";
                    $total_due = $net_tax - $data->dp;
                    $val['table'] .= "<tr><td align='right'>TOTAL DUE</td><td align='right'>".number_format($total_due, 2)."</td></tr>";
                    $val['table'] .= "</tfoot>";
                    $val['table'] .= "</table>";
                    $val['amount'] = $net_tax;
                    $val['type'] = "PO";
                }

                if (!empty($data)){
                    $paper_id = $data->id;
                    $inv_in = Finance_invoice_in::where('paper_type', strtoupper($paper[2]))
                        ->where('paper_id', $paper_id)
                        ->first();
                    if (!empty($inv_in)){
                        $val['status'] = 2;
                        $val['messages'] = "Paper has already inserted in invoice in";
                        $val['id'] = $inv_in->id;
                    } else {
                        if ($paper[2] == "WO"){
                            if (!empty($data->ba_by)){
                                $val['status'] = 1;
                                $val['messages'] = "Paper is ready";
                                $val['data'] = json_encode($data);
                            } else {
                                $val['status'] = 5;
                                $val['messages'] = "Paper need BA";
                            }
                        } else {
                            if (!empty($data->approved_by)) {
                                $val['status'] = 1;
                                $val['messages'] = "Paper is ready";
                                $val['data'] = json_encode($data);
                            } else {
                                $val['status'] = 5;
                                $val['messages'] = "Paper need Approval";
                            }
                        }
                    }
                } else {
                    $val['status'] = 3;
                    $val['messages'] = "The paper number that you looking for is not exist";
                }
            } else {
                $val['status'] = 4;
                $val['messages'] = "You entered the wrong format, please try again";
            }
        } else {
            $val['status'] = 4;
            $val['messages'] = "You entered the wrong format, please try again";
        }


        return json_encode($val);
    }
    function add(Request $request){
        $iFin = new Finance_invoice_in();
        $iFin->paper_id = $request->id_p;
        $iFin->paper_type = $request->t;
        $iFin->amount = $request->amount;
        $iFin->amount_left = $request->amount;
        $iFin->app_date = date('Y-m-d');
        $iFin->status = "input";
        $iFin->project = $request->p;
        $iFin->company_id = Session::get('company_id');
        if ($request->cod != null){
            $iFin->pay_date = date('Y-m-d H:i:s');
        } else {
            $iFin->pay_date = null;
        }

        $iFin->save();

        if ($request->dp > 0){
            $iPay = new Finance_invoice_in_pay();
            $iPay->inv_id = $iFin->id;
            $iPay->pay_num = 1;
            $iPay->amount = $request->dp;
            $iPay->pay_date = date('Y-m-d');
            $iPay->due_date = $iPay->pay_date;
            $iPay->description = "Down Payment";
            $iPay->company_id = $iFin->company_id;
            $iPay->save();
        }

        if(isset($request->_tc) && !empty($request->_tc)){
            $tc = Finance_coa::find($request->_tc);
            if($request->t == "PO"){
                $data = Asset_po::find($request->id_p);
                $data->po_type = $tc->name;
            } else {
                $data = Asset_wo::find($request->id_p);
                $data->wo_type = $tc->name;
            }
            $data->tc_id = $tc->id;
            $data->save();
        }

        return redirect()->route('inv_in.index');
    }

    function duedate(Request $request){
        $iFin = Finance_invoice_in::find($request->id);
        $iFin->pay_date = $request->tgl;
        $iFin->save();

        return redirect()->route('inv_in.index');
    }

    function view($id){
        $paper = array();
        $vPay = Finance_invoice_in_pay::where('inv_id', $id)->get();
        $paid = 0;
        if (count($vPay) > 0){
            foreach ($vPay as $value){
                $paid += $value->amount;
            }
        }
        $po = Asset_po::where('company_id', Session::get('company_id'))->get();
        $wo = Asset_wo::where('company_id', Session::get('company_id'))->get();
        foreach ($po as $value){
            $paper['paper_num']['PO'][$value->id] = $value->po_num;
            $paper['supplier'][$value->id] = $value->supplier_id;
            $paper['currency'][$value->id] = $value->currency;
            $paper['gr_date'][$value->id] = $value->gr_date;
        }

        foreach ($wo as $value){
            $paper['paper_num']['WO'][$value->id]= $value->wo_num;
            $paper['supplier'][$value->id] = $value->supplier_id;
            $paper['currency'][$value->id] = $value->currency;
            $paper['gr_date'][$value->id] = $value->gr_date;
        }
        $inv = Finance_invoice_in::where('id', $id)->first();


        $tc = Finance_coa::orderBy('code')->get();

        $tc_code = $tc->pluck('code', 'id');
        $tc_name = $tc->pluck('name', 'id');

        return view('finance.inv_in.view', [
            'inv' => $inv,
            'ipay' => $vPay,
            'paper' => $paper,
            'paid' => $paid,
            'tc' => $tc,
            'tc_code' => $tc_code,
            'tc_name' => $tc_name,
        ]);$paper = array();
        $vPay = Finance_invoice_in_pay::where('inv_id', $id)->get();
        $paid = 0;
        if (count($vPay) > 0){
            foreach ($vPay as $value){
                $paid += $value->amount;
            }
        }
        $po = Asset_po::where('company_id', Session::get('company_id'))->get();
        $wo = Asset_wo::where('company_id', Session::get('company_id'))->get();
        foreach ($po as $value){
            $paper['paper_num']['PO'][$value->id] = $value->po_num;
            $paper['supplier'][$value->id] = $value->supplier_id;
            $paper['currency'][$value->id] = $value->currency;
            $paper['gr_date'][$value->id] = $value->gr_date;
        }

        foreach ($wo as $value){
            $paper['paper_num']['WO'][$value->id]= $value->wo_num;
            $paper['supplier'][$value->id] = $value->supplier_id;
            $paper['currency'][$value->id] = $value->currency;
            $paper['gr_date'][$value->id] = $value->gr_date;
        }
        $inv = Finance_invoice_in::where('id', $id)->first();


        $tc = Finance_coa::orderBy('code')->get();

        $tc_code = $tc->pluck('code', 'id');
        $tc_name = $tc->pluck('name', 'id');

        return view('finance.inv_in.view', [
            'inv' => $inv,
            'ipay' => $vPay,
            'paper' => $paper,
            'paid' => $paid,
            'tc' => $tc,
            'tc_code' => $tc_code,
            'tc_name' => $tc_name,
        ]);
    }

    function pay(Request $request){
        $iPay = new Finance_invoice_in_pay();

        $iPay->inv_id = $request->id;
        $iPay->pay_num = $request->pay_num;
        $iPay->amount = str_replace(",", "", $request->amount);
        $iPay->pay_date = $request->pay_date;
        $iPay->due_date = date("Y-m-d", strtotime("+1 months ".$request->pay_date));
        $iPay->description = $request->description;
        $iPay->company_id = Session::get('company_id');

        $iPay->save();
        return redirect()->route('inv_in.view', $request->id);
    }

    function delete_pay(Request $request){
        $iPay = Finance_invoice_in_pay::find($request->id)->delete();
        if ($iPay){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function delete(Request $request){
        $iPay = Finance_invoice_in::find($request->id)->delete();
        $pay = Finance_invoice_in_pay::where('inv_id', $request->id)->delete();
        if ($iPay){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function list_items(Request $request){
        $inv_in = Finance_invoice_in::where('company_id', Session::get('company_id'))->get();
        $data_detail = Finance_invoice_in_pay::all();
        $data = array();
        $list = array();
        $detail = array();
        $payment = array();
        foreach ($data_detail as $item){
            if ($item->paid == 1){
                $detail[$item->inv_id][] = $item->amount;
                $payment[$item->inv_id][] = $item->pay_date;
            }
        }

        $data_supplier = Procurement_vendor::all();
        $supplier = array();
        foreach ($data_supplier as $item){
            $supplier[$item->id] = $item;
        }

        $po = Asset_po::where('company_id', Session::get('company_id'))->get();
        $detail_po = Asset_po_detail::all();
        $am_po = array();
        foreach ($detail_po as $item){
            $am_po[$item->po_num][] = $item->qty * $item->price;
        }

        foreach ($po as $item){
            $porow = array();
            $porow['paper'] = $item['po_num'];
            if (isset($supplier[$item['supplier_id']])){
                $porow['supplier'] = $supplier[$item['supplier_id']]->name;
                $porow['bank_account'] = $supplier[$item['supplier_id']]->bank_acct;
            } else {
                $porow['supplier'] = "";
                $porow['bank_account'] = "";
            }
            $porow['amount'] = (isset($am_po[$item->id])) ? array_sum($am_po[$item->id]) : 0;
            $porow['gr_date'] = (!empty($item['gr_date'])) ? $item['gr_date'] : "N/A";
            $porow['currency'] = $item['currency'];
            $list['PO'][$item->id] = $porow;
        }

        $wo = Asset_wo::where('company_id', Session::get('company_id'))->get();
        $detail_wo = Asset_wo_detail::all();
        $am_wo = array();
        foreach ($detail_wo as $item){
            $am_wo[$item->wo_id][] = $item->qty * $item->unit_price;
        }

        foreach ($wo as $item){
            $worow = array();
            $worow['paper'] = $item['wo_num'];
            if (isset($supplier[$item['supplier_id']])){
                $worow['supplier'] = $supplier[$item['supplier_id']]->name;
                $worow['bank_account'] = $supplier[$item['supplier_id']]->bank_acct;
            } else {
                $worow['supplier'] = "";
                $worow['bank_account'] = "";
            }
            $worow['amount'] = (isset($am_po[$item->id])) ? array_sum($am_po[$item->id]) : 0;
            $worow['gr_date'] = "N/A";
            $worow['currency'] = $item['currency'];
            $list['WO'][$item->id] = $worow;
        }

        foreach ($inv_in as $i => $item){
            $row = array();
            if (isset($list[$item->paper_type][$item->paper_id])){
                $det = $list[$item->paper_type][$item->paper_id];
                $row['i'] = $i+1;
                $row['paper'] = $det['paper'];
                $row['supplier'] = $det['supplier'];
                $row['bank_account'] = $det['bank_account'];
                $row['currency'] = $det['currency'];
                $row['amount'] = "<span class='text-right'>".number_format($det['amount'], 2)."</span>";
                $row['input_date'] = date('d F Y', strtotime($item->app_date));
                $row['gr_date'] = $det['gr_date'];
                $row['due_date'] = date('d F Y', strtotime($item->app_date));
                $row['payment_date'] = (isset($payment[$item->id])) ? date('d F Y', strtotime(max($payment[$item->id]))) : "N/A";
                $row['payment_history'] = "<a href='".route('inv_in.view', $item->id)."' class='btn btn-sm btn-primary'><i class='fa fa-search'></i> View</a>";
                $data[] = $row;
            }
        }

        $val = array(
            "data" => $data
        );
        return json_encode($val);
    }

    function get_tc($type, Request $request){
        $src = Finance_coa_source::where('name', strtolower($type))->first();
        $parent = Finance_coa::where('source', 'like', '%"'.$src->id.'"%')->get();
        $tc = Finance_coa::where(function($query) use($parent){
            foreach($parent as $item){
                $cd = rtrim($item->code, 0);
                $query->where('parent_id', 'like', "$cd%");
            }
        })
        ->whereRaw("(name like '%$request->q%' or code like '%$request->q%')")
        ->get();

        $data = [];
        foreach($tc as $item){
            $row = [];
            $row['id'] = $item->id;
            $row['text'] = "[$item->code] $item->name";
            $data[] = $row;
        }

        $results = array(
            'results' => $data
        );

        return json_encode($results);

    }

    function assign_tc(Request $request){

        $inv = Finance_invoice_in::find($request->id);
        $inv->tc_id = $request->_tc;
        if($inv->save()){
            // input to coa history
            $coa = Finance_coa::find($request->_tc);

            $coa_code = $coa->code;

            if($inv->paper_type == "PO"){
                $po = Asset_po::find($inv->paper_id);
                $paper_no = $po->po_num;
                $curr = $po->currency;
                $prj = $po->project;
            } else {
                $wo = Asset_wo::find($inv->paper_id);
                $paper_no = $wo->wo_num;
                $curr = $wo->currency;
                $prj = $wo->project;
            }

            $coa = Finance_coa_history::where('paper_type', "INVIN")
                ->where('paper_id', $inv->id)
                ->first();

            if(empty($coa)){
                $coa = new Finance_coa_history();
                $coa->project = $prj;
                $coa->paper_type = "INVIN";
                $coa->paper_id = $inv->id;
                $coa->description = "INVOICE IN : ".$inv->paper_type." $paper_no [$prj]";
                $coa->coa_date = date("Y-m-d");
                $coa->debit = $inv->amount;
                $coa->currency = $curr;
                $coa->company_id = $inv->company_id;
                $coa->created_by = Auth::user()->username;
            } else {
                $coa->updated_by = Auth::user()->username;
            }

            $coa->no_coa = $coa_code;
            $coa->save();
        }

        return redirect()->back();
    }
}
