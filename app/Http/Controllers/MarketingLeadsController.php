<?php

namespace App\Http\Controllers;

use App\Models\File_Management;
use App\Models\Finance_coa_history;
use App\Models\Marketing_clients;
use App\Models\Marketing_lead_contracts;
use App\Models\Marketing_lead_files;
use App\Models\Marketing_lead_meeting;
use App\Models\Marketing_lead_notes;
use App\Models\Marketing_lead_tasks;
use App\Models\Marketing_leads;
use App\Models\Marketing_leads_associates;
use App\Models\Marketing_leads_category;
use App\Models\Marketing_project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\This;
use Session;
use App\Helpers\FileManagement;

class MarketingLeadsController extends Controller
{

    private $step = array(
        'nda' => array(
            'title' => 'Non-Disclosure Agreement',
            'route' => 'nda',
        ),
        'drl' => array(
            'title' => 'Document Request Letter',
            'durations' => '7',
            'route' => 'drl',
            'isDocument' => true,
        ),
        'meeting' => array(
            'title' => 'Meeting',
            'durations' => '14',
            'route' => 'meeting',
            'isMeeting' => true,
        ),
        'wo' => array(
            'title' => 'Initial Engagement',
            'type' => array('Offering Letter', 'MoU', 'Mandate'),
            'durations' => '7',
            'route' => 'wo',
        ),
        'agreement' => array(
            'title' => 'Arrangement',
            'type' => array('PKS (Perjanjian Kerjasama)', 'PJH (Perjanjian Jasa Hukum)'),
            'durations' => '7',
            'route' => 'agreement',
        )
    );

    function index(){
        $id_user = array();
        array_push($id_user, Auth::id());

        $leads = Marketing_leads::where('company_id', Session::get('company_id'))
            ->get();
        $clients = Marketing_clients::all();
        $data = [];
        foreach ($clients as $client){
            $data['client_name'][$client->id] = $client->company_name;
            $data['pic'][$client->id] = $client->pic;
            $data['pic_number'][$client->id] = $client->pic_number;
        }
        $users = User::where('company_id', Session::get('company_id'))->get();
        $category = Marketing_leads_category::all();
        $datacategory = [];
        $datauser = [];
        foreach ($category as $value){
            $datacategory[$value->id]['category_name'] = $value->category_name." [".$value->category_type." Category]";
        }

        foreach ($users as $value){
            $datauser['username'][$value->id] = $value->username;
        }

        $associates = Marketing_leads_associates::where('company_id', Session::get('company_id'))
            ->get();
        $data_associates = array();
        foreach ($associates as $item){
            $data_associates[$item->id_leads][] = $item->id_user;
        }

        return view('leads.index', [
            'clients' => $clients,
            'leads' => $leads,
            'data_client' => $data,
            'users' => $users,
            'leads_category' => $category,
            'data_category' => $datacategory,
            'data_user' => $datauser,
            'data_associates' => $data_associates
        ]);
    }

    public function insertLeadsCategory(Request $request){
        $category = new Marketing_leads_category();
        $category->category_name = $request['category_name'];
        $category->category_type = $request['category_type'];
        $category->created_at = date('Y-m-d H:i:s');
        $category->save();
        return redirect() -> route('leads.index');
    }

    function edit_partner(Request $request){
        $leads = Marketing_leads::find($request->id_leads);
        $leads->partner = $request->partner;
        if ($leads->save()){
            return redirect()->back();
        }
    }

