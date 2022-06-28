<?php
namespace App\Http\Controllers\Api;

use App\Models\Asset_pre;
use App\Models\Asset_item;
use App\Models\Asset_pre_detail;
use App\Http\Controllers\Api\BaseController;
use App\Models\Asset_item_classification;
use App\Models\Asset_new_category;
use App\Models\Asset_wh;
use Illuminate\Http\Request;

class ItemsController extends BaseController {

    function get_item_gateway($last_id, $id_wh){
        $items = Asset_item::where("id", ">", $last_id)->get();

        $wh = Asset_wh::where("id", ">", $id_wh)->get();

        $data = [
            'items' => $items,
            'wh' => $wh
        ];

        if(count($items) > 0 || count($wh) > 0){
            return $this->sendResponse($data, "success");
        } else {
            return $this->sendError('No data found');
        }
    }

    function get_item_gateway_unapproved($key){
        $id = json_decode($key);
        $items = Asset_item::whereIn("id", $id)->get();
        $item = [];
        foreach($items as $value){
            $col = $value;
            $item[$value->id] = $col;
        }

        if(count($item) > 0){
            return $this->sendResponse($item, "success");
        } else {
            return $this->sendError('No data found');
        }
    }

    function get_items_approval($company_id){
        $fr = Asset_pre::where('company_id', $company_id)->get();
        $fr_detail = Asset_pre_detail::whereIn('fr_id', $fr->pluck('id'))->get();
        $items = Asset_item::where('company_id', $company_id)
            ->whereNotNull('uom2')
            ->orderBy('created_at', 'desc')
            ->get();
        $fr_num = $fr->pluck('fr_num', 'id');

        $data = [];

        foreach ($items as $key => $value) {
            $isFr = $fr_detail->where('item_id', $value->item_code)->first();
            if(!empty($isFr)){
                $value->paper = $fr_num[$isFr->fr_id];
                $data[] = $value;
            }
        }
        if (count($data) > 0) {
            return $this->sendResponse($data, "success");
        } else {
            return $this->sendError('No data found');
        }
    }

    function get_category($compid){
        $category = Asset_new_category::all();
        if (count($category) > 0) {
            return $this->sendResponse($category, "success");
        } else {
            return $this->sendError('No data found');
        }
    }

    function get_class($id_cat){
        $class = Asset_item_classification::where('id_category', $id_cat)->get();
        if (count($class) > 0) {
            return $this->sendResponse($class, "success");
        } else {
            return $this->sendError('No data found');
        }
    }

    function detail($id){
        $item = Asset_item::find($id);

        if(!empty($item)){
            return $this->sendResponse($item, 'success');
        } else {
            return $this->sendError('No Data found');
        }
    }

    function get_item_code(Request $request){
        $cat = Asset_new_category::find($request->category);
        $class = Asset_item_classification::find($request->classification);

        $items_exist = Asset_item::where('category_id', $request->category)
            ->where('class_id', $request->classification)
            ->orderBy('item_code', 'desc')
            ->first();

        $num = 1;
        if(!empty($items_exist)){
            $num = intval(substr($items_exist->item_code, -3)) + 1;
        }

        $code = $cat->code.$class->classification_code.sprintf("%03d", $num);

        return $this->sendResponse($code, 'success');
    }
}
