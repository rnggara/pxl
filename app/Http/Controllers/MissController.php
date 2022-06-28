<?php

namespace App\Http\Controllers;

use App\Helpers\FileManagement;
use App\Models\Marketing_project;
use App\Models\Qhse_miss;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use DB;


class MissController extends Controller
{
    public function index(){
        $nearmiss = Qhse_miss::where('company_id', Session::get('company_id'))
            ->orderBy('date','desc')
            ->get();
        $prj = Marketing_project::where('company_id', Session::get('company_id'))->get();
        $prj_name = [];
        $prj_id = [];
        foreach ($prj as $key => $value){
            $prj_name[$value->id] = $value->prj_name;
            $prj_id[] = $value->id;
        }
//        dd($nearmiss);

        return view('miss.index',[
            'nearmiss' => $nearmiss,
            'prj_id' => $prj_id,
            'prj_name' => $prj_name,
        ]);
    }

    public function getviewphoto($id = null,$status = null){
        $nearmiss = Qhse_miss::where('id', $id)->first();

        return view('miss.upload_foto',[
            'nearmiss' => $nearmiss,
            'status' => $status,
            'id' => $id,
        ]);
    }

    public function updatePhoto(Request $request){
//        dd($request);
        Artisan::call('cache:clear');
        $nearmiss = Qhse_miss::find($request->id);

        if ($request->status == 'edit'){
            if ($request->hasFile('image1')){
                $file = $request->file('image1');
                $newFile = date('Y_m_d_H_i_s')."pict_lapor.".$file->getClientOriginalExtension();

                $hashFile = Hash::make($newFile);
                $hashFile = str_replace("/", "", $hashFile);

                $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/miss_attachment");
                if ($upload == 1){
                    $nearmiss->pict = $newFile;
                }
            }
        } else {
            if ($request->hasFile('image1')){
                $file = $request->file('image1');
                $newFile = date('Y_m_d_H_i_s')."pict_lapor.".$file->getClientOriginalExtension();

                $hashFile = Hash::make($newFile);
                $hashFile = str_replace("/", "", $hashFile);

                $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/miss_attachment");
                if ($upload == 1){
                    $nearmiss->pict_follow = $newFile;
                }
            }
        }

        $nearmiss->save();
        return redirect()->route('miss.index');

    }

    public function getview($id = null,$status = null){
        $prj = Marketing_project::where('company_id', Session::get('company_id'))->get();
        $nearmiss = Qhse_miss::where('id', $id)->first();

        return view('miss.detail',[
            'nearmiss' => $nearmiss,
            'status' => $status,
            'prj' => $prj,
        ]);
    }

    public function nm_view($id){
        $nearmiss = Qhse_miss::where('id', $id)->first();
        return view('miss.view',[
            'nearmiss' => $nearmiss,
        ]);
    }

    public function store(Request $request){

//        dd($request);
        Artisan::call('cache:clear');
        if ($request->status == 'new'){
            $nearmiss = new Qhse_miss();
            $nearmiss->date = $request->date;
            $nearmiss->prj = $request->prj;
            $nearmiss->title = $request->title;
            $nearmiss->pelapor_name = Auth::user()->username;
            $nearmiss->pelapor = Auth::user()->id;
            $nearmiss->deskripsi = $request->desc;
            $nearmiss->company_id = Session::get('company_id');
            if ($request->hasFile('image1')){
                $file = $request->file('image1');
                $newFile = date('Y_m_d_H_i_s')."pict_lapor.".$file->getClientOriginalExtension();

                $hashFile = Hash::make($newFile);
                $hashFile = str_replace("/", "", $hashFile);

                $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/miss_attachment");
                if ($upload == 1){
                    $nearmiss->pict = $newFile;
                }
            }
            $nearmiss->save();
        }

        if ($request->submit == 'follow'){
            $nearmiss = Qhse_miss::find($request->id);
            $nearmiss->pelapor_follow_up = Auth::user()->username;
            $nearmiss->follow_up_task = $request->desc;
            if ($request->hasFile('image1')){
                $file = $request->file('image1');
                $newFile = date('Y_m_d_H_i_s')."pict_follow.".$file->getClientOriginalExtension();

                $hashFile = Hash::make($newFile);
                $hashFile = str_replace("/", "", $hashFile);

                $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/miss_attachment");
                if ($upload == 1){
                    $nearmiss->pict_follow = $newFile;
                }
            }

            $nearmiss->save();
        }

        return redirect()->route('miss.index');
    }

    public function delete(Request $request){
        Qhse_miss::find($request->del_id)->delete();
        return redirect()->back();
    }

    public function approval(Request $request){
//        dd($request);
        $nearmiss = Qhse_miss::find($request->id_nearmiss);
        if ($request->app == 'APPROVE'){
            $nearmiss->approved = "Management Representative";
            $nearmiss->close = date("Y-m-d");
        } else {
            $nearmiss->follow_up_task = "";
        }
        $nearmiss->save();
        return redirect()->route('miss.index');
    }

}
