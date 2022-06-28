<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Asset_po;
use App\Models\Asset_wh;
use App\Models\Asset_pre;
use App\Models\Asset_item;
use App\Models\General_do;
use App\Models\Asset_qty_wh;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\Asset_po_detail;
use App\Models\Asset_pre_detail;
use App\Models\Asset_item_update;
use App\Models\General_do_detail;
use App\Models\Asset_good_receive;
use App\Models\Asset_new_category;
use App\Models\Procurement_vendor;
use App\Models\Finance_depreciation;
use Illuminate\Support\Facades\Auth;
use App\Models\Asset_items_qty_history;
use App\Models\Asset_item_classification;

class AssetItemsController extends Controller
{

    public function itemCodeFunction(Request $request){
        $code = $request->classification;
//        dd($request);
//        dd($code);
        $item = Asset_item::where('item_code','like','%'.$code.'%')
            ->orderBy('item_code','DESC')
            ->get();

        $countitem = count($item);
        if ($countitem >0){
            $item_code =$item[0]['item_code'];
            $lastdigit = substr($item_code, -3);
            $nextdigit = intval($lastdigit)+1;
            if($nextdigit < 10)
            {
                $nextdigit = "00".$nextdigit;
            } elseif ($nextdigit < 100 && $nextdigit >= 10){
                $nextdigit = "0".$nextdigit;

            }
            $CODE = strtoupper($code).$nextdigit;
        } else {
            $CODE = strtoupper($code)."001";
        }

        $data = [
            'data' => $CODE,
        ];
        return json_encode($data);
    }
    function indexInventory(){
        $vendor = Procurement_vendor::where('category', 'Supplier')->get();
        $warehouses = Asset_wh::all();
        if (Session::get('company_child') != null){
            $childs = array();
            foreach (Session::get('company_child') as $item) {
                $childs[] = $item->id;
            }
            array_push($childs, Session::get('company_id'));
            $items = Asset_item::leftJoin('new_category as cat','cat.id','=','asset_items.category_id')
                ->select('asset_items.*','cat.name as catName')
//                ->where('asset_items.category_id', $category)
                ->whereIn('company_id', $childs)
                ->orderBy('item_code', 'asc')
                ->get();
            $itemsup = Asset_item_update::where('approved_by', null)
                ->whereIn('company_id', $childs)
                ->get();
            //  $warehouses = Asset_wh::select('name','id','company_id')->whereIn('company_id', $childs)->get();
        } else {
            $items = Asset_item::leftJoin('new_category as cat','cat.id','=','asset_items.category_id')
                ->select('asset_items.*','cat.name as catName')
//                ->where('asset_items.category_id', $category)
                ->where('company_id', Session::get('company_id'))
                ->orderBy('item_code', 'asc')
                ->get();
            $itemsup = Asset_item_update::where('approved_by', null)
                ->where('company_id', Session::get('company_id'))
                ->get();
            // $warehouses = Asset_wh::select('name','id','company_id')->where('company_id', \Session::get('company_id'))->get();
        }
        $category = Asset_new_category::all();
        return view('items.indexInventory', [
            'vendor' => $vendor,
            'items' => $items,
            'itemsup' => count($itemsup),
            'categories' => $category,
            'warehouses' => $warehouses,
        ]);
    }

