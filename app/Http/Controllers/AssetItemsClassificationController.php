<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset_item_classification;
use App\Models\Asset_new_category;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;

class AssetItemsClassificationController extends Controller
{

    public function getClassification($id,$class_id){
        $class = Asset_item_classification::where('id_category', $id)
            ->where('id', $class_id)
            ->get();
        $data = [];
        foreach ($class as $value){
            $data[] = array(
                "id" => $value->id,
                "text" => $value->classification_name.'/'.$value->classification_code
            );
        }
        return response()->json($data);
    }
    public function index($category=null){
        if ($category == null){
            $classification = Asset_item_classification::leftJoin('new_category as cat','cat.id','=','asset_items_classification.id_category')
                ->select('asset_items_classification.*','cat.name as catName')
                ->get();
            $categories = Asset_new_category::all();

        } else {
            $classification = Asset_item_classification::leftJoin('new_category as cat','cat.id','=','asset_items_classification.id_category')
                ->select('asset_items_classification.*','cat.name as catName')
                ->where('asset_items_classification.id_category', $category)
                ->get();
            $categories = Asset_new_category::where('id', $category)->get();

        }

//        dd($classification);
        return view('item_class.index',[
            'classifications' => $classification,
            'categories' => $categories,
            'cat_id' => $category,
        ]);

    }



    public function store(Request $request){
        $classification = new Asset_item_classification();
        $classification->id_category = $request->category;
        $classification->classification_name = $request->name;
        $classification->classification_code = $request->code;
        $classification->created_by = Auth::user()->username;
        $classification->created_at = date('Y-m-d H:i:s');
        $classification->company_id = \Session::Get('company_id');
        $classification->save();

        return redirect()->back();
    }

    public function update(Request $request){
        $classification = Asset_item_classification::find($request['id']);
        $classification->id_category = $request->category;
        $classification->classification_name = $request->name;
        $classification->classification_code = $request->code;
        $classification->updated_by = Auth::user()->username;
        $classification->updated_at = date('Y-m-d H:i:s');
        $classification->save();

        return redirect()->back();
    }

    public function delete($id){
        Asset_item_classification::find($id)->delete();
        return redirect()->back();
    }
}
