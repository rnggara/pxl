<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Asset_po;
use App\Models\Asset_wo;
use Illuminate\Http\Request;
use App\Models\Asset_po_detail;
use App\Models\Asset_wo_detail;
use App\Models\Pref_tax_config;
use App\Models\Marketing_clients;
use App\Models\Marketing_project;
use App\Models\Finance_invoice_in;
use App\Models\Finance_invoice_in_pay;
use App\Models\Finance_invoice_out;
use App\Models\Finance_invoice_out_detail;
use App\Models\Procurement_vendor;

class ARReport extends Controller
{
    function index(Request $request){

        if($request->ajax()){

            $filter = $request->filter;
            $date_to = $request->date_to;

            $in_d = Finance_invoice_out_detail::where('company_id', Session::get('company_id'))
                ->whereNull('ceo_app_date')
                ->whereNull("due_date")
                ->orderBy('due_date', 'desc')
                ->get();

            foreach($in_d as $item){
                $item->due_date = date("Y-m-d", strtotime("+1 months ".$item->date));
                $item->save();
            }

            $m = date("n");
            if($filter == "<3"){
                $_date = date("Y-m-d", strtotime("$date_to -3 months"));
                $whereDate = "due_date > '$_date' && due_date <= '$date_to'";
            } elseif($filter == "<6"){
                $_date = date("Y-m-d", strtotime("$date_to -3 months"));
                $_date2 = date("Y-m-d", strtotime("$date_to -6 months"));
                $whereDate = "due_date < '$_date' and due_date > '$_date2'";
            } elseif($filter == ">6"){
                $_date2 = date("Y-m-d", strtotime("$date_to -6 months"));
                $whereDate = "due_date < '$_date2'";
            } else {
                $whereDate = " due_date <= '$date_to'";
            }

            $inv = Finance_invoice_out::where('company_id', Session::get('company_id'))->get();

            $ppn = Pref_tax_config::where('tax_name', 'like', '%ppn%')->first();
            $pph23 = Pref_tax_config::where('tax_name', 'pph 23')->first();


            $inv_detail = Finance_invoice_out_detail::where('company_id', Session::get('company_id'))
                ->whereRaw($whereDate)
                ->whereNull('ceo_app_date')
                ->orderBy('due_date', 'desc')
                ->get();

            $detail = [];
            foreach ($inv_detail as $key => $value) {
                $value->date = date("d-M-Y", strtotime($value->date));
                $tax = (!empty($value->taxes)) ? json_decode($value->taxes, true) : [];
                $ppn10 = 0;
                $pph23_val = 0;

                $wapu = true;
                if($value->wapu == 0 || empty($value->wapu)){
                    $wapu = false;
                }

                if($wapu){
                    if(in_array($ppn->id, $tax)){
                        $sum = $value->value_d;
                        $eval = eval("return ".$ppn->formula.";");
                        $ppn10 = $eval;
                    }
                }

                if(in_array($pph23->id, $tax)){
                    $sum = $value->value_d;
                    $eval = eval("return ".$pph23->formula.";");
                    $pph23_val = $eval;
                }
                $value->ppn = $ppn10;
                $value->pph23 = $pph23_val;

                $d1 = date_create(date("Y-m-d"));
                $d2 = date_create($value->due_date);

                $diff = date_diff($d1, $d2);
                $m = $diff->format("%m");
                $y = $diff->format("%y");

                if($y > 0){
                    $m += ($y * 12);
                }

                $value->diff = $m;
                $value->due_date_text = date("d F Y", strtotime($value->due_date));

                $detail[$value->id_inv][] = $value;
            }

            $inv_prj = [];
            foreach ($inv as $key => $value) {
                if (isset($detail[$value->id_inv])) {
                    $inv_prj[$value->id_project]['no_invoice'] = $value->no;
                    $inv_prj[$value->id_project]['detail'] = $detail[$value->id_inv];
                }
            }

            $project = Marketing_project::where('company_id', Session::get('company_id'))
                ->get();


            $data = [];
            foreach ($project as $key => $value) {
                if(isset($inv_prj[$value->id])){
                    $cl = Marketing_clients::find($value->id_client);
                    if(!empty($cl)){
                        $data[$value->id_client]['client_name'] = $cl->company_name;
                        $data[$value->id_client]['data'][$value->id]['prj_name'] = $value->prj_name;
                        $data[$value->id_client]['data'][$value->id]['no_invoice'] = $inv_prj[$value->id]['no_invoice'] ?? "";
                        $data[$value->id_client]['data'][$value->id]['invoice'] = $inv_prj[$value->id]['detail'];
                    }
                }
            }

            return json_encode($data);
        }

        return view('report.ar.index');
    }

