<?php

namespace App\Http\Controllers;

use App\Models\Asset_po;
use App\Models\Asset_po_detail;
use App\Models\Asset_type_po;
use App\Models\Asset_type_wo;
use App\Models\Asset_wo;
use App\Models\Asset_wo_detail;
use App\Models\Chart_custom;
use App\Models\Finance_invoice_out;
use App\Models\Finance_invoice_out_detail;
use App\Models\Finance_util_salary;
use App\Models\General_cashbond;
use App\Models\General_cashbond_detail;
use App\Models\Marketing_c_prognosis;
use App\Models\Marketing_project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class ChartCustomController extends Controller
{
    function index(){
        $project = Marketing_project::where('company_id', Session::get('company_id'))->get();
        $data_project = array();
        foreach ($project as $item){
            $data_project[$item->id] = $item;
        }
        $arr = ["po", "wo", "invoice_out", "payroll", "sales", "cost", "operating_expenses"];
        $chart = Chart_custom::where('company_id', Session::get('company_id'))->get();
        return view('chart.custom.index', [
            'arr' => $arr,
            'project' => $project,
            'charts' => $chart,
            'data_project' => $data_project
        ]);
    }

    function add(Request $request){
//        dd($request);
        $nChart = new Chart_custom();
        $nChart->name = $request->name;
        $nChart->description = $request->description;
        $nChart->date_from = $request->date_from;
        $nChart->date_to = $request->date_to;
        $nChart->project = $request->project;

        $type = $request->type;
        foreach ($type as $i => $item){
            if ($item != null){
                $row['type'] = $item;
                $row['stack'] = (isset($request->stack[$i])) ? "on" : "off";
                $nChart["line_".$i] = json_encode($row);
            }
        }
        $nChart->created_by = Auth::user()->username;
        $nChart->company_id = Session::get('company_id');
        $nChart->save();

        return redirect()->back();
    }

    function find($id){
        $project = Marketing_project::where('company_id', Session::get('company_id'))->get();
        $data_project = array();
        foreach ($project as $item){
            $data_project[$item->id] = $item;
        }
        $arr = ["po", "wo", "invoice_out", "payroll", "sales", "cost", "operating_expenses"];
        $chart = Chart_custom::find($id);
        return view('chart.custom.edit', [
            'arr' => $arr,
            'project' => $project,
            'chart' => $chart,
            'data_project' => $data_project
        ]);
    }

    function delete($id){
        Chart_custom::find($id)->delete();
        return redirect()->back();
    }

    function update(Request $request){
        $row = Chart_custom::find($request->id_chart);
        $row['name'] = $request['name'];
        $row['description'] = $request['description'];
        $row['date_from'] = $request['date_from'];
        $row['date_to'] = $request['date_to'];
        $row['project'] = $request['project'];
        $_type = $request['type'];
        $_stack = $request['stack'];
        foreach ($_type as $key => $value) {
            if (!empty($value)) {
                $type = explode(" ", $value);
                if (isset($_stack[$key])) {
                    $stack = "on";
                } else {
                    $stack = "off";
                }
                $lines = array(
                    'type' => strtolower($type[0]),
                    'stack' => $stack
                );
                $row["line_".$key] = json_encode($lines);
            } else {
                $row["line_".$key] = null;
            }
        }

        $row->save();
        return redirect()->back();
    }

    function view($id){
        $project = Marketing_project::where('company_id', Session::get('company_id'))->get();
        $data_project = array();
        foreach ($project as $item){
            $data_project[$item->id] = $item;
        }
        $arr = ["po", "wo", "invoice_out", "payroll", "sales", "cost", "operating_expenses"];
        $chart = Chart_custom::find($id);
        // dd($chart);
        return view('chart.custom.view', [
            'arr' => $arr,
            'project' => $project,
            'chart' => $chart,
            'data_project' => $data_project
        ]);
    }

    function get_data($id_chart){
        $sesId = Session::get('company_id');
        $rChart = Chart_custom::find($id_chart);

        $date1 = $rChart->date_from;
        $date2 = $rChart->date_to;

        $rPro = $project = Marketing_project::where('company_id', $sesId)->get();

        $val = array();
        $jsVal = null;


        $rInv = Finance_invoice_out::where('company_id', $sesId)->get();
        foreach ($rInv as $item){
            $inv_no[$item->id_inv] = $item->no;
            $inv_prj[$item->id_inv] = $item->prj_code;
        }

        $rTPo = Asset_type_po::all();
        foreach ($rTPo as $item){
            $type_po[$item->id] = $item->name;
        }

        $rTwo = Asset_type_wo::all();
        foreach ($rTwo as $item){
            $type_wo[$item->id] = $item->name;
        }

        $inv_dets = Finance_invoice_out_detail::whereBetween('date', [$date1, $date2])
            ->where('company_id', $sesId)
            ->get();
        foreach ($inv_dets as $inv_det){
            $dateInv[$inv_prj[$inv_det->id_inv]][$inv_det->id_inv][] = $inv_det->date;
            $ppn = ($inv_det->ppn == "on") ? ($inv_det->value_d * 0.1) : 0;
            $pph_23 = ($inv_det->pph_23 == "on") ? ($inv_det->value_d * 0.02) : 0;
            $ppn_dpp = ($inv_det->ppn_dpp == "on") ? ($inv_det->value_d * 0.01) : 0;
            $pph_4 = ($inv_det->pph_4 == "on") ? ($inv_det->value_d * 0.03) : 0;
            $pph_22 = ($inv_det->pph_22 == "on") ? ($inv_det->value_d * 0.003) : 0;
            $pph_29 = ($inv_det->pph_29 == "on") ? ($inv_det->value_d * 0.25) : 0;
            $valueInv[$inv_prj[$inv_det->id_inv]][$inv_det->id_inv][] = $inv_det->value_d + $ppn + $ppn_dpp + $pph_4 + $pph_22 + $pph_23 + $pph_29;
            $taxes[$inv_det->id_inv]['ppn'][] = ($inv_det->ppn == "on") ? ($inv_det->value_d * 0.1) : 0;
            $taxes[$inv_det->id_inv]['pph_23'][] = ($inv_det->pph_23 == "on") ? ($inv_det->value_d * 0.02) : 0;
            $taxes[$inv_det->id_inv]['ppn_dpp'][] = ($inv_det->ppn_dpp == "on") ? ($inv_det->value_d * 0.01) : 0;
            $taxes[$inv_det->id_inv]['pph_4'][] = ($inv_det->pph_4 == "on") ? ($inv_det->value_d * 0.03) : 0;
            $taxes[$inv_det->id_inv]['pph_22'][] = ($inv_det->pph_22 == "on") ? ($inv_det->value_d * 0.003) : 0;
            $taxes[$inv_det->id_inv]['pph_29'][] = ($inv_det->pph_29 == "on") ? ($inv_det->value_d * 0.25) : 0;
        }

        $rdPos = Asset_po_detail::all();
        foreach ($rdPos as $rdPo){
            $sum_po[$rdPo->po_num][] = $rdPo->qty * $rdPo->price;
        }

        $rPos = Asset_po::whereBetween('po_date', [$date1, $date2])
            ->where('company_id', $sesId)
            ->whereNotNull('approved_by')
            ->get();
        foreach ($rPos as $rPo){
            $c['project'] = $rPo->project;
            $c['date'] = $rPo->po_date;
            $c['label'] = $rPo->po_num;
            $ppn_po = ($rPo->ppn == "on") ? 0.1 : 0;
            $c['amount'] = (array_sum($sum_po[$rPo->id]) - $rPo->discount) + (array_sum($sum_po[$rPo->id]) * $ppn_po);
            $po_data[$rPo->project][$rPo->po_type][] = $c;
        }

        $rdwos = Asset_wo_detail::all();
        foreach ($rdwos as $rdwo){
            $sum_wo[$rdwo->wo_id][] = $rdwo->qty * $rdwo->unit_price;
        }

        $rwos = Asset_wo::whereBetween('req_date', [$date1, $date2])
            ->where('company_id', $sesId)
            ->whereNotNull('approved_by')
            ->get();
        foreach ($rwos as $rwo){
            $c['project'] = $rwo->project;
            $c['date'] = $rwo->req_date;
            $c['label'] = $rwo->wo_num;
            $ppn_wo = ($rwo->ppn != null) ? array_sum($sum_wo[$rwo->id]) * 0.1 : 0;
            $c['amount'] = (array_sum($sum_wo[$rwo->id]) - $rwo->discount) + $ppn_wo;
            $wo_data[$rwo->project][$rwo->wo_type][] = $c;
        }

        $rcbs = General_cashbond::whereNotNull('m_approve')->get();
        foreach ($rcbs as $rcb){
            $cb_project[$rcb->id] = $rcb->project;
        }

        $rdcbs = General_cashbond_detail::whereBetween('tanggal', [$date1, $date2])->get();
        foreach ($rdcbs as $rdcb){
            $c['date'] = $rdcb->tanggal;
            $c['label'] = $rdcb->deskripsi;
            $c['amount'] = $rdcb->cashout;
            $cb_data[$cb_project[$rdcb->id_cashbond]][$rdcb->category][] = $c;
        }

        $qProg = "SELECT * FROM c_prognosis";
        if($rChart->project == 0){
            $progs = Marketing_c_prognosis::where('company_id', $sesId)->get();
        } else {
            $queryProject = " AND project LIKE '".$rChart->project."' ";
            $queryProjectInv = " AND prj_code LIKE '".$rChart->project."' ";
            $progs = Marketing_c_prognosis::where('company_id', $sesId)
                ->where('id_project', $rChart->project)
                ->get();
        }

        foreach ($progs as $prog){
            if ($prog->category == "sales") {
                if ($prog->whitelists != null) {
                    $json_sales[$prog->id] = json_decode($prog->whitelists);
                    $prog_pro[$prog->id] = $prog->id_project;
                }
            } elseif ($prog->category == "cost") {
                if ($prog->whitelists != null) {
                    $json_cost[$prog->id] = json_decode($prog->whitelists);
                }
            } elseif ($prog->category == "operating_expense") {
                if ($prog->whitelists != null) {
                    $json_oe[$prog->id] = json_decode($prog->whitelists);
                }
            }
            $proj_prog[$prog->id] = $prog->id_project;
        }


        // $db_fin->debug = 1;
//        $rPayroll = $db_fin->Execute("SELECT * FROM util_salary where salary_date BETWEEN '".$date1."' AND '".$date2."';");
        $rPayroll = Finance_util_salary::whereBetween('salary_date', [$date1, $date2])->get();
        // print_r($rPayroll->totalPayroll);

        // echo "<pre>".print_r($valueInv, 1)."</pre>";

        if (isset($json_sales)){
            foreach ($json_sales as $key => $value) {
                if ($value->inv_out != null || $value->inv_out != "" || count($value->inv_out) > 0) {
                    foreach ($value->inv_out as $inv) {
                        for ($i=0; $i < count($valueInv[$prog_pro[$key]][$inv]); $i++) {
                            $a['amount'] = intval($valueInv[$prog_pro[$key]][$inv][$i]);
                            $a['date'] = date('Y-m', strtotime($dateInv[$prog_pro[$key]][$inv][$i]));
                            $a['type'] = "sales";
                            $a['label'] = $inv_no[$inv];
                            $val[] = (object) $a;
                        }
                    }
                }

                if ($value->aggre_num != null || $value->aggre_num != "" || count($value->aggre_num) > 0) {
                    foreach ($value->aggre_num as $aggre) {
                        $taxam = 0;
                        foreach ($value->taxes as $tax) {
                            for ($j=0; $j < count($taxes[$aggre]); $j++) {
                                $taxam += array_sum($taxes[$aggre][$tax]);
                            }
                        }
                    }
                }
            }
        }

        // echo "<pre>".print_r($val, 1)."</pre>";

        if (isset($json_cost)){
            foreach ($json_cost as $key => $value) {
                $cost_po[$key] = $value->po;
                $cost_wo[$key] = $value->wo;
                $cost_cashbond[$key] = $value->cashbond;
            }
        }

        $po_cost = array();
        $wo_cost = array();

        if (isset($cost_po)){
            foreach ($cost_po as $keyObject => $object) {
                foreach ($object as $key => $value) {
                    if ($key != "_empty_") {
                        foreach ($value as $item) {
                            if (!in_array($item, $po_cost[$keyObject][$key])) {
                                $po_cost[$keyObject][$key][] = $item;
                            }
                        }
                    }
                }
            }
        }

        if (isset($cost_wo)){
            foreach ($cost_wo as $keyObject => $object) {
                foreach ($object as $key => $value) {
                    if ($key != "_empty_") {
                        foreach ($value as $item) {
                            if (!in_array($item, $wo_cost[$keyObject][$key])) {
                                $wo_cost[$keyObject][$key][] = $item;
                            }
                        }
                    }
                }
            }
        }

        if (isset($cost_cashbond)){
            foreach ($cost_cashbond as $keyProg => $value) {
                foreach ($value as $pro => $type) {
                    for ($i=0; $i < count($type); $i++) {
                        foreach ($cb_data[$pro][$type_wo[$type[$i]]] as $cb_val) {
                            // echo "<pre>".print_r($cb_val, 1)."</pre>";
                            $b['amount'] = intval($cb_val['amount']);
                            $b['date'] = date('Y-m', strtotime($cb_val['date']));
                            $b['type'] = "cost";
                            $b['label'] = "cashbond";
                            $prog_data[$keyProg][] = intval($cb_val['amount']);
                            $val[] = (object) $b;
                        }
                    }
                }
            }
        }

        // echo "<pre>".print_r($cost_cashbond, 1)."</pre>";

        if (isset($json_oe)){
            foreach ($json_oe as $key => $value) {
                $oe_po[] = $value->po;
                $oe_wo[] = $value->wo;
                $oe_payroll[] = $value->payroll;
            }
        }

        if (isset($po_cost)){
            foreach ($po_cost as $keyProg => $object) {
                foreach ($object as $key => $value) {
                    for ($i=0; $i < count($value); $i++) {
                        foreach ($po_data[$key][$type_po[$value[$i]]] as $po_val) {
                            // echo "<pre>".print_r($po_val, 1)."</pre>";
                            $b['amount'] = intval($po_val['amount']);
                            $b['date'] = date('Y-m', strtotime($po_val['date']));
                            $b['type'] = "cost";
                            $b['label'] = $po_val['label'];
                            $prog_data[$keyProg][] = intval($po_val['amount']);
                            $val[] = (object) $b;
                        }
                    }
                }
            }
        }

        if (isset($wo_cost)){
            foreach ($wo_cost as $keyProg => $object) {
                foreach ($object as $key => $value) {
                    for ($i=0; $i < count($value); $i++) {
                        foreach ($wo_data[$key][$type_wo[$value[$i]]] as $wo_val) {
                            // echo "<pre>".print_r($wo_val, 1)."</pre>";
                            $c['amount'] = intval($wo_val['amount']);
                            $c['date'] = date("Y-m", strtotime($wo_val['date']));
                            $c['type'] = "cost";
                            $c['label'] = $wo_val['label'];
                            $prog_data[$keyProg][] = intval($wo_val['amount']);
                            $tes_wo[] = $c;
                            // echo "<pre>".print_r($c, 1)."</pre>";
                            $val[] = (object) $c;
                        }
                    }
                }
            }
        }

        $sum_tes = 0;


        if (isset($tes_wo)){
            foreach ($tes_wo as $key => $value) {
                $sum_tes += $value['amount'];
            }
        }

        if (isset($oe_po)){
            foreach ($oe_po as $object) {
                foreach ($object as $key => $value) {
                    if ($key != "_empty_") {
                        for ($i=0; $i < count($value); $i++) {
                            foreach ($po_data[$key][$type_po[$value[$i]]] as $po_val) {
                                $d['amount'] = intval($po_val['amount']);
                                $d['date'] = date('Y-m', strtotime($po_val['date']));
                                $d['type'] = "oeprating";
                                $d['label'] = $po_val['label'];
                                $val[] = (object) $d;
                            }
                        }
                    }
                }
            }
        }

        if (isset($oe_wo)){
            foreach ($oe_wo as $object) {
                foreach ($object as $key => $value) {
                    if ($key != "_empty_") {
                        for ($i=0; $i < count($value); $i++) {
                            foreach ($wo_data[$key][$type_wo[$value[$i]]] as $wo_val) {
                                $e['amount'] = intval($wo_val['amount']);
                                $e['date'] = date("Y-m", strtotime($wo_val['date']));
                                $e['type'] = "oeprating";
                                $e['label'] = $wo_val['label'];
                                $val[] = (object) $e;
                            }
                        }
                    }
                }
            }
        }

        if (isset($oe_payroll)){
            foreach ($oe_payroll as $key => $value) {
                if ($value > 0) {
                    while (!$rPayroll->EOF) {
                        $f['amount'] = intval($rPayroll->amount);
                        $f['date'] = date("Y-m", strtotime($rPayroll->salary_date));
                        $f['type'] = "operating";
                        $f['label'] = "payroll";
                        $val[] = (object) $f;
                        $rPayroll->MoveNext();
                    }

                }
            }
        }

        function cmp($a, $b) {
            return strcmp($a->date, $b->date);
        }

        if (count($val) > 0){
            usort($val, "cmp");
        }

        $dataRow = array();

        for ($i=0; $i < 5; $i++) {
            if (!empty($rChart['line_'.($i + 1)])) {
                $line = json_decode($rChart['line_'.($i + 1)]);
                if ($rChart->project == 0) {
                    $queryProject = "";
                    $queryProjectInv = "";
                    $qProg = "";
                } else {
                    $queryProject = " AND project = '".$rChart->project."' ";
                    $queryProjectInv = " AND prj_code = '".$rChart->project."' ";
                    $qProg = " WHERE id_project = '".$rChart->project."' ";
                }
                if ($line->type == "po") {
                    $sPO_line = "SELECT asset_po.id, asset_po.po_date, asset_po.po_num, asset_po.currency, asset_po.discount, asset_po.ppn, SUM(asset_po_detail.qty*asset_po_detail.price) AS amountPO FROM asset_po INNER JOIN asset_po_detail ON asset_po.id = asset_po_detail.po_num WHERE asset_po.po_date BETWEEN '".$date1."' AND '".$date2."' AND asset_po.company_id = '".$sesId."' ".$queryProject." GROUP BY asset_po.id;";
                    $rPO_lines = DB::select(DB::raw($sPO_line));
                    foreach ($rPO_lines as $rPO_line){
                        if ($rPO_line->ppn != null) {
                            $thistax = 1.1;
                        } else {
                            $thistax = 1.0;
                        }
                        $row['id'] = $rPO_line->id;
                        $row['type'] = "po";
                        $row['date'] = $rPO_line->po_date;
                        $row['amount'] = ($rPO_line->amountPO - $rPO_line->discount) * $thistax;
                        $row['stack'] = ($line->stack == "on") ? "on" : "off";
                        $dataRow[date("Y-m", strtotime($rPO_line->po_date))][] = $row;
                    }
                } elseif ($line->type == "wo") {
                    $sWO_line = "SELECT asset_wo.id, asset_wo.req_date, asset_wo.wo_num, asset_wo.currency, asset_wo.discount, asset_wo.ppn, SUM(asset_wo_detail.qty*asset_wo_detail.unit_price) AS amountWO FROM asset_wo INNER JOIN asset_wo_detail ON asset_wo.id = asset_wo_detail.wo_id WHERE asset_wo.req_date BETWEEN '".$date1."' AND '".$date2."' AND asset_wo.company_id = '".$sesId."' ".$queryProject." GROUP BY asset_wo.id;";
                    $rWO_lines = DB::select(DB::raw($sWO_line));
//                    $rWO_lines = DB::raw($sWO_line);
                    foreach ($rWO_lines as $rWO_line){
                        if ($rWO_line->ppn == "on") {
                            $thistax = 1.1;
                        } else {
                            $thistax = 1.0;
                        }
                        $row['id'] = $rWO_line->id;
                        $row['type'] = "wo";
                        $row['date'] = $rWO_line->req_date;
                        $row['amount'] = ($rWO_line->amountWO - $rWO_line->discount)*$thistax;
                        $row['stack'] = ($line->stack == "on") ? "on" : "off";
                        $dataRow[date("Y-m", strtotime($rWO_line->req_date))][] = $row;
                    }
                } elseif ($line->type == "invoice"){
                    $sInv_line = "SELECT finance_inv_out.id_inv, finance_inv_out_detail.date, finance_inv_out_detail.discount, finance_inv_out_detail.ppn, finance_inv_out_detail.no_inv, finance_inv_out_detail.value_d, finance_inv_out.currency FROM finance_inv_out INNER JOIN finance_inv_out_detail ON finance_inv_out.id_inv = finance_inv_out_detail.id_inv WHERE finance_inv_out_detail.date  BETWEEN '".$date1."' AND '".$date2."' AND finance_inv_out.company_id = '".$sesId."' AND finance_inv_out_detail.ceo_app_by IS NOT NULL ".$queryProjectInv." GROUP BY finance_inv_out.id_inv;";
                    $rInv_lines = DB::select(DB::raw($sInv_line));
                    foreach ($rInv_lines as $rInv_line) {
                        if ($rInv_line->ppn == "on") {
                            $thistax = 1.1;
                        } else {
                            $thistax = 1.0;
                        }
                        $row['id'] = $rInv_line->id_inv;
                        $row['type'] = "invoice_out";
                        $row['date'] = $rInv_line->date;
                        $row['amount'] = ($rInv_line->value_d - $rInv_line->discount)*$thistax;
                        $row['stack'] = ($line->stack == "on") ? "on" : "off";
                        $dataRow[date("Y-m", strtotime($rInv_line->date))][] = $row;
                    }
                } elseif ($line->type == "payroll") {
//                    $sPayroll_line = "SELECT * FROM util_salary WHERE salary_date BETWEEN '".$date1."' AND '".$date2."' ORDER BY salary_date ASC;";
                    $rPayroll_lines = Finance_util_salary::whereBetween('salary_date', [$date1, $date2])
                        ->where('company_id', $sesId)
                        ->orderBy('salary_date', 'asc')
                        ->get();
                    foreach ($rPayroll_lines as $rPayroll_line) {
                        $row['id'] = $rPayroll_line->id;
                        $row['type'] = "payroll";
                        $row['date'] = $rPayroll_line->salary_date;
                        $row['amount'] = $rPayroll_line->amount;
                        $row['stack'] = ($line->stack == "on") ? "on" : "off";
                        $dataRow[date("Y-m", strtotime($rPayroll_line->salary_date))][] = $row;
                    }
                } elseif ($line->type == "sales") {
                    foreach ($val as $key => $valVal) {
                        if ($valVal->type == "sales") {
                            $row['id'] = $valVal->label;
                            $row['type'] = "sales";
                            $row['date'] = $valVal->date;
                            $row['amount'] = $valVal->amount;
                            $row['stack'] = ($line->stack == "on") ? "on" : "off";
                            $dataRow[date("Y-m", strtotime($valVal->date))][] = $row;
                        }
                    }
                } elseif ($line->type == "cost") {
                    foreach ($val as $key => $valVal) {
                        if ($valVal->type == "cost") {
                            $row['id'] = $valVal->label;
                            $row['type'] = "cost";
                            $row['date'] = $valVal->date;
                            $row['amount'] = $valVal->amount;
                            $row['stack'] = ($line->stack == "on") ? "on" : "off";
                            $dataRow[date("Y-m", strtotime($valVal->date))][] = $row;
                        }
                    }
                } elseif ($line->type == "operating") {
                    foreach ($val as $key => $valVal) {
                        if ($valVal->type == "operating") {
                            $row['id'] = $valVal->label;
                            $row['type'] = "operating_expenses";
                            $row['date'] = $valVal->date;
                            $row['amount'] = $valVal->amount;
                            $row['stack'] = ($line->stack == "on") ? "on" : "off";
                            $dataRow[date("Y-m", strtotime($valVal->date))][] = $row;
                        }
                    }
                }
            }
        }

        if (isset($dataRow) && count($dataRow) > 0){
            ksort($dataRow);
        }

        $prevKey = "";
        $po_amount = 0;
        $wo_amount = 0;
        $inv_amount = 0;
        $payroll_amount = 0;
        $sales_amount = 0;
        $cost_amount = 0;
        $operating_amount = 0;
        $bLines = array();
        $rowJS = array();
        if (count($dataRow) > 0){
            foreach ($dataRow as $key => $value) {
                $jsData['date'] = $key;
                $jsData['po'] = $po_amount;
                $jsData['wo'] = $wo_amount;
                $jsData['invoice_out'] = $inv_amount;
                $jsData['payroll'] = $payroll_amount;
                $jsData['sales'] = $sales_amount;
                $jsData['cost'] = $cost_amount;
                $jsData['operating_expenses'] = $operating_amount;
                foreach ($value as $keyValue => $item) {
                    if ($item['type'] == "po") {
                        $jsData['po'] += $item['amount'];
                        if ($item['stack'] == "on") {
                            $po_amount += $item['amount'];
                        } else {
                            $po_amount = 0;
                        }
                    } elseif ($item['type'] == "wo") {
                        $jsData['wo'] += $item['amount'];
                        if ($item['stack'] == "on") {
                            $wo_amount += $item['amount'];
                        } else {
                            $wo_amount = 0;
                        }
                    } elseif ($item['type'] == "invoice_out") {
                        $jsData['invoice'] += $item['amount'];
                        if ($item['stack'] == "on") {
                            $inv_amount += $item['amount'];
                        } else {
                            $inv_amount = 0;
                        }
                    } elseif ($item['type'] == "payroll") {
                        $jsData['payroll'] += $item['amount'];
                        if ($item['stack'] == "on") {
                            $payroll_amount += $item['amount'];
                        } else {
                            $payroll_amount = 0;
                        }
                    } elseif ($item['type'] == "sales") {
                        $jsData['sales'] += $item['amount'];
                        if ($item['stack'] == "on") {
                            $sales_amount += $item['amount'];
                        } else {
                            $sales_amount = 0;
                        }
                    } elseif ($item['type'] == "cost") {
                        $jsData['cost'] += $item['amount'];
                        if ($item['stack'] == "on") {
                            $cost_amount += $item['amount'];
                        } else {
                            $cost_amount = 0;
                        }
                    } elseif ($item['type'] == "operating_expenses") {
                        $jsData['operating'] += $item['amount'];
                        if ($item['stack'] == "on") {
                            $operating_amount += $item['amount'];
                        } else {
                            $operating_amount = 0;
                        }
                    }
                }
                $rowJS[] = $jsData;
                $prevKey = $key;
            }

            ksort($rowJS);

            $jsRow = json_encode($rowJS);

            for ($i=0; $i < 5; $i++) {
                $iLine = "line_".($i+1);
                if (!empty($rChart[$iLine])) {
                    $aLines = json_decode($rChart[$iLine]);
                    $vLines['type'] = $aLines->type;
                    switch ($aLines->type) {
                        case 'po':
                            $vLines['label'] = "PO";
                            break;
                        case 'wo':
                            $vLines['label'] = "WO";
                            break;
                        case 'invoice_out':
                            $vLines['label'] = "INVOICE OUT";
                            break;
                        case 'payroll':
                            $vLines['label'] = "PAYROLL";
                            break;
                        case 'sales':
                            $vLines['label'] = "SALES";
                            break;
                        case 'cost':
                            $vLines['label'] = "COST";
                            break;
                        case 'operating_expenses':
                            $vLines['label'] = "OPERATING EXPENSES";
                            break;
                    }
                    $bLines[] = $vLines;
                }
            }
        }


        $val = array(
            'data' => $rowJS,
            'bLines' => $bLines
        );

        return $val;
    }
}
