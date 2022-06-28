<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App\Models\Finance_coa;
use App\Models\Marketing_bp;
use Illuminate\Http\Request;
use App\Models\Finance_treasury;
use App\Models\Marketing_project;
use App\Models\Finance_coa_history;
use App\Models\Marketing_bp_detail;
use Illuminate\Support\Facades\Auth;
use App\Models\Finance_treasury_history;

class MarketingBpController extends Controller
{
    public function index(){
        $count_ongoing = 0;
        $count_publish = 0;
        $sum_ongoing = 0;
        $sum_publish = 0;

        $bpongoing = Marketing_bp::leftJoin('marketing_projects as prj','prj.id','=','marketing_bp_main.prj_code')
            ->select("marketing_bp_main.*", "marketing_bp_main.status as bp_status")
            ->where('marketing_bp_main.company_id', Session::get('company_id'))
            ->whereNull('marketing_bp_main.final_date')
            ->orderBy('marketing_bp_main.id', 'DESC')
            ->get();

        foreach ($bpongoing as $key => $value){
            $count_ongoing +=1;
            $sum_ongoing += intval($value->nilai_jaminan);
            $value->det = Marketing_bp_detail::where("id_main", $value->id)->get();
        }

        $bppublish = Marketing_bp::leftJoin('marketing_projects as prj','prj.id','=','marketing_bp_main.prj_code')
            ->select("marketing_bp_main.*", "marketing_bp_main.status as bp_status")
            ->where('prj.company_id', Session::get('company_id'))
            ->whereNotNull('marketing_bp_main.final_date')
            ->orderBy('marketing_bp_main.id', 'DESC')
            ->get();

        foreach ($bppublish as $key => $value){
            $count_publish += 1;
            $sum_publish += intval($value->nilai_jaminan);
        }
        $projects = Marketing_project::where('company_id', \Session::get('company_id'))
            ->get();
        $coa = Finance_coa::all();
        return view('bp.index',[
            'bppublish'=>$bppublish,
            'bpongoing' =>$bpongoing,
            'count_ongoing' => $count_ongoing,
            'count_publish' => $count_publish,
            'sum_ongoing' => $sum_ongoing,
            'sum_publish' =>$sum_publish,
            'projects'=> $projects,
            'coa' => $coa
        ]);
    }

    function view($id){
        $price = Marketing_bp::where('id', $id)->get();
        $detail = Marketing_bp_detail::where('id_main',$id)->get();
        $status = "view";

//        dd($detail);
        return view('bp.fin_appr',[
            'price' => $price,
            'detail' => $detail,
            'status' => $status
        ]);
    }

    public function addBP(Request $request){
        // dd($request);
        $prj = Marketing_project::where('id', $request['project'])->first();
        // dd($prj);
        $date = date('Y-m-d');
        $bp = new Marketing_bp();
        $bp->prj_code = $request['project'];
        $bp->perusahaan = $request['company_name'];
        $bp->no_tender = $request['tender_number'];
        $bp->no_bond = $request['bond_number'];
        $bp->type_bond = $request['bond_type'];
        $bp->submit_date = $date;
        $bp->input_date = $date;
        $bp->currency = $request['currency'];
        $bp->status = 'Marketing Done';
        $bp->nilai_jaminan = str_replace(",", "", $request['amount']);
        $bp->prj_name = $prj->prj_name;
        $bp->nama_prj = $request['purpose_work'];
        $bp->date1 = $request['date1'];
        $bp->durasi = $request['duration'];
        $sentence = $request['date1']." +".$request['duration']." day";
        $bp->date2 = date("Y-m-d",strtotime($sentence));
        $bp->alasan_approve_operation = '';
        $bp->alasan_reject_operation = '';
        $bp->alasan_approve_finance = '';
        $bp->alasan_reject_finance = '';
        $bp->company_id = Session::get('company_id');
        $bp->tc_id = $request->tc_id;
        $bp->created_by = Auth::user()->username;
        // dd($bp->save());
        $bp->save();
        $lastid = $bp->id;

        $details = [
            ['id'=> null,'id_main' => $lastid,'prj_code'=>$request['project'],'currency' =>'','item_name' => 'AMOUNT', 'request_amount' => null, 'actual_amount' => 0.0],
            ['id'=> null,'id_main' => $lastid,'prj_code'=>$request['project'],'currency' => '','item_name' => 'ADMINISTRATION', 'request_amount' => null, 'actual_amount' => 0.0],
        ];
        foreach ($details as $detail){
            Marketing_bp_detail::create($detail);
        }

        return redirect()->route('bp.index');

    }

    public function getFinDiv($id){

        $price = Marketing_bp::where('id', $id)->get();
        $detail = Marketing_bp_detail::where('id_main',$id)->get();
        $status = "";

//        dd($detail);
        return view('bp.fin_appr',[
            'price' => $price,
            'detail' => $detail,
            'status' => $status
        ]);
    }