    function update(Request $request){
        if($request->ajax()){
            if($request->type == "due_date"){
                $inv = Finance_invoice_out_detail::find($request->id);
                $inv->due_date = $request->date;
                if($inv->save()){
                    $response = [
                        "success" => true,
                        "message" => ""
                    ];
                } else {
                    $response = [
                        "success" => false,
                        "message" => "Error updating data"
                    ];
                }
            }

            if($request->type == "remarks"){
                $inv = Finance_invoice_out_detail::find($request->id);
                $inv->remarks = $request->remarks;
                if($inv->save()){
                    $response = [
                        "success" => true,
                        "message" => ""
                    ];
                } else {
                    $response = [
                        "success" => false,
                        "message" => "Error updating data"
                    ];
                }
            }

            return json_encode($response);
        }
    }

    function updateAP(Request $request){
        if($request->ajax()){
            if($request->type == "due_date"){
                $inv = Finance_invoice_in_pay::find($request->id);
                $inv->due_date = $request->date;
                if($inv->save()){
                    $response = [
                        "success" => true,
                        "message" => ""
                    ];
                } else {
                    $response = [
                        "success" => false,
                        "message" => "Error updating data"
                    ];
                }
            }

            if($request->type == "remarks"){
                $inv = Finance_invoice_in_pay::find($request->id);
                $inv->remarks = $request->remarks;
                if($inv->save()){
                    $response = [
                        "success" => true,
                        "message" => ""
                    ];
                } else {
                    $response = [
                        "success" => false,
                        "message" => "Error updating data"
                    ];
                }
            }

            if($request->type == "pay_date"){
                $inv = Finance_invoice_in_pay::find($request->id);
                $inv->pay_date = $request->pay_date;
                if($inv->save()){
                    $response = [
                        "success" => true,
                        "message" => ""
                    ];
                } else {
                    $response = [
                        "success" => false,
                        "message" => "Error updating data"
                    ];
                }
            }

            if($request->type == "close"){
                // dd($request);
                $inv_pay = Finance_invoice_in_pay::find($request->id);
                $inv_pay->paid = 1;
                $inv_pay->save();
                $inv = Finance_invoice_in::find($inv_pay->inv_id);
                $inv->amount_left = 0;
                $inv->status = "closed";
                $inv->save();
                $response = [
                    "success" => true,
                    "message" => ""
                ];
            }

            return json_encode($response);
        }
    }

