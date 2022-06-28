<?php

namespace App\Http\Controllers;

use App\Helpers\FileManagement;
use App\Models\Marketing_documents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;

class MarketingDocumentsController extends Controller
{
    function index(){
        return view('marketing_documents.index');
    }

    function add(Request $request){
        // dd($request);
        $file = $request->file('file_upload');
        $filename = $file->getClientOriginalName();
        // array_pop($filename);
        // $filename = str_replace(" ", "_", implode("_", $filename));
        // dd("media\asset\\resource_mkt");
        $newFile = $filename."-".date('Y_m_d_H_i_s').$file->getClientOriginalExtension();
        $hashFile = Hash::make($newFile);
        $hashFile = str_replace("/", "", $hashFile);
        $upload = FileManagement::save_file_management($hashFile, $file, $filename, "media\asset\\resource_mkt");
        if ($upload == 1){
            $document = new Marketing_documents();
            $document->prj_id = 0;
            $document->name = $filename;
            $document->category = 'private_marketing';
            $document->address = 'resource_mkt/'.$filename;
            $document->company_id = Session::get('company_id');
            $document->uploader = Auth::user()->username;
            $document->upload_time = date('Y-m-d');
            $document->save();
        }
        return redirect()->back();
    }

    function delete($id){
        $doc = Marketing_documents::find($id);
        if ($doc->delete()){
            $data['delete'] = 1;
        } else {
            $data['delete'] = 0;
        }

        return json_encode($data);
    }

    function list(Request $request){
        // if (!empty($request->filter)){
        //     $whereType = "category like '%private_marketing%'";
        // } else {
        //     $whereType = "1";
        // }
            $whereType = "category like '%private_marketing%'";


        $doc = Marketing_documents::where('company_id', Session::get('company_id'))
            ->whereRaw($whereType);

        $data = array();
        foreach ($doc->get() as $i => $item){
            $row = array();
            $row['i'] = $i+1;
            $row['name'] = $item['name'];
            // if (strtolower($item->file_type) == "document"){
            //     $bg = "label-primary";
            // } elseif (strtolower($item->file_type) == "presentation"){
            //     $bg = "label-warning";
            // } else {
            //     $bg = "label-info";
            // }
             $bg = "label-primary";
            $row['file_type'] = "<span class='label label-inline $bg'>$item->category</span>";
            $row['by'] = $item->uploader;
            $row['date'] = date('d F Y', strtotime($item->upload_time));
            $row['download'] = "<a href='".str_replace('download/','media/asset/',str_replace('public','public_html',route('download', $item->address)))."' target='_blank' class='btn btn-xs btn-icon btn-primary'><i class='fa fa-download'></i></a>";
            $row['delete'] = "<button type='button' onclick='delete_document($item->id)' class='btn btn-xs btn-danger btn-icon'><i class='fa fa-trash'></i></button>";
            $data[] = $row;
        }

        $return['data'] = $data;
        $return['query'] = $doc->toSql();

        return json_encode($return);
    }
}
