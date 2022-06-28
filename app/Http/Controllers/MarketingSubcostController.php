<?php

namespace App\Http\Controllers;

use App\Models\Finance_treasury;
use Illuminate\Http\Request;
use App\Models\Marketing_project;
use App\Models\Finance_treasury_history;
use App\Models\Marketing_subcost_detail;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;

class MarketingSubcostController extends Controller
{
    public function index(){
        $data = Marketing_project::whereNull('view_subcost')
            ->where('company_id',\Session::get('company_id'))
            ->orderBy('prj_name')
            ->get();

        $dataBank = Marketing_project::where('view_subcost','done')
            ->where('company_id',\Session::get('company_id'))
            ->orderBy('prj_name')
            ->get();


        $subcost = Marketing_subcost_detail::all();
        $sumcashidr = [];
        $sumcashusd = [];
        foreach ($subcost as $key => $value) {
            $sumcashidr[$value->id_subcost][] = $value->cashout;
            $sumcashusd[$value->id_subcost][] = $value->dcashout;
        }


        return view('subcost.index',[
            'subcost' => $data,
            'subcost_bank' => $dataBank,
            'sumcashidr' => $sumcashidr,
            'sumcashusd' => $sumcashusd,

        ]);
    }
    public function submitDone($id){
        Marketing_project::where('id',$id)
            ->update([
                'view_subcost' => 'done'
            ]);

        return redirect()->route('subcost.index');
    }

    public function getDetail($id){
        $cashin = DB::table('marketing_subcost_detail')
            ->join('marketing_projects as project','project.id','=','marketing_subcost_detail.id_subcost')
            ->select('project.prj_name as prj_name','marketing_subcost_detail.*')
            ->where('project.id',$id)
            ->whereNull('marketing_subcost_detail.deleted_at')
            ->where('marketing_subcost_detail.cashin','>',0)
            ->get();
        $cashout = DB::table('marketing_subcost_detail')
            ->join('marketing_projects as project','project.id','=','marketing_subcost_detail.id_subcost')
            ->select('project.prj_name as prj_name','marketing_subcost_detail.*')
            ->where('project.id',$id)
            ->whereNull('marketing_subcost_detail.deleted_at')
            ->where('marketing_subcost_detail.cashout','>',0)
            ->get();

        $numRowsIn = $cashin->count();
        $numRowsOut = $cashout->count();

        $project = Marketing_project::where('id',$id)->first();

        $treasure = Finance_treasury::where('company_id', Session::get('company_id'))->get();

        return view('subcost.detail',[
            'project' => $project,
            'cashin' => $cashin,
            'cashout' => $cashout,
            'numRowsIn' => $numRowsIn,
            'numRowsOut' => $numRowsOut,
            'sources' => $treasure,
        ]);
    }

    public function addCash(Request $request){
        $arr_str = array("'", "`");
        $deskripsi = str_replace($arr_str, "", $request['deskripsi']);
        if (isset($request['id_edit'])){
            if($request['cashtype'] == 'cashout'){
                Marketing_subcost_detail::where('id', $request['id_edit'])
                    ->update([
                        'cashout' => $request['amount'],
                    ]);
            }
            if ($request['cashtype'] == 'cashin') {
                Marketing_subcost_detail::where('id', $request['id_edit'])
                    ->update([
                        'cashin' => $request['amount'],
                    ]);
            }
            Marketing_subcost_detail::where('id', $request['id_edit'])
                ->update([
                    'source_string' => $request['source'],
                    'tanggal' => date("Y-m-d", strtotime($request['req_date'])),
                    'no_nota' => $request['subject'],
                    'deskripsi' => $deskripsi,
                    'updated_by' => Auth::user()->username,
                ]);
        } else {
            $r_date = date("Y-m-d", strtotime($request['req_date']));
            $currency = $request['currency'];
            $id_imburse = $request['prj_id'];
            $cashin = 0; $cashout = 0;
            $bank_name = $request['source'];

            if($request['cashtype'] == 'cashout'){
                $cashout += $request['amount'];
            } else {
                $cashin += $request['amount'];
            }

            $subcostDetail = new Marketing_subcost_detail();
            $subcostDetail->id_subcost = $id_imburse;
            $subcostDetail->tanggal = $r_date;
            $subcostDetail->source_string = $bank_name;
            $subcostDetail->no_nota = $request['subject'];
            $subcostDetail->deskripsi = $deskripsi;
            $subcostDetail->source_int = 0;
            $subcostDetail->receiver = $request['deliverto'];
            $subcostDetail->deliver = $request['deliverby'];
            $subcostDetail->currency = $currency;
            $subcostDetail->created_by = Auth::user()->username;
            $subcostDetail->cashin = $cashin;
            $subcostDetail->cashout = $cashout;
            $subcostDetail->save();
        }

        return redirect()->route('subcost.detail',['id' => $request['prj_id']]);
    }

    public function deleteSubcostDetail($id,$id_detail){

        Marketing_subcost_detail::where('id',$id_detail)->delete();

        return redirect()->route('subcost.detail',['id' => $id]);

    }

    public function submitApprove($id, $id_detail, $type){
        if (base64_decode($type) == 'ops'){
            Marketing_subcost_detail::where('id', $id_detail)
                ->update([
                    'ops_approve' => Auth::user()->username,
                    'ops_approve_time' => date('Y-m-d'),
                ]);
        } else{
            Marketing_subcost_detail::where('id', $id_detail)
                ->update([
                    'ceo_approve' => Auth::user()->username,
                    'ceo_approve_time' => date('Y-m-d'),
                ]);
        }

        return redirect()->route('subcost.detail',['id' => $id]);
    }

    public function submitApproveFin(Request $request){
        $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        Marketing_subcost_detail::where('id', $request['id'])
            ->update([
               'fin_approve' => Auth::user()->username,
                'fin_approve_time' => date('Y-m-d'),
            ]);
        $subcost = Marketing_subcost_detail::find($request['id']);

        $project = Marketing_project::find($subcost->id_subcost);
        $prj_name = (!empty($project->prj_name)) ? $project->prj_name : "";
        $no_subcost = sprintf("%03d", $subcost->id)."/SC/".Session::get('company_tag')."/".$array_bln[date('n', strtotime($subcost->tanggal))]."/".date('y', strtotime($subcost->tanggal));

        $treasureHistory = new Finance_treasury_history();
        $treasureHistory->id_treasure = $request['source'];
        $treasureHistory->date_input = date('Y-m-d');
        $treasureHistory->project = $project->id;
        $treasureHistory->description = "Subcost Out : [".$prj_name."] ".$request['deskripsi']." - ".$no_subcost;
        $treasureHistory->IDR = intval($request['amount']) * -1;
        $treasureHistory->sp_app = 0;
        $treasureHistory->created_by = Auth::user()->username;
        $treasureHistory->company_id = Session::get('company_id');
        $treasureHistory->save();

        return redirect()->route('subcost.detail',['id' => $request['prj_id']]);

    }
}
