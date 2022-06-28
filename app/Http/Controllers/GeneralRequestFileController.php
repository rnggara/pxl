<?php

namespace App\Http\Controllers;

use App\Models\File_Management;
use App\Models\File_request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class GeneralRequestFileController extends Controller
{
    function index(){
        $request = File_request::where('company_id', Session::get('company_id'))
            ->where('created_by', Auth::user()->username)
            ->get();

        $approval = File_request::where('company_id', Session::get('company_id'))
            ->where('own_approved_by', Auth::user()->username)
            ->get();

        $file = File_Management::all();
        $file_name = [];
        foreach ($file as $item){
            $fName = explode('/', str_replace("\\", "/", $item->file_name));
            $file_name[$item->hash_code] = end($fName);
        }

        return view('rf.index', [
            'requests' => $request,
            'approvals' => $approval,
            'file_name' => $file_name
        ]);
    }

    function find(Request $request){
        $hash = $request->req;

        $file = File_Management::where('hash_code', $hash)->first();
        if ($file){
            $filename = explode("/", str_replace("\\", "/", $file->file_name));
            $data['error'] = 0;
            $data['data'] = array(
                'file' => end($filename),
                'code' => $file->hash_code
            );
        } else {
            $data['error'] = 1;
            $data['data'] = array();
        }

        return json_encode($data);
    }

    function request(Request $request){
        $iReq = File_request::where('file_hash', $request->req)
            ->where('created_by', Auth::user()->username)
            ->get();

//        dd(count($iReq));

        if (count($iReq) > 0){
            $data['error'] = 2;
        } else {
            $file = File_Management::where('hash_code', $request->req)->first();

            $fRequest = new File_request();
            $fRequest->file_hash = $file->hash_code;
            $fRequest->created_by = Auth::user()->username;
            $fRequest->company_id = Session::get('company_id');
            $fRequest->own_approved_by = $file->created_by;

            if ($fRequest->save()){
                $data['error'] = 0;
            } else {
                $data['error'] = 1;
            }
        }

        return json_encode($data);
    }

    function delete($id){
        $del = File_request::find($id);

        if ($del->delete()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function approve(Request $request){
        $iFile = File_request::find($request->req);

        if ($request->appr == "own"){
            if ($iFile->own_approved_at == null){
                $iFile->own_approved_at = date('Y-m-d H:i:s');
            } else {
                $iFile->own_approved_at = null;
            }
        } else {
            $iFile->dir_approved_at = date('Y-m-d H:i:s');
            $iFile->dir_approved_by = Auth::user()->username;
        }

        if ($iFile->save()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }
}
