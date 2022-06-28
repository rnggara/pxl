<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Finance_coa;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\Marketing_leads;
use App\Models\Pref_tax_config;
use App\Models\Finance_treasury;
use App\Models\Marketing_clients;
use App\Models\Marketing_project;
use App\Models\Finance_coa_history;
use App\Models\Finance_invoice_out;
use Illuminate\Support\Facades\Auth;
use App\Models\Finance_treasury_history;
use App\Models\Finance_invoice_out_print;
use App\Models\Finance_invoice_out_detail;

class FinanceAccountReceivable extends Controller
{

    function _list(){
        $inv_out = Finance_invoice_out::where('company_id', Session::get('company_id'))
            ->orderBy('created_at', 'desc')
            ->get();

        $inv_detail = Finance_invoice_out_detail::all();
        $i_activity = [];
        $i_date = [];
        $i_value_d = [];
        $i_approved = [];
        foreach ($inv_detail as $item){
            $i_activity[$item->id_inv][] = $item->activity;
            $i_date[$item->id_inv][] = $item->date;
            $i_value_d[$item->id_inv][] = $item->value_d;
            if ($item->status == "approved"){
                $i_approved[$item->id_inv][] = $item->value_d;
            }
        }

        $prj = Marketing_project::where('company_id', Session::get('company_id'))->get();
        $prj_name = $prj->pluck('prj_name', 'id');
        $prj_aggr_num = $prj->pluck('agreement_number', 'id');
        $prj_client = $prj->pluck('id_client', 'id');
        $prj_val = $prj->pluck('value', 'id');

        $client = Marketing_clients::where('company_id', Session::get("company_id"))->get();
        $client_name = $client->pluck('company_name', 'id');

        $data = [];
        foreach($inv_out as $i => $inv){
            $inv_prj = json_decode($inv->title, true);
            $prj_id = "";
            $aggre_num = $inv->no;
            $c_name = "N/A";
            $title = "";
            if(is_array($inv_prj) && $inv_prj['type'] == 'project'){
                $prj_id = $inv_prj['id'];
            } else {
                $prj_id = $inv->id_project;
            }

            $rem_value = (isset($i_value_d[$inv->id_inv])) ? array_sum($i_value_d[$inv->id_inv]) : 0;

            $row = [];
            if(isset($prj_name[$prj_id])){
                if(empty($inv->no)){
                    $aggre_num = $prj_aggr_num[$prj_id];
                }
                $id_client = $prj_client[$prj_id];
                if(isset($client_name[$id_client])){
                    $c_name = $client_name[$id_client];
                }

                $inv_date = "-";

                if(isset($i_date[$inv->id_inv])){
                    $inv_date = '<div class="accordion accordion-toggle-arrow" id="accordionExample2">';
                    $inv_date .= '<div class="card">';
                    $inv_date .= '<div class="card-header" id="headingOne2">';
                    $inv_date .= '<div class="card-title collapsed" data-toggle="collapse" data-target="#collapse'.$i.'">';
                    $inv_date .= 'Invoice Date';
                    $inv_date .= '</div>';
                    $inv_date .= '</div>';
                    $inv_date .= '<div id="collapse'.$i.'" class="collapse" data-parent="#accordionExample2">';
                    $inv_date .= '<div class="card-body">';
                    foreach($i_date[$inv->id_inv] as $key => $dates){
                        $inv_date .= '<i class="flaticon2-right-arrow"></i> '.$i_activity[$inv->id_inv][$key].': '.date('d M Y', strtotime($dates)).' <br>';
                    }

                    $inv_date .= '</div>';
                    $inv_date .= '</div>';
                    $inv_date .= '</div>';
                    $inv_date .= '</div>';
                }

                $title .= $prj_name[$prj_id];
                $title .= "<br>";
                $title .= "<br>";
                $title .= "Client : ".$c_name;
                // $title .= "<br>";
                // $title .= number_format($prj_val[$prj_id], 2);
                $btn_delete = "";

                // if (RolesManagement::actionStart('inv_out', 'delete')) {
                //     $btn_delete = '<button class="btn btn-icon btn-xs btn-danger" onclick="button_delete('.$inv->id_inv.')"><i class="fa fa-trash"></i></button>';
                // }

                $btn_delete = '<button class="btn btn-icon btn-xs btn-danger" onclick="button_delete('.$inv->id_inv.')"><i class="fa fa-trash"></i></button>';

                $row['i'] = $i+1;
                $row['aggrement_num'] = "<a href='".route('ar.view', $inv->id_inv)."' class='label label-inline label-primary bg-hover-light-primary text-hover-primary text-nowrap'>".$aggre_num."</a>";
                $row['title'] = $title;
                $row['type'] = (isset($inv_prj['type'])) ? strtoupper($inv_prj['type']) : strtoupper($inv['type']);
                $row['invoice_date'] = $inv_date;
                $row['total_value'] = number_format($prj_val[$prj_id], 2);
                $row['remaining_value'] = number_format($prj_val[$prj_id] - $rem_value, 2);
                $row['action'] = $btn_delete;
                $data[] = $row;
            }
        }

        $result = array(
            "data" => $data
        );

        return json_encode($result);
    }

