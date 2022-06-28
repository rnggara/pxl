<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asset_wh;
use App\Models\Division;
use App\Models\Asset_item;
use App\Models\General_do;
use App\Models\Asset_qty_wh;
use App\Models\Driver_Model;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Helpers\FileManagement;
use App\Models\File_Management;
use App\Models\General_do_detail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use SebastianBergmann\CodeCoverage\Driver\Driver;

class DriverController extends Controller
{
    function index(Request $request){

        $comp = [];

        if(isset($request->t)){
            $comp = ConfigCompany::where('tag', $request->t)->first();
        }

        return view('driver.add', compact('comp'));
    }
   function add(Request $request){
       $file = $request->file('file_upload');
       $filename = explode(".", $file->getClientOriginalName());
       array_pop($filename);
       $filename = str_replace(" ", "_", implode("_", $filename));

       $newfile = $filename."-".date('Y_m_d_H_i_s')."(".$request->id.").".$file->getClientOriginalExtension();
       $hashfile = Hash::make($newfile);
       $hashfile = str_replace("/", "", $hashfile);
       $upload = FileManagement::save_file_management($hashfile, $file, $newfile, "driver");
       if ($upload == 1){
           $driver = new Driver_Model();
           $name = $request->namadepan;
           $name .= (!empty($request->namatengah)) ? " $request->namatengah" : "";
           $name .= (!empty($request->namabelakang)) ? " $request->namabelakang" : "";
           $driver->full_name = ucwords($name);
           $driver->nopol_kendaraan = strtoupper($request->nopol_kendaraan);
           $driver->jenis_kendaraan = $request->jenis_kendaraan;
           $driver->perusahaan = strtoupper($request->perusahaan);
           $driver->email = strtolower($request->email);
           $driver->no_telpon = $request->no_telpon;
           $driver->no_wa = $request->no_wa;
           $driver->no_id = $request->no_id;
           $driver->no_sim = $request->no_sim;
           $driver->file_upload = $hashfile;
           $driver->company_id = $request->comp_id;
           $driver->save();
       }
       return redirect()->back()->with('success', 'Terimakasih, Pendaftaran Anda sudah kami terima.');
   }

