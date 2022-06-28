<?php

namespace App\Http\Controllers;

use App\Models\Hrd_salary_history;
use Illuminate\Http\Request;
use App\Models\Hrd_employee;
use App\Models\Hrd_employee_type;
use Illuminate\Support\Facades\Auth;
use Session;

class SalaryListController extends Controller
{
    public function needsec(){
        return view('ha.salarylist.needsec');
    }

    public function submitNeedsec(Request $request){
        $this->validate($request,[
            'searchInput' => 'required'
        ]);
        if ($request['searchInput'] == 'koi999'){
            Session::put('seckey_sallist', 99);
            return redirect()->back()->with('message_needsec_sallist_success', 'Access Granted! Please re-access the salary list detail page');
        } else {
            return redirect()->back()->with('message_needsec_sallist_fail', 'Access Denied! Please enter the correct code');
        }
    }

    public function index(){
        $type = Hrd_employee_type::all();
        $employees = Hrd_employee::where('company_id',\Session::get('company_id'))
            ->whereNull('expel')
            ->get();
        return view('ha.salarylist.index',[
            'types' => $type,
            'employees' => $employees,
        ]);
    }

    public function save(Request $request){
        $empKeys = array_keys($request['ID']);
//        print_r($empKeys);
        for($sv = 0; $sv < count($empKeys); $sv++){
            $idProcess = $empKeys[$sv];
            if (isset($request['checkedit'][$idProcess])){
                $id_emp =  $request['checkedit'][$idProcess];
                if ($id_emp > 0){
                    $TSAL = $_POST['BASIC'][$idProcess];
//                echo $TSAL.'<br>';
                    $SAL = intval(round(($TSAL*0.4),2));
                    $TRANSPORT = intval(round(($TSAL*15/100),2));
                    $MEAL = intval(round(($TSAL*20/100),2));
                    $HEALTH = intval(round(($TSAL*15/100),2));
                    $HOUSE = intval(round(($TSAL*10/100),2));

                    $SAL = base64_encode($SAL);
                    $TRANSPORT = base64_encode($TRANSPORT);
                    $MEAL = base64_encode($MEAL);
                    $HOUSE = base64_encode($HOUSE);
                    $HEALTH = base64_encode($HEALTH);


                    $PENSION = ($request['PENSION'][$idProcess] != null) ?$request['PENSION'][$idProcess]:0;
//                echo $PENSION.'<br>';
                    $HEALTH_I = ($request['HEALTH_I'][$idProcess] != null) ?$request['HEALTH_I'][$idProcess]:0;
                    $JAMSOSTEK = ($request['JAMSOSTEK'][$idProcess] != null) ?$request['JAMSOSTEK'][$idProcess]:0;
                    $OVERTIME = ($request['OVERTIME'][$idProcess] != null) ?$request['OVERTIME'][$idProcess]:0;
                    $FLD_BONUS = ($request['FLD_BONUS'][$idProcess] != null) ?$request['FLD_BONUS'][$idProcess]:0;
                    $ODO_BONUS = ($request['ODO_BONUS'][$idProcess] != null) ?$request['ODO_BONUS'][$idProcess]:0;
                    $WH_BONUS = ($request['WH_BONUS'][$idProcess] != null) ?$request['WH_BONUS'][$idProcess]:0;
                    $VOUCHER = ($request['VOUCHER'][$idProcess] != null) ?$request['VOUCHER'][$idProcess]:0;
                    $ALLOWANCE = ($request['ALLOWANCE'][$idProcess] != null) ?$request['ALLOWANCE'][$idProcess]:0;
                    $YEARLY = ($request['YEARLY'][$idProcess] != null) ?$request['YEARLY'][$idProcess]:0;
                    $FX_YEARLY = ($request['FX_YEARLY'][$idProcess] != null) ?$request['FX_YEARLY'][$idProcess]:0;
                    $THR = ($request['THR'][$idProcess] != null) ?$request['THR'][$idProcess]:0.00;
                    $ID = $request['ID'][$idProcess];

                    Hrd_employee::where('id', $ID)
                        ->update([
                            'salary' => $SAL,
                            'transport' => $TRANSPORT,
                            'meal' => $MEAL,
                            'house'=> $HOUSE,
                            'health' => $HEALTH,
                            'pension' => $PENSION,
                            'health_insurance' => $HEALTH_I,
                            'jamsostek' => $JAMSOSTEK,
                            'fld_bonus' => $FLD_BONUS,
                            'odo_bonus' => $ODO_BONUS,
                            'wh_bonus' => $WH_BONUS,
                            'overtime' => $OVERTIME,
                            'voucher' => $VOUCHER,
                            'allowance_office' => $ALLOWANCE,
                            'yearly_bonus' => $YEARLY,
                            'fx_yearly_bonus' => $FX_YEARLY,
                            'thr' => $THR
                        ]);

                    $sal_history = new Hrd_salary_history();
                    $sal_history->user = Auth::user()->username;
                    $sal_history->target = $ID;
                    $sal_history->basic = $TSAL;
                    $sal_history->voucher = $VOUCHER;
                    $sal_history->position = $ALLOWANCE;
                    $sal_history->execute_time = date('Y-m-d H:i:s');
                    $sal_history->created_at = date('Y-m-d H:i:s');
                    $sal_history->save();
                }
            }

        }

        return redirect()->route('salarylist.index');
    }