    function index(){
        $clients = Marketing_clients::where('company_id', Session::get('company_id'))->get();
        $tc = Finance_coa::orderBy('code')->get();

        return view('finance.account_receivable.index', [
            'clients' => $clients,
            'tc' => $tc
        ]);
    }



    function getProjectLeads($id){
        $project = Marketing_project::where('id_client', $id)->get();
        $leads = Marketing_leads::where('id_client', $id)->get();
        $data = [];
        $val = [];
        foreach ($project as $item){
            $data['id'] = $item->id."-project";
            $data['text'] = $item->prj_name." [".$item->agreement_number."]";
            $val[] = (object) $data;
        }

        foreach ($leads as $item){
            $data['id'] = $item->id."-leads";
            $data['text'] = $item->leads_name."-[leads]";
            $val[] = (object) $data;
        }

        usort($val, function ($a, $b) {
            return strcmp($a->text, $b->text);
        });

        $response = [
            'results' => $val,
            'pagination' => ["more" => true]
        ];

        return json_encode($response);
    }

    function check_inv($id){
        $x = explode("-", $id);
        $data['id'] = $x[0];
        $data['type'] = $x[1];
        $title = json_encode($data);


        $inv_out = Finance_invoice_out::where('title', 'like', "%".$title."%")->get();


        return count($inv_out);
    }

    function add(Request $request){
        $pl = explode("-", $request->project_leads);
        $data['id'] = $pl[0];
        $data['type'] = $pl[1];
        $data['tag'] = strtoupper($request->inv_code);

        $inv_out = new Finance_invoice_out();
        $inv_out->title = json_encode($data);
        $inv_out->title = json_encode($data);
        $inv_out->id_project = $pl[0];
        $inv_out->type = $pl[1];
        $inv_out->tag = strtoupper($request->inv_code);
        $inv_out->created_by = Auth::user()->username;
        $inv_out->company_id = Session::get('company_id');
        if(!empty($request->tc)){
            $inv_out->tc_id = $request->tc;
        }
        $inv_out->save();

        // if(!empty($inv_out->tc_id)){
        //     // input to coa history
        //     $coa = Finance_coa::find($request->tc);

        //     $coa_code = $coa->code;

        //     $prj = Marketing_project::find($inv_out->id_project);

        //     $coa = Finance_coa_history::where('paper_type', "INVOUT")
        //         ->where('paper_id', $inv_out->id_inv)
        //         ->first();

        //     if(empty($coa)){
        //         $coa = new Finance_coa_history();
        //         $coa->paper_type = "INVOUT";
        //         $coa->paper_id = $inv_out->id_inv;
        //         $coa->description = "INVOICE OUT : $prj->prj_name";
        //         $coa->coa_date = date("Y-m-d");
        //         $coa->credit = $prj->value;
        //         $coa->currency = $prj->currency;
        //         $coa->company_id = $prj->company_id;
        //         $coa->created_by = Auth::user()->username;
        //     } else {
        //         $coa->updated_by = Auth::user()->username;
        //     }

        //     $coa->no_coa = $coa_code;
        //     $coa->save();
        // }

        return redirect()->route('ar.index');
    }

