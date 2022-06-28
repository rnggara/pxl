<?php

namespace App\Http\Controllers;

use App\Helpers\FileManagement;
use App\Models\ConfigCompany;
use App\Models\File_Management;
use App\Models\Marketing_c_prognosis;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Marketing_project;
use App\Models\Marketing_project_attachment;
use App\Models\Marketing_clients;
use App\Models\Te_pd;
use App\Models\Te_pd_category;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;

class MarketingProjectsController extends Controller
{
    private $step = array(
        'Non-Disclosure Agreement',
        'Document Request Letter',
        'Meeting',
        'Initial Engagement',
        'Arrangement',
    );

    function encryptID($id)
    {
        $data   = base64_encode($id);
        $output = urlencode($data);
        return $output;
    }

    function decryptID($id)
    {
        $data   = urldecode($id);
        $output = base64_decode($data);
        return $output;
    }

    function change_status($type, $id){
        $project = Marketing_project::find($id);
        if ($type == "delete") {
            $view = "fail";
            $project->view = $view;
            $project->save();
        } elseif($type == "done") {
            $view = "done";
            $project->view = $view;
            $project->save();
        } else {
            $project->view = null;
            $project->deleted_at = null;
            $project->save();
        }

        return redirect()->back();
    }


    public function indexProjects($view=null){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $arrCurrency = array('IDR' => 'Indonesian Rupiah',
            'USD' => 'American Dollar',
            'SGD' => 'Singapore Dollar',
            'AUD' => 'Australian Dollar',
            'EUR' => 'Euro',
            'GBP' => 'Great Britain Pondsterling',
            'JPY' => 'Japanese Yen',
            'CNY' => 'China Yuan'
        );
        $clients = Marketing_clients::whereIn('company_id', $id_companies)->get();
        $data_client = array();
        foreach ($clients as $item){
            $data_client[$item->id] = $item;
        }

        $users = User::where('company_id', Session::get('company_id'))
            ->get();
        $user_name = [];

        foreach ($users as $user){
            $user_name[$user->id] = $user->name;
        }
        $whereStatus = " 1";
        if ($view!=null){
            $param = base64_decode($view);
            if($param == "done"){
                $whereStatus = " (view = 'done')";
            } else {
                $whereStatus = " view = 'fail'";
            }
            $projects = Marketing_project::whereIn('company_id', $id_companies)
                ->whereRaw($whereStatus)
                ->orderBy('id', 'desc')
                ->get();
            $projectssales = Marketing_project::where('category','sales')
                ->whereIn('company_id', $id_companies)
                ->whereRaw($whereStatus)
                ->orderBy('id', 'desc')
                ->get();
            $projectscost = Marketing_project::where('category','cost')
                ->whereIn('company_id', $id_companies)
                ->whereRaw($whereStatus)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $projects = Marketing_project::whereIn('company_id', $id_companies)
                ->whereNull('view')
                ->orderBy('id', 'desc')
                ->get();
            $projectssales = Marketing_project::where('category','sales')
                ->whereIn('company_id', $id_companies)
                ->whereNull('view')
                ->orderBy('id', 'desc')
                ->get();
            $projectscost = Marketing_project::where('category','cost')
                ->whereIn('company_id', $id_companies)
                ->whereNull('view')
                ->orderBy('id', 'desc')
                ->get();
        }

        $cd_max = Marketing_project::max('id');
//        $associates = Marketing_projects_associates::groupBy('id_user', 'id_project')
//            ->get();

        $dataPrognosis = Marketing_c_prognosis::whereIn('company_id', $id_companies)->get();
        $prognosis = array();
        foreach ($dataPrognosis as $item){
            $prognosis[$item->id_project] = $item;
        }
//        dd($data_associates[8]);
//        dd($data_associates[7]['Non-Disclosure Agreement'][0]);

//        dd($this->step);
        return view('projects.index',[
            'projectsall' => $projects,
            'projectscost' => $projectscost,
            'projectssales' => $projectssales,
            'clients' => $clients,
            'arrCurrency' => $arrCurrency,
            'cd_max' => $cd_max,
            'view' => $view,
            'user_name' => $user_name,
            'users' => $users,
            'prognosis' => $prognosis,
            'data_client' => $data_client
        ]);
    }

