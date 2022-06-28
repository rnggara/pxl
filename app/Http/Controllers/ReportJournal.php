<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Finance_coa;
use Illuminate\Http\Request;
use App\Models\Marketing_clients;
use App\Models\Marketing_project;
use App\Models\Finance_coa_history;
use App\Models\Finance_invoice_out;
use App\Models\Finance_treasury_history;
use App\Models\Finance_invoice_out_detail;

class ReportJournal extends Controller
{
    function index(Request $request){

        if($request->ajax()){

            $from_dt = $request->from_dt;
            $to_dt = $request->to_dt;

            $row = [];

            $his = Finance_treasury_history::where("company_id", Session::get("company_id"))
                ->where('description', 'like', "%invoice out payment%")
                ->whereBetween('date_input', [$from_dt, $to_dt])
                ->get();

            foreach ($his as $key => $value) {
                $exp = explode(":", $value->description);
                // $no_inv = explode("/", end($exp));
                $no_inv = preg_split("/[[|.]+/", end($exp));

                $num = explode("/", trim(rtrim($no_inv[0])));
                $_num = "";
                for ($i=0; $i < count($num) - 1; $i++) {
                    $_num .= $num[$i]."/";
                }

                $_num .= substr(end($num), 0, 4);

                $row[$value->id] = $_num;
            }

            $coahis = Finance_coa_history::whereIn('id_treasure_history', array_keys($row))
                ->orderBy('debit', 'desc')
                ->orderBy('coa_date')
                ->get();

            $inv = Finance_invoice_out::where('company_id', Session::get("company_id"))
                ->get();

            $inv_id = $inv->pluck('id_inv');
            $inv_prj = $inv->pluck('id_project', 'id_inv');

            $inv_detail = Finance_invoice_out_detail::whereIn('id_inv', $inv_id)
                ->get();

            $detail_prj = [];
            $detail_desc = [];
            foreach ($inv_detail as $key => $value) {
                if(isset($inv_prj[$value->id_inv])){
                    $detail_prj[$value->no_inv] = $inv_prj[$value->id_inv];
                    $detail_desc[$inv_prj[$value->id_inv]][$value->no_inv] = $value->activity;
                }
            }

            $_row = [];

            $coa_name = Finance_coa::all()->pluck('name', 'code');

            foreach ($coahis as $key => $value) {

                $inv_num = $row[$value->id_treasure_history];

                $project = $value->project;
                if(empty($project)){
                    if(isset($detail_prj[$inv_num])){
                        $project = $detail_prj[$inv_num];
                    }
                }

                $prj =  Marketing_project::find($project);

                $desc = "";
                if(isset($detail_desc[$project])){
                    if(isset($detail_desc[$project][$inv_num])){
                        $desc .= $detail_desc[$project][$inv_num];
                    }
                }

                if(!empty($prj)){
                    $col = [];
                    $col['id'] = $value->id;
                    $col['num'] = $inv_num;
                    $col['debit'] = $value->debit;
                    $col['credit'] = $value->credit;
                    $col['code'] = $value->no_coa;
                    $col['date'] = $value->coa_date;
                    $col['code_name'] = $coa_name[$value->no_coa];
                    $col['project'] = $project;
                    $_row[$project]['name'] = "$prj->prj_name";
                    $_row[$project]['client'] = Marketing_clients::find($prj->id_client)->company_name;
                    $_row[$project]['data'][$inv_num]['date'] = $value->coa_date;
                    $_row[$project]['data'][$inv_num]['activity'] = $desc;
                    $_row[$project]['data'][$inv_num]['detail'][] = $col;
                }
            }

            return view("report.journal._search", compact('_row', 'from_dt', 'to_dt'));
        }

        return view("report.journal.index");
    }

    function index_general(Request $request){
        if($request->ajax()){

            $from_dt = $request->from_dt;
            $to_dt = $request->to_dt;

            $row = [];

            $coahis = Finance_coa_history::whereBetween('coa_date', [$from_dt, $to_dt])
                ->where('id_treasure_history', '!=', 0)
                ->where('company_id', Session::get('company_id'))
                ->orderBy('coa_date')
                ->orderBy('debit')
                ->get();

            $_row = [];

            $coa_name = Finance_coa::all()->pluck('name', 'code');

            foreach ($coahis as $key => $value) {

                $col = [];
                if(isset($coa_name[$value->no_coa])){
                    $id_his = $value->id_treasure_history;
                    $col['description'] = $value->description;
                    $col['debit'] = $value->debit;
                    $col['credit'] = $value->credit;
                    $col['code'] = $value->no_coa;
                    $col['code_name'] = $coa_name[$value->no_coa];
                    $col['job'] = $value->project;
                    $col['date'] = $value->coa_date;
                    $_row[$id_his]['description'] = $value->description;
                    $_row[$id_his]['date'] = $value->coa_date;
                    $_row[$id_his]['list'][] = $col;
                }
            }

            return view("report.journal._search_all", compact('_row', 'from_dt', 'to_dt'));
        }

        return view("report.journal.all");
    }
}
