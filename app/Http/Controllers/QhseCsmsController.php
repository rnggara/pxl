<?php

namespace App\Http\Controllers;

use App\Models\Qhse_csms;
use App\Models\Qhse_csms_ol;
use App\Models\Qhse_csms_tt;
use Illuminate\Http\Request;
use App\Models\Qhse_csms_step;
use App\Helpers\FileManagement;
use App\Models\File_Management;
use App\Models\Qhse_csms_files;
use App\Models\Qhse_csms_input;
use App\Models\Qhse_csms_links;
use App\Models\Qhse_csms_meeting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class QhseCsmsController extends Controller
{

    private $field_ol = array("name" => "text", "delivered_by" => "text", "delivered_at" => "date");

    function index(){
        $csms = Qhse_csms::where('company_id', Session::get('company_id'))->get();
        $step = Qhse_csms_step::where('company_id', Session::get('company_id'))->get();
        $step_csms = [];
        foreach ($step as $key => $value) {
            $step_csms[$value->id_csms][] = $value->id;
        }
        return view('csms.index', compact('csms', 'step_csms'));
    }

    function add(Request $request){
        $csms = new Qhse_csms();
        $csms->name = $request->name;
        $csms->year = $request->year;
        $csms->created_by = Auth::user()->username;
        $csms->company_id = Session::get('company_id');

        $csms->save();
        return redirect()->route('qhse.csms.view', ["type" => "step", "id" => $csms->id]);
    }

    function view($type, $id){
        $csms = Qhse_csms::find($id);
        $step = Qhse_csms_step::where('id_csms', $id)
            ->orderBy('step')
            ->get();
        $input = Qhse_csms_input::where('company_id', Session::get('company_id'))
            ->orderBy('step')
            ->get();

        $input_step = [];
        foreach ($input as $key => $value) {
            $input_step[$value->id_step][] = $value->type;
        }

        $files = Qhse_csms_files::where('id_csms', $id)->get();
        $data_file = array();
        $file = array();
        $_file = File_Management::all();
        foreach ($_file as $item){
            $name = explode( "/", str_replace("\\", "/", $item->file_name));
            $ext = explode(".", end($name));
            $data_file['type'] = end($ext);

            if (in_array($data_file['type'], ['jpeg', 'jpg', 'png'])){
                $data_file['src'] = "files/jpg.svg";
            } elseif (in_array($data_file['type'], ['xls', 'xlsm', 'xlsx', 'csv'])){
                $data_file['src'] = "files/csv.svg";
            } elseif (in_array($data_file['type'], ['pdf'])){
                $data_file['src'] = "files/pdf.svg";
            } elseif (in_array($data_file['type'], ['doc', 'docx'])){
                $data_file['src'] = "files/doc.svg";
            } elseif (in_array($data_file['type'], ['rar', 'zip'])){
                $data_file['src'] = "files/zip.svg";
            } else {
                $data_file['src'] = "icons/Files/File.svg";
            }
            $data_file['file_name'] = end($name);
            $file[$item->hash_code] = $data_file;
        }

        $meetings = Qhse_csms_meeting::where('id_csms', $id)->get();

        $ol = Qhse_csms_ol::where('id_csms', $id)
            ->where('type', 'ol')
            ->get();

        $tt = Qhse_csms_tt::where('id_csms', $id)
            ->orderBy('due_date', 'desc')
            ->get();

        $summary_field = Qhse_csms_ol::where('id_csms', $id)
            ->where('type', 'su')
            ->get();

        $links = Qhse_csms_links::where('id_csms', $id)
            ->get();

        if ($type == "step") {
            $view = "csms.step";
        } else {
            $view = "csms.view";
        }
        return view($view, [
            "csms" => $csms,
            "step" => $step,
            'files' => $files,
            'data_file' => $file,
            'meetings' => $meetings,
            'ol' => $ol,
            'tt' => $tt,
            'input_step' => $input_step,
            'field_ol' => $this->field_ol,
            'summary_field' => $summary_field,
            'links' => $links
        ]);
    }

    function input_step($id){
        $step = Qhse_csms_step::find($id);
        $input = Qhse_csms_input::where('id_step', $id)
            ->orderBy('step')
            ->get();

        return view('csms.input', compact('step', 'input'));
    }

    function add_step(Request $request){
        $iStep = Qhse_csms_step::where('id_csms', $request->id_csms)
            ->orderBy('step', 'desc')
            ->first();
        if (!empty($iStep)) {
            $nStep = $iStep->step + 1;
        } else {
            $nStep = 1;
        }
        $step = new Qhse_csms_step();
        $step->name = $request->name;
        $step->step = $nStep;
        $step->id_csms = $request->id_csms;
        $step->created_by = Auth::user()->username;
        $step->company_id = Session::get('company_id');

        $step->save();

        return redirect()->back();

    }

    function add_input(Request $request){
        $iStep = Qhse_csms_input::where('id_step', $request->id_step)
            ->orderBy('step', 'desc')
            ->first();
        if (!empty($iStep)) {
            $nStep = $iStep->step + 1;
        } else {
            $nStep = 1;
        }
        $input = new Qhse_csms_input();
        $input->type = $request->type;
        $input->step = $nStep;
        $input->id_step = $request->id_step;
        $input->created_by = Auth::user()->username;
        $input->company_id = Session::get('company_id');
        $input->save();

        return redirect()->back();
    }

    function change($type, $x, $y){
        if ($type == "step") {
            $frst = Qhse_csms_step::find($y);
            $scnd = Qhse_csms_step::find($x);
        } else {
            $frst = Qhse_csms_input::find($y);
            $scnd = Qhse_csms_input::find($x);
        }

        $step_frst = $frst->step;
        $step_scnd = $scnd->step;

        $frst->step = $step_scnd;
        $scnd->step = $step_frst;

        $frst->save();
        $scnd->save();

        return redirect()->back();
    }

    function delete($type, $id){
        if ($type == "csms") {
            $csms = Qhse_csms::find($id);
            $step = Qhse_csms_step::where('id_csms', $id);
            $id_step = [];
            foreach ($step->get() as $key => $value) {
                $id_step[] = $value->id;
            }
            $input = Qhse_csms_input::whereIn('id_step', $id_step);
            $csms->delete();
            $step->delete();
            $input->delete();
        } elseif ($type == "step"){
            $step = Qhse_csms_step::find($id);
            $id_step = [];
            foreach ($step->get() as $key => $value) {
                $id_step[] = $value->id;
            }
            $input = Qhse_csms_input::whereIn('id_step', $id_step);
            $step->delete();
            $input->delete();
        } elseif ($type == "input") {
            $input = Qhse_csms_input::find($id);
            $input->delete();
        }

        return redirect()->back();
    }

    /*DOCUMENT FUNCTIONS*/
    function files_upload(Request $request){
        $file = $request->file('file');
        $filename = explode(".", $file->getClientOriginalName());
        array_pop($filename);
        $filename = str_replace(" ", "_", implode("_", $filename));

        $newFile = $filename."-".date('Y_m_d_H_i_s')."(".$request->id_leads.").".$file->getClientOriginalExtension();
        $hashFile = Hash::make($newFile);
        $hashFile = str_replace("/", "", $hashFile);
        $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\lead");
        if ($upload == 1){
            $lead_files = new Qhse_csms_files();
            $lead_files->id_csms = $request->id_project;
            $lead_files->id_step = $request->id_step;
            $lead_files->file_code = $hashFile;
            $lead_files->type = $request->type;
            $lead_files->save();

            return redirect()->back();
        }
    }

    function files_delete($id){
        Qhse_csms_files::find($id)->delete();
        $data['error'] = 0;
        return json_encode($data);
    }

    /*MEETING FUNCTIONS*/
    function meetings_create(Request $request){
//        dd($request);
        $meeting = new Qhse_csms_meeting();
        $meeting->id_csms = $request->id_project;
        $meeting->id_step = $request->id_step;
        $meeting->subject = $request->subject;
        $meeting->description = $request->description;
        $meeting->attendees = $request->attendees;
        $meeting->date = $request->date;
        $meeting->time = $request->time;
        $meeting->duration = $request->duration;
        $meeting->created_by = Auth::user()->username;
        $meeting->save();
        return redirect()->back();
    }

    function meetings_mom(Request $request){
        $file = $request->file('file');
        $filename = explode(".", $file->getClientOriginalName());
        array_pop($filename);
        $filename = str_replace(" ", "_", implode("_", $filename));

        $meeting = Qhse_csms_meeting::find($request->id_meeting);
        $newFile = $filename."-".date('Y_m_d_H_i_s')."(".$meeting->id_csms.").".$file->getClientOriginalExtension();
        $hashFile = Hash::make($newFile);
        $hashFile = str_replace("/", "", $hashFile);
        $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\lead");
        if ($upload == 1){
            $meeting->file_mom = $hashFile;
            $meeting->save();

        }

        return redirect()->back();
    }

    function meetings_get($id){
        $meeting = Qhse_csms_meeting::where('id_csms', $id)->get();

        return json_encode($meeting);
    }

    /*SU FUNCTIONS*/
    function su_create_field(Request $request){
//        dd($request);
        $su_field = new Qhse_csms_ol();
        $su_field->id_step = $request->id_step;
        $su_field->id_csms = $request->id_project;
        $su_field->type = "su";
        $name = $request->field_name;
        $type = $request->field_type;
        $field = array();
        for ($i = 0; $i < count($name); $i++){
            $field[$name[$i]] = $type[$i];
        }

        $su_field->title = $request->table_title;
        $su_field->field = json_encode($field);

        $su_field->save();
        return redirect()->back();
    }
    function su_create(Request $request){
        $suExist = Qhse_csms_ol::where('id_csms', $request->id_project)
            ->where('type', 'su')
            ->where('id_step', $request->id_step)
            ->first();
        if (!empty($suExist)){
            $su = Qhse_csms_ol::find($suExist->id);
            $values = (empty($su->values)) ? array() : json_decode($su->values);
        } else {
            $su = new Qhse_csms_ol();
            $su->id_csms = $request->id_project;
            $su->id_step = $request->id_step;
            $su->type = "su";
            $values = array();
        }
        $su->field = json_encode($this->field_ol);
        foreach ($this->field_ol as $key => $item){
            $row[$key] = $request[$key];
        }
        $values[] = $row;

        $su->values = json_encode($values);
        $su->created_by = Auth::user()->username;
        $su->save();
        return redirect()->back();
    }

    function su_form($id){
        $su = Qhse_csms_ol::find($id);

        return view('csms._su_form', [
            'su' => $su
        ]);
    }

    function su_delete($id){
        Qhse_csms_ol::find($id)->delete();
        return redirect()->back();
    }

    function su_add_row(Request $request){
        $su = Qhse_csms_ol::find($request->id_su);
        $data = (!empty($su->values)) ? json_decode($su->values) : array();
        foreach (json_decode($su->field) as $name => $type){
            $row[$name] = $request[$name];
        }
        $data[] = $row;
        $su->values = json_encode($data);
        $su->save();

        return redirect()->back();
    }

    function su_delete_row(Request $request){
        $su = Qhse_csms_ol::find($request->_id);
        $values = json_decode($su->values);
        array_splice($values, $request->_index, 1);
        $su->values = json_encode($values);
        if ($su->save()){
            $data['delete'] = 1;
        } else {
            $data['delete'] = 0;
        }

        return json_encode($data);
    }

    /*OL FUNCTIONS*/
    function ol_create(Request $request){
//        dd($request);
        $olExist = Qhse_csms_ol::where('id_csms', $request->id_project)
            ->where('type', 'ol')
            ->where('id_step', $request->id_step)
            ->first();
        if (!empty($olExist)){
            $ol = Qhse_csms_ol::find($olExist->id);
            $values = json_decode($ol->values);
        } else {
            $ol = new Qhse_csms_ol();
            $ol->id_csms = $request->id_project;
            $ol->id_step = $request->id_step;
            $ol->type = "ol";
            $values = array();
        }
        $ol->field = json_encode($this->field_ol);
        foreach ($this->field_ol as $key => $item){
            $row[$key] = $request[$key];
        }
        $values[] = $row;

        $ol->values = json_encode($values);
        $ol->created_by = Auth::user()->username;
        $ol->save();
        return redirect()->back();
    }

    function ol_get($id){
        $ol = Qhse_csms_ol::find($id);

        return json_encode($ol);
    }

    function ol_delete($id){
        Qhse_csms_ol::find($id)->delete();
        return redirect()->back();
    }

    function ol_update(Request $request){
        $ol = Qhse_csms_ol::find($request->ol_id);
        $ol->title = $request->title;
        $ol->notes = $request->ol;
        $ol->save();
        return redirect()->back();
    }

    /*TT FUNCTIONS*/
    function tt_create(Request $request){
        $tt = new Qhse_csms_tt();
        $tt->title = $request->title;
        $tt->notes = $request->notes;
        $tt->id_csms = $request->id_project;
        $tt->id_step = $request->id_step;
        $tt->due_date = $request->due_date;
        $tt->due_time = $request->due_time;
        $tt->status = 0;
        $tt->created_by = Auth::user()->username;
        $tt->save();
        return redirect()->back();
    }

    function tt_follow($id){
        $tt = Qhse_csms_tt::find($id);
        if ($tt->status == 0){
            $tt->status = 1;
        } else {
            $tt->status = 0;
        }

        $tt->save();

        return json_encode($tt->status);
    }

    function tt_delete($id){
        Qhse_csms_tt::find($id)->delete();
        return redirect()->back();
    }

    // LINKS
    function links_create(Request $request){
        $links = new Qhse_csms_links();
        $links->id_step = $request->id_step;
        $links->id_csms = $request->id_csms;
        $links->links = $request->link;
        $links->created_by = Auth::user()->username;
        $links->company_id = Session::get('company_id');
        $links->save();

        return redirect()->back();
    }

    function links_delete($id){
        $links = Qhse_csms_links::find($id);

        if ($links->delete()) {
            return redirect()->back();
        }
    }

    function print($id){
        $csms = Qhse_csms::find($id);

        $step = Qhse_csms_step::where('id_csms', $id)->get();

        $files = Qhse_csms_files::where('id_csms', $id)->get();
        $file_step = [];
        foreach ($files as $key => $value) {
            $file_step[$value->id_step][] = $value;
        }

        $file_content = File_Management::all();
        $get_file = [];
        foreach ($file_content as $key => $value) {
            $get_file[$value->hash_code] = $value->file_name;
        }

        return view('csms.print', [
            "csms" => $csms,
            "step" => $step,
            "file" => $file_step,
            "get_file" => $get_file
        ]);
    }
}
