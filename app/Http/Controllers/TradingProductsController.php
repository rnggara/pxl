<?php

namespace App\Http\Controllers;

use App\Helpers\FileManagement;
use App\Models\Trading_products;
use App\Models\Trading_supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;

class TradingProductsController extends Controller
{
    function index(){
        $suppliers = Trading_supplier::where('company_id', Session::get('company_id'))->get();
        $data_supplier = array();
        foreach ($suppliers as $item){
            $data_supplier[$item->id] = $item;
        }
        $products = Trading_products::where('company_id', Session::get('company_id'))->get();
        return view('trading.products.index', [
            'suppliers' => $suppliers,
            'producst' => $products,
            'data_supplier' => $data_supplier
        ]);
    }

    public function update(Request $request){
//        dd($request);

        $product = Trading_products::find($request['id']);
        if (isset($request['update_picture'])){
            if ($request->file('picture')){
                $file = $request->file('picture');
                $filename = explode(".", $file->getClientOriginalName());
                array_pop($filename);
                $filename = str_replace(" ", "_", implode("_", $filename));
                $newFile = "(product)".$filename."-".date('Y_m_d_H_i_s').".".$file->getClientOriginalExtension();
                $hashFile = Hash::make($newFile);
                $hashFile = str_replace("/", "", $hashFile);

                $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\\trading\products");
                if ($upload == 1){
                    $product->picture = $hashFile;
                }
            }
        } elseif (isset($request['update_sample'])) {
            if ($request->file('sample')){
                $file = $request->file('sample');
                $filename = explode(".", $file->getClientOriginalName());
                array_pop($filename);
                $filename = str_replace(" ", "_", implode("_", $filename));
                $newFile = "(product)".$filename."-".date('Y_m_d_H_i_s').".".$file->getClientOriginalExtension();
                $hashFile = Hash::make($newFile);
                $hashFile = str_replace("/", "", $hashFile);

                $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\\trading\products");
                if ($upload == 1){
                    $product->sample = $hashFile;
                }
            }
        } elseif (isset($request['update_lab'])){
            if ($request->file('lab')){
                $file = $request->file('lab');
                $filename = explode(".", $file->getClientOriginalName());
                array_pop($filename);
                $filename = str_replace(" ", "_", implode("_", $filename));
                $newFile = "(product)".$filename."-".date('Y_m_d_H_i_s').".".$file->getClientOriginalExtension();
                $hashFile = Hash::make($newFile);
                $hashFile = str_replace("/", "", $hashFile);

                $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\\trading\products");
                if ($upload == 1){
                    $product->lab = $hashFile;
                }
            }
        } elseif (isset($request['update_survey'])){
            if ($request->file('survey')){
                $file = $request->file('survey');
                $filename = explode(".", $file->getClientOriginalName());
                array_pop($filename);
                $filename = str_replace(" ", "_", implode("_", $filename));
                $newFile = "(product)".$filename."-".date('Y_m_d_H_i_s').".".$file->getClientOriginalExtension();
                $hashFile = Hash::make($newFile);
                $hashFile = str_replace("/", "", $hashFile);

                $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\\trading\products");
                if ($upload == 1){
                    $product->survey = $hashFile;
                }
            }
        } else {
            $product->name = $request->product_name;
            $product->supplier = $request->supplier;
            $product->serial_number = $request->serial_number;
            $product->uom = $request->uom;
            $product->notes = $request->notes;
        }
        $product->save();

        return redirect()->route('trading.products.index');

    }

    function detail($id){
        $suppliers = Trading_supplier::where('company_id', Session::get('company_id'))->get();
        $product = Trading_products::find($id);

        return view('trading.products.edit', [
            'product' => $product,
            'suppliers' => $suppliers
        ]);
    }

    function add(Request $request){
//        dd($request);
        $nProducts = new Trading_products();
        $nProducts->name = $request->product_name;
        $nProducts->supplier = $request->supplier;
        $nProducts->serial_number = $request->serial_number;
        $nProducts->uom = $request->uom;
        $nProducts->notes = $request->notes;
        $nProducts->company_id = Session::get('company_id');

        $file = $request->file('pict');
        $upload_sample = $request->file('upload_sample');
        $upload_lab = $request->file('upload_lab');
        $upload_survey = $request->file('upload_survey');
        if (!empty($file)){
           $upload = $this->upload_file($file);
           $nProducts->picture = $upload;
        }

        if (!empty($upload_sample)){
            $upload = $this->upload_file($upload_sample);
            $nProducts->sample = $upload;
        }

        if (!empty($upload_lab)){
            $upload = $this->upload_file($upload_lab);
            $nProducts->lab = $upload;
        }

        if (!empty($upload_survey)){
            $upload = $this->upload_file($upload_survey);
            $nProducts->survey = $upload;
        }

        $nProducts->save();
        return redirect()->back();
    }

    function autocomplete($supplier){
        $products = Trading_products::where('supplier', $supplier)->get();
        $return_arr =[];
        foreach ($products as $key => $item){
            $row_array['item_category'] = $item->category_id;
            $row_array['item_id'] = $item->id;
            $row_array['item_name'] = $item->name;
            $row_array['item_code'] = $item->serial_number;
            $row_array['item_uom'] = trim($item->uom);

            $row_array['value'] = $item->serial_number." / ".$item->name." (".trim($item->uom).")";

            array_push($return_arr, $row_array);
        }
        echo json_encode($return_arr);
    }

    function upload_file($input){
        $filename = explode(".", $input->getClientOriginalName());
        array_pop($filename);
        $filename = str_replace(" ", "_", implode("_", $filename));

        $newFile = "(product)".$filename."-".date('Y_m_d_H_i_s').".".$input->getClientOriginalExtension();
        $hashFile = Hash::make($newFile);
        $hashFile = str_replace("/", "", $hashFile);
        $upload = FileManagement::save_file_management($hashFile, $input, $newFile, "media\\trading\products");
        return $hashFile;
    }
}
