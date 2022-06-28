<?php

namespace App\Http\Controllers;

use DB;
use Session;
use Carbon\Carbon;
use App\Models\Asset_wh;
use App\Models\Division;
use App\Models\Asset_pre;
use App\Models\Asset_item;
use App\Models\General_do;
use App\Models\Asset_qty_wh;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Helpers\FileManagement;
use App\Models\File_Management;
use App\Models\General_do_detail;
use App\Models\Hrd_employee_ppe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GeneralDOController extends Controller
{
    public function getWarehouse(Request $request){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $warehouses = Asset_wh::whereIn('company_id', [Session::get('company_id'),1])
        // $warehouses = Asset_wh::where('company_id', Session::get('company_id'))
            // ->orwhere('company_id', "=",1)
            ->where('name', 'like', "%$request->searchTerm%")
            ->get();

        $data = [];
        foreach ($warehouses as $value){
            $data[] = array(
                "id" => $value->id,
                "text" => $value->name
            );
        }
        return response()->json($data);

    }

    public function getWarehouseReport($id_wh){
        $wh = Asset_wh::where('id', $id_wh)->first();
        $months = array();
        $now = Carbon::now();
        $currentMonth = $now->month;
        $yearnow = $now->year;

        for ($i = 0; $i < 12; $i++) {
            $timestamp = mktime(0, 0, 0, date('n') - $i, 1);
            $months[date('n', $timestamp)] = date('F', $timestamp);
        }
        ksort($months);



        return view('do.wh_report',[
            'id_wh' => $id_wh,
            'wh' => $wh,
            'months' => $months,
            'now' => $now,
            's_month' => $currentMonth,
            's_year' => $yearnow,
        ]);

    }

    public function whReport(Request $request,$id_wh){
//        dd($request);

        $wh = Asset_wh::where('id', $id_wh)->first();
        $months = array();
        $now = Carbon::now();
        $currentMonth = $now->month;
        $yearnow = $now->year;

        for ($i = 0; $i < 12; $i++) {
            $timestamp = mktime(0, 0, 0, date('n') - $i, 1);
            $months[date('n', $timestamp)] = date('F', $timestamp);
        }
        ksort($months);

        $days_count = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);


        $do_detail = General_do_detail::leftJoin('do as do','do.id','=','do_detail.do_id')
            ->select('do.from_id','do.to_id','do.deliver_date','do_detail.qty','do_detail.item_id')
            ->whereMonth('do.deliver_date','=',$request->month)
            ->whereYear('do.deliver_date','=',$request->year)
            ->where('do.from_id', $id_wh)
            ->orWhere('do.to_id', $id_wh)
            ->get();

        $itemQty = [];
        $item=[];
        foreach ($do_detail as $key => $value){
            if($value->from_id == $id_wh){
                $itemQty[$value->item_id][date('j',strtotime($value->deliver_date))]['out'][] = $value->qty;
            }
            if ($value->to_id == $id_wh){
                $itemQty[$value->item_id][date('j',strtotime($value->deliver_date))]['in'][] = $value->qty;
            }
            $item[$value->item_id] = $value->item_id;
        }

        $itemName = [];
        $items = Asset_item::all();
        foreach ($items as $key => $value){
            $itemName[$value->item_code] = $value->name;
        }
//        dd($itemQty);


        return view('do.wh_report',[
            'id_wh' => $id_wh,
            'wh' => $wh,
            'months' => $months,
            'now' => $now,
            's_month' => $currentMonth,
            's_year' => $yearnow,
            'post' => 1,
            'days' => $days_count,
            'itemQty' => $itemQty,
            'itemName' => $itemName,
            'item' => $item,
        ]);
    }



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
        $delOrders = General_do::LeftJoin('asset_wh AS from','from.id','=','do.from_id')
            ->leftJoin('asset_wh AS to','to.id','=','do.to_id')
