<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\FileManagement;
use App\Models\ConfigCompany;
use App\Models\File_Management;
use App\Models\General_occurrence_detail;
use Illuminate\Support\Facades\Hash;
use App\Models\General_occurrence_letter;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Session;

class GeneralOccurrenceLetter extends Controller
{
    function index(Request $request){
        $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $ol = General_occurrence_letter::where("company_id", Session::get("company_id"))
            ->orderBy('ba_num', 'desc')
            ->get();

        if(isset($request->s) && $request->s == "bank"){
            $ol = $ol->whereNotNull('approved_at');
        } else {
            $ol = $ol->whereNull('approved_at');
        }

        $last_ol = General_occurrence_letter::where("company_id", Session::get("company_id"))
            ->where('ba_date', 'like', date("Y")."%")
            ->orderBy('id', 'desc')
            ->first();
        $num = 1;
        if(!empty($last_ol)){
            $last_num = explode("/", $last_ol->ba_num);
            $num = intval($last_num[0]) + 1;
        }
        $tag = Session::get('company_tag');
        $bulan = $array_bln[date('n')];
        $thn = date("y");
        $ba_num = sprintf("%03d", $num)."/$tag/BA/$bulan/$thn";
        return view('occurrence_letter.index', compact('ol', 'ba_num'));
    }

    function form($type, $id){
        $ol = General_occurrence_letter::find($id);

        $detail = General_occurrence_detail::where('id_ba', $id)->get();

        $file = File_Management::all()->pluck('file_name', 'hash_code');

        return view('occurrence_letter.form', compact('ol', 'type', 'detail', 'file'));
    }

    function add_form(Request $request){
        $file = $request->file('_file');
        $filename = explode(".", $file->getClientOriginalName());
        array_pop($filename);
        $filename = str_replace(" ", "_", implode("_", $filename));

        $newFile = $filename."-[ba_problems]-".date('Y_m_d_H_i_s').".".$file->getClientOriginalExtension();
        $hashFile = Hash::make($newFile);
        $hashFile = str_replace("/", "", $hashFile);
        $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\asset\documents");
        if($upload == 1){
            if (empty($request->_id_detail)) {
                $detail = new General_occurrence_detail();
                $detail->id_ba = $request->_id;
                $detail->problems = $request->_description;
                $detail->problems_attachment = $hashFile;
                $detail->created_by = Auth::user()->username;
                $detail->save();

                return redirect()->back()->with('msg', '1');
            } else {
                $detail = General_occurrence_detail::find($request->_id_detail);
                $detail->actions = $request->_description;
                $detail->actions_attachment = $hashFile;
                $detail->actions_at = date("Y-m-d H:i:s");
                $detail->actions_by = Auth::user()->username;
                $detail->save();

                return redirect()->back()->with('tab', 'actions');
            }
        }

        return redirect()->back()->with('msg', '0');
    }

    function detail_delete($id){
        General_occurrence_detail::find($id)->delete();

        return redirect()->back()->with('msg', '-1');
    }

    function form_update(Request $request){
        $ol = General_occurrence_letter::find($request->_id);
        $msg = '0';
        if($request->_type == "problems"){
            if ($request->status == "done") {
                $ol->problems_at = date("Y-m-d H:i:s");
                $ol->problems_by = Auth::user()->username;
                $msg = '2';
            } else {
                $ol->problems_at = null;
                $ol->problems_by = null;
                $msg = '3';
            }
        } elseif($request->_type == "actions"){
            if ($request->status == "done") {
                $ol->actions_at = date("Y-m-d H:i:s");
                $ol->actions_by = Auth::user()->username;
                $msg = '2';
            } else {
                $ol->actions_at = null;
                $ol->actions_by = null;
                $msg = '3';
            }
        } elseif($request->_type == "man-approve"){
            if ($request->status == "done") {
                $ol->man_approve_at = date("Y-m-d H:i:s");
                $ol->man_approve_by = Auth::user()->username;
                $msg = '2';
            } else {
                $ol->man_approve_at = null;
                $ol->man_approve_by = null;
                $msg = '3';
            }
        } elseif($request->_type == "hse-approve"){
            if ($request->status == "done") {
                $ol->hse_approve_at = date("Y-m-d H:i:s");
                $ol->hse_approve_by = Auth::user()->username;
                $msg = '2';
            } else {
                $ol->hse_approve_at = null;
                $ol->hse_approve_by = null;
                $msg = '3';
            }
        }

        $ol->save();

        return redirect()->back()->with(['msg' => $msg, 'tab' => $request->_type]);
    }

    function add(Request $request){
        $ba = new General_occurrence_letter();
        $ba->ba_num = $request->_num;
        $ba->ba_date = $request->_date;
        $ba->title = $request->_title;
        $ba->ba_by = (empty($request->_ba_by)) ? Auth::user()->username : $request->_ba_by;
        $ba->description = $request->_description;
        $ba->created_by = Auth::user()->username;
        $ba->updated_by = Auth::user()->username;
        $ba->company_id = Session::get('company_id');

        if($ba->save()){
            $file_attach = $request->file('_attachment');
            $attachDesc = $request->_attach_desc;
            if(!empty($file_attach)){
                foreach ($file_attach as $key => $value) {
                    $filename = explode(".", $value->getClientOriginalName());
                    array_pop($filename);
                    $filename = str_replace(" ", "_", implode("_", $filename));

                    $newFile = $filename."-[occurrance_letter]-".date('Y_m_d_H_i_s').".".$value->getClientOriginalExtension();
                    $hashFile = Hash::make($newFile);
                    $hashFile = str_replace("/", "", $hashFile);
                    $upload = FileManagement::save_file_management($hashFile, $value, $newFile, "media\asset\documents");
                    if($upload == 1){
                        $detail = new General_occurrence_detail();
                        $detail->id_ba = $ba->id;
                        $detail->problems = $attachDesc[$key];
                        $detail->problems_attachment = $hashFile;
                        $detail->created_by = $ba->created_by;
                        $detail->save();
                    }
                }
            }

            return redirect()->back()->with('msg', 'saved');
        }

        return redirect()->back();
    }

