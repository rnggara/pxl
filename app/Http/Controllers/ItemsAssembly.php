<?php

namespace App\Http\Controllers;

use App\Models\Asset_wh;
use App\Models\Asset_item;
use App\Models\Asset_item_assembly;
use App\Models\Asset_qty_wh;
use Illuminate\Http\Request;
use App\Models\Asset_new_category;
use Illuminate\Support\Facades\Auth;
use App\Models\Asset_item_classification;
use Illuminate\Support\Facades\DB;
use Session;

class ItemsAssembly extends Controller
{
    public function index(Request $request){

        $item_category = Asset_new_category::all();

        $childs = array();
        foreach (Session::get('company_child') as $item) {
            $childs[] = $item->id;
        }
        array_push($childs, Session::get('company_id'));
        array_push($childs, Session::get('company_id_parent'));
        $all_item = Asset_item::whereIn('company_id', $childs)
            ->whereNull('uom2')
            ->get();

        if($request->ajax()){
            if(isset($request->_category)){
                if(isset($request->_class)){
                    $code = $this->generate_item_code($request->_category, $request->_class);
                    return json_encode($code);
                }
                $class = Asset_item_classification::select(["id", "classification_name as text"])
                    ->where("id_category", $request->_category)->get();
                if(!empty($class)){
                    $data = [
                        "success" => true,
                        "data" => $class
                    ];
                } else {
                    $data = [
                        "success" => false,
                        "data" => []
                    ];
                }
                return json_encode($data);
            }

            if(isset($request->id)){
                $_list = Asset_item_assembly::where('item_parent', $request->id)->get();
                foreach ($_list as $key => $value) {
                    $nItem = Asset_item::find($value->item_id);
                    if(!empty($nItem)){
                        $value->item_name = "[$nItem->item_code] $nItem->name";
                    } else {
                        $value->item_name = "N/A";
                    }

                    $wh = Asset_qty_wh::where('item_id', $value->item_id)
                        ->where('qty', '>=', $value->qty)
                        ->get()->pluck('wh_id');

                    $value->wh = Asset_wh::whereIn('id', $wh)->get()->pluck('name', 'id');
                }

                $item_id = $request->id;

                return view('items.assembly._modal_approve', compact('_list', 'item_id'));
            }
        }

        $assembly = Asset_item::where('isAssembly', 1)
            ->whereIn('company_id', $childs)
            ->get();

        return view("items.assembly.index", compact("item_category", "all_item", "assembly"));
    }

    public function add_assembly(Request $request){
        $item_id = null;
        $status_item = "";
        if($request->submit == "add"){
            $items = new Asset_item();

            $file = $request->file('pict');

            $uploaddir = public_path('media/asset');

            $items->name = $request->item_name;
            $items->item_code = $request->item_code;
            $items->category_id = $request->category;
            $items->class_id = $request->class_id;
            $items->item_series = $request->item_series;
            $items->supplier = (isset($request->supplier))? $request->supplier : null;
            $items->serial_number = $request->serial_number;
            $items->type_id = $request->type;
            $items->minimal_stock = $request->min_stock;
            $items->uom = $request->uom;
            $items->notes = $request->notes;
            $items->specification = $request->specification;
            $items->created_by = Auth::user()->username;
            $items->company_id = (empty(Session::get('company_id_parent'))) ? Session::get('company_id') : Session::get('company_id_parent');
            if (!empty($file)) {
                $newName = $request->item_code."-".date('Y_m_d').".".$file->getClientOriginalExtension();
                $file->move($uploaddir, $newName);
                $items->picture = $newName;
            }

            if ($items->save()) {
                $item_id = $items->id;
                $warehouses = Asset_wh::where('company_id',\Session::get('company_id'))->get();
                foreach ($warehouses as $key => $value){
                    $qty_wh = new Asset_qty_wh();
                    $qty_wh->item_id = $item_id;
                    $qty_wh->wh_id = $value->id;
                    $qty_wh->qty = 0;
                    $qty_wh->created_at = date('Y-m-d H:i:s');
                    $qty_wh->save();
                }
            }

            $item_id = $items->id;
            $status_item = "add";
        } else {
            $item_id = $request->item_id;
            $status_item = "assign";
        }

        $url = route('items.assembly.list', $item_id)."?s=$status_item";

        return redirect()->to($url);
    }

