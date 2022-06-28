<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Helpers\FileManagement;
use App\Models\File_Management;
use App\Models\Marketing_project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\General_operation_report;
use App\Models\General_operation_report_detail;
use App\Models\General_operation_report_setting;
use App\Models\General_operation_report_activity;
use App\Models\General_operation_report_attach;
use App\Models\General_operation_report_items;
use App\Models\General_operation_report_templates;
use App\Models\General_report;
use Exception;

class GeneralOperationReport extends Controller
{

    private $_category = array(
        "tank" => "Storages",
        "safety" => "Safety Instruments",
        "pump" => "Transfer Pumps",
        "truck" => "Transfer to Trucks",
        "sum" => "Production Parameter"
    );

    function add_form_desc(Request $request){
        $last = $request->t;
        return view('operation._description_form', compact('last'));
    }

    function add_form_attachment(){
        return view('operation._attachment_form');
    }

    function index(){
        $projects = Marketing_project::where('company_id', Session::get('company_id'))
            ->whereNull('view')
            ->orderBy('id', 'desc')
            ->get();
        return view('operation.index', compact('projects'));
    }

    function setting($type, $id){
        $project = Marketing_project::find($id);
        $_category = $this->_category;

        $setting_report = General_operation_report_setting::where('id_project', $id)->first();

        $detail = General_operation_report_detail::where('id_project', $id)->orderBy('id', 'desc')->get();

        $templates = General_operation_report_templates::where("company_id", Session::get('company_id'))->get();


        if($type == "setting"){
            return view('operation.setting', compact('project', '_category', 'templates', 'setting_report', 'detail'));
        } else {

            $reports = General_operation_report::where("project_id", $id)->orderBy('report_no', 'desc')->get();

            return view('operation.report', compact('project', '_category', 'templates', 'setting_report', 'detail', 'reports'));
        }
    }

    function logo_setting(Request $request){
        $setting = General_operation_report_setting::where('id_project', $request->id_project)->first();
        if(empty($setting)){
            $setting = new General_operation_report_setting();
            $setting->id_project = $request->id_project;
        }

        if($request->hasFile('left_logo')){
            $upload = $this->upload_file($request->file('left_logo'), $request->id_project);
            if($upload){
                $setting->left_logo = $upload;
            }
        }

        if($request->hasFile('right_logo')){
            $upload = $this->upload_file($request->file('right_logo'), $request->id_project);
            if($upload){
                $setting->right_logo = $upload;
            }
        }

        if(isset($request->delete_left_logo)){
            $setting->left_logo = null;
        }

        if(isset($request->delete_right_logo)){
            $setting->right_logo = null;
        }

        $setting->id_template = $request->_template;

        $setting->company_id = Session::get("company_id");
        $setting->save();

        return redirect()->back();
    }

    function setting_add(Request $request){
        $detail = new General_operation_report_detail();
        $detail->id_project = $request->_id_project;
        $detail->item_name = $request->_name;
        $detail->description = $request->_description;
        $detail->uom = $request->_uom;
        $detail->category = $request->_category;
        $detail->created_by = Auth::user()->username;
        $detail->company_id = Session::Get('company_id');

        if($detail->save()){
            return redirect()->back()->with('success', 'Data saved');
        } else {
            return redirect()->back()->with('error', 'Data failed to be save');
        }
    }

    function setting_update(Request $request){
        $detail = General_operation_report_detail::find($request->id_detail);
        $detail->item_name = $request->_name;
        $detail->description = $request->_description;
        $detail->uom = $request->_uom;
        $detail->category = $request->_category;
        $detail->updated_by = Auth::user()->username;

        if($detail->save()){
            return redirect()->back()->with('success', 'Data updated');
        } else {
            return redirect()->back()->with('error', 'Data failed to be save');
        }
    }

    function setting_delete($id){
        if(General_operation_report_detail::find($id)->delete()){
            return redirect()->back()->with('delete', 'Data deleted');
        } else {
            return redirect()->back()->with('error', 'Data failed to be save');
        }
    }

    function setting_get($id){
        $record = General_operation_report_detail::find($id);

        $_cat = $this->_category;
        return view('operation._setting_edit', compact('record', '_cat'));
    }

    function report_add($id){
        $project = Marketing_project::find($id);
        $_category = $this->_category;

        $setting_report = General_operation_report_setting::where('id_project', $id)->first();

        $detail = General_operation_report_detail::where('id_project', $id)->orderBy('id', 'desc')->get();

        return view('operation.report_add', compact('project', '_category', 'setting_report', 'detail'));
    }

