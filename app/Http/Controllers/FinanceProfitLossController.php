<?php

namespace App\Http\Controllers;

use Session;
use Mpdf\Mpdf;
use App\Models\Finance_coa;
use Illuminate\Http\Request;
use App\Models\Finance_pl_save;
use App\Models\Pref_tax_config;
use App\Models\Finance_bl_files;
use App\Models\Marketing_project;
use App\Models\Finance_coa_history;
use App\Models\Report_exchange_rate;
use Illuminate\Support\Facades\Auth;
use App\Models\Marketing_c_prognosis;
use App\Models\Finance_invoice_out_detail;
use App\Models\Finance_profit_loss_setting;

class FinanceProfitLossController extends Controller
{

    private $list_oe = array('gaji', 'ppe&hse', 'asuransi_kesehatan_karyawan', 'pelatihan', 'perlengkapan_kantor', 'fotokopi', 'post&kurir', 'telepon&fax', 'internet', 'listrik', 'dapur', 'kendaraan', 'legal', 'administrasi_bank', 'pemeliharaan_komputer', 'pemeliharaan_kendaraan', 'pemeliharaan_gedung', 'konsultan', 'pbb', 'asuransi_gedung', 'asuransi_kendaraan', 'iklan', 'sosial', 'penyusutan_mesin', 'penyusutan_peralatan_kantor', 'penyusutan_kendaraan', 'penyusutan_gedung', 'sewa_warehouse/mess', 'keamanan', 'pajak_lain-lain', 'bunga');

    function index(Request $request){
        $coa = Finance_coa::all();
        $setting = Finance_profit_loss_setting::where('company_id', Session::get('company_id'))->first();
        $project = Marketing_project::where('company_id', Session::get('company_id'))->get()->pluck('prj_name', 'id');

        $data = [];
        $save = 0;
        if(isset($request->submit)){
            $data = $this->find($request);
            if($request->submit == "pdf"){
                $from = $request->start;
                $to = $request->end;
                $pdf = view('finance.pl.pdf', [
                    'coa' => $coa,
                    'setting' => $setting,
                    'project' => $project,
                    'oe' => $this->list_oe,
                    'data' => $data,
                    'from' => $from,
                    'to' => $to
                ]);

                // return $pdf;
                $mpdf = new Mpdf();
                $mpdf->WriteHTML($pdf);
                $file_name = 'media/reports/profit_loss_'.date("Ymd", strtotime($from)).'_'.date("Ymd", strtotime($to)).'_'.date("Y_m_d_h_i").".pdf";
                $mpdf->Output($file_name, \Mpdf\Output\Destination::FILE);
                $bl_file = new Finance_bl_files();
                $bl_file->date_from = $from;
                $bl_file->date_to = $to;
                $bl_file->file = $file_name;
                $bl_file->type = "p";
                $bl_file->created_by = Auth::user()->username;
                $bl_file->company_id = Session::get('company_id');
                $bl_file->save();
            }
            $save = 1;
        }

        return view('finance.pl.index', [
            'coa' => $coa,
            'setting' => $setting,
            'project' => $project,
            'oe' => $this->list_oe,
            'data' => $data,
            'save' => $save
        ]);
    }

    function setting(Request $request){
        $opi = json_encode($request->oi);
        $cs = json_encode($request->cs);
        $ope = json_encode($request->oe);
        $oti = json_encode($request->oti);
        $ote = json_encode($request->ote);

        $setting = Finance_profit_loss_setting::where('company_id', Session::get('company_id'))->first();
        if ($setting == null){
            $iSetting = new Finance_profit_loss_setting();
            $iSetting->operating_income = $opi;
            $iSetting->cost_sales = $cs;
            $iSetting->operating_expense = $ope;
            $iSetting->other_income = $oti;
            $iSetting->other_expense = $ote;
            $iSetting->tax = $request->tax;
            $iSetting->company_id = Session::get('company_id');
            $iSetting->save();
        } else {
            $setting->operating_income = $opi;
            $setting->cost_sales = $cs;
            $setting->operating_expense = $ope;
            $setting->other_income = $oti;
            $setting->other_expense = $ote;
            $setting->tax = $request->tax;
            $setting->save();
        }

        return redirect()->route('pl.index');
    }

