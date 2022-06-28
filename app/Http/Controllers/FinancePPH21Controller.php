<?php

namespace App\Http\Controllers;

use App\Models\Finance_pph21;
use App\Models\Hrd_employee;
use App\Models\Hrd_employee_history;
use Illuminate\Http\Request;
use Session;

class FinancePPH21Controller extends Controller
{
    function index(){
        $yearnow = date('Y');
        $yearbefore = date('Y', strtotime('-5 years', strtotime($yearnow)));
        $years = array();
        while ($yearnow >= $yearbefore){
            $years[] = intval($yearnow);
            $yearnow--;
        }

        return view('finance.pph21.index', [
            'years' => $years
        ]);
    }

    function find(Request $request){
        $pph21 = array();
        $emp = Hrd_employee::where('company_id', Session::get('company_id'))->get();
        $emp_hist = Hrd_employee_history::where('activity', 'in')->get();
        $act_date = array();
        foreach ($emp_hist as $item){
            $act_date[$item->emp_id] = $item->act_date;
        }
        if (empty($pph21)){
            $row = array();
            foreach ($emp as $item){
                $empid = (!empty($item->old_id)) ? $item->old_id : $item->id;
                $sal = base64_decode($item->salary) + base64_decode($item->transport) + base64_decode($item->meal) + base64_decode($item->house) + base64_decode($item->health);
                $in_date = $act_date[$empid];
                $diff_date = date_diff(date_create($in_date), date_create(date("Y")."-12-01"));
                $yeardiff = $diff_date->format("%y");
                if ($yeardiff > 0){
                    $mnthdiff = 12;
                } else {
                    $mnthdiff = intval($diff_date->format("%m"));
                }
                $tunjanganJabatan = $sal * 0.05;
                if ($tunjanganJabatan > (500000 * $mnthdiff)){
                    $tunjanganJabatan = 500000 * $mnthdiff;
                } else {
                    $tunjanganJabatan = $sal * 0.05;
                }
                $netperyear = ($sal - $tunjanganJabatan) * (($mnthdiff <= 12) ? $mnthdiff : 12);
                $ptkp = 54000000;
                if ($item->status_marriage == 1){
                    if ($item->allowance_family <= 3){
                        $ptkp += 4500000 * $item->allowance_family;
                    } else {
                        $ptkp += 4500000 * 3;
                    }
                }
                $ptkptanggungan = 4500000 * $item->allowance_family;
                $pkp = $netperyear - $ptkp - $ptkptanggungan;
                $pkpround = round($pkp);
                if ($pkpround < 0){
                    $deduc_pph21 = 0;
                } elseif($pkpround > 0 && $pkpround <= 50000000){
                    $deduc_pph21 = ($pkpround * 0.05) / (($mnthdiff <= 12) ? $mnthdiff : 12);
                } elseif($pkpround > 50000000 && $pkpround <= 250000000){
                    $deduc_pph21 = (($pkpround - 50000000)*0.15+2500000) / (($mnthdiff <= 12) ? $mnthdiff : 12);
                } elseif($pkpround > 250000000 && $pkpround <= 500000000){
                    $deduc_pph21 = (($pkpround - 250000000)*0.25+32500000) / (($mnthdiff <= 12) ? $mnthdiff : 12);
                } else {
                    $deduc_pph21 = (($pkpround - 500000000)*0.3+95000000) / (($mnthdiff <= 12) ? $mnthdiff : 12);
                }

                $data['employee'] = $item;
                $data['salary'] = $sal;
                $data['months'] = $mnthdiff;
                $data['tunjangan_jabatan'] = $tunjanganJabatan;
                $data['ptkp'] = $ptkp;
                $data['ptkp_tanggungan'] = $ptkptanggungan;
                $data['netperyear'] = $netperyear;
                $data['pkp'] = $pkpround;
                $data['pph21peryear'] = $deduc_pph21 * 12;
                $data['pph21permonth'] = $deduc_pph21;
                $row[] = $data;
            }
        }

        return view('finance.pph21.result', [
            'row' => $row
        ]);
    }
}
