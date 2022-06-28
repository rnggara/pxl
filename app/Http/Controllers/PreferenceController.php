<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Asset_wh;
use App\Models\Division;
use App\Models\Asset_item;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\Preference_ppe;
use App\Models\Template_files;
use App\Models\Finance_br_config;
use App\Models\Preference_config;
use Illuminate\Support\Facades\DB;
use App\Models\Pref_activity_point;
use Illuminate\Support\Facades\Auth;
use App\Models\Pref_work_environment;

class PreferenceController extends Controller
{
    public function index($id_company){
        $id = base64_decode($id_company);

        $isPref = Preference_config::where('id_company', $id)->first();
        if ($isPref == null){
            $newPref = new Preference_config();
            $comp = ConfigCompany::find($id);
            if ($comp->id_parent != null){
                $parentPref = Preference_config::where('id_company', $comp->id_parent)->first();
                $newPref->penalty_amt = $parentPref->penalty_amt;
                $newPref->penalty_period = $parentPref->penalty_period;
                $newPref->penalty_start = $parentPref->penalty_start;
                $newPref->penalty_stop = $parentPref->penalty_stop;
                $newPref->period_start = $parentPref->period_start;
                $newPref->period_end = $parentPref->period_end;
                $newPref->absence_deduction = $parentPref->absence_deduction;
                $newPref->bonus_period = $parentPref->bonus_period;
                $newPref->thr_period = $parentPref->thr_period;
                $newPref->odorate = $parentPref->odorate;
                $newPref->overtime_period = $parentPref->overtime_period;
                $newPref->overtime_start = $parentPref->overtime_start;
                $newPref->overtime_amt = $parentPref->overtime_amt;
                $newPref->performa_period = $parentPref->performa_period;
                $newPref->performa_start = $parentPref->performa_start;
                $newPref->performa_end = $parentPref->performa_end;
                $newPref->performa_amt1 = $parentPref->performa_amt1;
                $newPref->performa_amt2 = $parentPref->performa_amt2;
                $newPref->performa_amt3 = $parentPref->performa_amt3;
                $newPref->performa_amt4 = $parentPref->performa_amt4;
                $newPref->performa_amt5 = $parentPref->performa_amt5;
                $newPref->approval_start = $parentPref->approval_start;
                $newPref->btl_col = $parentPref->btl_col;
                $newPref->wo_signature = null;
                $newPref->po_signature = null;
                $newPref->to_signature = null;
                $newPref->id_company = $id;
                $newPref->save();
            }
        }

        $preferences = Preference_config::where('id_company', $id)->first();
        $template_files = Template_files::where('company_id', $id)->get();


       $br = Division::all();
       $br_config = Finance_br_config::all();
       $br_status = array();
       foreach ($br_config as $item){
           $br_status[$item->id_division] = $item;
       }

        $company = ConfigCompany::where('id', $id)->first();

        $label = DB::table('pref_activity_label')->get();
        $action = DB::table('pref_activity_action')->get();

        $pref_action_point = Pref_activity_point::where('company_id', $company->id)->get();
        $data_point = array();
        foreach ($pref_action_point as $item){
            $data_point[$item->id_modul][$item->action] = $item->point;
        }


        for ($m=1; $m<=12; $m++) {
            $month[$m] = date('F', mktime(0,0,0,$m, 1, date('Y')));
        }

        $we = Pref_work_environment::where('company_id', Session::get('company_id'))->get();

        $ppe = Preference_ppe::all();
        foreach($ppe as $item){
            $js = json_decode($item->items, true);
            $row = [];
            for ($i=0; $i < count($js); $i++) {
                $_item = Asset_item::find($js[$i]);
                if(!empty($_item)){
                    $col = [];
                    $col['id'] = $js[$i];
                    $col['text'] = "[$_item->item_code] $_item->name";
                    $row[] = $col;
                }
            }
            $item->item_arr = $row;
        }

        $warehouses = Asset_wh::whereIn('company_id', [$company->id,$company->id_parent])->get();

        return view('preference.index',[
            'company' => $company,
            'preferences' => $preferences,
            'template_files' => $template_files,
            'br_list' => $br,
            'months' => $month,
            'label' => $label,
            'action' => $action,
            'data_point' => $data_point,
            'we' => $we,
            'br_pref' => $br_status,
            "ppe" => $ppe,
            'wh' => $warehouses
        ]);
    }

