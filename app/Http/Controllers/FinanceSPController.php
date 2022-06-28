<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App\Models\Asset_po;
use App\Models\Asset_wo;
use App\Models\Finance_loan;
use Illuminate\Http\Request;
use App\Models\Asset_type_wo;
use App\Models\Finance_leasing;
use App\Models\Finance_treasury;
use App\Models\Hrd_employee_type;
use App\Models\Finance_invoice_in;
use App\Models\Procurement_vendor;
use App\Models\Finance_coa_history;
use App\Models\Finance_loan_detail;
use App\Models\Finance_util_master;
use App\Models\Finance_util_salary;
use Illuminate\Support\Facades\Auth;
use App\Models\Finance_util_criteria;
use App\Models\Finance_util_instance;
use App\Models\Finance_invoice_in_pay;
use App\Models\Finance_leasing_detail;
use App\Models\Finance_schedule_payment;
use App\Models\Finance_treasury_history;
use App\Models\Finance_coa;

class FinanceSPController extends Controller
{
    function index(Request $request){
        for ($m=1; $m<=12; $m++) {
            $data['month'][$m] = date('F', mktime(0,0,0,$m, 1, date('Y')));
        }

        $data['type'] = array(
            'all' => 'All',
            'staff'=> 'Staff',
            'manager'=> 'Manager',
            'marketing'=> 'Marketing',
            'bod'=> 'BOD',
            'field'=> 'Field Engineer',
            'whbin'=> 'WH Bintaro',
            'whcil'=> 'WH Cileungsi',
            'konsultan'=> 'Konsultan',
            'local'=> 'Local'
        );

        $startyear = date('Y', strtotime('-10 years'));
        for ($i = 0; $i < 20; $i++){
            $data['years'][$i] = $startyear;
            $startyear++;
        }

        $val = array();

        $data['m'] = "";
        $data['y'] = "";

        if (isset($request->month)){
            $data['find'] = 1;
            $m = $request->month;
            $y = $request->years;
            $data['m'] = $m;
            $data['y'] = $y;
            $frst_date = "$y-".sprintf("%02d", $m)."-01";
            $lastdate = date('Y-m-t', strtotime($frst_date));

            // INVOICE PO & WO
            $inv_in = Finance_invoice_in::where('company_id', Session::get('company_id'))
                ->get();

            $vendor = Procurement_vendor::where('company_id', Session::get('company_id'))->get();
            $paper = array();
            $supplier = array();
            foreach ($vendor as $value){
                $supplier['name'][$value->id] = $value->name;
                $supplier['bank_acct'][$value->id] = $value->bank_acct;
            }

            $po = Asset_po::where('company_id', Session::get('company_id'))->get();
            $wo = Asset_wo::where('company_id', Session::get('company_id'))->get();
            foreach ($po as $value){
                $paper['PO'][$value->id]['paper_num'] = $value->po_num;
                if (isset($supplier['name'][$value->supplier_id])) {
                    $supp = $supplier['name'][$value->supplier_id];
                } else {
                    $supp = $value->supplier_id;
                }
                $paper['PO'][$value->id]['supplier'] = $supp;
                $paper['PO'][$value->id]['currency'] = $value->currency;
                $paper['PO'][$value->id]['gr_date'] = $value->gr_date;
                $paper['PO'][$value->id]['bgcolor'] = "#f099ff";
            }

            foreach ($wo as $value){
                $paper['WO'][$value->id]['paper_num'] = $value->wo_num;
                if (isset($supplier['name'][$value->supplier_id])) {
                    $supp = $supplier['name'][$value->supplier_id];
                } else {
                    $supp = $value->supplier_id;
                }
                $paper['WO'][$value->id]['supplier'] = $supp;
                $paper['WO'][$value->id]['currency'] = $value->currency;
                $paper['WO'][$value->id]['gr_date'] = $value->gr_date;
                $paper['WO'][$value->id]['bgcolor'] = "#fc9979";
            }

            foreach ($inv_in as $item) {
                $idPaper[$item->id] = $item->paper_id;
                $typePaper[$item->id] = $item->paper_type;
            }

           // dd($paper);
            $inv_master = Finance_invoice_in::where('company_id', Session::get('company_id'))
                ->get();
            $idInv = [];
            foreach ($inv_master as $value) {
                $idInv[] = $value->id;
            }

            $inv_pay = Finance_invoice_in_pay::whereBetween('pay_date', [$frst_date, $lastdate])
                // ->where('company_id', Session::get('company_id'))
                ->whereIn('inv_id', $idInv)
                ->orderBy('id', 'desc')
                ->get();
            foreach ($inv_pay as $item){
                if (isset($idPaper[$item->inv_id])){
                    $type = $typePaper[$item->inv_id];
                    $idpap = $idPaper[$item->inv_id];
                    if (isset($paper[$type][$idpap])){
                        $iPaper = $paper[$type][$idpap];
                        $table['id'] = $item->id;
                        $table['type'] = $type;
                        $table['date'] = $item->pay_date;
                        $table['paper'] = $iPaper['paper_num'];
                        $table['amount'] = $item->amount;
                        $table['status'] = ($item->paid == 1) ? 1 : 0;
                        $table['description'] = $item->description."<br><br> Vendor : <b>".$iPaper['supplier']."</b>";
                        $table['bgcolor'] = $iPaper['bgcolor'];
                        $val[] = $table;
                    }
                }
            }
            // dd($val);

            // LOAN
            $loan = Finance_loan::where('company_id', Session::get('company_id'))
                ->get();
            $loanId = [];
            foreach ($loan as $value){
                $loan_bank[$value->id] = $value->bank;
                $loan_type[$value->id] = $value->type;
                $loan_description[$value->id] = $value->description;
                $loanId[] = $value->id;
            }

            $loan_detail = Finance_loan_detail::whereIn('id_loan', $loanId)
                ->whereBetween('plan_date', [$frst_date, $lastdate])
                ->orderBy('id', 'desc')
                ->get();

            foreach ($loan_detail as $item){
                $table['id'] = $item->id;
                $table['type'] = "LOAN";
                $table['date'] = $item->plan_date;
                $table['paper'] = $loan_bank[$item->id_loan];
                $table['amount'] = $item->cicilan + $item->bunga;
                $table['status'] = ($item->status == "paid") ? 1 : 0;
                $table['description'] = $loan_description[$item->id_loan];
                $table['bgcolor'] = "#00ffdc";
                $val[] = $table;
            }
            // BR

            // LEASING
            $leasing = Finance_leasing::where('company_id', Session::get('company_id'))->get();
            $leasId = array();
            foreach ($leasing as $value){
                $leasing_subject[$value->id] = $value->subject;
                $leasing_vendor[$value->id] = $value->vendor;
                $leasId[] = $value->id;
            }


            $leasing_detail = Finance_leasing_detail::whereIn('id_leasing', $leasId)
                ->whereBetween('plan_date', [$frst_date, $lastdate])
                ->orderBy('id', 'desc')
                ->get();

            foreach ($leasing_detail as $item){
                $table['id'] = $item->id;
                $table['type'] = "LEASING";
                $table['date'] = $item->plan_date;
                $table['paper'] = $leasing_subject[$item->id_leasing];
                $table['amount'] = $item->cicilan + $item->bunga;
                $table['status'] = ($item->status == "paid") ? 1 : 0;
                $table['description'] = $leasing_vendor[$item->id_leasing];
                $table['bgcolor'] = "#ff75a4";
                $val[] = $table;
            }

            // UTIL
            $util = Finance_util_master::where('company_id', Session::get('company_id'))
                ->where('status', 'running')
                ->get();
            $data_util = [];
            $util_id = [];
            foreach ($util as $item){
                $data_util['subject'][$item->id] = $item->subject;
                $data_util['type'][$item->id] = $item->type;
                $util_id[] = $item->id;
            }

            $util_instance = Finance_util_instance::whereBetween('pay_date', [$frst_date, $lastdate])
                // ->where('company_id', Session::get('company_id'))
                ->whereIn('id_master', $util_id)
                ->orderBy('id', 'desc')
                ->get();
            // dd($util_instance);

            foreach ($util_instance as $item){
                $table['id'] = $item->id;
                $table['type'] = "UTIL";
                $table['date'] = $item->pay_date;
                $table['paper'] = $data_util['subject'][$item->id_master];
                if ($data_util['type'][$item->id_master] == "variable") {
                    $amount = ($item->amount_back > 0) ? $item->amount_back : 0;
                } else {
                    $amount = $item->amount_back;
                }
                $table['amount'] = $amount;
                $table['status'] = ($item->progress == "paid") ? 1 : 0;
                $table['description'] = $item->description;
                $table['bgcolor'] = "#7dd7ff";
                $val[] = $table;
            }

            // SALARY
            $util_salary = Finance_util_salary::whereBetween('plan_date', [$frst_date, $lastdate])
                ->where('company_id', Session::get('company_id'))
                ->orderBy('id', 'desc')
                ->get();
            foreach ($util_salary as $item){
                $table['id'] = $item->id;
                $table['type'] = "SALARY";
                $table['date'] = $item->plan_date;
                $table['paper'] = "Salary, Jamsostek, Pension of ".$item->position;
                $table['amount'] = $item->amount;
                $table['status'] = ($item->status == "paid") ? 1 : 0;
                $table['description'] = "Salary, Jamsostek, Pension of ".$item->position." periode ".date('F Y', strtotime($y."-".sprintf("%02d", $m)));
                $table['bgcolor'] = "#64dd17";
                $val[] = $table;
            }

            usort($val, function ($a, $b){
                return strtotime($a['date']) - strtotime($b['date']);
            });
        }

        $sort_type = [];
        foreach ($val as $key => $value) {
            $sort_type[$key] = $value['type'];
        }

        array_multisort($sort_type, SORT_ASC, $val);

        return view('finance.sp.index', [
            'data' => $data,
            'val' => $val
        ]);
    }

