<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App\Models\Division;
use App\Models\RoleDivision;
use Illuminate\Http\Request;
use App\Models\Rms_divisions;
use App\Models\General_report;
use App\Helpers\FileManagement;
use App\Models\File_Management;;
use App\Models\General_report_qty;
use App\Models\General_report_item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\General_report_attach;
use Illuminate\Support\Facades\Route;
use App\Models\General_report_activity;


class GeneralDailyReport extends Controller
{

    public function index(){
        $report = General_report::where('company_id', Session::get('company_id'))
            ->orderBy('rpt_time','DESC')
            ->get();

        $divisions = Rms_divisions::all();
        $div =[];
        foreach ($divisions as $key => $value){
            $div[$value->id] = $value->name;
        }

        return view('daily_report.index',[
            'report' => $report,
            'divisions' => $divisions,
            'div' => $div,
        ]);

    }

    public function viewPage($id=null,$appr=null){
        $divisions = Rms_divisions::all();

        $count = 0;
        $route = Route::getFacadeRoot()->current()->uri();
        $user_rms = Auth::user()->id_rms_roles_divisions;
        $user_div = RoleDivision::find($user_rms);
        $div = Division::find($user_div->id_rms_divisions);
        if ($id != null){
            $detail_report = General_report::where('id', $id)->first();
            $detail_activity = General_report_activity::where('id_report', $id)->get();
            $count = $detail_activity->count();

            $detail_attach = General_report_attach::where('id_report', $id)->get();
            if ($appr!=null && $appr= base64_encode('appr')){
                $status = 'approve';
            } else {
                $status = null;

            }
            $file_hash = array();
            foreach ($detail_attach as $key => $value) {
                $file_hash[] = $value->filename;
            }
            $file_management = File_management::whereIn('hash_code', $file_hash)->get();
            $file_type = array();
            foreach ($file_management as $key => $value) {
                $file_type[$value->hash_code] = $value;
            }
//            dd($detail_activity);
            return view('daily_report.input',[
                'type' => 'edit',
                'count_activity' => $count,
                'report' => $detail_report,
                'activity' => $detail_activity,
                'attach' => $detail_attach,
                'divisions' => $divisions,
                'route' => $route,
                "file_detail" => $file_type,
                'status' => $status,
            ]);
        } else {
            if ($count == 0){
                $count = 1;
            }
            return view('daily_report.input',[
                'type' => 'input',
                'count_activity' => $count,
                'divisions' => $divisions,
                'route' => $route,
                'div' => $div
            ]);
        }
    }

