<?php

namespace App\Http\Controllers\Api;

use App\Models\Asset_wh;
use App\Models\Hrd_employee;
use Illuminate\Http\Request;
use App\Models\Marketing_project;
use App\Models\General_travel_order;
use App\Http\Controllers\Api\BaseController;

class MapController extends BaseController
{
    function index(){
        $project = Marketing_project::whereRaw('(longitude is not null and longitude != 0)')
            ->whereRaw('(latitude is not null and latitude != 0)')
            ->get(['id', 'prj_name', 'longitude', 'latitude', 'company_id', 'address']);

        $marker = [];

        $error = 0;

        if($project){
            foreach ($project as $key => $value) {
                $row = [];
                $row['id'] = $value->id;
                $row['name'] = $value->prj_name;
                $row['longitude'] = $value->longitude;
                $row['latitude'] = $value->latitude;
                $row['company_id'] = $value->company_id;
                $row['type'] = "project";
                $row['address'] = $value->address;
                $marker[] = $row;
            }
        } else {
            $error = 1;
        }

        $wh = Asset_wh::whereNotNull('longitude')
            ->whereNotNull('latitude')
            ->get(['id', 'name', 'longitude', 'latitude', 'company_id', 'address']);

        if($wh){
            foreach ($wh as $key => $value) {
                $row = [];
                $row['id'] = $value->id;
                $row['name'] = $value->name;
                $row['longitude'] = $value->longitude;
                $row['latitude'] = $value->latitude;
                $row['company_id'] = $value->company_id;
                $row['type'] = "storage";
                $row['address'] = $value->address;
                $marker[] = $row;
            }
        } else {
            $error = 1;
        }

        if($error == 0){
            return $this->sendResponse($marker, 'Success');
        } else {
            return $this->sendError('failed');
        }
    }

    function view($type, $id){
        if($type == "project"){
            $prj = Marketing_project::find($id);
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

            $emp = Hrd_employee::whereIn('id', $list_emp)->orderBy('emp_name')->get(['id', 'emp_name']);
        } else {
            $project = Asset_wh::find($id);
            $emp = Hrd_employee::where('id_wh', $id)->orderBy('emp_name')->get(['id', 'emp_name']);
        }

        if($emp){
            return $this->sendResponse($emp, 'Success');
        } else {
            return $this->sendError('failed');
        }
    }
}