    function indexAP(Request $request){
        if ($request->ajax()) {
            $row = [];

            $filter = $request->filter;
            $date_to = $request->date_to;

            $in = Finance_invoice_in_pay::whereRaw("(paid is null or paid = 0)")
                ->whereNull('due_date')
                ->orderBy('pay_date', 'desc')
                ->get();
            foreach($in as $item){
                $item->due_date = date("d F Y", strtotime($item->pay_date));
                $item->save();
            }

            if($filter == "<3"){
                $_date = date("Y-m-d", strtotime("$date_to -3 months"));
                $whereDate = "due_date > '$_date' && due_date <= '$date_to'";
            } elseif($filter == "<6"){
                $_date = date("Y-m-d", strtotime("$date_to -3 months"));
                $_date2 = date("Y-m-d", strtotime("$date_to -6 months"));
                $whereDate = "due_date < '$_date' and due_date > '$_date2'";
            } elseif($filter == ">6"){
                $_date2 = date("Y-m-d", strtotime("$date_to -6 months"));
                $whereDate = "due_date < '$_date2'";
            } else {
                $whereDate = " due_date <= '$date_to'";
            }

            $po = Asset_po::where('company_id', Session::get('company_id'))
                ->get();
            $po_num = $po->pluck('po_num', 'id');
            $po_prj = $po->pluck('project', 'id');
            $po_tax = $po->pluck("ppn", "id");
            $po_supplier = $po->pluck('supplier_id', "id");

            $supplier = Procurement_vendor::all()->pluck('name', 'id');

            $ppn = Pref_tax_config::where('tax_name', 'like', '%ppn%')->first();
            $pph23 = Pref_tax_config::where('tax_name', 'pph 23')->first();

            $inv_in = Finance_invoice_in::where("company_id", Session::get('company_id'))
                // ->whereRaw($whereDate)
                ->where('paper_type', "PO")
                ->orderBy('app_date', 'desc')
                ->get();

            $inv_po = [];

            $dnow = date("Y-m-d");

            foreach ($inv_in as $key => $value) {
                if (isset($po_num[$value->paper_id])) {
                    $inv_po[$value->id]['po_num'] = $po_num[$value->paper_id];
                    $inv_po[$value->id]['invoice'] = $value;
                    $col = [];
                    $inpay = Finance_invoice_in_pay::selectRaw("*, remarks as r, DATE_FORMAT(pay_date, '%d-%b-%y') as pay_date_format, TIMESTAMPDIFF(MONTH, due_date, '".$date_to."') as diff")
                        ->where('inv_id', $value->id)
                        ->whereRaw($whereDate)
                        ->whereRaw("(paid is null or paid = 0)")
                        ->orderBy('pay_date', 'desc')
                        ->get();

                    foreach($inpay as $i){
                        if(empty($i->due_date)){
                            $i->due_date = $i->pay_date;
                            $i->save();
                        }
                        $i->pay_date = date("d F Y", strtotime($i->pay_date));
                        $i->due_date = date("d F Y", strtotime($i->due_date));
                    }

                    $supplier_id = $po_supplier[$value->paper_id];

                    $suplier_name = "";
                    if(isset($supplier[$supplier_id])){
                        $suplier_name = $supplier[$supplier_id];
                    }
                    if(count($inpay) > 0){
                        $col['value'] = $value->amount_left;
                        $col['invoice'] = $inpay;
                        $col['project'] = $po_prj[$value->paper_id];
                        $col['num'] = $po_num[$value->paper_id];
                        $col['remarks'] = $value->remarks;
                        $col['detail'] = Asset_po_detail::selectRaw("asset_po_detail.price, asset_po_detail.qty, asset_po_detail.item_id, asset_items.name")
                            ->leftJoin('asset_items', 'asset_po_detail.item_id', 'asset_items.item_code')
                            ->where('po_num', $value->paper_id)->get();
                        $row[$suplier_name]['PO'][] = $col;
                    }
                }
            }

            $wo = Asset_wo::where('company_id', Session::get('company_id'))
                ->get();
            $wo_num = $wo->pluck('wo_num', 'id');
            $wo_prj = $wo->pluck('project', 'id');
            $wo_supplier = $wo->pluck('supplier_id', 'id');

            $inv_in = Finance_invoice_in::where("company_id", Session::get('company_id'))
                // ->whereRaw($whereDate)
                ->where('paper_type', "WO")
                ->orderBy('app_date', 'desc')
                ->get();

            foreach ($inv_in as $key => $value) {
                if (isset($wo_num[$value->paper_id])) {
                    $col = [];
                    $inpay = Finance_invoice_in_pay::selectRaw("*, DATE_FORMAT(pay_date, '%d-%b-%y') as pay_date_format, TIMESTAMPDIFF(MONTH, due_date, '".$date_to."') as diff")
                        ->where('inv_id', $value->id)
                        ->whereRaw($whereDate)
                        ->whereRaw("(paid is null or paid = 0)")
                        ->orderBy('pay_date', 'desc')
                        ->get();

                    foreach($inpay as $i){
                        if(empty($i->due_date)){
                            $i->due_date = $i->pay_date;
                            $i->save();
                        }
                        $i->pay_date = date("d F Y", strtotime($i->pay_date));
                        $i->due_date = date("d F Y", strtotime($i->due_date));
                    }

                    $supplier_id = $wo_supplier[$value->paper_id];

                    $suplier_name = "";
                    if(isset($supplier[$supplier_id])){
                        $suplier_name = $supplier[$supplier_id];
                    }
                    if(count($inpay) > 0){
                        $col['value'] = $value->amount_left;
                        $col['invoice'] = $inpay;
                        $col['project'] = $wo_prj[$value->paper_id];
                        $col['num'] = $wo_num[$value->paper_id];
                        $col['remarks'] = $value->remarks;
                        $col['detail'] = Asset_wo_detail::selectRaw("asset_wo_detail.unit_price as price, asset_wo_detail.qty, asset_wo_detail.job_desc as name")
                            ->where('wo_id', $value->paper_id)->get();
                        $row[$suplier_name]['WO'][] = $col;
                    }
                }
            }

            return json_encode($row);
        }
        return view('report.ap.index');
    }
}
