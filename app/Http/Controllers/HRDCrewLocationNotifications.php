<?php

namespace App\Http\Controllers;

use App\Models\Marketing_project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HRDCrewLocationNotifications extends Controller
{
    function index(Request $request){

        $projects = Marketing_project::where("company_id", Session::get('company_id'))
            ->whereRaw('(view is null || view = "")')
            ->get();

        if ($request->ajax()) {
            $id = $request->id;
            $days = $request->days;
            $prj = Marketing_project::find($id);
            $prj->crew_notification = $days;
            if($prj->save()){
                $response = [
                    "success" => true,
                    "message" => ""
                ];
            } else {
                $response = [
                    "success" => false,
                    "message" => "Error updating data"
                ];
            }

            return json_encode($response);
        }

        return view("crewloc.notifications.index", compact('projects'));
    }
}
