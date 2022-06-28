<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Asset_po;
use App\Models\Asset_wo;
use App\Models\Finance_coa;
use Illuminate\Http\Request;
use App\Models\Asset_type_po;
use App\Models\Asset_type_wo;
use App\Models\Finance_coa_source;
use App\Models\Procurement_vendor;
use Illuminate\Support\Facades\Auth;

class HAPoWoTypesController extends Controller
{
    public function index(){

        // $po_types = Asset_type_po::all();
        // $wo_types = Asset_type_wo::all();
        $po = Asset_po::where('company_id', Session::get('company_id'))->get();
        $wo = Asset_wo::where('company_id', Session::get('company_id'))->get();

        $vendor = Procurement_vendor::where('company_id', Session::get('company_id'))->get();
        $vendor_name = array();
        foreach ($vendor as $item){
            $vendor_name[$item->id] = $item->name;
        }

        $src = Finance_coa_source::where('name', 'po')->first();
        $tp_parent = Finance_coa::where('source', 'like', '%"'.$src->id.'"%')
            ->get()->pluck('code');
        $po_types = Finance_coa::where(function($query) use($tp_parent){
            foreach ($tp_parent as $key => $value) {
                $parent_code = rtrim($value, 0);
                $query->where('parent_id', 'like', "$parent_code%");
            }
        })->orderBy('code')->get();

        // dd($po_types);

        $src = Finance_coa_source::where('name', 'wo')->first();
        $tp_parent = Finance_coa::where('source', 'like', '%"'.$src->id.'"%')
            ->get()->pluck('code');
        $wo_types = Finance_coa::where(function($query) use($tp_parent){
            foreach ($tp_parent as $key => $value) {
                $parent_code = rtrim($value, 0);
                $query->where('parent_id', 'like', "$parent_code%");
            }
        })->orderBy('code')->get();

        return view('ha.powo.index', [
            'pos' => $po_types,
            'wos' => $wo_types,
            'po_data' => $po,
            'wo_data' => $wo,
            'vendor_name' => $vendor_name
        ]);
    }

    public function addPoType(Request $request){
        $po = new Asset_type_po();
        $po->name = $request->type_name;
        $po->created_by = Auth::user()->username;

        if ($po->save()){
            return redirect()->route('ha.powotypes.index');
        }
    }

    public function addWoType(Request $request){
        $wo = new Asset_type_wo();
        $wo->name = $request->type_name;
        $wo->created_by = Auth::user()->username;

        if ($wo->save()){
            return redirect()->route('ha.powotypes.index');
        }
    }

    public function updateType(Request $request){
        if ($request->type == "po"){
            $type = Finance_coa::find($request->id_type);
        } else {
            $type = Finance_coa::find($request->id_type);
        }

        $type->name = $request->type_name;

        if ($type->save()){
            return redirect()->route('ha.powotypes.index');
        }
    }

    public function deleteType($id, $type){
        if ($type == "po"){
            $type = Asset_type_po::find($id);
        } else {
            $type = Asset_type_wo::find($id);
        }

        if ($type->delete()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function getTypes ($type, $id){
        if ($type == "po"){
            $src = Finance_coa_source::where('name', 'po')->first();
            $paper = Asset_po::find($id);
            $paper->type = $paper->po_type;
        } else {
            $src = Finance_coa_source::where('name', 'wo')->first();
            $paper = Asset_wo::find($id);
            $paper->type = $paper->wo_type;
        }


        $tp_parent = Finance_coa::where('source', 'like', '%"'.$src->id.'"%')
            ->get()->pluck('code');
        $type = Finance_coa::where(function($query) use($tp_parent){
            foreach ($tp_parent as $key => $value) {
                $parent_code = rtrim($value, 0);
                $query->where('parent_id', 'like', "$parent_code%");
            }
        })->orderBy('code')->get();

        $data = array();
        $val = array();

        foreach ($type as $key => $item){
            $data['id'] = $item->name;
            $data['text'] = $item->name;
            $val[] = $data;
        }

        $response = [
            'results' => $val,
            "paper" => $paper,
            'pagination' => ["more" => true]
        ];

        return json_encode($response);
    }

    function changeType(Request $request){
//        dd($request);
        if ($request->type_data == "po"){
            $type = Asset_po::find($request->id_data);
            $iType = "po_type";
        } else {
            $type = Asset_wo::find($request->id_data);
            $iType = "wo_type";
        }

        $type[$iType] = $request->type_;

        if ($type->save()){
            return redirect()->route('ha.powotypes.index');
        }
    }
}
