<?php

namespace App\Http\Controllers;

use App\Models\Asset_good_receive;
use App\Models\Asset_gr_detail;
use App\Models\Asset_item;
use App\Models\Asset_po;
use App\Models\Asset_po_detail;
use App\Models\Asset_qty_wh;
use App\Models\Asset_type_po;
use App\Models\Asset_wh;
use App\Models\General_do;
use App\Models\General_do_detail;
use App\Models\Marketing_project;
use App\Models\Pref_tax_config;
use App\Models\Procurement_vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;
use Session;

class AssetGoodReceiveController extends Controller
{
    public function index(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $po = Asset_po::whereIn('company_id', $id_companies)
            ->where('po_date', '>=', '2021-01-01')
            ->whereNotNull('approved_time')
            ->orderBy('id', 'desc')
            ->get();

        $poId = [];

        foreach($po as $dataPo){
            $poId[] = $dataPo->id;
        }


        $po_det = Asset_po_detail::whereIn('po_num', $poId)->get();
        $price = array();
        $qty = array();
        foreach ($po_det as $value){
            $qty[$value->po_num][] = $value->qty;
            $price[$value->po_num][] = $value->price;
        }
        $po_type = Asset_type_po::all();
        $id_tax = [];
        $conflict = [];
        $formula = [];

        $tax = Pref_tax_config::all();
        foreach ($tax as $key => $value){
            $id_tax[] = $value->id;
            $conflict[$value->id] = json_decode($value->conflict_with);
            $formula[$value->id] = $value->formula;
        }
        $pro = Marketing_project::all();
        $pro_name = array();
        foreach ($pro as $value){
            $pro_name[$value->id] = $value->prj_name;
        }

        $vendor = Procurement_vendor::all();
        $vendor_name = array();
        foreach ($vendor as $item) {
            $vendor_name[$item->id] = $item->name;
        }

        return view('gr.index',[
            'po' => $po,
            'pro_name' => $pro_name,
            'vendor_name' => $vendor_name,
            'formula' => $formula,
            'qty_det' => $qty,
            'price_det' => $price,
            'po_type' => $po_type,
            'tax' => $tax
        ]);
    }

    public function getDetail($id, $type =null){
        $wh = Asset_wh::where('company_id', \Session::get('company_id'))->get();
        $po = Asset_po::where('id', $id)->first();
        $po_detail = Asset_po_detail::all();

        $vendor = Procurement_vendor::all();
        foreach ($vendor as $item) {
            $vendor_name[$item->id] = $item->name;
        }
        $items = Asset_item::all();
        $item_name =[];
        $item_code =[];
        $item_uom =[];
        foreach ($items as $item) {
            $item_name[$item->item_code] = $item->name;
            $item_code[$item->item_code] = $item->item_code;
            $item_uom[$item->item_code] = $item->uom;
        }

        $vendor = Procurement_vendor::all();
        $vendor_name = [];
        foreach ($vendor as $item) {
            $vendor_name[$item->id] = $item->name;
        }
        $pro_name = [];

        $pro = Marketing_project::all();
        foreach ($pro as $value){
            $pro_name[$value->id] = $value->prj_name;
        }

        $gr = Asset_good_receive::where('po_id', $po->id)->get();
        $gr_id = $gr->pluck('id');

        $item_prev = [];
        $gr_det = [];
        if(!empty($gr)){
            $gr_det = Asset_gr_detail::whereIn('gr_id', $gr_id)->get();
            foreach ($gr_det as $key => $value) {
                $item_prev[$value->item_id][] = $value->qty;
            }
        }

        return view('gr.detail',[
            'po' => $po,
            'po_detail' => $po_detail,
            'item_name' => $item_name,
            'item_code' => $item_code,
            'item_uom' => $item_uom,
            'vendor_name' => $vendor_name,
            'pro_name' => $pro_name,
            'type' => $type,
            'whs' => $wh,
            'gr' => $gr,
            'gr_det' => $gr_det,
            'item_prev' => $item_prev
        ]);
    }