    function view($id){
        $inv_out = Finance_invoice_out::where('id_inv', $id)->first();

        $project = Marketing_project::where('company_id', Session::get('company_id'))->get();
        $leads = Marketing_leads::where('company_id', Session::get('company_id'))->get();

        $det = Finance_invoice_out_detail::where('id_inv', $id)
            ->whereNull('revise_approved_at')
            ->orderBy('date', 'desc')
            ->get();

        $prj_name = [];
        $leads_name = [];
        foreach ($project as $item){
            $prj_name[$item->id] = $item->prj_name;
        }

        foreach ($leads as $item){
            $leads_name[$item->id] = $item->leads_name;
        }

        $bank = Finance_treasury::where('company_id', Session::get('company_id'))->get();
        $bank_name = [];
        foreach ($bank as $item){
            $bank_name[$item->id] = "[".$item->currency."] ".$item->source;
        }
        // $taxes = Pref_tax_config::where('company_id', Session::get('company_id'))->get();
        $taxes = Pref_tax_config::all();
        $tax = [];
        foreach ($taxes as $item){
            $tax[$item->id] = $item;
        }

        $coa = Finance_coa::all();

        return view('finance.account_receivable.view', [
            'inv' => $inv_out,
            'prj_name' => $prj_name,
            'leads_name' => $leads_name,
            'banks' => $bank,
            'taxes' => $taxes,
            'details' => $det,
            'bank_name' => $bank_name,
            'tax' => $tax,
            'coa' => $coa
        ]);
    }

