<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Asset_wh;
use App\Models\ConfigCompany;
use App\Models\Hrd_employee;
use Illuminate\Http\Request;
use App\Models\Marketing_project;
use App\Models\General_travel_order;

class GeneralMaps extends Controller
{

    function has_child($ids, $childs, $id){
        if (isseT($childs[$id])) {
            foreach ($childs[$id] as $key => $value) {
                $ids .= ",$value";
                if (isset($childs[$value])) {
                    $ids .= $this->has_child($ids, $childs, $value);
                }
            }
        }

        return $ids;
    }

    function index(){

        $company = ConfigCompany::find(Session::get('company_id'));
        $all = ConfigCompany::all();
        $company_name = $all->pluck('company_name', 'id');
        $company_bg = $all->pluck('bgcolor', 'id');
        $childs = [];
        foreach ($all as $key => $value) {
            $childs[$value->id_parent][] = $value->id;
        }
        $ids = "";
        $comp = $this->has_child($ids, $childs, $company->id);
        $comp .= ",$company->id";
        $comp = ltrim($comp, ",");
        $arrComp = array_unique(explode(",", $comp));
        $arr = [];
        foreach($arrComp as $value){
            $arr[] = $value;
        }
        sort($arr);
        $colorPallet = ['#0000cc', '#006666', '#006699', '#6600cc', '#669900', '#99cc00', '#cc0066', '#cc9900', '#ffff33', '#33ffff', '#cc6600'];

        return view('maps.index', compact('arr', 'company_name', 'company_bg'));
    }

    function markers_crew_loc(Request $request){
        $project = Marketing_project::whereNotNull('longitude')
            ->whereNotNull('latitude')
            ->whereIn('company_id', $request->comps)
            ->get();
        $company_bg = ConfigCompany::all()->pluck('bgcolor', 'id');

        $loc = [];
        foreach ($project as $key => $value) {
            $row = [];
            $row['loc'] = [];
            $row['title'] = "<a href='#' data-toggle='modal' data-target='#modalCrewLoc' onclick='listCrew(\"crew\", ".$value->id.")'>".$value->prj_name."</a>";
            $row['bg'] = $company_bg[$value->company_id];
            $row['loc'][] = $value->longitude;
            $row['loc'][] = $value->latitude;
            $loc[] = $row;
        }

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

    function markers_office_loc(Request $request){
        $project = Asset_wh::whereNotNull('longitude')
            ->whereNotNull('latitude')
            ->whereIn('office', ['1', '2'])
            ->whereIn('company_id', $request->comps)
            ->get();
        $company_bg = ConfigCompany::all()->pluck('bgcolor', 'id');

        $loc = [];
        foreach ($project as $key => $value) {
            $row = [];
            $row['loc'] = [];
            $row['title'] = "<a href='#' data-toggle='modal' data-target='#modalCrewLoc' onclick='listCrew(\"office\",".$value->id.")'>".$value->name."</a>";
            $row['bg'] = $company_bg[$value->company_id];
            $row['loc'][] = $value->longitude;
            $row['loc'][] = $value->latitude;
            $loc[] = $row;
        }

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

    function emp_list($type, $id){
        $list_emp = [];
        $dep_dt = [];
        $emp = [];
        if($type == "crew"){
            $to = General_travel_order::where('project', $id)
            ->where('return_dt', '>=', date('Y-m-d'))
            ->where('departure_dt', '<=', date('Y-m-d'))
            ->get();

            foreach ($to as $key => $value) {
                $list_emp[] = $value->employee_id;
                $dep_dt[$value->employee_id][] = $value->departure_dt;
                sort($dep_dt[$value->employee_id]);
            }

            $list_emp = array_unique($list_emp);

            $emp = Hrd_employee::whereIn('id', $list_emp)->get();

            $project = Marketing_project::find($id);
        } else {
            $project = Asset_wh::find($id);
            $emp = Hrd_employee::where('id_wh', $id)->get();
        }

        return view('maps._crew', compact('project', 'emp', 'dep_dt', 'type'));
    }
}
