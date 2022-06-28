<?php

namespace App\Http\Controllers\Api;

use DB;
use Session;
use App\Models\User;
use App\Models\Asset_wh;
use App\Models\Asset_pre;
use App\Models\Asset_item;
use App\Models\General_do;
use App\Models\Asset_qty_wh;
use App\Models\Driver_Model;
use Illuminate\Http\Request;
use App\Models\General_do_detail;
use App\Http\Controllers\Api\BaseController;


class DeliveryOrderController extends BaseController
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function get_last_id($id){
        $do = General_do::where("id", ">", $id)->get();
        foreach($do as $item){
            $item->detail = General_do_detail::where("do_id", $item->id)->get();
        }
        if(count($do) > 0){
            return $this->sendResponse($do, 'Success');
        } else {
            return $this->sendError('Failed to load data');
        }
    }

    public function insert_gateway(Request $request){
        $do = $request->do;
        try {
            foreach($do as $item){
                $isExist = General_do::where("no_do", $item['no_do'])->first();
                if(empty($isExist)){
                    $newDo = new General_do();
                    foreach($item as $field => $value){
                        if($field != "detail" && $field != "id"){
                            if($field == "created_by"){
                                $newDo[$field] = "assetgateway";
                            } else {
                                $newDo[$field] = $value;
                            }
                        }
                    }

                    $newDo->save();

                    foreach($item['detail'] as $detail){
                        $newDetail = new General_do_detail();
                        foreach($detail as $field => $value){
                            if($field != "id"){
                                $newDetail[$field] = $value;
                            }
                        }
                        $newDetail->save();
                    }
                }
            }
            $lastDO = General_do::orderBy("id", "desc")->first();
            return $this->sendResponse($lastDO->id, "success");
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(). " : ".$th->getLine());
        }
    }

    public function approveDO(Request $request){
        $do = General_do::where('id',$request['id_do'])->first();
        $whfrom = $do->from_id;
        $whto = $do->to_id;

        $listItems = General_do_detail::where('do_id', $request['id_do'])->get();
        foreach ($listItems as $key => $value){
            $item = Asset_item::where('item_code', $value->item_id)->first();
            $item_id = $item->id;
            $qtyupdate = $value->qty;
            $qtywhfrom = Asset_qty_wh::where('item_id', $item_id)
                ->where('wh_id', $whfrom)->first();
            $qtywhto = Asset_qty_wh::where('item_id', $item_id)
                ->where('wh_id', $whto)->first();
            if (empty($qtywhto)){
                $nQtywhto = new Asset_qty_wh();
                $nQtywhto->wh_id = $whto;
                $nQtywhto->item_id = $item_id;
                $nQtywhto->qty = $qtyupdate;
                $nQtywhto->save();
            } else {
                $newqtywhto = intval($qtywhto->qty) + intval($qtyupdate);
                Asset_qty_wh::where('item_id', $item_id)
                    ->where('wh_id', $whto)
                    ->update([
                        'qty' => $newqtywhto
                    ]);
            }

            if (!empty($qtywhfrom)) {
                $newqtywhfrom = intval($qtywhfrom->qty) - intval($qtyupdate);

                Asset_qty_wh::where('item_id', $item_id)
                    ->where('wh_id', $whfrom)
                    ->update([
                        'qty' => $newqtywhfrom
                    ]);
            }
        }
        $do = General_do::find($request['id_do']);
        $do->approved_by = $request['username'];
        $do->approved_time = date('Y-m-d');
        $do->arrive_by = $request['username'];
        $do->arrive_at = date('Y-m-d H:i:s');
        $do->gr_no = $request['username'];
        if (isset($request['notes'])){
            $do->notes = $request['notes'];
        }

        if ($do->save()){
            return $this->sendResponse($do,'Approve Success');
        } else {
            return $this->sendError('Approve Failed');
        }
    }

    public function receive_gateway(Request $request){
        $do = General_do::where("no_do", $request->no_do)->first();
        $whfrom = $do->from_id;
        $whto = $do->to_id;
        $listItems = General_do_detail::where('do_id', $do->id)->get();
        try {
            DB::beginTransaction();
            foreach ($listItems as $key => $value){
                $item = Asset_item::where('item_code', $value->item_id)->first();
                if (!empty($item)) {
                    $item_id = $item->id;
                    $qtyupdate = $value->qty;
                    $qtywhfrom = Asset_qty_wh::where('item_id', $item_id)
                        ->where('wh_id', $whfrom)->first();
                    $qtywhto = Asset_qty_wh::where('item_id', $item_id)
                        ->where('wh_id', $whto)->first();

                    if(strtolower($value->type) == 'transfer'){
                        if (empty($qtywhto)){
                            $nQtywhto = new Asset_qty_wh();
                            $nQtywhto->wh_id = $whto;
                            $nQtywhto->item_id = $item_id;
                            $nQtywhto->qty = $qtyupdate;
                            $nQtywhto->save();
                        } else {
                            $newqtywhto = intval($qtywhto->qty) + intval($qtyupdate);
                            $qtywhto->qty = $newqtywhto;
                            $qtywhto->save();
                        }
                    }

                    if (!empty($qtywhfrom)) {
                        $newqtywhfrom = intval($qtywhfrom->qty) - intval($qtyupdate);
                        if ($newqtywhfrom < 0) {
                            $newqtywhfrom = 0;
                        }

                        $qtywhfrom->qty = $newqtywhfrom;
                        $qtywhfrom->save();

                        // Asset_qty_wh::where('item_id', $item_id)
                        //     ->where('wh_id', $whfrom)
                        //     ->update([
                        //         'qty' => $newqtywhfrom
                        //     ]);
                    }
                }
            }

            $do->approved_by = "gateway";
            $do->approved_time = $request->approved_time;
            $do->arrive_by = "gateway";
            $do->arrive_at = $request->approved_time;
            $do->gr_no = "gateway";
            $do->save();

            $pre = Asset_pre::where('do_id', $do->id)->first();
            if(!empty($pre)){
                $pre->fr_deliver_times = $request->approved_time;
                $pre->save();
            }
            DB::commit();
            return $this->sendResponse($do, 'Success');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
            DB::rollback();
        }
    }

    public function getDetail($comp_id,$id){
        try {
            $do = General_do::leftJoin('asset_wh as wh_from','wh_from.id','=','do.from_id')
                ->leftJoin('asset_wh as wh_to','wh_to.id','=','do.to_id')
                ->select('do.*','wh_from.name as nameFrom', 'wh_to.name as nameTo')
                ->where('do.company_id', $comp_id)
                ->where('do.id',$id)
                ->first();

            $do_detail = General_do_detail::leftJoin('asset_items as item','item.item_code','=','do_detail.item_id')
                ->leftJoin('do','do.id','=','do_detail.do_id')
                ->select('do_detail.*','item.name as item_name', 'item.uom as item_uom')
                ->where('do.company_id', $comp_id)
                ->where('do_detail.do_id',$id)
                ->get();
            $data = [
                'do' => $do,
                'do_detail' => $do_detail
            ];

            if ($do) {
                return $this->sendResponse($data, 'Success');
            } else {
                return $this->sendError('Failed to load data');
            }
        } catch (\Exception $exception) {
            return $this->sendError('Catch some exception!'.$exception->getMessage());
        }
    }

    public function index($comp_id)
    {
        try {
            $do_all = General_do::leftJoin('asset_wh as wh_from','wh_from.id','=','do.from_id')
                ->leftJoin('asset_wh as wh_to','wh_to.id','=','do.to_id')
                ->select('do.*', 'wh_from.name as nameFrom', 'wh_to.name as nameTo')
                ->where('do.company_id', $comp_id)
                ->get();

            if ($do_all) {
                return $this->sendResponse($do_all, 'Success');
            } else {
                return $this->sendError('Failed to load data');
            }
        } catch (\Exception $exception) {
            return $this->sendError('Catch some exception!');
        }
    }

    public function indexDoWaiting($comp_id)
    {
        try {
            $dowaiting = General_do::leftJoin('asset_wh as wh_from','wh_from.id','=','do.from_id')
                ->leftJoin('asset_wh as wh_to','wh_to.id','=','do.to_id')
                ->select('do.*', 'wh_from.name as nameFrom', 'wh_to.name as nameTo')
                ->where('do.company_id', $comp_id)
                ->whereNull('do.approved_time')
                ->get();

            if ($dowaiting) {
                return $this->sendResponse($dowaiting, 'Success');
            } else {
                return $this->sendError('Failed to load data');
            }
        } catch (\Exception $exception) {
            return $this->sendError('Catch some exception!');
        }
    }
    public function indexDoDelivered($comp_id)
    {
        try {
            $do_delivered = General_do::leftJoin('asset_wh as wh_from','wh_from.id','=','do.from_id')
                ->leftJoin('asset_wh as wh_to','wh_to.id','=','do.to_id')
                ->select('do.*', 'wh_from.name as nameFrom', 'wh_to.name as nameTo')
                ->where('do.company_id', $comp_id)
                ->whereNotNull('do.approved_time')
                ->get();

            if ($do_delivered) {
                return $this->sendResponse($do_delivered, 'Success');
            } else {
                return $this->sendError('Failed to load data');
            }
        } catch (\Exception $exception) {
            return $this->sendError('Catch some exception!');
        }
    }

    public function dispatch_do(Request $request){
        $do = General_do::find($request->id);
        $do->departure_at = date("Y-m-d H:i:s");
        $do->departure_by = $request->dispatcher;
        $file = $request->file('image');
        if(!empty($file)){
            $filename = "[$do->id - driver]".$file->getClientOriginalName();
            $dir = public_path('media/do');
            if($file->move($dir, $filename)){
                $do->departure_file = $filename;
            } else {
                return $this->sendError('Failed to upload file');
            }
        }

        if($do->save()){
            return $this->sendResponse($do, 'Pindai berhasil. Silahkan melanjutkan pengiriman');
        } else {
            return $this->sendError('Failed to update data');
        }
    }

    public function get_detail($id){
        $do = General_do::find($id);
        $wh = Asset_wh::all()->pluck('name', 'id');

        if(!empty($do)){
            $do->storage_from = (isset($wh[$do->from_id])) ? $wh[$do->from_id] : "N/A";
            $do->storage_to = (isset($wh[$do->to_id])) ? $wh[$do->to_id] : "N/A";
            $do_detail = General_do_detail::leftJoin('asset_items as item','item.item_code','=','do_detail.item_id')
                ->leftJoin('do','do.id','=','do_detail.do_id')
                ->select('do_detail.*','item.name as item_name', 'item.uom as item_uom')
                ->where('do_detail.do_id',$do->id)
                ->get();

            // if(!empty($do->driver_id))

            $data = [
                "do" => $do,
                "do_detail" => $do_detail
            ];
            if(empty($do->approved_time)){
                if(empty($do->departure_at)){
                    return $this->sendResponse($data, 'ready');
                } else {
                    if(empty($do->arrive_at)){
                        return $this->sendResponse($data, 'DO ditemukan dan sedang dalam pengiriman.');
                    } else {
                        return $this->sendResponse($data, 'Proses pengiriman DO tersebut sudah selesai.');
                    }
                }
            } else {
                return $this->sendResponse($data, 'Proses pengiriman DO tersebut sudah selesai.');
            }
        } else {
            return $this->sendError('DO tidak ditemukan. Harap menghubungi petugas.');
        }
    }

    public function qrMobileGenerate(Request $request){
        try {
            $device = User::where('api_token', $request->token)->first();

            // return json_encode($device);

            if(!empty($device) && !empty($device->dispatch_name)){
                $drivers = Driver_Model::whereNotNull('checkout')
                    ->where('dispatch_name', $device->id)
                    ->first();
                $data = [
                    "driver_id" => $drivers
                ];
                if(!empty($drivers)){
                    return $this->sendResponse($data, 'Success');
                } else {
                    return $this->sendError("No data found");
                }
            } else {
                return $this->sendError("No data found");
            }
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
}
