<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App\Models\User;
use App\Models\Finance_coa;
use Illuminate\Http\Request;
use App\Models\Asset_type_wo;
use App\Helpers\ActivityConfig;
use App\Models\Finance_treasury;
use App\Models\General_reimburse;
use App\Models\Marketing_project;
use App\Models\Asset_new_category;
use App\Models\Finance_coa_source;
use App\Models\Finance_coa_history;
use Illuminate\Support\Facades\Auth;
use App\Models\Finance_treasury_history;
use App\Models\General_reimburse_detail;

class GeneralReimburse extends Controller
{
    public function index(){
        $reimburselists = DB::table('general_reimburse')
            ->leftJoin('marketing_projects as project','project.id','=','general_reimburse.project')
            ->select('general_reimburse.*','project.prj_name as prj_name')
            ->whereNull('general_reimburse.done')
            ->whereNull('general_reimburse.deleted_at')
            ->where('general_reimburse.company_id',\Session::get('company_id'))
            // ->where('general_reimburse.created_by',Auth::user()->username)
            ->orderBy('id', 'desc')
            ->get();

        $reimburserecv = DB::table('general_reimburse')
            ->leftJoin('marketing_projects as project','project.id','=','general_reimburse.project')
            ->select('general_reimburse.*','project.prj_name as prj_name')
            ->whereNotNull('general_reimburse.done')
            ->whereNull('approved_time')
            ->whereNull('general_reimburse.deleted_at')
            ->where('general_reimburse.company_id',\Session::get('company_id'))
            // ->where('general_reimburse.created_by',Auth::user()->username)
            ->orderBy('id', 'desc')
            ->get();

        $reimbursebanks = DB::table('general_reimburse')
            ->leftJoin('marketing_projects as project','project.id','=','general_reimburse.project')
            ->select('general_reimburse.*','project.prj_name as prj_name')
            ->whereNotNull('general_reimburse.done')
            ->whereNotNull('approved_time')
            ->whereNull('general_reimburse.deleted_at')
            ->where('general_reimburse.company_id',\Session::get('company_id'))
            // ->where('general_reimburse.created_by',Auth::user()->username)
            ->orderBy('id', 'desc')
            ->get();

        $listperson = User::where('company_id',\Session::get('company_id'))
            ->whereNotIn('password',['xxx','out'])
            ->orderBy('username','ASC')
            ->get();

        $projects = Marketing_project::where('company_id',\Session::get('company_id'))
            ->orderBy('prj_name','ASC')
            ->get();
        $prjname = $projects->pluck('prj_name', 'id');

        // dd($reimburselists);
        $sumcashout =[];
        $sumcashin = [];
        foreach ($reimburselists as $key => $reimburseList1){
            $sumcashin[$reimburseList1->id][] = DB::table('general_reimburse_detail')->where('id_reimburse', '=', $reimburseList1->id)->where('deleted_at', '=', null )->sum('cashout');
            $sumcashout[$reimburseList1->id][] = DB::table('general_reimburse_detail')->where('id_reimburse', '=', $reimburseList1->id)->where('deleted_at', '=', null )->sum('cashout');
        }
        foreach ($reimbursebanks as $key => $reimburseList1){
            $sumcashin[$reimburseList1->id][] = DB::table('general_reimburse_detail')->where('id_reimburse', '=', $reimburseList1->id)->where('deleted_at', '=', null )->sum('cashout');
            $sumcashout[$reimburseList1->id][] = DB::table('general_reimburse_detail')->where('id_reimburse', '=', $reimburseList1->id)->where('deleted_at', '=', null )->sum('cashout');
        }
        foreach ($reimburserecv as $key => $reimburseList1){
            $sumcashin[$reimburseList1->id][] = DB::table('general_reimburse_detail')->where('id_reimburse', '=', $reimburseList1->id)->where('deleted_at', '=', null )->sum('cashout');
            $sumcashout[$reimburseList1->id][] = DB::table('general_reimburse_detail')->where('id_reimburse', '=', $reimburseList1->id)->where('deleted_at', '=', null )->sum('cashout');
        }

        return view('reimburse.index',[
            'listpersons' => $listperson,
            'projects' => $projects,
            'reimburselists' => $reimburselists,
            'reimbursebanks' =>$reimbursebanks,
            'reimburserecv' =>$reimburserecv,
            'cashin' => $sumcashin,
            'cashout' => $sumcashout,
            'prjname' => $prjname
        ]);
    }