    function view($id){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $arrCurrency = array('IDR' => 'Indonesian Rupiah',
            'USD' => 'American Dollar',
            'SGD' => 'Singapore Dollar',
            'AUD' => 'Australian Dollar',
            'EUR' => 'Euro',
            'GBP' => 'Great Britain Pondsterling',
            'JPY' => 'Japanese Yen',
            'CNY' => 'China Yuan'
        );
        $clients = Marketing_clients::whereIn('company_id', $id_companies)->get();

        $users = User::where('company_id', Session::get('company_id'))
            ->get();
        $user_name = [];

        foreach ($users as $user){
            $user_name[$user->id] = $user->name;
        }
        $prj = Marketing_project::find($id);

        return view('projects.modal', [
            'prj' => $prj,
            'clients' => $clients,
            'arrCurrency' => $arrCurrency,
            'user_name' => $user_name,
            'users' => $users,
        ]);
    }

    function edit_partner(Request $request){
//        dd($request);
        $leads = Marketing_project::find($request->id_project);
        $leads->partner = json_encode($request->partner);
        $leads->save();
//        if ($leads->save()){
        return redirect()->back();
//        }
    }

    function deleteAssoc($id_project,$id_user){
        DB::table('marketing_projects_associates')->where('id_project', $id_project)
            ->where('id_user', $id_user)
            ->delete();
        return redirect()->back();

    }

    function submit_fee(Request $request){
//        dd($request);
        $id_users = $request->id_user;
        $fee_type = $request->fee_type;
        $fee_amount_detail = $request->fee_amount_detail;
        $fee_amount = $request->fee_amount;
        $total_amount = 0;
        foreach ($id_users as $key => $val){
            $total_amount += $fee_amount[$key];
            Marketing_projects_associates::where('id_user', $val)
                ->where('id_project', $request->id_project)
                ->update([
                    'fee_type' => $fee_type[$key],
                    'percent' => $fee_amount_detail[$key],
                    'fee_amount' => $fee_amount[$key],
                ]);
        }
        Marketing_project::where('id', $request->id_project)
            ->update([
                'total_fee' => $total_amount,
            ]);

        return redirect()->back();
    }

    function add_contributors(Request $request)
    {
        if (isset($request->edit)){
            $arr_user = $request->id_user;
            $arr_jobdesc = $request->job_desc;
            for ($i=0; $i<count($request->id_user); $i++){
                Marketing_projects_associates::where('id_user', $arr_user[$i])
                    ->where('id_project', $request->id_project)
                    ->update([
                        'job_desc' => $arr_jobdesc[$i]
                    ]);
            }
        } else {
            $cekuser = Marketing_projects_associates::where('id_user', $request->user)
                ->where('id_project', $request->id_project)
                ->get();
            $countcekuser = count($cekuser);

            if ($countcekuser>0){
                Marketing_projects_associates::where('id_user', $request->user)
                    ->where('id_project', $request->id_project)
                    ->update([
                        'job_desc' => $request->job_desc
                    ]);
            } else {
                $assoc = new Marketing_projects_associates();
                $assoc->id_project = $request->id_project;
                $assoc->id_user = $request->user;
                $assoc->job_desc = $request->job_desc;
                $assoc->company_id = Session::get('company_id');
                $assoc->save();
            }

        }

        return redirect()->back();
    }

    public function store(Request $request){
        $this->validate($request,[
            'prj_code' => 'required',
            'prj_name' => 'required',
            'prefix' => 'required',
            'category' => 'required',
            'prj_value' => 'required',
            // 'client' => 'required',
            'prj_start' => 'required',
            'prj_end' => 'required',
            'currency' => 'required',
            'address' => 'required',
            'quotation' => 'required',
            'agreement' => 'required',
            'agreement_title' => 'required',
            'transport' => 'required',
            'taxi' => 'required',
            'rent' => 'required',
            'airtax' => 'required',
        ]);
        $projects = new Marketing_project();

        $uploaddir = public_path('marketing\\uploads');

        if ($request->file('wo_attach')){
            $wo_attachInput = $request->file('wo_attach');
            $wo_attach   = $request->input('prj_code')."-wo_attach.".$wo_attachInput->getClientOriginalExtension();
            $wo_attachInput->move($uploaddir,$wo_attach);

            $projects->wo_attach = $wo_attach;
        }

        $projects->prj_code = $request['prj_code'];
        $projects->prj_name = $request['prj_name'];
        $projects->id_client = $request['client'];
        $projects->value = $request['prj_value'];
        $projects->agreement_number = $request['agreement'];
        $projects->agreement_title = $request['agreement_title'];
        $projects->prefix = $request['prefix'];
        $projects->address = $request['address'];
        $projects->currency = $request['currency'];
        $projects->category = $request['category'];
        $projects->type = $request['type'];
        $projects->transport = $request['transport'];
        $projects->taxi = $request['taxi'];
        $projects->rent = $request['rent'];
        $projects->airtax = $request['airtax'];
        $projects->start_time = $request['prj_start'];
        $projects->longitude = $request['longitude'];
        $projects->latitude = $request['latitude'];
        $projects->end_time = $request['prj_end'];
        $projects->company_id = \Session::get('company_id');
        $projects->save();

        return redirect()->route('marketing.project');

    }