    function getItemWh($id_wh){
        $wh = Asset_wh::where('id', $id_wh)->first();
        $assetQtyWH= Asset_qty_wh::where('wh_id',$id_wh)->get();
        $itemsQty = [];
        $itemsId = [];
        foreach ($assetQtyWH as $Key => $value){
            $itemsQty[$value->item_id]['qty'] = $value->qty;
            $itemsId[] = $value->item_id;
        }
        if (Session::get('company_child') != null){
            $childs = array();
            foreach (Session::get('company_child') as $item) {
                $childs[] = $item->id;
            }
            array_push($childs, Session::get('company_id'));
            $items = Asset_item::leftJoin('new_category as cat','cat.id','asset_items.category_id')
                ->select('asset_items.*', 'cat.name as catName')
                // ->whereIn('asset_items.company_id', $childs)
                ->get();
        } else {
            $items = Asset_item::leftJoin('new_category as cat','cat.id','asset_items.category_id')
                ->select('asset_items.*', 'cat.name as catName')
                // ->where('asset_items.company_id', \Session::get('company_id'))
                ->get();
        }

        $item_name = [];
        $item_category = [];
        $item_code = [];
        $item_type = [];
        $item_uom = [];
        $item_comp_id = [];

        foreach ($items as $key => $val){
            $item_name[$val->id]['name'] = $val->name;
            $item_category[$val->id]['cat'] = $val->catName;
            $item_code[$val->id]['code'] = $val->item_code;
            $item_type[$val->id]['type'] = $val->type_id;
            $item_uom[$val->id]['uom'] = $val->uom;
            $item_comp_id[$val->id]['comp_id'] = $val->company_id;
        }

        $companies = ConfigCompany::all();
        $company = [];
        foreach ($companies as $key => $value){
            $company[$value->id]['comp_name'] = $value->tag;
        }
//        dd($itemsId);
        return view('wh.item_wh',[
            'item_name' => $item_name,
            'item_category' => $item_category,
            'item_code' => $item_code,
            'item_type' => $item_type,
            'itemsQty' => $itemsQty,
            'itemsId' => $itemsId,
            'item_uom' => $item_uom,
            'company' => $company,
            'wh' => $wh,
            'item_comp_id' => $item_comp_id,
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

        $items = Asset_item::all();
        $class_item = [];
        foreach($items as $item){
            $class_item[$item->class_id][] = $item->id;
        }

//        dd($classification);
        return view('item_class.index',[
            'classifications' => $classification,
            'categories' => $categories,
            'cat_id' => $category,
            'category' => $cat,
            'class_item' => $class_item
        ]);

    }
    function index($category,$classification){

        $vendor = Procurement_vendor::where('category', 'Supplier')->get();

        $childs = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $childs[] = $item->id;
            }
            array_push($childs, Session::get('company_id'));
        }

        $_comp = ConfigCompany::select('id_parent')
            ->whereNotNull('id_parent')
            ->whereNotNull('inherit')
            ->where('id', Session::get('company_id'))
            ->get();
        foreach ($_comp as $item){
            $childs[] = $item->id_parent;
        }

        $childs[] = Session::get('company_id');
        if (!empty(Session::get('company_id_parent'))) {
            $childs[] = Session::get('company_id_parent');
        }


        $items = Asset_item::leftJoin('new_category as cat','cat.id','=','asset_items.category_id')
            ->select('asset_items.*','cat.name as catName')
            ->where('asset_items.category_id', $category)
            ->where('asset_items.class_id', $classification)
            ->whereIn('asset_items.company_id', array_unique($childs))
            ->whereNull('asset_items.deleted_at')
            ->orderBy('item_code', 'asc')
            ->get();
        $itemsup = Asset_item_update::where('approved_by', null)
            ->whereIn('company_id', $childs)
            ->get();
        $warehouses = Asset_wh::whereIn('company_id', $childs)->get();


        $cat = Asset_new_category::where('id', $category)->first();
        $class = Asset_item_classification::all();
        $iClass = Asset_item_classification::find($classification);

        $dep = Finance_depreciation::all()->pluck('id', 'item_id');

        return view('items.index', [
            'vendor' => $vendor,
            'items' => $items,
            'itemsup' => count($itemsup),
            'categories' => $cat,
            'warehouses' => $warehouses,
            'classification' => $class,
            'class' => $classification,
            'category' => $category,
            'iClass' => $iClass,
            'dep' => $dep
        ]);
    }

    function last_input(){
        return view('items.last_input');
    }

