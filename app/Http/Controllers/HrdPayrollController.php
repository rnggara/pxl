<?php

namespace App\Http\Controllers;

use DB;
use Session;
use Mpdf\Mpdf;
use App\Models\Hrd_bonus;
use App\Models\Hrd_config;
use App\Models\Users_zakat;
use App\Models\Hrd_employee;
use App\Models\Hrd_overtime;
use App\Models\Hrd_sanction;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\Hrd_bonus_payment;
use App\Models\Hrd_employee_loan;
use App\Models\Hrd_employee_type;
use App\Models\Preference_config;
use App\Models\Hrd_salary_archive;
use App\Models\Hrd_salary_remarks;
use App\Models\Finance_util_salary;
use App\Models\General_travel_order;
use App\Models\Hrd_employee_history;
use Illuminate\Support\Facades\Auth;
use App\Models\Hrd_employee_loan_payment;
use App\Models\Module;
use Illuminate\Support\Facades\DB as FacadesDB;

class HrdPayrollController extends Controller
{
    function signName($loc){
        switch($loc){
            case "staff":
            case "konsultan":
            case "whbin":
            case "whcil":
                $ret[0] = "HRD Manager"; $ret[1] = "Finance Manager"; $ret[2] = "Operation Director";
                break;
            case "field":
                $ret[0] = "Operation Manager"; $ret[1] = "Operation Director"; $ret[2] = "President Director";
                break;
            case "manager":
                $ret[0] = "Finance Director"; $ret[1] = "Operation Director";
                break;
            default:
                $ret[0] = "Operation Manager"; $ret[1] = "Finance Director"; $ret[2] = "Operation Director";
                break;
        }
        return $ret;
    }

    public function tableSignature($arr){
        $var1 = "<table width='827' border='1' style='border-collapse:collapse'><tr>";
        if(count($arr) == 3) { $var2 = "<td colspan='2' align='center'>Approved By </td>";
            $var4 = "
    <td width='25%' align='center'><br /><br /><br />$arr[2]<br />Date: ____ </td>
    <td width='25%' align='center'><br /><br /><br />$arr[1]<br />Date: ____</td>";
        }
        if(count($arr) == 2) { $var2 = "<td align='center'>Approved By </td>";
            $var4 = "<td width='25%' align='center'><br /><br /><br />$arr[1]<br />Date: ____ </td>";
        }
        $var3 = "</tr><tr>";
        $var5 = "</tr></table>";
        $var6 = $var1.$var2.$var3.$var4.$var5;
        return $var6;
    }

    function index(Request $request){
        for ($m=1; $m<=12; $m++) {
            $data['month'][$m] = date('F', mktime(0,0,0,$m, 1, date('Y')));
        }
        $id_companies = [];
        $comp = ConfigCompany::find(Session::get('company_id'));
        if (empty($comp->id_parent)) {
            $childCompany = ConfigCompany::select("id")
                ->where('id_parent', $comp->id)
                ->get();
            foreach($childCompany as $ids){
                $id_companies[] = $ids->id;
            }
        } else {
            $id_companies[] = $comp->id_parent;
        }

        $id_companies[] = Session::get('company_id');

        $emp = Hrd_employee::where('company_id', Session::get('company_id'))
            ->whereNull('expel')
            ->get();

        $data['emp_type'] = [];
        foreach ($emp as $item){
            $data['emp_type'][$item->emp_type][] = $item->id;
        }

        $data['type'] = Hrd_employee_type::whereIn('company_id', $id_companies)
            ->where('company_exclude', 'not like', '%"'.$comp->id.'"%')
            ->orWhereNull("company_exclude")
            ->get();

        $startyear = date('Y', strtotime('-10 years'));
        for ($i = 0; $i < 20; $i++){
            $data['years'][$i] = $startyear;
            $startyear++;
        }

        $data['mod'] = Module::all()->pluck('name', 'id');

        return view('payroll.index', $data);
    }

    function export(Request $request){
        return view('payroll.export', [
            'type' => $request->type,
            'month' => $request->month,
            'years' => $request->years,
        ]);
    }

