<?php

namespace App\Http\Controllers;

use App\Models\Report_exchange_rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class ReportExchangeRate extends Controller
{
    function index(){
        $rates = Report_exchange_rate::where("company_id", Session::get("company_id"))
            ->orderBy('date_rate', 'desc')
            ->get();

        return view('report.exchange_rate.index', compact('rates'));
    }

    function add(Request $request){
        $rate = new Report_exchange_rate();
        $rate->date_rate = $request->_date;
        $rate->rates = json_encode($request->rate);
        $rate->created_by = Auth::user()->username;
        $rate->company_id = Session::get('company_id');
        $rate->save();

        return redirect()->back();
    }

    function update(Request $request){
        $rate = Report_exchange_rate::find($request->id_rate);
        $rate->rates = json_encode($request->rate);
        $rate->updated_by = Auth::user()->username;
        $rate->save();

        return redirect()->back();
    }

    function get($id){
        $rate = Report_exchange_rate::find($id);

        $jsRate = [];
        $usd = 0;
        if(!empty($rate->rates)){
            $jsRate = json_decode($rate->rates, true);
        }

        return view('report.exchange_rate._others', compact('rate', 'jsRate'));
    }

    function delete($id){
        $rate = Report_exchange_rate::find($id);
        $rate->deleted_by = Auth::user()->username;
        $rate->save();
        $rate->delete();

        return redirect()->back();
    }

    function copy($id){
        $rate = Report_exchange_rate::find($id);

        $newRate = $rate->replicate();
        $newRate->created_at = date("Y-m-d H:i:s");
        $newRate->date_rate = date("Y-m-d");
        $newRate->save();

        return redirect()->back();
    }

    function insert_view(){

        $last_rate = Report_exchange_rate::orderBy('id', 'desc')->first();

        $rates = [];

        if(!empty($last_rate)){
            $rates = json_decode($last_rate->rates, true);
        }

        return view('report.exchange_rate.add', compact('rates'));
    }

    function insert(Request $request){
        $exchange = new Report_exchange_rate();

        $curr = json_encode($request->curr);

        $exchange->date_rate = date("Y-m-d H:i:s");
        $exchange->rates = $curr;
        $exchange->created_by = Auth::user()->username;
        $exchange->company_id = Session::get('company_id');
        $exchange->save();
        return redirect()->route('report.er.index');
    }
}