    public function update(Request $request){
        $this->validate($request,[
            'prj_code' => 'required',
            'prj_name' => 'required',
            'prefix' => 'required',
            'category' => 'required',
            'prj_value' => 'required',
            'client' => 'required',
            'prj_start' => 'required',
            'prj_end' => 'required',
            'currency' => 'required',
            'address' => 'required',
            'quotation' => 'required',
            'agreement' => 'required',
            'agreement_title' => 'required',
            'transport' => 'required',
            'taxi' => 'required',
            'rent' => 'required',
            'airtax' => 'required',
        ]);

        $uploaddir = public_path('marketing\\uploads');

        if ($request->hasFile('wo_attach')) {
            $wo_attachInput = $request->file('wo_attach');
            $wo_attach = $request->input('prj_code') . "-wo_attach." . $wo_attachInput->getClientOriginalExtension();
            $wo_attachInput->move($uploaddir, $wo_attach);
        }

        Marketing_project::where('id',$request['id'])
            ->update([
                'wo_attach' => (isset($wo_attach)) ? $wo_attach:'',
                'prj_code' =>$request['prj_code'],
                'prj_name' => $request['prj_name'],
                'id_client' => $request['client'],
                'value' => $request['prj_value'],
                'agreement_number' => $request['agreement'],
                'agreement_title' => $request['agreement_title'],
                'prefix' => $request['prefix'],
                'address' => $request['address'],
                'longitude' => $request['longitude'],
                'latitude' => $request['latitude'],
                'currency' => $request['currency'],
                'category' => $request['category'],
                'transport' => $request['transport'],
                'taxi' => $request['taxi'],
                'rent' => $request['rent'],
                'airtax' => $request['airtax'],
                'start_time' => $request['prj_start'],
                'end_time' => $request['prj_end'],
        ]);
        return redirect()->route('marketing.project');
    }

