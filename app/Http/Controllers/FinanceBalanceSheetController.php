<?php

namespace App\Http\Controllers;

use DB;
use Session;
use Mpdf\Mpdf;
use App\Models\Finance_coa;
use Illuminate\Http\Request;
use App\Models\Finance_bl_files;
use App\Models\Finance_bl_detail;
use App\Models\Marketing_project;
use App\Models\Finance_coa_history;
use App\Models\Report_exchange_rate;
use Illuminate\Support\Facades\Auth;
use App\Models\Finance_treasury_history;
use App\Models\Finance_balance_sheet_setting;
use App\Models\Finance_pl_save;

class FinanceBalanceSheetController extends Controller
{
    public function index(Request $request){
        $coa = Finance_coa::orderBy('code', 'asc')->get();
        $setting = Finance_balance_sheet_setting::where('company_id', Session::get('company_id'))->first();

        $detail = Finance_bl_detail::where('company_id', Session::get('company_id'))->get();
        $d = [];
        $c = [];
        $coa_code = $coa->pluck('code', 'id');
        foreach($detail as $item){
            if(empty($item->parent_id)){
                $d[$item->type][] = $item;
            } else {
                $c[$item->parent_id][] = $item;
            }
        }

        $rate = Report_exchange_rate::orderBy('id')->first();
        $rateUsd = 0;
        if(!empty($rate)){
            $jsRate = (!empty($rate->rates)) ? json_decode($rate->rates, true) : [];
            if(!empty($jsRate) && isset($jsRate['USD'])){
                $rateUsd = str_replace(",", "", $jsRate['USD']);
            }
        }

        $project = Marketing_project::where('company_id', Session::get('company_id'))
            ->get()->pluck('prj_name', 'id');

        $from = null;
        $to = null;
        $prj_selected = [];
        $pl_val = 0;
        if(isset($request->from_date)){
            $from = $request->from_date;
            $to = $request->to_date;
            $prj_selected = $request->project;

            $pl = Finance_pl_save::where('from', $from)
                ->where('to', $to)
                ->where('company_id', Session::get('company_id'))
                ->first();
            if(!empty($pl)){
                $pl_val = $pl->amount;
            }

            if(isset($request->pdf)){
                $whereProject = " 1";
                if(!empty($request->project)){
                    $whereProject = "(";
                    foreach($request->project as $prj){
                        $whereProject .= " description like '%[$prj]%' or";
                    }
                    $whereProject = substr($whereProject, 0, -2);
                    $whereProject .= ")";
                }

                $c_his = Finance_coa_history::where('company_id', Session::get('company_id'))
                    ->whereRaw($whereProject)
                    ->get();
                $data_his = [];
                foreach($c_his as $item){
                    $c_code = rtrim($item->no_coa, 0);
                    $amount = ($item->debit > 0) ? $item->debit : ($item->credit * -1);
                    if($item->currency == "USD"){
                        if($rateUsd != 0){
                            $amount = $amount / $rateUsd;
                        }
                    }
                    $data_his[$c_code][] = $amount;
                }
                $pdf =  view('finance.balance_sheet.pdf', [
                    'coa' => $coa,
                    'setting' => $setting,
                    'detail' => $d,
                    'detail_child' => $c,
                    'coa_code' => $coa_code,
                    'from' => $from,
                    'to' => $to,
                    'data_his' => $data_his
                ]);

                $mpdf = new Mpdf();
                $mpdf->WriteHTML($pdf);
                $file_name = 'media/reports/balance_sheet_'.date("Ymd", strtotime($from)).'_'.date("Ymd", strtotime($to)).'_'.date("Y_m_d_h_i").".pdf";
                $mpdf->Output($file_name, \Mpdf\Output\Destination::FILE);
                $bl_file = new Finance_bl_files();
                $bl_file->date_from = $from;
                $bl_file->date_to = $to;
                $bl_file->file = $file_name;
                $bl_file->type = "b";
                $bl_file->created_by = Auth::user()->username;
                $bl_file->company_id = Session::get('company_id');
                $bl_file->save();
            }

        }

        return view('finance.balance_sheet.index', [
            'coa' => $coa,
            'setting' => $setting,
            'detail' => $d,
            'detail_child' => $c,
            'coa_code' => $coa_code,
            'from' => $from,
            'to' => $to,
            'projects' => $project,
            'prj_selected' => $prj_selected,
            'pl_val' => $pl_val
        ]);
    }

    function delete_list($id){
        Finance_bl_files::find($id)->delete();

        return redirect()->back();
    }