    function list_drivers(Request $request){
        $bank = false;
        $whereDo = " approved_time is null";
        if(isset($request->view) && $request->view == "bank"){
            $bank = true;
            $whereDo = " approved_time is not null";
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

        $divisions = Division::all();
        $do_data = General_do::whereNotNull('driver_id')
            ->whereRaw($whereDo)
            ->get();
        $do = $do_data->pluck('no_do', 'driver_id');
        $do_dispatch = $do_data->pluck('departure_at', 'driver_id');
        $do_id = $do_data->pluck('id', 'driver_id');
        $do_received = $do_data->pluck('approved_time', 'driver_id');
        $do_receiver = $do_data->pluck('arrive_by', 'driver_id');
        $do_approver = $do_data->pluck('approved_by', 'driver_id');
        $doAssign = General_do::whereNull('driver_id')
            ->whereIn('company_id', $id_companies)
            ->whereRaw('(departure_at is null or approved_time is null)')
            ->orderBy('id', 'desc')
            ->get();
        $file = File_Management::all()->pluck('file_name', 'hash_code');
        // $drivers = Driver_Model::orderBy('id', 'desc')->get();
        $do_bank = General_do::whereNotNull('driver_id')
            ->whereNotNull('approved_time')
            ->get()->pluck('driver_id');
        if($bank){
            $dodriver = $do_data->pluck('driver_id');
            $drivers = Driver_Model::whereIn('id', $dodriver)->orderBy('id', 'desc')->get();
        } else {
            $drivers = Driver_Model::whereNotIn('id', $do_bank)->orderBy('id', 'desc')->get();
        }

        $driver_checkout = Driver_Model::where('checkout', 1)->first();

        $user_device = User::whereNotNull('dispatch_name')->get();

        return view('driver.index', compact('drivers', 'user_device', 'driver_checkout', 'bank', 'divisions', 'do', 'doAssign', 'do_dispatch', 'do_received', 'file', 'do_id', 'do_receiver', 'do_approver'));
    }

    function update_status($id){
        $do = General_do::where("driver_id", $id)->first();
        if(empty($do->departure_at)){
            $do->departure_at = date("Y-m-d H:i:s");
            $do->departure_by = Auth::user()->username;
        } else {
            $do->arrive_at = date("Y-m-d H:i:s");
            $do->arrive_by = Auth::user()->username;
            $do->approved_by = Auth::user()->username;
            $do->approved_time = date("Y-m-d");

            $whfrom = $do->from_id;
            $whto = $do->to_id;


            $listItems = General_do_detail::where('do_id', $do->id)->get();
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
                    }
                }
            }
        }
        $do->save();

        return redirect()->back();
    }

    function checkout (Request $request){
        $id = $request->id_driver_checkout;
        try {
            Driver_Model::where('id', '!=', $id)
                ->update([
                    "checkout" => null
                ]);
            $driver = Driver_Model::find($id);
            if(empty($driver->checkout)){
                $driver->checkout = 1;
                $driver->checkout_at = date("Y-m-d H:i:s");
                $driver->checkout_by = Auth::user()->username;
                $driver->dispatch_name = $request->user_device;
            } else {
                $driver->checkout = null;
                $driver->checkout_at = null;
                $driver->checkout_by = null;
                $driver->dispatch_name = null;
            }
            $driver->save();
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }

        return redirect()->back();
    }

   function assign_do(Request $request){
       $do = General_do::find($request->do_id);
       $do->driver_id = $request->id_driver;
       $do->save();

       return redirect()->back();
   }

   function delete($id){
       if(Driver_Model::find($id)->delete()){
           return redirect()->back();
       }
   }

    function checkout_driver($id, Request $request){
        $items = Asset_item::all()->pluck('name', 'item_code');
        $items_uom = Asset_item::all()->pluck('uom', 'item_code');
        $do = General_do::where('driver_id', $id)->orderBy('id', 'desc')->first();
        $detail = [];
        if(!empty($do)){
            $do->whFrom = Asset_wh::find($do->from_id)->name;
            $do->whTo = Asset_wh::find($do->to_id)->name;
            $detail = General_do_detail::where('do_id', $do->id)->get();
            if(!empty($detail)){
                foreach ($detail as $key => $value) {
                    $value->itemName = (isset($items[$value->item_id])) ? $items[$value->item_id] : $value->item_id;
                    $value->itemUom = (isset($items_uom[$value->item_id])) ? $items_uom[$value->item_id] : null;
                }
            }
        }
        $countdo = (empty($do)) ? 0 : 1;
        return view('driver.checkout', compact("do", "countdo", "detail"));
    }

    function checkout_post(Request $request){
        $do = General_do::find($request->id_do);
        $driver = Driver_Model::find($request->id_driver);

        $success = false;

        if(!empty($do)){
            $do->departure_at = date("Y-m-d H:i:s");
            $do->departure_by = "ip : $request->ip_address <br> device : $request->os_name <br> browser : $request->browser_name";
            $do->save();
            $success = true;
        } else {
            $success = false;
        }

        if(!empty($driver)){
            $driver->checkout = null;
            $driver->save();
            $success = true;
        } else {
            $success = false;
        }

        if($success){
            return redirect()->route('driver.checkout_success')->with('success', true);
        } else {
            return redirect()->route('driver.checkout_success')->with('success', false);
        }
    }

    function checkout_success(){
        return view('driver.success');
    }

    function remove_do($id){
        $do = General_do::where("driver_id", $id)->first();

        if(!empty($do)){
            $do->driver_id = null;
            $do->save();
        }

        return redirect()->back();
    }

    function view_do($id){
        $wh = Asset_wh::all();
        $do = General_do::join('asset_wh AS from','from.id','=','do.from_id')
            ->join('asset_wh AS to','to.id','=','do.to_id')
            ->select('from.name AS whFromName','to.name AS whToName','do.*',DB::raw('(SELECT COUNT(do_id) FROM do_detail WHERE do_id = do.id) AS items'))
            ->where('do.id', $id)
            ->first();

        $do_detail = General_do_detail::join('asset_items as items','items.item_code','=','do_detail.item_id')
            ->select('do_detail.*','items.name as itemName','items.uom as itemUom')
            ->where('do_detail.do_id', $id)
            ->whereNull('items.deleted_at')
            ->get();

        $divisions = Division::all();

        return view('driver.view_do',[
            'do' => $do,
            'do_detail' => $do_detail,
            'wh' => $wh,
            'divisions' => $divisions
        ]);
    }
}
