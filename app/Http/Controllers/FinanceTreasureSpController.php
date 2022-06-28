<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Finance_treasury;
use App\Models\Finance_treasure_sp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Finance_treasury_history;

class FinanceTreasureSpController extends Controller
{
    function index($id, Request $request){
        // $sp = Finance
        $treasury = Finance_treasury::find($id);
        // 001/SP/PSI/{id}/year

        $history = Finance_treasury_history::where('id_treasure', $id)
            ->whereNotNull('sp_date')
            ->whereRaw('(approval_status = 0 or approval_status is null)')
            ->get();

        $opening = Finance_treasury_history::where('id_treasure', $id)
            ->where('description', 'like', '%opening balance%')
            ->first();
        if (!empty($opening)) {
            $op_balance = $opening->IDR;
        } else {
            $op_balance = 0;
        }
        $amount = array();
        foreach ($history as $key => $value) {
            $amount[$value->sp_date][] = $value->IDR;
        }
        // dd($whereNum);

        $sp = Finance_treasure_sp::selectRaw("*, RIGHT(num, 2) as year_num, CAST(SUBSTRING(num, 1, LOCATE('/', num) - 1) as UNSIGNED) AS i_num")
            ->where('bank', $id)
            ->where('company_id', Session::get('company_id'))
            // ->where('date1', 'like', date("Y")."%")
            ->orderBy('year_num', 'desc')
            ->orderBy('i_num', 'desc')
            ->get();
        if (count($sp) == 0) {
            $spNum = sprintf("%03d", 1)."/SP/".Session::get('company_tag')."/$id/".date('y');
        } else {
            $spLast = Finance_treasure_sp::selectRaw("*,RIGHT(num, 2) as year_num,  CAST(SUBSTRING(num, 1, LOCATE('/', num) - 1) as UNSIGNED) AS i_num")
                ->where('bank', $id)
                ->where('company_id', Session::get('company_id'))
                // ->where('date1', 'like', date("Y")."%")
                ->orderBy('year_num', 'desc')
                ->orderBy('i_num', 'desc')
                ->first();
            $saldo = $spLast->saldo;
            if (isset($amount[$spLast->id])) {
                $op_balance = $saldo + array_sum($amount[$spLast->id]);
            } else {
                $op_balance = $saldo;
            }
            // $op_balance = $saldo;

            $num = explode("/", $spLast->num);
            $spNum = sprintf("%03d", intval($num[0]) + 1)."/SP/".Session::get('company_tag')."/$id/".date('y');
        }

        if($request->ajax()){
            $op_balance = 0;
            $spLast = Finance_treasure_sp::selectRaw("*,RIGHT(num, 2) as year_num,  CAST(SUBSTRING(num, 1, LOCATE('/', num) - 1) as UNSIGNED) AS i_num")
                ->where('bank', $id)
                ->where('company_id', Session::get('company_id'))
                ->where('date1', 'like', date("Y", strtotime($request->d))."%")
                ->orderBy('year_num', 'desc')
                ->orderBy('i_num', 'desc')
                ->first();
            $saldo = 0;
            if(!empty($spLast)){
                $saldo = $spLast->saldo;
                if (isset($amount[$spLast->id])) {
                    $op_balance = $saldo + array_sum($amount[$spLast->id]);
                } else {
                    $op_balance = $saldo;
                }
            } else {
                $opening = Finance_treasury_history::where('id_treasure', $id)
                    ->where('description', 'like', '%opening balance%')
                    ->where('date_input', 'like', date("Y", strtotime($request->d))."%")
                    ->first();
                if (!empty($opening)) {
                    $op_balance = $opening->IDR;
                }
            }




            return json_encode($op_balance);
        }
        return view('finance.treasure_sp.index', [
            "treasury" => $treasury,
            "sp" => $sp,
            "spNum" => $spNum,
            "op_balance" => $op_balance
        ]);
    }

    function list($id){
        $sp = Finance_treasure_sp::selectRaw("*, RIGHT(num, 2) as year_num,  CAST(SUBSTRING(num, 1, LOCATE('/', num) - 1) as UNSIGNED) AS i_num")
            ->where('bank', $id)
            ->where('company_id', Session::get('company_id'))
            ->whereNotNull('num')
            ->orderBy('year_num', 'desc')
            ->orderBy('i_num', 'desc')
            ->get();
        $data = array();
        foreach ($sp as $key => $value) {
            $row = array();
            $row['i'] = $key + 1;
            $sp_num = explode("/", $value->num);
            if (intval($sp_num[0]) % 2 == 0) {
                $bg = "warning";
            } else {
                $bg = "primary";
            }
            $row['num'] = "<a href='".route('treasure.sp.view', $value->id)."' class='btn btn-sm btn-$bg'>$value->num</a>";
            $row['from'] = date('d F Y', strtotime($value->date1));
            $row['to'] = date('d F Y', strtotime($value->date2));
            $btn = "";
            if (empty($value->approved_by)) {
                $btn .= "<a href='".route('treasure.sp.sp_input', $value->id)."' class='btn btn-xs btn-icon btn-primary'><i class='fa fa-edit'></i></a>";
            } else {
                $btn .= "<span class='label label-inline label-primary'>Approved</span>";
            }
            $row['action'] = $btn;
            $data[] = $row;
        }

        $result = array(
            "data" => $data
        );

        return json_encode($result);
    }

