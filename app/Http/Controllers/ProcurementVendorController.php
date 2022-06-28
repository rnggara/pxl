<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Procurement_vendor;
use App\Models\Asset_product_type;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;

class ProcurementVendorController extends Controller
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
        $vendor = DB::table('asset_organization')
            ->select('asset_organization.*', 'pro_type.type_name as type')
            ->leftJoin('product_type as pro_type','pro_type.id','=','asset_organization.id_product_type')
            ->whereIn('asset_organization.company_id', $id_companies)
            ->whereNull('asset_organization.deleted_at')
            ->orderBy('asset_organization.name')
            ->get();

//        dd($vendor);
        return view('vendor.index',[
            'product_type' => $product_type,
            'vendor' => $vendor,
        ]);
    }

    public function edit($id){
        $product_type = Asset_product_type::all();
        $vendor = DB::table('asset_organization')
            ->select('asset_organization.*', 'asset_organization.id as id_vendor', 'pro_type.type_name as type')
            ->leftJoin('product_type as pro_type','pro_type.id','=','asset_organization.id_product_type')
            ->where('asset_organization.id',$id)
            ->whereNull('asset_organization.deleted_at')
            ->first();

        return view('vendor.edit',[
            'product_type' => $product_type,
            'vendor' => $vendor,
        ]);
    }

    public function updateVendor(Request $request){
        Procurement_vendor::where('id',$request['id'])
            ->update([
                'name' => $request['name'],
                'category' => $request['category'],
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

        return redirect()->route('vendor.index');
    }

    public function storeVendor(Request $request){
        $vendor = new Procurement_vendor();
        $vendor->category = $request['category'];
        $vendor->name = $request['name'];
        $vendor->telephone = $request['phone'];
        $vendor->fax = $request['fax'];
        $vendor->web = $request['web'];
        $vendor->bank_acct = $request['bank_acct'];
        $vendor->pic = $request['pic_name'];
        $vendor->pic_email = $request['pic_mail'];
        $vendor->id_product_type = $request['product_type'];
        $vendor->rating = $request['ratingInput'];
        $vendor->company_id = \Session::get('company_id');
        $vendor->address = $request['address'];
        $vendor->created_by = Auth::user()->username;
        $vendor->save();

        return redirect()->route('vendor.index');

    }

    public function delete($id){
        Procurement_vendor::where('id',$id)->update([
            'deleted_by' => Auth::user()->username
        ]);
        Procurement_vendor::where('id',$id)->delete();

        return redirect()->route('vendor.index');

    }
}