    function setting(Request $request){

        $assets = json_encode($request->assets);
        $liablity = json_encode($request->liablity);
        $equity = json_encode($request->equity);

        $setting = Finance_balance_sheet_setting::where('company_id', Session::get('company_id'))->first();
        if ($setting == null){
            if (isset($request['asset'])){
                $newSetting = new Finance_balance_sheet_setting();
                $newSetting->assets = $assets;
                $newSetting->company_id = Session::get('company_id');
                $newSetting->save();
            } elseif (isset($request['lia'])){
                $newSetting = new Finance_balance_sheet_setting();
                $newSetting->liability = $liablity;
                $newSetting->company_id = Session::get('company_id');
                $newSetting->save();
            } elseif(isset($request['eq'])) {
                $newSetting = new Finance_balance_sheet_setting();
                $newSetting->equity = $equity;
                $newSetting->company_id = Session::get('company_id');
                $newSetting->save();
            }

        } else {
            if (isset($request['asset'])){
                $setting->assets = $assets;
                $setting->company_id = Session::get('company_id');
                $setting->save();

            } elseif (isset($request['lia'])){
                $setting->liability = $liablity;
                $setting->company_id = Session::get('company_id');
                $setting->save();
            } elseif(isset($request['eq'])) {
                $setting->equity = $equity;
                $setting->company_id = Session::get('company_id');
                $setting->save();
            }

        }
        return redirect()->route('bs.index');
    }

    public function find(Request $request){
        $coa = Finance_coa::all();
        $data['data'] = [];
        $setting = Finance_balance_sheet_setting::where('company_id', Session::get('company_id'))->first();
        if ($setting == null){
            $newSetting = new Finance_balance_sheet_setting();
            $newSetting->assets = '["1"]';
            $newSetting->liability = '["20"]';
            $newSetting->equity = '["25"]';
            $newSetting->company_id = Session::get('company_id');
            $newSetting->save();
            $setting_id = $newSetting->id;

            $setting = Finance_balance_sheet_setting::where('id', $setting_id)->first();

        }


        $coa_assets = $this->find_coa_code(json_decode($setting->assets));
        $coa_liability = $this->find_coa_code(json_decode($setting->liability));
        $coa_eq = $this->find_coa_code(json_decode($setting->equity));

        $assets = $this->find_coa_his($coa_assets, "Assets",$request['from_date'],$request['to_date']);
        $liability = $this->find_coa_his($coa_liability, "Liability",$request['from_date'],$request['to_date']);
        $equity = $this->find_coa_his($coa_eq, "Equity",$request['from_date'],$request['to_date']);

        array_push($data['data'], $assets);
        array_push($data['data'], $liability);
        array_push($data['data'], $equity);

        $num = 0;
        $num1 = 0;
        $num2 = 0;
        $sumassets = 0;
        $sumliability = 0;
        $sumequity = 0;
        $type = "";
        $asset = [];
        $lia = [];
        $eq = [];

        $type = "Assets";
        foreach ($assets as $value){
            $asset[$num][] = $value['code'];
            $asset[$num][] = array_sum($value['amount']);
            $asset[$num][] = $value['type'];
            $asset[$num][] = "";
            $sumassets = $sumassets + array_sum($value['amount']);
            $num++;
        }

        $type = "Liability";
        foreach ($liability as $value){
            $lia[$num1][] = $value['code'];
            $lia[$num1][] = array_sum($value['amount']);
            $lia[$num1][] = $value['type'];
            $lia[$num1][] = "";
            $sumliability = $sumliability + array_sum($value['amount']);
            $num1++;
        }

        $type = "Equity";
        foreach ($equity as $value){
            $eq[$num2][] = $value['code'];
            $eq[$num2][] = array_sum($value['amount']);
            $eq[$num2][] = $value['type'];
            $eq[$num2][] = "";
            $sumequity = $sumequity + array_sum($value['amount']);
            $num2++;
        }

        $type = "Total";

        $totalkiri = $sumassets;

        $row[$num][] = "".$type;
        $row[$num][] = "";
        $row[$num][] = $type;
        $row[$num][] = "<b>".number_format($totalkiri)."</b>";
        $num++;

        $type = "Total";

        $totalkanan = $sumequity+$sumliability;

        $row[$num][] = "".$type;
        $row[$num][] = "";
        $row[$num][] = $type;
        $row[$num][] = "<b>".number_format($totalkanan)."</b>";
        $num++;

        $val = array(
            'asset' => $asset,
            'equity' => $eq,
            'liability' => $lia,
        );

        $mpdf = new Mpdf();
        $pdf = view('finance.balance_sheet.pdf', [
            'asset' => $asset,
            'equity' => $eq,
            'liability' => $lia,
            'coa' => $coa,
            'setting' => $setting,
            'from' => $request['from_date'],
            'to' => $request['to_date']
        ]);
        $mpdf->WriteHTML($pdf);
        $file_name = 'media/reports/balance_sheet_'.date("Y_m_d_h_i").".pdf";
        $mpdf->Output($file_name, \Mpdf\Output\Destination::FILE);
        $bl_file = new Finance_bl_files();
        $bl_file->date_from = $request['from_date'];
        $bl_file->date_to = $request['to_date'];
        $bl_file->file = $file_name;
        $bl_file->type = "b";
        $bl_file->created_by = Auth::user()->username;
        $bl_file->company_id = Session::get('company_id');
        $bl_file->save();


        return view('finance.balance_sheet.index',[
            'asset' => $asset,
            'equity' => $eq,
            'liability' => $lia,
            'coa' => $coa,
            'setting' => $setting,
            'from' => $request['from_date'],
            'to' => $request['to_date']
        ]);

    }

