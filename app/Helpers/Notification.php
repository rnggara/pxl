<?php

namespace App\Helpers;

use App\Models\Action;
use App\Models\Module;
use App\Models\Notification_log;
use App\Models\Notification_text;
use App\Models\UserPrivilege;
use Illuminate\Support\Facades\Auth;
use Session;

class Notification
{
    public static function save($data){
        $action = Action::where('name', $data['action'])->first();
        $module = Module::where('name', $data['module'])->first();
        $user_priv = UserPrivilege::where('id_rms_modules', $module->id)
            ->where('id_rms_actions', $action->id)
            ->get();
        $id_users = array();
        foreach ($user_priv as $key => $value) {
            $id_users[] = "".$value->id_users."";
        }

        if ($action->name == "approvedir") {
            $txtAct = " Approval";
        } else {
            $txtAct = " Action";
        }

        if (isset($data['action_prev']) && !empty($data['action_prev'])){
            $action_prev = Action::where('name', $data['action_prev'])->first();
            if (isset($data['module_prev'])){
                $module_prev = Module::where('name', $data['module_prev'])->first();
                $id_modul = $module_prev->id;
            } else {
                $id_modul = $module->id;
            }

            if (isset($data['id_prev'])) {
                $id_prev = $data['id_prev'];
            } else {
                $id_prev = $data['id'];
            }
            $notif = Notification_log::where('id_module', $id_modul)
                ->where('id_action', $action_prev->id)
                ->where('id_item', $id_prev)
                ->first();
            if (!empty($notif)) {
                $notif->action_at = date('Y-m-d H:i:s');
                $notif->action_by = Auth::user()->username;
                $notif->save();
            } else {
                $newnotif = new Notification_log();
                $newnotif->text = $data['paper'].", ".strtoupper($module->name)." need".$txtAct;
                $newnotif->id_item = $data['id'];
                $newnotif->id_users = json_encode($id_users);
                $newnotif->id_module = $id_modul;
                $newnotif->id_action = $action->id;
                $newnotif->url = $data['url'];
                $newnotif->item_type = $data['module'];
                $newnotif->created_by = Auth::user()->username;
                $newnotif->company_id = Session::get('company_id');
                $newnotif->save();
            }
        }

        if (!isset($data['last'])) {
            $newnotif = new Notification_log();
            $newnotif->text = $data['paper'].", ".strtoupper($module->name)." need".$txtAct;
            $newnotif->id_item = $data['id'];
            $newnotif->id_users = json_encode($id_users);
            $newnotif->id_module = $module->id;
            $newnotif->id_action = $action->id;
            $newnotif->url = $data['url'];
            $newnotif->item_type = $data['module'];
            $newnotif->created_by = Auth::user()->username;
            $newnotif->company_id = Session::get('company_id');
            $newnotif->save();
        }
    }

    public static function update($data){
        $action = Action::where('name', $data['action'])->first();
        $module = Module::where('name', $data['module'])->first();
        $notif = Notification_log::where('id_module', $module->id)
            ->where('id_action', $action->id)
//            ->where('id_item', $data['id'])
//            ->where('url', $data['url'])
            ->get();
        dd($action->id);

        $notif->read_at = date('Y-m-d H:i:s');
        $notif->read_by = Auth::user()->username;
        $notif->save();
    }
}
