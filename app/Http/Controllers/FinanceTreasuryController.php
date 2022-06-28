<?php

namespace App\Http\Controllers;

use App\Helpers\FileManagement;
use App\Models\Asset_po;
use App\Models\Asset_wo;
use App\Models\Finance_coa;
use App\Models\Finance_coa_history;
use App\Models\Finance_coa_source;
use App\Models\Finance_invoice_in_pay;
use App\Models\Finance_invoice_out;
use App\Models\Finance_invoice_out_detail;
use App\Models\Finance_treasure_sp;
use App\Models\Finance_treasury;
use App\Models\Finance_treasury_history;
use App\Models\Finance_treasury_insert;
use App\Models\General_reimburse;
use App\Models\General_travel_order;
use App\Models\Marketing_project;
use App\Models\Procurement_vendor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;

class FinanceTreasuryController extends Controller
{
    function index(){
        $treasure = Finance_treasury::where('company_id', Session::get('company_id'))
            ->where('type', 'bank')
            ->orderBy('source')
            ->get();
        $his = Finance_treasury_history::whereRaw("(approval_status = 0 or approval_status is null)")
            ->where('date_input', 'like', date("Y")."_%")
            ->get();
        $tre_his = Finance_treasury_insert::where('company_id', Session::get('company_id'))
            ->where('approved_at', null)
            ->get();

        $count = [];
        foreach ($tre_his as $tre_hi) {
            $count[$tre_hi->id_treasure][] = $tre_hi->id;
        }

        $cashIn = array();
        $cashOut = array();
        $cashSum = array();
        $project = Marketing_project::where('company_id', Session::get('company_id'))->get();
        foreach ($his as $value) {
            if ($value->IDR > 0) {
                $cashIn[$value->id_treasure][] = $value->IDR;
            } elseif ($value->IDR < 0) {
                $cashOut[$value->id_treasure][] = $value->IDR;
            }

            $cashSum[$value->id_treasure][] = $value->IDR;
        }

        return view('finance.treasury.index', [
            'treasuries' => $treasure,
            'cashIn' => $cashIn,
            'cashOut' => $cashOut,
            'cashSum' => $cashSum,
            'projects' => $project,
            'tre_his' => $count,
        ]);
    }

    function add(Request $request){

        $treasure = new Finance_treasury();

        $treasure->source = $request->bank_name;
        $treasure->type   = "bank";
        $treasure->branch = $request->branch_name;
        $treasure->account_name = $request->account_name;
        $treasure->account_number = $request->account_number;
        $treasure->currency = $request->currency;
        if (isset($request->coa)){
            $coa = explode(" ", $request->coa);
            $coa_code = str_replace(str_split('[]'), "", $coa[0]);
            $treasure->bank_code = $coa_code;
        }
        $treasure->company_id = Session::get('company_id');
        $treasure->created_by = Auth::user()->username;

        $treasure->save();
        return redirect()->route('treasury.index');
    }

