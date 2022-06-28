<?php

namespace App\Http\Controllers;

use App\Helpers\FileManagement;
use Illuminate\Http\Request;
use App\Models\Ins_ru;
use App\Models\Ins_ru_detail;
use Illuminate\Support\Facades\Hash;
use Session;
use DB;
use Illuminate\Support\Facades\Auth;

class DirutInsCEOController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/jakarta');
    }

    public function index(){
        $ins_ru = Ins_ru::where('company_id', Session::get('company_id'))->get();

        return view('ha.insceo.index',[
            'ins_ru' => $ins_ru
        ]);
    }

    public function store(Request $request){
//        dd($request);
        if (isset($request['edit'])){
            $ins_ru = Ins_ru::find($request['edit']);
        } else {
            $ins_ru = new Ins_ru();
        }
        $ins_ru->nama_asuransi = $request['ins_name'];
        $ins_ru->alamat_asuransi = $request['ins_address'];
        $ins_ru->phone_asuransi = $request['ins_phone'];
        $ins_ru->polis = $request['pol_num'];
        $ins_ru->due_date = $request['due_date'];
        $ins_ru->currency = $request['curr'];
        $ins_ru->jumlah = $request['jml'];
        $ins_ru->angsuran = $request['angsuran'];
        $ins_ru->cover_ins = $request['ins_cover'];
        $ins_ru->tgl_trans = date('Y-m-d');
        $ins_ru->insured = $request['insured'];
        $ins_ru->company_id = Session::get('company_id');
        $ins_ru->save();

        return redirect()->back();

    }

    public function getDetail($id){
        $files = Ins_ru_detail::where('id_main', $id)->get();
        $ins = Ins_ru::where('id', $id)->first();
        return view('ha.insceo.detail',[
            'files' => $files ,
            'ins_detail' => $ins,
        ]);
    }

    public function saveFile(Request $request){

        $ins_file = new Ins_ru_detail();
        $ins_file->id_main = $request['id_main'];
        $ins_file->tgl_upload = date('Y-m-d H:i:s');
        $ins_file->created_by = Auth::user()->username;
        $ins_file->created_at = date('Y-m-d H:i:s');

        if ($request->hasFile('file')){
            $file = $request->file('file');

            $newFile = $file->getClientOriginalName();
            $fileext = $file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
//            dd($newFile);
            $hashFile = str_replace("/", "", $hashFile);
            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/insurance_ceo");
            if ($upload == 1){
                $ins_file->nama_file = $newFile;
                $ins_file->type_file = $fileext;
                $ins_file->location = $hashFile;
            }
        }

        $ins_file->save();

        return redirect()->back();
    }

    public function delete($id){
        Ins_ru::find($id)->delete();
        Ins_ru_detail::where('id_main', $id)->delete();
        return redirect()->back();
    }

    public function deleteDetail($id){
        Ins_ru_detail::where('id', $id)->delete();
        return redirect()->back();
    }
}