    function edit_date(Request $request){
        if ($request->type == "WO"){
            $wo = Finance_invoice_in_pay::find($request->id_item);
            $wo->pay_date = $request->date_item;
            $wo->save();
        } elseif ($request->type == "PO"){
            $wo = Finance_invoice_in_pay::find($request->id_item);
            $wo->pay_date = $request->date_item;
            $wo->save();
        } elseif ($request->type == "LEASING"){
            $wo = Finance_leasing_detail::find($request->id_item);
            $wo->plan_date = $request->date_item;
            $wo->save();
        } elseif ($request->type == "LOAN"){
            $wo = Finance_loan_detail::find($request->id_item);
            $wo->plan_date = $request->date_item;
            $wo->save();
        } elseif ($request->type == "SALARY"){
            $wo = Finance_util_salary::find($request->id_item);
            $wo->plan_date = $request->date_item;
            $wo->save();
        } elseif ($request->type == "UTIL"){
            $wo = Finance_util_instance::find($request->id_item);
            $wo->pay_date = $request->date_item;
            $wo->save();
        }

        if ($wo){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function pay($date){
        // INVOICE PO & WO
        $inv_in = Finance_invoice_in::all();
        $val = array();

        $vendor = Procurement_vendor::all();
        $paper = array();
        $supplier = array();
        foreach ($vendor as $value){
            $supplier[$value->id] = $value;
        }

        $po = Asset_po::where('company_id', Session::get('company_id'))->get();
        $wo = Asset_wo::where('company_id', Session::get('company_id'))->get();
        foreach ($po as $value){
            $paper['paper_num']['PO'][$value->id] = $value->po_num;
            $paper['supplier']['PO'][$value->id] = $value->supplier_id;
            $paper['currency'][$value->id] = $value->currency;
            $paper['gr_date'][$value->id] = $value->gr_date;
            $paper['bgcolor']['PO'][$value->id] = "#ab47bc";
        }

        foreach ($wo as $value){
            $paper['paper_num']['WO'][$value->id]= $value->wo_num;
            $paper['supplier']['WO'][$value->id] = $value->supplier_id;
            $paper['currency'][$value->id] = $value->currency;
            $paper['gr_date'][$value->id] = $value->gr_date;
            $paper['bgcolor']['WO'][$value->id] = "#ff7043";
        }

        foreach ($inv_in as $item) {
            $idPaper[$item->id] = $item->paper_id;
            $typePaper[$item->id] = $item->paper_type;
        }

        $treasury = Finance_treasury::where('company_id', Session::get('company_id'))->get();

//            dd($typePaper);

        $inv_master = Finance_invoice_in::where('company_id', Session::get('company_id'))
                ->get();
            $idInv = [];
            foreach ($inv_master as $value) {
                $idInv[] = $value->id;
            }


        $inv_pay = Finance_invoice_in_pay::where('pay_date', $date)
            ->whereRaw('(paid = 0 or paid is null)')
            ->whereIn('inv_id', $idInv)
            ->get();
        // dd($inv_master);
            // dd($idPaper);
        foreach ($inv_pay as $item){
            $table['id'] = $item->id;
            $table['type'] = "INVOICE IN";
            $table['date'] = $item->pay_date;
            $pType = $typePaper[$item->inv_id];
            $pId = $idPaper[$item->inv_id];
            // dd($paper);
            if (isset($paper['paper_num'][$typePaper[$item->inv_id]][$idPaper[$item->inv_id]])) {
                $paper_ = $paper['paper_num'][$typePaper[$item->inv_id]][$idPaper[$item->inv_id]];
            } else {
                $paper_ = '';
            }
            if (isset($paper['supplier'][$typePaper[$item->inv_id]][$idPaper[$item->inv_id]])) {
                $supp = $supplier[$paper['supplier'][$typePaper[$item->inv_id]][$idPaper[$item->inv_id]]]->name;
                $bank_acct = $supplier[$paper['supplier'][$typePaper[$item->inv_id]][$idPaper[$item->inv_id]]]->bank_acct;
            } else {
                $supp = "";
                $bank_acct = "";
            }

            $table['paper'] = $paper_;
            $table['row1'] = $supp;
            $table['row2'] =strip_tags($bank_acct);
            // $table['row1'] = "$supplier['name'][$paper['supplier'][$idPaper[$item->inv_id]]]";
            // $table['row2'] = "$supplier['bank_acct'][$paper['supplier'][$idPaper[$item->inv_id]]]";
            $table['row3'] = $item->amount;
            $table['bgcolor'] = $paper['bgcolor'][$pType][$pId];
            $val[] = $table;
        }

        // LOAN
        $loan = Finance_loan::where('company_id', Session::get('company_id'))->get();
        $loanID = [];
        foreach ($loan as $value){
            $loan_bank[$value->id] = $value->bank."-".$value->description;
            $loan_type[$value->id] = $value->type;
            $loan_description[$value->id] = $value->description;
            $loanID[] = $value->id;
        }
        $loan_detail = Finance_loan_detail::where('company_id', Session::get('company_id'))
            ->where('plan_date', $date)
            ->where('status', 'Planned')
            ->whereIn('id_loan', $loanID)
            ->get();

        foreach ($loan_detail as $item){
            $table['id'] = $item->id;
            $table['type'] = "LOAN";
            $table['date'] = $item->plan_date;
            $table['paper'] = $loan_bank[$item->id_loan];
            $table['row1'] = $loan_description[$item->id_loan];
            $table['row2'] = $loan_bank[$item->id_loan];
            $table['row3'] = $item->cicilan + $item->bunga;
            $table['bgcolor'] = "#00bfa5";
            $val[] = $table;
        }

        // BR

        // LEASING
        $leasing = Finance_leasing::where('company_id', Session::get('company_id'))->get();
        $leasingID = [];
        foreach ($leasing as $value){
            $leasing_subject[$value->id] = $value->subject;
            $leasing_vendor[$value->id] = $value->vendor;
            $leasingID[] = $value->id;
        }
        $leasing_detail = Finance_leasing_detail::where('status', 'Planned')
            ->where('plan_date', $date)
            ->whereIn('id_leasing', $leasingID)
            ->get();

        foreach ($leasing_detail as $item){
            if (isset($leasing_subject[$item->id_leasing])) {
                $table['id'] = $item->id;
                $table['type'] = "LEASING";
                $table['date'] = $item->plan_date;
                $table['paper'] = $leasing_subject[$item->id_leasing];
                $table['row1'] = $leasing_subject[$item->id_leasing];
                $table['row2'] = $leasing_vendor[$item->id_leasing];
                $table['row3'] = $item->cicilan + $item->bunga;
                $table['bgcolor'] = "#ff4081";
                $val[] = $table;
            }

        }

        // UTIL
        $util_crit = Finance_util_criteria::where('company_id', Session::get('company_id'))->get();
        $crit_name = [];
        foreach ($util_crit as $item){
            $crit_name[$item->id] = $item->name;
        }
        $util = Finance_util_master::where('company_id', Session::get('company_id'))
            ->where('status', 'running')
            ->get();
        $data_util = [];
        $util_id = [];
        foreach ($util as $item){
            $data_util['subject'][$item->id] = $item->subject;
            $data_util['classification'][$item->id] = (isset($crit_name[$item->classification])) ? $crit_name[$item->classification] : "";
            $util_id[] = $item->id;
        }

        $util_instance = Finance_util_instance::where('pay_date', $date)
            // ->where('company_id', Session::get('company_id'))
            ->whereIn('id_master', $util_id)
            ->where('progress', '!=', 'paid')
            ->get();

        foreach ($util_instance as $item){
            $table['id'] = $item->id;
            $table['type'] = "UTIL";
            $table['date'] = $item->pay_date;
            $table['paper'] = $data_util['subject'][$item->id_master];
            $table['row1'] = $data_util['subject'][$item->id_master];
            $table['row2'] = $data_util['classification'][$item->id_master];
            $table['row3'] = $item->amount_back;
            $table['bgcolor'] = "#03a9f4";
            $val[] = $table;
        }

        // SALARY
        // SALARY
        $util_salary = Finance_util_salary::where('plan_date', $date)
            ->where('company_id', Session::get('company_id'))
            ->where('status', '!=', 'paid')
            ->get();
        foreach ($util_salary as $item){
            $table['id'] = $item->id;
            $table['type'] = "SALARY";
            $table['date'] = $item->plan_date;
            $table['paper'] = "Salary of ".$item->position;
            $table['row1'] = "Salary, Jamsostek, Pension of ".$item->position;
            $table['row2'] = date('F Y', strtotime($item->plan_date));
            $table['row3'] = $item->amount;
            $table['bgcolor'] = "#64dd17";
            $val[] = $table;
        }

        $type = array();
        foreach ($val as $value){
            if (!in_array($value['type'], $type)){
                $type[] = $value['type'];
            }
        }

        return view('finance.sp.view', [
            'date' => $date,
            'items' => $val,
            'type' => $type,
            'source' => $treasury
        ]);
    }

    function confirm(Request $request){
       // dd($request);
        $po = Asset_po::where('company_id', Session::get('company_id'))->get();
        $wo = Asset_wo::where('company_id', Session::get('company_id'))->get();
        foreach ($po as $value){
            $paper['paper_num']['PO'][$value->id] = $value->po_num;
            $paper['supplier'][$value->id] = $value->supplier_id;
            $paper['currency'][$value->id] = $value->currency;
            $paper['gr_date'][$value->id] = $value->gr_date;
            $paper['bgcolor']['PO'][$value->id] = "#ab47bc";
        }

        foreach ($wo as $value){
            $paper['paper_num']['WO'][$value->id]= $value->wo_num;
            $paper['supplier'][$value->id] = $value->supplier_id;
            $paper['currency'][$value->id] = $value->currency;
            $paper['gr_date'][$value->id] = $value->gr_date;
            $paper['bgcolor']['WO'][$value->id] = "#ff7043";
        }

        $coa = Finance_coa::all()->pluck('code', 'id');

        $ids = $request->id;
        $iType = $request->type;
        $source = $request->source;
        $to_idr = $request->_to_idr;
        for ($i=0; $i < count($source); $i++){
            if ($source[$i] != null){
                $amount[$i] = 0;
                $desc[$i] = "";
                $type_pay[$i] = "";
                $subject[$i] = "";
                $tc[$i] = "";
                $prj_code = "";
                $project = null;
                $bunga[$i] = null;
                if ($iType[$i] == "INVOICE IN"){
                    $inv_pay = Finance_invoice_in_pay::where('id', $ids[$i])->first();
                    $inv = Finance_invoice_in::find($inv_pay->inv_id);
                    if (strtolower($inv->paper_type) == "po") {
                        $poInv[$i] = Asset_po::find($inv->paper_id);
                        $prj_code = ($poInv[$i]->project > 100) ? $poInv[$i]->project : sprintf("%03d", $poInv[$i]->project);
                        if(!empty($poInv[$i]->tc_id)){
                            if(isset($coa[$poInv[$i]->tc_id])){
                                $tc[$i] = $coa[$poInv[$i]->tc_id];
                                $prj_code = ($poInv[$i]->project > 100) ? $poInv[$i]->project : sprintf("%03d", $poInv[$i]->project);
                            }
                        }
                        $project = $poInv[$i]->project;
                    } else {
                        $woInv[$i] = Asset_wo::find($inv->paper_id);
                        $prj_code = ($woInv[$i]->project > 100) ? $woInv[$i]->project : sprintf("%03d", $woInv[$i]->project);
                        if(!empty($woInv[$i]->tc_id)){
                            if(isset($coa[$woInv[$i]->tc_id])){
                                $tc[$i] = $coa[$woInv[$i]->tc_id];
                                $prj_code = ($woInv[$i]->project > 100) ? $woInv[$i]->project : sprintf("%03d", $woInv[$i]->project);
                            }
                        }
                        $project = $woInv[$i]->project;
                    }

                    $inv->amount_left = $inv->amount_left - $inv_pay->amount;
                    if ($inv->amount_left <= 0){
                        $inv->status = "paid";
                    }

                    $uInv = Finance_invoice_in_pay::find($ids[$i]);
                    $uInv->paid = 1;
                    $id_paper = $inv->paper_id;
                    $type_paper = $inv->paper_type;

                    $amount[$i] = $inv_pay->amount * -1;
                    $type = $type_paper;
                    $subject[$i] = $paper['paper_num'][$type_paper][$id_paper];
                    $desc[$i] = "[SP] Schedule Payment for " . $paper['paper_num'][$type_paper][$id_paper]." [$prj_code] - ".$uInv->description;

                    $uInv->save();
                    $inv->save();
                } elseif ($iType[$i] == "LEASING"){
                    $leasing_det = Finance_leasing_detail::where('id', $ids[$i])->first();
                    $leasing = Finance_leasing::find($leasing_det->id_leasing);

                    $upLeas = Finance_leasing_detail::find($ids[$i]);
                    $upLeas->status = "paid";
                    $upLeas->save();

                    if(!empty($leasing->tc_id) && isset($coa[$leasing->tc_id])){
                        $tc[$i] = $coa[$leasing->tc_id];
                    }

                    $amount[$i] = ($leasing_det->cicilan) * -1;
                    $bunga[$i] = $leasing_det->bunga * -1;
                    $type = "LEASING";
                    $subject[$i] = "LEASING ".$leasing->subject;
                    $desc[$i] = "[SP] Schedule Payment for Leasing ".$leasing->subject." ".$leasing->vendor;
                } elseif ($iType[$i] == "LOAN"){
                    $loan_det = Finance_loan_detail::where('id', $ids[$i])->first();
                    $loan = Finance_loan::find($loan_det->id_loan);

                    $upLeas = Finance_loan_detail::find($ids[$i]);
                    $upLeas->status = "paid";

                    if(!empty($loan->tc_id) && isset($coa[$loan->tc_id])){
                        $tc[$i] = $coa[$loan->tc_id];
                    }

                    $amount[$i] = ($loan_det->cicilan) * -1;
                    $bunga[$i] = $loan_det->bunga * -1;
                    $type = "LOAN";
                    $subject[$i] = "LOAN ".$loan->bank;
                    $desc[$i] = "[SP] Schedule Payment for Loan ".$loan->bank." ".$loan->description;
                    $upLeas->save();
                } elseif ($iType[$i] == "UTIL"){
                    $util_instance = Finance_util_instance::where('id', $ids[$i])->first();

                    $upUtilIns = Finance_util_instance::find($ids[$i]);
                    $upUtilIns->progress = "paid";
                    $upUtilIns->save();

                    $utilMaster = Finance_util_master::find($upUtilIns->id_master);
                    if(!empty($utilMaster->tc_id)){
                        if(isset($coa[$utilMaster->tc_id])){
                            $tc[$i] = $coa[$utilMaster->tc_id];
                        }
                    }

                    $amount[$i] = $util_instance->amount_back * -1;
                    $type = "UTIL";
                    $subject[$i] = "UTIL ".$util_instance->subject;
                    $desc[$i] = "[SP] Schedule Payment for Utilization ".$util_instance->subject;
                } elseif ($iType[$i] == "SALARY"){
                    $util_salary = Finance_util_salary::find($ids[$i]);
                    $emp_type = Hrd_employee_type::where('name', $util_salary->position)->first();
                    if(!empty($emp_type)){
                        if(!empty($emp_type->tc_id)){
                            $tc_id = json_decode($emp_type->tc_id, true);
                            $comp_id = Session::get('company_id');
                            $comp_parent = Session::get('company_id_parent');
                            $_tc = "";
                            if(isset($tc_id[$comp_id])){
                                $_tc = $tc_id[$comp_id];
                            } elseif (isset($tc_id[$comp_parent])) {
                                $_tc = $tc_id[$comp_parent];
                            }

                            if($_tc != ""){
                                if(isset($coa[$_tc])){
                                    $tc[$i] = $coa[$_tc];
                                }
                            }
                        }
                    }

                    $util_salary->status = "paid";
                    $util_salary->save();
                    $amount[$i] = $util_salary->amount * -1;
                    $type = "SALARY";
                    $subject[$i] = "Salary, jamsostek, pension of ".$util_salary->position." periode ".date('F Y', strtotime($util_salary->plan_date));
                    $desc[$i] = "[SP] Salary, jamsostek, pension of ".$util_salary->position." periode ".date('F Y', strtotime($util_salary->plan_date));
                }
                $am = $amount[$i];
                if(!empty($to_idr[$i])){
                    $am = str_replace(",", "", $to_idr[$i]) * -1;
                }

                $tre_his = new Finance_treasury_history();
                $tre_his->id_treasure = $source[$i];
                $tre_his->project = $project;
                $tre_his->date_input = $request->date_input;
                $tre_his->description = $desc[$i];
                $tre_his->IDR = $am;
                $tre_his->PIC = Auth::user()->username;
                $tre_his->company_id = Session::get('company_id');
                $tre_his->save();

                $tre = Finance_treasury::find($tre_his->id_treasure);

                if(!empty($bunga[$i])){
                    $tre_his_bunga = new Finance_treasury_history();
                    $tre_his_bunga->id_treasure = $source[$i];
                    $tre_his_bunga->date_input = $request->date_input;
                    $tre_his_bunga->description = $desc[$i]." - BUNGA";
                    $tre_his_bunga->IDR = $bunga[$i];
                    $tre_his_bunga->PIC = Auth::user()->username;
                    $tre_his_bunga->company_id = Session::get('company_id');
                    $tre_his_bunga->save();
                }

                if(!empty($tc[$i])){
                    if(!empty($tre->bank_code)){
                        $iCoa = new Finance_coa_history();
                        $iCoa->no_coa = $tre->bank_code;
                        $iCoa->coa_date = $tre_his->date_input;
                        $iCoa->project = $project;
                        $iCoa->credit = abs($am);
                        $iCoa->id_treasure_history = $tre_his->id;
                        $iCoa->currency = $tre->currency;
                        $iCoa->created_by = Auth::user()->username;
                        $iCoa->description = $tre_his->description;
                        $iCoa->approved_at = date('Y-m-d H:i:s');
                        $iCoa->approved_by = Auth::user()->username;
                        $iCoa->company_id = Session::get('company_id');
                        $iCoa->save();
                    }

                    $iCoa = new Finance_coa_history();
                    $iCoa->no_coa = $tc[$i];
                    $iCoa->coa_date = $tre_his->date_input;
                    $iCoa->project = $project;
                    $iCoa->debit = abs($am);
                    $iCoa->id_treasure_history = $tre_his->id;
                    $iCoa->currency = $tre->currency;
                    $iCoa->created_by = Auth::user()->username;
                    $iCoa->description = $tre_his->description;
                    $iCoa->approved_at = date('Y-m-d H:i:s');
                    $iCoa->approved_by = Auth::user()->username;
                    $iCoa->company_id = Session::get('company_id');
                    $iCoa->save();
                }

                $tre = Finance_treasury::find($source[$i]);
                $tre->IDR = $tre->IDR + $am;
                $tre->save();

                $sp = new Finance_schedule_payment();
                $sp->input_time = date('Y-m-d');
                $sp->payment_type = $type;
                $sp->sp_date = $request->date_input;
                $sp->description = $subject[$i];
                $sp->IDR = $am;
                $sp->PIC = Auth::user()->username;
                $sp->company_id = Session::get('company_id');
                $sp->save();

                if(!empty($bunga[$i])){

                    $sp = new Finance_schedule_payment();
                    $sp->input_time = date('Y-m-d');
                    $sp->payment_type = $type;
                    $sp->sp_date = $request->date_input;
                    $sp->description = $subject[$i]." - BUNGA";
                    $sp->IDR = $bunga[$i];
                    $sp->PIC = Auth::user()->username;
                    $sp->company_id = Session::get('company_id');
                    $sp->save();
                }
            }
        }

        return redirect()->route('sp.index');
    }

    public function getSalaryFinancing(){
        $salaryfins = Finance_util_salary::orderBy('id','desc')
            ->where('company_id', \Session::get('company_id'))
            ->get();

        return view('finance.salary_financing.index',[
            'salaryfins' => $salaryfins,
        ]);
    }

    public function paySalaryFinancing(Request $request){
        Finance_util_salary::where('id', $request['id'])
            ->update([
                'plan_date' => $request['plan_date'],
                'status' => 'paid',
            ]);

        return redirect()->route('salfin.index');
    }

    public function getSalaryFinancingStat(){
        $salaryfinstat = Finance_util_salary::select(
            DB::raw('currency'),
            DB::raw('YEAR(salary_date) as year'),
            DB::raw('SUM(amount) as sum_amount'),
            DB::raw('SUM(jamsostek) as sum_jam'),
            DB::raw('SUM(health_insurance) as sum_health'),
            DB::raw('SUM(pension) as sum_pension')
        )
            ->groupBy('year','currency')
            ->get();

        return view('finance.salary_financing.stat',[
            'salaryfinstats' => $salaryfinstat
        ]);
    }

    function history(Request $request){
//        dd($request);
        $hist = Finance_schedule_payment::where('sp_date', $request->date)
            ->where('company_id', Session::get('company_id'))
            ->where('IDR', '!=', 0)
            ->get();

        $sum = 0;
        $data = [];
        foreach ($hist as $item){
            $list['date'] = $item->sp_date;
            $list['desc'] = ucwords($item->payment_type)." - ".$item->description;
            $list['exec'] = $item->PIC;
            $list['amount'] = number_format($item->IDR, 2);
            $sum += $item->IDR;
            $data[] = $list;
        }

        $val = array(
            'error' => 0,
            'sum' => $sum,
            'data' => $data
        );

        return json_encode($val);
    }

    function update_util(Request $request){
        $util = Finance_util_instance::find($request->id_util);
        $util->amount_back = $request->amount_back;
        $util->save();

        return redirect()->back();
    }

    function delete_salary_financing($id){
        $salfin = Finance_util_salary::find($id);


        $salfin->delete();

        return redirect()->back();
    }

}