    public function getSalaryHistory($id){
        $employee = Hrd_employee::where('id', $id)->first();
        $history = Hrd_salary_history::where('target',$employee->id)
            ->orderBy('execute_time','DESC')
            ->get();

        return view('ha.salarylist.history',[
            'emp' => $employee,
            'histories' => $history,
        ]);
    }

    public function reset(Request $request){
        if (isset($request['thr'])){
            Hrd_employee::select('*')
                ->update([
                    'thr' => 0
                ]);
        }
        if (isset($request['bonus'])){
            Hrd_employee::select('*')
                ->update([
                    'yearly_bonus' => 0,
                    'fx_yearly_bonus' => 0,
                ]);
        }
        return redirect()->route('salarylist.index');
    }

    public function generateTHR(Request $request){
        if (isset($request['genthr'])){
            $employees = Hrd_employee::whereNull('expel')->get();
            foreach ($employees as $key=>$val){
                $arr = array('K','C');

                $nik = str_replace($arr,'',$val->emp_id);
                $datenik = substr($nik,-10,8);
                $year = $datenik[4].$datenik[5].$datenik[6].$datenik[7];
                $month = $datenik[2].$datenik[3];
                $day = $datenik[0].$datenik[1];
                $redatenik = $year."-".$month."-".$day;
                $x = (( strtotime(date("Y-m-15")) - strtotime($redatenik))/86400);
                $month_start_int = intval($month);
                $year_start_int = intval($year);

                if(($month_start_int + 1) > 12) {
                    $year_start_2 = $year_start_int + 1;
                    $month_start2 = 1;
                } else {
                    $year_start_2 = $year_start_int;
                    $month_start2 = $month_start_int + 1;
                }
                if(($month_start2 + 1) > 12) {
                    $year_start_3 = $year_start_2 + 1;
                    $month_start3 = 1;
                } else {
                    $year_start_3 = $year_start_2;
                    $month_start3 = $month_start2 + 1;
                }

                $sum_month1 = cal_days_in_month(CAL_GREGORIAN, $month_start_int, $year_start_int);
                $sum_month2 = cal_days_in_month(CAL_GREGORIAN, $month_start2, $year_start_2);
                $sum_month3 = cal_days_in_month(CAL_GREGORIAN, $month_start3, $year_start_3);

                if($val->pro_hire == 1){
                    $effective_day = $x;
                } else {
                    $effective_day = $x - $sum_month1 - $sum_month2 - $sum_month3;
                    if($effective_day < 0) {
                        $effective_day = 0;
                    }
                }
                if($effective_day > 0) {
                    $proportional = round(($effective_day / 365),2);
                    if($proportional >= 1){
                        $proportional = 1;
                    }
                    $month_active = 1;
                } else {
                    $proportional = 0;
                    $month_active = 0;
                }

                if($month_active > 0){
                    Hrd_employee::where('id',$val->id)
                        ->update([
                            'thr' => $proportional
                        ]);
                }

            }
        }

        return redirect()->route('salarylist.index');
    }
}