    public function savePref(Request $request){
        $pref = Preference_config::where('id_company',$request['id_company'])->first();
        if ($pref === null){
            if (isset($request['saveAttendance'])){
                $prefNew = new Preference_config();
                $prefNew->penalty_amt = $request['penalty_amt'];
                $prefNew->penalty_period = $request['penalty_period'];
                $prefNew->penalty_start = $request['penalty_start'];
                $prefNew->penalty_stop = $request['penalty_stop'];
                $prefNew->id_company = $request['id_company'];
                $prefNew->save();
            }
            if (isset($request['savePayrollPeriod'])){
                $prefNew = new Preference_config();
                $prefNew->period_start = $request['period_start'];
                $prefNew->period_end = $request['period_end'];
                $prefNew->period_archive = $request['period_archive'];
                $prefNew->id_company = $request['id_company'];
                $prefNew->save();
            }
            if (isset($request['saveDeduction'])){
                $prefNew = new Preference_config();
                $prefNew->absence_deduction = $request['absence_deduction'];
                $prefNew->id_company = $request['id_company'];
                $prefNew->save();
            }
        } else {
            if (isset($request['saveAttendance'])){
                Preference_config::where('id',$request['id'])
                    ->update([
                        'penalty_amt' => $request['penalty_amt'],
                        'penalty_period' => $request['penalty_period'],
                        'penalty_start' => $request['penalty_start'],
                        'penalty_stop' => $request['penalty_stop'],
                    ]);
            }
            if (isset($request['savePayrollPeriod'])){
                Preference_config::where('id',$request['id'])
                    ->update([
                        'period_start' => $request['period_start'],
                        'period_end' => $request['period_end'],
                        'period_archive' => $request['period_archive']
                    ]);
            }
            if (isset($request['saveDeduction'])){
                Preference_config::where('id',$request['id'])
                    ->update([
                        'absence_deduction' => $request['absence_deduction'],
                    ]);
            }
        }


        return redirect()->route('preference',['id_company'=>base64_encode($request['id_company'])]);
    }

    function store_pr(Request $request){
        $pref = Preference_config::where('id_company', $request->id)->first();
        $pref->performa_period = $request->performa_period;
        $pref->performa_start = $request->performa_start;
        $pref->performa_end = $request->performa_end;
        $pref->performa_amt1 = $request->performa_amt1;
        $pref->performa_amt2 = $request->performa_amt2;
        $pref->performa_amt3 = $request->performa_amt3;
        $pref->performa_amt4 = $request->performa_amt4;
        $pref->performa_amt5 = $request->performa_amt5;

        Session::put('company_performa_period', $request->performa_period);
        Session::put('company_performa_start', $request->performa_start);
        Session::put('company_performa_end', $request->performa_end);
        Session::put('company_performa_amt1', $request->performa_amt1);
        Session::put('company_performa_amt2', $request->performa_amt2);
        Session::put('company_performa_amt3', $request->performa_amt3);
        Session::put('company_performa_amt4', $request->performa_amt4);
        Session::put('company_performa_amt5', $request->performa_amt5);

        $pref->save();

        return redirect()->route('preference', base64_encode($request->id));
    }

    function store_we(Request $request){
        $pref = new Pref_work_environment();
        $pref->name = $request->name;
        $pref->tag = $request->tag;
        $pref->formula = $request->formula;
        $pref->created_by = Auth::user()->username;
        $pref->company_id = Session::get('company_id');
        $pref->save();
        return redirect()->back();
    }

    function delete_we($id){
        Pref_work_environment::find($id)->delete();
        return redirect()->back();
    }

    function find_we($id){
        $we = Pref_work_environment::find($id);
        return view('preference.working_environment_edit', compact('we'));
    }

    function update_we(Request $request){
        $pref = Pref_work_environment::find($request->id_we);
        $pref->name = $request->name;
        $pref->tag = $request->tag;
        $pref->formula = $request->formula;
        $pref->updated_by = Auth::user()->username;
        $pref->save();
        return redirect()->back();
    }

    function store_ac(Request $request){
        $point = $request->point;
        foreach ($point as $key => $item){
            foreach ($item as $keyItem => $value){
                $iPoint = Pref_activity_point::where('company_id', Session::get('company_id'))
                    ->where('id_modul', $key)
                    ->where('action', $keyItem)
                    ->first();
                if (empty($iPoint)){
                    $nPoint = new Pref_activity_point();
                    $nPoint->id_modul = $key;
                    $nPoint->action = $keyItem;
                    $nPoint->point = $value;
                    $nPoint->created_by = Auth::user()->username;
                    $nPoint->company_id = Session::get('company_id');
                    $nPoint->save();
                } else {
                    $nPoint = Pref_activity_point::find($iPoint->id);
                    $nPoint->point = $value;
                    $nPoint->updated_by = Auth::user()->username;
                    $nPoint->save();
                }
            }
        }

        return redirect()->back();
    }

