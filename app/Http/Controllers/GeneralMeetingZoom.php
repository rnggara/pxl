<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\General_meeting_zoom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\General_meeting_zoom_participant;

class GeneralMeetingZoom extends Controller
{
    function store(Request $request){
        $meeting = new General_meeting_zoom();
        $meeting->description = $request->description;
        $meeting->meeting_date = $request->date;
        $meeting->meeting_time = $request->start_time;
        $meeting->link_zoom = $request->link;
        $meeting->created_by = Auth::user()->username;
        $meeting->company_id = Session::get("company_id");
        if($meeting->save()){
            $participant = new General_meeting_zoom_participant();
            $participant->meeting_id = $meeting->id;
            $participant->user_id = Auth::id();
            $participant->company_id = $meeting->company_id;
            $participant->save();
        }

        return redirect()->back();
    }

    function join(Request $request){
        if($request->checked){
            $participant = new General_meeting_zoom_participant();
            $participant->meeting_id = $request->id_meeting;
            $participant->user_id = $request->user_id;
            $participant->company_id = Session::get("company_id");
            $participant->save();

            $meeting = General_meeting_zoom::find($request->id_meeting);
            $data = [
                "success" => 1,
                "link" => $meeting->link_zoom
            ];
        } else {
            $participant = General_meeting_zoom_participant::where("meeting_id", $request->id_meeting)
                ->where("user_id", $request->user_id)->delete();
            $data = [
                "success" => 1,
                "link" => ""
            ];
        }

        return json_encode($data);
    }

    function get_detail($id){
        $meeting = General_meeting_zoom::find($id);

        $participant = General_meeting_zoom_participant::where('meeting_id', $id)
            ->leftJoin("users", 'general_meeting_zoom_participant.user_id', 'users.id')
            ->get();

        $isJoin = General_meeting_zoom_participant::where('meeting_id', $id)->where("user_id", Auth::id())->first();

        return view("zoom.view", compact("meeting", "participant", "isJoin"));
    }
}