    public function approve(Request $request){
        DB::beginTransaction();
        try {
            $item = Asset_item::find($request->item_id);

            $storage = $request->_storage;
            $type = $request->type;
            foreach($storage as $id => $val){
                if(strtolower($type[$id]) == "consumed"){
                    $item_assembly = Asset_item_assembly::where('item_parent', $request->item_id)
                        ->where('item_id', $id)
                        ->first();
                    $qtywh = Asset_qty_wh::where('item_id', $id)
                        ->where('wh_id', $val)
                        ->first();
                    $qtywh->qty = $qtywh->qty - $item_assembly->qty;
                    $qtywh->save();
                }
            }

            $item->assembly_approved_at = date("Y-m-d H:i:s");
            $item->assembly_approved_by = Auth::user()->username;
            $item->save();

            DB::commit();
            $mesasge = "Approved";
        } catch (\Throwable $th) {
            DB::rollBack();
            $mesasge = $th->getMessage();
        }

        return redirect()->back()->with('msg', $mesasge);
    }

    public function list($id, Request $request){
        $_item = Asset_item::find($id);

        $childs = array();
        foreach (Session::get('company_child') as $item) {
            $childs[] = $item->id;
        }
        array_push($childs, Session::get('company_id'));
        $all_item = Asset_item::whereIn('company_id', $childs)
            ->whereNull('uom2')
            ->where('id', '!=', $id)
            ->get();

        $type = $request->s;

        $_list = Asset_item_assembly::where('item_parent', $id)->get();
        foreach ($_list as $key => $value) {
            $nItem = Asset_item::find($value->item_id);
            if(!empty($nItem)){
                $value->item_name = "[$nItem->item_code] $nItem->name";
            } else {
                $value->item_name = "N/A";
            }
        }

        if($request->ajax()){
            if(isset($request->item)){
                $qty = Asset_qty_wh::where('item_id', $request->item)
                    ->where('qty', '>=', $request->q)
                    ->sum('qty');

                if($request->q > $qty){
                    $success = false;
                } else {
                    $success = true;
                }

                return json_encode($success);
            }
        }

        return view('items.assembly.list', compact('_item', 'all_item', 'type', '_list'));
    }

    public function add_list(Request $request){
        DB::beginTransaction();
        try {
            $item_id = $request->item_id;
            $item_qty = $request->item_qty;
            $item_type = $request->item_type;

            Asset_item_assembly::where('item_parent', $request->item)->delete();

            foreach ($item_id as $key => $value) {
                $assembly = new Asset_item_assembly();
                $assembly->item_parent = $request->item;
                $assembly->item_id = $value;
                $assembly->qty = $item_qty[$key];
                $assembly->type = $item_type[$key];
                $assembly->created_by = Auth::user()->username;
                $assembly->save();
            }

            $isItem = Asset_item::find($request->item);
            $isItem->isAssembly = 1;
            $isItem->save();
            DB::commit();

            $mesasge = "Success";
        } catch (\Throwable $th) {
            DB::rollBack();
            $mesasge = $th->getMessage();
        }

        return redirect()->back()->with('msg', $mesasge);
    }

    function generate_item_code($catId, $classId){
        $cat = Asset_new_category::find($catId);
        $class = Asset_item_classification::find($classId);

        $items_exist = Asset_item::where('category_id', $catId)
            ->where('class_id', $classId)
            ->orderBy('item_code', 'desc')
            ->first();

        $num = 1;
        if(!empty($items_exist)){
            $num = intval(substr($items_exist->item_code, -3)) + 1;
        }

        $code = $cat->code.$class->classification_code.sprintf("%03d", $num);

        return $code;
    }
}
