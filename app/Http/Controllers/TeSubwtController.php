<?php

namespace App\Http\Controllers;

use App\Models\Te_equipment_list;
use App\Models\Te_equipment_list_category;
use App\Models\Te_subwt;
use Illuminate\Http\Request;
use Session;

class TeSubwtController extends Controller
{
    function index(){
        $swt = Te_subwt::where('company_id', Session::get('company_id'))->get();
        return view('te.subwt.index', [
            'swt' => $swt
        ]);
    }

    function add(Request $request){
        $swt = new Te_subwt();
        $swt->subject = $request->project_name;
        $swt->company_id = Session::get('company_id');
        if ($swt->save()){
            return redirect()->back();
        }
    }

    function find($id){
        $swt = Te_subwt::find($id);

        return view('te.subwt._edit', [
            'swt_edit' => $swt
        ]);
    }

    function update(Request $request){
        $swt = Te_subwt::find($request->id_swt);
        $swt->subject = $request->project_name;
        if ($swt->save()){
            return redirect()->back();
        }
    }

    function delete($id){
        Te_subwt::find($id)->delete();
        return redirect()->back();
    }

    function items($id){
        $swt = Te_subwt::find($id);

        return view('te.subwt.items', [
            'swt' => $swt
        ]);
    }

    function get_items($id){
        $swt = Te_subwt::find($id);
        $list = array();

        $el = Te_equipment_list::where('company_id', Session::get('company_id'))->get();
        $item = array();
        foreach ($el as $value){
            $item[$value->id] = $value;
        }

        $el_cat = Te_equipment_list_category::all();
        $cat = array();
        foreach ($el_cat as $n){
            $cat[$n->id] = $n->category_name;
        }

        $data = array();

        $i = 1;
        if (!empty($swt->additional_information)){
            $row = json_decode($swt->additional_information);
            foreach ($row as $value){
                if (isset($item[$value])){
                    $column['key'] = $i++;
                    $column['serial_number'] = "<a class='btn btn-xs btn-primary' href='javascript:view_items(".$item[$value]['id'].")'>".$item[$value]['serial_number']."</a>";
                    $column['category'] = (isset($cat[$item[$value]['category']])) ? $cat[$item[$value]['category']] : "N/A";
                    if ($item[$value]['type'] == 1){
                        $column['type'] = "MAIN EQUIPMENT";
                    } elseif ($item[$value]['type'] == 2){
                        $column['type'] = "ACCESORIES";
                    } else {
                        $column['type'] = "SAFETY EQUIPMENT";
                    }
                    $column['subject'] = $item[$value]['subject'];
                    $column['status'] = ($item[$value]['status'] == 1) ? "READY" : "NOT READY";
                    $data[] = $column;
                }
            }
        }

        $val = [
            'data' => $data
        ];

        return json_encode($data);
    }

    function update_items(Request $request){
        $swt = Te_subwt::find($request->id_pd);
        $swt->additional_information = $request->items;
        if ($swt->save()){
            return redirect()->back();
        }
    }
}
