<?php

namespace App\Http\Controllers;

use App\Helpers\FileManagement;
use App\Models\Trading_market;
use App\Models\Trading_orders;
use App\Models\Trading_orders_items;
use App\Models\Trading_products;
use App\Models\Trading_supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;

class TradingOrdersController extends Controller
{
    private $arrRomawi	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");

    function index(){
        $suppliers = Trading_supplier::where('company_id', Session::get('company_id'))->get();
        $clients = Trading_market::where('company_id', Session::get('company_id'))->get();
        $order = Trading_orders::where('company_id', Session::get('company_id'))->get();
        $items = Trading_orders_items::where('company_id', Session::get('company_id'))->get();
        $detail = array();
        foreach ($items as $item){
            $detail[$item->id_order][] = $item->qty * $item->price;
        }

        $data_supplier = array();
        foreach ($suppliers as $item){
            $data_supplier[$item->id] = $item;
        }

        $products = Trading_products::where('company_id', Session::get('company_id'))->get();

        $productName = [];
        $productUom = [];
        $productSn = [];
        foreach ($products as $product){
            $productName[$product->id] = $product->name;
            $productUom[$product->id] = $product->uom;
            $productSn[$product->id] = $product->serial_number;
        }

        $data_client = array();
        foreach ($clients as $item){
            $data_client[$item->id] = $item;
        }
        return view('trading.orders.index', [
            'suppliers' => $suppliers,
            'clients' => $clients,
            'orders' => $order,
            'details' => $detail,
            'data_supplier' => $data_supplier,
            'data_client' => $data_client,
            'productName' => $productName,
            'productUom' =>$productUom,
            'productSn' => $productSn,
            'products' => $products,
            'items' => $items
        ]);
    }

    function delete($id){
        Trading_orders::find($id)->delete();
        Trading_orders_items::where('id_order', $id)->delete();
        return redirect()->back();
    }

    function update(Request $request){
        $nOrder = Trading_orders::find($request['id']);
        $nOrder->supplier = $request->supplier;
        $nOrder->client = $request->client;
        $nOrder->description = $request->description;
        $nOrder->request_date = $request->request_date;
        $nOrder->due_date = $request->due_date;
        $nOrder->notes = $request->notes;
        $nOrder->save();

        return redirect()->route('trading.orders.index');
    }

    function add(Request $request){
//        dd($request);
        $iOrder = Trading_orders::where('company_id', Session::get('company_id'))
            ->where('no_order', 'like', '%'.Session::get('company_tag').'%')
            ->where('request_date', 'like', '2020%')
            ->orderBy('no_order', 'DESC')
            ->first();

        if (!empty($iOrder)){
            $last = explode("/", $iOrder->no_order);
            $newNum = intval($last[0]) + 1;
            $num = sprintf("%03d", $newNum)."/".Session::get('company_tag')."/ORDER/".$this->arrRomawi[date('n')]."/".date("y");
        } else {
            $num = "001/".Session::get('company_tag')."/ORDER/".$this->arrRomawi[date('n')]."/".date("y");
        }

        $name_mod = $request->name_mod;
        $val_mod = $request->val_mod;
        $row = array();
        if (!empty($name_mod)){
            foreach ($name_mod as $key => $name){
                $data = array();
                $data['name'] = $name;
                $data['amount'] = $val_mod[$key];
                $row[] = $data;
            }
        }

        $nOrder = new Trading_orders();
        $nOrder->no_order = $num;
        $nOrder->supplier = $request->supplier;
        $nOrder->client = $request->client;
        $nOrder->description = $request->description;
        $nOrder->request_date = $request->request_date;
        $nOrder->due_date = $request->due_date;
        $nOrder->notes = $request->notes;
        $nOrder->modifiers = json_encode($row);
        $nOrder->company_id = Session::get('company_id');

        $loi = $request->file("loi");
        if (!empty($loi)){
            $upload = $this->upload_file($loi, "Letter of intent");
            $nOrder->loi_file = $upload;
        }
        $pof = $request->file("pof");
        if (!empty($pof)){
            $upload = $this->upload_file($pof, "Letter of intent");
            $nOrder->pof_file = $upload;
        }

        if ($nOrder->save()){
            $id_item = $request->id_item;
            $qty = $request->qty;
            $price = $request->price;
            foreach ($id_item as $i => $item){
                $nItem = new Trading_orders_items();
                $nItem->id_order = $nOrder->id;
                $nItem->id_item = $item;
                $nItem->qty = $qty[$i];
                $nItem->price = $price[$i];
                $nItem->company_id = Session::get('company_id');
                $nItem->save();
            }
        }

        return redirect()->back();

    }

    function uploadFinal(Request $request){
//        dd($request);
        $order = Trading_orders::find($request->id_order);
        $order->allocation_fee = $request->fee;
        $mou = $request->file('mou');
        $commitment = $request->file('commitment');
        if (!empty($mou)){
            $upload = $this->upload_file($mou, "MoU");
            $order->mou = $upload;
        }

        if (!empty($commitment)){
            $upload = $this->upload_file($commitment, "Commitment");
            $order->commitment = $upload;
        }

        $order->save();
        return redirect()->back();
    }

    function upload_file($input, $type){
        $filename = explode(".", $input->getClientOriginalName());
        array_pop($filename);
        $filename = str_replace(" ", "_", implode("_", $filename));

        $newFile = "(".$type.")".$filename."-".date('Y_m_d_H_i_s').".".$input->getClientOriginalExtension();
        $hashFile = Hash::make($newFile);
        $hashFile = str_replace("/", "", $hashFile);
        $upload = FileManagement::save_file_management($hashFile, $input, $newFile, "media\\trading\orders");
        return $hashFile;
    }

    function find($id){
        $order = Trading_orders::find($id);
        $detail = Trading_orders_items::where('id_order', $id)->get();

        $data_supplier = Trading_supplier::where('company_id', Session::get('company_id'))->get();
        $data_clients = Trading_market::where('company_id', Session::get('company_id'))->get();
        $data_products = Trading_products::where('company_id', Session::get('company_id'))->get();

        $products = array();
        foreach ($data_products as $item){
            $products[$item->id] = $item;
        }

        $clients = array();
        foreach ($data_clients as $item){
            $clients[$item->id] = $item;
        }

        $suppliers = array();
        foreach ($data_supplier as $item){
            $suppliers[$item->id] = $item;
        }

        return view('trading.orders.print', compact('order', 'detail', 'clients', 'suppliers', 'products'));
    }
}
