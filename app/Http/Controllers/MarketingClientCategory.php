<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\Models\Marketing_client_category;

class MarketingClientCategory extends Controller
{
    public function index(){
        $ccat = Marketing_client_category::where('company_id',\Session::get('company_id'))->get();

        return view('clients_category.index',[
            'client_categories' => $ccat,
        ]);
    }
    public function addCategory(Request $request){
        if (isset($request['edit'])){
            Marketing_client_category::where('id', $request['id'])
                ->update([
                    'name' => $request['name'],
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        } else {
            $ccat = new Marketing_client_category();
            $ccat->name = $request->name;
            $ccat->created_at = date('Y-m-d H:i:s');
            $ccat->company_id = \Session::get('company_id');
            $ccat->save();
        }


        return redirect()->route('cc.index');
    }

    public function delete($id){
        Marketing_client_category::where('id', $id)->delete();
        return redirect()->route('cc.index');

    }
}