    function list(){
        $list = Finance_bl_files::where('company_id', Session::get('company_id'))
            ->where('type', 'b')
            ->get();

        return view('finance.balance_sheet.list', compact('list'));
    }

    function add_detail(Request $request){
        if(isset($request->id_edit)){
            $detail = Finance_bl_detail::find($request->id_edit);
            $detail->updated_by = Auth::user()->username;
        } else {
            $detail = new Finance_bl_detail();
            $detail->company_id = Session::get('company_id');
            $detail->created_by = Auth::user()->username;
            if(!empty($request->parent_id) || $request->parent_id != null){
                $detail->parent_id = $request->parent_id;
            }
            $detail->type = $request->type;
            $detail->position = $request->position;
        }
        $detail->description = $request->nama;

        if(isset($request->tc)){
            if(!empty($request->tc)){
                $tc = json_encode($request->tc);
                $detail->tc = $tc;
            }
        } else {
            $detail->tc = null;
        }

        $detail->save();

        return redirect()->back();
    }

    function child_edit($id){
        $detail = Finance_bl_detail::find($id);
        $coa = Finance_coa::all();

        return view('finance.balance_sheet._edit_child', compact('detail', 'coa'));
    }

    function child_delete($id){
        $detail = Finance_bl_detail::find($id);
        $detail->deleted_by = Auth::user()->username;
        $detail->save();
        $detail->delete();

        return redirect()->back();
    }

    function search_value(Request $request){
        $whereProject = " 1";
        if(!empty($request->projects)){
            $whereProject = "(";
            foreach($request->projects as $prj){
                $whereProject .= " description like '%[$prj]%' or";
            }
            $whereProject = substr($whereProject, 0, -2);
            $whereProject .= ")";
        }

        $rate = Report_exchange_rate::orderBy('id')->first();
        $rateUsd = 0;
        if(!empty($rate)){
            $jsRate = (!empty($rate->rates)) ? json_decode($rate->rates, true) : [];
            if(!empty($jsRate) && isset($jsRate['USD'])){
                $rateUsd = str_replace(",", "", $jsRate['USD']);
            }
        }

        $code = rtrim($request->code, 0);
        $coa = Finance_coa::all()->pluck('id', 'code');
        $his = Finance_coa_history::where('no_coa', 'like', "$code%")
            ->whereBetween('coa_date', [$request->from, $request->to])
            ->whereRaw($whereProject)
            ->where('company_id', Session::get('company_id'))
            ->get();

        $sum = 0;
        // dd($request);
        foreach($his as $value){
            if (isset($coa[$value->no_coa])) {
                if($request->isAktifa == 1){
                    $amount = (empty($value->debit)) ? abs($value->credit) * -1 : abs($value->debit);
                } else {
                    $amount = (empty($value->debit)) ? abs($value->credit) : abs($value->debit) * -1;
                }
                if($value->currency == "USD"){
                    if($rateUsd != 0){
                        $amount = $amount / $rateUsd;
                    }
                }

                $sum += $amount;
            }
        }

        $result = array(
            "code" => $request->code,
            "total" => $sum
        );
        return json_encode($result);
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
        foreach ($x as $item){
            if(isset($cc[$item])){
                $code = str_replace("0", "", $cc[$item]);
                $coa = Finance_coa::where('parent_id', 'like', $code."%")->get();
                $coa_oi[] = $cc[$item];
                foreach ($coa as $value){
                    if (!in_array($value->code, $coa_oi)){
                        $coa_oi[] = $value->code;
                    }
                }
            }
        }

        return array_unique($coa_oi);
    }

