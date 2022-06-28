<?php

namespace App\Http\Controllers;

use App\Models\Ha_paper_permit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class HAPoWoValidationController extends Controller
{
    function index(){
        $papers = Ha_paper_permit::where('company_id', Session::get('company_id'))->get();
        return view('ha.powoval.index', [
            'papers' => $papers
        ]);
    }

    function addCode(Request $request){

        for ($i = 0; $i < $request->qty; $i++){
            $paper = new Ha_paper_permit();
            $intaz = rand(0,23);
            $a_z = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $rand_letter = $a_z[$intaz];
            $random = rand(1000,9999);
            $codeval = $rand_letter.$random;

            $isPaper = Ha_paper_permit::where('kode', $codeval)->first();
            while (!empty($isPaper)){
                $intaz = rand(0,23);
                $a_z = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $rand_letter = $a_z[$intaz];
                $random = rand(1000,9999);
                $codeval = $rand_letter.$random;
            }

            $paper->nama_paper = $request->type;
            $paper->kode = $codeval;
            $paper->purpose = $request->purpose;
            $paper->author = Auth::user()->username;
            $paper->created_by = Auth::user()->username;
            $paper->company_id = Session::get('company_id');
            $paper->save();
        }

        return redirect()->route('ha.powoval.index');
    }

    function find($type, $kode){
        $paper = Ha_paper_permit::where('kode', $kode)
            ->where('nama_paper', $type)
            ->first();
        if (!empty($paper)){
            if ($paper->issued_date != null || $paper->issued_date != ""){
                $data['data'] = 2;
            } else {
                $data['data'] = 1;
            }
        } else {
            $data['data'] = 0;
        }

        return json_encode($data);
    }

    function delete($id){
        if (Ha_paper_permit::find($id)->delete()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }
}
