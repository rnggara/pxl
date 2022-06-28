<?php

namespace App\Http\Controllers;

use App\Models\Asset_po;
use App\Models\Asset_po_detail;
use App\Models\Asset_wo;
use App\Models\Asset_wo_detail;
use App\Models\Finance_invoice_out;
use App\Models\Finance_invoice_out_detail;
use App\Models\Pref_tax_config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FinanceTaxController extends Controller
{
    private $tax;

    public function __construct()
    {
        $tax = Pref_tax_config::all();
        $this->tax = $tax;
    }

    function index(){
        return view('finance.tax.index', [
            'tax' => $this->tax
        ]);
    }

    function get_data(Request $request){
//        dd($request);
        $data = array();
        $num = 1;
        $total_sum = 0;
        $rTax = $request->tax;
        foreach ($this->tax as $taxes){
            $formula[$taxes->id] = $taxes->formula;
            $tax_name[$taxes->id] = $taxes->tax_name;
        }
        // PO
        $po = Asset_po::where('company_id', Session::get('company_id'))->get();
        $po_detail = Asset_po_detail::all();
        foreach ($po_detail as $item){
            $amount_po[$item->po_num][] = $item->qty * $item->price;
        }
        foreach ($po as $item){
            if ($item->ppn != null && $request->type != "out"){
                $jsonppn = json_decode($item->ppn, true);
                if(is_array($jsonppn)){
                    foreach (json_decode($item->ppn) as $tax){
                        if ($rTax[0] == "all" || $rTax[0] != "all" && in_array($tax, $rTax)) {
                            if ($request->sdate == null && $request->sdate == null || $request->sdate != null && $request->sdate != null && strtotime($item->po_date) >= strtotime($request->sdate) && strtotime($item->po_date) <= strtotime($request->edate)) {
                                $list['num'] = $num;
                                $list['source'] = "<span class='label label-inline label-success'>IN</span>";
                                $list['paper_type'] = "PO";
                                $list['paper'] = $item->po_num;
                                $list['date'] = $item->po_date;
                                $list['taxtype'] = $tax_name[$tax];
                                $sum = array_sum($amount_po[$item->id]);
                                $sumstring = $formula[$tax];
                                $amount = eval("return $sumstring;");
                                $total_sum += $amount;
                                $list['taxamount'] = number_format($amount);
                                $list['link'] = "<a href='" . route('po.view', $item->id) . "' class='btn btn-xs btn-icon btn-primary'><i class='fa fa-eye'></i></a>";
                                $data[] = $list;
                                $num++;
                            }
                        }
                    }
                }
            }
        }

        // WO
        $wo = Asset_wo::where('company_id', Session::get('company_id'))->get();
        $wo_detail = Asset_wo_detail::all();
        foreach ($wo_detail as $item){
            $amount_wo[$item->wo_id][] = $item->qty * $item->unit_price;
        }
        foreach ($wo as $item){
            if ($item->ppn != null && $request->type != "out"){
                $jsonppn = json_decode($item->ppn, true);
                if(is_array($jsonppn)){
                    foreach (json_decode($item->ppn) as $tax){
                        if ($rTax[0] == "all" || $rTax[0] != "all" && in_array($tax, $rTax)) {
                            if ($request->sdate == null && $request->sdate == null || $request->sdate != null && $request->sdate != null && strtotime($item->req_date) >= strtotime($request->sdate) && strtotime($item->req_date) <= strtotime($request->edate)) {
                                $list['num'] = $num;
                                $list['source'] = "<span class='label label-inline label-success'>IN</span>";
                                $list['paper_type'] = "WO";
                                $list['paper'] = $item->wo_num;
                                $list['date'] = $item->req_date;
                                $list['taxtype'] = $tax_name[$tax];
                                $sum = array_sum($amount_wo[$item->id]);
                                $sumstring = $formula[$tax];
                                $amount = eval("return $sumstring;");
                                $total_sum += $amount;
                                $list['taxamount'] = number_format($amount);
                                $list['link'] = "<a href='" . route('wo.view', $item->id) . "' class='btn btn-xs btn-icon btn-primary'><i class='fa fa-eye'></i></a>";
                                $data[] = $list;
                                $num++;
                            }
                        }
                    }
                }
            }
        }

        // INVOICE
        $inv = Finance_invoice_out::where('company_id', Session::get('company_id'))->get();
        foreach ($inv as $item){
            $id_inv[] = $item->id_inv;
        }
        $inv_det = Finance_invoice_out_detail::whereIn('id_inv', $id_inv)->get();
        foreach ($inv_det as $item){
            if ($item->taxes != null && $request->type != "in"){
                foreach (json_decode($item->taxes) as $tax){
                    if ($rTax[0] == "all" || $rTax[0] != "all" && in_array($tax, $rTax)) {
                        if ($request->sdate == null && $request->sdate == null || $request->sdate != null && $request->sdate != null && strtotime($item->date) >= strtotime($request->sdate) && strtotime($item->date) <= strtotime($request->edate)) {
                            $list['num'] = $num;
                            $list['source'] = "<span class='label label-inline label-danger'>OUT</span>";
                            $list['paper_type'] = "INVOICE";
                            $list['paper'] = $item->no_inv;
                            $list['date'] = $item->date;
                            $list['taxtype'] = $tax_name[$tax];
                            $sum = $item->value_d;
                            $sumstring = $formula[$tax];
                            $amount = eval("return $sumstring;");
                            $total_sum += $amount;
                            $list['taxamount'] = number_format($amount);
                            $list['link'] = "<a href='" . route('ar.view_entry', ['id'=>$item->id, 'act'=>'view']) . "' class='btn btn-xs btn-icon btn-primary'><i class='fa fa-eye'></i></a>";
                            $data[] = $list;
                            $num++;
                        }
                    }
                }
            }
        }

        $val = array(
            'total' => number_format($total_sum, 2),
            'data' => $data
        );

        return json_encode($val);
    }
}
