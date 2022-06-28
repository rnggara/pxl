<?php

namespace App\Http\Controllers;

use App\Models\Ha_password_permit;
use App\Models\Ha_password_permit_usage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class HAPasswordController extends Controller
{
    function index(){
        $passwords = Ha_password_permit::where('company_id', Session::get('company_id'))->get();
        $pass = array();
        $pass_id = [];
        foreach ($passwords as $password){
            $pass[$password->id] = $password;
            $pass_id[] = $password->id;
        }
        $detail = Ha_password_permit_usage::where('company_id', Session::get('company_id'))->get();
        return view('ha.passwords.index', [
            "passwords" => $passwords,
            "used" => $detail,
            "detail" => $pass
        ]);
    }

    function create(Request $request){
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $password = substr(str_shuffle($permitted_chars), 0, 6);

        $isExist = Ha_password_permit::where('password', $password)->first();
        if (!empty($isExist)){
            $postPassword = substr(str_shuffle($permitted_chars), 0, 6);
        } else {
            $postPassword = $password;
        }
        $management = new Ha_password_permit();
        $management->password = strtoupper($postPassword);
        $management->purposes = $request->purpose;
        $management->limit_usage = $request->usage;
        $management->available = 1;
        $management->created_by = Auth::user()->username;
        $management->company_id = Session::get('company_id');

        $management->save();

        return redirect()->back();
    }

    function delete($id){
        Ha_password_permit::find($id)->delete();
        Ha_password_permit_usage::where('id_password', $id)->delete();
        return redirect()->back();
    }
}