    function last_input_list(Request $request){
        $data = array();

        $data_vendor = Procurement_vendor::where('category', 'Supplier')->get();
        $vendor = array();
        foreach ($data_vendor as $item){
            $vendor[$item->id] = $item;
        }

        $data_cat = Asset_new_category::all();
        $cat = array();
        foreach ($data_cat as $item){
            $cat[$item->id] = $item;
        }

        $items = Asset_item::where('company_id', Session::get('company_id'))
            ->orderBy('created_at', 'desc')
            ->get();
        foreach ($items as $i=>$item){
            $row = array();
            $row['i'] = $i+1;
            $row['name'] = $item->name;
            $row['category'] = (isset($cat[$item->category_id])) ? $cat[$item->category_id]->name : "";
            $row['code'] = "<span class='label label-inline label-primary'>$item->item_code</span>";
            $row['uom'] = $item->uom;
            $row['date'] = date('d F Y', strtotime($item->created_at));
            $row['by'] = $item->created_by;
            $data[] = $row;
        }

        $return['data'] = $data;

        return json_encode($return);
    }

    function revision($category, $classification){
        $vendor = Procurement_vendor::where('category', 'Supplier')->get();
        $items = Asset_item::all();
        foreach ($items as $item) {
            $data[$item->id]['name'] = $item->name;
            $data[$item->id]['type_id'] = $item->type_id;
            $data[$item->id]['item_code'] = $item->item_code;
            $data[$item->id]['minimal_stock'] = $item->minimal_stock;
            $data[$item->id]['uom'] = $item->uom;
        }
        if (Session::get('company_child') != null){
            $childs = array();
            foreach (Session::get('company_child') as $item) {
                $childs[] = $item->id;
            }
            array_push($childs, Session::get('company_id'));
            array_push($childs, Session::get('company_id_parent'));
            $itemsup = Asset_item_update::where('approved_by', null)
                ->whereIn('company_id', $childs)
                ->get();
        } else {
            $itemsup = Asset_item_update::where('approved_by', null)
                ->where('company_id', Session::get('company_id'))
                ->get();
        }

        $url = route('items.index', ['category' => $category, 'classification' => $classification]);

        return view('items.revision', [
            'vendor' => $vendor,
            'data' => $data,
            'itemsup' => $itemsup,
            'back' => $url
        ]);
    }

    function revision_detail($id_item){
        $vendor = Procurement_vendor::where('category', 'Supplier')->get();
        foreach ($vendor as $value) {
            $vendor_name[$value->id] = $value->name;
        }
        $id = explode("-", base64_decode($id_item));
        $itemsup = Asset_item_update::where('id', end($id))->first();
        $item = Asset_item::where('id', $itemsup->id_item)->first();

        return view('items.revision_detail', [
            'vendor' => $vendor_name,
            'item' => $item,
            'itemsup' => $itemsup
        ]);
    }

    function revision_update(Request $request){
        $id = explode("-", base64_decode($request->item_id));
        $itemsup = Asset_item_update::where('id', end($id))->first();
        $items = Asset_item::find($itemsup->id_item);
        $items->name = $itemsup->name;
        $items->item_series = $itemsup->item_series;
        $items->supplier = $itemsup->supplier;
        $items->price = $itemsup->price;
        $items->serial_number = $itemsup->serial_number;
        $items->type_id = $itemsup->type_id;
        $items->minimal_stock = $itemsup->minimal_stock;
        $items->uom = $itemsup->uom;
        $items->notes = $itemsup->notes;
        $items->specification = $itemsup->specification;
        if ($itemsup->picture == "del") {
            $items->picture = null;
        } elseif ($itemsup->picture != "" && !empty($itemsup->picture) && $itemsup->picture != "del") {
            $items->picture = $itemsup->picture;
        }

        Asset_item_update::where('id', end($id))
            ->update([
                'approved_at' => date('Y-m-d'),
                'approved_by' => Auth::user()->username
            ]);

        $items->save();

        return redirect()->route('items.revision', ['category' => $items->category_id, 'classification' => $items->class_id]);
    }

    function revision_delete(Request $request){
        $item = Asset_item_update::find($request->id);
        if ($item->delete()){
            $data['del'] = 1;
        } else {
            $item['del'] = 0;
        }

        return json_encode($data);
    }