    public function addReimburse(Request $request){
        ActivityConfig::store_point('reimburse', 'create');
        if (isset($request['edit'])){
            General_reimburse::where('id',$request['id'])
                ->update([
                    'subject' => $request['subject'],
                    'currency' => $request['currency'],
                    'project' => $request['project'],
                    'division' => $request['division'],
                    'user' => $request['for_personel'],
                ]);
        }
        if (isset($request['add'])){
            $reimburse = new General_reimburse();
            $reimburse->subject = $request['subject'];
            $reimburse->input_date = date('Y-m-d');
            $reimburse->currency = $request['currency'];
            $reimburse->division = $request['division'];
            $reimburse->user = $request['for_personel'];
            $reimburse->project = $request['project'];

            $reimburse->created_by = Auth::user()->username;
            $reimburse->company_id = \Session::get('company_id');
            $reimburse->save();
        }


        return redirect()->route('reimburse.index');
    }

    public function getDetail($id){
        $reimburse = General_reimburse::where('general_reimburse.id',$id)
            ->where('company_id',\Session::get('company_id'))
            ->first();

        $detailOut = General_reimburse_detail::where('id_reimburse',$id)
            ->whereNotNull('tc_id')
            ->whereNotNull('tc_id_parent')
            ->where('cashout','>',0)
            ->orderBy('id', 'DESC')
            ->get();
        $numRowsOut = $detailOut->count();
        $typewo = Asset_type_wo::orderBy('name', 'ASC')->get();
        $category = Asset_new_category::all();
        $project = Marketing_project::find($reimburse->project);
        $isReimburse = [];
        foreach ($detailOut as $value) {
            $isReimburse[$value->category][] = $value;
        }

        $src = Finance_coa_source::where('name', 'rb')->first();
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

        return view('reimburse.detail',[
            'detail' => $reimburse,
            'detailOut' => $detailOut,
            'numRowsOut' => $numRowsOut,
            'typewo' => $tp,
            'categories' => $category,
            'project' => $project,
            'isReimburse' => $isReimburse,
            'coa' => $coa_child,
            'dCoa' => $dCoa
        ]);
    }

