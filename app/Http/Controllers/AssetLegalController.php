<?php

namespace App\Http\Controllers;

use App\Helpers\FileManagement;
use App\Models\Asset_certificate;
use App\Models\File_Management;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AssetLegalController extends Controller
{
    function index(){
        $legal = Asset_certificate::where('type', 'LEGAL')->get();
        $pict_code = array();
        foreach ($legal as $item){
            if (!empty($item->picture)){
                $pict_code[] = $item->picture;
            }
        }
        $file = File_Management::all();
        $data_file = array();
        if (!empty($file)){
            foreach ($file as $item){
                $filename = explode(".", $item->file_name);
                if (in_array(strtolower(end($filename)), ["jpg", "png", "jpeg", "tiff", "gif"])){
                    $ext = "image";
                } else {
                    $ext = "other";
                }
                $data_file[$item->hash_code] = $ext;
            }
        }
        return view('certificates.legal.index', [
            'legals' => $legal,
            'file_type' => $data_file
        ]);
    }

    function add(Request $request){
//        dd($request->request);
        $legal = new Asset_certificate();
        foreach ($request->request as $key => $value){
            if (!in_array($key, ["_token", "submit"])){
                $legal->$key = $request[$key];
            }
        }

        if ($legal->save()){
            return redirect()->back();
        }
    }

    function update(Request $request){
        $legal = Asset_certificate::find($request->id);
        foreach ($request->request as $key => $value){
            if (!in_array($key, ["_token", "submit"])){
                $legal->$key = $request[$key];
            }
        }

        if ($legal->save()){
            return redirect()->back();
        }
    }

    function upload(Request $request){
        $legal = Asset_certificate::find($request->id);
        $file = $request->file('picture');
        $filename = explode(".", $file->getClientOriginalName());
        array_pop($filename);
        $filename = str_replace(" ", "_", implode("_", $filename));

        $newFile = $filename."-".date('Y_m_d_H_i_s')."(".$request->id.").".$file->getClientOriginalExtension();
        $hashFile = Hash::make($newFile);
        $hashFile = str_replace("/", "", $hashFile);
        $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\asset\legal");
        if ($upload == 1){
            $legal->picture = $hashFile;
            $legal->save();
        }
        return redirect()->back();
    }

    function detail($id){
        $legal = Asset_certificate::find($id);

        return view('certificates.legal.edit', [
            'legal' => $legal
        ]);
    }

    function image($id){
        $legal = Asset_certificate::find($id);
        $file_management = File_Management::where('hash_code', $legal->picture)->first();

        return view('certificates.legal.image', [
            'legal' => $legal,
            'file' => $file_management
        ]);
    }

    function delete($id){
        $legal = Asset_certificate::find($id);

        if ($legal->delete()){
            $data['delete'] = 1;
        } else {
            $data['delete'] = 0;
        }

        return json_encode($data);
    }
}