    function upload_file($file, $id_project){
        $filename = explode(".", $file->getClientOriginalName());
        array_pop($filename);
        $filename = str_replace(" ", "_", implode("_", $filename));

        $newFile = $filename."-".date('Y_m_d_H_i_s')."(".$id_project.").".$file->getClientOriginalExtension();
        $dir = str_replace("\\", "/", public_path('media/reports'));
        $upload = $file->move($dir, $newFile);
        return "media/reports/".$newFile;
    }

    function post_report(Request $request, $id){
        $project = Marketing_project::find($id);

        $prefix = strtoupper($project->prefix);

        $last_num = General_operation_report::where("project_id", $id)->orderBy('report_no', 'desc')->first();
        $num = 1;
        if(!empty($last_num)){
            $last_no = explode("/", $last_num);
            $num = intval($last_no[0]) + 1;
        }

        $company = ConfigCompany::find($project->company_id);

        $tag = (!empty($company)) ? strtoupper($company->tag) : strtoupper(Session::get('company_tag'));

        $m = date("m");
        $y = date("y");

        $report_no = sprintf("%03d", $num)."/REPORT/$tag/$prefix/$m/$y";

        $report = new General_operation_report();
        $report->project_id = $id;
        $report->report_no = $report_no;
        $report->report_date = $request->report_date;
        $report->location = $request->location;
        $report->do_in = json_encode($request->js);
        $report->created_by = Auth::user()->username;
        $report->company_id = Session::get('company_id');

        if($report->save()){
            $activity_from = $request->activity_from;
            $activity_to = $request->activity_to;
            $description = $request->description;

            if (!empty($activity_from)) {
                for ($i=0; $i < count($activity_from); $i++) {
                    $act = new General_operation_report_activity();
                    $act->id_report = $report->id;
                    $act->_from = $activity_from[$i];
                    $act->_to = $activity_to[$i];
                    $act->description = $description[$i];
                    $act->company_id = $report->company_id;
                    $act->created_by = Auth::user()->username;
                    $act->save();
                }
            }

            $attach_file = $request->file("attachment_file");
            if(!empty($attach_file)){
                foreach($attach_file as $file){
                    $filename = explode(".", $file->getClientOriginalName());
                    array_pop($filename);
                    $filename = str_replace(" ", "_", implode("_", $filename));

                    $newFile = $filename."-".date('Y_m_d_H_i_s')."(".$report->id.").".$file->getClientOriginalExtension();
                    $hashFile = Hash::make($newFile);
                    $hashFile = str_replace("/", "", $hashFile);
                    $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/reports");
                    if($upload == 1){
                        $attach = new General_operation_report_attach();
                        $attach->id_report = $report->id;
                        $attach->file_hash = $hashFile;
                        $attach->save();
                    }
                }
            }
        }

        return redirect()->route("general.operation.setting", ["type" => "report", "id" => $id]);
    }

    function update_report(Request $request, $id){
        $report = General_operation_report::find($id);
        if (isset($request->appr_notes)) {
            $report->approved_at = date("Y-m-d H:i:s");
            $report->approved_by = Auth::user()->username;
            $report->approved_notes = $request->appr_notes;
            $report->save();
            return redirect()->route("general.operation.setting", ["type" => "report", "id" => $report->project_id]);
        } else {
            $report->report_date = $request->report_date;
            $report->location = $request->location;
            $report->do_in = json_encode($request->js);
            $report->updated_by = Auth::user()->username;

            if($report->save()){
                $activity_from = $request->activity_from;
                $activity_to = $request->activity_to;
                $description = $request->description;

                if (!empty($activity_from)) {
                    General_operation_report_activity::where('id_report', $report->id)->forceDelete();
                    for ($i=0; $i < count($activity_from); $i++) {
                        $act = new General_operation_report_activity();
                        $act->id_report = $report->id;
                        $act->_from = $activity_from[$i];
                        $act->_to = $activity_to[$i];
                        $act->description = $description[$i];
                        $act->company_id = $report->company_id;
                        $act->created_by = Auth::user()->username;
                        $act->save();
                    }
                }

                $attach_file = $request->file("attachment_file");
                if(!empty($attach_file)){
                    foreach($attach_file as $file){
                        $filename = explode(".", $file->getClientOriginalName());
                        array_pop($filename);
                        $filename = str_replace(" ", "_", implode("_", $filename));

                        $newFile = $filename."-".date('Y_m_d_H_i_s')."(".$report->id.").".$file->getClientOriginalExtension();
                        $hashFile = Hash::make($newFile);
                        $hashFile = str_replace("/", "", $hashFile);
                        $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/reports");
                        if($upload == 1){
                            $attach = new General_operation_report_attach();
                            $attach->id_report = $report->id;
                            $attach->file_hash = $hashFile;
                            $attach->save();
                        }
                    }
                }
            }
        }

        return redirect()->back();
    }

