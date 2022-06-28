<?php

namespace App\Http\Controllers;

use App\Helpers\FileManagement;
use App\Models\General_documents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;

class GeneralDocumentsController extends Controller
{
    function index(){
        return view('documents.index');
    }

    function add(Request $request){
        $file = $request->file('file_upload');
        if(count($file) > 0){
            foreach($file as $i => $_file){
                $filename = explode(".", $_file->getClientOriginalName());
                array_pop($filename);
                $filename = str_replace(" ", "_", implode("_", $filename));

                $newFile = $filename."-".date('Y_m_d_H_i_s')."(".$request->id.").".$_file->getClientOriginalExtension();
                $hashFile = Hash::make($newFile);
                $hashFile = str_replace("/", "", $hashFile);
                $upload = FileManagement::save_file_management($hashFile, $_file, $newFile, "media\asset\documents");
                if ($upload == 1){
                    $document = new General_documents();
                    $document->name = $request->doc_name."#".($i+1);
                    $document->file_type = strtoupper($request->type);
                    $document->file_code = $hashFile;
                    $document->company_id = Session::get('company_id');
                    $document->created_by = Auth::user()->username;
                    $document->save();
                }
            }
        }

        return redirect()->back();
    }

    function delete($id){
        $doc = General_documents::find($id);
        if ($doc->delete()){
            $data['delete'] = 1;
        } else {
            $data['delete'] = 0;
        }

        return json_encode($data);
    }

    function list(Request $request){
        if (!empty($request->filter)){
            $whereType = "file_type like '%".strtoupper($request->filter)."%'";
        } else {
            $whereType = "1";
        }

        $doc = General_documents::where('company_id', Session::get('company_id'))
            ->whereRaw($whereType);

        $data = array();
        foreach ($doc->get() as $i => $item){
            $row = array();
            $row['i'] = $i+1;
            $row['name'] = $item['name'];
            if (strtolower($item->file_type) == "document"){
                $bg = "label-primary";
            } elseif (strtolower($item->file_type) == "presentation"){
                $bg = "label-warning";
            } else {
                $bg = "label-info";
            }
            $row['file_type'] = "<span class='label label-inline $bg'>$item->file_type</span>";
            $row['by'] = $item->created_by;
            $row['date'] = date('d F Y', strtotime($item->created_at));
            $row['download'] = "<a href='".route('download', $item->file_code)."' class='btn btn-xs btn-icon btn-primary'><i class='fa fa-download'></i></a>";
            $row['delete'] = "<button type='button' onclick='delete_document($item->id)' class='btn btn-xs btn-danger btn-icon'><i class='fa fa-trash'></i></button>";
            $data[] = $row;
        }

        $return['data'] = $data;
        $return['query'] = $doc->toSql();

        return json_encode($return);
    }
}
