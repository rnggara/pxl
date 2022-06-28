<?php

namespace App\Http\Controllers;

use DB;
use Session;
use Carbon\Carbon;
use App\Models\Hrd_employee;
use App\Models\Hrd_overtime;
use Illuminate\Http\Request;
use App\Models\Rms_divisions;
use Illuminate\Support\Facades\Auth;

class HrdOvertimeController extends Controller
{

    public function index(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $now = Carbon::now();
        $months = array();
        for ($i = 0; $i < 12; $i++) {
            $timestamp = mktime(0, 0, 0, date('n') - $i, 1);
            $months[date('n', $timestamp)] = date('F', $timestamp);
        }
        ksort($months);

        $currentMonth = $now->month;
        $yearnow = $now->year;

        $divisions = Rms_divisions::whereIn('id_company', $id_companies)
            ->where('name','not like','%admin%')
            ->get();
        $divName = [];
        foreach ($divisions as $key => $val){
            $divName['name'][$val->id] = $val->name;
        }
//        dd($divName);

        $range1 = date("Y-m-d", mktime(0,0,0,$currentMonth-1,16,$yearnow));
        $range2 = date("Y-m-d", mktime(0,0,0,$currentMonth,15,$yearnow));

        $employees = Hrd_employee::join('hrd_employee_type as type','type.id','=','hrd_employee.emp_type')
            ->select('hrd_employee.*', 'type.name as empType')
            ->orderBy('emp_name')
            ->whereNull('expel')
            ->get();

        $overtimes = Hrd_overtime::whereIn('company_id', $id_companies)
            ->whereBetween('ovt_date',[$range1,$range2])->get();
        return view('overtime.index',[
            'employees' => $employees,
            'overtimes' => $overtimes,
            'now' => $now,
            'months' => $months,
            's_month' => $currentMonth,
            's_year' => $yearnow,
            'divName' => $divName,
        ]);
    }

    public function getOvertime(Request $request){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $now = Carbon::now();
        $months = array();
        for ($i = 0; $i < 12; $i++) {
            $timestamp = mktime(0, 0, 0, date('n') - $i, 1);
            $months[date('n', $timestamp)] = date('F', $timestamp);
        }
        ksort($months);

        //===================================

        $currentMonth = $request['month'];
        $yearnow = $request['year'];

        $range1 = date("Y-m-d", mktime(0,0,0,$currentMonth-1,16,$yearnow));
        $range2 = date("Y-m-d", mktime(0,0,0,$currentMonth,15,$yearnow));

        $employees = Hrd_employee::leftJoin('hrd_employee_type as type','type.id','=','hrd_employee.emp_type')
            ->select('hrd_employee.*', 'type.name as empType')
            ->whereNull('expel')
            ->get();

        $divisions = Rms_divisions::whereIn('id_company', $id_companies)
            ->where('name','not like','%admin%')
            ->get();
        $divName = [];
        foreach ($divisions as $key => $val){
            $divName['name'][$val->id] = $val->name;
        }
        $overtimes = Hrd_overtime::whereIn('company_id', $id_companies)
            ->whereBetween('ovt_date',[$range1,$range2])->get();

        return view('overtime.show_list',[
            'employees' => $employees,
            'overtimes' => $overtimes,
            'now' => $now,
            'months' => $months,
            's_month' => $currentMonth,
            's_year' => $yearnow,
            'divName' => $divName,
        ]);
    }

    public function getDetail($id,$year,$month){
        $emp = Hrd_employee::where('id', $id)
            ->whereNull('expel')
            ->first();
        $max_col1 = date("t", mktime(0,0,0,$month -1,1,$year));
        $max_col2 = date("t", mktime(0,0,0,$month,1,$year));
        $range1 = date("Y-m-d", mktime(0,0,0,$month-1,16,$year));
        $range2 = date("Y-m-d", mktime(0,0,0,$month,15,$year));

        $ovt = Hrd_overtime::where('emp_id',$id)
            ->where('company_id', \Session::get('company_id'))
            ->whereBetween('ovt_date',[$range1,$range2])
            ->get();
        if (count($ovt) > 0){
            for ($i = 0; $i< count($ovt); $i++){
                $indexOvt = date("Ymd", strtotime($ovt[$i]->ovt_date));
                $overtime[$indexOvt] = $ovt[$i]->time_in;
                $overtimeOut[$indexOvt] = $ovt[$i]->time_out;
                $idovt[$indexOvt] = $ovt[$i]->id;
            }
            return view('overtime.show_detail',[
                'emp' => $emp,
                'month' => $month,
                'year' => $year,
                'max_col1' => $max_col1,
                'max_col2' => $max_col2,
                'indexOvt' => $indexOvt,
                'overtime' => $overtime,
                'overtimeOut' => $overtimeOut,
                'idovt' => $idovt,
            ]);
        } else {
            return view('overtime.show_detail',[
                'emp' => $emp,
                'month' => $month,
                'year' => $year,
                'max_col1' => $max_col1,
                'max_col2' => $max_col2,
            ]);
        }

    }

    public function storeOvertime(Request $request){
        $id = $request['id_emp'];
        $out = $request['overtimeout'];
        $id_ov = $request['id_ovt'];
        foreach($request['overtime'] as $k => $v) {
            if ($v) {
                if (!empty($id_ov[$k])) {
                    Hrd_overtime::where('id',$id_ov[$k])
                        ->update([
                            'time_in' => $v,
                            'time_out' =>$out[$k]
                        ]);
                } else {
                    $ovtIn = Hrd_overtime::where('emp_id', $id)
                        ->where('ovt_date', $k)
                        ->where('time_in', $v)
                        ->first();
                    if(empty($ovtIn)){
                        $ovt = new Hrd_overtime();
                        $ovt->emp_id = $id;
                        $ovt->ovt_date = $k;
                        $ovt->time_in = $v;
                        $ovt->time_out = $out[$k];
                        $ovt->company_id = Session::get('company_id');
                        $ovt->created_by = Auth::user()->username;
                        $ovt->save();
                    }
                    // DB::insert("INSERT INTO hrd_overtime (emp_id, ovt_date, time_in, time_out, company_id) VALUES ('" . $id . "','" . $k . "','" . $v . "', '". $out[$k] ."','".\Session::get('company_id')."') ON DUPLICATE KEY UPDATE time_in = '" . $v . "'");
                }
            } else {
                Hrd_overtime::where('emp_id',$id)
                    ->where('ovt_date',$k)->delete();
            }
        }
        return redirect()->back();
    }
}
