<?php

namespace App\Http\Controllers;

use App\Models\Hrd_employee;
use Illuminate\Http\Request;
use App\Models\General_travel_order;
use App\Models\Marketing_project;
use Illuminate\Support\Facades\Session;

class HRDCrewLocation extends Controller
{
    function index(){

        $crewOnDuty = General_travel_order::where('company_id', Session::get('company_id'))
            ->where('return_dt', '>', date("Y-m-d"))
            ->where('departure_dt', '<=', date("Y-m-d"))
            ->whereNotNull('action_time')
            ->get()->pluck('employee_id');

        $datato = General_travel_order::where('company_id', Session::get('company_id'))
            ->where('return_dt', '<', date("Y-m-d"))
            ->whereNotNull('action_time')
            ->whereNotIn('employee_id', $crewOnDuty)
            ->get();

        $project = Marketing_project::where("company_id", Session::get('company_id'))->get()->pluck('prj_name', 'id');

        $available = [];
        $prj = [];
        foreach ($datato as $key => $value) {
            $prj[$value->id] = (isset($project[$value->project])) ? $project[$value->project] : "N/A";
            $available[$value->employee_id] = $value;
        }


        $upto = General_travel_order::where('company_id', Session::get('company_id'))
            ->where('departure_dt', '>=', date("Y-m-d"))
            // ->whereNotNull('action_time')
            // ->whereNotIn('employee_id', $crewOnDuty)
            ->get();

        $next = [];
        foreach ($upto as $key => $value) {
            $prj_name = (isset($project[$value->project])) ? $project[$value->project] : "N/A";
            $value->project_name = $prj_name;
            $next[$value->employee_id][] = $value;
        }

        $kruoff = Hrd_employee::where('emp_type',2)
            ->whereNull('expel')
            ->orderBy('emp_name')
            ->get();

        return view("crewloc.hr.index", compact('kruoff', 'available', 'prj', 'next'));
    }
}
