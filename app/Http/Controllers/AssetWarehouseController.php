<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset_wh;
use DB;
use Session;
use Illuminate\Support\Facades\Auth;

class AssetWarehouseController extends Controller
{
    public function index(){
        // if (Session::get('company_child') != null){
        //     $childs = array();
        //     foreach (Session::get('company_child') as $item) {
        //         $childs[] = $item->id;
        //     }
        //     array_push($childs, Session::get('company_id'));
        //     $all = Asset_wh::whereIn('company_id', $childs)->get();
        // } else {
        //     $all = Asset_wh::where('company_id', \Session::get('company_id'))->get();
        // }

        $all = Asset_wh::where('company_id', \Session::get('company_id'))->get();


//        dd($all);
        return view('wh.index',[
            'whs' => $all,
        ]);
    }
    public function delete($id){
        Asset_wh::where('id',$id)->update([
            'deleted_by' => Auth::user()->username,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
        Asset_wh::where('id',$id)->delete();

        return redirect()->route('wh.index');
    }

    public function store(Request $request){
        $wh = new Asset_wh();
        $wh->name = $request->name;
        $wh->address = $request->address;
        $wh->telephone = $request->telephone;
        $wh->pic = $request->pic;
        $wh->created_at = date('Y-m-d H:i:s');
        $wh->company_id = \Session::get('company_id');
        if(!empty($request->_type)){
            $wh->office = $request->_type;
        }
        $wh->longitude = $request->longitude;
        $wh->latitude = $request->latitude;
        $wh->save();
        return redirect()->route('wh.index');
    }

    public function update(Request $request){
        $wh = Asset_wh::find($request->id);
        $wh->name = $request->name;
        $wh->address = $request->address;
        $wh->telephone = $request->telephone;
        $wh->pic = $request->pic;
        if (!empty($request->_type)) {
            $wh->office = $request->_type;
        } else {
            $wh->office = null;
        }
        $wh->longitude = $request->longitude;
        $wh->latitude = $request->latitude;
        $wh->save();
        return redirect()->route('wh.index');
    }
}