    function del(Request $request){
        $id = explode("-", base64_decode($request->val));

        $tre_his = Finance_treasury::find(end($id));

        if ($tre_his->delete()) {
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function deposit(Request $request) {
        $date = date('Y-m-d', strtotime($request->date_input));

        $tre_ins = new Finance_treasury_insert();
        $tre_ins->id_treasure = $request->source;
        $tre_ins->date_insert  = $date;
        $tre_ins->bank_adm    = $request->bank_adm;
        $tre_ins->description = $request->description;
        $tre_ins->project     = $request->project;
        $tre_ins->IDR         = str_replace(",", "", $request->amount);
        $tre_ins->project     = (isset($request->project)) ? $request->project : '';
        $tre_ins->created_by  = Auth::user()->username;
        $tre_ins->company_id  = Session::get('company_id');
        $tre_ins->save();

        return redirect()->route('treasury.index');
    }

    function view_treasure($code) {
        $id = (explode("-", base64_decode($code)));
        $tre_his = Finance_treasury_insert::where('id_treasure', end($id))
            ->where('approved_at', null)
            ->get();
        $tre = Finance_treasury::where('id', end($id))->first();

        return view('finance.treasury.view', [
            'treasure' => $tre,
            'tre_his' => $tre_his
        ]);
    }

    function approve(Request $request){
        $id = explode("-", base64_decode($request->val));

        $tre_in = Finance_treasury_insert::find(end($id));
        $tre_in->approved_at = date("Y-m-d H:i:s");
        $tre_in->approved_by = Auth::user()->username;
        $tre_in->updated_by = Auth::user()->username;

        // insert to history
        $iTre = Finance_treasury_insert::where('id', end($id))->first();
        $tre_his = new Finance_treasury_history();
        $tre_his->id_treasure = $iTre->id_treasure;
        $tre_his->date_input  = $iTre->date_insert;
        $tre_his->project = $iTre->project;
        $tre_his->description = "[".$iTre->project."]".strip_tags($iTre->description);
        $tre_his->IDR         = $iTre->IDR;
        $tre_his->approval_status = 0;
        $tre_his->created_by  = Auth::user()->username;
        $tre_his->company_id  = Session::get('company_id');
        $tre_his->save();

        $his = Finance_treasury_insert::where('id', end($id))->first();

        $tre = Finance_treasury::find($his->id_treasure);
        $tre->IDR = $tre->IDR + $his->IDR;

        if ($tre_in->save() && $tre->save() && $tre_his->save()) {
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function reject(Request $request){
        $id = explode("-", base64_decode($request->val));

        $tre_his = Finance_treasury_insert::find(end($id));

        if ($tre_his->delete()) {
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function find(Request $request){
        $id = explode("-", base64_decode($request->val));
        $tre = Finance_treasury::where('id', end($id))->first();

        return json_encode($tre);
    }

    function edit(Request $request){

        $tre = Finance_treasury::find($request->id_tre);
        $tre->source = $request->bank_name;
        $tre->branch = $request->branch_name;
        $tre->account_name = $request->account_name;
        $tre->account_number = $request->account_number;
        if (isset($request->coa)){
            $coa = explode(" ", $request->coa);
            $coa_code = str_replace(str_split('[]'), "", $coa[0]);
            $tre->bank_code = $coa_code;
        }
        $tre->currency = $request->currency;
        $tre->save();

        return redirect()->route('treasury.index');
    }

    function history($type, $x, Request $request) {
        $id = (explode("-", base64_decode($x)));

        $startyear = date('Y', strtotime('-10 years'));
        for ($i = 0; $i < 20; $i++){
            $years[$i] = $startyear;
            $startyear++;
        }

        $tre = Finance_treasury::where('id', end($id))->first();
        $tre_his = Finance_treasury_history::where('id_treasure', end($id))
            ->where('date_input', 'like', date('Y')."%")
            ->orderBy('date_input', 'desc')
            ->orderBy('id', 'DESC')
            ->get();
        $balance = 0;
        foreach ($tre_his as $value){
            $balance += $value->IDR;
        }

        $sp = Finance_treasure_sp::where('company_id', Session::get('company_id'))->get();
        $spData = array();
        foreach ($sp as $item){
            $spData[$item->id] = $item;
        }

        $cashIn = array();
        $cashOut = array();
        $cashSum = array();


        foreach ($tre_his as $value) {
            if ($value->IDR > 0) {
                $cashIn[$value->id_treasure][] = $value->IDR;
            } elseif ($value->IDR < 0) {
                $cashOut[$value->id_treasure][] = $value->IDR;
            }

            $cashSum[$value->id_treasure][] = $value->IDR;
        }

        $year = date("Y");
        if(isset($request->year)){
            $year = $request->year;
        }

        $vendor = Procurement_vendor::where('company_id', Session::get('company_id'))->get();

        $user = User::find($id)->first();

        return view('finance.treasury.history', [
            'treasury' => $tre,
            'balance' => $balance,
            'cashIn' => $cashIn,
            'cashOut' => $cashOut,
            'cashSum' => $cashSum,
            'y' => $years,
            'vendors' => $vendor,
            'sp' => $spData,
            'year' => $year,
            'type' => $type,
            'user' => $user
        ]);
    }

    function change_date(Request $request){
        $his = Finance_treasury_history::find($request->id_his);
        $his->date_input = $request->new_date;
        $his->bank_adm   = $request->bank_adm;
        $his->save();
        return redirect()->back();
    }

    function historyjs(Request $request){
        // dd($request->hist);
        // $tre = Finance_treasury::find($request->hist);

        $whereDate = "date_input like '".$request->year."%'";

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

        $type = ($request->type != "energy") ? "sell" : "energy";

        $history = Finance_treasury_history::where('id_treasure',$request->hist)
            ->where('date_input', 'like', $request->year."-%")
            ->whereRaw('(approval_status = 0 or approval_status is null)')
            ->where("pic",$type)
            ->orderBy('date_input', 'desc')
            ->orderBy('id', 'DESC')
            ->get();

        $balance = 0;
        foreach ($history as $value){
            $balance += $value->IDR;
        }
        $data = array();

        $sp = Finance_treasure_sp::where('company_id', Session::get('company_id'))
            ->where('bank', $request->hist)
            ->get();
        $spData = array();
        foreach ($sp as $item){
            $spData[$item->id] = $item;
        }

        $iNum = 1;

        foreach ($history as $i => $item){
            $row = array();
                $row['i'] = $iNum++;
                // $row['date'] = "<button type='button' onclick='change_date(".$item->id.", \"$item->date_input\", \"$item->bank_adm\")' class='btn btn-sm btn-primary'>".date('d F Y', strtotime($item->date_input))."</button>";
                $row['date'] = "<button type='button' class='btn btn-sm btn-primary'>".date('d F Y', strtotime($item->date_input))."</button>";
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
                $row['balance'] = "<label class='font-weight-bold'>".number_format($balance, 2)."</label>";
                $balance = $balance - $item->IDR;
                $sp = "";
                if (isset($spData[$item->sp_date])){
                    $num = explode("/", $spData[$item->sp_date]->num);
                    if (intval($num[0]) % 2 == 0){
                        $bg = "label-primary";
                    } else {
                        $bg = "label-warning";
                    }
                    $sp .= "<a href='".route('treasure.sp.view', $spData[$item->sp_date]->id)."' class='label label-inline $bg'>".$spData[$item->sp_date]->num."</a>";
                }

                if ($item->sp_app != 1){
                    $sp .= '<label class="checkbox checkbox-outline-2x checkbox-outline checkbox-success mx-auto justify-content-center check-sp hidden">
                                            <input type="checkbox" name="sp[]" onchange="addToSP(this)" value="'.$item->id.'" "'.$item->date_input.'}}"/>
                                            <span></span>
                                        </label>';
                }

                $row['sp'] = $sp;
                $data[] = $row;
        }

        $result = array(
            'data' => $data,
        );

        if ($request->_action == "ajax"){
            return json_encode($result);
        }

        return view('finance.treasury.excel', compact('data'));
    }

    function coa($id){
        $startyear = date('Y', strtotime('-10 years'));
        $years = [];
        for ($i = 0; $i < 20; $i++){
            $years[$i] = $startyear;
            $startyear++;
        }

        $tre = Finance_treasury::where('id', $id)->first();
        $tre_his = Finance_treasury_history::where('id_treasure', $tre->id)
            ->where('USD', 0)
            ->orderBy('date_input', 'desc')
            ->orderBy('id', 'DESC')
            ->get();
        $balance = 0;
        foreach ($tre_his as $value){
            $balance += $value->IDR;
        }

        $cashIn = array();
        $cashOut = array();
        $cashSum = array();

        $coa = Finance_coa_history::all();
        $coa_his = [];
        foreach ($coa as $item){
            $coa_his[$item->id_treasure_history]['coa'] = $item->id;
            $coa_his[$item->id_treasure_history]['file_hash'] = $item->file_hash;
        }

        foreach ($tre_his as $value) {
            if ($value->IDR > 0) {
                $cashIn[$value->id_treasure][] = $value->IDR;
            } elseif ($value->IDR < 0) {
                $cashOut[$value->id_treasure][] = $value->IDR;
            }

            $cashSum[$value->id_treasure][] = $value->IDR;
        }

//        $vendor = Procurement_vendor::where('company_id', Session::get('company_id'))->get();

        return view('finance.treasury.coa', [
            'treasury' => $tre,
            'tre_his' => $tre_his,
            'balance' => $balance,
            'cashIn' => $cashIn,
            'cashOut' => $cashOut,
            'cashSum' => $cashSum,
            'y' => $years,
            'coa_his' => $coa_his
        ]);
    }

    function setcoa(Request $request){
        $his = Finance_treasury_history::find($request->id_his);
        $debit = $request->debit;
        $de_amount = $request->de_amount;
        $credit = $request->credit;
        $cre_amount = $request->cre_amount;
        $upload = false;
        if (!empty($request->file('file_upload'))){
            $file = $request->file('file_upload');
            $filename = explode(".", $file->getClientOriginalName());
            array_pop($filename);
            $filename = str_replace(" ", "_", implode("_", $filename));

            $newFile = $filename."-".date('Y_m_d_H_i_s')."(".$request->id_leads.").".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);
            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\lead");
        }

        $tre = Finance_treasury::find($his->id_treasure);

        foreach ($debit as $key => $value){
            if ($value != null){
                $coa = explode(" ", $value);
                $coa_code = str_replace(str_split('[]'), "", $coa[0]);
                $iCoa = new Finance_coa_history();
                $iCoa->no_coa = $coa_code;
                $iCoa->coa_date = $his->date_input;
                $iCoa->project = $his->project;
                $iCoa->debit = $de_amount[$key];
                $iCoa->currency = $tre->currency;
                $iCoa->id_treasure_history = $request->id_his;
                $iCoa->created_by = Auth::user()->username;
                $iCoa->description = $his->description;
                if ($upload){
                    $iCoa->file_hash = $hashFile;
                }
                $iCoa->approved_at = date('Y-m-d H:i:s');
                $iCoa->approved_by = Auth::user()->username;
                $iCoa->company_id = Session::get('company_id');
                $iCoa->save();
            }
        }

        foreach ($credit as $key => $value){
            if ($value != null){
                $coa = explode(" ", $value);
                $coa_code = str_replace(str_split('[]'), "", $coa[0]);
                $iCoa = new Finance_coa_history();
                $iCoa->no_coa = $coa_code;
                $iCoa->coa_date = $his->date_input;
                $iCoa->project = $his->project;
                $iCoa->credit = $cre_amount[$key];
                $iCoa->currency = $tre->currency;
                $iCoa->id_treasure_history = $request->id_his;
                $iCoa->created_by = Auth::user()->username;
                $iCoa->description = $his->description;
                if ($upload){
                    $iCoa->file_hash = $hashFile;
                }
                $iCoa->approved_at = date('Y-m-d H:i:s');
                $iCoa->approved_by = Auth::user()->username;
                $iCoa->company_id = Session::get('company_id');
                $iCoa->save();
            }
        }

        return redirect()->back();
    }

    function editcoa(Request $request){
        $his = Finance_treasury_history::find($request->id_his);
        $debit = $request->debit;
        $de_amount = $request->de_amount;
        $id_coa_debit = $request->id_coa_debit;
        $credit = $request->credit;
        $cre_amount = $request->cre_amount;
        $id_coa_credit = $request->id_coa_credit;
        $upload = false;
        if (!empty($request->file('file_upload'))){
            $file = $request->file('file_upload');
            $filename = explode(".", $file->getClientOriginalName());
            array_pop($filename);
            $filename = str_replace(" ", "_", implode("_", $filename));

            $newFile = $filename."-".date('Y_m_d_H_i_s')."(".$request->id_leads.").".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);
            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\lead");
        }
        if ($request->id_del != null){
            $id_del = json_decode($request->id_del);

            foreach ($id_del as $value){
                Finance_coa_history::find($value)->delete();
            }
        }

        foreach ($debit as $key => $value){
            if ($value != null){
                $coa = explode(" ", $value);
                $coa_code = str_replace(str_split('[]'), "", $coa[0]);
                if ($id_coa_debit[$key] != null){
                    $iCoa = Finance_coa_history::find($id_coa_debit[$key]);
                    $iCoa->no_coa = $coa_code;
                    $iCoa->coa_date = $his->date_input;
                    if ($upload){
                        $iCoa->file_hash = $hashFile;
                    }
                    $iCoa->description = $his->description;
                    $iCoa->project = $his->project;
                    $iCoa->debit = $de_amount[$key];
                    $iCoa->updated_by = Auth::user()->username;
                    $iCoa->save();
                } else {
                    $iCoa = new Finance_coa_history();
                    $iCoa->no_coa = $coa_code;
                    $iCoa->coa_date = $his->date_input;
                    $iCoa->project = $his->project;
                    $iCoa->debit = $de_amount[$key];
                    $iCoa->id_treasure_history = $request->id_his;
                    $iCoa->created_by = Auth::user()->username;
                    $iCoa->description = $his->description;
                    if ($upload){
                        $iCoa->file_hash = $hashFile;
                    }
                    $iCoa->approved_at = date('Y-m-d H:i:s');
                    $iCoa->approved_by = Auth::user()->username;
                    $iCoa->company_id = Session::get('company_id');
                    $iCoa->save();
                }
            } else {
                if($id_coa_debit[$key] != null){
                    Finance_coa_history::find($id_coa_debit[$key])->delete();
                }
            }
        }

        foreach ($credit as $key => $value){
            if ($value != null){
                $coa = explode(" ", $value);
                $coa_code = str_replace(str_split('[]'), "", $coa[0]);
                if ($id_coa_credit[$key] != null){
                    $iCoa = Finance_coa_history::find($id_coa_credit[$key]);
                    $iCoa->no_coa = $coa_code;
                    $iCoa->coa_date = $his->date_input;
                    $iCoa->credit = $cre_amount[$key];
                    $iCoa->description = $his->description;
                    if ($upload){
                        $iCoa->file_hash = $hashFile;
                    }
                    $iCoa->project = $his->project;
                    $iCoa->updated_by = Auth::user()->username;
                    $iCoa->save();
                } else {
                    $iCoa = new Finance_coa_history();
                    $iCoa->no_coa = $coa_code;
                    $iCoa->coa_date = $his->date_input;
                    $iCoa->credit = $cre_amount[$key];
                    $iCoa->project = $his->project;
                    $iCoa->description = $his->description;
                    if ($upload){
                        $iCoa->file_hash = $hashFile;
                    }
                    $iCoa->id_treasure_history = $request->id_his;
                    $iCoa->created_by = Auth::user()->username;
                    $iCoa->company_id = Session::get('company_id');
                    $iCoa->save();
                }
            } else {
                if($id_coa_credit[$key] != null){
                    Finance_coa_history::find($id_coa_credit[$key])->delete();
                }
            }
        }

        $sum_debit = 0;
        $sum_credit = 0;
        $c_his = Finance_coa_history::where('id_treasure_history', $request->id_his)->get();
        foreach($c_his as $item){
            if($item->credit > 0){
                $sum_credit += abs($item->credit);
            }

            if($item->debit > 0){
                $sum_debit += abs($item->debit);
            }
        }

        if(($sum_debit - $sum_credit) == 0){
            $his->tc_balance = 1;
            $his->save();
        }

        return redirect()->back();
    }

    function viewcoa($id){
        $tre_his = Finance_treasury_history::find($id);

        $coa_adm = [];

        if(!empty($tre_his->bank_adm)){
            $src = [];
            switch ($tre_his->bank_adm) {
                case 'pajak':
                    $src = Finance_coa_source::where('name', 'tbt')->first();
                    break;
                case 'bunga':
                    $src = Finance_coa_source::where('name', 'tbi')->first();
                    break;
                case 'adm':
                    $src = Finance_coa_source::where('name', 'tba')->first();
                    break;
            }

            if(!empty($src)){
                $coa_adm = Finance_coa::where('source', 'like', "%\"$src->id\"%")->first();
            }
        }
        $tre = Finance_treasury::find($tre_his->id_treasure);
        $val = [];
        $coa = Finance_coa::select('id','code','name')
            ->whereNull('deleted_at')->get();
        foreach ($coa as $value){
            $val[$value->code] = "[".$value->code."] ".$value->name;
        }

        $coa_his = Finance_coa_history::where('id_treasure_history', $id)->get();
        $coa_data = [];
        $debet = [];
        $credit = [];
        foreach ($coa_his as $item){
            if (!empty($item->debit)){
                $coa_data['id'] = $item->id;
                $coa_data['lock'] = $item->locked;
                $coa_data['code'] = $item->no_coa;
                $coa_data['amount'] = $item->debit;
                $debet[] = $coa_data;
            } elseif (!empty($item->credit)){
                $coa_data['id'] = $item->id;
                $coa_data['lock'] = $item->locked;
                $coa_data['code'] = $item->no_coa;
                $coa_data['amount'] = $item->credit;
                $credit[] = $coa_data;
            }
        }

        $coa = array(
            'debit' => $debet,
            'credit' => $credit
        );

        return view('finance.treasury.view_coa', [
            'tre_his' => $tre_his,
            'treasury' => $tre,
            'coa' => $val,
            'coa_his' => $coa_his,
            'data_coa' => $coa,
            'coa_adm' => $coa_adm
        ]);
    }

    function findsp(Request $request){
        $sp = Finance_treasure_sp::whereRaw("'".$request->date."' BETWEEN date1 AND date2")
//            ->where('date2', '<=', $request->date)
            ->where('bank', $request->treasure)
            ->get();

//        dd($sp->toSql());

        if (count($sp) == 0){
            $data['sp'] = null;
        } else {
            $data['sp'] = $sp;
        }

        return json_encode($data);
    }

    function addsp(Request $request){
        // dd($request);
        $sp = Finance_treasure_sp::where('year', date('Y', strtotime($request->date)))
            ->where('bank', $request->treasure)
            ->where('company_id', Session::get('company_id'))
            ->orderBy('id', 'desc')
            ->first();

        $his = $request->sp;
        $saldo = 0;
        $his_date = $request->spdate;
        sort($his_date);
        if (empty($sp)){
            $num = 1;
        } else {
            $paper = explode("/",$sp->num);
            $num = intval($paper[0]) + 1;
        }

        $newSp = new Finance_treasure_sp();

        $newSp->num = sprintf("%03d", $num)."/".Session::get('company_tag')."/SP/".$request->treasure."/".date('Y', strtotime($request->date));
        $newSp->year = date('Y', strtotime($request->date));
        $newSp->bank = $request->treasure;
        $newSp->date1 = $his_date[0];
        $newSp->date2 = end($his_date);
        $newSp->company_id = Session::get('company_id');
        if ($newSp->save()){
            foreach ($his as $item){
                $tre_his = Finance_treasury_history::find($item);
                $tre_his->sp_date = $newSp->id;
                $tre_his->save();
            }
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function viewsp($id){
        $sp = Finance_treasure_sp::find($id);
        $treasury = Finance_treasury::find($sp->bank);
        $his = Finance_treasury_history::where('sp_date', $id)->get();

        return view('finance.treasury.viewsp', [
            'sp' => $sp,
            'treasury' => $treasury,
            'his' => $his
        ]);
    }

    function apprsp(Request $request){
        $sp = Finance_treasure_sp::find($request->id);

        $sp->approved_by = Auth::user()->username;
        $sp->approved_date = date('Y-m-d');

        if ($sp->save()){
            Finance_treasury_history::where('sp_date', $request->id)
                ->update([
                    'sp_app' => 1
                ]);

            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function printsp($id){
        $sp = Finance_treasure_sp::find($id);
        $treasury = Finance_treasury::find($sp->bank);
        $his = Finance_treasury_history::where('sp_date', $id)->get();
        return view('finance.treasury.printsp', [
            'sp' => $sp,
            'treasury' => $treasury,
            'his' => $his
        ]);
    }

    function hold_amount(Request $request){
        $tre = Finance_treasury::find($request->id_tre);
        $tre->actual_idr = str_replace(",", "", $request->hold_amount);
        $tre->save();

        return redirect()->back();
    }

    function transfer(Request $request){
        // dd($request);
        $from = Finance_treasury::find($request->bank_from);
        $to = Finance_treasury::find($request->bank_to);
        $amount = str_replace(",", "", $request->amount);
        $sell = str_replace(",", "", $request->sell_rate);
        $buy = str_replace(",", "", $request->buy_rate);

        $sumFrom = 0;
        $sumTo = 0;

        if ($from->currency != $to->currency) {
            if ($from->currency == "IDR") {
                $sumTo = intval($amount) / intval($buy);
                $sumFrom = $amount;
            } else {
                $sumTo = intval($amount) * intval($sell);
                $sumFrom = $amount;
            }
        } else {
            $sumTo = $amount;
            $sumFrom = $amount;
        }

        $hisFrom = new Finance_treasury_history();
        $hisFrom->id_treasure = $from->id;
        $hisFrom->date_input  = date('Y-m-d');
        $hisFrom->description = "[OUT] Transfer [".$to->currency."] ".$to->source;
        $hisFrom->IDR         = $sumFrom * -1;
        $hisFrom->pic         = Auth::user()->username;
        $hisFrom->created_by  = Auth::user()->username;
        $hisFrom->company_id  = Session::get('company_id');
        $hisFrom->save();

        $hisTo = new Finance_treasury_history();
        $hisTo->id_treasure = $to->id;
        $hisTo->date_input  = date('Y-m-d');
        $hisTo->description = "[IN] Transfer [".$from->currency."] ".$from->source;
        $hisTo->IDR         = $sumTo;
        $hisTo->pic         = Auth::user()->username;
        $hisTo->created_by  = Auth::user()->username;
        $hisTo->company_id  = Session::get('company_id');
        $hisTo->save();

        return redirect()->back();
    }

    function hello($bulan, $tahun, Request $request){

        $data = [];

        $query = "";

        if($request->t == "invoice_out"){
            $desc = "%Invoice out Payment%";
        } elseif($request->t == "po") {
            $desc = "%PSI/PO%";
        } elseif($request->t == "wo") {
            $desc = "%PSI/WO%";
        } elseif($request->t == "history"){
            $history = Finance_treasury_history::where('company_id', Session::get("company_id"))
                ->where("date_input", 'like', "$tahun-".sprintf("%02d", $bulan)."%")
                ->whereNotNull('project')->get();

                $iNum = 0;
            foreach ($history as $key => $value) {
                $coa_hist = Finance_coa_history::where('id_treasure_history', $value->id)
                    ->get();
                if(count($coa_hist) > 0){
                    $query .= "UPDATE finance_coa_history SET project = $value->project WHERE id_treasure_history = $value->id;<br>";
                    $base64 = base64_encode($value->id);
                    $query .= base64_decode($base64);
                }
            }

            return $query;
        } elseif($request->t == "reimburse"){
            $reimburse = General_reimburse::where('company_id', Session::get('company_id'))->get();

            $history = Finance_treasury_history::where('company_id', Session::get("company_id"))
                ->where("date_input", 'like', "$tahun-".sprintf("%02d", $bulan)."%")
                ->where(function($query) use($reimburse){
                    foreach ($reimburse as $key => $value) {
                        $query->orWhere('description', 'like', '%ID: '.$value->id.'%');
                    }
                });

            foreach ($history->get() as $key => $value) {
                $desc = explode("ID:", $value->description);
                $idRe = preg_replace("/[\s,()-]/", "", $desc[1]);
                // $query .= $idRe."<br>";
                $re = General_reimburse::find($idRe);
                $query .= "UPDATE finance_treasure_history SET project = $re->project WHERE id = $value->id;<br>";
            }
                // ->get();

            // $query = "";
            // foreach ($history as $key => $value) {
            //     $query .= "$value->description<br>";
            // }

            return $query;
        } elseif($request->t == "to"){
            $project = Marketing_project::where("id_client", $request->id)->get()->pluck("id");
            $to = General_travel_order::whereIn("project", $project)->orderBy('doc_num')->get();
            $his = Finance_treasury_history::where(function($query) use ($to){
                foreach($to as $item){
                    $query->orWhere("description", 'like', "%$item->doc_num%");
                }
            })
            ->where("date_input", 'like', "$tahun-".sprintf("%02d", $bulan)."%")
            ->get();

            foreach($his as $item){
                $query .= $item->id.", ";
            }

            return $query;
        }

        $treasure = Finance_treasury::where('company_id', Session::get('company_id'))
            ->get()->pluck('id');

        $history = Finance_treasury_history::whereIn("id_treasure", $treasure)
            ->where("date_input", 'like', "$tahun-".sprintf("%02d", $bulan)."%")
            ->where('description', 'like', $desc);

        foreach ($history->get() as $key => $value) {
            if($request->t == "invoice_out"){
                $exp = explode(":", $value->description);
                // $no_inv = explode("/", end($exp));
                $no_inv = preg_split("/[[|.]+/", end($exp));
                // $query .= $value->description."<br>";
                $num = "";
                if(count($no_inv) > 1){
                    if($no_inv[0] != ""){
                        $num = substr($no_inv[0], 1);

                        $inv_out_detail = Finance_invoice_out_detail::where('no_inv', $num)
                            ->whereNull('req_revise_date')
                            ->first();
                        if(!empty($inv_out_detail)){
                            $inv = Finance_invoice_out::find($inv_out_detail->id_inv);
                            $project = $inv->id_project;
                            $query .= "UPDATE finance_treasure_history SET project = $project WHERE id = $value->id;<br>";
                            // $row['num'] = $num;
                            $data[$value->id] = $query;
                        }
                    }
                }
            } elseif($request->t == "po") {
                $exp = explode("for", $value->description);
                $num = preg_replace("/[\s,()-]/", "", $exp[1]);

                $poNum = substr($num, 0, strpos($num, "["));

                $po = Asset_po::where('po_num', $poNum)->first();
                $query .= "UPDATE finance_treasure_history SET project = $po->project WHERE id = $value->id;<br>";
            } elseif($request->t == "wo") {
                $exp = explode("for", $value->description);
                $num = preg_replace("/[\s,()-]/", "", $exp[1]);

                $poNum = substr($num, 0, strpos($num, "["));

                $po = Asset_wo::where('wo_num', $poNum)->first();
                if(!empty($po)){
                    if(!empty($po->project)){
                        $query .= "UPDATE finance_treasure_history SET project = $po->project WHERE id = $value->id;<br>";
                    }
                }
            }
        }

        echo $query;
    }

    function hiscoa(Request $request){
        $type = null;
        $wo = [];
        $po = [];
        $history = [];
        $hiswo = [];
        $hispo = [];
        $coa = Finance_coa::get()->pluck('name', 'code');

        $hisdesc = Finance_treasury_history::where("company_id", Session::get("company_id"))->get()->pluck('description', 'id');
        $hisdate = Finance_treasury_history::where("company_id", Session::get("company_id"))->get()->pluck('date_input', 'id');

        $tag = Session::get('company_tag');

        if (isset($request->submit)) {
            if($request->submit == 1){
                $type = "1";
                $listwo = (!empty($request->wo)) ? explode(",", $request->wo) : [];
                $listpo = (!empty($request->po)) ? explode(",", $request->po) : [];
                $listhis = (!empty($request->history)) ? explode(",", $request->history) : [];

                foreach ($listwo as $key => $value) {
                    $val = trim($value, " ");
                    $desc = "% ".$val."/$tag/WO/%";
                    $his = Finance_treasury_history::where('description', 'like', $desc)->orderBy("date_input", "desc")->get();
                    $row = [];
                    if(!empty($his)){
                        foreach ($his as $h) {
                            $col = [];
                            $col['desc'] = $h->description;
                            $col['id_his'] = $h->id;
                            $col['project'] = $h->project;
                            $col['amount'] = $h->IDR;
                            $col['date_input'] = $h->date_input;
                            $row[] = $col;
                        }
                    }
                    $wo[$val] = $row;
                }

                foreach ($listpo as $key => $value) {
                    $val = trim($value, " ");
                    $desc = "% ".$val."/$tag/PO/%";
                    $his = Finance_treasury_history::where('description', 'like', $desc)->orderBy("date_input", "desc")->get();
                    $row = [];
                    if(!empty($his)){
                        foreach ($his as $h) {
                            $col = [];
                            $col['desc'] = $h->description;
                            $col['id_his'] = $h->id;
                            $col['project'] = $h->project;
                            $col['amount'] = $h->IDR;
                            $col['date_input'] = $h->date_input;
                            $row[] = $col;
                        }
                    }
                    $po[$val] = $row;
                }

                foreach ($listhis as $key => $value) {
                    $val = trim($value, " ");
                    $hist = Finance_treasury_history::find($val);
                    $row = [];
                    if(!empty($hist)){
                        $col = [];
                        $col['desc'] = $hist->description;
                        $col['id_his'] = $hist->id;
                        $col['project'] = $hist->project;
                        $col['amount'] = $hist->IDR;
                        $col['date_input'] = $hist->date_input;
                        $row[] = $col;
                    }
                    $history[$val] = $row;
                }
            }

            if($request->submit == 2){
                $type = "2";

                $reqwo = $request->wo;
                $reqpo = $request->po;
                $reqhis = $request->history;

                //wo
                if(!empty($reqwo)){
                    foreach ($reqwo as $num => $value) {
                        $row = [];
                        if(!empty($value)){
                            foreach ($value as $id_his => $val) {
                                $save = 0;
                                if(!empty($val['project'])){
                                    $id_his = $val['id_his'];
                                    $coahisExist = Finance_coa_history::where('id_treasure_history', $id_his)->get();
                                    if(count($coahisExist) > 0){
                                        if(!empty($val['debit_tc'])){
                                            Finance_coa_history::where('id_treasure_history', $id_his)
                                                ->whereNotNull('debit')
                                                ->delete();
                                        }

                                        if(!empty($val['credit_tc'])){
                                            Finance_coa_history::where('id_treasure_history', $id_his)
                                                ->whereNotNull('credit')
                                                ->delete();
                                        }
                                    }
                                    //debit
                                    if(!empty($val['debit_tc'])){
                                        $coahis = new Finance_coa_history();
                                        $coahis->id_treasure_history = $id_his;
                                        $coahis->project = $val['project'];
                                        $coahis->description = $hisdesc[$id_his];
                                        $coahis->coa_date = $hisdate[$id_his];
                                        $coahis->no_coa = $val['debit_tc'];
                                        $coahis->debit = str_replace(",", "", $val['debit_amt']);
                                        $coahis->currency = "IDR";
                                        $coahis->company_id = Session::get('company_id');
                                        $coahis->created_by = "system";
                                        $coahis->save();
                                    }

                                    //credit
                                    if(!empty($val['credit_tc'])){
                                        $coahis = new Finance_coa_history();
                                        $coahis->id_treasure_history = $id_his;
                                        $coahis->project = $val['project'];
                                        $coahis->description = $hisdesc[$id_his];
                                        $coahis->coa_date = $hisdate[$id_his];
                                        $coahis->no_coa = $val['credit_tc'];
                                        $coahis->debit = str_replace(",", "", $val['credit_amt']);
                                        $coahis->currency = "IDR";
                                        $coahis->company_id = Session::get('company_id');
                                        $coahis->created_by = "system";
                                        $coahis->save();
                                    }

                                    $save = 1;
                                }
                                $row[] = $save;
                            }
                        }
                        $wo[$num] = $row;
                    }
                }

                if(!empty($reqpo)){
                    foreach ($reqpo as $num => $value) {
                        $row = [];
                        if(!empty($value)){
                            foreach ($value as $id_his => $val) {
                                $save = 0;
                                if(!empty($val['project'])){
                                    $id_his = $val['id_his'];
                                    $coahisExist = Finance_coa_history::where('id_treasure_history', $id_his)->get();
                                    if(count($coahisExist) > 0){
                                        Finance_coa_history::where('id_treasure_history', $id_his)->delete();
                                    }
                                    //debit
                                    if(!empty($val['debit_tc'])){
                                        $coahis = new Finance_coa_history();
                                        $coahis->id_treasure_history = $id_his;
                                        $coahis->project = $val['project'];
                                        $coahis->description = $hisdesc[$id_his];
                                        $coahis->coa_date = $hisdate[$id_his];
                                        $coahis->no_coa = $val['debit_tc'];
                                        $coahis->debit = $val['debit_amt'];
                                        $coahis->currency = "IDR";
                                        $coahis->company_id = Session::get('company_id');
                                        $coahis->created_by = "system";
                                        $coahis->save();
                                    }

                                    //credit
                                    if(!empty($val['credit_tc'])){
                                        $coahis = new Finance_coa_history();
                                        $coahis->id_treasure_history = $id_his;
                                        $coahis->project = $val['project'];
                                        $coahis->description = $hisdesc[$id_his];
                                        $coahis->coa_date = $hisdate[$id_his];
                                        $coahis->no_coa = $val['credit_tc'];
                                        $coahis->debit = $val['credit_amt'];
                                        $coahis->currency = "IDR";
                                        $coahis->company_id = Session::get('company_id');
                                        $coahis->created_by = "system";
                                        $coahis->save();
                                    }

                                    $save = 1;
                                }
                                $row[] = $save;
                            }
                        }
                        $po[$num] = $row;
                    }
                }

                if(!empty($reqhis)){
                    foreach ($reqhis as $num => $value) {
                        $row = [];
                        if(!empty($value)){
                            foreach ($value as $id_his => $val) {
                                $save = 0;
                                $id_his = $val['id_his'];
                                $coahisExist = Finance_coa_history::where('id_treasure_history', $id_his)->get();
                                if(count($coahisExist) > 0){
                                    Finance_coa_history::where('id_treasure_history', $id_his)->delete();
                                }

                                //debit
                                if(!empty($val['debit_tc'])){
                                    $coahis = new Finance_coa_history();
                                    $coahis->id_treasure_history = $id_his;
                                    $coahis->project = $val['project'];
                                    $coahis->description = $hisdesc[$id_his];
                                    $coahis->coa_date = $hisdate[$id_his];
                                    $coahis->no_coa = $val['debit_tc'];
                                    $coahis->debit = str_replace(",", "", $val['debit_amt']);
                                    $coahis->currency = "IDR";
                                    $coahis->company_id = Session::get('company_id');
                                    $coahis->created_by = "system";
                                    $coahis->save();
                                }

                                //credit
                                if(!empty($val['credit_tc'])){
                                    $coahis = new Finance_coa_history();
                                    $coahis->id_treasure_history = $id_his;
                                    $coahis->project = $val['project'];
                                    $coahis->description = $hisdesc[$id_his];
                                    $coahis->coa_date = $hisdate[$id_his];
                                    $coahis->no_coa = $val['credit_tc'];
                                    $coahis->debit = str_replace(",", "", $val['credit_amt']);
                                    $coahis->currency = "IDR";
                                    $coahis->company_id = Session::get('company_id');
                                    $coahis->created_by = "system";
                                    $coahis->save();
                                }

                                $save = 1;
                                $row[] = $save;
                            }
                        }
                        $history[$num] = $row;
                    }
                }
            }
        }
        return view('finance/treasury/move', compact('type', 'po', 'wo', 'coa', 'hiswo', 'hispo', 'history'));
    }
}
