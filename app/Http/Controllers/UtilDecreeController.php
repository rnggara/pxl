<?php

namespace App\Http\Controllers;

use App\Helpers\FileManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use DB;
use App\Models\Util_decree_main;

class UtilDecreeController extends Controller
{
    public function index(){
        $decree = Util_decree_main::where('company_id',\Session::get('company_id'))->get();
        return view('decree.index',[
            'decrees' => $decree,
        ]);
    }
    public function addDecree(Request $request){
        $decree = new Util_decree_main();
        $decree->author = Auth::user()->username;
        $decree->title = $request['title'];
        $decree->deskripsi = $request['description'];
        $decree->class = $request['classification'];
        $decree->created_at = date('Y-m-d H:i:s');
        $decree->company_id = \Session::get('company_id');
        $file = $request->file('file_form');
        $newFile = $file->getClientOriginalName()."_".date('Y_m_d_H_i_s').".".$file->getClientOriginalExtension();
        $hashFile = Hash::make($newFile);
        $hashFile = str_replace("/", "", $hashFile);
        $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/decree_archive");
        if ($upload == 1){
            $decree->file_form = $hashFile;
            $decree->save();
        }
        return redirect()->route('decree.index');
    }

    public function delete($id){
        Util_decree_main::where('id',$id)->delete();
        return redirect()->route('decree.index');
    }
}