//            ->join('marketing_projects AS prj','prj.id','=','do.project')
            ->select('from.name AS whFromName','to.name AS whToName','do.*',DB::raw('(SELECT COUNT(do_id) FROM do_detail WHERE do_id = do.id) AS items'))
            ->where('do.company_id',Session::get('company_id'))
            ->orderBy('do.id', 'desc')
            ->get();

        $warehouse = Asset_wh::all();
        $wh_name = [];
        foreach ($warehouse as $key=> $value){
            $wh_name[$value->id] = $value->name;
        }
        $wh_from = [];
        $do_wh_from = General_do::groupBy('from_id')
            ->get();
        foreach ($do_wh_from as $key => $value){
            $wh_from[] = $value->from_id;
        }
        $wh_to = [];
        $do_wh_to = General_do::groupBy('to_id')
            ->get();

        foreach ($do_wh_to as $key => $value){
            $wh_to[] = $value->to_id;
        }
        // dd($wh_from);
        $wh_merge = [];
        $wh_merge = array_unique(array_merge($wh_from,$wh_to), SORT_REGULAR);
       // dd($wh_merge);
        // if ($wh_merge[0] == 0) {
        //     unset($wh_merge[0]);
        // }
        // dd($wh_merge);
        $divisions = Division::all();

//        dd($delOrders);
        return view('do.index',[
            'dos' => $delOrders,
            'wh_name' => $wh_name,
            'wh_merge' => $wh_merge,
            'divisions' => $divisions
        ]);

    }

    public function nextDocNumber($code,$year){
        $cek = General_do::where('no_do','like','%'.$code.'%')
            ->where('deliver_date','like','%'.date('y').'-%')
            ->where('company_id', \Session::get('company_id'))
            ->whereNull('deleted_at')
            ->orderBy('id','DESC')
            ->get();

        if (count($cek) > 0){
            $frNum = $cek[0]->no_do;
            $str = explode('/', $frNum);
            if (date('y',strtotime($year)) == date('y')){
                $number = intval($str[0]);
                $number+=1;
                if ($number > 99){
                    $no = strval($number);
                } elseif ($number > 9) {
                    $no = "0".strval($number);
                } else {
                    $no = "00".strval($number);
                }
            } else {
                $no = "001";
            }
        } else {
            $no = "001";
        }
        return $no;
    }
    public function store(Request $request){
        if(count($request->code) > 0){
            $arrRomawi	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
            $do = new General_do();
            $deliver_time = $request['delivery_time'];
            $no_do = $this->nextDocNumber(strtoupper(\Session::get('company_tag')).'/DO',$deliver_time);
            $no_do_id = sprintf('%03d',$no_do).'/'.strtoupper(\Session::get('company_tag')).'/DO/'.$arrRomawi[date("n")].'/'.date("y");
            $do->no_do = $no_do_id;
            $do->company_id = \Session::get('company_id');
            $do->division = $request['division'];
            $do->notes = $request['notes'];
            $do->location = $request['location'];
            $do->deliver_by = $request['deliver_by'];
            $do->deliver_date = $request['delivery_time'];
            $do->from_id = $request['from'];
            $do->to_id = $request['to'];
            if (isset($request->id_driver)) {
                $do->driver_id = $request->id_driver;
            }
            $do->save();
            $last_id = $do->id;
            foreach ($request->code as $key => $itemCode){
                $do_detail = new General_do_detail();
                $do_detail->do_id = $last_id;
                $do_detail->item_id = $itemCode;
                $do_detail->qty = $request['qty'][$key];
                $do_detail->type = $request['transfer_type'][$key];
                $do_detail->save();
            }

            if(isset($request->fr_id)){
                $pre = Asset_pre::find($request->fr_id);
                $pre->do_id = $do->id;
                $pre->save();

                return redirect()->route('fr.index');
            }
        } else {
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function deleteDoDetail($id){
        General_do_detail::where('id', $id)->delete();
        return redirect()->back();
    }

    public function deleteDO($id){
        $do = General_do::find($id);
        $detail = General_do_detail::where('do_id', $id)->get();
        if (!empty($do->gr_no)) {
            foreach ($detail as $key => $value) {
                if ($value->type == "Transfer") {
                    $item  = Asset_item::where('item_code', $value->item_id)->first();
                    $wh = Asset_qty_wh::where('wh_id', $do->from_id)
                        ->where('item_id', $item->id)
                        ->first();
                    $towh = Asset_qty_wh::where('wh_id', $do->to_id)
                        ->where('item_id', $item->id)
                        ->first();
                    $newqty = $towh->qty - $value->qty;
                    $qty = $wh->qty + $value->qty;

                    Asset_qty_wh::where('wh_id', $do->from_id)
                        ->where('item_id', $item->id)
                        ->update([
                            'qty' => $qty
                        ]);
                    Asset_qty_wh::where('wh_id', $do->to_id)
                        ->where('item_id', $item->id)
                        ->update([
                            'qty' => $newqty
                        ]);
                }
            }
        }
        General_do::where('id', $id)->delete();
        General_do_detail::where('do_id',$id)->delete();

        $ppe = Hrd_employee_ppe::where('do_id', $id)->first();
        if(!empty($ppe)){
            Hrd_employee_ppe::where('do_id', $id)
                ->update([
                    "do_id" => null,
                    "ppe_index" => null
                ]);
        }
        return redirect()->route('do.index');
    }

    public function getDO($type=null,$id){
        $wh = Asset_wh::all();
        $do = General_do::leftJoin('asset_wh AS from','from.id','=','do.from_id')
            ->join('asset_wh AS to','to.id','=','do.to_id')
//            ->join('marketing_projects AS prj','prj.id','=','do.project')
            ->select('from.name AS whFromName','to.name AS whToName','do.*',DB::raw('(SELECT COUNT(do_id) FROM do_detail WHERE do_id = do.id) AS items'))
            ->where('do.id', $id)
            ->first();

        $do_detail = General_do_detail::join('asset_items as items','items.item_code','=','do_detail.item_id')
            ->select('do_detail.*','items.name as itemName','items.uom as itemUom')
            ->where('do_detail.do_id', $id)
            ->whereNull('items.deleted_at')
            ->get();

        $do_detail_item = $do_detail->pluck('item_id');

        $divisions = Division::all();

        $file_driver = null;
        if(!empty($do->departure_file)){
            $file_ = File_Management::where("hash_code", $do->departure_file)->first();
            if(!empty($file_)){
                $file_driver = $file_->file_name;
            } else {
                $file_driver = "media/do/".$do->departure_file;
            }
        }

        $view = 'do.detail';

        if($type == "view"){
            $view = 'do.view_do';
        }

        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }

        $item_wh = [];
        if(!empty($do->from_id)){
            $item_wh = Asset_qty_wh::where("wh_id", $do->from_id)->get()->pluck('item_id');
        }

        $items = Asset_item::whereIn('company_id', $id_companies)
            ->whereNull('uom2')
            ->whereIn('id', $item_wh)
            ->whereNotIn('item_code', $do_detail_item)
            ->get();

        return view($view,[
            'do' => $do,
            'do_detail' => $do_detail,
            'type' => $type,
            'wh' => $wh,
            'divisions' => $divisions,
            'file_driver' => $file_driver,
            'items' => $items
        ]);
    }

    public function do_dispatch($id){
        $wh = Asset_wh::where('company_id',Session::get('company_id'))->get();
        $do = General_do::join('asset_wh AS from','from.id','=','do.from_id')
            ->join('asset_wh AS to','to.id','=','do.to_id')
            ->select('from.name AS whFromName','to.name AS whToName','do.*',DB::raw('(SELECT COUNT(do_id) FROM do_detail WHERE do_id = do.id) AS items'))
            ->where('do.id', $id)->first();

        $do_detail = General_do_detail::join('asset_items as items','items.item_code','=','do_detail.item_id')
            ->select('do_detail.*','items.name as itemName','items.uom as itemUom')
            ->where('do_detail.do_id', $id)
            ->get();

        return view('do.dispatch',[
            'do' => $do,
            'do_detail' => $do_detail,
            'wh' => $wh,
        ]);
    }

    function capture(Request $request){
        $st = "";
        if ($request->submit == "dispatch") {
            $file = $request->file('departure_file');
            $filename = explode(".", $file->getClientOriginalName());
            array_pop($filename);
            $filename = str_replace(" ", "_", implode("_", $filename));

            $newfile = $filename."-".date('Y_m_d_H_i_s')."(".$request->id.").".$file->getClientOriginalExtension();
            $hashfile = Hash::make($newfile);
            $hashfile = str_replace("/", "", $hashfile);
            $upload = FileManagement::save_file_management($hashfile, $file, $newfile, "driver/");
            if ($upload == 1){
                $capture = General_do::find($request->id_do);
                $capture->departure_at = date("Y-m-d H:i:s");
                $capture->departure_file = $hashfile;
                $capture->departure_by = Auth::user()->username;
                $capture->save();
            }
            $st = "dispatch";
        } else {
            $do = General_do::where('id',$request['id_do'])->first();
            $whfrom = $do->from_id;
            $whto = $do->to_id;


            $listItems = General_do_detail::where('do_id', $request['id_do'])->get();
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

            General_do::where('id', $request['id_do'])
                ->update([
                    'approved_by' => Auth::user()->username,
                    'approved_time' => date('Y-m-d'),
                    'arrive_by' => Auth::user()->username,
                    'arrive_at' => date('Y-m-d'),
                    'gr_no' => Auth::user()->username,
                ]);

            $pre = Asset_pre::where('do_id', $request['id_do'])->first();
            if(!empty($pre)){
                $pre->fr_deliver_times = $request['delivery_time'];
                $pre->save();
            }
            $st = "receive";
        }
        return redirect()->route('do.redirect', ["type" => $st, "id" => $request->id_do]);
    }

    public function viewPrint($id,$type=null){
        $wh = Asset_wh::all();
        $do = General_do::join('asset_wh AS from','from.id','=','do.from_id')
            ->join('asset_wh AS to','to.id','=','do.to_id')
//            ->join('marketing_projects AS prj','prj.id','=','do.project')
            ->select('from.name AS whFromName','from.address AS whFromAddress','from.telephone AS whFromTelp','to.name AS whToName','to.address AS whToAddress','to.telephone AS whToTelp','to.name AS whToName','do.*',DB::raw('(SELECT COUNT(do_id) FROM do_detail WHERE do_id = do.id) AS items'))
            ->where('do.id', $id)
            ->first();

        $company = ConfigCompany::find($do->company_id);

        $do_detail = General_do_detail::join('asset_items as items','items.item_code','=','do_detail.item_id')
            ->select('do_detail.*','items.name as itemName','items.uom as itemUom','items.specification as specification')
            ->where('do_detail.do_id', $id)
            ->whereNull('items.deleted_at')
            ->get();

        $divisions = Division::all();



//        dd($do_detail);

        $view = "do.print";
        if($type == "matrix"){
            $view = "do.print_matrix";
        }

        return view($view,[
            'do' => $do,
            'do_detail' => $do_detail,
            'type' => $type,
            'wh' => $wh,
            'divisions' => $divisions,
            'company' => $company
        ]);
    }

    public function updateGR(Request $request){
        General_do::where('id', $request['id'])
            ->update([
                'gr_no' => $request['receive_by'],
            ]);
        return redirect()->route('do.index');
    }
    public function update(Request $request){
        if ($request['type'] == 'appr' || $request['type'] == "receive"){
            $do = General_do::where('id',$request['id_do'])->first();
            $whfrom = $do->from_id;
            $whto = $do->to_id;


            $listItems = General_do_detail::where('do_id', $request['id_do'])->get();
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

            General_do::where('id', $request['id_do'])
                ->update([
                    'from_id' => $request['from'],
                    'to_id' => $request['to'],
                    'division' => $request['division'],
                    'deliver_date' => $request['delivery_time'],
                    'notes' => $request['notes'],
                    'approved_by' => Auth::user()->username,
                    'approved_time' => date('Y-m-d'),
                    'arrive_by' => Auth::user()->username,
                    'arrive_at' => date('Y-m-d'),
                    'gr_no' => Auth::user()->username,
                ]);

            $pre = Asset_pre::where('do_id', $request['id_do'])->first();
            if(!empty($pre)){
                $pre->fr_deliver_times = $request['delivery_time'];
                $pre->save();
            }
        } else {
            General_do::where('id', $request['id_do'])
                ->update([
                    'from_id' => $request['from'],
                    'to_id' => $request['to'],
                    'division' => $request['division'],
                    'deliver_date' => $request['delivery_time'],
                    'notes' => $request['notes'],
                ]);
        }

        if (isset($request->rpage)) {
            return redirect()->route('do.redirect', ["type" => 'receive', 'id' => $request->id_do]);
        }
        return redirect()->route('do.index');
    }

    function check_item (Request $request){
        $qtywh = Asset_qty_wh::where('wh_id', $request->wh_id)
            ->where('item_id', $request->item)
            ->first();

        if (!empty($qtywh)){
            $data['item'] = $request->item;
            $data['qty'] = $qtywh->qty;
        } else {
            $data['item'] = null;
        }

        return json_encode($data);
    }

    function redirect_page($type, $id){
        $do = General_do::find($id);
        return view("do.redirect_page", compact("type", "do"));
    }

    public function add_item(Request $request){
        $item = Asset_item::find($request->item_id);

        $detail = new General_do_detail();
        $detail->do_id = $request->id_do;
        $detail->item_id = $item->item_code;
        $detail->qty = $request->qty;
        $detail->type = $request->type;
        $detail->save();

        return redirect()->back();
    }
}