    public function approveLeads($id){
        $lead = Marketing_leads::where('id', $id)->first();
        $acronym = '';
        if ($lead->leads_name == trim($lead->leads_name) && strpos($lead->leads_name, ' ') !== false) {
            $words = explode(" ", $lead->leads_name);
            foreach ($words as $w) {
                $acronym .= $w[0];
            }
        } else {
            $acronym = substr($lead->leads_name, 0, 3);
        }

        $prefix = strtoupper($acronym);
//        dd($prefix);
        $max_prj_code = Marketing_project::max('prj_code');
        $prj_code = $max_prj_code+1;

        $prj = new Marketing_project();
        $prj->prj_code = $prj_code;
        $prj->prj_name = $lead->leads_name;
        $prj->id_client = $lead->id_client;
        $prj->prefix = $prefix;
        $prj->category = 'cost';
        $prj->company_id = Session::get('company_id');
        if ($prj->save()){
            Marketing_leads::where('id',$id)
                ->update([
                    'approved_by' => Auth::user()->username,
                    'approved_at' => date('Y-m-d H:i:s')
                ]);
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
//        dd($lead);
    }

    function add(Request $request){
        $leads = new Marketing_leads();
        $leads->leads_name = $request->leads_name;
        $leads->partner = $request->partner;
        $leads->description = $request->description;
        $leads->id_client = $request->client;
        $leads->id_category = $request->category;
        $leads->progress = 0;
        $leads->company_id = Session::get('company_id');
        $leads->created_by = Auth::user()->username;
        $leads->updated_by = Auth::user()->username;
        $leads->save();

        foreach ($this->step as $key => $item) {
            $nAssociates = new Marketing_leads_associates();
            $nAssociates->id_leads = $leads->id;
            $nAssociates->type = $key;
            $nAssociates->created_by = Auth::user()->username;
            $nAssociates->company_id = Session::get('company_id');
            $nAssociates->save();
        }

        return redirect()->route('leads.index');
    }

    public function get_categories(){

        $categories = Marketing_leads_category::all();
        $data = [];
        $val = [];
        foreach ($categories as $item){
            $data['id'] = $item->id;
            $data['text'] = $item->category_name.' ['.$item->category_type.']';
            $val[] = $data;
        }

        $response = [
            'results' => $val,
            'pagination' => ["more" => true]
        ];

        return json_encode($response);
    }

    function edit(Request $request){
        $leads = Marketing_leads::find($request->id_leads);
        $leads->leads_name = $request->leads_name;
        $leads->description = $request->description;
        $leads->id_client = $request->client;
        $leads->id_category = $request->category;
        $leads->updated_by = Auth::user()->username;
        $leads->save();

        return redirect()->route('leads.index');
    }

    function delete($id){
        $leads = Marketing_leads::find($id)->delete();
        $files = Marketing_lead_files::where('id_lead', $id)->delete();
        $meeting = Marketing_lead_meeting::where('id_lead', $id)->delete();
        $notes = Marketing_lead_notes::where('id_leads', $id)->delete();
        $tasks = Marketing_lead_tasks::where('id_lead', $id)->delete();

        $data['error'] = 0;

        return json_encode($data);
    }

    function view($id){
        $lead = Marketing_leads::where('id', $id)->first();
        $files = Marketing_lead_files::where('id_lead', $id)
            ->orderBy('created_at','DESC')
            ->limit(20)
            ->get();
        $meeting = Marketing_lead_meeting::where('id_lead', $id)
            ->orderBy('start_time','ASC')
            ->limit(20)
            ->get();
        $notes = Marketing_lead_notes::where('id_leads', $id)
            ->orderBy('created_at','DESC')
            ->limit(20)
            ->get();
        $tasks = Marketing_lead_tasks::where('id_lead', $id)
            ->orderBy('due_date','ASC')
            ->limit(20)
            ->get();

        $contracts = Marketing_lead_contracts::where('id_lead', $id)
            ->orderBy('created_at', 'DESC')
            ->limit(20)
            ->get();

        $data_act = [];
        $activity = [];

        foreach ($notes as $n){
            $data_act['type'] = "notes";
            $data_act['date'] = date('Y-m-d', strtotime($n->created_at));
            $data_act['data'] = Marketing_lead_notes::find($n->id);
            $activity[] = (object) $data_act;
        }

        foreach ($meeting as $meet){
            $data_act['type'] = "meeting";
            $data_act['date'] = date("Y-m-d", strtotime($meet->start_time));
            $data_act['data'] = Marketing_lead_meeting::find($meet->id);
            $activity[] = (object) $data_act;
        }

        foreach ($tasks as $task){
            $data_act['type'] = "tasks";
            $data_act['date'] = date('Y-m-d', strtotime($task->due_date));
            $data_act['data'] = Marketing_lead_tasks::find($task->id);
            $activity[] = (object) $data_act;
        }

        foreach ($contracts as $contract){
            $data_act['type'] = "contracts";
            $data_act['date'] = date('Y-m-d', strtotime($contract->created_at));
            $data_act['data'] = Marketing_lead_contracts::find($contract->id);
            $activity[] = (object) $data_act;
        }

        usort($activity, function ($a, $b) {
            return strcmp($b->date, $a->date);
        });

        // Files
        $mimes = new \Mimey\MimeTypes;

        $_file = File_Management::all();
        $file_name = [];
        $data_file = [];
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
            $file_name[$item->hash_code] = $data_file;
        }

        $clients = Marketing_clients::all();
        $data = [];
        foreach ($clients as $client){
            $data['client_name'][$client->id] = $client->company_name;
            $data['address'][$client->id] = $client->address;
            $data['phone'][$client->id] = $client->phone_1;
            $data['pic'][$client->id] = $client->pic;
            $data['pic_number'][$client->id] = $client->pic_number;
        }

        $users = User::where('company_id', Session::get('company_id'))
            ->get();
        $user_name = [];
        foreach ($users as $user){
            $user_name[$user->id] = $user->username;
        }

        $associates = Marketing_leads_associates::where('id_leads', $lead->id)->get();
        $data_associates = array();
        foreach ($associates as $item){
            $data_associates[$item->type] = $item;
        }

        $progress = array(
            'mom' => array(
                'val' => '15',
                'message' => 'This is the first requirement for the lead to advance. Please upload the Minutes of Meeting file here.'
            ),
            'nda' => array(
                'val' => '15',
                'message' => 'After you received the signed Non-Disclosure Agreement, please upload it here to advance to the next step.'
            ),
            'spd' => array(
                'val' => '15',
                'message' => 'After you received the signed Surat Permintaan Data, please upload it here to advance to the next step.'
            ),
            'ol' => array(
                'val' => '15',
                'message' => 'After you received the signed Offering Letter, please upload it here to advance to the next step.'
            ),
            'mou' => array(
                'val' => '20',
                'message' => 'After you received the signed MoU, please upload it here to advance to the next step.'
            ),
            'pks' => array(
                'val' => '20',
                'message' => 'Please upload the signed PKS/PJH to complete this Lead.'
            ));

        foreach ($this->step as $key => $item) {
            $fAssociates = Marketing_leads_associates::where('type', $key)
                ->where('id_leads', $id)
                ->first();
            if (empty($fAssociates)){
                $nAssociates = new Marketing_leads_associates();
                $nAssociates->id_leads = $id;
                $nAssociates->type = $key;
                $nAssociates->created_by = Auth::user()->username;
                $nAssociates->company_id = Session::get('company_id');
                $nAssociates->save();
            }
        }

        return view('leads.view', [
            'lead' => $lead,
            'files' => $files,
            'meetings' => $meeting,
            'notes' => $notes,
            'tasks' => $tasks,
            'contracts' => $contracts,
            'data_client' => $data,
            'data_file' => $file_name,
            'activity' => $activity,
            'users' => $users,
            'user_name' => $user_name,
            'progress_tab' => $progress,
            'step' => $this->step,
            'associates' => $data_associates
        ]);
    }