    function find(Request $request){
        $setting = Finance_profit_loss_setting::where('company_id', Session::get('company_id'))->first();
        $project = $request->projects;

        $data['data'] = [];

        $pl = [];

        if(!empty($setting)){
            $coa_oi = $this->find_coa_code(json_decode($setting->operating_income, true));
            $coa_sales = $this->find_coa_code(json_decode($setting->cost_sales, true));
            $coa_oe = $this->find_coa_code(json_decode($setting->operating_expense, true));
            // dd($coa_oe);
            $coa_oti = $this->find_coa_code(json_decode($setting->other_income, true));
            $coa_ote = $this->find_coa_code(json_decode($setting->other_expense, true));

            // array_push($data['data'], $this->find_coa_his($coa_oi, "Sales", $request->start, $request->end));
            // array_push($data['data'], $this->find_coa_his($coa_oe, "Operating Expense", $request->start, $request->end));
            // array_push($data['data'], $this->find_coa_his($coa_oti, "Other Incomes", $request->start, $request->end));
            // array_push($data['data'], $this->find_coa_his($coa_ote, 'Other Expenses', $request->start, $request->end));

            $pl['sales'] = $this->find_coa_his($coa_oi, "Sales", $request->start, $request->end, $project);
            $pl['cost_of_sales'] = $this->find_coa_his($coa_sales, "Cost of Sales", $request->start, $request->end, $project);
            $pl['gross_profit'] = '$total["sales"] - $total["cost_of_sales"]';
            foreach($this->list_oe as $item){
                $row = [];
                if(isset($coa_oe[$item])){
                    $row = $this->find_coa_his($coa_oe[$item], "Operational Expense", $request->start, $request->end, $project);
                }
                $pl['operational_expenses'][$item] = $row;
            }
            $pl['operating_profit'] = '$total["gross_profit"] - $total["operational_expenses"]';
            $pl['other_incomes'] = $this->find_coa_his($coa_oti, "Other Incomes", $request->start, $request->end, $project);
            $pl['other_expenses'] = $this->find_coa_his($coa_ote, 'Other Expenses', $request->start, $request->end, $project);
            $pl['total_other'] = '$total["other_incomes"] - $total["other_expenses"]';
            $pl['laba_sebelum_pajak'] = '$total["operating_profit"] - $total["total_other"]';
            $pl['pajak_penghasilan'] = '$total["laba_sebelum_pajak"] * ($rate/100)';
            $pl['laba_setelah_pajak'] = '$total["laba_sebelum_pajak"] - $total["pajak_penghasilan"]';

            $val = array(
                'data' => $pl,
            );

            if ($request->pdf == 1) {
                $valPdf = [];
                foreach ($val as $key => $value) {
                    foreach ($value as $nval) {
                        $valPdf[$nval[2]][$key][] = $nval;
                    }
                }
                $from = $request->start;
                $to = $request->end;
                $pdf = view('finance.pl.pdf', compact('valPdf', 'from', 'to'));
                $mpdf = new Mpdf();
                $mpdf->WriteHTML($pdf);
                $file_name = 'media/reports/profit_loss_'.date("Ymd", strtotime($from)).'_'.date("Ymd", strtotime($to)).'_'.date("Y_m_d_h_i").".pdf";
                $mpdf->Output($file_name, \Mpdf\Output\Destination::FILE);
                $bl_file = new Finance_bl_files();
                $bl_file->date_from = $from;
                $bl_file->date_to = $to;
                $bl_file->file = $file_name;
                $bl_file->type = "p";
                $bl_file->created_by = Auth::user()->username;
                $bl_file->company_id = Session::get('company_id');
                $bl_file->save();
            }
        }




        return $pl;
    }

    function list(){
        $list = Finance_bl_files::where('company_id', Session::get('company_id'))
            ->where('type', 'p')
            ->orderBy('id', 'desc')
            ->get();

        return view('finance.pl.list', compact('list'));
    }

    function find_coa_his($x, $y, $dateFrom, $dateTo, $project){
        $c = Finance_coa::all();
        $coa_name = [];
        foreach ($c as $item){
            $coa_name[$item->code] = $item->name;
        }

        $whereProject = " 1";
        if(!empty($project)){
            $whereProject = "(";
            foreach($project as $prj){
                $whereProject .= " description like '%[$prj]%' or";
            }
            $whereProject = substr($whereProject, 0, -2);
            $whereProject .= ")";
        }

        $his = Finance_coa_history::whereBetween('coa_date',[$dateFrom,$dateTo])
            ->whereIn('no_coa', $x)
            ->whereRaw($whereProject)
            ->where('company_id', Session::get('company_id'))
            ->get();
        $coa = [];

        $rates = Report_exchange_rate::orderBy('id', 'desc')->first();
        $arrRates = [];
        if(!empty($rates)){
            $arrRates = json_decode($rates->rates, true);
        }

        foreach ($his as $item){
            $sum = 0;
            $coa[$item->no_coa]['code'] = "[".$item->no_coa."] ".$coa_name[$item->no_coa];
            if ($item->debit != null || $item->debit != 0){
                $sum = $item->debit;
            } else {
                $sum = $item->credit * -1;
            }
            $coa[$item->no_coa]['type'] = $y;
            $multiplier = (isset($arrRates[$item->currency]) && $item->currency != "IDR") ? floatval(str_replace(",", "", $arrRates[$item->currency])) : 1;
            $coa[$item->no_coa]['amount'][] = abs($sum) * $multiplier;
        }

        return $coa;
    }