    function show(Request $request){
        $id_companies = Session::get('company_id');
        $t = $request->type;
        $m = $request->month;
        $y = $request->years;

        $pref = Preference_config::where('id_company', $id_companies)->get();
        $prefCount = $pref->count();
        $now = date('Y-n-d');

        if ($prefCount >0){
            $period_end = $pref[0]->period_end;
            $period_start = $pref[0]->period_start;
        } else {
            if (session()->has('company_period_end') && session()->has('company_period_start')){
                $period_end = Session::get('company_period_end');
                $period_start = Session::get('company_period_start');
            } else {
                $period_end = 27;
                $period_start = 28;
            }
        }

        $thr_period = Session::get('company_thr_period');
        if($t == "all"){
            $emp = Hrd_employee::where('expel', null)
                ->where('company_id', $id_companies)
                ->orderBy('emp_name')
                ->get();
        } else {
            $emp = Hrd_employee::where('emp_type', $t)
                ->where('expel', null)
                ->where('company_id', $id_companies)
                ->orderBy('emp_name')
                ->get();
        }

        $emp_name = [];
        $emp_pos = [];
        $emp_bank = [];
        $emp_type = [];
        $type_emp = [];
        $emp_comp = [];

        foreach ($emp as $key => $value) {
            $emp_name[$value->id] = $value->emp_name;
            $emp_pos[$value->id] = $value->emp_position;
            $emp_bank[$value->id] = $value->bank_acct;
            $emp_type[] = $value->id;
            $type_emp[$value->emp_type][] = $value->id;
            $emp_comp[$value->id] = $value->company_id;
        }

        $eType = Hrd_employee_type::all();
        $detType = [];
        foreach ($eType as $value){
            $detType[$value->id] = $value;
        }


        $emp_his = Hrd_employee_history::where('activity', 'in')
            ->where('company_id', $id_companies)
            ->get();

        foreach ($emp_his as $key => $value) {
            $act_date[$value->company_id][$value->emp_id] = $value->act_date;
        }

        $sign = $this->signName($t);

        $period_start_date = $y."-".sprintf('%02d', $m-1)."-".$period_start;
        if($m-1 == 0){
            $period_start_date = ($y-1)."-12-".$period_start;
        }
        $period_end_date = $y."-".sprintf('%02d', $m)."-".$period_end;
        $period_4 = $y."-".sprintf('%02d', $m)."-". ($period_end + 1);

        $ovt = Hrd_overtime::where('company_id', $id_companies)
            ->whereBetween('ovt_date', [$period_start_date, $period_end_date])
            ->get();
        $ovt_date = [];
        foreach ($ovt as $key => $value) {
            $time_in[$value->emp_id][] = $value->time_in;
            $time_out[$value->emp_id][] = $value->time_out;
            $ovt_date[$value->emp_id][] = $value->ovt_date;
        }

        $period['start'] = $period_start_date;
        $period['end'] = $period_end_date;

        $to = General_travel_order::whereNotNull('action_time')
            ->where(function($query) use($period){
                $query->whereRaw("(departure_dt >= '".$period['start']."' and return_dt <= '".$period['end']."')");
                $query->orWhereRaw("(return_dt >= '".$period['start']."' and departure_dt <= '".$period['end']."')");
            })
            ->where('company_id', Session::get('company_id'))
            ->where('status', 0)
            ->get();



        foreach ($to as $key => $value) {
            if ($value->departure_dt < $period_start_date){
                $d2 = date('Y-m-d', strtotime($period_start_date." -1 day"));
            } else {
                $d2 = $value->departure_dt;
            }

            if ($value->return_dt < $period_end_date){
                $d1 = $value->return_dt;
            } else {
                $d1 = $period_end_date;
            }
            // $d1 = ($value->return_dt >= $period_end_date) ? date("Y-m-d", strtotime($period_end_date." +1 day")) : $value->return_dt;
            // $d2 = ($value->departure_dt <= $period_start_date) ? date('Y-m-d', strtotime($period_start_date." -1 day")) : $value->departure_dt;

            $sum = date_diff(date_create($d1), date_create($d2));

            if ($value->travel_type == "reg") {
                if ($value->location_rate == "SWT") {
                    switch ($value->dest_type) {
                        case "fld" :
                            $fld_swt[$value->employee_id][] = $sum->format("%a");
                            break;
                    }
                } elseif ($value->location_rate == "DGR") {
                    switch ($value->dest_type) {
                        case "fld" :
                            $fld_dgr[$value->employee_id][] = $sum->format("%a");
                            break;
                    }
                } else {
                    switch ($value->dest_type) {
                        case "fld" :
                            $fld_day[$value->employee_id][] = $sum->format("%a");
                            break;
                        case "wh" :
                            $wh_day[$value->employee_id][] = $sum->format("%a");
                            break;
                    }
                }
            } elseif ($value->travel_type = "odo") {
                if (empty($value->location_rate)) {
                    if ($value->dest_type == "fld_bonus") {
                        $odo_day[$value->employee_id][] = $sum->format("%a");
                    }
                } elseif ($value->location_rate == "SWT") {
                    if ($value->dest_type == "fld_bonus") {
                        $odo_swt[$value->employee_id][] = $sum->format("%a");
                    }
                } elseif ($value->location_rate == "DGR") {
                    if ($value->dest_type == "fld_bonus") {
                        $odo_dgr[$value->employee_id][] = $sum->format("%a");
                    }
                }
            }
        }


        // dd($wh_day, $emp_name);

        $whereLoan = $y."-".sprintf("%02d", $m);

        $loan = Hrd_employee_loan::where("company_id", Session::get("company_id"))->get();
        foreach($loan as $item){
            $loanEmp[$item->emp_id][] = $item->id;
        }


        $loan_det = Hrd_employee_loan_payment::where('date_of_payment', 'like', $whereLoan."%")->get();
        foreach($loan_det as $item){
            $loanDet[$item->loan_id][] = $item->amount;
        }

        $bonus = Hrd_bonus::all();
        $bonusEmp = [];
        foreach ($bonus as $value) {
            $bonusEmp[$value->emp_id][] = $value->id;
        }

        $bonus_pay = Hrd_bonus_payment::whereBetween('date_of_payment', [$period_start_date, $period_end_date])->get();
        foreach ($bonus_pay as $value) {
            $bonusPay[$value->bonus_id][] = $value->amount;
        }

        $foot['sum_salary'] = 0;
        $foot['sum_ovt'] = 0;
        $foot['sum_fld'] = 0;
        $foot['sum_wh'] = 0;
        $foot['sum_odo'] = 0;
        $foot['sum_tk'] = 0;
        $foot['sum_ks'] = 0;
        $foot['sum_jshk'] = 0;
        $foot['sum_tot_salary'] = 0;
        $foot['sum_sunction'] = 0;
        $foot['sum_absence'] = 0;
        $foot['sum_loan'] = 0;
        $foot['sum_ded_tk'] = 0;
        $foot['sum_ded_ks'] = 0;
        $foot['sum_ded_jshk'] = 0;
        $foot['sum_bonus'] = 0;
        $foot['sum_thr'] = 0;
        $foot['sum_pph21'] = 0;
        $foot['sum_prop'] = 0;
        $foot['sum_thp'] = 0;
        $foot['sum_voucher'] = 0;
        $foot['sum_ovt'] = 0;
        $foot['sum_sanction'] = 0;

        $rangeStart = $y."-".($m-1)."-".$period_start;
        $rangeEnd = $y."-".$m."-".$period_end;
        $pro_n_day = date("t", strtotime($rangeEnd));

        $empType = Hrd_employee_type::withTrashed()->get();
        $eType = [];
        foreach ($empType as $item){
            $eType[$item->id] = $item->name;
        }

        if (empty(Session::get('company_period_archive'))) {
            $period_archive = 10;
        } else {
            $period_archive = Session::get('company_period_archive');
        }

        $p_archive = $request->years."-".$m."-".$period_archive;

        $btnArch = false;
        if(strtotime($now) > strtotime(date("Y")."-".date("n")."-".$period_archive)){
            if($request->month == date("n")){
                $btnArch = true;
            }
        }

        $config_company = ConfigCompany::find(Session::get('company_id'));

        $_t = Hrd_employee_type::find($t);

        if (strtotime($now) > strtotime($p_archive)) {
            foreach ($emp as $key => $value) {
                $archive = Hrd_salary_archive::where('emp_id', $value->id)
                    ->where('archive_period', $m."-".$y)
                    ->where('company_id', $id_companies)->first();
                if (empty($archive) || $archive == null){
                    $empid = $value->id;
                    $row = new Hrd_salary_archive();
                    $salary_emp = base64_decode($value->salary);
                    $sunction = 0;
                    $absence_deduct = 0;
                    $bonus_amt = 0;
                    $ln_amt = 0;
                    $hours = 0;

                    $sanction = Hrd_sanction::where('emp_id', $value->id)
                        ->whereNotNull('approved_by')
                        ->whereBetween('sanction_date',[$period_start_date,$period_end_date])
                        ->get();
                    foreach ($sanction as $key => $valSanc){
                        $sunction += floatval($valSanc->sanction_amount);
                    }



                    $allow_bpjs_tk = ($value->allow_bpjs_tk == "") ? 0 : $value->allow_bpjs_tk;
                    $allow_bpjs_kes = ($value->allow_bpjs_kes == "") ? 0 : $value->allow_bpjs_kes;
                    $allow_jshk = ($value->allow_jshk == "") ? 0 : $value->allow_jshk;

                    $foot['sum_tk'] += $allow_bpjs_tk;
                    $foot['sum_ks'] += $allow_bpjs_kes;
                    $foot['sum_jshk'] += $allow_jshk;

                    $deduc_bpjs_tk = ($value->deduc_bpjs_tk == "") ? 0 : $value->deduc_bpjs_tk;
                    $deduc_bpjs_kes = ($value->deduc_bpjs_kes == "") ? 0 : $value->deduc_bpjs_kes;
                    $deduc_jshk = ($value->deduc_jshk == "") ? 0 : $value->deduc_jshk;
                    $deduc_pph21 = ($value->deduc_pph21 == "") ? 0 : $value->deduc_pph21;

                    $foot['sum_ded_tk'] += $deduc_bpjs_tk;
                    $foot['sum_ded_ks'] += $deduc_bpjs_kes;
                    $foot['sum_ded_jshk'] += $deduc_jshk;


                    $sal = $salary_emp + base64_decode($value->transport) + base64_decode($value->meal) + base64_decode($value->house) + base64_decode($value->health);

                    // OVT
                    // if (!empty($time_in[$value->id])) {
                    //     for ($i=0; $i < count($time_in[$value->id]); $i++) {
                    //         $otdate = date("N", strtotime($ovt_date[$value->id][$i]));
                    //         $jamout = $time_out[$value->id][$i];
                    //         if(!empty($jamout)){
                    //             if($jamout == "00:00:00"){
                    //                 $jamout = "24:00:00";
                    //             }
                    //             if($otdate >= 6){
                    //                 $diff = strtotime($jamout) - strtotime($time_in[$value->id][$i]);
                    //                 $hours += $diff;
                    //             } else {
                    //                 $ovtStartTime = Session::get('company_penalty_stop');
                    //                 if(!empty($ovtStartTime)){
                    //                     if(strtotime($time_in[$value->id][$i]) >= strtotime($ovtStartTime)){
                    //                         $diff = strtotime($jamout) - strtotime($time_in[$value->id][$i]);
                    //                     } else {
                    //                         $diff = strtotime($jamout) - strtotime($ovtStartTime);
                    //                     }
                    //                     $hours += $diff;
                    //                 }
                    //             }
                    //         }
                    //     }
                    // }
                    if (!empty($time_in[$value->id])) {
                        for ($i=0; $i < count($time_in[$value->id]); $i++) {
                            $otdate = date("N", strtotime($ovt_date[$value->id][$i]));
                            $jamout = $time_out[$value->id][$i];
                            if(!empty($jamout)){
                                if($jamout == "00:00:00"){
                                    $jamout = "24:00:00";
                                }
                                if($otdate >= 6){
                                    $diff = strtotime($jamout) - strtotime($time_in[$value->id][$i]);
                                    $hours += $diff;
                                } else {
                                    $ovtStartTime = Session::get('company_penalty_stop');
                                    if(!empty($ovtStartTime)){
                                        if(strtotime($time_in[$value->id][$i]) >= strtotime($ovtStartTime)){
                                            $diff = strtotime($jamout) - strtotime($time_in[$value->id][$i]);
                                        } else {
                                            $jammasuk = strtotime(Session::get("company_penalty_start"));
                                            if(strtotime($time_in[$value->id][$i]) <= $jammasuk){
                                                $diff = strtotime($jamout) - strtotime($time_in[$value->id][$i]);;
                                            } else {
                                                $diff = strtotime($jamout) - strtotime($ovtStartTime);
                                            }
                                        }
                                        $hours += $diff;
                                    }
                                }
                            }
                        }
                    }

                    $ovt_total = $value->overtime * ceil(($hours / 3600));

                    $foot['sum_ovt'] += $ovt_total;
                    $whday = (empty($wh_day[$value->id])) ? "0" : array_sum($wh_day[$value->id]);
                    $fldday = (empty($fld_day[$value->id])) ? "0" : array_sum($fld_day[$value->id]);
                    $fldswtday = (empty($fld_swt[$value->id])) ? "0" : array_sum($fld_swt[$value->id]);
                    $fldgrday = (empty($fld_dgr[$value->id])) ? "0" : array_sum($fld_dgr[$value->id]);

                    $fld = $value->fld_bonus * $fldday;
                    $flddgr = ($value->fld_bonus + 25000) * $fldgrday;
                    $fldswt = ($value->fld_bonus + 50000) * $fldswtday;

                    $foot['sum_fld'] += $fld + $flddgr + $fldswt;

                    $wh = $value->wh_bonus * $whday;

                    $foot['sum_wh'] += $wh;

                    $ododay = (empty($odo_day[$value->id])) ? "0" : $odo_day[$value->id];
                    $odoswtday = (empty($odo_swt[$value->id])) ? "0" : $odo_swt[$value->id];
                    $odogrday = (empty($odo_dgr[$value->id])) ? "0" : $odo_dgr[$value->id];

                    $odo = $value->odo_bonus * $ododay;
                    $ododgr = ($value->odo_bonus + 25000) * $odogrday;
                    $odoswt = ($value->odo_bonus + 50000) * $odoswtday;

                    $foot['sum_odo'] += $odo + $ododgr + $odoswt;

                    if(isset($loanEmp[$value->id])){
                        foreach($loanEmp[$value->id] as $lEmp){
                            if (isset($loanDet[$lEmp])){
                                $ln_amt += array_sum($loanDet[$lEmp]);
                            }
                        }
                    }

                    // foreach ($loan as $keyLoan => $valueLoan) {
                    //     if ($value->id == $valueLoan->emp_id) {
                    //         foreach ($loan_det as $keyDet => $valueDet) {
                    //             if ($valueLoan->id == $valueDet->loan_id) {
                    //                 $ln_amt += $valueDet->amount;
                    //             }
                    //         }
                    //     }
                    // }

                    $foot['sum_loan'] += $ln_amt;

                    if (isset($bonusEmp[$value->id])) {
                        foreach ($bonusEmp[$value->id] as $bEmp) {
                            if (isset($bonusPay[$bEmp])) {
                                $bonus_amt += array_sum($bonusPay[$bEmp]);
                            }
                        }
                    }

                    $yearly_bonus = $value->yearly_bonus * $salary_emp + $value->fx_yearly_bonus;
                    $bonus_only = $value->yearly_bonus * $salary_emp;

                    // Datatable
                    $row->emp_id = $value->id;
                    $row->archive_period = $m."-".$y;
                    $row->salary = base64_encode($sal + $value->allowance_office);
                    $row->ovt_rate = $value->overtime;
                    $row->ovt_nom = $ovt_total;
                    $row->field_rate = $value->fld_bonus;
                    $row->field_nom = $fld;
                    $row->wh_rate = $value->wh_bonus;
                    $row->wh_nom = $wh;
                    $row->odo_rate = $value->odo_bonus;
                    $row->odo_nom = $odo;
                    $row->voucher = $value->voucher;
                    $row->deduction = $ln_amt;
                    $row->lateness = $sunction;
                    $row->bonus = $bonus_amt;
                    $isThr = sprintf("%02d", $m)."-".$y;

                    $date_join = (isset($act_date[$value->company_id][$empid])) ? $act_date[$value->company_id][$empid] : "0000-00-00";
                    $mntDiff = 0;
                    $dIn = 0;
                    if ($date_join != "0000-00-00") {
                        $dateIn = date_create($date_join);
                        $dateNow = date_create(date("Y-m-d"));
                        $diff = date_diff($dateIn, $dateNow);
                        $mntDiff = $diff->format("%y-%m");
                        $dIn = date('d', strtotime($date_join));
                    }

                    $wPeriod = explode("-", $mntDiff);

                    $mnt = 0;
                    if(count($wPeriod) > 0 && $wPeriod[0] >= 1){
                        $mnt = 12 * $wPeriod[0];
                    }

                    $mnt += end($wPeriod);

                    if($dIn > $period_start){
                        $mnt -= 1;
                    }

                    $prob = [];
                    $vcr = [];
                    $_thr = [];
                    $_bonusArr = [];
                    if(isset($detType[$value->emp_type])){
                        $prob = json_decode($detType[$value->emp_type]['no_probation'], true);
                        $vcr = json_decode($detType[$value->emp_type]['with_voucher'], true);
                        $_thr = json_decode($detType[$value->emp_type]['disable_thr'], true);
                        $_bonusArr = json_decode($detType[$value->emp_type]['with_bonus'], true);
                    }

                    $minThr = 3;
                    $maxThr = 15;

                    if(isset($prob[$value->company_id])){
                        if($prob[$value->company_id] == 1){
                            $minThr = 0;
                            $maxThr = 12;
                        }
                    } elseif (isset($prob[$config_company->id_parent])){
                        if($prob[$config_company->id_parent] == 1){
                            $minThr = 0;
                            $maxThr = 12;
                        }
                    }

                    $with_voucher = 0;
                    if(isset($vcr[$value->company_id])){
                        if($vcr[$value->company_id]){
                            $with_voucher = $value->voucher;
                        }
                    } elseif (isset($vcr[$config_company->id_parent])){
                        if($vcr[$config_company->id_parent] == 1){
                            $with_voucher = $value->voucher;
                        }
                    }

                    $with_bonus = 0;
                    if(isset($_bonusArr[$value->company_id])){
                        if($_bonusArr[$value->company_id]){
                            $with_bonus = $bonus_amt;
                        }
                    } elseif (isset($_bonusArr[$config_company->id_parent])){
                        if($_bonusArr[$config_company->id_parent] == 1){
                            $with_bonus = $bonus_amt;
                        }
                    }


                    $disable_thr = 0;
                    if(isset($_thr[$value->company_id])){
                        if($_thr[$value->company_id]){
                            $disable_thr = $_thr[$value->company_id];
                        }
                    } elseif (isset($_thr[$config_company->id_parent])){
                        if($_thr[$config_company->id_parent] == 1){
                            if(isset($_thr[$value->company_id])){
                                $disable_thr = $_thr[$value->company_id];
                            }
                        }
                    }

                    if($mnt <= $minThr){
                        $thr_num = 0;
                    } elseif($mnt > $minThr && $mnt < $maxThr){
                        $thr_num = (($mnt - $minThr) / 12) * ($sal + $value->allowance_office + $with_voucher + $with_bonus);
                    } elseif($mnt >= $maxThr) {
                        $thr_num = ($sal + $value->allowance_office + $with_voucher + $with_bonus);
                    }

                    if ($isThr == strip_tags($thr_period)){
                        $thr_total = ($disable_thr == 0) ? $thr_num : 0;
                    } else {
                        $thr_total = 0;
                    }
                    $row->thr = $thr_total;
                    $row->category = $value->emp_position;
                    $row->fld_dgr = $flddgr;
                    $row->fld_swt = $fldswt;
                    $row->odo_dgr = $ododgr;
                    $row->odo_swt = $odoswt;
                    $row->allow_bpjs_tk = $allow_bpjs_tk;
                    $row->allow_bpjs_kes = $allow_bpjs_kes;
                    $row->allow_jshk = $allow_jshk;
                    $row->deduc_bpjs_tk = $deduc_bpjs_tk;
                    $row->deduc_bpjs_kes = $deduc_bpjs_kes;
                    $row->deduc_jshk = $deduc_jshk;
                    $row->deduc_pph21 = $deduc_pph21;

                    $total_sal = $sal + $value->allowance_office + $ovt_total + $fld + $wh + $odo + $ododgr + $odoswt + $flddgr + $fldswt + $value->voucher + $value->allow_bpjs_tk + $value->allow_bpjs_kes + $value->allow_jshk;

                    $thp = $total_sal - $absence_deduct - $ln_amt - $value->deduc_bpjs_tk - $value->deduc_bpjs_kes - $value->deduc_jshk - $value->deduc_pph21;
                    $xthp = $thp - $fld - $wh - $odo - $ododgr - $odoswt - $fldswt - $flddgr;
                    $date = (isset($act_date[$value->company_id][$empid])) ? $act_date[$value->company_id][$empid] : "0000-00-00";
                    $pro_day = round((strtotime($date) - strtotime($rangeStart)) / 86400,0);
                    $in_date = $date;
                    $zero_day = (strtotime($rangeEnd) - strtotime($date)) / 86400;
                    // if($empid == 1425){
                    //     dd($pro_day, $pro_n_day, $xthp, $value->emp_name, $salary_emp);
                    // }
                    if($pro_day > 0 && $pro_day <= $pro_n_day)
                    {
                        $pro_basis = $pro_n_day;
                        $pro_decrement = ($pro_day) / $pro_basis * $xthp;
                    }
                    //kalau hari masuk = start month gaji, pengurangan = gaji = ZERO gaji.
                    elseif($pro_day == 0)
                    {
                        // $pro_decrement = $xthp;
                        if(date('d',strtotime($in_date)) == 16)
                        {
                            $pro_decrement = 0;
                        }
                        else
                        {
                            $pro_decrement = $xthp;
                        }
                    }
                    //tidak ada pemotongan
                    else
                    {
                        $pro_decrement = 0;
                    }

                    //kalau tgl masuk baru lebih baru dari range2. ZERO gaji
                    if($zero_day <= 0)
                    {
                        $pro_decrement = $xthp;
                    }

                    if($pro_day >= 0 && $pro_day <= 30) {
                        $total_decrement = $pro_decrement;
                    } elseif($zero_day <= 0) {
                        $total_decrement = $pro_decrement;
                    } else {
                        $total_decrement = 0;
                    }

                    $row->proportional = $pro_decrement; //Proportional
                    $row->company_id = $value->company_id;

                    $row->save();

                    $amount_zakat = base64_decode($row->salary) + $row->ovt_nom + $row->field_nom + $row->wh_nom + $row->voucher + $row->fld_dgr + $row->fld_swt +  $row->odo_dgr + $row->odo_swt + $row->allow_bpjs_kes + $row->allow_bpjs_tk + $row->allow_jshk + $row->thr;
                    $amount_zakat -= $row->deduction + $row->lateness + $row->deduc_bpjs_tk + $row->deduc_bpjs_kes + $row->deduc_jshk + $row->deduc_pph21 + $row->deduc_proportional;

                    $zakat = new Users_zakat();
                    $zakat->emp_id = $value->id;
                    $zakat->company_id = $value->company_id;
                    $zakat->description = "Zakat Mal ".date("F Y", strtotime($y."-".$m."-".$t));
                    $zakat->salary_period = $y."-".sprintf("%02d", $m);
                    $zakat->paid = 0;
                    $zakat->amount = $amount_zakat * (2.5 / 100);
                    $zakat->save();
                }

            }

            $emp_arc = Hrd_salary_archive::where('archive_period', $m."-".$y)
                ->where('company_id', $id_companies)
                ->whereIn('emp_id', $emp_type)
                // ->orWhere('category', 'like', "%$_t->name%")
                ->get();



            $iNum = 1;

            if (count($emp_arc) > 0) {
                foreach ($emp_arc as $key => $value) {
                    $sunction = 0;
                    $sanction = Hrd_sanction::where('emp_id', $value->emp_id)
                        ->whereNotNull('approved_by')
                        ->whereBetween('sanction_date',[$rangeStart,$rangeEnd])
                        ->get();

                    foreach ($sanction as $key => $valSanc){
                        $sunction += floatval($valSanc->sanction_amount);
                    }

                    $row = [];
                    $salary_emp = base64_decode($value->salary);

                    $allow_bpjs_tk = ($value->allow_bpjs_tk == "") ? 0 : $value->allow_bpjs_tk;
                    $allow_bpjs_kes = ($value->allow_bpjs_kes == "") ? 0 : $value->allow_bpjs_kes;
                    $allow_jshk = ($value->allow_jshk == "") ? 0 : $value->allow_jshk;

                    $foot['sum_tk'] += $allow_bpjs_tk;
                    $foot['sum_ks'] += $allow_bpjs_kes;
                    $foot['sum_jshk'] += $allow_jshk;

                    $deduc_bpjs_tk = ($value->deduc_bpjs_tk == "") ? 0 : $value->deduc_bpjs_tk;
                    $deduc_bpjs_kes = ($value->deduc_bpjs_kes == "") ? 0 : $value->deduc_bpjs_kes;
                    $deduc_jshk = ($value->deduc_jshk == "") ? 0 : $value->deduc_jshk;

                    $foot['sum_ded_tk'] += $deduc_bpjs_tk;
                    $foot['sum_ded_ks'] += $deduc_bpjs_kes;
                    $foot['sum_ded_jshk'] += $deduc_jshk;


                    $sal = base64_decode($value->salary);

                    $hours = 0;

                    // if (!empty($time_in[$value->emp_id])) {
                    //     for ($i=0; $i < count($time_in[$value->emp_id]); $i++) {
                    //         $otdate = date("N", strtotime($ovt_date[$value->emp_id][$i]));
                    //         if(!empty($time_out[$value->emp_id][$i])){
                    //             $jamout = $time_out[$value->emp_id][$i];
                    //             if($jamout == "00:00:00"){
                    //                 $jamout = "24:00:00";
                    //             }
                    //             if($otdate >= 6){
                    //                 $diff = strtotime($jamout) - strtotime($time_in[$value->emp_id][$i]);
                    //                 $hours += $diff;
                    //             } else {
                    //                 $ovtStartTime = Session::get('company_penalty_stop');
                    //                 if(!empty($ovtStartTime)){
                    //                     if(strtotime($time_in[$value->emp_id][$i]) >= strtotime($ovtStartTime)){
                    //                         $diff = strtotime($jamout) - strtotime($time_in[$value->emp_id][$i]);
                    //                     } else {
                    //                         $jammasuk = strtotime(Session::get("company_penalty_start"));
                    //                         if($time_in[$value->emp_id][$i] < $jammasuk){
                    //                             $diff = strtotime($jamout) - strtotime($time_in[$value->emp_id][$i]);
                    //                         } else {
                    //                             $diff = strtotime($jamout) - strtotime($ovtStartTime);
                    //                         }
                    //                     }
                    //                     $hours += $diff;
                    //                 }
                    //             }
                    //         }
                    //     }
                    // }

                    if($value->ovt_nom > 0){
                        if($value->ovt_rate > 0){
                            $hours = $value->ovt_nom / $value->ovt_rate * 3600;
                        }
                    }

                    $whday = 0;
                    $fldday = 0;
                    $fldswtday = 0;
                    $fldgrday = 0;
                    $ododay = 0;
                    $odoswtday = 0;
                    $odogrday = 0;

                    $ovt_total = $value->ovt_rate * $hours;
                    if($value->field_nom > 0){
                        if($value->field_rate > 0){
                            $fldday = $value->field_nom / $value->field_rate;
                        }
                    }

                    if($value->fld_swt > 0){
                        $fldswtday = $value->fld_swt / ($value->field_rate + 25000);
                    }

                    if($value->fld_dgr > 0){
                        $fldgrday = $value->fld_dgr / ($value->field_rate + 50000);
                    }

                    if($value->wh_nom > 0){
                        if($value->wh_rate > 0){
                            $whday = $value->wh_nom / $value->wh_rate;
                        }
                    }

                    if($value->odo_nom > 0){
                        if($value->odo_rate > 0){
                            $ododay = $value->odo_nom / $value->odo_rate;
                        }
                    }

                    if($value->odo_swt > 0){
                        $odoswtday = $value->odo_swt / ($value->odo_rate + 25000);
                    }

                    if($value->odo_dgr > 0){
                        $odogrday = $value->odo_dgr / ($value->odo_rate + 50000);
                    }

                    $foot['sum_ovt'] += $ovt_total;
                    // $whday = (empty($wh_day[$value->emp_id])) ? "0" : array_sum($wh_day[$value->emp_id]);
                    // $fldday = (empty($fld_day[$value->emp_id])) ? "0" : array_sum($fld_day[$value->emp_id]);
                    // $fldswtday = (empty($fld_swt[$value->emp_id])) ? "0" : array_sum($fld_swt[$value->emp_id]);
                    // $fldgrday = (empty($fld_dgr[$value->emp_id])) ? "0" : array_sum($fld_dgr[$value->emp_id]);

                    $fld = $value->field_rate * intval($fldday);
                    $_fld[$value->id]['day'] = $fldday;
                    $_fld[$value->id]['nom'] = $fld;
                    $_fld[$value->id]['rate'] = $value->field_rate;
                    $flddgr = ($value->field_rate + 25000) * $fldgrday;
                    $fldswt = ($value->field_rate + 50000) * $fldswtday;

                    $foot['sum_fld'] += $fld + $flddgr + $fldswt;

                    $wh = $value->wh_rate * $whday;

                   $foot['sum_wh'] += $wh;

                    // $ododay = (empty($odo_day[$value->emp_id])) ? "0" : $odo_day[$value->emp_id];
                    // $odoswtday = (empty($odo_swt[$value->emp_id])) ? "0" : $odo_swt[$value->emp_id];
                    // $odogrday = (empty($odo_dgr[$value->emp_id])) ? "0" : $odo_dgr[$value->emp_id];

                    $odo = $value->odo_rate * $ododay;
                    $ododgr = ($value->odo_rate + 25000) * $odogrday;
                    $odoswt = ($value->odo_rate + 50000) * $odoswtday;

                   $foot['sum_odo'] += $odo + $ododgr + $odoswt;

                    $ln_amt = $value->deduction;

                    $foot['sum_loan'] += $ln_amt;

                    $bonus_amt = $value->bonus;
                    $foot['sum_bonus'] += $bonus_amt;

                    // Datatable
                    $date = (isset($act_date[$value->company_id][$value->emp_id])) ? $act_date[$value->company_id][$value->emp_id] : "0000-00-00";
                    $pro_day = round((strtotime($date) - strtotime($rangeStart)) / 86400,0);
                    $row[] = $iNum++;//
                    if (empty($emp_name) || $emp_name[$value->emp_id] == null){
                        $row[] = '';
                    } else {
                        $row[] = $emp_name[$value->emp_id]."<br>".$emp_pos[$value->emp_id]."<br><label style='font-style: italic;'>'".$emp_bank[$value->emp_id]."</label><a href='".route('payroll.slip.print', ['id' => $value->emp_id, 'period' => $y."-".$m])."' target='_blank' class='btn btn-xs btn-icon btn-secondary'><i class='fa fa-print'></i></a>";//

                    }

                    $row[] = number_format($sal + $value->allowance_office,2);
                    $row[] = number_format($value->ovt_rate,2);
                    $row[] = floor(($hours / 3600))." hour(s) ". round(($hours%3600) / 60)." minute(s)";
                    $row[] = number_format($value->ovt_nom,2);
                    $row[] = number_format($value->field_rate,2)."<br>". number_format(($value->field_rate + 50000),2) ."<br>".number_format(($value->field_rate + 25000),2);
                    $row[] = $fldday."<br>".$fldswtday."<br>".$fldgrday;
                    $row[] = number_format($fld,2)."<br>". number_format(($fldswt),2) ."<br>".number_format(($flddgr),2);
                    $row[] = number_format($value->wh_rate,2);
                    $row[] = $whday; // DAYS WH
                    $row[] = number_format($wh,2);
                    $row[] = number_format($value->odo_rate,2)."<br>". number_format(($value->odo_rate + 50000),2) ."<br>".number_format(($value->odo_rate + 25000),2);
                    $row[] = $ododay."<br>".$odoswtday."<br>".$odogrday; // DAYS ODO
                    $row[] = number_format($odo,2)."<br>". number_format(($odoswt),2) ."<br>".number_format(($ododgr),2);
                    $row[] = number_format($allow_bpjs_tk,2);
                    $row[] = number_format($allow_bpjs_kes,2);
                    $row[] = number_format($allow_jshk,2);
                    $row[] = number_format($value->voucher,2);

                    $foot['sum_salary'] += $sal;
                    // $foot['sum_ovt'] += $ovt_total;
                    $foot['sum_voucher'] += $value->voucher;
                    $total_sal = $sal + $ovt_total + $fld + $wh + $odo + $ododgr + $odoswt + $flddgr + $fldswt + $value->voucher + $value->allow_bpjs_tk + $value->allow_bpjs_kes + $value->allow_jshk + $bonus_amt;
                    $foot['sum_tot_salary'] += $total_sal;

                    $row[] = number_format($total_sal,2);
                    $row[] = number_format($sunction,2); //SUNCTION
                    $row[] = 0; //ABSENCE
                    $row[] = number_format($ln_amt, 2); //LOAN
                    $row[] = number_format($deduc_bpjs_tk,2);
                    $row[] = number_format($deduc_bpjs_kes,2);
                    $row[] = number_format($deduc_jshk,2);
                    $row[] = number_format($bonus_amt, 2); //BONUS

                    $thr_total = $value->thr;

                    $foot['sum_thr'] += $thr_total;
                    $row[] = number_format($thr_total,2); //THR

                    $thp_total = $total_sal + $thr_total - $sunction - $ln_amt - $deduc_jshk - $deduc_bpjs_tk - $value->deduc_pph21 - $deduc_bpjs_kes;

                    $thp_total -= $value->proportional;
                    $foot['sum_thp'] += $thp_total;

                    $row[] = ($value->deduc_pph21 == "") ? 0 : number_format($value->deduc_pph21,2); //PPH21
                    $foot['sum_pph21'] += $value->deduc_pph21;
                    $row[] = number_format($value->proportional,2); //Proportional
                    $row[] = number_format($thp_total,2); //THP
                    $empsalid[$value->emp_id] = $thp_total;
                    $foot['sum_prop'] += $value->proportional;
                    $foot['sum_sanction'] += $sunction;
                    $data[] = $row;
                    $source = "Archive";


                }
                foreach ($type_emp as $keyEmp => $valueEmp){
                    if (isset($eType[$keyEmp])) {
                        $util = Finance_util_salary::where('position', $eType[$keyEmp])
                            ->where('salary_date', 'like', $y."-".sprintf("%02d", $m)."%")
                            ->where('company_id', Session::get('company_id'))
                            ->first();
                        $sal_total = 0;
                        foreach ($valueEmp as $itemEmp){
                            $sal_total += $empsalid[$itemEmp];
                        }
                        if (empty($util)){
                            $nUtil = new Finance_util_salary();
                            $sdate = $y."-".$m."-28";
                            $nUtil->salary_date = $sdate;
                            $nUtil->currency = "IDR"; //default
                            $nUtil->amount = $sal_total;
                            $nUtil->plan_date = $sdate;
                            $nUtil->status = "waiting";
                            $nUtil->position = $eType[$keyEmp];
                            $nUtil->company_id = Session::get('company_id');
                            $nUtil->created_by = Auth::user()->username;
                            $nUtil->save();
                        } else {
                            $util->amount = $sal_total;
                            $util->save();
                        }
                    }
                }

                $error = 0;
            } else {
                $error = 1;
                $data = null;
                $source = null;
            }
        } else {
            if (count($emp) > 0) {
                foreach ($emp as $key => $value) {
                    $row = [];
                    $salary_emp = base64_decode($value->salary);
                    $sunction = 0;
                    $absence_deduct = 0;
                    $bonus_amt = 0;
                    $ln_amt = 0;
                    $hours = 0;
                    $sumSanc = 0;
                    $empid = $value->id;

                    $sanction = Hrd_sanction::where('emp_id', $value->id)
                        ->whereNotNull('approved_by')
                        ->whereBetween('sanction_date',[$rangeStart,$rangeEnd])
                        ->get();

                    foreach ($sanction as $key => $valSanc){
                        $sunction += intval($valSanc->sanction_amount);
                    }


                    $allow_bpjs_tk = ($value->allow_bpjs_tk == "") ? 0 : $value->allow_bpjs_tk;
                    $allow_bpjs_kes = ($value->allow_bpjs_kes == "") ? 0 : $value->allow_bpjs_kes;
                    $allow_jshk = ($value->allow_jshk == "") ? 0 : $value->allow_jshk;

                    $foot['sum_sanction'] += $sunction;

                    $foot['sum_tk'] += $allow_bpjs_tk;
                    $foot['sum_ks'] += $allow_bpjs_kes;
                    $foot['sum_jshk'] += $allow_jshk;

                    $deduc_bpjs_tk = ($value->deduc_bpjs_tk == "") ? 0 : $value->deduc_bpjs_tk;
                    $deduc_bpjs_kes = ($value->deduc_bpjs_kes == "") ? 0 : $value->deduc_bpjs_kes;
                    $deduc_jshk = ($value->deduc_jshk == "") ? 0 : $value->deduc_jshk;

                    $foot['sum_ded_tk'] += $deduc_bpjs_tk;
                    $foot['sum_ded_ks'] += $deduc_bpjs_kes;
                    $foot['sum_ded_jshk'] += $deduc_jshk;


                    $sal = $salary_emp + base64_decode($value->transport) + base64_decode($value->meal) + base64_decode($value->house) + base64_decode($value->health);

                    if (!empty($time_in[$value->id])) {
                        for ($i=0; $i < count($time_in[$value->id]); $i++) {
                            $otdate = date("N", strtotime($ovt_date[$value->id][$i]));
                            if($otdate >= 6){
                                $diff = strtotime($time_out[$value->id][$i]) - strtotime($time_in[$value->id][$i]);
                                $hours += $diff;
                            } else {
                                $ovtStartTime = Session::get('company_penalty_stop');
                                if(!empty($ovtStartTime)){
                                    $diff = strtotime($time_out[$value->id][$i]) - strtotime($ovtStartTime);
                                    $hours += $diff;
                                }
                            }
                        }
                    }

                    $ovt_total = $value->overtime * ceil(($hours / 3600));

                    $foot['sum_ovt'] += $ovt_total;
                    $whday = (empty($wh_day[$value->id])) ? "0" : array_sum($wh_day[$value->id]);
                    $fldday = (empty($fld_day[$value->id])) ? "0" : array_sum($fld_day[$value->id]);
                    $fldswtday = (empty($fld_swt[$value->id])) ? "0" : array_sum($fld_swt[$value->id]);
                    $fldgrday = (empty($fld_dgr[$value->id])) ? "0" : array_sum($fld_dgr[$value->id]);

                    $fld = $value->fld_bonus * $fldday;
                    $flddgr = ($value->fld_bonus + 25000) * $fldgrday;
                    $fldswt = ($value->fld_bonus + 50000) * $fldswtday;

                    $foot['sum_fld'] += $fld + $flddgr + $fldswt;

                    $wh = $value->wh_bonus * $whday;

                    $foot['sum_wh'] += $wh;

                    $ododay = (empty($odo_day[$value->id])) ? "0" : $odo_day[$value->id];
                    $odoswtday = (empty($odo_swt[$value->id])) ? "0" : $odo_swt[$value->id];
                    $odogrday = (empty($odo_dgr[$value->id])) ? "0" : $odo_dgr[$value->id];

                    $odo = $value->odo_bonus * $ododay;
                    $ododgr = ($value->odo_bonus + 25000) * $odogrday;
                    $odoswt = ($value->odo_bonus + 50000) * $odoswtday;

                    $foot['sum_odo'] += $odo + $ododgr + $odoswt;

                    if(isset($loanEmp[$value->id])){
                        foreach($loanEmp[$value->id] as $lEmp){
                            if (isset($loanDet[$lEmp])){
                                $ln_amt += array_sum($loanDet[$lEmp]);
                            }
                        }
                    }

                    $foot['sum_loan'] += $ln_amt;

                    if (isset($bonusEmp[$value->id])) {
                        foreach ($bonusEmp[$value->id] as $bEmp) {
                            if (isset($bonusPay[$bEmp])) {
                                $bonus_amt += array_sum($bonusPay[$bEmp]);
                            }
                        }
                    }

                    $foot['sum_bonus'] += $bonus_amt;

                    $yearly_bonus = $value->yearly_bonus * $salary_emp + $value->fx_yearly_bonus;
                    $bonus_only = $value->yearly_bonus * $salary_emp;

                    // Datatable
                    $row[] = $key + 1;//
                    $row[] = $value->emp_name."<br>".$value->emp_position."<br><label style='font-style: italic;'>'".$value->bank_acct."</label>";//
                    $row[] = number_format($sal + $value->allowance_office,2);
                    $row[] = number_format($value->overtime,2);
                    $row[] = floor(($hours / 3600))." hour(s) ". round(($hours%3600) / 60)." minute(s)";
                    $row[] = number_format($ovt_total,2);
                    $row[] = number_format($value->fld_bonus,2)."<br>". number_format(($value->fld_bonus + 50000),2) ."<br>".number_format(($value->fld_bonus + 25000),2);
                    $row[] = $fldday."<br>".$fldswtday."<br>".$fldgrday;
                    $row[] = number_format($fld,2)."<br>". number_format(($fldswt),2) ."<br>".number_format(($flddgr),2);
                    $row[] = number_format($value->wh_bonus,2);
                    $row[] = $whday; // DAYS WH
                    $row[] = number_format($wh,2);
                    $row[] = number_format($value->odo_bonus,2)."<br>". number_format(($value->odo_bonus + 50000),2) ."<br>".number_format(($value->odo_bonus + 25000),2);
                    $row[] = $ododay."<br>".$odoswtday."<br>".$odogrday; // DAYS ODO
                    $row[] = number_format($odo,2)."<br>". number_format(($odoswt),2) ."<br>".number_format(($ododgr),2);
                    $row[] = number_format($allow_bpjs_tk,2);
                    $row[] = number_format($allow_bpjs_kes,2);
                    $row[] = number_format($allow_jshk,2);
                    $row[] = number_format($value->voucher,2);

                    $foot['sum_salary'] += $sal;
                    $foot['sum_ovt'] += $ovt_total;
                    $foot['sum_voucher'] += $value->voucher;
                    $total_sal = $sal + $value->allowance_office + $ovt_total + $fld + $wh + $odo + $ododgr + $odoswt + $flddgr + $fldswt + $value->voucher + $value->allow_bpjs_tk + $value->allow_bpjs_kes + $value->allow_jshk + $bonus_amt;
                    $foot['sum_tot_salary'] += $total_sal;

                    $row[] = number_format($total_sal,2);
                    $row[] = number_format($sunction,2); //SUNCTION
                    $row[] = 0; //ABSENCE
                    $row[] = number_format($ln_amt, 2); //LOAN
                    $row[] = number_format($deduc_bpjs_tk,2);
                    $row[] = number_format($deduc_bpjs_kes,2);
                    $row[] = number_format($deduc_jshk,2);
                    $row[] = number_format($bonus_amt, 2); //BONUS

                    $isThr = sprintf("%02d", $m)."-".$y;
                    $date_join = (isset($act_date[$value->company_id][$empid])) ? $act_date[$value->company_id][$empid] : "0000-00-00";
                    $mntDiff = 0;
                    $dIn = 0;
                    if ($date_join != "0000-00-00") {
                        $dateIn = date_create($date_join);
                        $dateNow = date_create(date("Y-m-d"));
                        $diff = date_diff($dateIn, $dateNow);
                        $mntDiff = $diff->format("%y-%m");
                        $dIn = date('d', strtotime($date_join));
                    }

                    $wPeriod = explode("-", $mntDiff);

                    $mnt = 0;
                    if($wPeriod[0] >= 1){
                        $mnt = 12 * $wPeriod[0];
                    }

                    $mnt += end($wPeriod);

                    if($dIn > $period_start){
                        $mnt -= 1;
                    }

                    $prob = [];
                    $vcr = [];
                    $_thr = [];
                    if(isset($detType[$value->emp_type])){
                        $prob = json_decode($detType[$value->emp_type]['no_probation'], true);
                        $vcr = json_decode($detType[$value->emp_type]['with_voucher'], true);
                        $_thr = json_decode($detType[$value->emp_type]['disable_thr'], true);
                    }

                    $minThr = 3;
                    $maxThr = 15;

                    if(isset($prob[$value->company_id])){
                        if($prob[$value->company_id] == 1){
                            $minThr = 0;
                            $maxThr = 12;
                        }
                    } elseif (isset($prob[$config_company->id_parent])){
                        if($prob[$config_company->id_parent] == 1){
                            $minThr = 0;
                            $maxThr = 12;
                        }
                    }

                    $with_voucher = 0;
                    if(isset($vcr[$value->company_id])){
                        if($vcr[$value->company_id]){
                            $with_voucher = $value->voucher;
                        }
                    } elseif (isset($vcr[$config_company->id_parent])){
                        if($vcr[$config_company->id_parent] == 1){
                            $with_voucher = $value->voucher;
                        }
                    }

                    $disable_thr = 0;
                    if(isset($_thr[$value->company_id])){
                        if($_thr[$value->company_id]){
                            $disable_thr = (isset($_thr[$value->company_id])) ? $_thr[$value->company_id] : 0;
                        }
                    } elseif (isset($_thr[$config_company->id_parent])){
                        if($_thr[$config_company->id_parent] == 1){
                            $disable_thr = (isset($_thr[$value->company_id])) ? $_thr[$value->company_id] : 0;
                        }
                    }

                    if($mnt <= $minThr){
                        $thr_num = 0;
                    } elseif($mnt > $minThr && $mnt < $maxThr){
                        $thr_num = (($mnt - $minThr) / 12) * ($sal + $value->allowance_office + $with_voucher);
                    } elseif($mnt >= $maxThr) {
                        $thr_num = ($sal + $value->allowance_office + $with_voucher);
                    }

                    if ($isThr == strip_tags($thr_period)){
                        $thr_total = ($disable_thr == 0) ? $thr_num : 0;
                    } else {
                        $thr_total = 0;
                    }
                    $foot['sum_thr'] += $thr_total;
                    $row[] = number_format($thr_total,2); //THR

                    // $total_sal = $sal + $value->allowance_office + $ovt_total + $fld + $wh + $odo + $ododgr + $odoswt + $flddgr + $fldswt + $value->voucher + $value->allow_bpjs_tk + $value->allow_bpjs_kes + $value->allow_jshk;

                    $thp = $total_sal - $absence_deduct - $ln_amt - $value->deduc_bpjs_tk - $value->deduc_bpjs_kes - $value->deduc_jshk - $value->deduc_pph21;
                    $xthp = $thp - $fld - $wh - $odo - $ododgr - $odoswt - $fldswt - $flddgr;
                    $_date = (isset($act_date[$value->company_id][$empid])) ? $act_date[$value->company_id][$empid] : "000-00-00";
                    $pro_day = round((strtotime($_date) - strtotime($rangeStart)) / 86400,0);
                    $in_date = (isset($act_date[$value->company_id][$empid])) ? $act_date[$value->company_id][$empid] : "000-00-00";
                    $zero_day = (strtotime($rangeEnd) - strtotime($_date)) / 86400;
                    if($pro_day > 0 && $pro_day <= $pro_n_day)
                    {
                        $pro_basis = $pro_n_day;
                        $pro_thp = $pro_day / $pro_basis * $xthp;
                        $pro_decrement = ($pro_day) / $pro_basis * $xthp;
                    }
                    //kalau hari masuk = start month gaji, pengurangan = gaji = ZERO gaji.
                    elseif($pro_day == 0)
                    {
                        // $pro_decrement = $xthp;
                        if(date('d',strtotime($in_date)) == 16)
                        {
                            $pro_decrement = 0;
                        }
                        else
                        {
                            $pro_decrement = $xthp;
                        }
                    }
                    //tidak ada pemotongan
                    else
                    {
                        $pro_thp = 0;
                        $pro_decrement = 0;
                    }

                    //kalau tgl masuk baru lebih baru dari range2. ZERO gaji
                    if($zero_day <= 0)
                    {
                        $pro_decrement = $xthp;
                    }

                    if($pro_day >= 0 && $pro_day <= 30) {
                        $total_decrement = $pro_decrement;
                        $thp_total = $thp - $pro_decrement;
                        $foot['sum_thp'] += $thp - $pro_decrement;
                    } elseif($zero_day <= 0) {
                        $total_decrement = $pro_decrement;
                        $thp_total = $thp - $pro_decrement;
                        $foot['sum_thp'] += $thp - $pro_decrement;
                    } else {
                        $foot['sum_thp'] += $thp;
                        $thp_total = $thp;
                        $total_decrement = 0;
                    }

                    $row[] = ($value->deduc_pph21 == "") ? 0 : number_format($value->deduc_pph21,2); //PPH21
                    $foot['sum_pph21'] += $value->deduc_pph21;
                    $row[] = number_format($total_decrement,2); //Proportional
                    $row[] = number_format($thp_total,2); //THP

                    $foot['sum_prop'] += $total_decrement;

                    $data[] = $row;

                }
                $error = 0;
                $source = "EMPLOYEE";
            } else {
                $error = 1;
                $data = null;
                $source = null;
            }
        }

        if (isset($eType[$t])) {
            $pos = $eType[$t];
        } else {
            $pos = $t;
        }

        if (date('d') >= Session::get('period_start')) {
            if($request->month != date("n")){
                $refresh = 0;
            } else {
                $refresh = 1;
            }
        } else {
            $refresh = 0;
        }

        $val = array(
            'error' => $error,
            'data' => $data,
            'footer' => $foot,
            'table_signature' => $this->tableSignature($sign),
            'source' => $source,
            'periode' => date("F Y", strtotime($y."-".$m)),
            'position' => strtoupper($pos),
            'refresh' => $refresh,
            'btnArch' => $btnArch
        );

        return json_encode($val);
    }

