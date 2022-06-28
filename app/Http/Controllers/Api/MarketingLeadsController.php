<?php

namespace App\Http\Controllers\Api;

use App\Models\File_Management;
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
use App\Models\Finance_coa_history;
use App\Models\Marketing_projects_associates;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\This;
use Session;
use App\Helpers\FileManagement;

class MarketingLeadsController extends BaseController
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
            'isFinal' => true,
        )
    );

    function index($comp_id){
        $id_user = array();
        array_push($id_user, Auth::id());

        $leads = Marketing_leads::select('marketing_leads.*','client.company_name','client.pic','client.pic_number','category.category_name','category.category_type')
            ->leftJoin('marketing_clients as client', 'client.id','=','marketing_leads.id_client')
            ->leftJoin('marketing_leads_category as category', 'category.id','=','marketing_leads.id_category')
            ->where('marketing_leads.company_id', $comp_id)
            ->get();

        if ($leads){
            return $this->sendResponse($leads, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

    function getClient($comp_id){
        $clients = Marketing_clients::where('company_id', $comp_id)->get();

        if ($clients){
            return $this->sendResponse($clients, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

    function getUsers($comp_id){
        $users = User::where('company_id', $comp_id)->get();
        if ($users){
            return $this->sendResponse($users, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

    function getCategory($comp_id){
        $category = Marketing_leads_category::where('company_id', $comp_id)->get();
        if ($category){
            return $this->sendResponse($category, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

    function addLeads(Request $request){
        $pass = 0;
        $leads = new Marketing_leads();
        $leads->leads_name = $request->leads_name;
        $leads->partner = (isset($request->partner)) ? $request->partner : null;
        $leads->referral = (isset($request->referral)) ? $request->referral : null;
        $leads->description = $request->description;
        $leads->id_client = $request->client;
        $leads->id_category = $request->category;
        $leads->progress = 0;
        $leads->company_id = $request->company_id;
        $leads->created_by = $request->username;

        if ($leads->save()){
            foreach ($this->step as $key => $item) {
                $nAssociates = new Marketing_leads_associates();
                $nAssociates->id_leads = $leads->id;
                $nAssociates->type = $key;
                $nAssociates->created_by = $request->username;
                $nAssociates->company_id = $request->company_id;
                $nAssociates->save();
            }
            $pass = 1;
        }
        if ($pass>0){
            return $this->sendResponse([
                "status"=> 200,
            ],'Success');
        } else {
            return $this->sendError('Failed');
        }
    }


}