    public function approveGR(Request $request){
        // dd($request);
        // Asset_po::where('id',$request['po_id'])
        //     ->update([
        //         'gr_date' => $request['receive_date'],
        //     ]);

        $po = Asset_po::find($request['po_id']);

        $item_recv = $request->qty_receive;
        $item_qty = $request->item_qty;

        $gr = new Asset_good_receive();
        $gr->po_id = $request['po_id'];
        $gr->po_num = $request['po_num'];
        $gr->gr_date = $request['receive_date'];
        $gr->wh_id = $request['warehouse'];
        $gr->gr_by = Auth::user()->username;
        $gr->notes = $request['notes'];
        $gr->created_at = date('Y-m-d H:i:s');
        $gr->save();

        $recv = 0;

        foreach ($item_recv as $key => $value) {
            $grDetail = new Asset_gr_detail();
            $grDetail->gr_id = $gr->id;
            $grDetail->po_id = $request->po_id;
            $grDetail->item_id = $key;
            $grDetail->qty = $value;
            $grDetail->created_by = Auth::user()->username;
            $grDetail->company_id = Session::get("company_id");
            $grDetail->save();
            $qty_left = $item_qty[$key] - $value;
            if($qty_left > 0){
                $recv += 1;
            }
        }

        if($recv == 0){
            Asset_po::where('id',$request['po_id'])
            ->update([
                'gr_date' => $request['receive_date'],
            ]);
        }

        $arrRomawi	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");

        $po_details = Asset_po_detail::where('po_num', $request['po_id'])->get();
        if (!empty($request['warehouse_deliver'])){
            $isDO = General_do::where('company_id', Session::get('company_id'))
                ->where('created_at', 'like', date('Y').'-%')
                ->orderBy('no_do', 'desc')
                ->first();
            if (empty($isDO)){
                $num = "001/".Session::get('company_tag')."/DO/".$arrRomawi[date('n')]."/".date('y');
            } else {
                $last = explode("/", $isDO->no_do);
                $lastNum = intval($last[0]) + 1;
                $num = sprintf("%03d", $lastNum)."/".Session::get('company_tag')."/DO/".$arrRomawi[date('n')]."/".date('y');
            }

            $do = new General_do();
            $do->from_id = $request['warehouse'];
            $do->to_id = $request['warehouse_deliver'];
            $do->deliver_date = date('Y-m-d H:i:s');
            $do->deliver_by = Auth::user()->username;
            $do->gr_no = Auth::user()->username;
            $do->approved_by = Auth::user()->username;
            $do->approved_time = date('Y-m-d H:i:s');
            $do->no_do = $num;
            $do->division = $po->division;
            $do->company_id = Session::get('company_id');
            $do->save();
        }
        foreach ($po_details as $key => $val){
            $qtyrecv = $item_recv[$val->item_id];
            $item = Asset_item::where('item_code', $val->item_id)->first();
            $qtywh = Asset_qty_wh::where('wh_id', $request['warehouse'])
                ->where('item_id', $item->id)->get();
            if (count($qtywh)>0){
                $qtyold = intval($qtywh[0]->qty);
                $qtynew = intval($qtyrecv) + $qtyold;
                Asset_qty_wh::where('wh_id', $request['warehouse'])
                    ->where('item_id', $item->id)
                    ->update([
                        'qty' => $qtynew
                    ]);
            } else {
//                dd($request['warehouse']);
                $wh = new Asset_qty_wh();
                $wh->item_id = $item->id;
                $wh->wh_id = $request['warehouse'];
                $wh->qty = $qtyrecv;
                $wh->save();
            }

            if (isset($do)){
                if($qtyrecv > 0){
                    $do_detail = new General_do_detail();
                    $do_detail->do_id = $do->id;
                    $do_detail->item_id = $item->item_code;
                    $do_detail->qty = $qtyrecv;
                    $do_detail->type = "Transfer";
                    $do_detail->save();

                    $whold = Asset_qty_wh::where('wh_id', $request['warehouse'])
                        ->where('item_id', $item->id)->first();
                    if(!empty($whold)){
                        $whqtyold = $whold->qty;
                        $whnewqtyold = $whqtyold - $qtyrecv;
                        Asset_qty_wh::where('wh_id', $request['warehouse'])
                            ->where('item_id', $item->id)
                            ->update([
                                'qty' => $whnewqtyold
                            ]);
                    } else {
                        $wh = new Asset_qty_wh();
                        $wh->item_id = $item->id;
                        $wh->wh_id = $request['warehouse'];
                        $wh->qty = $qtyrecv;
                        $wh->save();
                    }

                    $whnew = Asset_qty_wh::where('wh_id', $request['warehouse_deliver'])
                        ->where('item_id', $item->id)->first();

                    if (!empty($whnew)){
                        $whqtynew = $whnew->qty;
                        $newwhqtynew = $whqtynew + $qtyrecv;
                        Asset_qty_wh::where('wh_id', $request['warehouse_deliver'])
                            ->where('item_id', $item->id)
                            ->update([
                                'qty' => $newwhqtynew
                            ]);
                    } else {
                        $wh = new Asset_qty_wh();
                        $wh->item_id = $item->id;
                        $wh->wh_id = $request['warehouse_deliver'];
                        $wh->qty = $qtyrecv;
                        $wh->save();
                    }
                }
            }
//            dd(count($qtywh));
        }

        return redirect()->route('gr.index');
    }

    function detail($id, $type = null){
        $gr = Asset_good_receive::find($id);
        $po = Asset_po::find($gr->po_id);

        $detail = Asset_gr_detail::where('gr_id', $gr->id)->get();

        $vendor = Procurement_vendor::find($po->supplier_id);

        $items = Asset_item::all();
        $item_name = $items->pluck('name', 'item_code');
        $item_uom = $items->pluck('uom', 'item_code');

        $project = Marketing_project::find($po->project);

        $po_detail = Asset_po_detail::where('po_num', $po->id)->get();

        $item_price = $po_detail->pluck('price', 'item_id');

        return view('gr.view', compact('gr', 'po', 'detail', 'item_name', 'item_uom', 'item_price', 'vendor', 'project', 'type'));
    }
}