    public function needsec(){
        return view('payroll.needsec');
    }
    public function submitNeedsec(Request $request){
        $this->validate($request,[
            'searchInput' => 'required'
        ]);
        if ($request['searchInput'] == 'koi999'){
            Session::put('seckey_payroll', 99);
            return redirect()->route('payroll.index');
        } else {
            return redirect()->back()->with('message_needsec_fail', 'Access Denied! Please enter the correct code');
        }
    }

    function update(Request $request){
        // dd($request);
        if ($request->type == "all"){
            $whereType = " 1";
            $wherePos = "";
        } else {
            $whereType = " emp_type = ".$request->type;
            $wherePos = " emp_position = ".$request->type;
        }
        $emp = Hrd_employee::whereRaw($whereType)
            ->where('company_id', Session::get('company_id'))
            ->get();

        $empId = [];
        foreach($emp as $item){
            $empId[] = $item->id;
        }

        $period = $request->month."-".$request->years;
        Hrd_salary_archive::whereRaw("archive_period='".$period."'")
            ->whereIn('emp_id', $empId)
            ->where('company_id', Session::get('company_id'))
            ->delete();

        $salary_period = $request->years."-".sprintf("%02d", $request->month);

        Users_zakat::whereRaw("salary_period='".$salary_period."'")
            ->whereIn('emp_id', $empId)
            ->where('company_id', Session::get('company_id'))
            ->forceDelete();

        $data['error'] = 0;

        return json_encode($data);
    }

