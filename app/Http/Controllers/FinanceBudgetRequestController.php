<?php

namespace App\Http\Controllers;

use App\Models\Asset_type_wo;
use App\Models\Division;
use App\Models\Finance_br;
use App\Models\Finance_br_config;
use App\Models\Finance_br_detail;
use App\Models\Marketing_project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class FinanceBudgetRequestController extends Controller
{
    function index(){
        $mnths = array();
        for ($i=1; $i<=12;$i++){
            $mnths[$i] = date('F', strtotime(date('Y')."-".$i));
        }

        $division = Division::all();

        return view('finance.br.index', [
            'mnths' => $mnths,
            'divisions' => $division
        ]);
    }

    function appr($action, $id){
        $br = Finance_br::find($id);
        $br_detail = Finance_br_detail::where('id_br', $id)->get();
        $project = Marketing_project::where('company_id', Session::get('company_id'))->get();
        $prj = array();
        foreach ($project as $item){
            $prj[$item->id] = $item;
        }
        $approve = 0;
        if ($action == "release" && !empty($br->released_approved_at)){
            $approve = 1;
        } elseif ($action == "dir" && !empty($br->dirut_approved_at)){
            $approve = 1;
        } elseif ($action == "budget" && !empty($br->budget_received_at)){
            $approve = 1;
        } elseif ($action == "balance" && !empty($br->balance_approved_at)){
            $approve = 1;
        } elseif ($action == "balance_recv" && !empty($br->balance_received_at)){
            $approve = 1;
        }

        return view('finance.br.appr', [
            'br' => $br,
            'details' => $br_detail,
            'prj' => $prj,
            'action' => $action,
            'approve' => $approve
        ]);
    }

    function approve(Request $request){
        $br = Finance_br::find($request->id_br);
        if ($request->action == "release"){
            $br->released_approved_at = date('Y-m-d H:i:s');
            $br->released_approved_by = Auth::user()->username;
            $br->released_approved_notes = $request->notes;
        } elseif ($request->action == "dir"){
            $br->dirut_approved_at = date('Y-m-d H:i:s');
            $br->dirut_approved_by = Auth::user()->username;
            $br->dirut_approved_notes = $request->notes;
        } elseif ($request->action == "budget"){
            $br->budget_received_at = date('Y-m-d H:i:s');
            $br->budget_received_by = Auth::user()->username;
        } elseif ($request->action == "balance"){
            $br->balance_approved_at = date('Y-m-d H:i:s');
            $br->balance_approved_by = Auth::user()->username;
            $br->balance_approved_notes = $request->notes;
        } elseif ($request->action == "balance_recv"){
            $br->balance_received_at = date('Y-m-d H:i:s');
            $br->balance_received_by = Auth::user()->username;
        }

        $br->save();

        return redirect()->route('finance.br.index');
    }

    function list_item(Request $request){
        if ($request->division == "all") {
            $whereDiv = "1";
        } elseif ($request->division == "waiting"){
            $whereDiv = "released_approved_at is null";
        } elseif ($request->division == "monthly"){
            $whereDiv = "1";
        } else {
            $whereDiv = " division = ".$request->division;
        }

        if (!empty($request->_type)){
            $whereFrom = "input_date like '".date('Y-m', strtotime($request->year."-".$request->month))."%'";
        } else {
            $whereFrom = 1;
        }

        $br = Finance_br::where('company_id', Session::get('company_id'))
            ->whereRaw($whereDiv)
            ->whereRaw($whereFrom);

        $br_detail = Finance_br_detail::all();
        $amount_br = array();
        $balance_br = array();
        foreach ($br_detail as $item){
            if (!empty($item->cashin)){
                $amount_br[$item->id_br][] = $item->cashin;
            }
            if (!empty($item->cashout)){
                $balance_br[$item->id_br][] = $item->cashout;
            }
        }

        $col = array();
        foreach ($br->get() as  $i => $item){
            $row['key'] = $i+1;
            $row['no'] = "<a href='".route('finance.br.input', ['action' => base64_encode('input'), 'id' => $item->id])."'>".$item->no_br."</a>";
            $row['subject'] = $item->subject;

            if (isset($amount_br[$item->id])){
                $row['amount'] = number_format(array_sum($amount_br[$item->id]), 2);
            } else {
                $row['amount'] = number_format(0);
            }

            $row['date'] = date('d F Y', strtotime($item->input_date));
            $row['due_date'] = date('d F Y', strtotime($item->due_date));
            if (empty($item->released_approved_at)){
                $row['release'] = "<a href='".route('finance.br.appr', ['action' => 'release', 'id' => $item->id])."'>waiting</a>";
            } else {
                $row['release'] = "<a href='".route('finance.br.appr', ['action' => 'release', 'id' => $item->id])."'>".date('d-m-Y', strtotime($item->released_approved_at))."</a>";
            }

            if (empty($item->dirut_approved_at)){
                if (empty($item->released_approved_at)){
                    $row['dirut'] = "waiting";
                } else {
                    $row['dirut'] = "<a href='".route('finance.br.appr', ['action' => 'dir', 'id' => $item->id])."'>waiting</a>";
                }
            } else {
                $row['dirut'] = "<a href='".route('finance.br.appr', ['action' => 'dir', 'id' => $item->id])."'>".date('d-m-Y', strtotime($item->dirut_approved_at))."</a>";
            }

            if (empty($item->budget_received_at)){
                if (empty($item->dirut_approved_at)){
                    $row['budget'] = "waiting";
                } else {
                    $row['budget'] = "<a href='".route('finance.br.appr', ['action' => 'budget', 'id' => $item->id])."'>waiting</a>";
                }
            } else {
                $row['budget'] = "<a href='".route('finance.br.appr', ['action' => 'budget', 'id' => $item->id])."'>".date('d-m-Y', strtotime($item->budget_received_at))."</a>";
            }

            if (isset($balance_br[$item->id])){
                $row['balance_amount'] = number_format(array_sum($balance_br[$item->id]), 2);
            } else {
                if (empty($item->budget_received_at)){
                    $row['balance_amount'] = "waiting";
                } else {
                    $row['balance_amount'] = "<a href='".route('finance.br.input', ['action' => base64_encode('balance'), 'id' => $item->id])."'>waiting</a>";
                }
            }

            if (empty($item->balance_approved_at)){
                if (!isset($balance_br[$item->id])){
                    $row['balance'] = "waiting";
                } else {
                    $row['balance'] = "<a href='".route('finance.br.appr', ['action' => 'balance', 'id' => $item->id])."'>waiting</a>";
                }
            } else {
                $row['balance'] = "<a href='".route('finance.br.appr', ['action' => 'balance', 'id' => $item->id])."'>".date('d-m-Y', strtotime($item->balance_approved_at))."</a>";
            }

            if (empty($item->balance_received_at)){
                if (empty($item->balance_approved_at)){
                    $row['balance_recv'] = "waiting";
                } else {
                    $row['balance_recv'] = "<a href='".route('finance.br.appr', ['action' => 'balance_recv', 'id' => $item->id])."'>waiting</a>";
                }
            } else {
                $row['balance_recv'] = "<a href='".route('finance.br.appr', ['action' => 'balance_recv', 'id' => $item->id])."'>".date('d-m-Y', strtotime($item->balance_received_at))."</a>";
            }

            $row['action'] = "<a href='".route('finance.br.delete', $item->id)."' class='btn btn-xs btn-icon btn-danger'><i class='fa fa-trash'></i></a>";


            $col[] = $row;
        }

        $return['data'] = $col;
        $return['sql'] = $br->toSql();
        $return['type'] = $request->_type;

        return json_encode($return);
    }

    function delete($id){
        $br = Finance_br::find($id);
        if ($br->delete()){
            Finance_br_detail::where('id_br', $id)->delete();
        }

        return redirect()->back();
    }

    function post_request(Request $request){
//        dd($request);
        $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");

        $division = Division::find($request->division);
        $div_tag = strtoupper(substr($division->name, 0, 3));
        $iBr = Finance_br::where('input_date', 'like', date('Y-m', strtotime($request->input_date))."%")
            ->where('no_br', 'like', "%".$div_tag."%")
            ->get();
        if (count($iBr) > 0){
            $numb = array();
            foreach ($iBr as $item){
                $num = explode("/", $item->no_br);
                $numb[] = $num[0];
            }
            $max_num = max($numb);

            $br_num = sprintf("%03d", $max_num+1);
        } else {
            $br_num = sprintf("%03d", 1);
        }

        $br = new Finance_br();
        $br->no_br = $br_num."/".Session::get('company_tag')."/BR-".$div_tag."/".$array_bln[date('n', strtotime($request->input_date))]."/".date('Y', strtotime($request->input_date));
        $br->subject = $request->subject;
        $br->input_date = $request->input_date;
        $br->due_date = $request->due_date;
        $br->currency = $request->currency;
        $br->division = $request->division;
        $br->company_id = $request->company_id;
        $br->created_by = Auth::user()->username;
        $br->save();

        $action = 'input';

        return redirect()->route('finance.br.input', ['action' => base64_encode($action), 'id' => $br->id]);
    }

    function input_amount($action, $id){
        $br = Finance_br::find($id);
        $br_detail = Finance_br_detail::where('id_br', $id)->get();
        $division = Division::find($br->division);
        $project = Marketing_project::where('company_id', Session::get('company_id'))->get();
        $prj = array();
        foreach ($project as $item){
            $prj[$item->id] = $item;
        }
        $wo_type = Asset_type_wo::all();
        return view('finance.br.input', [
            'br' => $br,
            'details' => $br_detail,
            'action' => base64_decode($action),
            'division' => $division,
            'projects' => $project,
            'prj' => $prj,
            'wo_type' => $wo_type
        ]);
    }

    function delete_entry($id){
        $br_detail = Finance_br_detail::find($id);
        if ($br_detail->delete()){
            $data['delete'] = 1;
        } else {
            $data['delete'] = 0;
        }

        return json_encode($data);
    }

    function post_entry(Request $request){
        $br_detail = new Finance_br_detail();
        $br_detail->description = $request->description;
        $br_detail->id_br = $request->id_br;
        $br_detail->remarks = $request->remarks;
        $br_detail->project = $request->project;
        $br_detail->company_id = $request->company_id;
        $br_detail->created_by = Auth::user()->username;
        $amount = str_replace(",", "", $request->amount);
        if ($request->type == "cashin"){
            $br_detail->cashin = $amount;
        } else {
            $br_detail->cashout = $amount;
            $br_detail->category = $request->category;
        }

        $br_detail->save();

        return redirect()->back();
    }

    function check($id){
        $conf = Finance_br_config::where('id_division', $id)->first();
        if (!empty($conf)){
            $data['error'] = 0;
            $data['data'] = $conf;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }
}