    function find_coa_code($x){
        $c = Finance_coa::all();
        $coa_code = [];
        $cc = [];
        $coa_oi = [];
        foreach ($c as $item){
            $coa_code[$item->parent_id][] = $item->code;
            $cc[$item->id] = $item->code;
        }

        $coa = [];
        if($this->is_assoc($x)){
            foreach($x as $key => $item){
                foreach($item as $n){
                    if(isset($cc[$n])){
                        $code = str_replace("0", "", $cc[$n]);
                        $coa = Finance_coa::where('parent_id', 'like', $code."%")->get();
                        $coa_oi[$key][] = $cc[$n];
                    }
                }
            }
        } else {
            foreach ($x as $item){
                $code = str_replace("0", "", $cc[$item]);
                $coa = Finance_coa::where('parent_id', 'like', $code."%")->get();
                $coa_oi[] = $cc[$item];
                foreach ($coa as $value){
                    if (!in_array($value->code, $coa_oi)){
                        $coa_oi[] = $value->code;
                    }
                }
            }
            $coa_oi = array_unique($coa_oi);
        }

        return $coa_oi;
    }

    public static function is_assoc(array $array)
    {
        // Keys of the array
        $keys = array_keys($array);

        // If the array keys of the keys match the keys, then the array must
        // not be associative (e.g. the keys array looked like {0:0, 1:1...}).
        return array_keys($keys) !== $keys;
    }

    public function update(Request $request){
        $from = $request->from;
        $to = $request->to;
        $amount = $request->total_val;

        $plsave = Finance_pl_save::where('from', $from)
            ->where('to', $to)
            ->where('company_id', Session::get('company_id'))
            ->first();

        if (empty($plsave)) {
            $plsave = new Finance_pl_save();
            $plsave->from = $from;
            $plsave->to = $to;
            $plsave->company_id = Session::get('company_id');
            $plsave->created_by = Auth::user()->username;
        } else {
            $plsave->updated_by = Auth::user()->username;
        }

        $plsave->amount = $amount;
        $plsave->save();

        return 1;
    }