    function find_coa_his($x, $y,$dateFrom,$dateTo){
        $c = Finance_coa::all();
        $coa_name = [];
        foreach ($c as $item){
            $coa_name[$item->code] = $item->name;
        }
        $his = Finance_coa_history::whereBetween('coa_date',[$dateFrom,$dateTo])
            ->whereIn('no_coa', $x)->get();
        $coa = [];
        foreach ($his as $item){
            $sum = 0;
            $coa[$item->no_coa]['code'] = "[".$item->no_coa."] ".$coa_name[$item->no_coa];
            if ($item->debit != null || $item->debit != 0){
                $sum = $item->debit;
            } else {
                $sum = $item->credit * -1;
            }
            $coa[$item->no_coa]['type'] = $y;
            $coa[$item->no_coa]['amount'][] = $sum;
        }

        return $coa;
    }

    function export($id, Request $request){
        $bs = Finance_bl_detail::find($id);
        $entry = [];
        $coa_description = Finance_coa::all()->pluck("name", 'code');
        if(!empty($bs->tc)){
            $tc = json_decode($bs->tc, true);
            $coa = Finance_coa::whereIn('id', $tc)->get();
            $coa_code = $coa->pluck('code');

            $coa_history = Finance_coa_history::whereIn('no_coa', $coa_code)
                ->where('company_id', Session::get('company_id'))
                ->whereBetween('coa_date', [$request->s, $request->to])
                ->orderBy('coa_date', 'desc')
                ->get();


            $coa_tre = $coa_history->pluck('id_treasure_history');
            $t_his = Finance_treasury_history::whereIn('id', $coa_tre)->get()->pluck('description', 'id');
            foreach($coa_history as $item){
                $amount = (empty($item->debit)) ? $item->credit : $item->debit;
                $row = [];
                $row['description'] = (isset($t_his[$item->id_treasure_history])) ? $t_his[$item->id_treasure_history] : $item->description;
                $row['date'] = $item->coa_date;
                $row['amount'] = $amount;
                $entry[$item->no_coa][] = $row;
            }
        }

        $from = $request->s;
        $to = $request->to;

        return view('finance.balance_sheet.export', compact('entry', 'bs', 'coa_description', 'from', 'to'));
    }

    function view($id){
        $detail = Finance_bl_detail::find($id);

        $type = $detail->position;

        $tc = (!empty($detail->tc)) ? json_decode($detail->tc, true) : [];

        $coa = Finance_coa::whereIn('id', $tc)->get();

        $tre_hist = Finance_treasury_history::where('company_id', Session::get('company_id'))->get();

        $data_tre = array();
        foreach ($tre_hist as $item){
            $data_tre[$item->id] = $item;
        }

        $data_history = [];

        $list_coa = [];
        if(!empty($tc)){
            $his = Finance_coa_history::where('company_id', Session::get('company_id'))
                ->where(function($q) use ($coa){
                    foreach ($coa as $key => $value) {
                        $coa_code = rtrim($value->code, 0);
                        $q->where('no_coa', 'like', "$coa_code%");
                    }
                })->get();

            foreach($his as $item){
                $data['id'] = $item->id;
                $data['date'] = $item->coa_date;
                if ($item->description == null){
                    $data['desc'] = (isset($data_tre[$item->id_treasure_history])) ? $data_tre[$item->id_treasure_history]->description : "N/A";
                    $data['ref_id'] = $item->id_treasure_history;
                    $data['source'] = "B";
                } else {
                    $data['desc'] = $item->description;
                    $data['ref_id'] = $item->md5;
                    $data['source'] = "J";
                }

                $data['debit'] = $item->debit;
                $data['credit'] = $item->credit;
                $data_history[$item->no_coa][] = $data;
            }

            $list_code = $his->pluck('no_coa');

            $list_coa = Finance_coa::where(function($q) use ($coa){
                foreach ($coa as $key => $value) {
                    $coa_code = rtrim($value->code, 0);
                    $q->where('code', 'like', "$coa_code%");
                }
            })->orderBy('code')->get();

            // dd($list_code, $list_coa);
        }


        return view('finance.balance_sheet.view', compact('list_coa', 'his', 'data_history', 'type'));
    }
}
