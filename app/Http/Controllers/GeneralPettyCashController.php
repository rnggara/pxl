<?php

namespace App\Http\Controllers;

use Session;
use App\Models\User;
use App\Models\Finance_coa;
use Illuminate\Http\Request;
use App\Models\Asset_type_wo;
use App\Helpers\ActivityConfig;
use App\Models\Finance_treasury;
use App\Models\General_cashbond;
use App\Models\Marketing_project;
use App\Models\Asset_new_category;
use App\Models\Finance_coa_source;
use Illuminate\Support\Facades\DB;
use App\Models\Finance_coa_history;
use Illuminate\Support\Facades\Auth;
use App\Models\General_cashbond_detail;
use App\Models\Finance_treasury_history;

class GeneralPettyCashController extends Controller
{
    public function index(){

        $cashbond = General_cashbond::where('company_id', \Session::get('company_id'))->get();
        $dataDetail = General_cashbond_detail::all();
        $detail = array();
        foreach ($dataDetail as $item){
            $detail[$item->id_cashbond]['cashin'][] = $item->cashin;
            $detail[$item->id_cashbond]['cashout'][] = $item->cashout;
        }

        $outstanding = 0;
        $outstandingBnk = 0;

        $dataCashbond = array();
        foreach ($cashbond as $item){
            if ($item->user == "open"){
                if ($item->is_private != 0){
                    if ($item->created_by == Auth::user()->username){
                        $dataCashbond[$item->id] = $item;
                    } elseif (Auth::user()->id_rms_roles_divisions == 1) {
                        $dataCashbond[$item->id] = $item;
                    }
                } else {
                    $dataCashbond[$item->id] = $item;
                }
            } else {
                if ($item->created_by == Auth::user()->username){
                    $dataCashbond[$item->id] = $item;
                } elseif (Auth::user()->id_rms_roles_divisions == 1) {
                    $dataCashbond[$item->id] = $item;
                } elseif ($item->user == Auth::id()) {
                    $dataCashbond[$item->id] = $item;
                }
            }
        }

        foreach ($detail as $key => $item){
            if (isset($item['cashin']) && isset($dataCashbond[$key])){
                foreach ($item['cashin'] as $cashin){
                    $outstanding += $cashin;
                }
            }

            if (isset($item['cashout']) && isset($dataCashbond[$key])){
                foreach ($item['cashout'] as $cashout){
                    if ($dataCashbond[$key]->dir_approved_at != null){
                        $outstandingBnk += $cashout;
                    }
                }
            }
        }

//        $outstandingBnk = DB::table('general_cashbond')
//            ->join('general_cashbond_detail as detail','detail.id_cashbond','=','general_cashbond.id')
//            ->select('general_cashbond.*','general_cashbond_detail.*')
//            ->whereNotNull('general_cashbond.done')
//            ->where('general_cashbond.company_id',\Session::get('company_id'))
//            ->sum('detail.cashout');
//
//        $outstanding = DB::table('general_cashbond')
//            ->join('general_cashbond_detail as detail','detail.id_cashbond','=','general_cashbond.id')
//            ->select('general_cashbond.*','general_cashbond_detail.*')
//            ->whereNull('general_cashbond.done')
//            ->where('general_cashbond.company_id',\Session::get('company_id'))
//            ->sum('detail.cashin');
//
//        $cashbondlists = DB::table('general_cashbond')
//            ->join('marketing_projects as project','project.id','=','general_cashbond.project')
//            ->select('general_cashbond.*','project.prj_name as prj_name')
//            ->whereNull('general_cashbond.done')
//            ->where('general_cashbond.company_id',\Session::get('company_id'))
//            ->get();
//
//        $cashbondbanks = DB::table('general_cashbond')
//            ->join('marketing_projects as project','project.id','=','general_cashbond.project')
//            ->select('general_cashbond.*','project.prj_name as prj_name')
//            ->whereNotNull('general_cashbond.done')
//            ->where('general_cashbond.company_id',\Session::get('company_id'))
//            ->get();

        $listperson = User::where('company_id',\Session::get('company_id'))
            ->whereNotIn('password',['xxx','out'])
            ->orderBy('username','ASC')
            ->get();

        $projects = Marketing_project::where('company_id',\Session::get('company_id'))
            ->orderBy('prj_name','ASC')
            ->get();
        $category = DB::table('asset_items')
            ->join('new_category as category', 'category.id','=','asset_items.category_id')
            ->select('asset_items.*', 'category.name as categoryName')
            ->where('category.name','like','%Transportation%')
            ->orWhere('category.name','like','%Vehicle%')
            ->orWhere('category.name','like','%car%')
            ->get();
//        $sumcashout =[];
//        $sumcashin = [];
//        foreach ($cashbondlists as $key => $cashbondList1){
//            $sumcashin[$cashbondList1->id][] = DB::table('general_cashbond_detail')->where('id_cashbond', '=', $cashbondList1->id)->sum('cashin');
//            $sumcashout[$cashbondList1->id][] = DB::table('general_cashbond_detail')->where('id_cashbond', '=', $cashbondList1->id)->sum('cashout');
//        }
//        foreach ($cashbondbanks as $key => $cashbondList1){
//            $sumcashin[$cashbondList1->id][] = DB::table('general_cashbond_detail')->where('id_cashbond', '=', $cashbondList1->id)->sum('cashin');
//            $sumcashout[$cashbondList1->id][] = DB::table('general_cashbond_detail')->where('id_cashbond', '=', $cashbondList1->id)->sum('cashout');
//        }

        return view('pettycash.index', [
            'cashbond' => $dataCashbond,
            'details' => $detail,
            'listpersons' => $listperson,
            'projects' => $projects,
            'category' => $category,
            'outstandingBnk' => $outstandingBnk,
            'outstanding' =>$outstanding,
        ]);

//        return view('pettycash.index',[
//            'listpersons' => $listperson,
//            'projects' => $projects,
//            'cashbondlists' => $cashbondlists,
//            'cashbondbanks' =>$cashbondbanks,
//            'cashin' => $sumcashin,
//            'cashout' => $sumcashout,
//            'category' => $category,
//            'outstandingBnk' => $outstandingBnk,
//            'outstanding' =>$outstanding,
//        ]);
    }