    public function finDivAppr(Request $request){
//        dd($request);
        $detail_id = $request['detail_id'];
        $Main_id = $request['main_id'];


        Marketing_bp_detail::where('id_main', $Main_id)
            ->where('item_name', 'AMOUNT')
            ->update([
                'currency' => $request['adm_currency_AMOUNT']
            ]);

        Marketing_bp_detail::where('id_main', $Main_id)
            ->where('item_name', 'ADMINISTRATION')
            ->update([
                'currency' => $request['adm_currency_ADMINISTRATION']
            ]);

        Marketing_bp_detail::where('id_main', $Main_id)
            ->where('item_name','AMOUNT')
            ->update([
                'request_amount' => str_replace(",", "", $request['AMOUNT'])
            ]);

        Marketing_bp_detail::where('id_main', $Main_id)
            ->where('item_name','ADMINISTRATION')
            ->update([
                'request_amount' => str_replace(",", "", $request['ADMINISTRATION'])
            ]);

        Marketing_bp::where('id',$Main_id)
            ->update([
                'price_date' =>date("Y-m-d"),
                'status' =>'Waiting Approval',
            ]);

        return redirect()->route('bp.index');
    }
    public function getDirAppr($id,$code){
        $key = base64_decode($code);
        $sources = Finance_treasury::where('source','not like','%BR %')
            ->where('company_id', Session::get('company_id'))
            ->get();
        $price = Marketing_bp::where('id', $id)->get();
        $detail = Marketing_bp_detail::where('id_main',$id)->get();


        return view('bp.dir_appr',[
            'price' => $price,
            'detail' => $detail,
            'code' => $key,
            'sources' =>$sources,
        ]);
    }