    public function addNotes(Request $request){
        if (isset($request['edit'])){
            Marketing_lead_notes::where('id',$request['edit'])
                ->update([
                    'notes' => $request['notes'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } else {
            $notes = new Marketing_lead_notes();
            $notes->id_leads = $request['id_lead'];
            $notes->notes = $request['notes'];
            $notes->created_at = date('Y-m-d H:i:s');
            $notes->created_by = Auth::user()->username;
            $notes->company_id = \Session::get('company_id');
            $notes->save();
        }
        return redirect()->route('leads.view',['id' => $request['id_lead']]);
    }

    public function deleteNotes($id,$id_lead){
        Marketing_lead_notes::where('id',$id)->delete();
        return redirect()->route('leads.view',['id' => $id_lead]);
    }
    public function addMeetings(Request $request){
        $search = ['{"value":','}'];
        $attendees = str_replace($search,'',$request['attendees']);
        $meeting_time = $request['start_date'].' '.$request['start_time'].':00';

        if (isset($request['edit'])){
            Marketing_lead_meeting::where('id',$request['edit'])
                ->update([
                    'subject' => $request['subject'],
                    'description' => $request['description'],
                    'attendees' => $attendees,
                    'start_time' => $meeting_time,
                    'duration' => $request['duration'],
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->username,
                ]);
        }else{
            $meetings = new Marketing_lead_meeting();
            $meetings->id_lead = $request['id_lead'];
            $meetings->subject = $request['subject'];
            $meetings->description = $request['description'];
            $meetings->attendees = $attendees;
            $meetings->start_time = $meeting_time;
            $meetings->duration = $request['duration'];
            $meetings->company_id = \Session::get('company_id');
            $meetings->created_at = date('Y-m-d H:i:s');
            $meetings->created_by = Auth::user()->username;
            $meetings->save();
        }

        return redirect()->route('leads.view',['id' => $request['id_lead']]);
    }

    public function deleteMeetings($id,$id_meeting){
        Marketing_lead_meeting::where('id',$id_meeting)->delete();
        return redirect()->route('leads.view',['id' => $id]);
    }

    function update_progress(Request $request){
        $leads = Marketing_leads::find($request->id);
        $leads->progress = $request->progress;
        $leads->updated_by = Auth::user()->username;
        $leads->save();
    }

    function upload_file(Request $request){
        $file = $request->file('file');
        $filename = explode(".", $file->getClientOriginalName());
        array_pop($filename);
        $filename = str_replace(" ", "_", implode("_", $filename));

        $newFile = $filename."-".date('Y_m_d_H_i_s')."(".$request->id_leads.").".$file->getClientOriginalExtension();
        $hashFile = Hash::make($newFile);
        $hashFile = str_replace("/", "", $hashFile);
        $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\lead");
        if ($upload == 1){
            $lead_files = new Marketing_lead_files();
            $lead_files->id_lead = $request->id_leads;
            $lead_files->file_code = $hashFile;
            $lead_files->save();

            return redirect()->route('leads.view', $request->id_leads);
        }
    }

    function delete_file($id){
        Marketing_lead_files::find($id)->delete();
        $data['error'] = 0;
        return json_encode($data);
    }

    function addContracts(Request $request){
        $contract = new Marketing_lead_contracts();
        $contract->id_lead = $request->id_lead;
        $contract->contract_name = $request->contract_name;
        $contract->description = $request->description;
        $contract->value = $request->value;
        $contract->created_by = Auth::user()->username;
        $contract->company_id = Session::get('company_id');

        $contract->save();
        return redirect()->route('leads.view', $request->id_lead);
    }

    function editContracts(Request $request){
        $contract = Marketing_lead_contracts::find($request->edit);
        $contract->contract_name = $request->contract_name;
        $contract->description = $request->description;
        $contract->value = $request->value;
        $contract->updated_by = Auth::user()->username;

        $contract->save();
        return redirect()->route('leads.view', $request->id_lead);
    }

    function editInvContracts(Request $request){
        $contract = Marketing_lead_contracts::find($request->edit);
        $contract->inv_date = $request->inv_date;
        $contract->updated_by = Auth::user()->username;

        $contract->save();
        return redirect()->route('leads.view', $request->id_lead);
    }

    public function deleteContracts($id_lead,$id){
        Marketing_lead_contracts::where('id',$id)->delete();
        return redirect()->route('leads.view',['id' => $id_lead]);
    }

    function add_contributors(Request $request){
        $req = $request->associates;
        foreach ($req as $key => $item){
            if ($req[$key] != null){
                $associates = Marketing_leads_associates::where('id_leads', $request->id_leads)
                    ->where('type', $key)
                    ->first();
                if (empty($associates)){
                    $nAssociates = new Marketing_leads_associates();
                    $nAssociates->id_leads = $request->id_leads;
                    $nAssociates->id_user = $item;
                    $nAssociates->type = $key;
                    $nAssociates->created_by = Auth::user()->username;
                    $nAssociates->company_id = Session::get('company_id');
                    $nAssociates->save();
                } else {
                    Marketing_leads_associates::where('id_leads', $request->id_leads)
                        ->where('type', $key)
                        ->update([
                            'id_user' => $item
                        ]);
                }
            }
        }
        return redirect()->route('leads.view',['id' => $request->id_leads]);
    }

    function upload_progress(Request $request, $type){
//        dd($request);
        $associates = Marketing_leads_associates::where('id_leads', $request->id_leads)
            ->where('type', $type)
            ->first();
        if (!isset($this->step[$type]['isMeeting'])){
            if ($associates->file_draft == null){
                $file = $request->file('file_draft');
                $filename = explode(".", $file->getClientOriginalName());
                array_pop($filename);
                $filename = str_replace(" ", "_", implode("_", $filename));

                $newFile = "(".$type."-draft)".$filename."-".date('Y_m_d_H_i_s')."(".$request->id_leads.").".$file->getClientOriginalExtension();
                $hashFile = Hash::make($newFile);
                $hashFile = str_replace("/", "", $hashFile);
                $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\lead");
                $associates->file_draft = $hashFile;
                $point = 5;
            } elseif ($associates->file_draft != null && $associates->resi == null){
                $file = $request->file('resi_file');
                $filename = explode(".", $file->getClientOriginalName());
                array_pop($filename);
                $filename = str_replace(" ", "_", implode("_", $filename));

                $newFile = "(".$type."-resi)".$filename."-".date('Y_m_d_H_i_s')."(".$request->id_leads.").".$file->getClientOriginalExtension();
                $hashFile = Hash::make($newFile);
                $hashFile = str_replace("/", "", $hashFile);
                $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\lead");
                $associates->resi_file = $hashFile;
                $associates->resi = $request->resi;
                $associates->amount = $request->amount;
                $associates->resi_date = date('Y-m-d H:i:s');
                $this->addToGJ($hashFile, date('Y-m-d'), 'Receipt of paper delivery ['.$request->resi.'] amount ' . number_format($request->amount));
                $point = 5;
            } else {
                $file = $request->file('file');
                $filename = explode(".", $file->getClientOriginalName());
                array_pop($filename);
                $filename = str_replace(" ", "_", implode("_", $filename));

                $newFile = "(".$type."-signed)".$filename."-".date('Y_m_d_H_i_s')."(".$request->id_leads.").".$file->getClientOriginalExtension();
                $hashFile = Hash::make($newFile);
                $hashFile = str_replace("/", "", $hashFile);
                $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\lead");
                $associates->file = $hashFile;
                $associates->file_date = date("Y-m-d H:i:s");
                $point = 10;
                if (isset($this->step[$type]['isDocument'])){
                    //store to client
//                    $leads = Marketing_leads::find($request->id_leads);
                }
            }
        } else {
            // create meeting
            if ($request->type == "internal" || $request->type == "eksternal"){
                $search = ['{"value":','}'];
                $attendees = str_replace($search,'',$request['attendees']);
                $meeting_time = $request['start_date'].' '.$request['start_time'].':00';
                $meetings = new Marketing_lead_meeting();
                $meetings->id_lead = $request['id_leads'];
                $meetings->subject = $request['subject'];
                $meetings->description = $request['description'];
                $meetings->attendees = $attendees;
                $meetings->start_time = $meeting_time;
                $meetings->duration = $request['duration'];
                $meetings->company_id = \Session::get('company_id');
                $meetings->created_at = date('Y-m-d H:i:s');
                $meetings->created_by = Auth::user()->username;
                $meetings->save();
                switch ($request->type){
                    case "internal" :
                        $associates->id_meeting_internal = $meetings->id;
                        break;
                    case "eksternal" :
                        $associates->id_meeting_eksternal = $meetings->id;
                        break;
                }
                $point = 5;
            } else {
                $file = $request->file('file');
                $filename = explode(".", $file->getClientOriginalName());
                array_pop($filename);
                $filename = str_replace(" ", "_", implode("_", $filename));

                switch ($request->type){
                    case "internal_file" :
                        $newFile = "(".$type."-internal-mom)".$filename."-".date('Y_m_d_H_i_s')."(".$request->id_leads.").".$file->getClientOriginalExtension();
                        $hashFile = Hash::make($newFile);
                        $hashFile = str_replace("/", "", $hashFile);
                        $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\lead");
                        $associates->file_draft = $hashFile;
                        break;
                    case "eksternal_file" :
                        $newFile = "(".$type."-eksternal-mom)".$filename."-".date('Y_m_d_H_i_s')."(".$request->id_leads.").".$file->getClientOriginalExtension();
                        $hashFile = Hash::make($newFile);
                        $hashFile = str_replace("/", "", $hashFile);
                        $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\lead");
                        $associates->file = $hashFile;
                        break;
                }
                $point = 5;
            }
        }

        if (isset($request->seltype)){
            $associates->step_type = $request->seltype;
        }

        if ($associates->save()){
            $leads = Marketing_leads::find($request->id_leads);
//            $leads[$request->type] = $hashFile;
            $leads->progress = $leads->progress + intval($point);
            if ($leads->progress >= 100){
                $leads->progress == 100;
            }
            if ($leads->save()){
                if (isset($upload)){
                    $lead_files = new Marketing_lead_files();
                    $lead_files->id_lead = $request->id_leads;
                    $lead_files->file_code = $hashFile;
                    $lead_files->save();
                }

                if ($leads->progress >= 100){
                    $this->approveLeads($leads->id);
                }

                return redirect()->route('leads.view',['id' => $request->id_leads]);
            }
        }
    }

    function index_management(){
        $leads = Marketing_leads::where('company_id', Session::get('company_id'))
            ->get();
        $clients = Marketing_clients::where('company_id', Session::get('company_id'))->get();
        $data = [];
        foreach ($clients as $client){
            $data['client_name'][$client->id] = $client->company_name;
            $data['pic'][$client->id] = $client->pic;
            $data['pic_number'][$client->id] = $client->pic_number;
        }

        $progress = array(
            'mom' => array(
                'val' => '15',
                'message' => 'Waiting MOM',
                'title' => 'Minutes of Meeting'
            ),
            'nda' => array(
                'val' => '15',
                'message' => 'MOM Uploaded',
                'title' => 'Non-Disclosure Agreement'
            ),
            'spd' => array(
                'val' => '15',
                'message' => 'NDA Signed',
                'title' => 'Surat Permintaan Data'
            ),
            'ol' => array(
                'val' => '15',
                'message' => 'SPD Signed',
                'title' => 'Offering Letter'
            ),
            'mou' => array(
                'val' => '20',
                'message' => 'Offering Letter Signed',
                'title' => 'MoU'
            ),
            'pks' => array(
                'val' => '20',
                'message' => 'MOU Signed',
                'title' => 'PKS/PJH'
            ));

        return view('leads.indexmanagement', [
            'clients' => $clients,
            'leads' => $leads,
            'data_client' => $data,
            'progress' => $progress
        ]);
    }

    function addToGJ($hashFile, $date, $description){
        $last = Finance_coa_history::where('company_id', Session::get('company_id'))
            ->orderBy('md5', "desc")
            ->first();
        $hash = $last->md5 + 1;

        $iCoa = new Finance_coa_history();
        $iCoa->md5 = $hash;
//        $iCoa->no_coa = $coa_code;
        $iCoa->coa_date = $date;
//        $iCoa->debit = $de_amount[$key];
        $iCoa->file_hash = $hashFile;
        $iCoa->description = $description;
        $iCoa->created_by = Auth::user()->username;
        $iCoa->company_id = Session::get('company_id');
        $iCoa->save();
    }
}