    public function addCashbond(Request $request){
        ActivityConfig::store_point('cashbond', 'create');
//        dd($point);
        $cashbond = new General_cashbond();
        $cashbond->subject = $request['subject'];
        $cashbond->input_date = date('Y-m-d');
        $cashbond->currency = $request['currency'];
        $cashbond->division = $request['division'];
        $cashbond->user = $request['for_personel'];
        $cashbond->project = $request['project'];
        $cashbond->vehicle = $request['vehicle'];
        $cashbond->man_fin_cashout_date = date('Y-m-d', strtotime($request['due_date']));
        if (isset($request['is_private'])){
            $cashbond->is_private = $request['is_private'];
        } else {
            $cashbond->is_private = 0;
        }
        $cashbond->created_by = Auth::user()->username;
        $cashbond->company_id = \Session::get('company_id');
        $cashbond->save();

        return redirect()->route('cashbond.index');
    }

    public function getDetail($id){
        $cashbond = General_cashbond::where('general_cashbond.id',$id)
            ->where('company_id',\Session::get('company_id'))
            ->first();
        $detailIn = General_cashbond_detail::where('id_cashbond',$id)
            ->where('cashin','>',0)
            ->orderBy('id', 'DESC')
            ->get();
        $numRowsIn = $detailIn->count();
        $detailOut = General_cashbond_detail::where('id_cashbond',$id)
            ->whereNotNull('tc_id_parent')
            ->whereNotNull('tc_id')
            ->where('cashout','>',0)
            ->orderBy('id', 'DESC')
            ->get();
        $numRowsOut = $detailOut->count();
        $typewo = Asset_type_wo::orderBy('name', 'ASC')->get();
        $category = Asset_new_category::all();
        $project = Marketing_project::find($cashbond->project);
        $isCashbond = [];
        foreach ($detailOut as $value) {
            $isCashbond[$value->category][] = $value;
        }

        $src = Finance_coa_source::where('name', 'cb')->first();
        $tp_parent = Finance_coa::where('source', 'like', '%"'.$src->id.'"%')
            ->get()->pluck('code');
        $tp = Finance_coa::whereIn('parent_id', $tp_parent)->get();

        $coa = Finance_coa::all();
        $dCoa['name'] = $coa->pluck('name', 'id');
        $dCoa['code'] = $coa->pluck('code', 'id');
        $coa_child = [];
        foreach($coa as $item){
            $coa_child[$item->parent_id][] = $item;
        }

        return view('pettycash.detail',[
            'detail' => $cashbond,
            'detailIn' => $detailIn,
            'detailOut' => $detailOut,
            'numRowsIn' => $numRowsIn,
            'numRowsOut' => $numRowsOut,
            'typewo' => $tp,
            'categories' => $category,
            'project' => $project,
            'isCashbond' => $isCashbond,
            'coa' => $coa_child,
            'dCoa' => $dCoa
        ]);
    }