    function report_delete($id){
        if(General_operation_report::find($id)->delete()){
            General_operation_report_attach::where("id_report", $id)->delete();
            General_operation_report_activity::where("id_report", $id)->delete();

            return redirect()->back()->with('delete', "Report Deleted");
        } else {
            return redirect()->back()->with('error', "Please contact your system administrator");
        }
    }

    function report_detail($id, Request $request){
        $reports = General_operation_report::find($id);

        $attach = General_operation_report_attach::where("id_report", $id)->get();
        $activity = General_operation_report_activity::where("id_report", $id)->get();

        $project = Marketing_project::find($reports->project_id);
        $_category = $this->_category;

        $setting_report = General_operation_report_setting::where('id_project', $reports->project_id)->first();

        $detail = General_operation_report_detail::where('id_project', $reports->project_id)->orderBy('id', 'desc')->get();

        $file_management = File_Management::all()->pluck('file_name', 'hash_code');

        $type = $request->act;

        $items = General_operation_report_items::where('report_id', $id)->get();

        return view('operation.report_view', compact('project', '_category', 'setting_report', 'detail', 'reports', 'attach', 'activity', 'file_management', 'type', 'items'));
    }

    function attach_delete($id){
        if(General_operation_report_attach::find($id)->delete()){
            return redirect()->back()->with('delete', "Attachment deleted");
        } else {
            return redirect()->back()->with('error', "Please contact your system administrator");
        }
    }

    function item_add(Request $request){
        $report = General_operation_report::find($request->report_id);
        try{
            if(!empty($report)){
                $item = new General_operation_report_items();
                $item->report_id = $request->report_id;
                $item->item_name = $request->item_name;
                $item->qty = $request->item_qty;
                $item->created_by = Auth::user()->username;
                $item->company_id = $report->company_id;
                if($item->save()){
                    $data = [
                        "success" => true,
                        "messages" => "Item saved"
                    ];
                } else {
                    $data = [
                        "success" => false,
                        "messages" => "Item cannot be saved, please contact your system administrator"
                    ];
                }
            } else {
                $data = [
                    "success" => false,
                    "messages" => "Error occured, please contact your system administrator"
                ];
            }
        } catch(Exception $e){
            $data = [
                "success" => false,
                "messages" => "Error occured, please contact your system administrator"
            ];
        }

        return json_encode($data);
    }

    function item_calculate(Request $request){
        $qty_out = $request->qty_out;
        foreach ($request->qty_in as $key => $value) {
            if(!empty($value)){
                $item = General_operation_report_items::find($key);
                if(!empty($item)){
                    $item->in = $value;
                    $item->out = $qty_out[$key];
                    $item->save();
                }
            }
        }

        $data = [
            "success" => true,
            "messages" => "Item saved"
        ];

        return json_encode($data);

    }

    function item_lock(Request $request, $id){
        $report = General_report::find($id);
        if(!empty($report)){
            $lock = General_operation_report_items::where("report_id", $report->id)
                ->update([
                    "lock" => $request->lock
                ]);

            if($lock){
                $data = [
                    "success" => true,
                    "messages" => "Update success"
                ];
            } else {
                $data = [
                    "success" => false,
                    "messages" => "Error occured, please contact your system administrator"
                ];
            }
        } else {
            $data = [
                "success" => false,
                "messages" => "Error occured, please contact your system administrator"
            ];
        }

        return json_encode($data);
    }


    // CRUD TEMPLATE
    function templates(){
        $templates = General_operation_report_templates::where('company_id', Session::get('company_id'))
            ->orderBy('id', 'desc')
            ->get();

        return view('operation.templates', compact('templates'));
    }

    function template_edit($id){
        $template = General_operation_report_templates::find($id);

        $_category = $this->_category;

        $_header = ["logo_1", "title", "logo_2"];

        if(!empty($template->layout_header)){
            $_header = json_decode($template->layout_header, true);
        }

        $title = "";
        $logo_1 = "";
        $logo_2 = "";

        if(!empty($template->header_setting)){
            $header_setting = json_decode($template->header_setting, true);
            $title = $header_setting['title'];
            $logo_1 = $header_setting['logo_1'];
            $logo_2 = $header_setting['logo_2'];
        }

        return view("operation.template_edit", compact("template", "_category", "_header", "title", "logo_1", "logo_2"));
    }

