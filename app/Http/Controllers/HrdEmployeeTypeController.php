<?php

namespace App\Http\Controllers;

use App\Models\Hrd_employee;
use App\Models\Hrd_employee_type;
use App\Models\Module;
use Illuminate\Http\Request;

class HrdEmployeeTypeController extends Controller
{
    function store(Request $request){
        $comp_id = base64_decode($request->coid);
        $type = new Hrd_employee_type();
		$type->name = $request->name;
        $no_probation = [];
        if(isset($request->no_probation)){
            $probation[$comp_id] = "1";
            $no_probation[] = $probation;
        } else {
            $probation[$comp_id] = "0";
            $no_probation[] = $probation;
        }

        $with_voucher = [];
        if(isset($request->with_voucher)){
            $voucher[$comp_id] =  "1";
            $with_voucher[] = $voucher;
        } else {
            $voucher[$comp_id] =  "0";
            $with_voucher[] = $voucher;
        }

        $with_bonus = [];
        if(isset($request->with_bonus)){
            $bonus[$comp_id] =  "1";
            $with_bonus[] = $bonus;
        } else {
            $bonus[$comp_id] =  "0";
            $with_bonus[] = $bonus;
        }

        $disable_thr = [];
        if(isset($request->disable_thr)){
            $thr[$comp_id] = "1";
            $disable_thr[] = $thr;
        } else {
            $thr[$comp_id] = "0";
            $disable_thr[] = $thr;
        }

        $tc_id = [];
        if(!empty($request->tc_id) && $request->tc_id != ""){
            $tc[$comp_id] = $request->tc_id;
            $tc_id[] = $tc;
        } else {
            $tc[$comp_id] = 0;
            $tc_id[] = $tc;
        }

        $type->no_probation = json_encode($no_probation);
        $type->with_voucher = json_encode($with_voucher);
        $type->with_bonus = json_encode($with_bonus);
        $type->disable_thr = json_encode($disable_thr);
        $type->tc_id = json_encode($tc_id);
        $type->company_id = $comp_id;

		$type->save();

        $module_name = "payroll_".str_replace(" ", "_", strtolower($type->name));
        $module = new Module();
        $module->name = $module_name;
		$module->desc = "payroll ".$type->name;
		$module->save();
        $type->rms_id = $module->id;
        $type->save();

		return redirect()->back();
    }

    public function update($id, Request $request)
	{
        $comp_id = base64_decode($request->coid);
		$role = Hrd_employee_type::find($id);
        $module_name = "payroll_".str_replace(" ", "_", strtolower($role->name));
        $module = Module::where('name', $module_name)->first();

		$role->name = $request->name;
        $probation = json_decode($role->no_probation, true);
        if(isset($request->no_probation)){
            $probation[$comp_id] = 1;
        } else {
            $probation[$comp_id] = 0;
        }

        $voucher = json_decode($role->with_voucher, true);
        if(isset($request->with_voucher)){
            $voucher[$comp_id] =  "1";
        } else {
            $voucher[$comp_id] =  "0";
        }

        $bonus = json_decode($role->with_bonus, true);
        if(isset($request->with_bonus)){
            $bonus[$comp_id] =  "1";
        } else {
            $bonus[$comp_id] =  "0";
        }

        $thr = json_decode($role->disable_thr, true);
        if(isset($request->disable_thr)){
            $thr[$comp_id] = "1";
        } else {
            $thr[$comp_id] = "0";
        }

        $tc_id = json_decode($role->tc_id, true);
        if(!empty($request->tc_id) && $request->tc_id != ""){
            $tc_id[$comp_id] = $request->tc_id;
        } else {
            $tc_id[$comp_id] = 0;
        }

        $role->no_probation = json_encode($probation);
        $role->with_voucher = json_encode($voucher);
        $role->with_bonus = json_encode($bonus);
        $role->disable_thr = json_encode($thr);
        $role->tc_id = json_encode($tc_id);
		$role->save();

        $new_module_name = "payroll_".str_replace(" ", "_", strtolower($role->name));
        $module->name = $new_module_name;
        $module->desc = "payroll ".$role->name;
        $module->save();

		return redirect()->back();
	}

	public function delete($id, Request $request)
	{
        $comp_id = base64_decode($request->coid);
		$type = Hrd_employee_type::find($id);
        // $module_name = "payroll_".str_replace(" ", "_", strtolower($type->name));
        // $module = Module::where('name', $module_name)->first();
        // $module->delete();

        $emp = Hrd_employee::where('emp_type', $id)
            ->whereNull('expel')
            ->where('company_id', $comp_id)
            ->first();

        if(!empty($emp)){
            return redirect()->back()->with('msg', 'employee found');
        }

        if($comp_id == $type->company_id){
            $type->delete();
        } else {
            $exclude = json_decode($type->company_exclude, true);
            $exclude[$comp_id] = "deleted";
            $type->company_exclude = json_encode($exclude);
            $type->save();
        }

		return redirect()->back();
	}
}