    public function addCashIn(Request $request){
        $arr_str = array("'", "`");
        $deskripsi = str_replace($arr_str, "", $request['deskripsi']);
        if (isset($request['id_edit'])){
            General_cashbond_detail::where('id', $request['id_edit'])
                ->update([
                    'source_string' => $request['source'],
                    'tanggal' => date("Y-m-d", strtotime($request['req_date'])),
                    'no_nota' => $request['subject'],
                    'deskripsi' => $deskripsi,
                    'cashin' => str_replace(",", "", $request['amount']),
                    'updated_by' => Auth::user()->username,
                ]);
        } else {

            $r_date = date("Y-m-d", strtotime($request['req_date']));
            $currency = $request['curr'];
            $id_imburse = $request['id'];
            $cashin = 0; $cashout = 0;
            $bank_name = $request['source'];
            $amount = str_replace(",", "", $request['amount']);

            if($request['cashtype'] == 'cashout'){ $cashout += $amount; } else { $cashin += $amount; }
            if (isset($request['source'])){
                General_cashbond::where('id', $id_imburse)
                    ->update([
                        'input_date' => $r_date,
                    ]);
            }
            $cashIn = new General_cashbond_detail();
            $cashIn->id_cashbond = $id_imburse;
            $cashIn->tanggal = $r_date;
            $cashIn->source_string = $bank_name;
            $cashIn->no_nota = $request['subject'];
            $cashIn->deskripsi = $deskripsi;
            $cashIn->source_int = 0;
            $cashIn->created_by = Auth::user()->username;
            $cashIn->cashin = $cashin;
            $cashIn->cashout = $cashout;
            $cashIn->save();
        }

        return redirect()->route('cashbond.detail',['id' => $request['id']]);
    }

