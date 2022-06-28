<?php

namespace App\Http\Controllers;

use App\Helpers\FileManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use Session;
use App\Models\Qhse_mcu_main;
use App\Models\Qhse_mcu_log;
use App\Models\Hrd_employee;

class MCUController extends Controller
{
    public function index(){
        $employees = Hrd_employee::where('company_id', \Session::get('company_id'))->get();
        $mcu_main = Qhse_mcu_main::leftJoin('hrd_employee as emp','emp.id','=','mcu_main.emp_id')
            ->select('mcu_main.*','emp.emp_name as empName')
            ->where('mcu_main.company_id',\Session::get('company_id'))
            ->get();

        $expDate = [];
        $lastCheckUp = [];
        $lastRemark = [];

        $logs = Qhse_mcu_log::orderBy('id', 'desc')->get();
        foreach ($logs as $key => $value){
            $expDate[$value->mcu_id][] = $value->mcu_expired;
            $lastCheckUp[$value->mcu_id][] = $value->mcu_date;
            $lastRemark[$value->mcu_id][] = $value->mcu_remark;
        }


//        dd($expDate);
//        dd($logs);

        return view('mcu.index',[
            'employees' => $employees,
            'main' => $mcu_main,
            'expDate' => $expDate,
            'lastCheckUp'=> $lastCheckUp,
            'lastRemark' => $lastRemark,
        ]);
    }

    public function getLogMCU($id){
        $logs = Qhse_mcu_log::where('mcu_id',$id)->get();
        $mcu = Qhse_mcu_main::where('id',$id)->first();
        $employee = Hrd_employee::where('id',$mcu->emp_id)->first();

        return view('mcu.log',[
            'logs' => $logs,
            'mcu' => $mcu,
            'employee' => $employee,
        ]);
    }

    public function storeMCU(Request $request){
        $olddata = Qhse_mcu_main::where('emp_id', $request['employee'])->get();
        $countemp = count($olddata);
//        dd($countemp);
        if ($countemp < 1){
            $mcu = new Qhse_mcu_main();
            $mcu->emp_id = $request['employee'];
            $mcu->company_id = \Session::get('company_id');
            $mcu->created_at = date('Y-m-d H:i:s');
            $mcu->save();

            return redirect()->route('mcu.index');
        } else {
            return redirect()->back()->with('failed_mcu','This Employee Has Already Being Registerd To MCU Data Base !');
        }
    }

    public function storeMCULog(Request $request){
        $mculog = new Qhse_mcu_log();
        $mculog->mcu_id = $request['mcu_id'];
        $mculog->name = $request['title'];
        $mculog->uploader = Auth::user()->username;
        $mculog->upload_time = date('Y-m-d');
        $mculog->mcu_date = $request['mcu_date'];
        $mculog->mcu_remark = strip_tags($request['remarks']);
        $mculog->mcu_expired = $request['mcu_expired'];
        if ($request->hasFile('file')){
            $file = $request->file('file');
            $newFile = date('Y_m_d_H_i_s')."_attachment_mcu.".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);
            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/mcu_attachment");
            if ($upload == 1){
                $mculog->address = $hashFile;
            }
        }
        $mculog->save();

        return redirect()->route('mcu.logs',$request['mcu_id']);
    }

    public function delete($id){
        Qhse_mcu_main::where('id',$id)->delete();
        Qhse_mcu_log::where('mcu_id',$id)->delete();
        return redirect()->route('mcu.index');
    }
}
