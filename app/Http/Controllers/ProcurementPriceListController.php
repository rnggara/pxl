<?php

namespace App\Http\Controllers;

use App\Models\Asset_po;
use App\Models\Asset_po_detail;
use Illuminate\Http\Request;

class ProcurementPriceListController extends Controller
{
    public function index(){
        $pricelists = Asset_po_detail::leftJoin('asset_po as po','po.id','=','asset_po_detail.po_num')
            ->join('asset_items as item','item.item_code','=','asset_po_detail.item_id')
            ->join('asset_organization as vendor','vendor.id','=','po.supplier_id')
            ->join('new_category as cat', 'cat.id','=','item.category_id')
            ->join('marketing_projects as prj','prj.id','=','po.project')
            ->select('asset_po_detail.*','po.po_num as poNum','item.name as itemName','vendor.name as vendorName','cat.name as catName','item.price as itemPrice','item.uom as itemUom')
            ->groupBy('item.item_code')
            ->whereNull('item.deleted_at')
            ->get();

//        dd($pricelists);
        $item_code = [];
        foreach ($pricelists as $key => $val){
            $item_code[] = $val->item_id;
        }
        $list_po = [];
        $qty_po = [];

        for ($i =0 ; $i<count($item_code); $i++){
            $po = Asset_po::leftJoin('asset_po_detail as po_detail','po_detail.po_num','=','asset_po.id')
                ->select('asset_po.id','asset_po.po_num','po_detail.qty', 'po_detail.price')
                ->where('po_detail.item_id',$item_code[$i])
                ->get();
//            dd($po);
            foreach ($po as $key2 => $val2){
                $list_po[$item_code[$i]][] = $val2->po_num;
                $qty_po[$item_code[$i]][] = $val2->qty;
                $price[$item_code[$i]][] = $val2->price;
            }

        }

//        dd($list_po);
//        dd($qty_po);

        return view('pricelist.index',[
            'pricelists' => $pricelists,
            'list_po' => $list_po,
            'list_qty' => $qty_po,
            'price' => $price
        ]);
    }

}