    function addItem(Request $request){
        // dd($request);
        $items = new Asset_item();

        $file = $request->file('pict');

        $uploaddir = public_path('media/asset');


        $items->name = strtoupper($request->item_name);
        $items->item_code = $request->item_code;
        $items->category_id = $request->category;
        $items->class_id = $request->class_id;
        $items->item_series = $request->item_series;
        $items->supplier = (isset($request->supplier))? $request->supplier : null;
//        $items->price = $request->price;
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
        // dd($items, $request);

        if ($items->save() && $items->minimal_stock > 0) {

            $item_id = $items->id;
            $warehouses = Asset_wh::where('company_id',\Session::get('company_id'))->get();
            foreach ($warehouses as $key => $value){
                $min_stock = $value->min_stock;
                $qty_wh = new Asset_qty_wh();
                $qty_wh->item_id = $item_id;
                $qty_wh->wh_id = $value->id;
                $qty_wh->qty = $items->minimal_stock;
                $qty_wh->created_at = date('Y-m-d H:i:s');
                $qty_wh->save();
            }

        }

        return redirect()->back();
    }

    function delete(Request $request){
        $item = Asset_item::find($request->id);
        $item->item_code = $item->item_code."-del";
        $item->save();
        if ($item->delete()){
            $data['del'] = 1;
        } else {
            $item['del'] = 0;
        }

        return json_encode($data);
    }

    function find_item(Request $request) {
        $item = Asset_item::where('id', $request->id)->first();

        $warehouse = Asset_qty_wh::where('item_id', $request->id)->get();
        $qtywh = array();
        foreach ($warehouse as $key => $value){
            $qtywh[$value->wh_id] = $value->qty;
        }

        $val = array('item' => $item,'qtywh' => $qtywh);

        return json_encode($val);
    }

    function find_transaction($id){
        $items = Asset_item::find($id);
        $do_details = General_do_detail::where('item_id', $items->item_code)->get();
        $dataDo = General_do::all();
        $whData = Asset_wh::all();
        $wh = array();
        $data = array();
        foreach ($whData as $item){
            $wh[$item->id] = $item;
        }
        $do = array();
        foreach ($dataDo as $item){
            $do[$item->id] = $item;
        }

        $poData = Asset_po::all();
        foreach ($poData as $item){
            $po[$item->id] = $item;
        }

        $poDetailData = Asset_po_detail::where('item_id', $items->item_code)->get();

        $grData = Asset_good_receive::all();
        foreach ($grData as $item){
            $gr[$item->po_num] = $item;
        }

        $his = Asset_items_qty_history::where('item_id', $id)->orderBy('id', 'desc')->get();
        foreach($his as $item){
            $row['no'] = "";
            $row['date'] = date("Y-m-d", strtotime($item->created_at));
            $row['description'] = strip_tags($item->notes);
            $row['paper'] = "-";
            $row['warehouse'] = $wh[$item->wh_id]->name;
            $row['amount'] = $item->qty;
            $data[] = $row;
        }

        foreach ($poDetailData as $item){
            $iGr = (isset($gr[$po[$item->po_num]->po_num])) ? $gr[$po[$item->po_num]->po_num] : null;
            if (!empty($iGr)){
                $row['no'] = "";
                $row['date'] = $iGr->gr_date;
                $row['description'] = "Good Received";
                $row['paper'] = $iGr->po_num;
                $row['warehouse'] = $wh[$iGr->wh_id]->name;
                $row['amount'] = $item->qty;
                $data[] = $row;
            }
        }

        foreach ($do_details as $item){
            $description = ($item->type == "Transfer") ? "Transfer" : "Use";
            $row['no'] = "";
            $row['date'] = $do[$item->do_id]->deliver_date;
            $row['description'] = "DO - ".$description;
            $row['paper'] = $do[$item->do_id]->no_do;
            $row['warehouse'] = $wh[$do[$item->do_id]->from_id]->name;
            $row['amount'] = $item->qty*-1;
            $data[] = $row;
            if ($item->type == "Transfer"){
                $row['no'] = "";
                $row['date'] = $do[$item->do_id]->deliver_date;
                $row['description'] = "DO - ".$description;
                $row['paper'] = $do[$item->do_id]->no_do;
                $row['warehouse'] = $wh[$do[$item->do_id]->to_id]->name;
                $row['amount'] = $item->qty;
                $data[] = $row;
            }
        }

        if (count($data) > 0){
            usort($data, function ($a, $b){
                if ($a["date"] == $b["date"])
                    return (0);
                return (($a["date"] > $b["date"]) ? -1 : 1);
            });
        }

        $val = array(
            "data" => $data
        );

        return json_encode($val);
    }