    function indexPL(Request $request){
        $pl = [];
        $opex = [];
        $prj_sel = [];
        $id_project = Marketing_c_prognosis::all()->pluck('id_project');
        $projects = Marketing_project::where("company_id", Session::get("company_id"))
            ->whereIn("id", $id_project)
            ->orderBy('prj_name')
            ->get();
        $total = [];
        $data = [];
        if(isset($request->submit)){
            $prj_sel = Marketing_project::find($request->project);
            $pl = Marketing_c_prognosis::where("id_project", $prj_sel->id)
                ->where('category', 'cost')
                ->get();
            $opex = Marketing_c_prognosis::where("id_project", $prj_sel->id)
                ->where('category', 'operating_expenses')
                ->get();
            $total['revenue'] = 0;
            $sales = Marketing_c_prognosis::where("id_project", $prj_sel->id)
                ->where('category', 'sales')
                ->get();
            foreach($sales as $item){
                $exWhite = (!empty($item->whitelists)) ? json_decode($item['whitelists'],true) : [];
                $s_amount = 0;

                $hidelist = json_decode($item['blacklist']);
                $notInInv = "";
                if (!empty($hidelist)) {
                    foreach ($hidelist as $key => $value) {
                        foreach ($value as $list) {
                            if ($key == "inv_out") {
                                $notInInv .= $list.",";
                            }
                        }
                    }
                }
                $notInInv = rtrim($notInInv, ", ");
                $whereNotInInv = "";
                if ($notInInv != "") {
                    $whereNotInInv = " AND id not in ($notInInv) ";
                }
                if(count($exWhite) > 0){
                    if ( count($exWhite['inv_out']) > 0) {
                        for($irs = 0; $irs < count($exWhite['inv_out']); $irs++){
                            $wheresales = " (id_inv = ".$exWhite['inv_out'][$irs].") OR ";
                        }
                        $wheresales = substr($wheresales,0,-3);
                        $r_detail = Finance_invoice_out_detail::whereRaw($wheresales)
                            ->whereRaw($whereNotInInv)
                            ->whereNotNull("ceo_app_by")
                            ->get();
                        // $sql_detail3 = "SELECT * FROM inv_out_detail where ".$wheresales." AND ceo_app_by IS NOT NULL".$whereNotInInv;
                        // $r_detail3 = $db_fin->Execute($sql_detail3);
                        foreach($r_detail as $det){
                            $s_amount += $det['value_d'];
                            // if ($det['addcost'] > 0) {
                            //     $s_amount += $det['addcost'];
                            // }
                        }
                    }

                    if (count($exWhite['aggre_num']) > 0) {
                        for ($inum=0; $inum < count($exWhite['aggre_num']); $inum++) {
                            $r_num = Finance_invoice_out_detail::where('id_inv', $exWhite['aggre_num'][$inum])
                                ->whereNotNull('ceo_app_by')
                                ->get();
                            foreach($r_num as $val){
                                for ($itax=0; $itax < count($exWhite['taxes']); $itax++) {
                                    $taxes = (!empty($val->taxes)) ? json_decode($val->taxes) : [];
                                    foreach($taxes as $tax){
                                        $tx = Pref_tax_config::find($tax);
                                        if(!empty($tx)){
                                            $sum = $val['value_d'];
                                            $eval = eval("return $tx->formula;");
                                            $s_amount += $eval;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $total['revenue'] += $s_amount;
            }

            $mProg = new MarketingPrognosisController();
            $data = $mProg->cacl($request->project);

            // foreach($pl as $item){
            //     $white = $item['whitelists'];
            //     $powo = [];
            //     if($white) {
            //         $powo = json_decode($white, true);
            //     }
            //     dd($powo);
            //     $hidelist = json_decode($item['blacklist'], true);
            //     $notInWO = "";
            //     $notInPO = "";
            //     $notInCb = "";
            //     $notInRe = "";
            //     $notInTO = "";
            //     $notInSB = "";
            //     $notInPY = "";
            //     if (!empty($hidelist)) {
            //         foreach ($hidelist as $key => $value) {
            //             foreach ($value as $list) {
            //                 if ($key == "wo") {
            //                     $notInWO .= $list.",";
            //                 } elseif ($key == "po") {
            //                     $notInPO .= $list.",";
            //                 } elseif ($key == "to") {
            //                     $notInTO .= $list.",";
            //                 } elseif ($key == "cashbond") {
            //                     $notInCb .= $list.",";
            //                 } elseif ($key == "reimburse") {
            //                     $notInRe .= $list.",";
            //                 } elseif ($key == "subcost") {
            //                     $notInSB .= $list.",";
            //                 } elseif ($key == "payroll") {
            //                     $notInPY .= $list.",";
            //                 }
            //             }
            //         }
            //     }

            //     $whereNotInWO = "";
            //     $whereNotInPO = "";
            //     $whereNotInCb = "";
            //     $whereNotInRe = "";
            //     $whereNotInTO = "";
            //     $whereNotInSB = "";
            //     $whereNotInPY = "";
            //     $notInWO = rtrim($notInWO, ", ");
            //     $notInPO = rtrim($notInPO, ", ");
            //     $notInRe = rtrim($notInRe, ", ");
            //     $notInTO = rtrim($notInTO, ", ");
            //     $notInCb = rtrim($notInCb, ", ");
            //     $notInSB = rtrim($notInSB, ", ");
            //     if ($notInWO != "") {
            //         $whereNotInWO = " AND id not in ($notInWO) ";
            //     }

            //     if ($notInPO != "") {
            //         $whereNotInPO = " AND id not in ($notInPO) ";
            //     }

            //     if ($notInTO != "") {
            //         $whereNotInTO = " AND id not in ($notInTO) ";
            //     }

            //     if ($notInCb != "") {
            //         $whereNotInCb = " AND id not in ($notInCb) ";
            //     }

            //     if ($notInRe != "") {
            //         $whereNotInRe = " AND id not in ($notInRe) ";
            //     }

            //     if ($notInSB != "") {
            //         $whereNotInSB = " AND id not in ($notInSB) ";
            //     }

            //     if ($notInPY != "") {
            //         $whereNotInPY = " AND id not in ($notInPY) ";
            //     }

            //     if(isset($powo['po'])){
            //         dd($powo['po']);
            //         foreach($powo['po'] as $ipo => $po){

            //         }
            //     }
            // }
        }
        return view("report.pl.indexpl", compact("projects", "pl", "prj_sel", 'total', 'data', 'opex'));
    }
}
