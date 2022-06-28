<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App\Models\Asset_wh;
use App\Models\Asset_item;
use App\Models\Asset_qty_wh;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\Asset_item_update;
use App\Models\Asset_new_category;
use App\Models\Procurement_vendor;
use Illuminate\Support\Facades\Auth;
use App\Models\Asset_items_qty_history;
use App\Models\Asset_item_classification;

class AssetCategoryInventoryController extends Controller
{
    public function search(Request $request){
        $stringsearch = $request['search_val'];

        $category1 = Asset_new_category::where('company_id','like','%"'.Session::get('company_id').'"%')
            ->where('standard',1)
            ->get();

        //child category
        $comp_id = [];
        $company_child = ConfigCompany::where('id_parent',Session::get('company_id'))
            ->get();
        foreach ($company_child as $key => $value){
            array_push($comp_id,"".$value->id."");
        }
        $category2 = [];
        foreach ($comp_id as $key => $value){
            $category2 = Asset_new_category::where('company_id','like','%'.$value.'%')
                ->where('standard',0)
                ->get();
        }
        //non standard punya company sendiri
        $category3 = Asset_new_category::where('company_id','like','%'.Session::get('company_id').'%')
            ->where('standard',0)
            ->get();

        $category_id = [];
        foreach ($category1 as $key => $value){
            array_push($category_id, $value->id);
        }
        foreach ($category2 as $key => $value){
            array_push($category_id, $value->id);
        }
        foreach ($category3 as $key => $value){
            array_push($category_id, $value->id);
        }

        $searchArr = [];
        $countSearch = 0;
        if ($stringsearch == trim($stringsearch) && strpos($stringsearch, ' ') !== false) {
            $searchArr = explode(' ',$stringsearch);
            $countSearch += count($searchArr);
        } else {
            $searchArr = json_decode('["'.$request['search_val'].'"]');
            $countSearch += 1;
        }

        $items_array = [];
        for ($i = 0; $i < $countSearch; $i++){
            $items = Asset_item::whereIn('category_id',$category_id)
                ->where('name','like','%'.$searchArr[$i].'%')
                ->get();
            foreach ($items as $key => $item){
                $items_array['name'][$item->id] = $item->name;
                $items_array['code'][$item->id] = $item->item_code;
                $items_array['id'][$item->id] = $item->id;
                $items_array['id_count'][] = $item->id;
                $items_array['cat'][$item->id] = $item->category_id;
                $items_array['class'][$item->id] = $item->class_id;
                $items_array['series'][$item->id] = $item->item_series;
            }
        }

        return view('category.searchresultinventory',[
            'searchArr' =>$searchArr,
            'items_array' => $items_array
        ]);
    }

    function indexClassification($category){
        if ($category == null){
            $classification = Asset_item_classification::leftJoin('new_category as cat','cat.id','=','asset_items_classification.id_category')
                ->select('asset_items_classification.*','cat.name as catName')
                ->get();
            $categories = Asset_new_category::all();
            $cat = '';
        } else {
            $classification = Asset_item_classification::leftJoin('new_category as cat','cat.id','=','asset_items_classification.id_category')
                ->select('asset_items_classification.*','cat.name as catName')
                ->where('asset_items_classification.id_category', $category)
                ->get();
            $categories = Asset_new_category::where('id', $category)->get();
            $cat = Asset_new_category::where('id', $category)->first();
        }

//        dd($classification);
        return view('item_class.indexInventory',[
            'classifications' => $classification,
            'categories' => $categories,
            'cat_id' => $category,
            'category' => $cat
        ]);

    }
    public function getCategory(){
        $category = Asset_new_category::all();
        $data = [];
        foreach ($category as $value){
            $data[] = array(
                "id" => $value->id,
                "text" => $value->name.'/'.$value->code,
            );
        }
        return response()->json($data);
    }
    public function index(){

        //standard category
        $category1 = Asset_new_category::where('company_id','like','%"'.Session::get('company_id').'"%')
            ->where('standard',1)
            ->get();

        //child category
        $comp_id = [];
        $company_child = ConfigCompany::where('id_parent',Session::get('company_id'))
            ->get();
        foreach ($company_child as $key => $value){
            array_push($comp_id,"".$value->id."");
        }
        $category2 = [];
        foreach ($comp_id as $key => $value){
            $category2 = Asset_new_category::where('company_id','like','%'.$value.'%')
                ->where('standard',0)
                ->get();
        }
        //non standard punya company sendiri
        $category3 = Asset_new_category::where('company_id','like','%'.Session::get('company_id').'%')
            ->where('standard',0)
            ->get();

        $parent_name = [];
        $id_parent = [];
        foreach ($category1 as $key => $value){
            $parent_name[$value->id] = $value->name;
            $id_parent[$value->id] = $value->id_parent;
        }
        foreach ($category2 as $key => $value){
            $parent_name[$value->id] = $value->name;
            $id_parent[$value->id] = $value->id_parent;
        }
        foreach ($category3 as $key => $value){
            $parent_name[$value->id] = $value->name;
            $id_parent[$value->id] = $value->id_parent;
        }

        return view('category.indexInventory',[
            'categories' => $category1,
            'categories2' => $category2,
            'categories3' => $category3,
            'parents' => $parent_name,
            'id_parents' => $id_parent,
        ]);
    }

