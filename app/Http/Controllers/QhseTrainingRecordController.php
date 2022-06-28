<?php

namespace App\Http\Controllers;

use App\Models\Hrd_employee;
use Illuminate\Http\Request;
use App\Helpers\FileManagement;
use App\Models\Qhse_training_type;
use App\Models\Qhse_training_record;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class QhseTrainingRecordController extends Controller
{
    function index(){
        $type = Qhse_training_type::where('company_id', Session::get('company_id'))->get();

        $typeById = $type->pluck('type_name', 'id');
        $emp = Hrd_employee::where('company_id', Session::get('company_id'))
            ->whereNull('expel')
            ->orderBy('emp_name')
            ->get()
            ->pluck('emp_name', 'id');

        $tr = Qhse_training_record::where('company_id', Session::get('company_id'))->get();

        return view('training_record.index', [
            "type" => $type,
            'emp' => $emp,
            'training' => $tr,
            'typeById' => $typeById
        ]);
    }

    function add(Request $request){
        $tr = new Qhse_training_record();
        $tr->emp_id = $request->emp_id;
        $tr->placement = $request->placement;
        $tr->paper_number = $request->paper_number;
        $tr->training_type = $request->training_type;
        $tr->training_date = $request->training_date;
        $tr->training_place = $request->training_place;
        $tr->exp_date = $request->exp_date;
        $tr->company_id = Session::get('company_id');
        $tr->created_by = Auth::user()->username;

        $file = $request->file('up_file');
        $filename = $file->getClientOriginalName();
        $newFile = "training-record-[".$request->emp_id."]-".$filename;
        $hashFile = Hash::make($newFile);
        $hashFile = str_replace("/", "", $hashFile);
        $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/training_record");
        if($upload == 1){
            $tr->file = $hashFile;
        }

        $tr->save();

        return redirect()->back();
    }

    function delete($id){
        $tr = Qhse_training_record::find($id);
        $tr->deleted_by = Auth::user()->username;
        $tr->save();
        $tr->delete();

        return redirect()->back();
    }

    function type_add(Request $request){
        $type = new Qhse_training_type();
        $type->type_name = $request->type_name;
        $type->created_by = Auth::user()->username;
        $type->company_id = Session::get('company_id');
        $type->save();

        return redirect()->back();
    }

    function type_delete($id){
        Qhse_training_type::find($id)->delete();

        return redirect()->back();
    }
}