    function view($id){
        $sp = Finance_treasure_sp::find($id);

        $tre = Finance_treasury::find($sp->bank);

        $history = Finance_treasury_history::where('sp_date', $id)
            ->whereRaw('(approval_status = 0 or approval_status is null)')
            ->get();

        return view('finance.treasure_sp.view', [
            "sp" => $sp,
            "history" => $history,
            'treasury' => $tre
        ]);
    }

    function add(Request $request){
        // dd($request);
        $fSp = Finance_treasure_sp::where('num', $request->sp_num)->first();
        if (empty($fSp)) {
            $sp = new Finance_treasure_sp();
            $sp->num = $request->sp_num;
            $sp->year = date('Y', strtotime($request->date_from));
            $sp->bank = $request->id_bank;
            $sp->date1 = $request->date_from;
            $sp->date2 = $request->date_to;
            $sp->saldo = str_replace(",", "", $request->balance);
            $sp->created_by = Auth::user()->username;
            $sp->company_id = Session::get('company_id');
            $sp->save();

            return redirect()->route('treasure.sp.sp_input', $sp->id);
        }
    }

    function sp_input($id){
        $sp = Finance_treasure_sp::find($id);

        return view('finance.treasure_sp.input', compact('sp'));
    }

    function add_input(Request $request){
        $sp = $request->sp;
        if (count($sp) > 0) {
            Finance_treasury_history::where('sp_date', $request->sp_id)
                ->where('company_id', Session::get('company_id'))
                ->where('id_treasure', $request->bank)
                ->update([
                    "sp_date" => null
                ]);
            Finance_treasury_history::whereIn('id', $sp)
                ->update([
                    "sp_date" => $request->sp_id
                ]);
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function approve(Request $request){
        $sp = Finance_treasure_sp::find($request->sp);
        $sp->approved_date = date('Y-m-d H:i:s');
        $sp->approved_by = Auth::user()->username;
        if ($sp->save()) {
            Finance_treasury_history::where('sp_date', $request->sp)
                ->where('id_treasure', $request->bank)
                ->where('company_id', Session::get('company_id'))
                ->update([
                    "sp_app" => 1
                ]);
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }
        return json_encode($data);
    }

    function historyjs(Request $request){
        $tre = Finance_treasury::find($request->hist);

        $whereDate = "date_input like '".date('Y')."%'";

        if (!empty($request->from)){
            $whereDate = "date_input >= '$request->from'";
            if (!empty($request->to)){
                $whereDate .= " and date_input <= '$request->to'";
            }
        }

        $whereFilter = "1";
        if (isset($request->filter)){
            $whereFilter = "(";
            foreach ($request->filter as $i => $filter){
                $whereFilter .= "description like '%$filter%'";
                if ($i != count($request->filter) - 1){
                    $whereFilter .= " or ";
                }
            }
            $whereFilter .= ")";
        }

        $his = Finance_treasury_history::where('id_treasure', $request->hist)
            ->orderBy('date_input', 'desc')
            ->orderBy('id', 'DESC');

        $balance = 0;
        foreach ($his->get() as $value){
            $balance += $value->IDR;
        }
        $data = array();

        $sp = Finance_treasure_sp::where('company_id', Session::get('company_id'))->get();
        $spData = array();
        foreach ($sp as $item){
            $spData[$item->id] = $item;
        }
        $iNum = 1;
        foreach ($his->get() as $i => $item){
            if (empty($item->sp_date) || $item->sp_date == $request->sp_id) {
                $row = array();
                $row['id'] = $item->id;
                $row['i'] = $iNum++;
                $row['date'] = "<button type='button' onclick='change_date(".$item->id.", \"$item->date_input\", \"$item->bank_adm\")' class='btn btn-sm btn-primary'>".date('d F Y', strtotime($item->date_input))."</button>";
                $row['activity'] = $item->description;
                if ($item->IDR > 0){
                    $credit = $item->IDR;
                    $debit = 0;
                } else {
                    $credit = 0;
                    $debit = str_replace("-", "", $item->IDR);
                }
                $row['credit'] = "<label class='text-success'>".number_format($credit, 2)."</label>";
                $row['debit'] = "<label class='text-danger'>".number_format($debit, 2)."</label>";
                $balance = $balance - $item->IDR;
                $sp = "";
                if (isset($spData[$item->sp_date])){
                    $checked = 1;
                    $sp .= '<label class="checkbox checkbox-outline-2x checkbox-outline checkbox-success mx-auto justify-content-center check-sp hidden">
                        <input type="checkbox" class="sp-check" name="sp[]" onchange="addToSP(this)" checked value="'.$item->id.'"/>
                        <span></span>
                    </label>';
                } else {
                    $checked = 0;
                    $sp .= '<label class="checkbox checkbox-outline-2x checkbox-outline checkbox-success mx-auto justify-content-center check-sp hidden">
                        <input type="checkbox" class="sp-check" name="sp[]" onchange="addToSP(this)" value="'.$item->id.'"/>
                        <span></span>
                    </label>';
                }
                $row['checked'] = $checked;
                $row['sp'] = $sp;
                $data[] = $row;
            }

        }

        $result = array(
            'data' => $data,
        );

        return json_encode($result);
    }
}
