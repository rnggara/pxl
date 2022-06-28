<?php

namespace App\Http\Controllers;

use App\Models\Finance_treasury;
use App\Models\Finance_treasury_history;
use App\Models\Hrd_employee;
use App\Models\Hrd_employee_history;
use App\Models\Hrd_salary_master_appreciation;
use App\Models\Hrd_salary_master_reason;
use App\Models\Hrd_salary_master_severance;
use App\Models\Hrd_severance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class HrdSeveranceController extends Controller
{
    function index(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $users = Hrd_employee::whereIn('company_id', $id_companies)
            ->orderBy('emp_name', 'asc')
            ->get();

        $empsal = array();
        $empdata = array();
        foreach ($users as $item){
            $empsal[$item->id] = base64_decode($item->salary);
            $empdata[$item->id] = $item;
        }

        $reason = Hrd_salary_master_reason::all();
        $reasons = array();
        foreach ($reason as $item){
            $reasons[$item->id] = $item;
        }
        $app = Hrd_salary_master_appreciation::all();
        $severance = Hrd_salary_master_severance::all();

        $emp = Hrd_employee_history::all();
        $act_date = array();
        foreach ($emp as $item){
            $act_date[$item->emp_id] = date('m/d/Y', strtotime($item->act_date));
        }

        $treasures = Finance_treasury::whereIn('company_id', $id_companies)->get();

        $data_severance = Hrd_severance::whereIn('company_id', $id_companies)->get();


        return view('severance.index', [
            'users' => $users,
            'reasons' => $reason,
            'reas' => $reasons,
            'act_date' => $act_date,
            'appreciation' => $app,
            'severance' => $severance,
            'empsal' => $empsal,
            'data' => $data_severance,
            'empdata' => $empdata,
            'treasures' => $treasures
        ]);
    }

    function add(Request $request){
        $sev = new Hrd_severance();
        $sev->emp_id = $request->emp_id;
        $sev->act_date = date('Y-m-d', strtotime($request->emp_in));
        $sev->sev_date = $request->sev_date;
        $sev->id_reasons = $request->reasons;
        $sev->sev_amount = $request->emp_sev;
        $sev->app_amount = $request->emp_app;
        $sev->add_out_salary = $request->emp_out_salary;
        $sev->add_thr = $request->emp_thr;
        $sev->add_bonus = $request->emp_bonus;
        $sev->add_others = $request->emp_others;
        $sev->deduc_loan = $request->emp_loan;
        $sev->deduc_union = $request->emp_union;
        $sev->deduc_others = $request->emp_deduc_others;
        $sev->created_by = Auth::user()->username;
        $sev->company_id = Session::get('company_id');
        $sev->save();

        return redirect()->route('severance.index');
    }

    function approve(Request $request){
        $sev = Hrd_severance::find($request->id_sev);
        $sev->act_date = date('Y-m-d', strtotime($request->emp_in));
        $sev->sev_date = $request->sev_date;
        $sev->sev_amount = $request->emp_sev;
        $sev->app_amount = $request->emp_app;
        $sev->add_out_salary = $request->emp_out_salary;
        $sev->add_thr = $request->emp_thr;
        $sev->add_bonus = $request->emp_bonus;
        $sev->add_others = $request->emp_others;
        $sev->deduc_loan = $request->emp_loan;
        $sev->deduc_union = $request->emp_union;
        $sev->deduc_others = $request->emp_deduc_others;
        $sev->approved_by = Auth::user()->username;
        $sev->approved_at = date('Y-m-d H:i:s');
        $sev->save();

        $emp_his = new Hrd_employee_history();
        $emp_his->emp_id = $request->emp_id;
        $emp_his->activity = "dismiss";
        $emp_his->act_date = $request->sev_date;
        $emp_his->act_by = Auth::user()->username;
        $emp_his->save();

        $emp = Hrd_employee::find($request->emp_id);
        $emp->expel = $request->sev_date;
        $emp->save();

        $tre_his = new Finance_treasury_history();
        $tre_his->id_treasure = $request->treasury;
        $tre_his->date_input = date('Y-m-d');
        $tre_his->description = "[Severance] ".$emp->emp_name;
        $tre_his->IDR = $request->sev_total * -1;
        $tre_his->PIC = Auth::user()->username;
        $tre_his->save();

        return redirect()->route('severance.index');
    }

    function delete($id){
        if (Hrd_severance::find($id)->delete()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function print($id){
        $severance = Hrd_severance::find($id);
        $emp = Hrd_employee::find($severance->emp_id);
        $reason = Hrd_salary_master_reason::find($severance->id_reasons);


        return view('severance.print', [
            'severance' => $severance,
            'emp' => $emp,
            'reason' => $reason
        ]);
    }
}