    public function submitAppr(Request $request){
        $coa = Finance_coa::all()->pluck('code', 'id');
        $tre_curr = Finance_treasury::all()->pluck('currency', 'id');
        if (isset($request['submit'])){
            if ($request['code']=='detail'){
                Marketing_bp::where('id',$request['main_id'])
                    ->update([
                        'release_date' =>date("Y-m-d"),
                        'status'=> 'Released',
                        'alasan_approve_finance' => '',
                    ]);
                Marketing_bp_detail::where('id_main', $request['main_id'])
                    ->where('item_name','AMOUNT')
                    ->update([
                        'request_amount' => $request['price_AMOUNT']
                    ]);
                Marketing_bp_detail::where('id_main', $request['main_id'])
                    ->where('item_name','ADMINISTRATION')
                    ->update([
                        'request_amount' => $request['price_ADMINISTRATION']
                    ]);

                $bp = Marketing_bp::find($request['main_id']);

                if (intval($request['price_AMOUNT']) != 0) {
                    $prj_code = ($bp->prj_code > 100) ? $bp->prj_code : sprintf("%03d", $bp->prj_code);
                    $treasure_his = new Finance_treasury_history();
                    $treasure_his->id_treasure = $request['AMOUNT'];
                    $treasure_his->project = $bp->prj_code;
                    $treasure_his->date_input = date("Y-m-d");
                    $treasure_his->description = "[$prj_code] ".$request['jobdesc_AMOUNT'];
                    $treasure_his->IDR = intval($request['price_AMOUNT']) * (-1);
                    $treasure_his->PIC = Auth::user()->username;
                    $treasure_his->company_id = Session::get('company_id');
                    $treasure_his->approval_status = 0;
                    $treasure_his->save();
                    if(!empty($bp->tc_id)){
                        if(isset($coa[$bp->tc_id])){
                            $iCoa = new Finance_coa_history();
                            $iCoa->no_coa = $coa[$bp->tc_id];
                            $iCoa->coa_date = $treasure_his->date_input;
                            $iCOa->project = $treasure_his->project;
                            $iCoa->debit = abs($treasure_his->IDR);
                            $iCoa->id_treasure_history = $treasure_his->id;
                            $iCoa->currency = $tre_curr[$treasure_his->id_treasure];
                            $iCoa->created_by = Auth::user()->username;
                            $iCoa->description = $treasure_his->description;
                            $iCoa->approved_at = date('Y-m-d H:i:s');
                            $iCoa->approved_by = Auth::user()->username;
                            $iCoa->company_id = Session::get('company_id');
                            $iCoa->save();
                        }
                    }
                }


                if (intval($request['price_ADMINISTRATION']) != 0) {
                    $treasure_his2 = new Finance_treasury_history();
                    $treasure_his2->id_treasure = $request['ADMINISTRATION'];
                    $treasure_his2->date_input = date("Y-m-d");
                    $treasure_his2->description = $request['jobdesc_ADMINISTRATION'];
                    $treasure_his2->IDR = intval($request['price_ADMINISTRATION']) * (-1);
                    $treasure_his2->PIC = Auth::user()->username;
                    $treasure_his2->company_id = Session::get('company_id');
                    $treasure_his2->approval_status = 0;
                    $treasure_his2->save();
                    if(!empty($bp->tc_id)){
                        if(isset($coa[$bp->tc_id])){
                            $iCoa = new Finance_coa_history();
                            $iCoa->no_coa = $coa[$bp->tc_id];
                            $iCoa->coa_date = $treasure_his2->date_input;
                            $iCoa->debit = abs($treasure_his2->IDR);
                            $iCoa->id_treasure_history = $treasure_his2->id;
                            $iCoa->currency = $tre_curr[$treasure_his2->id_treasure];
                            $iCoa->created_by = Auth::user()->username;
                            $iCoa->description = $treasure_his2->description;
                            $iCoa->approved_at = date('Y-m-d H:i:s');
                            $iCoa->approved_by = Auth::user()->username;
                            $iCoa->company_id = Session::get('company_id');
                            $iCoa->save();
                        }
                    }
                }
                // $treasure_history = [
                //     ['id_treasure' => $request['AMOUNT'],'date_input' =>date("Y-m-d"),'description' => $request['jobdesc_AMOUNT'],'IDR' => intval($request['price_AMOUNT']) * (-1),'PIC' => Auth::user()->username],
                //     ['id_treasure' => $request['ADMINISTRATION'],'date_input' =>date("Y-m-d"),'description' => $request['jobdesc_ADMINISTRATION'],'IDR' => intval($request['price_ADMINISTRATION']) * (-1),'PIC' => Auth::user()->username]
                // ];

                // foreach ($treasure_history as $value){
                //     Finance_treasury_history::create($value);
                // }

            } else {
                Marketing_bp::where('id',$request['main_id'])
                    ->update([
                        'final_date' => date("Y-m-d"),
                        'status'=> 'Done',
                    ]);
                Marketing_bp_detail::where('id_main', $request['main_id'])
                    ->where('item_name','AMOUNT')
                    ->update([
                        'request_amount' => $request['price_AMOUNT']
                    ]);
                Marketing_bp_detail::where('id_main', $request['main_id'])
                    ->where('item_name','ADMINISTRATION')
                    ->update([
                        'request_amount' => $request['price_ADMINISTRATION']
                    ]);

                $bp = Marketing_bp::find($request['main_id']);


                $treasure_his = new Finance_treasury_history();
                $treasure_his->id_treasure = $request['AMOUNT'];
                $treasure_his->date_input = date("Y-m-d");
                $treasure_his->description = $request['jobdesc_AMOUNT'];
                $treasure_his->IDR = intval($request['price_AMOUNT']);
                $treasure_his->PIC = Auth::user()->username;
                $treasure_his->company_id = Session::get('company_id');
                $treasure_his->approval_status = 0;
                $treasure_his->save();
                if(!empty($bp->tc_id)){
                    if(isset($coa[$bp->tc_id])){
                        $iCoa = new Finance_coa_history();
                        $iCoa->no_coa = $coa[$bp->tc_id];
                        $iCoa->coa_date = $treasure_his->date_input;
                        $iCoa->debit = abs($treasure_his->IDR);
                        $iCoa->id_treasure_history = $treasure_his->id;
                        $iCoa->currency = $tre_curr[$treasure_his->id_treasure];
                        $iCoa->created_by = Auth::user()->username;
                        $iCoa->description = $treasure_his->description;
                        $iCoa->approved_at = date('Y-m-d H:i:s');
                        $iCoa->approved_by = Auth::user()->username;
                        $iCoa->company_id = Session::get('company_id');
                        $iCoa->save();
                    }
                }
                // $treasure_history = [
                //     ['id_treasure' => $request['AMOUNT'],'date_input' =>date("Y-m-d"),'description' => $request['jobdesc_AMOUNT'],'IDR' => $request['price_AMOUNT'],'PIC' => Auth::user()->username],
                // ];

                // foreach ($treasure_history as $value){
                //     Finance_treasury_history::create($value);
                // }
            }

        }
        if (isset($request['reject'])){
            Marketing_bp::where('id',$request['main_id'])
                ->update([
                    'release_date' =>date("Y-m-d"),
                    'status'=> 'Reject',
                    'alasan_reject_finance' => '',
                ]);
        }

        return redirect()->route('bp.index');
    }

    public function bondR(Request $request){
        if ($request['type'] == 'Retrive'){
            Marketing_bp::where('id', $request['id'])
                ->update([
                   'status' => 'Retrieved',
                   'retrieve_by' => Auth::user()->username,
                   'retrieve_date' => date('Y-m-d'),
                    'retrieve_to' => $request['retrieve_to'],
                ]);
        } else {
            Marketing_bp::where('id', $request['id'])
                ->update([
                    'status' => 'Received',
                    'receive_by' => Auth::user()->username,
                    'receive_date' => date('Y-m-d'),
                    'receive_to' => $request['receive_to'],
                ]);
        }
        return redirect()->route('bp.index');
    }

    function delete($id){
        Marketing_bp::where('id', $id)
            ->update([
                "deleted_by" => Auth::user()->username
            ]);
        Marketing_bp::find($id)->delete();
        Marketing_bp_detail::where('id_main', $id)->delete();

        return redirect()->back();
    }
}
