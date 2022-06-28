<?php

namespace App\Http\Controllers;

use App\Models\Asset_item;
use App\Models\Finance_coa;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\Finance_coa_history;
use App\Models\Finance_coa_source;
use App\Models\Finance_depreciation;
use App\Models\Finance_depreciation_detail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FinanceDepreciationController extends Controller
{
    function index(Request $request){
        $items = Asset_item::all()->pluck('name', 'id');
        $dp = Finance_depreciation::where('company_id', Session::get('company_id'))->get();
        $id = null;
        $item_sel = null;
        if(isset($request->i)){
            $id = $request->i;
            $item_sel = Asset_item::find($id);
        }

        return view('finance.dp.index', compact('dp', 'items', 'id', 'item_sel'));
    }

    function items_list(Request $request){
        $comp = ConfigCompany::find(Session::get('company_id'));
        $id_comp = [];
        if (!empty($comp->id_parent)) {
            $id_comp = ConfigCompany::where('id_parent', $comp->id_parent)->get()->pluck('id');
        }
        $id_comp[] = $comp->id;

        $items = Asset_item::whereIn('company_id', $id_comp)
            ->whereRaw("(name like '%$request->q%' or item_code like '%$request->q%')")
            ->get();

        $data = [];

        foreach ($items as $key => $value) {
            $row = [];
            $row['id'] = $value->id;
            $row['text'] = "[$value->item_code] $value->name";
            $data[] = $row;
        }

        $result = array(
            "results" => $data
        );

        return json_encode($result);
    }

    function tc_list(Request $request){
        $coa = Finance_coa::whereRaw("(code like '%$request->q%' or name like '%$request->q%')")->get();
        $data = [];
        foreach ($coa as $key => $value) {
            $row = [];
            $row['id'] = $value->id;
            $row['text'] = "[$value->code] $value->name";
            $data[] = $row;
        }

        $result = array(
            "results" => $data
        );

        return json_encode($result);
    }

    function add(Request $request){
        $amount = str_replace(",", "", $request->amount);
        if (isset($request->edit) && $request->edit == 1) {
            $dp = Finance_depreciation::find($request->id_dp);
            $dp->updated_by = Auth::user()->username;
        } else {
            $dp = new Finance_depreciation();
            $dp->company_id = Session::get('company_id');
            $dp->created_by = Auth::user()->username;
        }

        $dp->item_id = $request->item_id;
        $dp->amount = $amount;
        $dp->start_mnth = $request->mnth;
        $dp->start = $request->year;
        $dp->start_time = $request->year_time;
        $dp->tc_id = (!empty($request->tc_id)) ? $request->tc_id : null;
        $balance = $amount;
        $pctg_value = $amount / $request->year_time;
        $coa = Finance_coa::all()->pluck('code', 'id');
        $item_name = Asset_item::all()->pluck('name', 'id');
        if($dp->save()){
            if (isset($request->edit) && $request->edit == 1) {
                Finance_depreciation_detail::where('id_dp', $dp->id)->forceDelete();
            }
            for ($i=0; $i < ($request->year_time+1); $i++) {
                if ($i > 0 && $balance > 0){
                    $iCoa = new Finance_coa_history();
                    $iCoa->no_coa = $coa[$dp->tc_id];
                    $iCoa->description = "Depreciation : ".$item_name[$dp->item_id]."[dep-$dp->id]";
                    $iCoa->coa_date = ($dp->start + $i)."-$dp->start_mnth-02";
                    $iCoa->debit = $balance;
                    $iCoa->company_id = $dp->company_id;
                    $iCoa->created_by = Auth::user()->username;
                    $iCoa->save();
                }
                $balance -= $pctg_value;
                // $detail = new Finance_depreciation_detail();
                // $detail->id_dp = $dp->id;
                // $detail->year = $dp->start + $i;
                // $detail->from_value = $balance;
                // $detail->dep_value = $pctg_value;
                // $detail->created_by = Auth::user()->username;
                // $detail->company_id = Session::get("company_id");
                // $detail->save();
                // $balance -= $pctg_value;
            }
        }

        return redirect()->route('finance.dp.index');
    }

    function get_data($id){
        $dp = Finance_depreciation::find($id);
        $item = Asset_item::find($dp->item_id);
        $coa = [];
        if(!empty($dp->tc_id)){
            $coa = Finance_coa::find($dp->tc_id);
        }

        return view('finance.dp._edit', compact('dp', 'item', 'coa'));
    }

    function detail($id){
        $dp = Finance_depreciation::find($id);
        $detail = Finance_depreciation_detail::where('id_dp', $dp->id)->get();
        $item = Asset_item::find($dp->item_id);

        return view('finance.dp.detail', compact('dp', 'detail', 'item'));
    }

    function update(Request $request){
        $dp = Finance_depreciation::find($request->id_dp);
        $f = $request->from_value;
        $d = $request->dep_value;
        foreach ($f as $id => $from) {
            $det = Finance_depreciation_detail::find($id);
            if($det->year == $dp->start){
                $dp->amount = str_replace(",", "", $from);
                $dp->save();
            }

            $det->from_value = str_replace(",", "", $from);
            $det->dep_value = str_replace(",", "", $d[$id]);
            $det->updated_by = Auth::user()->username;
            $det->save();
        }

        return redirect()->back();
    }

    function delete($id){
        $code = "[dep-$id]";
        $dp = Finance_depreciation::find($id);
        $dp->deleted_by = Auth::user()->username;
        $dp->save();
        $dp->delete();
        Finance_depreciation_detail::where('id_dp', $id)->delete();
        Finance_coa_history::where('description', 'like', "%$code%")->forceDelete();

        return redirect()->back()->with('msg', 'delete');
    }
}
