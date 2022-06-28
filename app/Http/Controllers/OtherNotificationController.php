<?php

namespace App\Http\Controllers;

use App\Models\Notification_rules;
use App\Models\RoleDivision;
use Illuminate\Http\Request;
use Session;

class OtherNotificationController extends Controller
{
    function index(){
        $position = RoleDivision::where('id_company', Session::get('company_id'))->get();
        $notifications = Notification_rules::all();

        return view('other.notification.index', [
            'positions' => $position,
            'notifications' => $notifications
        ]);
    }

    function check_code(Request $request){
        $hasCode = Notification_rules::where('notification_code', strtoupper($request->code))->get();
        $data['data'] = $hasCode;
        $data['count'] = count($hasCode);
        return json_encode($data);
    }

    function add(Request $request){
//        dd($request);
        $notif = new Notification_rules();
        $notif->notification_code = strtoupper($request->code);
        $notif->description = $request->description;
        $notif->receivers = json_encode($request->receiver);
        $notif->save();

        return redirect()->route('other.notif.index');
    }

    function update(Request $request){
        $notif = Notification_rules::find($request->id_notif);
        $notif->notification_code = strtoupper($request->code);
        $notif->description = $request->description;
        $notif->receivers = json_encode($request->receiver);
        $notif->save();

        return redirect()->route('other.notif.index');
    }

    function delete($id){
        if (Notification_rules::find($id)->delete()){
            $data['error'] = 0;
        } else {
            $data['error'] = 0;
        }

        return json_encode($data);
    }
}