    function template_add(Request $request){
        $template = new General_operation_report_templates();

        $row = [];
        $cbrecord = null;
        $cbactivity = null;
        $cbinventory = null;
        if(isset($request->cb_record) && $request->cb_record == "on"){
            $cbrecord = 1;
        }

        if(isset($request->cb_activity) && $request->cb_activity == "on"){
            $cbactivity = 1;
        }

        if(isset($request->cb_inventory) && $request->cb_inventory == "on"){
            $cbinventory = 1;
        }
        $row = [
            "record" => $cbrecord,
            "activity" => $cbactivity,
            "inventory" => $cbinventory,
        ];

        $template->template_name = $request->template_name;
        $template->settings = json_encode($row);
        $template->created_by = Auth::user()->username;
        $template->company_id = Session::get('company_id');
        $template->save();

        return redirect()->back();
    }

    function template_update_layout(Request $request){
        $success = false;
        $message = "";
        if ($request->ajax()) {
            try {
                $template = General_operation_report_templates::find($request->id_template);
                $template->layout_order = json_encode($request->layout);
                $template->layout_activity = (!isset($request->activity)) ? null : json_encode($request->activity);
                $template->layout_header = json_encode($request->header);
                $setting_header = [
                    "title" => ($request->title == "") ? "FIELD REPORT" : $request->title,
                    "logo_1" => $request->logo_1,
                    "logo_2" => $request->logo_2,
                ];
                $template->header_setting = json_encode($setting_header);
                try {
                    $template->save();
                    $success = true;
                    $message = "Template updated";
                } catch (\Throwable $th) {
                    $success = false;
                    $message = $th->getMessage();
                }
            } catch (\Throwable $th) {
                $success = false;
                $message = $th->getMessage();
            }
        }

        $data = [
            "success" => $success,
            "message" => $message
        ];

        return json_encode($data);
    }

    function print($id){
        $report = General_operation_report::find($id);
        $project = Marketing_project::find($report->project_id);
        $setting = General_operation_report_setting::where("id_project", $report->project_id)->first();
        $template = General_operation_report_templates::find($setting->id_template);

        // get do_in
        $doin = (!empty($report->do_in)) ? json_decode($report->do_in, true) : [];

        // reports data
        $details = General_operation_report_detail::where("id_project", $report->project_id)->get();
        $rowdetails = [];
        foreach($details as $item){
            $row = [];
            $row['description'] = $item->description;
            $row['item_name'] = $item->item_name;
            $row['uom'] = $item->uom;
            $row['data'] = [];

            if(isset($doin[$item->category])){
                $datacat = $doin[$item->category];
                $valrow = [];
                foreach($datacat as $cakey => $ca){
                    if(isset($ca[$item->id])){
                        $valrow[$cakey] = $ca[$item->id];
                    }
                }
                $row['data'] = $valrow;
            }

            $rowdetails[$item->category][$item->id] = $row;
        }

        $activity = General_operation_report_activity::where('id_report', $id)->get();
        $inventory = General_operation_report_items::where("report_id", $id)->get();
        $attachments = General_operation_report_attach::where("id_report", $id)->get();
        $attach_hash = $attachments->pluck('file_hash');
        $file = File_Management::whereIn('hash_code', $attach_hash)->get();

        $layout_order = ["Record", "Activity", "Inventory"];
        $category = $this->_category;

        if(!empty($template)){
            $layout_order = json_decode($template->layout_order, true);
            if(!empty($template->layout_activity)){
                $act = json_decode($template->layout_activity, true);
                $actrow = [];
                foreach ($act as $key => $value) {
                    if(isset($category[$value])){
                        $actrow[$value] = $category[$value];
                    }
                }
                $category = $actrow;
            }
        }

        $_header = ["logo_1", "title", "logo_2"];

        if(!empty($template->layout_header)){
            $_header = json_decode($template->layout_header, true);
        }

        $title = "";
        $logo_1 = "";
        $logo_2 = "";

        if(!empty($template->header_setting)){
            $header_setting = json_decode($template->header_setting, true);
            $title = $header_setting['title'];
            $logo_1 = $header_setting['logo_1'];
            $logo_2 = $header_setting['logo_2'];
        }

        return view("operation.print", compact("_header", "title", "logo_1", "logo_2", "report", "project", "activity", "file", "inventory", "setting", "template", "layout_order", "category", "rowdetails"));
    }
}
