<?php

namespace App\Http\Controllers;

use App\Models\General_travel_order_plan;
use App\Models\Marketing_project;
use Illuminate\Http\Request;
use App\Models\General_travel_order;
use App\Models\Hrd_employee_type;
use App\Models\Hrd_employee;
use DB;
use Session;

class GeneralCrewLocationController extends Controller
{
    public function index(){

        //kru off
        $spandays=[];
        $assigndate=[];
        $projectsplan = [];
        $remark = [];

        $crewOnDuty = General_travel_order::where('company_id', \Session::get('company_id'))
            ->where('return_dt', '>', date("Y-m-d"))
            ->where('departure_dt', '<=', date("Y-m-d"))
            ->whereNotNull('action_time')
            ->get()->pluck('employee_id');

        $datato = General_travel_order::where('company_id', \Session::get('company_id'))
            ->where('return_dt', '<', date("Y-m-d"))
            ->whereNotNull('action_time')
            ->whereNotIn('employee_id', $crewOnDuty)
            ->get();

        $available = [];
        foreach ($datato as $key => $value) {
            $available[$value->employee_id] = $value->return_dt;
        }

        $projects = Marketing_project::where('company_id', \Session::get('company_id'))->get();
        $kruoff = Hrd_employee::where('emp_type',2)
            ->whereNull('expel')
            ->orderBy('emp_name')
            ->get();

        $toplan = General_travel_order_plan::all();

        // foreach ($kruoff as $key => $value){
        //     foreach ($datato as $key2 => $value2){
        //         if ($value->id == $value2->employee_id){
        //             if($value2->status != 0){
        //                 $spandays[$value->id][] = $value2->return_dt;
        //             }
        //         }
        //     }
        // }
        //Local kru off
        $localkruoff = Hrd_employee::where('emp_type',8)
            ->whereNull('expel')
            ->orderBy('emp_name')
            ->get();

        $emp = Hrd_employee::where('company_id', Session::get('company_id'))->get();
        $list_emp = [];
        foreach ($emp as $key => $value) {
            $list_emp[$value->id] = $value->emp_name;
        }

        $to = General_travel_order::where('company_id', Session::get('company_id'))
            ->where('return_dt', '>=', date('Y-m-d'))
            ->where('departure_dt', '<=', date('Y-m-d'))
            ->get();
        $list_project = [];
        $det_emp = [];
        foreach ($to as $key => $value) {
            $list_project[] = $value->project;
            $det_emp[$value->project][$value->employee_id]['name'] = (isset($list_emp[$value->employee_id])) ? $list_emp[$value->employee_id] : "";
            $det_emp[$value->project][$value->employee_id]['period'][] = $value->departure_dt;
            sort($det_emp[$value->project][$value->employee_id]['period']);
        }

        $list_project = array_unique($list_project);
        $project = Marketing_project::whereIn('id', $list_project)->get();

        $theme = ["default", 'primary', 'success', 'info', 'warning', 'danger'];

        $loc = [];
        $iTheme = 0;
        foreach ($project as $key => $value) {
            $row = [];
            $row['loc'] = [];
            $row['title'] = $value->prj_name;
            $row['bg'] = $theme[$iTheme];
            $row['emp'] = (isset($det_emp[$value->id])) ? $det_emp[$value->id] : [];
            $iTheme++;
            if ($iTheme == count($theme)) {
                $iTheme = 0;
            }
            $loc[] = $row;
        }

        return view('crewloc.index',[
            'datato' => $datato,
            'projects' => $projects,
            'kruoff' => $kruoff,
            'localkruoff' => $localkruoff,
            'spandays' => $spandays,
            'to_plan' => $toplan,
            'pro_loc' => $loc,
            'available' => $available
        ]);
    }

    function maps(Request $request){
        return view('crewloc.maps');
    }

    function maps_js(Request $request){
        return view('crewloc.maps_js');
    }

    public function addToPlan(Request $request){
        $cekto_plan = General_travel_order_plan::where('emp_id',$request['emp_id'])->first();
        if ($cekto_plan === null){
            $to_plan = new General_travel_order_plan();
            $to_plan->emp_id = $request['emp_id'];
            $to_plan->assign_date = $request['date_assign'];
            $to_plan->project = $request['project'];
            $to_plan->remark = $request['remark'];
            $to_plan->created_at = date('Y-m-d H:i:s');
            $to_plan->save();
        } else {
            General_travel_order_plan::where('emp_id', $request['emp_id'])
                ->update([
                    'assign_date' => $request['date_assign'],
                    'project' => $request['project'],
                    'remark' => $request['remark'],
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }

        return redirect()->route('crewloc.index');
    }

    function markers(){
        $to = General_travel_order::where('company_id', Session::get('company_id'))
            ->where('return_dt', '>=', date('Y-m-d'))
            ->where('departure_dt', '<=', date('Y-m-d'))
            ->get();
        $list_project = [];
        foreach ($to as $key => $value) {
            $list_project[] = $value->project;
        }

        $list_project = array_unique($list_project);
        $project = Marketing_project::whereIn('id', $list_project)->get();

        $loc = [];
        foreach ($project as $key => $value) {
            $row = [];
            $row['loc'] = [];
            $row['title'] = "<a href='#' data-toggle='modal' data-target='#modalCrewLoc' onclick='listCrew(".$value->id.")'>".$value->prj_name."</a>";
            if (!empty($value->longitude) && !empty($value->latitude)) {
                $row['loc'][] = $value->longitude;
                $row['loc'][] = $value->latitude;
                $loc[] = $row;
            }
        }
        // dd($list_project, $project, $loc);

        if ($loc){
            $success = true;
            $message = "Success";
            $data = $loc;
        } else {
            $success = false;
            $message = "Failed";
            $data = "No data found";
        }

        $response = [
            "success" => $success,
            "messages" => $message,
            "data" => $data
        ];

        return json_encode($response);
    }

    function crew($id){
        $to = General_travel_order::where('project', $id)
            ->where('return_dt', '>=', date('Y-m-d'))
            ->where('departure_dt', '<=', date('Y-m-d'))
            ->get();

        $list_emp = [];
        $dep_dt = [];
        foreach ($to as $key => $value) {
            $list_emp[] = $value->employee_id;
            $dep_dt[$value->employee_id][] = $value->departure_dt;
            sort($dep_dt[$value->employee_id]);
        }

        $list_emp = array_unique($list_emp);

        $emp = Hrd_employee::whereIn('id', $list_emp)->get();

        $project = Marketing_project::find($id);

        return view('crewloc.crew', compact('to', 'project', 'emp', 'dep_dt'));
    }
}
