<?php

namespace App\Http\Controllers;

use App\Models\Hrd_cv_u;
use App\Models\Hrd_employee;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\General_covid;
use App\Helpers\FileManagement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\General_covid_employee;
use Illuminate\Support\Facades\Session;

class GeneralCovidProtocol extends Controller
{
    function index(){
        $protocols = General_covid::whereNotNull('approved_at')
            ->orderBy('id', 'desc')
            ->get();

        $emp = General_covid_employee::whereNull('tanggal_negatif')
            ->orderBy('tanggal_infeksi', 'desc')
            ->get();

        $companies = ConfigCompany::where('id', 1)
            ->orWhere('id_parent', 1)
            ->get()->pluck('company_name', 'id');

        return view('covid.index', compact('protocols', 'emp', 'companies'));
    }

    function setting(){
        $protocols = General_covid::orderBy('id')
            ->get();


        $companies = ConfigCompany::where('id', 1)
            ->orWhere('id_parent', 1)
            ->get()->pluck('company_name', 'id');


        $emp = General_covid_employee::all();
        return view('covid.setting', compact('protocols', 'companies', 'emp'));
    }

    function add(){
        return view('covid.add');
    }

    function store(Request $request){
        $last_num = 1;
        $last_pro = General_covid::where('company_id', Session::get('company_id'))
            ->orderBy('protocol_num', 'desc')
            ->first();
        if(!empty($last_pro)){
            $pro_num = explode("/", $last_pro->protocol_num);
            $last_num = intval($pro_num[0]) + 1;
        }

        $tag = Session::get('company_tag');
        $m = date("m");
        $y = date("y");
        $pnum = sprintf("%03d", $last_num)."/$tag/$m/$y";
        $protocol = new General_covid();
        $protocol->content = $request->topic;
        $protocol->protocol_num = $pnum;
        $file = $request->file('attachment');
        if(!empty($file)){
            $targetdir = "media/asset/documents/";
            $file_name = "[covid-protocol]-".date("Ymd")."-".$file->getClientOriginalName();
            $allowed = ["jpg", 'png', 'jpeg', 'svg', 'tiff', 'gif'];
            $ext =$file->getClientOriginalExtension();
            if(in_array($ext, $allowed)){
                $upload = $file->move(public_path($targetdir), $file_name);
                if($upload){
                    $protocol->content_eng = $targetdir.$file_name;
                }
            } else {
                return redirect()->back()->with('error', 'not image file');
            }
        }

        $protocol->created_by = Auth::user()->username;
        $protocol->date_detail = date('Y-m-d H:i:s');
        $protocol->company_id = Session::get('company_id');
        $protocol->save();

        return redirect()->route('general.covid.setting');
    }

    function update(Request $request){
        $pro = General_covid::find($request->id);
        if($request->submit == "ack"){
            $pro->acknowledge_by = Auth::user()->username;
            $pro->acknowledge_at = date("Y-m-d H:i:s");
        } else {
            $pro->approved_by = Auth::user()->username;
            $pro->approved_at = date("Y-m-d H:i:s");
        }

        $pro->save();
        return redirect()->back();
    }

    function view($id){
        $protocol = General_covid::find($id);
        return view('covid.view', compact('protocol'));
    }

    function delete($id){
        General_covid::find($id)->delete();

        return redirect()->back();
    }

    function emp_add(Request $request){
        $emp = new General_covid_employee();
        $emp->nama_emp = $request->_nama;
        $emp->jabatan = $request->_jabatan;
        $emp->perusahaan = $request->_perusahaan;
        $emp->tanggal_infeksi = $request->_tanggal;
        $emp->penyakit_bawaan = $request->_penyakit;
        $emp->save();

        return redirect()->route('general.covid.emp_detail', $emp->id);
    }

    function emp_detail($id, $type = null){
        $emp = General_covid_employee::find($id);
        $company = ConfigCompany::find($emp->perusahaan);

        $kar = Hrd_employee::whereNull('expel')
            ->where('company_id', $emp->perusahaan)->orderBy('emp_name')->get()->pluck('emp_name', 'id');

        $vac = [];
        if(!empty($emp->id_emp)){
            $vac = Hrd_cv_u::where('user_id', $emp->id_emp)
                ->where('vaccine', 1)
                ->get();
        }

        return view('covid.emp', compact('emp', 'company', 'type', 'kar', 'vac'));
    }

    function emp_update($type, $id, Request $request){
       $emp = General_covid_employee::find($id);
        if($type == "obat"){
            $emp->obat_office = $request->_office;
            $emp->obat_dokter = $request->_dokter;
            $emp->obat_bawaan = $request->_bawaan;
        } elseif ($type == "kondisi"){
            $emp->kondisi = $request->_kondisi;
        } elseif ($type == "test"){
            $metode = $request->_metode;
            $tanggal = $request->_tanggal;
            $tempat = $request->_tempat;
            $hasil = $request->_hasil;
            $file = $request->file("_file");
            for ($i=1; $i <= 3 ; $i++) {
                $field = "test_$i";
                if(!empty($metode[$i])){
                    $row = [];
                    $jsTest = (!empty($emp->$field)) ? json_decode($emp->$field, true) : [];
                    if(!empty($jsTest)){
                        $row = $jsTest;
                    }
                    $row['metode'] = $metode[$i];
                    $row['tanggal'] = $tanggal[$i];
                    $row['tempat'] = $tempat[$i];
                    $row['hasil'] = $hasil[$i];

                    if (isset($file[$i])) {
                        $filename = explode(".", $file[$i]->getClientOriginalName());
                        array_pop($filename);
                        $filename = str_replace(" ", "_", implode("_", $filename));

                        $newFile = $filename."-".date('Y_m_d_H_i_s')."(file-test-$i).".$file[$i]->getClientOriginalExtension();
                        $hashFile = Hash::make($newFile);
                        $hashFile = str_replace("/", "", $hashFile);
                        $upload = FileManagement::save_file_management($hashFile, $file[$i], $newFile, "media\asset\documents");
                        if ($upload == 1){
                            $row['file'] = $hashFile;
                        }
                    }
                    $emp->$field = json_encode($row);
                }
            }
        } elseif ($type == "bawaan"){
            $emp->penyakit_bawaan = $request->_bawaan;
        } elseif ($type == "negatif"){
            $emp->tanggal_negatif = $request->_negatif;
        } elseif ($type == "employee"){
            $emp->id_emp = $request->_emp_id;
        }

        $emp->save();

        return redirect()->back();
    }

    function emp_delete($id){
        General_covid_employee::find($id)->delete();

        return redirect()->back();
    }

    function emp_export($type){
        $emp = General_covid_employee::orderBy('tanggal_infeksi', 'desc')->get();
        $data = [];
        if($type == "positive"){
            $data = $emp->whereNull('tanggal_negatif');
        } else {
            $data = $emp->whereNotNull('tanggal_negatif');
        }

        $emp_comp = ConfigCompany::all()->pluck('company_name', 'id');

        return view('covid.export', compact('type', 'data', 'emp_comp'));
    }
}