    public function addCashOut(Request $request){
        $arr_str = array("'", "`");
        $deskripsi = str_replace($arr_str, "", $request['deskripsi']);
        if (isset($request['id_edit'])){
            General_cashbond_detail::where('id', $request['id_edit'])
                ->update([
                    'source_string' => $request['source'],
                    'tanggal' => date("Y-m-d", strtotime($request['req_date'])),
                    'no_nota' => $request['subject'],
                    'deskripsi' => $deskripsi,
                    'cashout' => str_replace(",", '', $request['amount']),
                    'updated_by' => Auth::user()->username,
                    'tc_id' => $request->tc_child
                ]);
        } else {

            $r_date = date("Y-m-d", strtotime($request['req_date']));
            $currency = $request['curr'];
            $id_imburse = $request['id'];
            $cashin = 0; $cashout = 0;
            $bank_name = $request['source'];
            $reqAmount = str_replace(",", '', $request['amount']);

            if($request['cashtype'] == 'cashout'){ $cashout += $reqAmount; } else { $cashin += $reqAmount; }
            if (isset($request['source'])){
                General_cashbond::where('id', $id_imburse)
                    ->update([
                        'input_date' => $r_date,
                    ]);
            }
            $cashOut = new General_cashbond_detail();
            $cashOut->id_cashbond = $id_imburse;
            $cashOut->tanggal = $r_date;
            $cashOut->source_string = $bank_name;
            $cashOut->no_nota = $request['subject'];
            $cashOut->deskripsi = $deskripsi;
            $cashOut->source_int = 0;
            $cashOut->created_by = Auth::user()->username;
            $cashOut->cashin = $cashin;
            $cashOut->cashout = $cashout;
            $cashOut->tc_id_parent = $request['category'];
            $cashOut->tc_id = $request->tc_child;
            $cashOut->save();
        }

        return redirect()->route('cashbond.detail',['id' => $request['id']]);
    }

    public function deleteDetail($id,$id_cb){
        General_cashbond_detail::where('id', $id)->delete();
        return redirect()->route('cashbond.detail',['id' => $id_cb]);
    }

    public function delete($id){
        General_cashbond::where('id',$id)->delete();
        General_cashbond_detail::where('id_cashbond', $id)->delete();
        return redirect()->route('cashbond.index');

    }
    public function getDetRA($id,$who=null){
        $cb = General_cashbond::where('id',$id)
            ->where('company_id',\Session::get('company_id'))
            ->first();
        $sources = Finance_treasury::where('source','not like','%BR %')
            ->where('company_id',\Session::get('company_id'))
            ->where('type', 'bank')
            ->get();
        $cashbond_detail = General_cashbond_detail::where('id_cashbond', $id)
            ->where('cashin','>',0)
            ->get();
        $cashbond_detailOut = General_cashbond_detail::where('id_cashbond', $id)
            ->where('cashout','>',0)
            ->get();

        return view('pettycash.cashbond_print',[
            'cashbond' => $cb,
            'sources' => $sources,
            'cashbond_detail' => $cashbond_detail,
            'cashbond_detailOut' => $cashbond_detailOut,
            'who' => base64_decode($who)
        ]);
    }

