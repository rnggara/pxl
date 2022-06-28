<?php

namespace App\Http\Controllers;

use App\Models\Asset_item;
use App\Models\Asset_item_update;
use App\Models\Asset_qty_wh;
use App\Models\Asset_wh;
use App\Models\Procurement_vendor;
use Illuminate\Http\Request;
use App\Models\Asset_item_classification;
use App\Models\Asset_new_category;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;

class AssetItemsClassificationInventoryController extends Controller
{
    public function getClassification($id,$class_id){
        $class = Asset_item_classification::where('id_category', $id)
            ->where('id', $class_id)
            ->get();
        $data = [];
        foreach ($class as $value){
            $data[] = array(
                "id" => $value->id,
                "text" => $value->classification_name.'/'.$value->classification_code
            );
        }
        return response()->json($data);
    }

    function indexInventory($category,$classification){
        $vendor = Procurement_vendor::where('category', 'Supplier')->get();
        $warehouses = Asset_wh::select('id', 'name')->get();
        if (Session::get('company_child') != null){
            $childs = array();
            foreach (Session::get('company_child') as $item) {
                $childs[] = $item->id;
            }
            array_push($childs, Session::get('company_id'));
            $items = Asset_item::leftJoin('new_category as cat','cat.id','=','asset_items.category_id')
                ->select('asset_items.*','cat.name as catName')
                ->where('asset_items.category_id', $category)
                ->where('asset_items.class_id', $classification)->get();
            $itemsup = Asset_item_update::where('approved_by', null)
                ->whereIn('company_id', $childs)
                ->get();
            // $warehouses = Asset_wh::select('name','id','company_id')->whereIn('company_id', $childs)->get();
            $n = 1;
        } else {
            $items = Asset_item::join('new_category as cat','cat.id','=','asset_items.category_id')
                ->select('asset_items.*','cat.name as catName')
                ->where('asset_items.category_id', $category)
                ->where('asset_items.class_id', $classification)
//                ->where('asset_items.category_id', $category)
                ->where('asset_items.company_id', Session::get('company_id'))->get();
            $itemsup = Asset_item_update::where('approved_by', null)
                ->where('company_id', Session::get('company_id'))
                ->get();
            // $warehouses = Asset_wh::select('name','id','company_id')->where('company_id', \Session::get('company_id'))->get();
            $n = 2;
        }
        $qtyWh=[];
        $qtyWhs = Asset_qty_wh::all();
        foreach ($qtyWhs as $keyWh => $valQty){
            $qtyWh[$valQty->item_id][$valQty->wh_id] = $valQty->qty;
        }

        // dd($items ,$n);
//        dd($warehouses);
//        $category = Asset_new_category::all();
        $category = Asset_new_category::where('id', $category)->get();
//        dd($qtyWh);
        return view('items.indexInventory', [
            'qtyWh' => $qtyWh,
            'vendor' => $vendor,
            'items' => $items,
            'itemsup' => count($itemsup),
            'categories' => $category,
            'warehouses' => $warehouses,
        ]);
    }

    public function index($category=null){
        if ($category == null){
            $classification = Asset_item_classification::leftJoin('new_category as cat','cat.id','=','asset_items_classification.id_category')
                ->select('asset_items_classification.*','cat.name as catName')
                ->get();
            $categories = Asset_new_category::all();

        } else {
            $classification = Asset_item_classification::leftJoin('new_category as cat','cat.id','=','asset_items_classification.id_category')
                ->select('asset_items_classification.*','cat.name as catName')
                ->where('asset_items_classification.id_category', $category)
                ->get();
            $categories = Asset_new_category::where('id', $category)->first();
        }

        $items = Asset_item::where('category_id', $category)
            ->get();
        $class_item = [];
        foreach($items as $item){
            $class_item[$item->class_id][] = $item->id;
        }

        $type = "inventory";

//        dd($classification);
        return view('item_class.index',[
            'classifications' => $classification,
            'category' => $categories,
            'cat_id' => $category,
            'type' => $type,
            'class_item' => $class_item
        ]);

    }



    public function store(Request $request){
        $classification = new Asset_item_classification();
        $classification->id_category = $request->category;
        $classification->classification_name = $request->name;
        $classification->classification_code = $request->code;
        $classification->created_by = Auth::user()->username;
        $classification->created_at = date('Y-m-d H:i:s');
        $classification->company_id = \Session::Get('company_id');
        $classification->save();

        return redirect()->back();
    }

    public function update(Request $request){
        $classification = Asset_item_classification::find($request['id']);
        $classification->id_category = $request->category;
        $classification->classification_name = $request->name;
        $classification->classification_code = $request->code;
        $classification->updated_by = Auth::user()->username;
        $classification->updated_at = date('Y-m-d H:i:s');
        $classification->save();

        return redirect()->back();
    }

    public function delete($id){
        Asset_item_classification::find($id)->delete();
        return redirect()->back();
    }
}
