<?php

namespace App\Http\Controllers;

use App\Models\Asset_item;
use App\Models\Asset_item_update;
use App\Models\Asset_new_category;
use App\Models\Procurement_vendor;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;

class AssetItemsController extends Controller
{
    function index(){
        $vendor = Procurement_vendor::where('category', 'Supplier')->get();
        $items = Asset_item::leftJoin('new_category as cat','cat.id','=','asset_items.category_id')
            ->select('asset_items.*','cat.name as catName')
            ->whereNull('asset_items.deleted_at')->get();
        $itemsup = Asset_item_update::where('approved_at', null)->get();
        $category = Asset_new_category::all();
        return view('items.index', [
            'vendor' => $vendor,
            'categories' => $category,
            'items' => $items,
            'itemsup' => count($itemsup)
        ]);
    }

    function revision(){
        $vendor = Procurement_vendor::where('category', 'Supplier')->get();
        $items = Asset_item::all();
        foreach ($items as $item) {
            $data[$item->id]['name'] = $item->name;
            $data[$item->id]['type_id'] = $item->type_id;
            $data[$item->id]['item_code'] = $item->item_code;
            $data[$item->id]['minimal_stock'] = $item->minimal_stock;
            $data[$item->id]['uom'] = $item->uom;
        }
        $itemsup = Asset_item_update::where('approved_by', null)->get();
        return view('items.revision', [
            'vendor' => $vendor,
            'data' => $data,
            'itemsup' => $itemsup
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
        $items->item_code = $itemsup->item_code;
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

        return redirect()->route('items.revision');
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
        $items = new Asset_item();

        $file = $request->file('pict');

        $uploaddir = public_path('media/asset');


        $items->name = $request->item_name;
        $items->item_code = $request->item_code;
        $items->category_id = $request->category;
        $items->item_series = $request->item_series;
        $items->supplier = (isset($request->supplier)) ? $request->supplier : 0;
        $items->price = $request->price;
        $items->serial_number = $request->serial_number;
        $items->type_id = $request->type;
        $items->minimal_stock = $request->min_stock;
        $items->uom = $request->uom;
        $items->notes = $request->notes;
        $items->specification = $request->specification;
        $items->company_id = Session::get('company_id');
        if (isset($file)) {
            $newName = $request->item_code."-".date('Y_m_d').".".$file->getClientOriginalExtension();
            $items->picture = $newName;
        }

        if ($items->save()) {
            if (isset($file)) {
                $file->move($uploaddir, $newName);
            }
        }

        return redirect()->route('items.index');
    }

    function delete(Request $request){
        $item = Asset_item::find($request->id);
        if ($item->delete()){
            $data['del'] = 1;
        } else {
            $item['del'] = 0;
        }

        return json_encode($data);
    }

    function find_item(Request $request) {
        $item = Asset_item::where('id', $request->id)->first();

        return json_encode($item);
    }

    function edit_item(Request $request){
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
        $items->item_code = $request->item_code;
        $items->item_series = $request->item_series;
        $items->supplier = $request->supplier;
        $items->price = $request->price;
        $items->serial_number = $request->serial_number;
        $items->type_id = $request->type;
        $items->minimal_stock = $request->min_stock;
        $items->uom = $request->uom;
        $items->notes = $request->notes;
        $items->specification = $request->specification;
        $items->created_by = Auth::user()->username;

        if ($items->save()) {
            if (isset($file)) {
                $file->move($uploaddir, $newName);
            }
        }

        return redirect()->route('items.index');
    }
}