    function detail_get($id){
        $detail = General_occurrence_detail::find($id);

        $status = false;
        if(!empty($detail)){
            $status = true;
        }

        $result = array(
            "status" => $status,
            "data" => $detail
        );

        return json_encode($result);
    }

    function _get_ol($id){
        $ol = General_occurrence_letter::find($id);

        $status = false;
        if(!empty($ol)){
            $status = true;
        }

        $result = array(
            "status" => $status,
            "data" => $ol
        );

        return json_encode($result);
    }

    function _add(Request $request){
        $attachment = null;
        $file_attach = $request->file('_attachment');
        $attachDesc = $request->_attach_desc;
        if(!empty($file_attach)){
            foreach ($file_attach as $key => $value) {
                $filename = explode(".", $value->getClientOriginalName());
                array_pop($filename);
                $filename = str_replace(" ", "_", implode("_", $filename));

                $newFile = $filename."-[occurrance_letter]-".date('Y_m_d_H_i_s').".".$value->getClientOriginalExtension();
                $hashFile = Hash::make($newFile);
                $hashFile = str_replace("/", "", $hashFile);
                $upload = FileManagement::save_file_management($hashFile, $value, $newFile, "media\asset\documents");
                if($upload == 1){
                    $row = [];
                    $row['code'] = $hashFile;
                    $row['description'] = (!empty($attachDesc[$key])) ? $attachDesc[$key] : $filename;
                    $attachment[] = $row;
                }
            }
        }

        $letter = General_occurrence_letter::find($request->_id);
        $letter->title = $request->_title;
        $letter->description = $request->_description;
        $letter->input_date = $request->_date;
        if(!empty($attachment)){
            $letter->attachments = json_encode($attachment);
        }
        $letter->ba_by = Auth::user()->username;
        $letter->ba_input_date = date("Y-m-d H:i:s");
        if($letter->save()){
            return redirect()->back()->with('msg', 'saved');
        }

        return redirect()->back();
    }

    function detail($type, $id){
        $ol = General_occurrence_letter::find($id);
        $details = General_occurrence_detail::where('id_ba', $id)->get();

        $file_address = File_Management::all()->pluck('file_name', 'hash_code');

        $user_created = User::where('username', $ol->created_by)->get();
        $sign_created = "";
        if(count($user_created)){
            if (count($user_created) > 1) {
                $user_comp = $user_created->where('company_id', Session::get('company_id'))->first();
                $sign_created = $user_comp->file_signature;
            } else {
                $sign_created = $user_created[0]->file_signature;
            }
        }

        $user_approved = User::where('username', $ol->approved_by)->get();
        $sign_approved = "";
        if(count($user_approved) > 0){
            if (count($user_approved) > 1) {
                $user_comp = $user_approved->where('company_id', Session::get('company_id'))->first();
                $sign_approved = $user_comp->file_signature;
            } else {
                $sign_approved = $user_approved[0]->file_signature;
            }
        }

        return view('occurrence_letter._approve', compact('ol', 'file_address', 'type', 'sign_created', 'sign_approved', 'details'));
    }

    function approve(Request $request){
        $ol = General_occurrence_letter::find($request->id);
        if($request->submit == "approve"){
            $ol->approved_at = date("Y-m-d H:i:s");
            $ol->approved_by = Auth::user()->username;
        } elseif($request->submit == "reject"){
            $ol->rejected_at = date("Y-m-d H:i:s");
            $ol->rejected_by = Auth::user()->username;
        }


        $ol->save();

        return redirect()->to(route('oletter.index')."?s=bank");
    }

    function _delete($id){
        General_occurrence_letter::find($id)->delete();
        General_occurrence_detail::where('id_ba', $id)->delete();

        return redirect()->back();
    }

    function print($id, $type){
        $ol = General_occurrence_letter::find($id);
        $detail = General_occurrence_detail::where('id_ba', $id)->get();
        $file_address = File_Management::all()->pluck('file_name', 'hash_code');

        $sign_created = $this->get_paraf($ol->created_by);

        $sign_fol_up = $this->get_paraf($ol->problems_by);

        $sign_approved = $this->get_paraf($ol->approved_by);

        $comp_ba = ConfigCompany::find($ol->company_id);

        return view('occurrence_letter.print', compact('ol', 'detail', 'file_address', 'sign_created', 'sign_approved', 'comp_ba', 'type', 'sign_fol_up'));
    }

    function get_paraf($user_signed){
        $user = User::where('username', $user_signed)->get();
        $signed = null;
        if(count($user) > 0){
            if (count($user) > 1) {
                $user_comp = $user->whereNotNull('file_signature')->first();
                if(!empty($user_comp)){
                    $signed = $user_comp->file_signature;
                }
            } else {
                $signed = $user[0]->file_signature;
            }
        }

        return $signed;
    }
}