    public function loadData(Request $request)
    {
        $t = $_GET['term'];
        $data = Asset_new_category::select('id','name')
            ->where('id', 'like', "%".$t."%")
            ->where('name', 'like', "%".$t."%")
            ->whereNull('deleted_at')->get();
        foreach ($data as $value){
            $val[] = "[".$value->id."] ".$value->name;
        }
        return json_encode($val);
    }

    public function store(Request $request){
        $category = new Asset_new_category();
        $category->id_parent = $request['id_parent'];
        $category->name = $request['name'];
        $category->code = $request['code'];
        $category->standard = 0;
        $category->company_id = '["'.Session::get('company_id').'"]';
        $category->save();
        return redirect()->route('categoryinventory.index');

    }

    public function update(Request $request){
        Asset_new_category::where('id', $request['id'])
            ->update([
                'name' => $request['name'],
                'code' => $request['code'],
                'id_parent' => $request['id_parent']
            ]);

        return redirect()->route('categoryinventory.index');
    }
    public function delete($id){
        Asset_new_category::where('id',$id)->delete();
        Asset_new_category::where('id_parent', $id)->delete();
        return redirect()->route('categoryinventory.index');
    }

    function detail($id){
        $item = Asset_item::leftJoin('asset_items_classification', 'asset_items.class_id', '=', 'asset_items_classification.id')
            ->leftJoin('new_category', 'asset_items.category_id', '=', 'new_category.id')
            ->select('asset_items.*', 'asset_items_classification.classification_name as class_name', 'new_category.name as cat_name')
            ->where('asset_items.id', $id)
            ->first();

        $wh = Asset_wh::all()->pluck('name', 'id');

        return view('items.item_detail', compact('item', 'wh'));
    }

    function add_qty(Request $request){
        $history = new Asset_items_qty_history();
        $history->item_id = $request->_item_id;
        $history->wh_id = $request->_storage;
        $history->qty = $request->_qty;
        $history->notes = $request->_notes;
        $history->created_by = Auth::user()->username;
        $history->company_id = Session::get("company_id");

        if($history->save()){
            $qwh = Asset_qty_wh::where('item_id', $history->item_id)
                ->where('wh_id', $history->wh_id)
                ->first();

            if(empty($qwh)){
                $qwh = new Asset_qty_wh();
                $qwh->item_id = $history->item_id;
                $qwh->wh_id = $history->wh_id;
                $qwh->qty = $history->qty;
            } else {
                $qwh->qty = $qwh->qty + $history->qty;
            }
            if($qwh->save()){
                return redirect()->back()->with('success', 'Quantity updated');
            } else {
                return redirect()->back()->with('error', 'Please contact your system administrator');
            }
        } else {
            return redirect()->back()->with('error', 'Please contact your system administrator');
        }
    }

    function find(Request $request)
    {
        $item = Asset_item::where('id', $request->id)->first();

        $warehouse = Asset_qty_wh::where('item_id', $request->id)
            ->whereNull('deleted_at')
            ->get();
        $qtywh = array();

        $wh = Asset_wh::all()->pluck('name', 'id');
        foreach ($warehouse as $key => $value) {
            if (isset($wh[$value->wh_id])) {
                $row['name'] = $wh[$value->wh_id];
                $row['qty'] = $value->qty;
                $qtywh[$value->wh_id] = $row;
            }
        }

        $val = array('item' => $item, 'qtywh' => $qtywh);

        return json_encode($val);
    }
}