    public function print_btl(Request $request){
        $id_companies = Session::get('company_id');

        $t = $request->t;
        $m = $request->m;
        $y = $request->y;

        $pref = Preference_config::where('id_company', $id_companies)->get();

        $prefCount = $pref->count();
        $now = date('Y-n-d');

        if ($prefCount >0){
            $period_end = $pref[0]->period_end;
            $period_start = $pref[0]->period_start;
        } else {
            if (session()->has('company_period_end') && session()->has('company_period_start')){
                $period_end = Session::get('company_period_end');
                $period_start = Session::get('company_period_start');
            } else {
                $period_end = 27;
                $period_start = 28;
            }
        }

        $thr_period = Session::get('company_thr_period');
        if($t == "all"){
            $emp = Hrd_employee::where('expel', null)
                ->where('company_id', $id_companies)
                ->orderBy('emp_name')
                ->get();
        } else {
            $emp = Hrd_employee::where('emp_type', $t)
                ->where('expel', null)
                ->where('company_id', $id_companies)
                ->orderBy('emp_name')
                ->get();
        }

        $emp_name = [];
        $emp_pos = [];
        $emp_bank = [];
        $emp_type = [];
        $data_emp = [];

        foreach ($emp as $key => $value) {
            $emp_name[$value->id] = $value->emp_name;
            $emp_pos[$value->id] = $value->emp_position;
            $emp_bank[$value->id] = $value->bank_acct;
            $emp_type[] = $value->id;
            $data_emp[$value->id] = $value;
        }

        $emp_arc = Hrd_salary_archive::where('company_id',$id_companies)->get();

        $eType = Hrd_employee_type::all();
        $detType = [];
        foreach ($eType as $value){
            $detType[$value->id] = $value;
        }

        $emp_his = Hrd_employee_history::where('activity', 'in')->get();

        foreach ($emp_his as $key => $value) {
            $act_date[$value->company_id][$value->emp_id] = $value->act_date;
        }

        $sign = $this->signName($t);

        $period_start_date = $y."-".sprintf('%02d', $m-1)."-".$period_start;
        if($m-1 == 0){
            $period_start_date = ($y - 1)."-12-".$period_start;
        }
        $period_end_date = $y."-".sprintf('%02d', $m)."-".$period_end;

        $ovt = Hrd_overtime::where('company_id', $id_companies)
            ->whereBetween('ovt_date', [$period_start_date, $period_end_date])
            ->get();
        $ovt_date = [];
        foreach ($ovt as $key => $value) {
            $time_in[$value->emp_id][] = $value->time_in;
            $time_out[$value->emp_id][] = $value->time_out;
            $ovt_date[$value->emp_id][] = $value->ovt_date;
        }

        $to = General_travel_order::whereNotNull('action_time')
            ->whereRaw("(departure_dt >= '".$period_start_date."' and return_dt <= '".$period_end_date."')")
            ->orWhereRaw("(return_dt >= '".$period_start_date."' and departure_dt <= '".$period_end_date."')")
            ->where('company_id', Session::get('company_id'))
            ->where('status', 0)
            ->get();

        foreach ($to as $key => $value) {
            if ($value->departure_dt < $period_start_date){
                $d2 = date('Y-m-d', strtotime($period_start_date." -1 day"));
            } else {
                $d2 = $value->departure_dt;
            }

            if ($value->return_dt < $period_end_date){
                $d1 = $value->return_dt;
            } else {
                $d1 = $period_end_date;
            }
            // $d1 = ($value->return_dt >= $period_end_date) ? date("Y-m-d", strtotime($period_end_date." +1 day")) : $value->return_dt;
            // $d2 = ($value->departure_dt <= $period_start_date) ? date('Y-m-d', strtotime($period_start_date." -1 day")) : $value->departure_dt;

            $sum = date_diff(date_create($d1), date_create($d2));

            if ($value->travel_type == "reg") {
                if ($value->location_rate == "SWT") {
                    switch ($value->dest_type) {
                        case "fld" :
                            $fld_swt[$value->employee_id][] = $sum->format("%a");
                            break;
                    }
                } elseif ($value->location_rate == "DGR") {
                    switch ($value->dest_type) {
                        case "fld" :
                            $fld_dgr[$value->employee_id][] = $sum->format("%a");
                            break;
                    }
                } else {
                    switch ($value->dest_type) {
                        case "fld" :
                            $fld_day[$value->employee_id][$value->id] = $sum->format("%a");
                            break;
                        case "wh" :
                            $wh_day[$value->employee_id][] = $sum->format("%a");
                            break;
                    }
                }
            } elseif ($value->travel_type = "odo") {
                if (empty($value->location_rate)) {
                    if ($value->dest_type == "fld_bonus") {
                        $odo_day[$value->employee_id][] = $sum->format("%a");
                    }
                } elseif ($value->location_rate == "SWT") {
                    if ($value->dest_type == "fld_bonus") {
                        $odo_swt[$value->employee_id][] = $sum->format("%a");
                    }
                } elseif ($value->location_rate == "DGR") {
                    if ($value->dest_type == "fld_bonus") {
                        $odo_dgr[$value->employee_id][] = $sum->format("%a");
                    }
                }
            }
        }

        $whereLoan = $y."-".sprintf("%02d", $m);

        $loan = Hrd_employee_loan::where("company_id", Session::get("company_id"))->get();
        foreach($loan as $item){
            $loanEmp[$item->emp_id][] = $item->id;
        }

        $loan_det = Hrd_employee_loan_payment::where('date_of_payment', 'like', $whereLoan."%")->get();
        foreach($loan_det as $item){
            $loanDet[$item->loan_id] = $item->amount;
        }

        $bonus_pay = Hrd_bonus_payment::where('date_of_payment', 'like', $whereLoan."%")->get();
        foreach ($bonus_pay as $value) {
            $bonusPay[$value->bonus_id][] = $value->amount;
        }

        $foot['sum_salary'] = 0;
        $foot['sum_ovt'] = 0;
        $foot['sum_fld'] = 0;
        $foot['sum_wh'] = 0;
        $foot['sum_odo'] = 0;
        $foot['sum_tk'] = 0;
        $foot['sum_ks'] = 0;
        $foot['sum_jshk'] = 0;
        $foot['sum_tot_salary'] = 0;
        $foot['sum_sunction'] = 0;
        $foot['sum_absence'] = 0;
        $foot['sum_loan'] = 0;
        $foot['sum_ded_tk'] = 0;
        $foot['sum_ded_ks'] = 0;
        $foot['sum_ded_jshk'] = 0;
        $foot['sum_bonus'] = 0;
        $foot['sum_thr'] = 0;
        $foot['sum_pph21'] = 0;
        $foot['sum_prop'] = 0;
        $foot['sum_thp'] = 0;
        $foot['sum_voucher'] = 0;
        $foot['sum_sanction'] = 0;

        $rangeStart = $y."-".($m-1)."-".$period_start;
        $rangeEnd = $y."-".$m."-".$period_end;
        $pro_n_day = date("t", strtotime($rangeEnd));

        $config_company = ConfigCompany::find(Session::get('company_id'));

        $bonus = Hrd_bonus::all();
        $bonusEmp = [];
        foreach ($bonus as $value) {
            $bonusEmp[$value->emp_id][] = $value->id;
        }

        if (strtotime($now) > strtotime($period_end_date)){
            $emp_arc = Hrd_salary_archive::where('archive_period', intval($m)."-".$y)
                ->where('company_id', $id_companies)
                ->whereIn('emp_id', $emp_type)
                ->get();

            if (count($emp_arc) > 0) {
                foreach ($emp_arc as $key => $value) {
                    $row = [];
                    $salary_emp = base64_decode($value->salary);

                    $allow_bpjs_tk = ($value->allow_bpjs_tk == "") ? 0 : $value->allow_bpjs_tk;
                    $allow_bpjs_kes = ($value->allow_bpjs_kes == "") ? 0 : $value->allow_bpjs_kes;
                    $allow_jshk = ($value->allow_jshk == "") ? 0 : $value->allow_jshk;

                    $foot['sum_tk'] += $allow_bpjs_tk;
                    $foot['sum_ks'] += $allow_bpjs_kes;
                    $foot['sum_jshk'] += $allow_jshk;

                    $deduc_bpjs_tk = ($value->deduc_bpjs_tk == "") ? 0 : $value->deduc_bpjs_tk;
                    $deduc_bpjs_kes = ($value->deduc_bpjs_kes == "") ? 0 : $value->deduc_bpjs_kes;
                    $deduc_jshk = ($value->deduc_jshk == "") ? 0 : $value->deduc_jshk;

                    $foot['sum_ded_tk'] += $deduc_bpjs_tk;
                    $foot['sum_ded_ks'] += $deduc_bpjs_kes;
                    $foot['sum_ded_jshk'] += $deduc_jshk;

                    $sunction = 0;
                    $sanction = Hrd_sanction::where('emp_id', $value->emp_id)
                        ->whereNotNull('approved_by')
                        ->whereBetween('sanction_date',[$rangeStart,$rangeEnd])
                        ->get();
                    foreach ($sanction as $key => $valSanc){
                        $sunction += intval($valSanc->sanction_amount);
                    }


                    $sal = base64_decode($value->salary);

                    $hours = 0;

                    // if (!empty($time_in[$value->emp_id])) {
                    //     for ($i=0; $i < count($time_in[$value->emp_id]); $i++) {
                    //         $otdate = date("N", strtotime($ovt_date[$value->emp_id][$i]));
                    //         $jamout = $time_out[$value->emp_id][$i];
                    //         if($jamout == "00:00:00"){
                    //             $jamout = "24:00:00";
                    //         }
                    //         if($otdate >= 6){
                    //             $diff = strtotime($jamout) - strtotime($time_in[$value->emp_id][$i]);
                    //             $hours += $diff;
                    //         } else {
                    //             $ovtStartTime = Session::get('company_penalty_stop');
                    //             if(!empty($ovtStartTime)){
                    //                 if(strtotime($time_in[$value->emp_id][$i]) >= strtotime($ovtStartTime)){
                    //                     $diff = strtotime($jamout) - strtotime($time_in[$value->emp_id][$i]);
                    //                 } else {
                    //                     $jammasuk = strtotime(Session::get("company_penalty_start"));
                    //                     if($time_in[$value->emp_id][$i] < $jammasuk){
                    //                         $diff = strtotime($jamout) - strtotime($time_in[$value->emp_id][$i]);
                    //                     } else {
                    //                         $diff = strtotime($jamout) - strtotime($ovtStartTime);
                    //                     }
                    //                 }
                    //                 $hours += $diff;
                    //             }
                    //         }
                    //     }
                    // }

                    if($value->ovt_nom > 0){
                        if($value->ovt_rate > 0){
                            $hours = $value->ovt_nom / $value->ovt_rate * 3600;
                        }
                    }

                    $ovt_total = $value->ovt_nom;
                    $whday = 0;
                    $fldday = 0;
                    $fldswtday = 0;
                    $fldgrday = 0;
                    $ododay = 0;
                    $odoswtday = 0;
                    $odogrday = 0;

                    $foot['sum_ovt'] += $ovt_total;
                    if($value->field_nom > 0){
                        if($value->field_rate > 0){
                            $fldday = $value->field_nom / $value->field_rate;
                        }
                    }

                    if($value->fld_swt > 0){
                        $fldswtday = $value->fld_swt / ($value->field_rate + 25000);
                    }

                    if($value->fld_dgr > 0){
                        $fldgrday = $value->fld_dgr / ($value->field_rate + 50000);
                    }

                    if($value->wh_nom > 0){
                        if($value->wh_rate > 0){
                            $whday = $value->wh_nom / $value->wh_rate;
                        }
                    }

                    if($value->odo_nom > 0){
                        if($value->odo_rate > 0){
                            $ododay = $value->odo_nom / $value->odo_rate;
                        }
                    }

                    if($value->odo_swt > 0){
                        $odoswtday = $value->odo_swt / ($value->odo_rate + 25000);
                    }

                    if($value->odo_dgr > 0){
                        $odogrday = $value->odo_dgr / ($value->odo_rate + 50000);
                    }

                    $fld = $value->field_rate * intval($fldday);
                    $flddgr = ($value->field_rate + 25000) * $fldgrday;
                    $fldswt = ($value->field_rate + 50000) * $fldswtday;

                    $foot['sum_fld'] += $fld + $flddgr + $fldswt;

                    $wh = $value->wh_nom;

                    $foot['sum_wh'] += $wh;

                    $ododay = (empty($odo_day[$value->emp_id])) ? "0" : $odo_day[$value->emp_id];
                    $odoswtday = (empty($odo_swt[$value->emp_id])) ? "0" : $odo_swt[$value->emp_id];
                    $odogrday = (empty($odo_dgr[$value->emp_id])) ? "0" : $odo_dgr[$value->emp_id];

                    $odo = $value->odo_rate * $ododay;
                    $ododgr = ($value->odo_rate + 25000) * $odogrday;
                    $odoswt = ($value->odo_rate + 50000) * $odoswtday;

                    $foot['sum_odo'] += $odo + $ododgr + $odoswt;

                    $ln_amt = $value->deduction;

                    $foot['sum_loan'] += $ln_amt;

                    $bonus_amt = $value->bonus;

                    // Datatable
//                    $row[] = $key + 1;//
//                    if (empty($emp_name) || $emp_name[$value->emp_id] == null){
//                        $row[] = '';
//                    } else {
//                        $row[] = $emp_name[$value->id]."<br>".$emp_pos[$value->id]."<br><label style='font-style: italic;'>'".$emp_bank[$value->id]."</label>";//
//
//                    }

                    $row['bank_account'] = $data_emp[$value->emp_id]->bank_acct;
                    $row['bank_code'] = $data_emp[$value->emp_id]->bank_code;
                    $row['emp_name'] = $data_emp[$value->emp_id]->emp_name;
                    $row['position'] = $data_emp[$value->emp_id]->emp_position;
//                    $row[] = number_format($value->ovt_rate,2);
//                    $row[] = floor(($hours / 3600))." hour(s) ". round(($hours%3600) / 60)." minute(s)";
//                    $row[] = number_format($ovt_total,2);
//                    $row[] = number_format($value->field_rate,2)."<br>". number_format(($value->field_rate + 50000),2) ."<br>".number_format(($value->field_rate + 25000),2);
//                    $row[] = $fldday."<br>".$fldswtday."<br>".$fldgrday;
                //    $row['field'] = number_format($fld,2)."<br>". number_format(($fldswt),2) ."<br>".number_format(($flddgr),2);
//                    $row[] = number_format($value->wh_rate,2);
//                    $row[] = $whday; // DAYS WH
//                    $row[] = number_format($wh,2);
//                    $row[] = number_format($value->odo_rate,2)."<br>". number_format(($value->odo_rate + 50000),2) ."<br>".number_format(($value->odo_rate + 25000),2);
//                    $row[] = $ododay."<br>".$odoswtday."<br>".$odogrday; // DAYS ODO
//                    $row[] = number_format($odo,2)."<br>". number_format(($odoswt),2) ."<br>".number_format(($ododgr),2);
//                    $row[] = number_format($allow_bpjs_tk,2)."<br>". number_format($allow_bpjs_kes,2) ."<br>".number_format($allow_jshk,2);
//                    $row[] = number_format($value->voucher,2);

                    $foot['sum_salary'] += $sal;
                    $foot['sum_ovt'] += $ovt_total;
                    $foot['sum_voucher'] += $value->voucher;
                    $total_sal = $sal + $ovt_total + $fld + $wh + $odo + $ododgr + $odoswt + $flddgr + $fldswt + $value->voucher + $value->allow_bpjs_tk + $value->allow_bpjs_kes + $value->allow_jshk + $bonus_amt;
                    $foot['sum_tot_salary'] += $total_sal;

//                    $row[] = number_format($total_sal,2);
//                    $row[] = 0; //SUNCTION
//                    $row[] = 0; //ABSENCE
//                    $row[] = number_format($ln_amt, 2); //LOAN
//                    $row[] = number_format($deduc_bpjs_tk,2)."<br>". number_format($deduc_bpjs_kes,2) ."<br>".number_format($deduc_jshk,2);;
//                    $row[] = number_format(0, 2)."<br>B: ".number_format(0, 2)."<br>A: ".number_format(0, 2); //BONUS

                    $thr_total = $value->thr;

                    $thp_total = $total_sal + $thr_total - $sunction - $ln_amt - $deduc_jshk - $deduc_bpjs_tk - $value->deduc_pph21 - $deduc_bpjs_kes;

                    $foot['sum_thr'] += $thr_total;
//                    $row[] = number_format($thr_total,2); //THR

                    $thp_total -= $value->proportional;
                    $foot['sum_thp'] += $value->proportional;

//                    $row[] = ($value->deduc_pph21 == "") ? 0 : number_format($value->deduc_pph21,2); //PPH21
                    $foot['sum_pph21'] += $value->deduc_pph21;
//                    $row[] = number_format($value->proportional,2); //Proportional
                    $row['thp'] = number_format($thp_total,2); //THP
                    $row['emp_id'] = $value->emp_id;
                    $row['company_id'] = $value->company_id;

                    $foot['sum_prop'] += $value->proportional;

                    $data[] = $row;
                    $source = "Archive";

                }
                $error = 0;
            } else {
                $error = 1;
                $data = null;
                $source = null;
            }
        } else {
            if (count($emp) > 0) {
                foreach ($emp as $key => $value) {
                    $row = [];
                    $salary_emp = base64_decode($value->salary);
                    $sunction = 0;
                    $absence_deduct = 0;
                    $bonus_amt = 0;
                    $ln_amt = 0;
                    $hours = 0;
                    $empId = $value->id;

                    $allow_bpjs_tk = ($value->allow_bpjs_tk == "") ? 0 : $value->allow_bpjs_tk;
                    $allow_bpjs_kes = ($value->allow_bpjs_kes == "") ? 0 : $value->allow_bpjs_kes;
                    $allow_jshk = ($value->allow_jshk == "") ? 0 : $value->allow_jshk;

                    $foot['sum_tk'] += $allow_bpjs_tk;
                    $foot['sum_ks'] += $allow_bpjs_kes;
                    $foot['sum_jshk'] += $allow_jshk;

                    $deduc_bpjs_tk = ($value->deduc_bpjs_tk == "") ? 0 : $value->deduc_bpjs_tk;
                    $deduc_bpjs_kes = ($value->deduc_bpjs_kes == "") ? 0 : $value->deduc_bpjs_kes;
                    $deduc_jshk = ($value->deduc_jshk == "") ? 0 : $value->deduc_jshk;

                    $foot['sum_ded_tk'] += $deduc_bpjs_tk;
                    $foot['sum_ded_ks'] += $deduc_bpjs_kes;
                    $foot['sum_ded_jshk'] += $deduc_jshk;


                    $sal = $salary_emp + base64_decode($value->transport) + base64_decode($value->meal) + base64_decode($value->house) + base64_decode($value->health);

                    if (!empty($time_in[$value->id])) {
                        for ($i=0; $i < count($time_in[$value->id]); $i++) {
                            $otdate = date("N", strtotime($ovt_date[$value->id][$i]));
                            if($otdate >= 6){
                                $diff = strtotime($time_out[$value->id][$i]) - strtotime($time_in[$value->id][$i]);
                                $hours += $diff;
                            } else {
                                $ovtStartTime = Session::get('company_penalty_stop');
                                if(!empty($ovtStartTime)){
                                    $diff = strtotime($time_out[$value->id][$i]) - strtotime($ovtStartTime);
                                    $hours += $diff;
                                }
                            }
                        }
                    }

                    $ovt_total = $value->overtime * ceil(($hours / 3600));

                    $foot['sum_ovt'] += $ovt_total;
                    $whday = (empty($wh_day[$value->id])) ? "0" : $wh_day[$value->id];
                    $fldday = (empty($fld_day[$value->id])) ? "0" : $fld_day[$value->id];
                    $fldswtday = (empty($fld_swt[$value->id])) ? "0" : $fld_swt[$value->id];
                    $fldgrday = (empty($fld_dgr[$value->id])) ? "0" : $fld_dgr[$value->id];

                    $fld = $value->fld_bonus * $fldday;
                    $flddgr = ($value->fld_bonus + 25000) * $fldgrday;
                    $fldswt = ($value->fld_bonus + 50000) * $fldswtday;

                    $foot['sum_fld'] += $fld + $flddgr + $fldswt;

                    $wh = $value->wh_bonus * $whday;

                    $foot['sum_wh'] += $wh;

                    $ododay = (empty($odo_day[$value->id])) ? "0" : $odo_day[$value->id];
                    $odoswtday = (empty($odo_swt[$value->id])) ? "0" : $odo_swt[$value->id];
                    $odogrday = (empty($odo_dgr[$value->id])) ? "0" : $odo_dgr[$value->id];

                    $odo = $value->odo_bonus * $ododay;
                    $ododgr = ($value->odo_bonus + 25000) * $odogrday;
                    $odoswt = ($value->odo_bonus + 50000) * $odoswtday;

                    $foot['sum_odo'] += $odo + $ododgr + $odoswt;

                    if(isset($loanEmp[$value->id])){
                        foreach($loanEmp[$value->id] as $lEmp){
                            if (isset($loanDet[$lEmp])){
                                $ln_amt += $loanDet[$lEmp];
                            }
                        }
                    }

                    $foot['sum_loan'] += $ln_amt;

                    foreach ($bonus as $keyBonus => $valueBonus) {
                        if ($value->id == $valueBonus->emp_id) {
                            foreach ($bonus_pay as $keyBonusPay => $valueBonusPay) {
                                if ($valueBonus->id == $valueBonusPay->bonus_id) {
                                    $bonus_amt += $valueBonusPay->amount;
                                }
                            }
                        }
                    }

                    $yearly_bonus = $value->yearly_bonus * $salary_emp + $value->fx_yearly_bonus;
                    $bonus_only = $value->yearly_bonus * $salary_emp;

                    // Datatable
                    $row['bank_account'] = $value->bank_acct;//
                    $row['bank_code'] = $value->bank_code;
                    $row['emp_name'] = $value->emp_name;
                    $row['position'] = $value->emp_position;
//                    $row[] = number_format($sal,2);
//                    $row[] = number_format($value->overtime,2);
//                    $row[] = floor(($hours / 3600))." hour(s) ". round(($hours%3600) / 60)." minute(s)";
//                    $row[] = number_format($ovt_total,2);
//                    $row[] = number_format($value->fld_bonus,2)."<br>". number_format(($value->fld_bonus + 50000),2) ."<br>".number_format(($value->fld_bonus + 25000),2);
//                    $row[] = $fldday."<br>".$fldswtday."<br>".$fldgrday;
//                    $row[] = number_format($fld,2)."<br>". number_format(($fldswt),2) ."<br>".number_format(($flddgr),2);
////                    $row[] = number_format($value->wh_bonus,2);
////                    $row[] = $whday; // DAYS WH
////                    $row[] = number_format($wh,2);
////                    $row[] = number_format($value->odo_bonus,2)."<br>". number_format(($value->odo_bonus + 50000),2) ."<br>".number_format(($value->odo_bonus + 25000),2);
////                    $row[] = $ododay."<br>".$odoswtday."<br>".$odogrday; // DAYS ODO
////                    $row[] = number_format($odo,2)."<br>". number_format(($odoswt),2) ."<br>".number_format(($ododgr),2);
//                    $row[] = number_format($allow_bpjs_tk,2)."<br>". number_format($allow_bpjs_kes,2) ."<br>".number_format($allow_jshk,2);
//                    $row[] = number_format($value->voucher,2);

                    $foot['sum_salary'] += $sal;
                    $foot['sum_ovt'] += $ovt_total;
                    $foot['sum_voucher'] += $value->voucher;
                    $total_sal = $sal + $ovt_total + $fld + $wh + $odo + $ododgr + $odoswt + $flddgr + $fldswt + $value->voucher + $value->allow_bpjs_tk + $value->allow_bpjs_kes + $value->allow_jshk;
                    $foot['sum_tot_salary'] += $total_sal;

//                    $row[] = number_format($total_sal,2);
//                    $row[] = 0; //SUNCTION
//                    $row[] = 0; //ABSENCE
//                    $row[] = number_format($ln_amt, 2); //LOAN
//                    $row[] = number_format($deduc_bpjs_tk,2)."<br>". number_format($deduc_bpjs_kes,2) ."<br>".number_format($deduc_jshk,2);;
//                    $row[] = number_format(0, 2)."<br>B: ".number_format(0, 2)."<br>A: ".number_format(0, 2); //BONUS

                    $isThr = sprintf("%02d", $m)."-".$y;
                    $date_join = (isset($act_date[$value->company_id][$value->id])) ? $act_date[$value->company_id][$value->id] : "0000-00-00";
                    $mntDiff = 0;
                    $dIn = 0;
                    if ($date_join != "0000-00-00") {
                        $dateIn = date_create($date_join);
                        $dateNow = date_create(date("Y-m-d"));
                        $diff = date_diff($dateIn, $dateNow);
                        $mntDiff = $diff->format("%y-%m");
                        $dIn = date('d', strtotime($date_join));
                    }

                    $wPeriod = explode("-", $mntDiff);

                    $mnt = 0;
                    if($wPeriod[0] >= 1){
                        $mnt = 12 * $wPeriod[0];
                    }

                    $mnt += end($wPeriod);

                    if($dIn > $period_start){
                        $mnt -= 1;
                    }

                    $prob = [];
                    $vcr = [];
                    $_thr = [];
                    if(isset($detType[$value->emp_type])){
                        $prob = json_decode($detType[$value->emp_type]['no_probation'], true);
                        $vcr = json_decode($detType[$value->emp_type]['with_voucher'], true);
                        $_thr = json_decode($detType[$value->emp_type]['disable_thr'], true);
                    }

                    $minThr = 3;
                    $maxThr = 15;

                    if(isset($prob[$value->company_id])){
                        if($prob[$value->company_id] == 1){
                            $minThr = 0;
                            $maxThr = 12;
                        }
                    } elseif (isset($prob[$config_company->id_parent])){
                        if($prob[$config_company->id_parent] == 1){
                            $minThr = 0;
                            $maxThr = 12;
                        }
                    }

                    $with_voucher = 0;
                    if(isset($vcr[$value->company_id])){
                        if($vcr[$value->company_id]){
                            $with_voucher = $value->voucher;
                        }
                    } elseif (isset($vcr[$config_company->id_parent])){
                        if($vcr[$config_company->id_parent] == 1){
                            $with_voucher = $value->voucher;
                        }
                    }


                    $disable_thr = 0;
                    if(isset($_thr[$value->company_id])){
                        if($_thr[$value->company_id]){
                            $disable_thr = $_thr[$value->company_id];
                        }
                    } elseif (isset($_thr[$config_company->id_parent])){
                        if($_thr[$config_company->id_parent] == 1){
                            $disable_thr = $_thr[$value->company_id];
                        }
                    }

                    if($mnt <= $minThr){
                        $thr_num = 0;
                    } elseif($mnt > $minThr && $mnt < $maxThr){
                        $thr_num = (($mnt - $minThr) / 12) * ($sal + $value->allowance_office + $with_voucher);
                    } elseif($mnt >= $maxThr) {
                        $thr_num = ($sal + $value->allowance_office + $with_voucher);
                    }

                    if ($isThr == strip_tags($thr_period)){
                        $thr_total = ($disable_thr == 0) ? $thr_num : 0;
                    } else {
                        $thr_total = 0;
                    }

                    $foot['sum_thr'] += $thr_total;
//                    $row[] = number_format($thr_total,2); //THR

                    $thp = $total_sal - $sunction - $absence_deduct - $ln_amt - $value->deduc_bpjs_tk - $value->deduc_bpjs_kes - $value->deduc_jshk - $value->deduc_pph21;
                    $xthp = $thp - $fld - $wh - $odo - $ododgr - $odoswt - $fldswt - $flddgr;
                    $pro_day = round((strtotime($act_date[$value->company_id][$empId]) - strtotime($rangeStart)) / 86400,0);
                    $in_date = $act_date[$value->company_id][$empId];
                    $zero_day = (strtotime($rangeEnd) - strtotime($act_date[$value->company_id][$empId])) / 86400;
                    if($pro_day > 0 && $pro_day <= $pro_n_day)
                    {
                        $pro_basis = $pro_n_day;
                        $pro_thp = $pro_day / $pro_basis * $xthp;
                        $pro_decrement = ($pro_day) / $pro_basis * $xthp;
                    }
                    //kalau hari masuk = start month gaji, pengurangan = gaji = ZERO gaji.
                    elseif($pro_day == 0)
                    {
                        // $pro_decrement = $xthp;
                        if(date('d',strtotime($in_date)) == 16)
                        {
                            $pro_decrement = 0;
                        }
                        else
                        {
                            $pro_decrement = $xthp;
                        }
                    }
                    //tidak ada pemotongan
                    else
                    {
                        $pro_thp = 0;
                        $pro_decrement = 0;
                    }

                    //kalau tgl masuk baru lebih baru dari range2. ZERO gaji
                    if($zero_day <= 0)
                    {
                        $pro_decrement = $xthp;
                    }

                    if($pro_day >= 0 && $pro_day <= 30) {
                        $total_decrement = $pro_decrement;
                        $thp_total = $thp - $pro_decrement;
                        $foot['sum_thp'] += $thp - $pro_decrement;
                    } elseif($zero_day <= 0) {
                        $total_decrement = $pro_decrement;
                        $thp_total = $thp - $pro_decrement;
                        $foot['sum_thp'] += $thp - $pro_decrement;
                    } else {
                        $foot['sum_thp'] += $thp;
                        $thp_total = $thp;
                        $total_decrement = 0;
                    }

//                    $row[] = ($value->deduc_pph21 == "") ? 0 : number_format($value->deduc_pph21,2); //PPH21
                    $foot['sum_pph21'] += $value->deduc_pph21;
//                    $row[] = number_format($total_decrement,2); //Proportional
                    $row['thp'] = number_format($thp_total,2); //THP
                    $row['emp_id'] = $value->id;
                    $row['company_id'] = $value->company_id;

                    $foot['sum_prop'] += $total_decrement;

                    $data[] = $row;

                }
                $error = 0;
                $source = "EMP";
            } else {
                $error = 1;
                $data = null;
                $source = null;
            }
        }

        // $rep_bank_code = array("002" => "BRI","008" => "MANDIRI","009" => "BNI","120" => "SUMSEL","014" => "BCA");

        $rep_bank_code = FacadesDB::table('master_banks')->get()->pluck('bank_name', 'bank_code');

        $empType = Hrd_employee_type::withTrashed()->get();
        $typeEmp = [];
        foreach($empType as $item){
            $typeEmp[$item->id] = $item->name;
        }

        $val = array(
            'error' => $error,
            'data' => $data,
            't' => $t,
            'periode' => date('F Y', strtotime($y."-".$m)),
            'bank_code' => $rep_bank_code,
            'source' => $source,
            'emp_type' => $typeEmp
        );

        if ($request->act == 'remarks'){
            $view = "payroll.btl_remarks";
        } else {
            $view = "payroll.btl";
        }

        $data_remarks = Hrd_salary_remarks::where('periode',  $y."-".$m)
            ->where('company_id', Session::get('company_id'))
            ->get();
        $remarks = array();
        foreach ($data_remarks as $item){
            $remarks[$item->emp_id] = $item;
        }

        return view($view, [
            'data' => $val,
            'remarks' => $remarks
        ]);
    }