    public function RAppr(Request $request){
        $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $m_sum = $request['cashinPost'];
        $datenow = date("Y-m-d");
        $bank_id = $request['bank_sel'];
        $br_id = $request['id'];
        $name = Auth::user()->username;
        $text2min = "Petty Cash [out]: ".$request['subject'].". (ID: $br_id)";
        $text2plus = "Petty Cash [in]: ".$request['subject'].". (ID: $br_id)";
        $coa = Finance_coa::all();
        $coa_name = $coa->pluck('name', 'id');
        $coa_code = $coa->pluck('code', 'id');
        $cb = General_cashbond::find($br_id);
        $tag = Session::get('company_tag');
        $blnArray = $array_bln[date('n', strtotime($cb->input_date))];
        $yr = date("y", strtotime($cb->input_date));
        $no_cb = sprintf("%03d", $cb->id)."/$tag/CASHBOND/$blnArray/$yr";
        $prj_code = $cb->project;
        if($cb->project < 100){
            $prj_code = sprintf("%03d", $cb->project);
        }

        if ($request['who'] == "admin"){
            $cashbond = General_cashbond::find($br_id);
            $cashbond['adm_approve_by'] = $name;
            $cashbond['adm_approve_at'] = date('Y-m-d H:i:s');

            if (isset($request['approved'])){
                $cashbond['is_need'] = 1;
            } else{
                $cashbond['is_need'] = 0;
            }

            $cashbond->save();
        }

        if ($request['who'] == 'cashin'){
            $m_sum_min = $m_sum * -1;

            $treasuryHistory = new Finance_treasury_history();
            $treasuryHistory->id_treasure = $bank_id;
            $treasuryHistory->date_input = $datenow;
            $treasuryHistory->description = $text2min;
            $treasuryHistory->IDR = $m_sum_min;
            $treasuryHistory->USD = 0.00;
            $treasuryHistory->PIC = $name;
            $treasuryHistory->created_by= $name;
            $treasuryHistory->company_id = \Session::get('company_id');
            $treasuryHistory->save();

            ActivityConfig::store_point('so', 'approve');
            General_cashbond::where('id',$br_id)
                ->update([
                    'approved_by' => $name,
                    'approved_time' => date('Y-m-d H:i:s'),
                    'man_fin_cashout_date' => $request['due_date'],
                ]);
        }
        if ($request['who'] == 'director'){
            ActivityConfig::store_point('cashbond', 'approve_dir');
            $treasuryHistory = new Finance_treasury_history();
            $treasuryHistory->id_treasure = $bank_id;
            $treasuryHistory->date_input = $datenow;
            $treasuryHistory->description = $text2plus;
            $treasuryHistory->IDR = intval($m_sum);
            $treasuryHistory->USD = 0.00;
            $treasuryHistory->PIC = $name;
            $treasuryHistory->created_by= $name;
            $treasuryHistory->company_id = \Session::get('company_id');
            $treasuryHistory->save();

            $cbDetail = General_cashbond_detail::where('id_cashbond', $br_id)->get();
            $cbd = [];
            foreach($cbDetail as $item){
                if($item->cashout > 0){
                    if(isset($coa_name[$item->tc_id_parent])){
                        $cbd[$item->tc_id_parent]['name'] = $coa_name[$item->tc_id_parent];
                        $cbd[$item->tc_id_parent]['no_coa'] = $coa_code[$item->tc_id_parent];
                        $cbd[$item->tc_id_parent]['value'][] = $item->cashout;
                    }
                }
            }

            if(!empty($cbd)){
                foreach ($cbd as $key => $value) {
                    $n_ame = $value['name'];
                    $tre_desc = "Petty Cash [out]: $no_cb [$n_ame][$prj_code]";
                    $tre = new Finance_treasury_history();
                    $tre->id_treasure = $bank_id;
                    $tre->date_input = date("Y-m-d H:i:s");
                    $tre->description = $tre_desc;
                    $tre->IDR = array_sum($value['value']) * -1;
                    $tre->USD = 0.00;
                    $tre->created_by = $name;
                    $tre->company_id = \Session::get('company_id');
                    $tre->save();

                    $tre_curr = Finance_treasury::find($tre->id_treasure);

                    $iCoa = new Finance_coa_history();
                    $iCoa->no_coa = $value['no_coa'];
                    $iCoa->coa_date = $tre->date_input;
                    $iCoa->debit = abs($tre->IDR);
                    $iCoa->id_treasure_history = $tre->id;
                    $iCoa->currency = $tre_curr->currency;
                    $iCoa->created_by = Auth::user()->username;
                    $iCoa->description = $tre->description;
                    $iCoa->approved_at = date("Y-m-d H:i:s");
                    $iCoa->approved_by = Auth::user()->username;
                    $iCoa->company_id = Session::get('company_id');
                    $iCoa->save();
                }
            }



            General_cashbond::where('id',$br_id)
                ->update([
                    'dir_appr' => $name,
                    'dir_appr_date' => date('Y-m-d H:i:s'),
                ]);
        }
        if ($request['who'] == 'manager'){
            ActivityConfig::store_point('cashbond', 'approve_div');
            General_cashbond::where('id',$br_id)
                ->update([
                    'm_approve' => $name,
                    'm_approve_time' => date('Y-m-d H:i:s'),
                    'sisa' => $request['sum'],
                    'done' => date('Y-m-d H:i:s'),
                ]);
        }

        return redirect()->route('cashbond.index');
    }
}
