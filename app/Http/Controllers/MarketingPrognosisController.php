<?php

namespace App\Http\Controllers;

use App\Models\Asset_po;
use App\Models\Asset_po_detail;
use App\Models\Asset_type_po;
use App\Models\Asset_type_wo;
use App\Models\Asset_wo;
use App\Models\Asset_wo_detail;
use App\Models\ConfigCompany;
use App\Models\Finance_coa;
use App\Models\Finance_coa_source;
use App\Models\Finance_invoice_in;
use App\Models\Finance_invoice_in_pay;
use App\Models\Finance_invoice_out;
use App\Models\Finance_invoice_out_detail;
use App\Models\Finance_treasury;
use App\Models\Finance_treasury_history;
use App\Models\Finance_util_salary;
use App\Models\General_cashbond;
use App\Models\General_cashbond_detail;
use App\Models\General_reimburse;
use App\Models\General_reimburse_detail;
use App\Models\General_travel_order;
use App\Models\Marketing_c_prognosis;
use App\Models\Marketing_project;
use App\Models\Marketing_subcost_detail;
use App\Models\Pref_tax_config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class MarketingPrognosisController extends Controller
{
    function index($id){
        $project = Marketing_project::find($id);

        $tables = ["sales", "cost", "operating_expenses"];
        $num = array();
        foreach ($tables as $item){
            $pro = Marketing_c_prognosis::where('id_project', $id)
                ->where('category', $item)
                ->orderBy('RCTR', "desc")
                ->first();

            if (empty($pro)){
                $num[$item] = sprintf("%03d", 1);
            } else {
                $num[$item] = sprintf("%03d", intval(substr($pro->RCTR, -3)) + 1);
            }
        }

        $prognosis = Marketing_c_prognosis::where('id_project', $id)->get();
        $totalsales = 0;
        foreach ($prognosis as $item) {
            if ($item->category == "sales"){
                $totalsales += $item->amount;
            }
        }

        return view('prognosis.index', [
            'project' => $project,
            'tables' => $tables,
            'num' => $num,
            'prognosis' => $prognosis,
            'totalsales' => $totalsales
        ]);
    }

    function view($id){
        $prjId = base64_decode($id);
        $prj = Marketing_project::find($prjId);
        $prognosis = Marketing_c_prognosis::where('id_project', $prjId)->get();

        $total_sales = 0;
        foreach ($prognosis as $list){
            if ($list->category == "sales"){
                $total_sales += $list->amount;
            }
        }

        $treasure = Finance_treasury::where('company_id', Session::get('company_id'))->get();

        $data = $this->cacl($prjId);

        $history = Finance_treasury_history::where('date_input', 'like', "".date('Y')."%")
            ->orderBy('date_input', 'asc')
            ->get();
        $balance_treasure = array();
        foreach ($history as $item){
            $balance_treasure[$item->id_treasure][] = $item->IDR;
        }

        return view('prognosis.view', [
            'prj' => $prj,
            'prognosis' => $prognosis,
            'totalsales' => $total_sales,
            'data_value' => $data,
            'treasure' => $treasure,
            'balance_treasure' => $balance_treasure
        ]);
    }

    function project($code, Request $request){
        $project = Marketing_project::find($request->id_project);
        if ($code == "share"){
            $project->sharing_profit = $request->share_profit;
        } elseif ($code == "bank"){
            $project->bank_account = $request->treasure;
        } elseif ($code == "tax"){
            $project->tax = $request->tax;
        }

        $project->save();
        return redirect()->back();
    }

    function modal($type, $id){
        $prognosis = Marketing_c_prognosis::find($id);
        $project = Marketing_project::where('company_id', Session::get('company_id'))->get();
        $prj = array();
        foreach ($project as $list){
            $prj[$list->id] = $list;
        }

        $inv = Finance_invoice_out::where('company_id', Session::get('company_id'))->get();

        $inv_list = array();
        foreach ($inv as $item){
            $title = json_decode($item->title);
            $row['id'] = $title->id;
            $row['name'] = (isset($prj[$title->id])) ? $prj[$title->id]['prj_name'] : "";
            $row['aggrement'] = (isset($prj[$title->id])) ? $prj[$title->id]['agreement_number'] : "";
            $inv_list[] = $row;
        }

        $tax = Pref_tax_config::all();

        if ($type == "sales"){

            $whitelist = array();

            if (!empty($prognosis->whitelists)){
                $white = json_decode($prognosis->whitelists);
                foreach ($white as $key => $item){
                    foreach ($item as $i => $list){
                        $whitelist[$i] = $list;
                    }
                }
            }


            return view('prognosis.modal_sales', [
                'detail' => $prognosis,
                'inv' => $inv_list,
                'tax' => $tax,
                'whitelist' => $whitelist
            ]);
        } else {
            /*cost & oe modal*/
            /*PO*/
            $whitelist = array();
            if (!empty($prognosis->whitelists)){
                $white = json_decode($prognosis->whitelists);
                foreach ($white as $key => $item){
                    foreach ($item as $i => $list){
                        $whitelist[$i] = $list;
                    }
                }
            }

            $posrc = Finance_coa_source::where('name', 'po')->first();
            $type_po = [];
            if(!empty($posrc)){
                $parent = Finance_coa::where('source', 'like', '%"'.$posrc->id.'"%')
                    ->get();
                $type_po = Finance_coa::where(function($query) use($parent){
                    foreach ($parent as $key => $value) {
                        $prCode = rtrim($value->code, "0");
                        $query->orWhere('parent_id', 'like', "$prCode%");
                    }
                })->get();
            }
            // $type_po = Finance_coa::all();

            /*WO*/
            $wosrc = Finance_coa_source::where('name', 'wo')->first();
            $type_wo = [];
            if(!empty($wosrc)){
                $parent = Finance_coa::where('source', 'like', '%"'.$wosrc->id.'"%')
                    ->get();
                $type_wo = Finance_coa::where(function($query) use($parent){
                    foreach ($parent as $key => $value) {
                        $prCode = rtrim($value->code, "0");
                        $query->orWhere('parent_id', 'like', "$prCode%");
                    }
                })->get();
            }
            // $type_wo = Asset_type_wo::all();

            $tre_hist = array(
                'pajak' => 'Pajak',
                'bunga' => 'Bunga',
                'adm' => 'Administrasi',
            );



            return view('prognosis.modal_cost_oe', [
                'detail' => $prognosis,
                'type' => $type,
                'prj_show' => $project,
                'type_po' => $type_po,
                'type_wo' => $type_wo,
                'inv' => $inv_list,
                'tax' => $tax,
                'tre_his' => $tre_hist,
                'whitelist' => $whitelist
            ]);
        }
    }

    function add(Request $request){
//        dd($request);
        $nProg = new Marketing_c_prognosis();
        $nProg->RCTR = $request->code_project;
        $nProg->id_project = $request->project;
        $nProg->subject = ($request->list_prognosis == null) ? strtoupper($request->subject) : strtoupper($request->list_prognosis);
        $nProg->description = ($request->list_prognosis == null) ? strtoupper($request->subject) : strtoupper($request->list_prognosis);
        $nProg->category = $request->type;
        $nProg->amount = $request->amount;
        $nProg->created_by = Auth::user()->username;
        $nProg->company_id = Session::get('company_id');
        $nProg->save();

        return redirect()->back();
    }

    function delete($id){
        $prog = Marketing_c_prognosis::find($id);
        if (!empty($prog)) {
            $prog->delete();
        }
        return redirect()->back();
    }

    function update(Request $request){
        $prog = Marketing_c_prognosis::find($request->id_prog);
        $prog->amount = $request->value_p;
        if (!empty($request->notes)){
            $prog->notes = $request->notes;
        }

        $prog->save();
        return redirect()->back();
    }

    function whitelists(Request $request, $type){

        $prog = Marketing_c_prognosis::find($request->id_prog);
        if ($type == "sales"){
            if (isset($request->inv_out)){
                $row['inv_out'] = $request->inv_out;
            }

            if (isset($request->agree)){
                $row['agreement'] = $request->agree;
            }

            if (isset($request->tax)){
                $row['tax'] = $request->tax;
            }

            $white[] = $row;
            $prog->whitelists = json_encode($white);
        } else {
            if (isset($request->inv_out)){
                $row['inv_out'] = $request->inv_out;
            }

            if (isset($request->agree)){
                $row['agreement'] = $request->agree;
            }

            if (isset($request->tax)){
                $row['tax'] = $request->tax;
            }

            /*PO*/
            if (isset($request->po)){
                $po = $request->po;
                $po_cat = $request->po_cat;
                foreach ($po as $i => $item){
                    if (!empty($item) && isset($po_cat[$i])){
                        $row['po'][$i]['project'] = $item;
                        $row['po'][$i]['category'] = $po_cat[$i];
                    }
                }
            }

            /*WO*/
            if (isset($request->wo)){
                $wo = $request->wo;
                $wo_cat = $request->wo_cat;
                foreach ($wo as $i => $item){
                    if (!empty($item) && isset($wo_cat[$i])){
                        $row['wo'][$i]['project'] = $item;
                        $row['wo'][$i]['category'] = $wo_cat[$i];
                    }
                }
            }

            /*CASHBOND*/
            if (isset($request->cb)){
                $cb = $request->cb;
                $cb_cat = $request->cb_cat;
                foreach ($cb as $i => $item){
                    if (!empty($item) && isset($cb_cat[$i])){
                        $row['cashbond'][$i]['project'] = $item;
                        $row['cashbond'][$i]['category'] = $cb_cat[$i];
                    }
                }
            }

            /*REIMBURSE*/
            if (isset($request->rs)){
                $rs = $request->rs;
                $rs_cat = $request->rs_cat;
                foreach ($rs as $i => $item){
                    if (!empty($item) && isset($rs_cat[$i])){
                        $row['reimburse'][$i]['project'] = $item;
                        $row['reimburse'][$i]['category'] = $rs_cat[$i];
                    }
                }
            }

            /*BP*/
            if (isset($request->bp)){
                $bp = $request->bp;
                foreach ($bp as $item){
                    if (!empty($item)){
                        $row['bp'][] = $item;
                    }
                }
            }

            /*TO*/
            if (isset($request->to)){
                $to = $request->to;
                foreach ($to as $item){
                    if (!empty($item)){
                        $row['to'][] = $item;
                    }
                }
            }

            /*SUBCOST*/
            if (isset($request->sc)){
                $sc = $request->sc;
                foreach ($sc as $item){
                    if (!empty($item)){
                        $row['subcost'][] = $item;
                    }
                }
            }

            /*payroll*/
            if (isset($request->payroll)){
                $row['payroll'] = $request->payroll;
            }

            if (isset($request->tre_his)){
                $row['treasure_history'] = $request->tre_his;
            }

            $white[] = $row;
            $prog->whitelists = json_encode($white);
        }

        $prog->save();
        return redirect()->back();
    }

    function excel_export($id){
        $prognosis = Marketing_c_prognosis::find($id);
        $data = $this->cacl($prognosis->id_project);

        return view('prognosis.excel', [
            'data' => $data,
            'id' => $id,
            'prognosis' => $prognosis
        ]);
    }

    function cacl($project){
        $data = array();
        $prognosis = Marketing_c_prognosis::where('id_project', $project)->get();
        $sessId = Session::get('company_id');

        /*MASTER DATA*/
        $desc = array();
        $tax = Pref_tax_config::all();
        $id_ppn = "";
        $formula = array();
        foreach ($tax as $item){
            if (strpos(strtolower($item->tax_name), "ppn 10") !== false){
                $id_ppn = $item->id;
            }
            $formula[$item->id] = $item->formula;
        }


        $data_project = Marketing_project::where('company_id', $sessId)->get();
        $project = array();
        foreach ($data_project as $item){
            $project[$item->id] = $item;
        }

        $data_inv_detail = Finance_invoice_out_detail::all();
        $inv_detail = array();
        $inv_taxes = array();
        foreach ($data_inv_detail as $item){
            $inv_detail[$item->id_inv][] = $item->value_d;
            if (!empty($item->taxes) && $item->taxes != "null"){
                $taxes = json_decode($item->taxes);
                foreach ($taxes as $iTax){
                    $sum = $item->value_d;
                    $ppn = eval("return $formula[$iTax];");
                    $inv_taxes[$item->id_inv][$iTax][] = $ppn;
                }
            }
        }


        $data_inv_out = Finance_invoice_out::where('company_id', $sessId)->get();
        $inv_out = array();
        foreach ($data_inv_out as $item){
            $inv_out[$item->id_project]['inv'][$item->id_inv] = (isset($inv_detail[$item->id_inv])) ? array_sum($inv_detail[$item->id_inv]) : 0;
            $inv_out[$item->id_project]['tax'][$item->id_inv] = (isset($inv_taxes[$item->id_inv])) ? $inv_taxes[$item->id_inv] : array();
            $row['paper'] = $project[$item->id_project]['agreement_number'];
            $row['amount'] = (isset($inv_detail[$item->id_inv])) ? array_sum($inv_detail[$item->id_inv]) : 0;;
            $desc['agreement'][$item->id_inv] = $row;
        }

        $type = array();

        /*INVOICE IN*/
        $data_in_detail = Finance_invoice_in_pay::where('paid', 1)->get();
        $in_detail = array();
        foreach ($data_in_detail as $item){
            $in_detail[$item->inv_id][] = $item->amount;
        }

        $data_inv_in = Finance_invoice_in::where('company_id', $sessId)->get();
        $inv_in = array();
        foreach ($data_inv_in as $item){
            $inv_in[strtolower($item->paper_type)][$item->paper_id] = (isset($in_detail[$item->id])) ? array_sum($in_detail[$item->id]) : 0;
        }


        /*PO*/
        $data_type_po = Asset_type_po::all();
        foreach ($data_type_po as $item){
            $nTy = str_replace(" ", "_", str_replace("/", "_", $item->name));
            $type['po'][$nTy] = $item->id;
        }

        $data_po_detail = Asset_po_detail::all();
        $nDetail = array();

        foreach ($data_po_detail as $item){
            $nDetail['po'][$item->po_num][] = $item->qty * $item->price;
        }

        $data_po = Asset_po::where('company_id', $sessId)
            ->whereNotNull('approved_by')
            ->get();
        $po = array();
        $paid = array();

        foreach ($data_po as $item){
            $ty = str_replace(" ", "_", str_replace("/", "_", $item->po_type));
            $ppn_eval = 0;
            if (isset($type['po'][$ty])){
                $val_detail = (isset($nDetail['po'][$item->id])) ? array_sum($nDetail['po'][$item->id]) : 0;
                $discount = $item->discount;
                if (!empty($item->ppn)){
                    $poTax = json_decode($item->ppn);
                    if (is_array($poTax)) {
                        foreach ($poTax as $pTax){
                            if ($pTax == $id_ppn){
                                $sum = $val_detail - $discount;
                                $ppn_eval = eval("return $formula[$pTax];");
                            }
                        }
                    }
                }
                $po[$item->project][$type['po'][$ty]][$item->id] = $val_detail - $discount + $ppn_eval;
                if (isset($inv_in['po'][$item->id])){
                    $paid['po'][$item->project][$type['po'][$ty]][$item->id] = $inv_in['po'][$item->id];
                }
                $row['paper'] = $item->po_num;
                $row['discount'] = $item->discount;
                $row['ppn'] = $ppn_eval;
                $row['amount'] = $val_detail;
                $desc['po'][$item->id] = $row;
            }
        }


        /*WO*/
        $data_type_wo = Asset_type_wo::all();
        foreach ($data_type_wo as $item){
            $nTy = str_replace(" ", "_", str_replace("/", "_", $item->name));
            $type['wo'][$nTy] = $item->id;
        }

        $data_wo_detail = Asset_wo_detail::all();

        foreach ($data_wo_detail as $item){
            $nDetail['wo'][$item->wo_id][] = $item->qty * $item->unit_price;
        }

        $data_wo = Asset_wo::where('company_id', $sessId)
            ->whereNotNull('approved_by')
            ->get();
        $wo = array();
        foreach ($data_wo as $item){
            $ty = str_replace(" ", "_", str_replace("/", "_", $item->wo_type));
            if (isset($type['wo'][$ty])){
                $val_detail = (isset($nDetail['wo'][$item->id])) ? array_sum($nDetail['wo'][$item->id]) : 0;
                $discount = $item->discount;
                $ppn_eval = 0;
                if (!empty($item->ppn)){
                    $poTax = json_decode($item->ppn);
                    if (is_array($poTax)) {
                        foreach ($poTax as $pTax){
                            if ($pTax == $id_ppn){
                                $sum = $val_detail - $discount;
                                $ppn_eval = eval("return $formula[$pTax];");
                            }
                        }
                    }

                }
                $wo[$item->project][$type['wo'][$ty]][$item->id] = $val_detail - $discount + $ppn_eval;
                if (isset($inv_in['wo'][$item->id])){
                    $paid['wo'][$item->project][$type['wo'][$ty]][$item->id] = $inv_in['wo'][$item->id];
                }
                $row['paper'] = $item->wo_num;
                $row['discount'] = $item->discount;
                $row['ppn'] = $ppn_eval;
                $row['amount'] = $val_detail;
                $desc['wo'][$item->id] = $row;
            }
        }

        /*CASHBOND*/
        $data_detail_cashbond = General_cashbond_detail::all();
        foreach ($data_detail_cashbond as $item){
            if (!empty($item->category)){
                $nDetail['cashbond'][$item->id_cashbond][$item->category][] = $item->cashout;
            }
        }

        $data_company = ConfigCompany::all();
        $company = array();
        foreach ($data_company as $item){
            $company[$item->id] = $item;
        }

        $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");

        $data_cashbond = General_cashbond::where('company_id', $sessId)->get();
        $cashbond = array();
        foreach ($data_cashbond as $item){
            if (isset($nDetail['cashbond'][$item->id])){
                $cashbond[$item->project][$item->id] = $nDetail['cashbond'][$item->id];
                $bln = $array_bln[date('n', strtotime($item->input_date))];
                $row['paper'] = sprintf("%03d", $item->id)."/".$company[$item->company_id]['tag']."/CASHBOND/".$bln."/".date('y');
                $row['amount'] = $nDetail['cashbond'][$item->id];
                $desc['cashbond'][$item->id] = $row;
            }
        }

        /*REIMBURSE*/
        $data_detail_reimburse = General_reimburse_detail::all();
        foreach ($data_detail_reimburse as $item){
            if (!empty($item->category)){
                $nDetail['reimburse'][$item->id_reimburse][$item->category][] = $item->cashout;
            }
        }

        $data_reimburse = General_reimburse::where('company_id', $sessId)->get();
        $reimburse = array();
        foreach ($data_reimburse as $item){
            if (isset($nDetail['reimburse'][$item->id])){
                $reimburse[$item->project][$item->id] = $nDetail['reimburse'][$item->id];
                $bln = $array_bln[date('n', strtotime($item->input_date))];
                $row['paper'] = sprintf("%03d", $item->id)."/".$company[$item->company_id]['tag']."/REIMBURSE/".$bln."/".date('y');
                $row['amount'] = $nDetail['reimburse'][$item->id];
                $desc['reimburse'][$item->id] = $row;
            }
        }

        /*TO*/
        $data_to = General_travel_order::where('company_id', $sessId)
            ->where('action', 'approve')
            ->get();
        $to = array();
        foreach ($data_to as $item){
            $meal = intval($item->duration) * intval($item->to_meal);
            $spending = intval($item->duration) * intval($item->to_spending);
            $overnight = intval($item->duration) * intval($item->to_overnight);
            $transport = intval($item->transport);
            $local_trans = intval($item->to_transport);
            $taxi = intval($item->taxi);
            $carrent = intval($item->rent);
            $airtax = intval($item->airtax);

            $totalcostFT = $meal + $spending + $overnight + $transport + $local_trans + $taxi + $carrent + $airtax;
            $to[$item->project][$item->id] = $totalcostFT;
            $row['paper'] = $item->doc_num;
            $row['amount'] = $totalcostFT;
            $desc['to'][$item->id] = $row;
        }

        /*SUBCOST*/
        $data_subcost = Marketing_subcost_detail::all();
        $subcost = array();
        foreach ($data_subcost as $item){
            $subcost[$item->id_subcost][$item->id] = $item->cashout;
            $row['paper'] = $item->no_nota;
            $desc['subcost'][$item->id] = $row;
        }

        /*BID PERFORMA BOND*/
        $data_bp = Marketing_bp::whereNotNull('price_date')->get();
        $detail_bp = Marketing_bp_detail::all();
        $bp_detail = [];
        foreach($detail_bp as $item){
            $bp_detail[$item->id_main][] = $item;
            $desc['bp'][$item->id] = $item;
        }
        $bp = [];
        foreach($data_bp as $item){
            $det = [];
            if(isset($bp_detail[$item->id])){
                $det = $bp_detail[$item->id];
            }

            $item->det = $det;
            $bp[$item->prj_code][] = $item;
        }

        /*PAYROLL*/
        $data_payroll = Finance_util_salary::where('company_id', $sessId)->get();
        $payroll = 0;
        foreach ($data_payroll as $item){
            $payroll += $item->amount;
        }

        /*TREASURE HISTORY*/


        /*END MASTER DATA*/


        foreach ($prognosis as $item){
            if (!empty($item->whitelists)){
                $white = json_decode($item->whitelists);
                foreach ($white as $list){
                    foreach ($list as $key => $value){
                        $act_value[$key] = 0;
                        $paid_value[$key] = 0;
                        $nData[$key] = array();
                        if ($key == "inv_out"){
                            foreach ($value as $detail){
                                if (isset($inv_out[$detail])){
                                    $act_value[$key] += array_sum($inv_out[$detail]['inv']);
                                    $paid_value[$key] += array_sum($inv_out[$detail]['inv']);
                                }
                            }

                        }
                        if ($key == "agreement"){
                            foreach ($value as $detail){
                                if (isset($inv_out[$detail]['tax'])){
                                    foreach ($inv_out[$detail]['tax'] as $n => $x){
                                        foreach ($x as $y){
                                            $act_value[$key] += array_sum($y);
                                            $paid_value[$key] += array_sum($y);
                                            $col = array();
                                            $col['paper'] = $desc[$key][$n]['paper'];
                                            $col['amount'] = array_sum($y);
                                            $nData[$key]['value'][$n] = $col;
                                            $nData[$key]['paid'][$n] = $col;
                                        }

                                    }
                                }
                            }
                        }
                        if ($key == "po"){
                            foreach ($value as $detail){
                                if (isset($po[$detail->project])){
                                    foreach ($detail->category as $cat){
                                        if (isset($po[$detail->project][$cat])){
                                            $act_value[$key] += array_sum($po[$detail->project][$cat]);
                                            foreach ($po[$detail->project][$cat] as $n => $m){
                                                if (isset($desc['po'][$n])){
                                                    $col['paper'] = $desc['po'][$n]['paper'];
                                                    $col['amount'] = $desc['po'][$n]['amount'];
                                                    $col['discount'] = $desc['po'][$n]['discount'];
                                                    $col['ppn'] = $desc['po'][$n]['ppn'];
                                                    $col['url'] = route('po.view', $n);
                                                    $nData[$key]['value'][$n] = $col;
                                                }
                                            }
                                            if (isset($paid['po'][$detail->project][$cat])){
                                                $paid_value[$key] += array_sum($paid['po'][$detail->project][$cat]);
                                                foreach ($paid['po'][$detail->project][$cat] as $n => $m){
                                                    if (isset($desc['po'][$n])){
                                                        $colPaid['paper'] = $desc['po'][$n]['paper'];
                                                        $colPaid['amount'] = $m;
                                                        $nData[$key]['paid'][$n] = $colPaid;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if ($key == "wo"){
                            foreach ($value as $detail){
                                if (isset($wo[$detail->project])){
                                    foreach ($detail->category as $cat){
                                        if (isset($wo[$detail->project][$cat])){
                                            $act_value[$key] += array_sum($wo[$detail->project][$cat]);
                                            foreach ($wo[$detail->project][$cat] as $n => $m){
                                                if (isset($desc['wo'][$n])){
                                                    $col['paper'] = $desc['wo'][$n]['paper'];
                                                    $col['amount'] = $desc['wo'][$n]['amount'];
                                                    $col['discount'] = $desc['wo'][$n]['discount'];
                                                    $col['ppn'] = $desc['wo'][$n]['ppn'];
                                                    $col['url'] = route('wo.view', $n);
                                                    $nData[$key]['value'][$n] = $col;
                                                }
                                            }
                                            if (isset($paid['wo'][$detail->project][$cat])){
                                                $paid_value[$key] += array_sum($paid['wo'][$detail->project][$cat]);
                                                foreach ($paid['wo'][$detail->project][$cat] as $n => $m){
                                                    if (isset($desc['wo'][$n])){
                                                        $colPaid['paper'] = $desc['wo'][$n]['paper'];
                                                        $colPaid['amount'] = $m;
                                                        $nData[$key]['paid'][$n] = $colPaid;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if ($key == "cashbond"){
                            foreach ($value as $detail){
                                if (isset($cashbond[$detail->project])){
                                    foreach ($cashbond[$detail->project] as $n => $cb){
                                        $am = 0;
                                        foreach ($detail->category as $cat){
                                            if (isset($cb[$cat])){
                                                $act_value[$key] += array_sum($cb[$cat]);
                                                $paid_value[$key] += array_sum($cb[$cat]);
                                                $am += array_sum($cb[$cat]);
                                            }
                                        }
                                        if (isset($desc['cashbond'][$n])){
                                            $col = array();
                                            $col['paper'] = $desc['cashbond'][$n]['paper'];
                                            $col['amount'] = $am;
                                            $col['url'] = route('cashbond.detail', $n);
                                            $nData[$key]['value'][$n] = $col;
                                            $nData[$key]['paid'][$n] = $col;
                                        }
                                    }
                                }
                            }
                        }
                        if ($key == "reimburse"){
                            foreach ($value as $detail){
                                if (isset($reimburse[$detail->project])){
                                    foreach ($reimburse[$detail->project] as $n => $cb){
                                        $am = 0;
                                        foreach ($detail->category as $cat){
                                            if (isset($cb[$cat])){
                                                $act_value[$key] += array_sum($cb[$cat]);
                                                $paid_value[$key] += array_sum($cb[$cat]);
                                                $am += array_sum($cb[$cat]);
                                            }
                                        }
                                        if (isset($desc['reimburse'][$n])){
                                            $col = array();
                                            $col['paper'] = $desc['reimburse'][$n]['paper'];
                                            $col['amount'] = $am;
                                            $col['url'] = route('reimburse.detail', $n);
                                            $nData[$key]['value'][$n] = $col;
                                            $nData[$key]['paid'][$n] = $col;
                                        }
                                    }
                                }
                            }
                        }
                        if ($key == "to"){
                            foreach ($value as $detail){
                                if (isset($to[$detail])){
                                    $act_value[$key] += array_sum($to[$detail]);
                                    $paid_value[$key] += array_sum($to[$detail]);
                                    foreach ($to[$detail] as $n => $iTo){
                                        if (isset($desc[$key][$n])){
                                            $col = array();
                                            $col['paper'] = $desc[$key][$n]['paper'];
                                            $col['amount'] = $iTo;
                                            $col['url'] = route('to.ftdetail', $n);
                                            $nData[$key]['value'][$n] = $col;
                                            $nData[$key]['paid'][$n] = $col;
                                        }
                                    }
                                }
                            }
                        }
                        if ($key == "payroll"){
                            $act_value[$key] += $payroll;
                            $paid_value[$key] += $payroll;
                            $col = array();
                            $col['paper'] = "PAYROLL : ".number_format($payroll, 2);
                            $col['url'] = "#";
                            $col['amount'] = $payroll;
                            $nData['payroll']['value'][] = $col;
                            $nData['payroll']['paid'][] = $col;
                        }

                        if ($key == "subcost"){
                            foreach ($value as $detail){
                                if (isset($subcost[$detail])){
                                    $act_value[$key] += array_sum($subcost[$detail]);
                                    $paid_value[$key] += array_sum($subcost[$detail]);
                                    $iCol = array();
                                    foreach ($subcost[$detail] as $n => $iTo){
                                        if (isset($desc[$key][$n])){
                                            $iCol[$n]['desc'] = $desc[$key][$n]['paper'];
                                            $iCol[$n]['amount'] = $iTo;
                                        }
                                    }
                                    $col = array();
                                    $col['paper'] = "SUBCOCST: ".$project[$detail]['agreement_number'];
                                    $col['url'] = route('subcost.detail', $detail);
                                    $col['subcost'] = $iCol;
                                    $nData[$key]['value'][$detail] = $col;
                                    $nData[$key]['paid'][$detail] = $col;
                                }
                            }
                        }

                        if($key == "bp"){
                            foreach($value as $detail){
                                if(isset($bp[$detail])){
                                    foreach($bp[$detail] as $n => $iBp){
                                        $bpval = 0;
                                        $iCol = [];
                                        $_val = 0;
                                        // dd($iBp->det);
                                        foreach($iBp->det as $iBpDet){
                                            if(!empty($iBpDet->actual_amount) && $iBpDet->actual_amount > 0){
                                                $bpval += $iBpDet->actual_amount;
                                            } else {
                                                $bpval += $iBpDet->request_amount;
                                            }

                                            if(!empty($iBp->final_date)){
                                                if($iBpDet->item_name == "AMOUNT"){
                                                    $bpval = 0;
                                                }
                                            }

                                            $iCol[$n]["desc"] = $iBpDet->item_name;
                                            $iCol[$n]['amount'] = $bpval;
                                        }
                                        $_val = $bpval;
                                        $act_value[$key] += $_val;
                                        $paid_value[$key] += $_val;
                                        $col = array();
                                        $col['paper'] = "BID PERFORMA BOND: ".$iBp->no_tender;
                                        $col['url'] = route('bp.view', $iBp->id);
                                        // $col['bp'] = $iCol;
                                        $col['amount'] = $_val;
                                        $nData[$key]['value'][$iBp->id] = $col;
                                        $nData[$key]['paid'][$iBp->id] = $col;
                                    }
                                }
                            }
                        }

                        if ($key == "treasure_history"){

                        }

                        if ($key != "tax"){
                            $data[$item->id][$key]['actual_value'] = $act_value[$key];
                            $data[$item->id][$key]['paid_value'] = $paid_value[$key];
                            if (isset($desc[$key]) || $key == "payroll"){
                                $data[$item->id][$key]['description'] = $nData[$key];
                            }
                        }
                    }
                }
            }
        }

//        dd($data);

        return $data;
    }
}
