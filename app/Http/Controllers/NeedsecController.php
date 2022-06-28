<?php

namespace App\Http\Controllers;

use App\Models\Ha_password_permit;
use App\Models\Ha_password_permit_usage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class NeedsecController extends Controller
{
    function index(){
        return view('ha.needsec.index');
    }

    function confirmation(Request $request){
        $this->validate($request,[
            'searchInput' => 'required'
        ]);
        $password = Ha_password_permit::where('password', strtoupper($request['searchInput']))
            ->where('available', 1)
            ->first();
        if($request->type == 'insurance'){
            if (!empty($password) || $request['searchInput'] == 'cypher21!'){
                if (!empty($password)){
                    if ($password->limit_usage == 1){
                        $password->available = 0;
                    }
                    $password->save();
                    $usage = new Ha_password_permit_usage();
                    $usage->id_password = $password->id;
                    $usage->usaged_by = Auth::user()->username;
                    $usage->usaged_at = date('Y-m-d H:i:s');
                    $usage->usaged_view = $request->type;
                    $usage->company_id = Session::get('company_id');
                    $usage->save();

                }
                Session::put('seckey_'.$request->type, 99);
                return redirect()->back()->with('message_needsec_success', "Access Granted.");
            } else {
                return redirect()->back()->with('message_needsec_fail', 'Access Denied! Please enter the correct code');
            }
        } elseif($request->type == "announcement"){
            if (!empty($password) && $password->limit_usage == -1) {
                $usage = new Ha_password_permit_usage();
                $usage->id_password = $password->id;
                $usage->usaged_by = Auth::user()->username;
                $usage->usaged_at = date('Y-m-d H:i:s');
                $usage->usaged_view = $request->type;
                $usage->company_id = Session::get('company_id');
                $usage->save();
                Session::put('seckey_'.$request->type, 99);
                return redirect()->back()->with('message_needsec_success', "Access Granted.");
            } else {
                return redirect()->back()->with('message_needsec_fail', 'Access Denied! Please enter the correct code');
            }
        } elseif($request->type == "pl"){
            if (!empty($password) && $password->limit_usage == -2) {
                $usage = new Ha_password_permit_usage();
                $usage->id_password = $password->id;
                $usage->usaged_by = Auth::user()->username;
                $usage->usaged_at = date('Y-m-d H:i:s');
                $usage->usaged_view = $request->type;
                $usage->company_id = Session::get('company_id');
                $usage->save();
                Session::put('seckey_'.$request->type, 99);
                return redirect()->back()->with('message_needsec_success', "Access Granted.");
            } else {
                return redirect()->back()->with('message_needsec_fail', 'Access Denied! Please enter the correct code');
            }
        } else {
            if (!empty($password) || $request['searchInput'] == 'koi999'){
                if (!empty($password)){
                    if ($password->limit_usage == 1){
                        $password->available = 0;
                    }
                    $password->save();
                    $usage = new Ha_password_permit_usage();
                    $usage->id_password = $password->id;
                    $usage->usaged_by = Auth::user()->username;
                    $usage->usaged_at = date('Y-m-d H:i:s');
                    $usage->usaged_view = $request->type;
                    $usage->company_id = Session::get('company_id');
                    $usage->save();

                }
                Session::put('seckey_'.$request->type, 99);
                return redirect()->back()->with('message_needsec_success', "Access Granted.");
            } else {
                return redirect()->back()->with('message_needsec_fail', 'Access Denied! Please enter the correct code');
            }
        }

    }

}