    public function addCashOut(Request $request){
        $arr_str = array("'", "`");
        $deskripsi = str_replace($arr_str, "", $request['deskripsi']);
        if (isset($request['id_edit'])){
            General_reimburse_detail::where('id', $request['id_edit'])
                ->update([
                    'source_string' => $request['source'],
                    'tanggal' => date("Y-m-d", strtotime($request['req_date'])),
                    'no_nota' => $request['subject'],
                    'deskripsi' => $deskripsi,
                    'cashout' =>$request['amount'],
                    'updated_by' => Auth::user()->username,
                    'tc_id' => $request['tc_child']
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
                General_reimburse::where('id', $id_imburse)
                    ->update([
                        'input_date' => $r_date,
                    ]);
            }
            $cashOut = new General_reimburse_detail();
            $cashOut->id_reimburse = $id_imburse;
            $cashOut->tanggal = $r_date;
            $cashOut->source_string = $bank_name;
            $cashOut->no_nota = $request['subject'];
            $cashOut->deskripsi = $deskripsi;
            $cashOut->source_int = 0;
            $cashOut->created_by = Auth::user()->username;
            $cashOut->cashin = $cashin;
            $cashOut->cashout = $cashout;
            $cashOut->tc_id_parent = $request['category'];
            $cashOut->tc_id = $request['tc_child'];
            $cashOut->save();
        }

        return redirect()->route('reimburse.detail',['id' => $request['id']]);
    }

    public function deleteDetail($id,$id_cb){
        General_reimburse_detail::where('id', $id)->delete();
        return redirect()->route('reimburse.detail',['id' => $id_cb]);
    }

    public function delete($id){
        General_reimburse::where('id',$id)->delete();
        General_reimburse_detail::where('id_reimburse', $id)->delete();
        return redirect()->route('reimburse.index');

    }

    public function getDetRA($id,$who=null){
        $cb = General_reimburse::where('id',$id)
            ->where('company_id',\Session::get('company_id'))
            ->first();
        $sources = Finance_treasury::where('source','not like','%BR %')
            ->where('company_id',\Session::get('company_id'))
            ->get();
        $reimburse_detail = General_reimburse_detail::where('id_reimburse', $id)
            ->where('cashin','>',0)
            ->get();
        $reimburse_detailOut = General_reimburse_detail::where('id_reimburse', $id)
            ->where('cashout','>',0)
            ->get();

        return view('reimburse.reimburse_print',[
            'reimburse' => $cb,
            'sources' => $sources,
            'reimburse_detail' => $reimburse_detail,
            'reimburse_detailOut' => $reimburse_detailOut,
            'who' => base64_decode($who)
        ]);
    }

    public function RAppr(Request $request){
        // dd($request);
        $m_sum = $request['cashinPost'];
        $datenow = date("Y-m-d");
        $bank_id = $request['bank_sel'];
//        dd($bank_id);
        $br_id = $request['id'];
        $name = Auth::user()->username;
        $text2 = $request['subject'];
        $wo_type = Asset_type_wo::all()->pluck('name', 'id');


        if ($request['who'] == 'manager'){
            if (isset($request['approved'])){
                ActivityConfig::store_point('reimburse', 'approve_dir');
                General_reimburse::where('id',$br_id)
                    ->update([
                        'm_approve' => $name,
                        'm_approve_time' => date('Y-m-d H:i:s'),
                        'sisa' => $request['sum'],
                    ]);
            }
        }
        if ($request['who'] == 'finance'){
            $m_sum_his = $request['sum'];
//            $m_sum_min = $m_sum * -1;
            // $treasuryHistory = new Finance_treasury_history();
            // $treasuryHistory->id_treasure = $bank_id;
            // $treasuryHistory->date_input = $datenow;
            // $treasuryHistory->description = $text2;
            // $treasuryHistory->IDR = $m_sum_his;
            // $treasuryHistory->USD = 0.00;
            // $treasuryHistory->PIC = $name;
            // $treasuryHistory->created_by= $name;
            // $treasuryHistory->company_id = \Session::get('company_id');
            // $treasuryHistory->save();
            $coa = Finance_coa::all();
            $coa_name = $coa->pluck('name', 'id');
            $coa_code = $coa->pluck('code', 'id');
            $rb = General_reimburse::find($br_id);
            $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
            $tag = Session::get('company_tag');
            $blnArray = $array_bln[date('n', strtotime($rb->input_date))];
            $yr = date("y", strtotime($rb->input_date));
            $no_rb = sprintf("%03d", $rb->id)."/$tag/REIMBURSE/$blnArray/$yr";
            $prj_code = $rb->project;
            $detail = General_reimburse_detail::where('id_reimburse', $br_id)->get();
            $rbd = [];
            foreach($detail as $item){
                // if ($item->cashout > 0) {
                //     $rbd[$item->category]['name'] = (isset($wo_type[$item->category])) ? strtoupper($wo_type[$item->category]) : "-";
                //     $rbd[$item->category]['value'][] = $item->cashout;
                // }
                if(isset($coa_name[$item->tc_id]) && $item->cashout > 0){
                    $rbd[$item->tc_id]['name'] = $coa_name[$item->tc_id];
                    $rbd[$item->tc_id]['no_coa'] = $coa_code[$item->tc_id];
                    $rbd[$item->tc_id]['value'][] = $item->cashout;
                }
            }
            if(!empty($rbd) && isset($request['approved'])){
                ActivityConfig::store_point('reimburse', 'approve_finance');
                General_reimburse::where('id',$br_id)
                    ->update([
                        'approved_by' => $name,
                        'approved_time' => date('Y-m-d H:i:s'),
                        'sisa' => 0,
                    ]);
                foreach($rbd as $value){
                    $n_ame = $value['name'];
                    $tre_desc = "Reimburse: $text2 [$no_rb][$n_ame][$prj_code]";
                    // $tre_desc = "Reimburse: $text2 [$no_rb][$prj_code]";
                    $tre = new Finance_treasury_history();
                    $tre->id_treasure = $bank_id;
                    $tre->project = $rb->project;
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
                    $iCoa->project = $tre->project;
                    $iCoa->debit = abs($tre->IDR);
                    $iCoa->id_treasure_history = $tre->id;
                    $iCoa->created_by = Auth::user()->username;
                    $iCoa->currency = $tre_curr->currency;
                    $iCoa->description = $tre->description;
                    $iCoa->approved_at = date("Y-m-d H:i:s");
                    $iCoa->approved_by = Auth::user()->username;
                    $iCoa->company_id = Session::get('company_id');
                    $iCoa->save();
                }
            }
        }
        if ($request['who'] == 'user'){
            General_reimburse::where('id',$br_id)
                ->update([
                    'done' => date('Y-m-d'),
                ]);
        }


        return redirect()->route('reimburse.index');
    }
}