    function update_category(Request $request){
        $project = Marketing_project::find($request->id_project);
        $project->type = $request->category;
        $project->save();

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
            $lead_files = new Marketing_projects_files();
            $lead_files->id_project = $request->id_project;
            $lead_files->id_step = $request->id_step;
            $lead_files->file_code = $hashFile;
            $lead_files->type = $request->type;
            $lead_files->save();

            return redirect()->back();
        }
    }

    function files_delete($id){
        Marketing_projects_files::find($id)->delete();
        $data['error'] = 0;
        return json_encode($data);
    }

    /*MEETING FUNCTIONS*/
    function meetings_create(Request $request){
//        dd($request);
        $meeting = new Marketing_projects_meeting();
        $meeting->id_project = $request->id_project;
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
//        dd($request);
        $file = $request->file('file');
        $filename = explode(".", $file->getClientOriginalName());
        array_pop($filename);
        $filename = str_replace(" ", "_", implode("_", $filename));

        $newFile = $filename."-".date('Y_m_d_H_i_s')."(".$request->id_leads.").".$file->getClientOriginalExtension();
        $hashFile = Hash::make($newFile);
        $hashFile = str_replace("/", "", $hashFile);
        $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\lead");
        if ($upload == 1){
            $meeting = Marketing_projects_meeting::find($request->id_meeting);
            $meeting->file_mom = $hashFile;
            $meeting->save();

            return redirect()->back();
        }
    }

    function meetings_get($id){
        $meeting = Marketing_projects_meeting::where('id_project', $id)->get();

        return json_encode($meeting);
    }

    /*OL FUNCTIONS*/
    function ol_create(Request $request){
        $ol = new Marketing_projects_ol();
        $ol->title = $request->title;
        $ol->notes = $request->ol;
        $ol->id_project = $request->id_project;
        $ol->id_step = $request->id_step;
        $ol->created_by = Auth::user()->username;
        $ol->save();
        return redirect()->back();
    }

    function ol_get($id){
        $ol = Marketing_projects_ol::find($id);

        return json_encode($ol);
    }

    function ol_delete($id){
        Marketing_projects_ol::find($id)->delete();
        return redirect()->back();
    }

    function ol_update(Request $request){
        $ol = Marketing_projects_ol::find($request->ol_id);
        $ol->title = $request->title;
        $ol->notes = $request->ol;
        $ol->save();
        return redirect()->back();
    }

    /*TT FUNCTIONS*/
    function tt_create(Request $request){
        $tt = new Marketing_projects_tt();
        $tt->title = $request->title;
        $tt->notes = $request->notes;
        $tt->id_project = $request->id_project;
        $tt->id_step = $request->id_step;
        $tt->due_date = $request->due_date;
        $tt->due_time = $request->due_time;
        $tt->status = 0;
        $tt->created_by = Auth::user()->username;
        $tt->save();
        return redirect()->back();
    }

    function tt_follow($id){
        $tt = Marketing_projects_tt::find($id);
        if ($tt->status == 0){
            $tt->status = 1;
        } else {
            $tt->status = 0;
        }

        $tt->save();

        return json_encode($tt->status);
    }

    function tt_delete($id){
        Marketing_projects_tt::find($id)->delete();
        return redirect()->back();
    }

    function attachment($id){
        $project = Marketing_project::find($id);

        $files = Marketing_project_attachment::where('id_project', $id)->get();

        return view('projects.attachment', [
            "project" => $project,
            "files" => $files
        ]);
    }

    function add_attachment(Request $request){
        $file = $request->file('_file');
        $filename = explode(".", $file->getClientOriginalName());
        array_pop($filename);
        $filename = str_replace(" ", "_", implode("_", $filename));

        $newFile = $filename."-".date('Y_m_d_H_i_s')."(".$request->id.").".$file->getClientOriginalExtension();
        $hashFile = Hash::make($newFile);
        $hashFile = str_replace("/", "", $hashFile);
        $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\projects\attachment");
        if ($upload == 1){
            $newFile = new Marketing_project_attachment();
            $newFile->id_project = $request->id_project;
            $newFile->document_name = $request->doc_name;
            $newFile->type = $request->type;
            $newFile->file_hash = $hashFile;
            $newFile->created_by = Auth::user()->username;
            $newFile->company_id = Session::get('company_id');
            $newFile->save();
        }
        return redirect()->back();
    }

    function delete_attachment($id){
        $attach = Marketing_project_attachment::find($id);
        if (!empty($attach)) {
            $attach->delete();
        }

        return redirect()->back();
    }

    function equipments($id){
        $prj = Marketing_project::find($id);
        $id_companies[] = $prj->company_id;
        $pd = [];
        if(!empty($prj->list)){
            $list = json_decode($prj->list);
            $ids = [];
            if(!empty($list)){
                foreach($list as $item){
                    $ids[] = $item->id;
                }

                $pd = Te_pd::whereIn('id', $ids)->get();
            }
        }


        $pd_category = Te_pd_category::withTrashed()->get()->pluck('category_name', 'id');


        return view('projects.equipment', [
            "project" => $prj,
            "pd" => $pd,
            'category' => $pd_category
        ]);
    }

    function get_equipments($id){
        $prj = Marketing_project::find($id);
        $id_companies[] = $prj->company_id;
        $list = (empty($prj->list)) ? [] : json_decode($prj->list);
        $ids = [];
        if(!empty($list)){
            foreach($list as $item){
                $ids[] = $item->id;
            }
        }

        $configCompany = ConfigCompany::find($prj->company_id);
        if(!empty($configCompany->id_parent)){
            $id_companies[] = $configCompany->id_parent;
        } else {
            $companyChild = ConfigCompany::select("id")
                ->where('id_parent', $configCompany->id)->get();
            foreach ($companyChild as $key => $value) {
                $id_companies[] = $value->id;
            }
        }

        $id_companies = array_unique($id_companies);

        $pd_category = Te_pd_category::withTrashed()->get()->pluck('category_name', 'id');

        $pd = Te_pd::whereIn('company_id', $id_companies)->get();
        $row = [];
        foreach($pd as $i => $item){
            $checked = (in_array($item->id, $ids)) ? "checked" : "";
            $col = [];
            $col['ck'] = "<input type='checkbox' onclick='add_to_list(this)' $checked class='ck_' value='".$item->id."'>";
            $col['name'] = $item->project_name;
            $col['category'] = (isset($pd_category[$item->category])) ? $pd_category[$item->category] : "N/A";
            $row[] = $col;
        }

        $result = array(
            "data" => $row
        );


        return json_encode($result);
    }

    function save_equipments(Request $request){

        $project = Marketing_project::find($request->prj);
        $list = $request->list;

        if(empty($list)){
            $project->list = null;
        } else {
            $project->list = json_encode($list);
        }

        $success = false;
        if($project->save()){
            $success = true;
        }

        $result = array(
            'success' => $success
        );

        return json_encode($result);
    }

}