    function delete($id){
        if (Finance_invoice_out::where('id_inv',$id)->delete()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function addEntry(Request $request){
        $arrRomawi	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $num = Finance_invoice_out_detail::where('year_id_inv', date('Y', strtotime($request->date)))
            ->where('id_inv', $request->id_inv)
            ->orderBy('no_id_inv', 'desc')
            ->limit(1)
            ->first();
        if (!empty($num)){
            $no_num = $num->no_id_inv + 1;
        } else {
            $no_num = 1;
        }
        $inv = Finance_invoice_out::where('id_inv', $request->id_inv)->first();
        $title = json_decode($inv->title);
        if(isset($title->tag)){
            $_tag = $title->tag;
        } else {
            $_tag = $inv->tag;
        }

        $_inv_tag = Finance_invoice_out::where('tag', $_tag)->get();
        if(count($_inv_tag) > 1){
            $_tag .= "-".$inv->id_project;
        }


        $m = date("n", strtotime($request->date));
        $inv_num = sprintf("%03d", $no_num)."/INV-".Session::get('company_tag')."/".$_tag."/".$arrRomawi[$m]."/".date("Y", strtotime($request->date));
        $inv_detail = new Finance_invoice_out_detail();
        $inv_detail->year_id_inv = date('Y', strtotime($request->date));
        $inv_detail->no_id_inv = $no_num;
        $inv_detail->no_inv = $inv_num;
        $inv_detail->id_inv = $request->id_inv;
        $inv_detail->activity = $request->activity;
        $inv_detail->date = $request->date;
        $inv_detail->due_date = date("Y-m-d", strtotime("+1 months ".$request->date));
        $inv_detail->payment_account = $request->bank_src;
        if (isset($request->tax) && !empty($request->tax)) {
            $inv_detail->taxes = json_encode($request->tax);
        }
        $inv_detail->value_d = 0;
        $inv_detail->created_by = Auth::user()->username;
        $inv_detail->company_id = Session::get('company_id');
        $inv_detail->tc_id = $request->tc_id;
        if (isset($request->wapu)){
            $inv_detail->wapu = $request->wapu;
        }

        $inv_detail->save();
        return redirect()->route('ar.view', $request->id_inv);
    }

    function delete_entry($id){
        if (Finance_invoice_out_detail::find($id)->delete()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function input_entry($id){
        $inv_detail = Finance_invoice_out_detail::find($id);
        $inv = Finance_invoice_out::where('id_inv', $inv_detail->id_inv)->first();

        $project = Marketing_project::where('company_id', Session::get('company_id'))->get();
        $leads = Marketing_leads::where('company_id', Session::get('company_id'))->get();

        $prj_name = [];
        $prj_client = [];
        $leads_name = [];
        $leads_client = [];
        foreach ($project as $item){
            $prj_name[$item->id] = $item->agreement_title;
            $prj_client[$item->id] = $item->id_client;
        }

        foreach ($leads as $item){
            $leads_name[$item->id] = $item->leads_name;
            $leads_client[$item->id] = $item->id_client;
        }

        $title = json_decode($inv->title);
        // dd($title);

        // $id_client = ($title->type == "project") ? ((isset($prj_client[$title->id]))?$prj_client[$title->id]:'') : ((isset($leads_client[$title->id]))?$leads_client[$title->id]:'');
        // $title_name = ($title->type == "project") ? ((isset($prj_name[$title->id]))?$prj_name[$title->id]:'') : ((isset($leads_name[$title->id]))?$leads_name[$title->id]:'');

        $id_client = $prj_client[$inv->id_project];
        $title_name = $prj_name[$inv->id_project];

        $clients = Marketing_clients::where('company_id', Session::get('company_id'))->get();
        $client_address = [];
        $client_pic = [];
        foreach ($clients as $item){
            $client_address[$item->id] = $item->address;
            $client_pic[$item->id] = $item->pic;
        }
        $taxes = Pref_tax_config::all();
        $tax_name = [];
        $tax_formula = [];
        $isWapu = [];
        $isPrint = [];
        foreach ($taxes as $item){
            $tax_name[$item->id] = $item->tax_name;
            $tax_formula[$item->id] = $item->formula;
            $isWapu[$item->id] = $item->is_wapu;
            $isPrint[$item->id] = $item->is_print;
        }

        return view('finance.account_receivable.input', [
            'inv_detail' => $inv_detail,
            'client_address' => (isset($client_address[$id_client]))?$client_address[$id_client]:'',
            'client_pic' => (isset($client_pic[$id_client]))?$client_pic[$id_client]:'',
            'title_name' => $title_name,
            'taxes' => $taxes,
            'tax_name' => $tax_name,
            'tax_formula' => $tax_formula,
            'isWapu' => $isWapu,
            'isPrint' => $isPrint
        ]);
    }

    function edit_entry($id){
        $inv_detail = Finance_invoice_out_detail::find($id);
        $inv = Finance_invoice_out::where('id_inv', $inv_detail->id_inv)->first();
        $inv_print = Finance_invoice_out_print::where('id_inv_out_detail', $inv_detail->id)->get();

        $project = Marketing_project::where('company_id', Session::get('company_id'))->get();
        $leads = Marketing_leads::where('company_id', Session::get('company_id'))->get();

        $prj_name = [];
        $prj_client = [];
        $leads_name = [];
        $leads_client = [];
        foreach ($project as $item){
            $prj_name[$item->id] = $item->agreement_title;
            $prj_client[$item->id] = $item->id_client;
        }

        foreach ($leads as $item){
            $leads_name[$item->id] = $item->leads_name;
            $leads_client[$item->id] = $item->id_client;
        }

        $title = json_decode($inv->title);
        // dd($title);

        // $id_client = ($title->type == "project") ? ((isset($prj_client[$title->id]))?$prj_client[$title->id]:'') : ((isset($leads_client[$title->id]))?$leads_client[$title->id]:'');
        // $title_name = ($title->type == "project") ? ((isset($prj_name[$title->id]))?$prj_name[$title->id]:'') : ((isset($leads_name[$title->id]))?$leads_name[$title->id]:'');

        $id_client = $prj_client[$inv->id_project];
        $title_name = $prj_name[$inv->id_project];

        $clients = Marketing_clients::where('company_id', Session::get('company_id'))->get();
        $client_address = [];
        $client_pic = [];
        foreach ($clients as $item){
            $client_address[$item->id] = $item->address;
            $client_pic[$item->id] = $item->pic;
        }
        $taxes = Pref_tax_config::all();
        $tax_name = [];
        $tax_formula = [];
        $isWapu = [];
        $isPrint = [];
        foreach ($taxes as $item){
            $tax_name[$item->id] = $item->tax_name;
            $tax_formula[$item->id] = $item->formula;
            $isWapu[$item->id] = $item->is_wapu;
            $isPrint[$item->id] = $item->is_print;
        }

        return view('finance.account_receivable.edit_input', [
            'inv_detail' => $inv_detail,
            'client_address' => (isset($client_address[$id_client]))?$client_address[$id_client]:'',
            'client_pic' => (isset($client_pic[$id_client]))?$client_pic[$id_client]:'',
            'title_name' => $title_name,
            'taxes' => $taxes,
            'tax_name' => $tax_name,
            'tax_formula' => $tax_formula,
            'isWapu' => $isWapu,
            'isPrint' => $isPrint,
            'invPrint' => $inv_print,
        ]);
    }

    function view_entry($id, $act){
        $inv_detail = Finance_invoice_out_detail::find($id);
        $inv = Finance_invoice_out::where('id_inv', $inv_detail->id_inv)->first();
        $prj = Marketing_project::where('id', $inv->id_project)->first();
        // dd($prj);
        $print = Finance_invoice_out_print::where('id_inv_out_detail', $id)->get();

        $project = Marketing_project::where('company_id', Session::get('company_id'))->get();
        // dd($project);
        $leads = Marketing_leads::where('company_id', Session::get('company_id'))->get();

        $prj_name = [];
        $prj_client = [];
        $leads_name = [];
        $leads_client = [];
        foreach ($project as $item){
            $prj_name[$item->id] = $item->agreement_title;
            $prj_client[$item->id] = $item->id_client;
        }

        foreach ($leads as $item){
            $leads_name[$item->id] = $item->leads_name;
            $leads_client[$item->id] = $item->id_client;
        }

        $title = json_decode($inv->title);

        $prjId = (!empty($inv->id_project)) ? $inv->id_project : $title->id;

        $id_client = (isset($prj_client[$prjId])) ? $prj_client[$prjId] : "";
        $title_name = (isset($prj_name[$prjId])) ? $prj_name[$prjId] : "";

        $clients = Marketing_clients::where('company_id', Session::get('company_id'))->get();
        $client_address = [];
        $client_pic = [];
        foreach ($clients as $item){
            $client_address[$item->id] = $item->address;
            $client_pic[$item->id] = $item->pic;
        }
        $taxes = Pref_tax_config::all();
        $tax_name = [];
        $tax_formula = [];
        $isWapu = [];
        $isPrint = [];
        foreach ($taxes as $item){
            $tax_name[$item->id] = $item->tax_name;
            $tax_formula[$item->id] = $item->formula;
            $isWapu[$item->id] = $item->is_wapu;
            $isPrint[$item->id] = $item->is_print;
        }
        $src = Finance_treasury::where('company_id', $inv->company_id)
            ->where('type', 'bank')
            ->get();
//        return view('finance.account_receivable.detail', [
//            'inv_detail' => $inv_detail,
//            'client_address' => $client_address[$id_client],
//            'client_pic' => $client_pic[$id_client],
//            'title_name' => $title_name,
//            'taxes' => $taxes,
//            'tax_name' => $tax_name,
//            'tax_formula' => $tax_formula,
//            'inv_prints' => $print,
//            'act' => $act
//        ]);
        $c_address = (isset($client_address[$id_client])) ? $client_address[$id_client] : "";
        $c_pic = (isset($client_pic[$id_client])) ? $client_pic[$id_client] : "";
        $company = ConfigCompany::find($inv->company_id);
        if ($act == 'print'){
            $titleinv = json_decode($inv->title);
            if(!empty($inv->id_project)){
                $id_prj = $inv->id_project;
            } else {
                $id_prj = $titleinv->id;
            }
            $prj = Marketing_project::where('id',$id_prj)->first();
            if (!empty($prj->id_client)) {
               $data_client = Marketing_clients::where('id', $prj->id_client)->first();
            } else {
                $data_client = array();
            }

            $payment_account = Finance_treasury::where('id',$inv_detail->payment_account)->first();
//            dd($payment_account);
            return view('finance.account_receivable.print', [
                'inv_detail' => $inv_detail,
                'inv' => $inv,
                'prj' => $prj,
                'client_address' => $c_address,
                'client_pic' => $c_pic,
                'title_name' => $title_name,
                'taxes' => $taxes,
                'tax_name' => $tax_name,
                'tax_formula' => $tax_formula,
                'inv_prints' => $print,
                'act' => $act,
                'data_client' => $data_client,
                'payment_account' => $payment_account,
                'isWapu' => $isWapu,
                'isPrint' => $isPrint,
                'company' => $company
            ]);
        } else {
            return view('finance.account_receivable.detail', [
                'inv_detail' => $inv_detail,
                'inv' => $inv,
                'prj' => $prj,
                'client_address' => $c_address,
                'client_pic' => $c_pic,
                'title_name' => $title_name,
                'taxes' => $taxes,
                'tax_name' => $tax_name,
                'tax_formula' => $tax_formula,
                'inv_prints' => $print,
                'act' => $act,
                'isWapu' => $isWapu,
                'src' => $src,
                'company' => $company
            ]);
        }
    }

    function appr_manager(Request $request){
        $inv_detail = Finance_invoice_out_detail::find($request->id_detail);
        $inv_detail->fin_approved_date = date('Y-m-d');
        $inv_detail->fin_approved_by = Auth::user()->username;
        $inv_detail->fin_approved_note = $request->notes;
        $inv_detail->save();
        return redirect()->route('ar.view', $inv_detail->id_inv);
    }

    function appr_finance(Request $request){
        $inv_detail = Finance_invoice_out_detail::find($request->id_detail);
        $inv_detail->ceo_app_date = date('Y-m-d');
        $inv_detail->ceo_app_by = Auth::user()->username;
        $inv_detail->ceo_app_note = $request->app_notes;
        $inv_detail->status = "approved";

        // input to treasure history

        $inv = Finance_invoice_out::where('id_inv', $inv_detail->id_inv)->first();
        $project = Marketing_project::where('company_id', Session::get('company_id'))->get();
        $leads = Marketing_leads::where('company_id', Session::get('company_id'))->get();

        $prj_name = [];
        $prj_client = [];
        $leads_name = [];
        $leads_client = [];
        foreach ($project as $item){
            $prj_name[$item->id] = $item->prj_name;
            $prj_client[$item->id] = $item->id_client;
        }

        foreach ($leads as $item){
            $leads_name[$item->id] = $item->leads_name;
            $leads_client[$item->id] = $item->id_client;
        }

        $iTax = array();
        $tax = Pref_tax_config::all();
        foreach ($tax as $value) {
            $iTax[$value->id] = $value;
        }

        $amountTax = 0;

        if (!empty($inv_detail->taxes)) {
            $taxes = json_decode($inv_detail->taxes);
            if (is_array($taxes)) {
                foreach ($taxes as $value) {
                    $sum = $inv_detail->value_d - $inv_detail->discount;
                    $aTax = eval("return ".$iTax[$value]->formula.";");
                    if ($inv_detail->wapu =="on") {
                        if ($iTax[$value]->is_wapu != 1) {
                            $amountTax += $aTax;
                        }
                    } else {
                        $amountTax += $aTax;
                    }

                }
            }
        }

        $title = json_decode($inv->title);

        // if(is_object($title)){
        //     $prj_id = (!empty($inv->project)) ? $inv->project : $title->id;
        // } else {
        //     $prj_id = $inv->ptoject;
        // }

        $prj_id = $inv->id_project;

        $title_name = ((isset($prj_name[$prj_id])) ? $prj_name[$prj_id] : "");

        $tre_his = new Finance_treasury_history();
        $tre_his->id_treasure = $request->source;
        $tre_his->project = $prj_id;
        $tre_his->date_input = date('Y-m-d');
        $tre_his->description = "Invoice out Payment: ".$inv_detail->no_inv."[".$title_name."]";
        $tre_his->IDR = ($inv_detail->value_d - $inv_detail->discount) + $amountTax;
        $tre_his->PIC = Auth::user()->username;
        $tre_his->company_id = Session::get('company_id');
        $tre_his->save();

        // $coa = Finance_coa_history::where('paper_type', 'INVOUT')
        //     ->where('paper_id', $inv->id_inv)
        //     ->first();
        // if(!empty($coa)){
        //     $coa->credit = $coa->credit - $inv_detail->value_d;
        //     $coa->save();
        // }

        $tre = Finance_treasury::find($tre_his->id_treasure);
        $coa = Finance_coa::all()->pluck('code', 'id');
        if(!empty($inv_detail->tc_id)){
            if(isset($coa[$inv_detail->tc_id])){
                if(!empty($tre->bank_code)){
                    $iCoa = new Finance_coa_history();
                    $iCoa->no_coa = $tre->bank_code;
                    $iCoa->coa_date = $tre_his->date_input;
                    $iCoa->debit = abs($tre_his->IDR);
                    $iCoa->project = $prj_id;
                    $iCoa->id_treasure_history = $tre_his->id;
                    $iCoa->currency = $tre->currency;
                    $iCoa->created_by = Auth::user()->username;
                    $iCoa->description = $tre_his->description;
                    $iCoa->approved_at = date('Y-m-d H:i:s');
                    $iCoa->approved_by = Auth::user()->username;
                    $iCoa->company_id = Session::get('company_id');
                    $iCoa->save();
                }

                $iCoa = new Finance_coa_history();
                $iCoa->no_coa = $coa[$inv_detail->tc_id];
                $iCoa->project = $prj_id;
                $iCoa->coa_date = $tre_his->date_input;
                $iCoa->credit = abs($tre_his->IDR);
                $iCoa->id_treasure_history = $tre_his->id;
                $iCoa->currency = $tre->currency;
                $iCoa->created_by = Auth::user()->username;
                $iCoa->description = $tre_his->description;
                $iCoa->approved_at = date('Y-m-d H:i:s');
                $iCoa->approved_by = Auth::user()->username;
                $iCoa->company_id = Session::get('company_id');
                $iCoa->save();
            }
        }

        $inv_detail->save();
        return redirect()->route('ar.view', $inv_detail->id_inv);
    }

    function revise(Request $request){
        $inv_detail = Finance_invoice_out_detail::find($request->id_detail);
        $inv_detail->req_revise_by = Auth::user()->username;
        $inv_detail->req_revise_date = date('Y-m-d');
        $inv_detail->req_revise_note = $request->notes;
        $inv_detail->save();

        return redirect()->route('ar.view', $inv_detail->id_inv);
//        $new = $inv_detail->replicate();
//        $new->revise = ($inv_detail->revise_number == null) ? 1 : $inv_detail->revise_number + 1;
//        $new->save();
    }

    function add_input(Request $request){
        $desc = $request->description;
        $qty = $request->qty;
        $uom = $request->uom;
        $price = $request->price;
        $discount = $request->discount;
        $amount = 0;
        for ($i = 0; $i < count($qty); $i++){
            $print = new Finance_invoice_out_print();
            $print->id_inv_out_detail = $request->id_detail;
            $print->description = $desc[$i];
            $print->unit_price = $price[$i];
            $print->qty = $qty[$i];
            $print->uom = $uom[$i];
            $print->created_by = Auth::user()->username;
            $print->company_id = Session::get('company_id');
            $amount += $qty[$i] * $price[$i];
            $print->save();
        }

        $inv_det = Finance_invoice_out_detail::find($request->id_detail);
        $inv_det->value_d = $amount;
        $inv_det->discount = $discount;
        $inv_det->updated_by = Auth::user()->username;
        $inv_det->save();

        return redirect()->route('ar.view', $inv_det->id_inv);
    }

    function edit_input(Request $request){
        $print = Finance_invoice_out_print::where('id_inv_out_detail', $request->id_detail);
        if ($print->delete()) {
            $desc = $request->description;
            $qty = $request->qty;
            $uom = $request->uom;
            $price = $request->price;
            $discount = $request->discount;
            $amount = 0;
            for ($i = 0; $i < count($qty); $i++){
                $print = new Finance_invoice_out_print();
                $print->id_inv_out_detail = $request->id_detail;
                $print->description = $desc[$i];
                $print->unit_price = str_replace(",", "", $price[$i]);
                $print->qty = str_replace(",", "", $qty[$i]);
                $print->uom = $uom[$i];
                $print->created_by = Auth::user()->username;
                $print->company_id = Session::get('company_id');
                $amount += str_replace(",", "", $qty[$i]) * str_replace(",", "", $price[$i]);
                $print->save();
            }

            $inv_det = Finance_invoice_out_detail::find($request->id_detail);
            $inv_det->value_d = $amount;
            $inv_det->discount = $discount;
            $inv_det->updated_by = Auth::user()->username;
            $inv_det->save();

        }

        return redirect()->route('ar.view_entry', ['id'=>$request->id_detail, 'act'=>'view']);
    }

    function find($id){
        $detail = Finance_invoice_out_detail::find($id);
        $taxes = Pref_tax_config::all();
        $banks = Finance_treasury::where('company_id', Session::get('company_id'))->get();
        $coa = Finance_coa::all();

        return view('finance.account_receivable.edit_entry', compact('detail', 'taxes', 'banks', 'coa'));
    }

    function update(Request $request){
        $detail = Finance_invoice_out_detail::find($request->id_detail);
        $inv = Finance_invoice_out::find($detail->id_inv);
        $detail->activity = $request->activity;
        $detail->date = $request->date;
        $detail->tc_id = $request->tc_id;

        $arrRomawi  = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $prevNum = explode("/", $detail->no_inv);
        $tag = "INV-".Session::get('company_tag');
        $jsInv = json_decode($inv->title, true);
        $tag_prj = (!empty($inv->tag)) ? $inv->tag : $jsInv['tag'];
        $newNum = $prevNum[0]."/$tag/$tag_prj/".$arrRomawi[date('n', strtotime($request->date))]."/".date('Y', strtotime($request->date));
        // dd($newNum);

        $detail->no_inv = $newNum;
        $detail->payment_account = $request->bank_src;
        if (isset($request->tax) && !empty($request->tax)) {
            $detail->taxes = json_encode($request->tax);
        } else {
            $detail->taxes = null;
        }


        if (isset($request->wapu)){
            $detail->wapu = $request->wapu;
        } else {
            $detail->wapu = null;
        }

        $detail->save();
        return redirect()->back();
    }

    function revise_approve($id){
        $detail = Finance_invoice_out_detail::find($id);

        $detail->revise_approved_at = date("Y-m-d H:i:s");
        $detail->revise_approved_by = Auth::user()->username;

        $detail->save();

        $newDetail = $detail->replicate();

        $arrRomawi  = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $prevNum = explode("/", $newDetail->no_inv);
        $prevNum[4] = $arrRomawi[date('n')];
        $newNum = implode("/", $prevNum);

        $newDetail->no_inv = $newNum;
        $newDetail->req_revise_note = null;
        $newDetail->req_revise_date = null;
        $newDetail->ceo_app_by = null;
        $newDetail->ceo_app_date = null;
        $newDetail->req_revise_by = null;
        $newDetail->fin_approved_by = null;
        $newDetail->fin_approved_note = null;
        $newDetail->fin_approved_date =  null;
        $newDetail->revise_approved_at = null;
        $newDetail->revise_approved_by = null;
        $revNum = (empty($newDetail->revise_number)) ? 0 : $newDetail->revise_number;
        $newDetail->revise_number = $revNum+1;
        if ($newDetail->save() && $detail->delete()) {
            $print = Finance_invoice_out_print::where('id_inv_out_detail', $id)->get();
            foreach ($print as $value) {
                $newPrint = $value->replicate();
                $newPrint->id_inv_out_detail = $newDetail->id;
                $newPrint->save();
            }

            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }
}