    function edit_item(Request $request){
//        dd($request->wh);

        $items = new Asset_item_update();
        $file = $request->file('pict');
        $uploaddir = public_path('media/asset');
        $itemup = Asset_item_update::where('id_item', $request->id_item)->get();
        if (count($itemup) == 0) {
            $count = 1;
        } else {
            $count = count($itemup) + 1;
        }
        $del_pict = $request->del_pict;
        if (isset($del_pict)) {
            $items->picture = "del";
        } else {
            if (isset($file)) {
                $newName = $request->item_code."-".date('Y_m_d')."(".$count.").".$file->getClientOriginalExtension();
                $items->picture = $newName;
            }
        }

        $items->id_item = $request->id_item;
        $items->name = $request->item_name;
//        $items->item_code = $request->item_code;
        $items->item_series = $request->item_series;
        $items->supplier = $request->supplier;
        $items->price = $request->price;
        $items->serial_number = $request->serial_number;
        $items->type_id = $request->type;
        $items->minimal_stock = $request->min_stock;
        $items->uom = $request->uom;
        $items->notes = $request->notes;
        $items->specification = $request->specification;
        // $items->company_id = Session::get('company_id');
        $items->company_id = 1;
        $items->created_by = Auth::user()->username;

        if ($items->save()) {
            $warehouses = $request->wh;
            if (isset($file)) {
                $file->move($uploaddir, $newName);
            }
            // foreach ($warehouses as $key => $value){
            //     Asset_qty_wh::where('item_id', $request->id_item)
            //         ->where('wh_id',$key)
            //         ->update([
            //             'qty' => $value
            //         ]);
            // }
        }

        return redirect()->back();
    }

    function items_approval(){
        $comp_id = (empty(Session::get('company_id_parent'))) ? Session::get('company_id') : Session::get('company_id_parent');
        $items = Asset_pre_detail::join('asset_items', 'asset_items.item_code', '=', 'asset_pre_detail.item_id')
            ->whereNull('asset_items.deleted_at')
            ->whereNotNull('asset_items.uom2')
            ->where('asset_items.company_id', $comp_id)
            ->select('asset_pre_detail.fr_id', 'asset_items.id as item_id', 'asset_pre_detail.id as frId', 'asset_items.name')
            ->get();

        $fr = Asset_pre::where('company_id', Session::get('company_id'))
            ->pluck('fr_num', 'id');

        return view('items.approval', compact('fr', 'items'));
    }

    function items_approval_get($id){
        $childs = array();
        foreach (Session::get('company_child') as $item) {
            $childs[] = $item->id;
        }
        array_push($childs, Session::get('company_id'));
        if(!empty(Session::get('company_id_parent'))){
            array_push($childs, Session::get('company_id_parent'));
        }
        $all_item = Asset_item::whereIn('company_id', $childs)
            ->whereNull('uom2')
            ->get();
        $items = Asset_item::find($id);

        $categories = Asset_new_category::all();

        return view('items._modal_approval', compact('items', 'categories', 'all_item'));
    }

    function items_approval_class_get(Request $request, $category){
        $class = Asset_item_classification::where('id_category', $category)
            ->where('classification_name', 'like', '%'.$request->q.'%')
            ->orderBy('classification_name')
            ->pluck('classification_name', 'id');
        $row = [];
        foreach($class as $key => $item){
            $col['id'] = $key;
            $col['text'] = $item;
            $row[] = $col;
        }

        $result = array(
            "results" => $row
        );

        return json_encode($result);
    }