    function save_remarks(Request $request){
        $thp = $request->thp;
        $thp_old = $request->thp_old;
        $remarks = $request->remarks;

        foreach ($thp as $thpKey => $thpValue){
            if ($thpValue != $thp_old[$thpKey]){
                $find = Hrd_salary_remarks::where('periode', $request->periode)
                    ->where('emp_id', $thpKey)
                    ->where('company_id', Session::get('company_id'))
                    ->first();
                if (empty($find)){
                    $hrd_remarks = new Hrd_salary_remarks();
                    $hrd_remarks->emp_id = $thpKey;
                    $hrd_remarks->company_id = Session::get('company_id');
                    $hrd_remarks->periode = $request->periode;
                    $hrd_remarks->thp = $thpValue;
                    $hrd_remarks->remarks = $remarks[$thpKey];
                    $hrd_remarks->save();
                } else {
                    $hrd_remarks = Hrd_salary_remarks::find($find->id);
                    $hrd_remarks->thp = $thpValue;
                    $hrd_remarks->remarks = $remarks[$thpKey];
                    $hrd_remarks->save();
                }
            }
        }

        return redirect()->back();
    }

    function print_slip($id, $period){
        $emp = Hrd_employee::find($id);
        $xperiod = explode("-", $period);
        $archive = Hrd_salary_archive::where('emp_id', $id)
            ->where('archive_period', $xperiod[1]."-".$xperiod[0])
            ->firstOrFail();
        $pref = Preference_config::where('id_company', $emp->company_id)->first();

        $total_sal = base64_decode($archive->salary) + $archive->voucher + $archive->allow_bpjs_tk + $archive->allow_bpjs_kes + $archive->allow_jshk;

        $yearly_bonus = 0;
        if ($xperiod[1] == $pref->bonus_period) {
            $yearly_bonus = $emp->yearly_bonus * $total_sal;
        }

        $comp = ConfigCompany::find($emp->company_id);

        $arr_thr_period = explode('\n', $pref->thr_period);
            $arr_thr_period[0] = strip_tags($arr_thr_period[0]);
            $thr_period = str_pad($xperiod[1], 2, '0', STR_PAD_LEFT).'-'.$xperiod[0];

        $total_deduc = $archive->deduction + $archive->lateness + $archive->deduc_pph21 + $archive->deduc_jshk + $archive->deduc_bpjs_tk + $archive->deduc_bpjs_kes;

        $salary_total = $total_sal + $archive->wh_nom + $archive->odo_nom + $archive->field_nom + $archive->bonus + $archive->ovt_nom + $archive->thr;

        $view = view('payroll.slip', [
            'emp' => $emp,
            'archive' => $archive,
            'period' => $period,
            'total_sal' => $salary_total,
            'total_deduc' => $total_deduc,
            'yearly_bonus' => $yearly_bonus,
            'pref' => $comp
        ]);

        // return $view;

        $mpdf = new Mpdf([
            'default_font_size' => 11,
            'default_font' => 'arial',
            'tempDir'=>storage_path('tempdir')
        ]);
        $mpdf->SetAuthor('PT. ');
        $mpdf->SetTitle('Payroll Total Archive');
        $mpdf->SetSubject('total archive');
        $mpdf->SetKeywords('archive, PDF');

        $mpdf->SetDisplayMode('fullpage');
        // $stylesheet = file_get_contents('mpdf-style-payslip.css');
        // $mpdf->WriteHTML($stylesheet,1);
        $mpdf->writeHtml($view);
        // $mpdf->RestartDocTemplate();
        $mpdf->Output('Payroll Payslip.pdf','I');
    }

    function get_total_payroll(Request $request){
        dd($request);
        $configCompany = ConfigCompany::find(Session::get('company_id'));
        $id_companies = [];
        if(!empty($configCompany->id_parent)){
            $id_companies[] = $configCompany->id_parent;
        } else {
            $companyChild = ConfigCompany::where('id_parent', $configCompany->id_parent)->get();
            foreach($companyChild as $item){
                $id_companies[] = $item->id;
            }
        }

        $data['type'] = Hrd_employee_type::whereIn('company_id', $id_companies)
            ->where('company_exclude', 'not like', '%"'.$configCompany->id.'"%')
            ->orWhereNull("company_exclude")
            ->get();

        $archive = Hrd_salary_archive::where('archive_period', $request->period)
            ->where('company_id', Session::get('company_id'))
            ->get();

        $row = [];

        if(count($archive) > 0){
            foreach($archive as $item){

            }
        } else {

        }
    }
}
