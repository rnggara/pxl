<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank_ru;
use Session;
use DB;
use Illuminate\Support\Facades\Auth;

class DirutBankCEOController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/jakarta');
    }

    public function index(){
        //mandiri
        $totalMandiriIDR = 0;
        $totalMandiriUSD = 0;
        $sql_bank_mandiri = "SELECT  SUM(IF(nama_bank LIKE '%mandiri%' AND currency = 'IDR', jumlah, 0)) AS jml_idr,SUM(IF(nama_bank LIKE '%mandiri%' AND currency = 'USD', jumlah, 0)) AS jml_usd FROM bank_ru WHERE company_id = '".Session::get('company_id')."' AND deleted_at IS NULL";
        $bank_mandiri = DB::select($sql_bank_mandiri);
        foreach ($bank_mandiri as $key => $value){
            $totalMandiriIDR += $value->jml_idr;
            $totalMandiriUSD += $value->jml_usd;
        }

        //bca
        $totalBCAIDR = 0;
        $totalBCAUSD = 0;
        $sql_bank_bca = "SELECT  SUM(IF(nama_bank LIKE '%bca%' AND currency = 'IDR', jumlah, 0)) AS jml_idr,SUM(IF(nama_bank LIKE '%bca%' AND currency = 'USD', jumlah, 0)) AS jml_usd FROM bank_ru WHERE company_id = '".Session::get('company_id')."' AND deleted_at IS NULL";
        $bank_bca = DB::select($sql_bank_bca);
        foreach ($bank_bca as $key => $value){
            $totalBCAIDR += $value->jml_idr;
            $totalBCAUSD += $value->jml_usd;
        }

        //hsbc
        $totalHSBCIDR = 0;
        $totalHSBCUSD = 0;
        $sql_bank_hsbc = "SELECT  SUM(IF(nama_bank LIKE '%hsbc%' AND currency = 'IDR', jumlah, 0)) AS jml_idr,SUM(IF(nama_bank LIKE '%hsbc%' AND currency = 'USD', jumlah, 0)) AS jml_usd FROM bank_ru WHERE company_id = '".Session::get('company_id')."' AND deleted_at IS NULL";
        $bank_hsbc = DB::select($sql_bank_hsbc);
        foreach ($bank_hsbc as $key => $value){
            $totalHSBCIDR += $value->jml_idr;
            $totalHSBCUSD += $value->jml_usd;
        }

        //bri
        $totalBRIIDR = 0;
        $totalBRIUSD = 0;
        $sql_bank_bri = "SELECT  SUM(IF(nama_bank LIKE '%bri%' AND currency = 'IDR', jumlah, 0)) AS jml_idr,SUM(IF(nama_bank LIKE '%bri%' AND currency = 'USD', jumlah, 0)) AS jml_usd FROM bank_ru WHERE company_id = '".Session::get('company_id')."' AND deleted_at IS NULL";
        $bank_bri = DB::select($sql_bank_bri);
        foreach ($bank_bri as $key => $value){
            $totalBRIIDR += $value->jml_idr;
            $totalBRIUSD += $value->jml_usd;
        }

        //citibank
        $totalCitiIDR = 0;
        $totalCitiUSD = 0;
        $sql_bank_citi = "SELECT  SUM(IF(nama_bank LIKE '%citi%' AND currency = 'IDR', jumlah, 0)) AS jml_idr,SUM(IF(nama_bank LIKE '%citi%' AND currency = 'USD', jumlah, 0)) AS jml_usd FROM bank_ru WHERE company_id = '".Session::get('company_id')."' AND deleted_at IS NULL";
        $bank_citi = DB::select($sql_bank_citi);
        foreach ($bank_citi as $key => $value){
            $totalCitiIDR += $value->jml_idr;
            $totalCitiUSD += $value->jml_usd;
        }

        return view('ha.bankceo.index', compact('totalMandiriUSD', 'totalMandiriIDR', 'totalBCAIDR','totalBCAUSD',
        'totalBRIIDR','totalBRIUSD','totalCitiIDR','totalCitiUSD','totalHSBCIDR','totalHSBCUSD'));
    }

    public function getDetail($bank=null){
        $bank_name = base64_decode($bank);
        $transaction_list = Bank_ru::where('nama_bank','like','%'.$bank_name.'%')
            ->get();
        return view('ha.bankceo.detail',compact('bank_name','transaction_list'));

    }
    public function filterBankDetail(Request $request,$bank=null){
        $bank_name =$request['bank'];
        $curr = ($request['curr']!=null)?$request['curr']:null;

        if ($request['bank'] != null && $request['bank'] != ""){
            if ($request['curr']!= null){
                $transaction_list = Bank_ru::where('nama_bank','like','%'.$request['bank'].'%')
                    ->where('currency',$request['curr'])
                    ->get();
            } else {
                $transaction_list = Bank_ru::where('nama_bank','like','%'.$request['bank'].'%')
                    ->get();
            }
        } else {
            $transaction_list = Bank_ru::all();
        }
        return view('ha.bankceo.detail',compact('bank_name','transaction_list','curr'));
    }
    public function addTrans(Request $request){
//        dd($request);
        if (isset($request['edit'])){
            $bankceo = Bank_ru::find($request['edit']);

            $bankceo->updated_by = Auth::user()->username;
            $bankceo->updated_at = date('Y-m-d H:i:s');
        } else {
            $bankceo = new Bank_ru();
            $bankceo->created_by = Auth::user()->username;
            $bankceo->created_at = date('Y-m-d H:i:s');

        }
        $bankceo->nama_bank = $request['bank_name'];
        $bankceo->keterangan = $request['desc'];
        $bankceo->tgl_trans = $request['trans_date'];
        $bankceo->tgl_exp = $request['exp_date'];
        $bankceo->currency = $request['currency'];
        $bankceo->jumlah = $request['amount'];
        $bankceo->pic = $request['pic'];
        $bankceo->telp_pic = $request['pic_number'];
        $bankceo->company_id = Session::get('company_id');

        $bankceo->save();

        return redirect()->back();
    }

    public function delete($id){
        Bank_ru::find($id)->delete();
        return redirect()->back();
    }


}
