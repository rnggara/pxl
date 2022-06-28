<?php


namespace App\Helpers;


use Session;
use App\Models\Hrd_point;
use App\Models\Pref_activity_label;
use App\Models\Preference_config;
use Illuminate\Support\Facades\DB;
use App\Models\Pref_activity_point;
use Illuminate\Support\Facades\Auth;

class ActivityConfig
{
    public static function store_point($modul, $action){
        $iModul = Pref_activity_label::where('name', $modul)->first();

        $iPoint = Pref_activity_point::where('company_id', Session::get('company_id'))
            ->where('id_modul', $iModul->id)
            ->where('action', $action)
            ->first();

            $tes = null;

        if (!empty($iPoint)){
            // save to point
            $point = new Hrd_point();
            $point->id_p = Auth::id();
            $point->gp = $iPoint->point;
            $point->keterangan = $action." ".$modul;
            $point->status = 2;
            $point->date_of_case = date("Y-m-d");
            $point->bod_approved_by = "system";
            $point->bod_approved_at = date("Y-m-d H:i:s");
            $point->created_by = "system";
            $point->company_id = Session::get('company_id');
            $point->save();
            $tes = $iPoint;
        }

        return $tes;
    }

    public static function accounting_initial(){
        $tc = "coa";
        $pref = Preference_config::where('id_company', 1)->first();
        if(!empty($pref) && !empty($pref->transaction_initial)){
            $tc = strtolower($pref->transaction_initial);
        }

        return $tc;
    }
}
