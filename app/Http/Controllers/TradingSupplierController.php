<?php

namespace App\Http\Controllers;

use App\Helpers\FileManagement;
use App\Models\Asset_product_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Trading_supplier;
use Illuminate\Support\Facades\Hash;
use Session;
use DB;

class TradingSupplierController extends Controller
{
    public function index($id=null){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $product_type = Asset_product_type::all();
        $supplier = DB::table('trading_supplier')
            ->select('trading_supplier.*', 'pro_type.type_name as type')
            ->join('product_type as pro_type','pro_type.id','=','trading_supplier.id_product_type')
            ->whereIn('trading_supplier.company_id', $id_companies)
            ->whereNull('trading_supplier.deleted_at')
            ->get();

//        dd($supplier);
        return view('trading.supplier.index',[
            'product_type' => $product_type,
            'supplier' => $supplier,
        ]);
    }

    public function uploadNDA(Request $request){
//        dd($request);
        $supplier = Trading_supplier::where('id', $request['id'])->first();
        if ($request->file('file_draft')){
            $file = $request->file('file_draft');

            $newFile = $supplier->id.'_'.$supplier->name."-nda.".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/trading_supplier_attach");
            if ($upload == 1){
                Trading_supplier::where('id',$supplier->id)
                    ->update([
                        'nda_file' =>$hashFile,
                    ]);
            }
        }

        return redirect()->route('trading.supplier.index');
    }

    public function edit($id){
        $product_type = Asset_product_type::all();
        $supplier = DB::table('trading_supplier')
            ->select('trading_supplier.*', 'pro_type.type_name as type')
            ->join('product_type as pro_type','pro_type.id','=','trading_supplier.id_product_type')
            ->where('trading_supplier.id',$id)
            ->whereNull('trading_supplier.deleted_at')
            ->first();

        return view('trading.supplier.edit',[
            'product_type' => $product_type,
            'supplier' => $supplier,
        ]);
    }

    public function updateSupplier(Request $request){
        Trading_supplier::where('id',$request['id'])
            ->update([
                'name' => $request['name'],
                'telephone' => $request['phone'],
                'fax' => $request['fax'],
                'web' => $request['web'],
                'bank_acct' => $request['bank_acct'],
                'pic' => $request['pic_name'],
                'pic_email' => $request['pic_mail'],
                'id_product_type' => $request['product_type'],
                'rating' => $request['ratingInput'],
                'company_id' => \Session::get('company_id'),
                'address' => $request['address'],
                'updated_by' => Auth::user()->username,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return redirect()->route('trading.supplier.index');
    }

    public function storeSupplier(Request $request){
        $supplier = new Trading_supplier();
        $supplier->name = $request['name'];
        $supplier->telephone = $request['phone'];
        $supplier->fax = $request['fax'];
        $supplier->web = $request['web'];
        $supplier->bank_acct = $request['bank_acct'];
        $supplier->pic = $request['pic_name'];
        $supplier->pic_email = $request['pic_mail'];
        $supplier->id_product_type = $request['product_type'];
        $supplier->rating = $request['ratingInput'];
        $supplier->company_id = \Session::get('company_id');
        $supplier->address = $request['address'];
        $supplier->created_by = Auth::user()->username;
        $supplier->save();

        return redirect()->route('trading.supplier.index');

    }

    public function delete($id){
        Trading_supplier::where('id',$id)->update([
            'deleted_by' => Auth::user()->username
        ]);
        Trading_supplier::where('id',$id)->delete();

        return redirect()->route('trading.supplier.index');

    }
}