    function br_update($id){
        $iConf = Finance_br_config::where('id_division', $id)->first();
        if (!empty($iConf)){
            if ($iConf->unlocked == 1){
                $iConf->unlocked = 0;
            } else {
                $iConf->unlocked = 1;
            }
            $iConf->save();
        } else {
            $conf = new Finance_br_config();
            $conf->id_division = $id;
            $conf->unlocked = 1;
            $conf->save();
        }

        return redirect()->back();
    }

    function signatureSave(Request $request){
        // dd($request);
        $pref = Preference_config::find($request->id_company);
        $data = $request->data;
        if (is_object(json_decode($pref->po_signature)) && !empty($pref->po_signature)) {
            $jsPO = json_decode($pref->po_signature);
        } else {
            $jsPO = (object) [];
        }

        if (is_object(json_decode($pref->wo_signature)) && !empty($pref->wo_signature)) {
            $jsWO = json_decode($pref->wo_signature);
        } else {
            $jsWO = (object) [];
        }
        foreach ($data as $key => $powo) {
            if (isset($powo['min'])) {
                foreach ($powo['min'] as $i => $value) {
                    if (!empty($value)) {
                        $min = str_replace(",", "", $value);
                    } else {
                        $min = "";
                    }

                    if ($key == "po") {
                        $jsPO->min[$i] = $min;
                    } else {
                        $jsWO->min[$i] = $min;
                    }
                }
            }

            if (isset($powo['max'])) {
                foreach ($powo['max'] as $i => $value) {
                    if (!empty($value)) {
                        $max = str_replace(",", "", $value);
                    } else {
                        $max = "";
                    }

                    if ($key == "po") {
                        $jsPO->max[$i] = $max;
                    } else {
                        $jsWO->max[$i] = $max;
                    }
                }
            }

            if (isset($powo['bypass'])) {
                foreach ($powo['bypass'] as $i => $value) {
                    if (!empty($value)) {
                        $bypass = 1;
                    } else {
                        $bypass = "";
                    }

                    if ($key == "po") {
                        $jsPO->bypass[$i] = $bypass;
                    } else {
                        $jsWO->bypass[$i] = $bypass;
                    }
                }
            }

            if (isset($powo['img'])) {
                foreach ($powo['img'] as $i => $value) {
                    $file = $value;
                    $upload_dir = "images/signature";
                    $file_name = "SIGNATURE_".str_replace(" ", "_", $file->getClientOriginalName());
                    if ($file->move(public_path($upload_dir), $file_name)) {
                        if ($key == "po") {
                            $jsPO->img[$i] = $file_name;
                        } else {
                            $jsWO->img[$i] = $file_name;
                        }
                    }
                }
            }
        }

        $pref->po_signature = json_encode($jsPO);
        $pref->wo_signature = json_encode($jsWO);

        if ($pref->save()) {
            return redirect()->back();
        }
    }

    function thr(Request $request){
        $pref = Preference_config::find($request->id);
        $pref->thr_period = $request->thr_period;
        $pref->save();

        if(Session::get('company_id') == $pref->id_company){
            Session::put('company_thr_period', $pref->thr_period);
        }

        return redirect()->back();
    }

    function accounting_save(Request $request){
        $pref = Preference_config::where("id_company", $request->id_comp)->first();
        $pref->transaction_name = $request->t_name;
        $pref->transaction_initial = $request->t_initial;
        $pref->save();

        Session::put('company_tc_name', $pref->transaction_name);
        Session::put('copmany_tc_initial', $pref->transaction_initial);

        return redirect()->back();
    }

    function ppe_add(Request $request){
        $ppe = Preference_ppe::find($request->id);
        if(empty($ppe)){
            $ppe = new Preference_ppe();
            $ppe->created_by = Auth::user()->username;
        } else {
            $ppe->updated_by = Auth::user()->username;
        }

        $ppe->description = $request->desc;
        $ppe->qty = $request->qty;
        $ppe->items = json_encode($request->items);

        $ppe->save();
        return redirect()->back();
    }

    function ppe_storage(Request $request){
        $pre = Preference_config::where("id_company", $request->id)->first();
        $pre->ppe_wh = $request->storage;
        $pre->save();
        return redirect()->back();
    }
}