    public function store(Request $request){
//        dd($request);
        $divisions = Rms_divisions::all();

        $route = Route::getFacadeRoot()->current()->uri();
        if (isset($request['add_activity'])){
//            dd($request['id']);
//            dd($request);
            $type = $request['type'];


            if ($request['id'] != 0){
                $report_by = $request['report_by'];
                $division_ = $request['divisions'];
                $report_date = $request['report_date'];
                $location = $request['location'];

                $detail_report = General_report::where('id', $request['id'])->first();
                $detail_activity = General_report_activity::where('id_report', $request['id'])->get();
                $detail_attach = General_report_attach::where('id_report', $request['id'])->get();

                $rpt_from = [];
                $rpt_to = [];
                $rpt_desc = [];
                foreach ($detail_activity as $key => $value){
                    $rpt_from[] = $value->rep_from;
                    array_push($rpt_from,$value->rep_from);
                }
                foreach ($detail_activity as $key => $value){
                    $rpt_to[] = $value->rep_to;
                    array_push($rpt_to,"");
                }
                foreach ($detail_activity as $key => $value){
                    $rpt_desc[] = $value->rep_desc;
                    array_push($rpt_desc,"");
                }
//                dd($rpt_to);
//              dd($rpt_to);
                $count_activity = count($rpt_from);

                return view('daily_report.input',[
                    'type' => $type,
                    'count_activity' => $count_activity,
                    'report' => $detail_report,
                    'activity' => $detail_activity,
                    'attach' => $detail_attach,
                    'divisions' => $divisions,
                    'addmore' => 1,
                    'report_by' => $report_by,
                    'division' => $division_,
                    'report_date' => $report_date,
                    'location' => $location,
                    'rpt_from' => $rpt_from,
                    'rpt_to' => $rpt_to,
                    'rpt_desc' => $rpt_desc,
                    'route' => $route,
                ]);
            } else {

                $rpt_frompost = $request['rep_from'];
                $rpt_topost = $request['rep_to'];
                $rpt_descpost = $request['rep_desc'];

                $report_by = $request['report_by'];
                $division_ = $request['divisions'];
                $report_date = $request['report_date'];
                $location = $request['location'];

                $rpt_from = [];
                $rpt_to = [];
                $rpt_desc = [];
                foreach ($rpt_frompost as $key => $value){
                    $rpt_from[] = $value;
                    array_push($rpt_from,$rpt_topost[$key]);
                }
                foreach ($rpt_topost as $key => $value){
                    $rpt_to[] = $value;
                    array_push($rpt_to,"");
                }
                foreach ($rpt_descpost as $key => $value){
                    $rpt_desc[] = $value;
                    array_push($rpt_desc,"");
                }
//            dd($rpt_to);
                $count_activity = count($rpt_from);

                return view('daily_report.input',[
                    'type' => $type,
                    'count_activity' => $count_activity,
                    'divisions' => $divisions,
                    'addmore' => 1,
                    'report_by' => $report_by,
                    'division' => $division_,
                    'report_date' => $report_date,
                    'location' => $location,
                    'rpt_from' => $rpt_from,
                    'rpt_to' => $rpt_to,
                    'rpt_desc' => $rpt_desc,
                    'route' => $route,
                ]);
            }
        } elseif (isset($request['approve_report'])){
//            dd($request['approved']);
            if ($request['approved'] != null){
                General_report::where('id', $request['report_id'])
                    ->update([
                        'approve_by' => Auth::user()->username,
                        'approve_time' => date('Y-m-d H:i:s'),
                        'approve_notes' => $request['notes_approve']
                    ]);
            }

            return redirect()->route('general.dr');

        } else {
            $divisions = Rms_divisions::all();
            $div =[];
            foreach ($divisions as $key => $value){
                $div[$value->id] = $value->name;
            }

            if (isset($request['submitAll'])){
                $rpt = new General_report();
            } else {
                $rpt= General_report::find($request['updateAll']);
            }
//            dd($_FILES);

            date_default_timezone_set('Asia/Jakarta');

            $rpt->rpt_wh = $request['divisions'];
            $rpt->rpt_subject = "REPORT/".strtoupper(Session::get('company_tag')).'/'.strtoupper($div[$request['divisions']]).'/'.date("m")."/".date("d");
            $rpt->rpt_text = $request['location'];
            $rpt->rpt_time = date('Y-m-d H:i:s',strtotime($request['report_date']));
            $rpt->create_time = date('Y-m-d H:i:s');
            $rpt->created_at = date('Y-m-d H:i:s');
            $rpt->create_by = Auth::user()->username;
            $rpt->created_by = Auth::user()->username;
            $rpt->company_id = Session::get('company_id');
            $rpt->save();
            $id_report = $rpt->id;

            foreach ($request->rep_from as $key => $val){
                $activity = new General_report_activity();
                $activity->id_report = $id_report;
                $activity->rep_from = $val;
                $activity->rep_to = $request['rep_to'][$key];
                $activity->rep_desc = $request['rep_desc'][$key];
                $activity->save();
            }

            if ($request->hasFile('attachment')){
                if ($_FILES['attachment']['name'] != null) {
                    $file_count = count($_FILES['attachment']['name']);
                    // dd($file_count);
                    for ($f = 0; $f <$file_count; $f++){
                        $attachment = new General_report_attach();
                        $attachment->id_report = $id_report;
                        $attachment->created_by = Auth::user()->username;
                        $attachment->created_at = date('Y-m-d H:i:s');
                        //                dd($request->file('attachment')[]);
                        if (isset($request->file('attachment')[$f])) {
                            $file = $request->file('attachment')[$f];

                            $newFile = $file->getClientOriginalName();
                            //                dd($newFile);
                            $hashFile = Hash::make($newFile);
                            $hashFile = str_replace("/", "", $hashFile);

                            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/daily_report_attachment");
                            if ($upload == 1){
                                $attachment->filename = $hashFile;
                            }
                            $attachment->save();
                        }
                    }
                }
            }

            return redirect()->route('general.dr');
        }

//        dd($request);
    }

