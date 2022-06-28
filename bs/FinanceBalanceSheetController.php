<?php

namespace App\Http\Controllers;

use App\Models\Finance_coa;
use App\Models\Finance_coa_history;
use App\Models\Finance_balance_sheet_setting;
use Illuminate\Http\Request;
use Session;
use DB;


class FinanceBalanceSheetController extends Controller
{
    public function index(){
        $coa = Finance_coa::all();
        $setting = Finance_balance_sheet_setting::where('company_id', Session::get('company_id'))->first();
//        dd($setting);
        return view('finance.balance_sheet.index', [
            'coa' => $coa,
            'setting' => $setting
        ]);
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
//        dd($setting);


        $coa_assets = $this->find_coa_code(json_decode($setting->assets));
        $coa_liability = $this->find_coa_code(json_decode($setting->liability));
        $coa_eq = $this->find_coa_code(json_decode($setting->equity));

//        dd($coa_eq);

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

//        dd($data);

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

//        dd($asset[0][1]);

//        dd($asset);
        return view('finance.balance_sheet.index',[
            'asset' => $asset,
            'equity' => $eq,
            'liability' => $lia,
            'coa' => $coa,
            'setting' => $setting,
        ]);

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
            $code = str_replace("0", "", $cc[$item]);
            $coa = Finance_coa::where('parent_id', 'like', $code."%")->get();
            $coa_oi[] = $cc[$item];
            foreach ($coa as $value){
                if (!in_array($value->code, $coa_oi)){
                    $coa_oi[] = $value->code;
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
}