    function items_approval_get_code(Request $request){
        $cat = Asset_new_category::find($request->cat);
        $class = Asset_item_classification::find($request->class);

        $items_exist = Asset_item::where('category_id', $request->cat)
            ->where('class_id', $request->class)
            ->orderBy('item_code', 'desc')
            ->first();

        $num = 1;
        if(!empty($items_exist)){
            $num = intval(substr($items_exist->item_code, -3)) + 1;
        }

        $code = $cat->code.$class->classification_code.sprintf("%03d", $num);

        return json_encode($code);
    }

    function items_approval_update(Request $request){
        if($request->submit == "assign"){
            $old_item = Asset_item::find($request->id);
            $iteme = Asset_item::find($request->_item_exist);
            $detail = Asset_pre_detail::where('item_id', $request->old_code)->get();
            foreach($detail as $item){
                $item->item_id = $iteme->item_code;
                $item->save();
            }
            $old_item->delete();
        } else {
            $category = Asset_new_category::find($request->category);
            $class = Asset_item_classification::find($request->classification);

            $itemsLast = Asset_item::where('category_id', $category->id)
                ->where('class_id', $class->id)
                ->orderBy('item_code', 'desc')
                ->first();

            $last_num = 1;
            if(!empty($itemsLast)){
                $last_num = intval(substr($itemsLast->item_code, -3)) + 1;
            }
            $newItemCode = $category->code.$class->classification_code.sprintf("%03d", $last_num);
            $itemCode = Asset_item::where("item_code", $newItemCode)->get();
            while(count($itemCode) != 0){
                $last_num++;
                $newItemCode = $category->code.$class->classification_code.sprintf("%03d", $last_num);
                $itemCode = Asset_item::where("item_code", $newItemCode)->get();
            }


            $item = Asset_item::find($request->id);
            $item->name = $request->item_name;
            $item->category_id = $category->id;
            $item->class_id = $class->id;
            $item->item_series = $request->item_series;
            $item->serial_number = $request->serial_number;
            $item->item_code = $newItemCode;
            $item->type_id = $request->type;
            $item->updated_by = Auth::user()->username;
            $item->uom2 = null;

            if($item->save()){
                Asset_pre_detail::where('item_id', $request->old_code)
                    ->update([
                        "item_id" => $item->item_code
                    ]);
            }
        }

        return redirect()->back();
    }

    function itemWh()
    {
        $item_all = Asset_item::where('company_id', Session::get('company_id'))->orderBy('item_code')->get();
        $items = $item_all->pluck('name', 'id');
        $item_code= $item_all->pluck('item_code', 'id');
        $wh = Asset_wh::all()->pluck('name', 'id');
        $qty_wh = Asset_qty_wh::all();

        return view('items.items_wh', compact('items', 'wh', 'qty_wh', 'item_code'));
    }

    function get_items_class($class, Request $request){
        $items = Asset_item::where('class_id', $class)
            ->whereRaw("(name like '%$request->q%' or item_code like '%$request->q%')")
            ->orderBy('item_code')
            ->get();

        $option = [];
        foreach($items as $item){
            $row = [];
            $row['id'] = $item->id;
            $row['text'] = "[$item->item_code] $item->name";
            $option[] = $row;
        }

        $result = array(
            "results" => $option
        );

        return json_encode($result);
    }

    function get_item_js(Request $request){
        $childs = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $childs[] = $item->id;
            }
            array_push($childs, Session::get('company_id'));
        }
        $childs[] = Session::get('company_id');
        if (!empty(Session::get('company_id_parent'))) {
            $childs[] = Session::get('company_id_parent');
        }
        $items = Asset_item::where(function($query) use($request){
                if(!empty($request['term'])){
                    $query->where('item_code','like','%'.$request['term'].'%');
                    $query->orWhere('name','like','%'.$request['term'].'%');
                }
            })
           ->whereIn('company_id', array_unique($childs))
           ->whereNull('uom2')
           ->get();
        $data = [];
        foreach($items as $i){
            $row = [];
            $row['id'] = $i->id;
            $row['text'] = "[$i->item_code] $i->name";
            $data[] = $row;
        }

        $result = [
            "results" => $data
        ];

        return json_encode($result);
    }
}