    public function getDataInventory($id,$division){
        $route = Route::getFacadeRoot()->current()->uri();
        $report = General_report::where('id',$id)->first();
        $itemIDs = [];
        $itemNames = [];
        $itemInit = [];
        $items = General_report_item::where('rpt_wh', $division)
            ->where('company_id', Session::get('company_id'))
            ->get();
        foreach ($items as $value){
            $itemIDs[]=$value->id;
            $itemNames[]=$value->item_name;
            $itemInit[]=$value->initials;
        }

        $qtyItemID =[];
        $qtyId = [];
        $qtyIn = [];
        $qtyOut = [];
        $qtyInit = [];
        $qtyBal = [];
        $qtyLock= [];
        $qtyIdReport = [];

        $item_qty = General_report_qty::all();

        foreach ($item_qty as $value){
            $qtyItemID[] = $value->item_id;
            $qtyId[$value->item_id] = $value->id;
            $qtyIn[$value->item_id] = $value->qty_in;
            $qtyOut[$value->item_id] = $value->qty_out;
            $qtyInit[$value->item_id] = $value->qty_init;
            $qtyBal[$value->item_id] = $value->qty_bal;
            $qtyIdReport[$value->item_id] = $value->id_report;
            $qtyLock[$value->item_id] = $value->locked;
        }
//        dd($qtyLock);

        return view('daily_report.inventory',[
            'report' => $report,
            'itemIDs' => $itemIDs,
            'itemNames' => $itemNames,
            'itemInit' => $itemInit,
            'qtyItemID' => $qtyItemID,
            'qtyId' => $qtyId,
            'qtyIn' => $qtyIn,
            'qtyOut' => $qtyOut,
            'qtyInit' => $qtyInit,
            'qtyBal' => $qtyBal,
            'qtyLock' => $qtyLock,
            'qtyIdReport' => $qtyIdReport,
            'route' => $route,
        ]);
    }
    public function insertInitInventory(Request $request){
        $item = new General_report_item();
        $item->rpt_wh = $request->division;
        $item->item_name = $request->item_name;
        $item->initials = $request->item_qty;
        $item->created_by = Auth::user()->username;
        $item->company_id = Session::get('company_id');
        $item->save();
//        dd($request);
        return redirect()->back();
    }

    public function postInQty(Request $request){
        $qty_init = $request->qty_init;
        $qtyIn = $request->qty;
        $id_item = $request->id_item;
        $id_report = $request->id_report;
        $qtyX = $request->qtyx;

        $report_qty_old = General_report_qty::where('id_report',$id_report)
            ->where('item_id', $id_item)
            ->where('qty_out', $qtyX)
            ->latest('id')
            ->first();

        if ($report_qty_old != null){
            $balance = intval($report_qty_old->qty_bal) + intval($qtyIn);
            $report_qty = General_report_qty::find($report_qty_old->id);

        } else {
            $balance = intval($qty_init) + intval($qtyIn);
            $report_qty = new General_report_qty();

        }
        $report_qty->id_report = $id_report;
        $report_qty->item_id = $id_item;
        $report_qty->qty_in = intval($qtyIn);
        $report_qty->qty_out = intval($qtyX);
        $report_qty->qty_init = intval($qty_init);
        $report_qty->qty_bal = $balance;
        if ($balance >=0){
            $report_qty->save();
        }
        $id_report_qty = $report_qty->id;
        $report_qty_data = General_report_qty::where('id', $id_report_qty)->first();

        return json_encode($report_qty_data);
    }

    public function postOutQty(Request $request){
        $qty_init = $request->qty_init;
        $qtyOut = $request->qty;
        $id_item = $request->id_item;
        $id_report = $request->id_report;
        $qtyX = $request->qtyx;

        $report_qty_old = General_report_qty::where('id_report',$id_report)
            ->where('item_id', $id_item)
            ->where('qty_in', $qtyX)
            ->latest('id')
            ->first();

        if ($report_qty_old != null){
            $balance = intval($report_qty_old->qty_bal) - intval($qtyOut);
            $report_qty = General_report_qty::find($report_qty_old->id);
        } else {
            $balance = intval($qty_init) - intval($qtyOut);
            $report_qty = new General_report_qty();
        }

        $report_qty->id_report = $id_report;
        $report_qty->item_id = $id_item;
        $report_qty->qty_out = intval($qtyOut);
        $report_qty->qty_init = intval($qty_init);
        $report_qty->qty_bal = $balance;
        if ($balance >=0){
            $report_qty->save();
        }
        $id_report_qty = $report_qty->id;

        $report_qty_data = General_report_qty::where('id', $id_report_qty)->first();

        return json_encode($report_qty_data);
    }

    public function lockInventory(Request $request){
//        dd($request);
        foreach ($request->id_report as $key => $value){
            General_report_qty::where('id_report', $value)
                ->update([
                    'locked' => $value
                ]);
        }

        return redirect()->back();

    }

    public function delete($id){
        General_report::where('id',$id)->delete();
        General_report_attach::where('id_report', $id)->delete();
        return redirect()->back();
    }


}
