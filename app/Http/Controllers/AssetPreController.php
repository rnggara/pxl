<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App\Models\Asset_po;
use App\Models\Asset_wh;
use App\Models\Division;
use App\Models\Asset_pre;
use App\Models\Asset_item;
use App\Models\General_do;
use App\Models\Finance_coa;
use App\Models\Asset_qty_wh;
use App\Rms\RolesManagement;
use Illuminate\Http\Request;
use App\Helpers\Notification;
use App\Models\Asset_type_po;
use App\Models\ConfigCompany;
use App\Helpers\ActivityConfig;
use App\Models\Asset_po_detail;
use App\Models\Pref_tax_config;
use App\Models\Asset_pre_detail;
use App\Models\Marketing_project;
use App\Models\Preference_config;
use App\Models\Asset_good_receive;
use App\Models\Finance_coa_source;
use App\Models\Procurement_vendor;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AssetPreController extends Controller
{

    function lists($type, $category){
        switch ($type) {
            case 'fr':
                $whereType = " 1 and fr_num is not null";
                $orderBy = "fr_num";
                $whereDate = " (request_at like '".date('Y')."%' or fr_date like '".date('Y')."%')";
                break;
            case 'pre':
                $whereType = " fr_approved_at is not null and (pre_num is not null and fr_num != '')";
                $orderBy = "pre_date";
                $whereDate = " (fr_approved_at like '".date('Y')."%' or pre_date like '".date('Y')."%')";
                break;
            case 'pev':
                $whereType = " pev_num is not null and pre_num != ''";
                $orderBy = "pre_date";
                $whereDate = " (pre_approved_at like '".date('Y')."%' or pev_date like '".date('Y')."%')";
                break;
        }

        switch ($category) {
            case 'waiting':
                if($type == "fr"){
                    $whereCategory = " fr_deliver_times is null and ".$type."_rejected_at is null";
                } else {
                    $whereCategory = " ".$type."_approved_at is null and ".$type."_rejected_at is null";
                }
                break;
            case 'bank':
                if($type == "fr"){
                    $whereCategory = " fr_deliver_times is not null and ".$type."_approved_at is not null";
                } else {
                    $whereCategory = " ".$type."_approved_at is not null";
                }
                break;
            case 'reject':
                $whereCategory = " ".$type."_rejected_at is not null";
                break;
        }

        $do = General_do::withTrashed()->get()->pluck('no_do', 'id');

        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }

        $pre = Asset_pre::whereIn('company_id', $id_companies)
            ->whereRaw($whereType)
            ->whereRaw($whereCategory)
            // ->whereRaw($whereDate)
            ->orderBy('id', 'desc')
            ->get();


        if($type == "fr"){
            $gr = Asset_good_receive::all()->pluck('id', 'po_id');
            $po = Asset_po::all();
            $item_pr = [];
            foreach($po as $ipo){
                $item_pr[$ipo->reference][] = (isset($gr[$ipo->id])) ? 1 : 0;
            }
        }

        $details = Asset_pre_detail::all();

        $qtyDeliver = [];

        $listItems = [];
        foreach ($details as $key => $value) {
            $qty = (!empty($value->qty_buy)) ? $value->qty_buy : $value->qty;
            $listItems[$value->fr_id][] = $qty;
            if(!empty($value->qty_deliver)){
                $qtyDeliver[$value->fr_id][] = $value->qty_deliver;
            }
        }

        $prj = Marketing_project::whereIn('company_id', $id_companies)
            ->withTrashed()
            ->get();

        $listPrj = [];
        foreach ($prj as $key => $value) {
            $listPrj[$value->id] = $value;
        }

        $tagCompany = [];
        $comp = ConfigCompany::all();
        foreach ($comp as $key1 => $val){
            $tagCompany[$val->id] = $val->tag;
        }

        $col = [];
        foreach ($pre as $key => $value) {
            $row = [];
            $row['i'] = $key + 1;
            $row['date'] = date('d F Y', strtotime($value[$type."_date"]));
            switch ($type) {
                case 'fr':
                    $route = route('fr.view', ['id'=>$value->id]);
                    break;
                case 'pre':
                    $route = route('pr.view',['id'=>$value->id]);
                    break;
                case 'pev':
                    $route = route('pe.view', $value->id);
            }
            $paper = "<a href='".$route."' class='btn btn-xs btn-link text-hover-dark-75'><i class='fa fa-search text-primary text-hover-dark-75'></i>".$value[$type."_num"]."</a>";
            $row['paper'] = $paper;
            $row['req_by'] = $value['request_by'];
            $row['division'] = $value['division'];
            $row['project'] = (isset($listPrj[$value['project']])) ? $listPrj[$value['project']]['prj_name'] : "";
            $row['company'] = (isset($tagCompany[$value['company_id']])) ? $tagCompany[$value['company_id']] : "";
            $row['items'] = (isset($listItems[$value->id])) ? array_sum($listItems[$value->id]) : 0;

            $deleteBtn = $delete = "<a href='".route('fr.pr.delete',['id'=>$value->id,'code' =>$type])."' class='btn btn-danger btn-sm btn-icon' title='Delete' onclick='return confirm(\"Are you sure you want to delete?\")'><i class='fa fa-trash'></i></a>";

            if ($type == "fr") {
                $div = "";
                $asset = "";
                $delivery = "";
                if (empty($value['fr_division_approved_at'])) {
                    if (RolesManagement::actionStart('fr', 'approvediv1')) {
                        $div = "<a href='".route('fr.view',['id'=>$value->id,'code'=>base64_encode('div_appr')])."' class='btn btn-link btn-xs'><i class='fa fa-clock'></i>waiting</a>";
                    } else {
                        $div = "waiting";
                    }
                    $asset = "waiting";
                    $delivery = "waiting";
                } elseif(!empty($value['fr_division_approved_at']) && empty($value['fr_approved_at'])){
                    $div = date('d F Y', strtotime($value['fr_division_approved_at']));
                    if (RolesManagement::actionStart('fr', 'approvediv2')) {
                        $asset = "<a href='".route('fr.view',['id'=>$value->id,'code'=>base64_encode('asset_appr')])."' class='btn btn-link btn-xs'><i class='fa fa-clock'></i>waiting</a>";
                    } else {
                        $asset = "waiting";
                    }

                    $delivery = "waiting";
                } elseif(!empty($value['fr_division_approved_at']) && !empty($value['fr_approved_at']) && empty($value['fr_deliver_times'])) {
                    $div = date('d F Y', strtotime($value['fr_division_approved_at']));
                    $asset = date('d F Y', strtotime($value['fr_approved_at']));
                    if(RolesManagement::actionStart('fr', 'approvediv2')) {
                        if(empty($value['pre_num'])){
                            if(empty($value['do_id'])){
                                $delivery = "<a href='".route('fr.create.do',$value->id)."' class='btn btn-link btn-xs'><i class='fa fa-clock'></i>ready to deliver</a>";
                            } else {
                                $delivery = "<a href='".route('do.detail',['id'=>$value->do_id,'type'=>"appr"])."' class='btn btn-link btn-xs'><i class='fa fa-clock'></i>".$do[$value['do_id']]."</a>";
                            }
                        } else {
                            $delivery = "waiting purchase";
                            if(isset($item_pr[$value['pev_num']])){
                                if(count($item_pr[$value['pev_num']]) == array_sum($item_pr[$value['pev_num']])){
                                    $delivery = "<a href='".route('fr.create.do',$value->id)."' class='btn btn-link btn-xs'><i class='fa fa-clock'></i>ready to deliver</a>";
                                }
                            }
                        }
                    } else {
                        if(empty($value['do_id'])){
                            $delivery = "waiting";
                        } else {
                            $delivery = "<a href='".route('do.detail', ['id' => $value['do_id'], 'type' => 'appr'])."'>".$do[$value['do_id']]."</a>";
                        }
                    }

                } else {
                    $div = date('d F Y', strtotime($value['fr_division_approved_at']));
                    $asset = date('d F Y', strtotime($value['fr_approved_at']));
                    $delivery = "Delivered <br> ";
                    $delivery .= (isset($do[$value['do_id']])) ? "<a href='".route('do.detail', ['id' => $value['do_id'], 'type' => 'view'])."'>".$do[$value['do_id']]."</a>" : "";
                }

                if(isset($qtyDeliver[$value->id])){
                    $delivery = "<a href='".route('fr.create.do',$value->id)."' class='btn btn-link btn-xs'><i class='fa fa-clock'></i>items need to deliver</a>";
                }

                $row['div_appr'] = $div;
                $row['asset_appr'] = $asset;
                $row['delivery_status'] = $delivery;
                $row['action'] = (RolesManagement::actionStart('fr', 'delete')) ? $deleteBtn : "";
            } elseif($type == "pre"){
                $row['fr_num'] = $value['fr_num'];
                $dir = "";
                if (empty($value['pre_approved_at'])) {
                    if(RolesManagement::actionStart('pr', 'approvedir')){
                        $dir = '<a href="'.route('pr.view',['id'=>$value->id,'code'=>base64_encode('dir_appr')]).'" class="btn btn-link"><i class="fa fa-clock"></i>waiting</a>';
                    } else {
                        $dir = "waiting";
                    }
                } else {
                    $dir = date('d F Y', strtotime($value['pre_approved_at']));
                }
                $row['dir_appr'] = $dir;
                $row['action'] = (RolesManagement::actionStart('pr', 'delete')) ? $deleteBtn : "";
            } else {
                $row['pre_num'] = $value['pre_num'];
                $input = "waiting";
                $dir_appr = "waiting";
                if(empty($value['pev_date'])){
                    if(RolesManagement::actionStart('pe', 'approvediv1')) {
                        $input = '<a href="'.route('pe.input', $value->id).'" class="text-hover-danger">Input <i class="fa fa-clock"></i></a>';
                    } else {
                        $input = "waiting";
                    }
                } else {
                    $input = date("d F Y", strtotime($value['pev_date']));
                }
                if(!empty($value['pev_date'])){
                    if(empty($value['pev_approved_at'])){
                        if(RolesManagement::actionStart('pe', 'approvedir')) {
                            $dir_appr = '<a href="'.route('pe.dir_appr', $value->id).'" class="text-hover-danger">Waiting <i class="fa fa-clock"></i></a>';
                        } else {
                            $dir_appr = "waiting";
                        }
                    } else {
                        $dir_appr = date("d F Y", strtotime($value['pev_approved_at']));
                    }
                } else {
                    $dir_appr = "waiting";
                }
                $row['input_date'] = $input;
                $row['pre_date'] = date('d F Y', strtotime($value['pre_date']));
                $row['dir_appr'] = $dir_appr;
                $row['action'] = (RolesManagement::actionStart('pe', 'delete')) ? $deleteBtn : "";
            }


            if ($type == "fr") {
                if (RolesManagement::actionStart('fr', 'read')) {
                    $col[] = $row;
                }
            } elseif ($type == "pre") {
                if (RolesManagement::actionStart('pr', 'read')) {
                    $col[] = $row;
                }
            } elseif ($type == "pev") {
                if (RolesManagement::actionStart('pe', 'read')) {
                    $col[] = $row;
                }
            }
        }

        if ($type == "fr") {
            $rawColumn = ['paper', 'div_appr', 'asset_appr', 'delivery_status', 'action'];
        } elseif ($type == "pre") {
            $rawColumn = ['paper', 'dir_appr', 'action'];
        } elseif ($type == "pev") {
            $rawColumn = ['paper', 'input_date', 'pre_date', 'dir_appr', 'action'];
        }

        return DataTables::collection($col)
            ->rawColumns($rawColumn)
            ->make(true);

        $result = array(
            "data" => $col,
            'type' => $category
        );

        return json_encode($result);
    }

    public function getFrReject(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $frreject = DB::table('asset_pre')
            ->join('marketing_projects as projects','projects.id','=','asset_pre.project')
            ->select('asset_pre.*','projects.prj_name as prj_name',
                DB::raw('(SELECT COUNT(asset_pre_detail.id) FROM asset_pre_detail WHERE asset_pre_detail.fr_id = asset_pre.id) AS items'),
                DB::raw('(SELECT SUM(asset_pre_detail.qty) FROM asset_pre_detail WHERE asset_pre_detail.fr_id = asset_pre.id) AS qty'),
                DB::raw('(SELECT SUM(asset_pre_detail.delivered) FROM asset_pre_detail WHERE asset_pre_detail.fr_id = asset_pre.id) AS delivered'))
            ->whereNotNull('asset_pre.fr_rejected_at')
            ->whereNull('asset_pre.deleted_at')
            ->whereIn('asset_pre.company_id', $id_companies)
            ->orWhereNotNull('asset_pre.fr_division_rejected_at')
            ->orderBy('asset_pre.id','DESC')
            ->get();
        $row = [];
        $fr_waiting = [];

        $view_company = [];
        $comp = ConfigCompany::all();
        foreach ($comp as $key1 => $val){
            $view_company[$val->id] = $val->tag;
        }
        foreach ($frreject as $key => $value){
            $fr_waiting['no'] = ($key+1);
            $fr_waiting['req_date'] = date('d F Y',strtotime($value->request_at));
            $fr_waiting['id_code'] = "<a href='".route('fr.view',['id'=>$value->id])."' class='btn btn-xs btn-link'><i class='fa fa-search'></i>".$value->fr_num."</a>";
            $fr_waiting['req_by'] = $value->request_by;
            $fr_waiting['division'] = $value->division;
            $fr_waiting['project'] = $value->prj_name;
            $fr_waiting['company'] = $view_company[$value->company_id];
            $fr_waiting['items'] = ($value->qty != null)?$value->qty:'-';
            if ($value->fr_division_rejected_by != null || ($value->fr_division_rejected_at != null)){
                $fr_waiting['div_appr'] = date('d F Y', strtotime($value->fr_division_rejected_at))." by ".$value->fr_division_rejected_by;
            } else {
                $fr_waiting['div_appr'] = "-";
            }

            if (($value->fr_rejected_by != null) || ( $value->fr_rejected_at != null)){
                $fr_waiting['asset_appr'] = date('d F Y', strtotime( $value->fr_rejected_at))." by ".$value->fr_rejected_by;
            } else {
               $fr_waiting['asset_appr'] = '-';
            }

            if (RolesManagement::actionStart('frwaiting','delete')){
                $fr_waiting['action'] = "<a href='".route('fr.pr.delete',['id'=>$value->id,'code' =>'fr'])."' class='btn btn-danger btn-xs' title='Delete' onclick='return confirm(\"Are you sure you want to delete?\")'><i class='fa fa-trash'></i></a>";
            } else {
                $fr_waiting['action'] = '';
            }
            if (RolesManagement::actionStart('fr','read')) {
                $row[] = $fr_waiting;
            } else {
                $row[] = [];
            }

        }
        $data = [
            'data' => $row,
        ];
        return json_encode($data);
    }

    public function getFrBank(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $frbank = DB::table('asset_pre')
            ->join('marketing_projects as projects','projects.id','=','asset_pre.project')
            ->select('asset_pre.*','projects.prj_name as prj_name',
                DB::raw('(SELECT COUNT(asset_pre_detail.id) FROM asset_pre_detail WHERE asset_pre_detail.fr_id = asset_pre.id) AS items'),
                DB::raw('(SELECT SUM(asset_pre_detail.qty) FROM asset_pre_detail WHERE asset_pre_detail.fr_id = asset_pre.id) AS qty'),
                DB::raw('(SELECT SUM(asset_pre_detail.delivered) FROM asset_pre_detail WHERE asset_pre_detail.fr_id = asset_pre.id) AS delivered'))
            ->whereNotNull('asset_pre.pre_num')
            ->whereNotNull('asset_pre.fr_approved_at')
            ->whereNotNull('asset_pre.fr_division_approved_at')
            ->whereNull('asset_pre.deleted_at')
            ->whereIn('asset_pre.company_id', $id_companies)
            ->orderBy('asset_pre.id','DESC')
            ->get();

        $row = [];
        $fr_waiting = [];

        $view_company = [];
        $comp = ConfigCompany::all();
        foreach ($comp as $key1 => $val){
            $view_company[$val->id] = $val->tag;
        }
        foreach ($frbank as $key => $value){
            $fr_waiting['no'] = ($key+1);
            $fr_waiting['req_date'] = date('d F Y',strtotime($value->request_at));
            $fr_waiting['id_code'] = "<a href='".route('fr.view',['id'=>$value->id])."' class='btn btn-xs btn-link'><i class='fa fa-search'></i>".$value->fr_num."</a>";
            $fr_waiting['req_by'] = $value->request_by;
            $fr_waiting['division'] = $value->division;
            $fr_waiting['project'] = $value->prj_name;
            $fr_waiting['company'] = $view_company[$value->company_id];
            $fr_waiting['items'] = ($value->qty != null)?$value->qty:'-';
            if (($value->fr_division_approved_by != null) && ($value->fr_division_approved_at != null)){
                $fr_waiting['div_appr'] = date('d F Y', strtotime( $value->fr_division_approved_at));
            } else {
                $fr_waiting['div_appr'] = "<a href='".route('fr.view',['id'=>$value->id,'code'=>base64_encode('div_appr')])."' class='btn btn-link btn-xs'><i class='fa fa-clock'></i>waiting</a>";
            }

            if (($value->fr_approved_by != null) && ( $value->fr_approved_at != null)){
                $fr_waiting['asset_appr'] = date('d F Y', strtotime( $value->fr_approved_at));
            } else {
                if (($value->fr_division_approved_by != null) && ( $value->fr_division_approved_at != null)){
                    $fr_waiting['asset_appr'] = "<a href='".route('fr.view',['id'=>$value->id,'code'=>base64_encode('asset_appr')])."' class='btn btn-link btn-xs'><i class='fa fa-clock'></i>waiting</a>";
                } else {
                    $fr_waiting['asset_appr'] = "waiting";
                }
            }
            if (( $value->qty == $value->delivered) && ( $value->qty > 0)){
                $fr_waiting['deliv_status'] = "Delivered";
            } else {
                if (($value->fr_division_approved_by != null) && ( $value->fr_division_approved_at != null)){
                    $fr_waiting['deliv_status'] = "<a href='".route('fr.view',['id'=>$value->id,'code'=>base64_encode('deliver')])."' class='btn btn-link btn-xs'><i class='fa fa-clock'></i>waiting</a>";
                } else {
                    $fr_waiting['deliv_status'] = "waiting";
                }
            }
            if (RolesManagement::actionStart('frwaiting','delete')){
                $fr_waiting['action'] = "<a href='".route('fr.pr.delete',['id'=>$value->id,'code' =>'fr'])."' class='btn btn-danger btn-xs' title='Delete' onclick='return confirm(\"Are you sure you want to delete?\")'><i class='fa fa-trash'></i></a>";
            } else {
                $fr_waiting['action'] = '';
            }
            if (RolesManagement::actionStart('fr','read')) {
                $row[] = $fr_waiting;
            } else {
                $row[] = [];
            }
        }
        $data = [
            'data' => $row,
        ];
        return json_encode($data);
    }

    public function getFrWaiting(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $frwaiting = DB::table('asset_pre')
            ->join('marketing_projects as projects','projects.id','=','asset_pre.project')
            ->select('asset_pre.*','projects.prj_name as prj_name',
                DB::raw('(SELECT COUNT(asset_pre_detail.id) FROM asset_pre_detail WHERE asset_pre_detail.fr_id = asset_pre.id) AS items'),
                DB::raw('(SELECT SUM(asset_pre_detail.qty) FROM asset_pre_detail WHERE asset_pre_detail.fr_id = asset_pre.id) AS qty'),
                DB::raw('(SELECT SUM(asset_pre_detail.delivered) FROM asset_pre_detail WHERE asset_pre_detail.fr_id = asset_pre.id) AS delivered'))
            ->whereNull('asset_pre.fr_delivers')
            ->whereNull('asset_pre.fr_rejected_at')
            ->WhereNull('asset_pre.fr_division_rejected_at')
            ->whereNull('asset_pre.deleted_at')
            ->whereIn('asset_pre.company_id', $id_companies)
            ->orderBy('asset_pre.id','DESC')
            ->get();

        $row = [];
        $fr_waiting = [];

        $view_company = [];
        $comp = ConfigCompany::all();
        foreach ($comp as $key1 => $val){
            $view_company[$val->id] = $val->tag;
        }
        foreach ($frwaiting as $key => $value){
            $fr_waiting['no'] = ($key+1);
            $fr_waiting['req_date'] = date('d F Y',strtotime($value->request_at));
            $fr_waiting['id_code'] = "<a href='".route('fr.view',['id'=>$value->id])."' class='btn btn-xs btn-link'><i class='fa fa-search'></i>".$value->fr_num."</a>";
            $fr_waiting['req_by'] = $value->request_by;
            $fr_waiting['division'] = $value->division;
            $fr_waiting['project'] = $value->prj_name;
            $fr_waiting['company'] = $view_company[$value->company_id];
            $fr_waiting['items'] = ($value->qty != null)?$value->qty:'-';
            if (($value->fr_division_approved_by != null) && ($value->fr_division_approved_at != null)){
                $fr_waiting['div_appr'] = date('d F Y', strtotime( $value->fr_division_approved_at));
            } else {
                $fr_waiting['div_appr'] = "<a href='".route('fr.view',['id'=>$value->id,'code'=>base64_encode('div_appr')])."' class='btn btn-link btn-xs'><i class='fa fa-clock'></i>waiting</a>";
            }

            if (($value->fr_approved_by != null) && ( $value->fr_approved_at != null)){
                $fr_waiting['asset_appr'] = date('d F Y', strtotime( $value->fr_approved_at));
            } else {
                if (($value->fr_division_approved_by != null) && ( $value->fr_division_approved_at != null)){
                    $fr_waiting['asset_appr'] = "<a href='".route('fr.view',['id'=>$value->id,'code'=>base64_encode('asset_appr')])."' class='btn btn-link btn-xs'><i class='fa fa-clock'></i>waiting</a>";
                } else {
                    $fr_waiting['asset_appr'] = "waiting";
                }
            }
            if (( $value->qty == $value->delivered) && ( $value->qty > 0)){
                $fr_waiting['deliv_status'] = "Delivered";
            } else {
                if (($value->fr_division_approved_by != null) && ( $value->fr_division_approved_at != null)){
                    $fr_waiting['deliv_status'] = "<a href='".route('fr.view',['id'=>$value->id,'code'=>base64_encode('deliver')])."' class='btn btn-link btn-xs'><i class='fa fa-clock'></i>waiting</a>";
                } else {
                    $fr_waiting['deliv_status'] = "waiting";
                }
            }
            if (RolesManagement::actionStart('frwaiting','delete')){
                $fr_waiting['action'] = "<a href='".route('fr.pr.delete',['id'=>$value->id,'code' =>'fr'])."' class='btn btn-danger btn-xs' title='Delete' onclick='return confirm(\"Are you sure you want to delete?\")'><i class='fa fa-trash'></i></a>";
            } else {
                $fr_waiting['action'] = '';
            }
            if (RolesManagement::actionStart('fr','read')) {
                $row[] = $fr_waiting;
            } else {
                $row[] = [];
            }
        }
        $data = [
            'data' => $row,
        ];
        return json_encode($data);

    }

    public function indexFr(){
        $projects = Marketing_project::where('company_id',\Session::get('company_id'))
            ->get();

        $user_division = DB::table('rms_roles_divisions')
        ->select('id','name', 'id_rms_divisions')
        ->where('id', Auth::user()->id_rms_roles_divisions)
        ->first();

        $division = Division::find($user_division->id_rms_divisions);
        $div = Division::where('name', '!=', 'admin')
            ->get();
        $po_type = Asset_type_po::all();

        $src = Finance_coa_source::where('name', 'po')->first();
        $tp_parent = Finance_coa::where('source', 'like', '%"'.$src->id.'"%')
            ->get()->pluck('code');
        $tp = Finance_coa::where(function($query) use($tp_parent){
            foreach ($tp_parent as $key => $value) {
                $parent_code = rtrim($value, 0);
                $query->where('parent_id', 'like', "$parent_code%");
            }
        })->orderBy('code')->get();

        // dd($user_division);
        return view('fr.index',[
            'projects' => $projects,
            'user_division' => $user_division,
            'division' => $division,
            'po_type' => $tp,
            'div' => $div
        ]);
    }

    public function getItems(){
        $childs = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $childs[] = $item->id;
            }
            array_push($childs, Session::get('company_id'));
        }
        $childs[] = Session::get('company_id');
        if (!empty(Session::get('company_id_parent'))) {
            $childs[] = Session::get('company_id_parent');
        }
       $items = Asset_item::where('item_code','like','%'.$_GET['term'].'%')
           ->orWhere('name','like','%'.$_GET['term'].'%')
           ->whereIn('company_id', array_unique($childs))
           ->get();
       $return_arr =[];
       foreach ($items as $key => $item){
            $row_array['item_category'] = $item->category_id;
            $row_array['item_category'] = $item->category_id;
            $row_array['item_id'] = $item->id;
            $row_array['item_name'] = $item->name;
            $row_array['item_code'] = $item->item_code;
            $row_array['item_uom'] = trim($item->uom);

            $row_array['value'] = $item->item_code." / ".$item->name." (".trim($item->uom).") - ".$item->item_series;

            array_push($return_arr, $row_array);
       }
        return json_encode($return_arr);
    }

    public function getProject($cat, Request $request){
        $arr = array(
            'category' => $cat,
        );

        $search = $request->searchTerm;

        $projects = Marketing_project::where('company_id',\Session::get('company_id'))
            ->whereNull('view')
            ->whereRaw('(prj_name like "%'.$search.'%" or id like "%'.$search.'%")')
            ->where($arr)->get();

        $data = [];
        foreach ($projects as $value){
            $data[] = array(
                "id" => $value->id,
                "text" => "[$value->id] $value->prj_name"
            );
        }
        return response()->json($data);

    }

    public function nextDocNumber($code,$year){
        $cek = Asset_pre::where('fr_num','like','%'.$code.'%')
            ->where('fr_date','like','%'.date('y').'-%')
            ->where('company_id', \Session::get('company_id'))
            ->whereNull('deleted_at')
            ->orderBy('id','DESC')
            ->get();

        if (count($cek) > 0){
            $frNum = $cek[0]->fr_num;
            $frDate = $cek[0]->fr_date;
            $str = explode('/', $frNum);
            if (date('y',strtotime($year)) == date('y')){
                $number = intval($str[0]);
                $number+=1;
                if ($number > 99){
                    $no = strval($number);
                } elseif ($number > 9) {
                    $no = "0".strval($number);
                } else {
                    $no = "00".strval($number);
                }
            } else {
                $no = "001";
            }
        } else {
            $no = "001";
        }
        return $no;
    }

    public function addFr(Request $request){
        ActivityConfig::store_point('fr', 'create');
        $iRequest = new Asset_pre();

        $arrRomawi	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $request_date = date("Y-m-d", strtotime($request['request_date']));
        $fr_num = $this->nextDocNumber(strtoupper(\Session::get('company_tag')).'/FR',$request_date);
        $fr_num_id = sprintf('%03d',$fr_num).'/'.strtoupper(\Session::get('company_tag')).'/FR/'.$arrRomawi[date("n")].'/'.date("y");
        $iRequest->fr_num = $fr_num_id;
        $iRequest->request_by = Auth::user()->username;
        $iRequest->created_by = Auth::user()->username;
        $iRequest->request_at = $request_date;
        $iRequest->fr_date = date("Y-m-d", strtotime($request['request_date']));
        $iRequest->due_date = date("Y-m-d", strtotime($request['due_date']));
        $iRequest->project = $request['project'];
        $iRequest->division = $request['division'];
        $iRequest->fr_type = $request['fr_type'];
        $iRequest->fr_notes = $request['notes'];
        $iRequest->company_id = \Session::get('company_id');
        if (isset($request['bd'])){
            $iRequest->bd = 1;
        } else {
            $iRequest->bd = 0;
        }
        $iRequest->company_id = \Session::get('company_id');

        $uploaddir  = public_path('media/asset/');
        $justiInput = $request->file('justification');
        if (!empty($justiInput)){
            $d = date("YmdHi");
            $justi      = $fr_num_id."-justification-$d.".$justiInput->getClientOriginalExtension();
            $iRequest->justification = $justi;
            $upload = $justiInput->move($uploaddir,$justi);
            if($upload){
                // dd($uploaddir, $justi);
            }
        }


        $iRequest->save();
        $last_id = $iRequest->id;

        $notif['module'] = "fr";
        $notif['action'] = "approvediv1";
        $notif['paper']  = $iRequest->fr_num;
        $notif['url']    = route('fr.view', ['id'=>$last_id,'code'=>base64_encode('div_appr')]);
        $notif['id']     = $last_id;

        Notification::save($notif);

        if (isset($request->code)) {
            foreach ($request->code as $key => $itemCode) {
                $iRequestDetail = new Asset_pre_detail();
                $iRequestDetail->fr_id = $last_id;
                $iRequestDetail->item_id = $itemCode;
                $iRequestDetail->qty = $request['qty'][$key];
                $iRequestDetail->save();
            }
        }

        if (isset($request->item_n_name)) {
            $new_i_name = $request->item_n_name;
            $new_i_uom = $request->item_n_uom;
            $new_i_qty = $request->item_n_qty;
            $dateCode = date("YmdHis");
            for ($i = 0; $i < count($new_i_name); $i++) {
                $code = ($i + 1) . $dateCode . rand(1000, 9999);
                $iRequestDetail = new Asset_pre_detail();
                $iRequestDetail->fr_id = $last_id;
                $iRequestDetail->item_id = $code;
                $iRequestDetail->qty = $new_i_qty[$i];
                $iRequestDetail->save();

                $new_item = new Asset_item();
                $new_item->item_code = $code;
                $new_item->name = $new_i_name[$i];
                $new_item->uom = $new_i_uom[$i];
                $new_item->minimal_stock = 1;
                $new_item->uom2 = "waiting";
                $new_item->company_id = (empty(Session::get('company_id_parent'))) ? Session::get('company_id') : Session::get('company_id_parent');
                $new_item->created_by = Auth::user()->username;
                $new_item->save();
            }
        }

        return redirect()->route('fr.index');

    }

    public function frView($id,$code=null){
        $fr = Asset_pre::where('id',$id)->first();
        $po = Asset_po::where('reference', $fr->pev_num)->first();
        $gr = [];
        if(!empty($po)){
            $gr = Asset_good_receive::where('po_id', $po->id)->first();
        }
        // dd($po);
        $fr_detail = DB::table('asset_pre_detail')
            ->leftJoin('asset_items as items','items.item_code','=','asset_pre_detail.item_id')
            ->select('asset_pre_detail.*','items.name as itemName','items.uom as uom', 'items.id as itemId', 'items.uom2 as new_item')
            ->where('asset_pre_detail.fr_id',$fr->id)
            ->whereNull('items.deleted_at')
            ->get();
            //Asset_pre_detail::where('fr_id',$fr->fr_id)->get();
        $project = Marketing_project::where('id',$fr->project)->first();

        $wh_data = Asset_wh::select("id", "name")->get();
        $wh = [];
        foreach($wh_data as $item){
            $wh[$item->id] = $item;
        }

        $qty_wh_data = Asset_qty_wh::all();
        $qty_wh = [];
        $qoh = [];
        foreach($qty_wh_data as $item){
            $qty_wh[$item->item_id][] = $item->wh_id;
            $qoh[$item->item_id][] = $item->qty;
        }

        return view('fr.frview',[
           'fr' => $fr,
           'po' => $po,
           'fr_detail' => $fr_detail,
           'project' => $project,
           'code' => base64_decode($code),
           'wh' => $wh,
           'qty_wh' => $qty_wh,
           'qoh' => $qoh,
           'gr' => $gr
        ]);
    }

    public function prView($id,$code=null){
        $pr = Asset_pre::where('id',$id)->first();
        $po = Asset_po::where('reference', $pr->pev_num)->first();
        $pr_detail = DB::table('asset_pre_detail')
            ->join('asset_items as items','items.item_code','=','asset_pre_detail.item_id')
            ->select('asset_pre_detail.*','items.name as itemName','items.uom as uom')
            ->where('asset_pre_detail.fr_id',$pr->id)
            ->whereRaw('(asset_pre_detail.qty_deliver is null or asset_pre_detail.qty_deliver = 0)')
            ->whereNull('asset_pre_detail.deleted_at')
            ->whereNull('items.deleted_at')
            ->get();

        $project = Marketing_project::where('id',$pr->project)->first();
        return view('pr.prview',[
            'pr' => $pr,
            'po' => $po,
            'pr_detail' => $pr_detail,
            'project' => $project,
            'code' => base64_decode($code)
        ]);
    }

    public function apprDiv(Request $request){
        $fr = Asset_pre::find($request->fr_id);
        ActivityConfig::store_point('fr', 'approve_div');
        if ($request['submit'] == 'Approve'){
            Asset_pre::where('id', $request['fr_id'])
                ->update([
                    'fr_division_approved_by' => Auth::user()->username,
                    'fr_division_approved_at' => date('Y-m-d H:i:s'),
                    'fr_approved_notes' => $request['notes']
                ]);

            $notif['module'] = "fr";
            $notif['action'] = "approvediv2";
            $notif['id']     = $request->fr_id;
            $notif['paper']  = $fr->fr_num;
            $notif['url']    = route('fr.view', ['id'=>$fr->id,'code'=>base64_encode('asset_appr')]);
            $notif['action_prev'] = "approvediv1";
            Notification::save($notif);
        }
        if ($request['submit'] == 'Reject'){
            Asset_pre::where('id', $request['fr_id'])
                ->update([
                    'fr_division_rejected_by' => Auth::user()->username,
                    'fr_division_rejected_at' => date('Y-m-d H:i:s'),
                    'fr_rejected_notes' => $request['notes']
                ]);
        }

        return redirect()->route('fr.index');
    }

    public function apprDir(Request $request){
        if ($request['submit'] == 'Approve'){
            $frnum = $request['fr_num'];
            $pev_num = str_replace("FR","PEV",$frnum);
            Asset_pre::where('id', $request['id'])
                ->update([
                    'pev_num' => $pev_num,
                    'pre_approved_by' => Auth::user()->username,
                    'pre_approved_at' => date('Y-m-d H:i:s'),
                    'pre_approved_notes' => $request['notes'],
                    'pre_notes' => $request['notes']
                ]);
            $fr = Asset_pre::find($request->id);
            $notif['module'] = "pe";
            $notif['module_prev'] = "pr";
            $notif['action'] = "approvediv1";
            $notif['id']     = $fr->id;
            $notif['paper']  = $fr->pev_num;
            $notif['url']    = route('pe.input', $fr->id);
            $notif['action_prev'] = "approvedir";
            Notification::save($notif);
            foreach ($request['itemID'] as $key => $idDetail){
                Asset_pre_detail::where('id',$idDetail)
                    ->update([
                        'pev_num' => $pev_num,
                        'pre_id' => $request['id'],
                        'qty_appr' =>$request['qty_appr'][$key],
                    ]);
            }
        }
        if ($request['submit'] == 'Reject'){
            Asset_pre::where('id', $request['id'])
                ->update([
                    'pre_rejected_by' => Auth::user()->username,
                    'pre_rejected_at' => date('Y-m-d H:i:s'),
                    'pre_rejected_notes' => $request['notes']
                ]);
        }

        return redirect()->route('pr.index');
    }

    public function apprAsset(Request $request){
        ActivityConfig::store_point('fr', 'approve_asset');
        $toDo = false;
        $qdeliver = 0;
        $qbuy = 0;
        if ($request['submit'] == 'Approve'){
            $fr = Asset_pre::find($request->fr_id);
            $frnum = $request['fr_num'];
            $pre_num = str_replace("FR","PRE",$frnum);

            if(!empty($request['qty_deliver'])){
                foreach($request['qty_deliver'] as $qde){
                    $qdeliver += $qde;
                }
            }

            if(!empty($request['qty_buy'])){
                $qbuy = array_sum($request['qty_buy']);
            }

            if(!empty(array_filter($request['qty_buy']))) {
                $fr['fr_delivers'] ='deliver';
                $fr['fr_approved_by'] = Auth::user()->username;
                $fr['fr_approved_at'] = date('Y-m-d H:i:s');
                $fr['fr_approved_notes'] = $request['notes'];
                $fr['pre_num'] = $pre_num;
                $fr['pre_date'] = date('Y-m-d');
                $fr->save();
                $notif['module'] = "pr";
                $notif['module_prev'] = "fr";
                $notif['action'] = "approvedir";
                $notif['id']     = $fr->id;
                $notif['paper']  = $fr->pre_num;
                $notif['url']    = route('pr.view', ['id'=>$fr->id,'code'=>base64_encode('dir_appr')]);
                $notif['action_prev'] = "approvediv2";
                Notification::save($notif);
                foreach ($request['qty_buy'] as $key => $qty_buy){
                    Asset_pre_detail::where('id',$request['fr_detail_id'][$key])
                        ->update([
                            'item_id' => $request['fr_detail_code'][$key],
                            'qty_buy' => $qty_buy,
                            // 'qty_req' => $qty_buy,
                            'pre_num' => $pre_num,
                        ]);
                }
            }

            if(!empty(array_filter($request['qty_deliver']))){
                // $wh = $request['wh'];
                $itemCode = $request['fr_detail_code'];
                foreach($request['qty_deliver'] as $key => $qty_deliver) {
                    Asset_pre_detail::where('id',$request['fr_detail_id'][$key])
                        ->update([
                            'delivered' => $qty_deliver,
                            'qty_deliver' => $qty_deliver,
                        ]);

                    // $item = Asset_item::where('item_code', $itemCode[$key])->first();
                    // $qtyWh = Asset_qty_wh::where('item_id', $item->id)
                    //         ->where('wh_id', $wh[$key])
                    //         ->first();
                    // if(!empty($qtyWh)){
                    //     $qtyWh->qty = $qtyWh->qty - $qty_deliver;
                    //     $qtyWh->save();
                    // }
                }

                $fr['fr_approved_by'] = Auth::user()->username;
                $fr['fr_approved_at'] = date('Y-m-d H:i:s');
                $fr->save();
            }
        }
        if ($request['submit'] == 'Reject'){
            Asset_pre::where('id', $request['fr_id'])
                ->update([
                    'fr_rejected_by' => Auth::user()->username,
                    'fr_rejected_at' => date('Y-m-d H:i:s'),
                    'fr_rejected_notes' => $request['notes']
                ]);
        }

        if($qdeliver > 0 && $qbuy == 0){
            return redirect()->route('fr.create.do', $request['fr_id']);
        }

        return redirect()->route('fr.index');
    }

    public function apprDeliver(Request $request){
        if ($request['submit'] == 'Approve'){
            foreach($request['remnant'] as $key => $remnant){
                $delivered = $remnant;
                $sisa = intval($request['qty_remnant'][$key]) - intval($delivered);
                if ($sisa >= 0){
                    Asset_pre_detail::where('id',$request['fr_detail_id'][$key])
                        ->update([
                            'delivered' => intval($request['qty_deliver'][$key])+intval($remnant),
                        ]);

                    if ($sisa == 0){
                        Asset_pre::where('id',$request['fr_id'])
                            ->update([
                                'fr_delivers' => 'delivered',
                                'fr_deliver_times' => date('Y-m-d H:i:s'),
                            ]);
                    }
                }
            }
        }
        return redirect()->route('fr.index');
    }

    public function indexPr(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $prwaiting = DB::table('asset_pre')
            ->join('marketing_projects as projects','projects.id','=','asset_pre.project')
            ->select('asset_pre.*','projects.prj_name as prj_name',
                DB::raw('(SELECT COUNT(asset_pre_detail.id) FROM asset_pre_detail WHERE asset_pre_detail.fr_id = asset_pre.id) AS items'),
                DB::raw('(SELECT SUM(asset_pre_detail.qty) FROM asset_pre_detail WHERE asset_pre_detail.fr_id = asset_pre.id) AS qty'),
                DB::raw('(SELECT SUM(asset_pre_detail.delivered) FROM asset_pre_detail WHERE asset_pre_detail.fr_id = asset_pre.id) AS delivered'))
            ->where('asset_pre.request_at','like','%'.date('Y').'%')
            ->whereNotNull('asset_pre.fr_approved_at')
            ->whereNull('asset_pre.pre_approved_at')
            ->whereNull('asset_pre.deleted_at')
            ->whereIn('asset_pre.company_id', $id_companies)
            ->orderBy('asset_pre.id','DESC')
            ->get();
        $prbank = DB::table('asset_pre')
            ->join('marketing_projects as projects','projects.id','=','asset_pre.project')
            ->select('asset_pre.*','projects.prj_name as prj_name',
                DB::raw('(SELECT COUNT(asset_pre_detail.id) FROM asset_pre_detail WHERE asset_pre_detail.fr_id = asset_pre.id) AS items'),
                DB::raw('(SELECT SUM(asset_pre_detail.qty) FROM asset_pre_detail WHERE asset_pre_detail.fr_id = asset_pre.id) AS qty'),
                DB::raw('(SELECT SUM(asset_pre_detail.delivered) FROM asset_pre_detail WHERE asset_pre_detail.fr_id = asset_pre.id) AS delivered'))
            ->where('asset_pre.request_at','like','%'.date('Y').'%')
            ->whereNotNull('asset_pre.pre_approved_at')
            ->whereIn('asset_pre.company_id', $id_companies)
            ->whereNull('asset_pre.deleted_at')
            ->orderBy('asset_pre.id','DESC')
            ->get();
        $prreject = DB::table('asset_pre')
            ->join('marketing_projects as projects','projects.id','=','asset_pre.project')
            ->select('asset_pre.*','projects.prj_name as prj_name',
                DB::raw('(SELECT COUNT(asset_pre_detail.id) FROM asset_pre_detail WHERE asset_pre_detail.fr_id = asset_pre.id) AS items'),
                DB::raw('(SELECT SUM(asset_pre_detail.qty) FROM asset_pre_detail WHERE asset_pre_detail.fr_id = asset_pre.id) AS qty'),
                DB::raw('(SELECT SUM(asset_pre_detail.delivered) FROM asset_pre_detail WHERE asset_pre_detail.fr_id = asset_pre.id) AS delivered'))
            ->whereNotNull('asset_pre.fr_approved_at')
            ->whereNotNull('asset_pre.fr_approved_by')
            ->whereNotNull('asset_pre.pre_rejected_at')
            ->whereNull('asset_pre.deleted_at')
            ->whereNull('asset_pre.deleted_at')
            ->whereIn('asset_pre.company_id', $id_companies)
            ->orderBy('asset_pre.id','DESC')
            ->get();

        return view('pr.index',[
            'waitings' => $prwaiting,
            'banks' => $prbank,
            'rejects' => $prreject,
        ]);
    }

    public function delete($code,$id){
        Asset_pre::where('id', $id)
            ->update([
                "deleted_by" => Auth::user()->username
            ]);
        Asset_pre::where('id',$id)->delete();
        Asset_pre_detail::where('fr_id',$id)->delete();
        return redirect()->back();
    }

    // PEV
    function indexPev(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $whereLike = date("Y")."%";
        $pev = Asset_pre::where('pev_num', '!=', null)
            ->whereIn('company_id', $id_companies)
            ->whereRaw('(pev_date like "'.$whereLike.'" or pre_approved_at like "'.$whereLike.'")')
            // ->where('pre_approved_at', 'like',date('Y').'%')
            ->orderBy('id', 'desc')
            ->get();
        $pev_detail = Asset_pre_detail::all();
        $pev_items = array();
        foreach ($pev_detail as $value){
            $pev_items[$value->fr_id][] = $value->qty;
        }
        $pro = Marketing_project::whereIn('company_id', $id_companies)->get();
        $pro_name = array();
        foreach ($pro as $value){
            $pro_name[$value->id] = $value->prj_name;
        }
        return view('pe.index', [
            'pev' => $pev,
            'pro' => $pro_name,
            'items' => $pev_items
        ]);
    }

    function pc_apprPev($id){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $pev = Asset_pre::where('id', $id)->first();
        if (empty($pev->pev_date) && empty($pev->pev_approved_at)){
            $link = URL::route('pe.input_post');
            $status = "input";
        } elseif (empty($pev->pev_approved_at)){
            $link = URL::route('pe.dir_post');
            $status = "dir";
        } else {
            $link = "";
            $status = "";
        }
        $pro = Marketing_project::whereIn('company_id', $id_companies)->get();
        $pro_name = array();
        foreach ($pro as $value){
            $pro_name[$value->id] = $value->prj_name;
        }

        $pev_detail = Asset_pre_detail::where('fr_id', $id)
            ->whereRaw('(qty_deliver is null or qty_deliver = 0)')
            ->get();

        $items = Asset_item::all();
        $item_name = [];
        $item_uom = [];
        foreach ($items as $item) {
            $item_name[$item->item_code] = $item->name;
            $item_uom[$item->item_code] = $item->uom;
        }

        $po_det = Asset_po_detail::orderBy('id', 'DESC')->get();
        $price = array();
        foreach ($po_det as $item) {
            $price[$item->item_id][] = $item->price;
        }

        $tax = Pref_tax_config::all();
        $id_tax = array();
        foreach ($tax as $key => $value){
            $id_tax[] = $value->id;
            $conflict[$value->id] = json_decode($value->conflict_with);
            $formula[$value->id] = $value->formula;
        }

        $vendor = Procurement_vendor::whereIn('company_id', $id_companies)
            ->orderBy('name')
            ->get();

        $jsonConflict = (!empty($conflict)) ? json_encode($conflict) : "";
        $jsonFormula = (!empty($formula)) ? json_encode($formula) : "";

        return view('pe.pc_appr', [
            'pev' => $pev,
            'pro' => $pro_name,
            'vendors' => $vendor,
            'items' => $pev_detail,
            'item_name' => $item_name,
            'uom' => $item_uom,
            'prices' => $price,
            'taxes' => $tax,
            'conflict' => $jsonConflict,
            'formula' => $jsonFormula,
            'link_post' => $link,
            'id_tax' => $id_tax,
            'status' => $status
        ]);
    }

    function pev_view($id){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $pev = Asset_pre::where('id', $id)->first();
        $po = Asset_po::where('reference',$pev->pev_num)->first();

        if (empty($pev->pev_date) && empty($pev->pc_time) && empty($pev->pev_division_approved_at) && empty($pev->pev_approved_at)){
            $link = URL::route('pe.input_post');
            $status = "input";
        } elseif (empty($pev->pc_time) && empty($pev->pev_division_approved_at) && empty($pev->pev_approved_at)){
            $link = URL::route('pe.pc_post');
            $status = "pc";
        } elseif (empty($pev->pev_division_approved_at) && empty($pev->pev_approved_at)){
            $link = URL::route('pe.div_post');
            $status = "div";
        } elseif (empty($pev->pev_approved_at)){
            $link = URL::route('pe.dir_post');
            $status = "dir";
        } else {
            $link = "";
            $status = "";
        }
        $pro = Marketing_project::whereIn('company_id', $id_companies)->get();
        foreach ($pro as $value){
            $pro_name[$value->id] = $value->prj_name;
        }

        $pev_detail = Asset_pre_detail::where('fr_id', $id)
            ->whereRaw('(qty_deliver is null or qty_deliver = 0)')
            ->get();

        $item_name = [];
        $item_uom = [];
        $items = Asset_item::all();
        foreach ($items as $item) {
            $item_name[$item->item_code] = $item->name;
            $item_uom[$item->item_code] = $item->uom;
        }

        $po_det = Asset_po_detail::orderBy('id', 'DESC')->get();
        $price = array();
        foreach ($po_det as $item) {
            $price[$item->item_id][] = $item->price;
        }

        $tax = Pref_tax_config::all();
        foreach ($tax as $key => $value){
            $id_tax[] = $value->id;
            $conflict[$value->id] = json_decode($value->conflict_with);
            $formula[$value->id] = $value->formula;
        }

        $vendor = Procurement_vendor::whereIn('company_id', $id_companies)
            ->orderBy('name')
            ->get();

        $jsonConflict = (!empty($conflict)) ? json_encode($conflict) : "";
        $jsonFormula = (!empty($formula)) ? json_encode($formula) : "";

        return view('pe.view', [
            'pev' => $pev,
            'po' => $po,
            'pro' => $pro_name,
            'vendors' => $vendor,
            'items' => $pev_detail,
            'item_name' => $item_name,
            'uom' => $item_uom,
            'prices' => $price,
            'taxes' => $tax,
            'conflict' => $jsonConflict,
            'formula' => $jsonFormula,
            'link_post' => $link,
            'id_tax' => $id_tax,
            'status' => $status
        ]);
    }

    function pc_postPev(Request $request){
        $id = $request->id_fr;
        $pre = Asset_pre::find($id);

        $dir = public_path('media/asset/');

        $pre->pev_date = date('Y-m-d H:i:s');
        $pre->suppliers = json_encode($request->vendor);
        $pre->ppns = (empty($request->tax)) ? null : json_encode($request->tax);
        $pre->dps = json_encode($request->dp);
        $pre->discs = json_encode($request->discount);
        $pre->tops = json_encode($request->terms_pay);
        $pre->pev_notes = json_encode($request->notes);
        $pre->currencies = json_encode($request->currency);
        $pre->delivers = json_encode($request->d_to);
        $pre->deliver_times = json_encode($request->d_time);
        $pre->terms = json_encode($request->terms);
        $quot = $request->file('file_quot');
        if ($request->status == "input"){
            $pre->pc_by = Auth::user()->username;
            $pre->pc_time = date('Y-m-d H:i:s');
            $notif['module'] = "pe";
            $notif['action'] = "approvedir";
            $notif['id']     = $pre->id;
            $notif['paper']  = $pre->pev_num;
            $notif['url']    = route('pe.input', $pre->id);
            $notif['action_prev'] = "approvediv1";
            Notification::save($notif);
        } elseif ($request->status == "div"){
            $pre->pev_division_approved_by = Auth::user()->username;
            $pre->pev_division_approved_at = date('Y-m-d H:i:s');
            $notif['module'] = "pe";
            $notif['action'] = "approvedir";
            $notif['id']     = $pre->id;
            $notif['paper']  = $pre->pev_num;
            $notif['url']    = route('pe.input', $pre->id);
            $notif['action_prev'] = "approvediv2";
            Notification::save($notif);
        } elseif ($request->status == "dir"){
            $pre->pev_approved_by = Auth::user()->username;
            $pre->pev_approved_at = date('Y-m-d H:i:s');
            $pre->pev_approved_notes = $request->pev_notes;
            $paper = explode("/", $pre->pev_num);
            $tag = $paper[1];

            // save to PO
            $pre_data = Asset_pre::where('id', $id)->first();
            $arr_idx = $request->radio;
            $d_to = $request->d_to;
            $d_time = $request->d_time;
            $curr_po = $request->currency;
            $disc_po = $request->discount;
            $dp_po = $request->dp;
            $ppn_po = $request->tax;
            $pay_term = $request->terms_pay;
            $term_po = $request->terms;
            $notes_po = $request->notes;
            $up_po = $request->unit_price;
            $qty_po = $request->qty;
            $arrRomawi	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
            foreach ($arr_idx as $key => $value){
                $newidx = explode("-", $value);
                $newpo[end($newidx)][] = $key;
            }

            $pref = Preference_config::where('id_company', $pre->company_id)->first();
            $po_signature = (!empty($pref->po_signature)) ? json_decode($pref->po_signature, true) : [];
            // $pdetail = Asset_pre_detail::where("fr_id", $id)->get(['qty', 'price']);
            // dd($newpo, $pref);

            foreach ($newpo as $x => $pox){
                $total_price = 0;
                foreach($pox as $ipo){
                    $pdetail = Asset_pre_detail::find($ipo);
                    $total_price += str_replace(",", "", $up_po[$ipo][$x]) * $qty_po[$ipo];
                }

                $bypass = false;
                $minArr = [];
                $maxArr = [];
                $bypassArr = [];
                if(is_array($po_signature) && !empty($po_signature)){
                    $minArr = $po_signature['min'];
                    $maxArr = $po_signature['max'];
                    if(isset($po_signature['bypass'])){
                        $bypassArr = $po_signature['bypass'];
                    }
                }

                $keyBypass = null;
                for ($i=0; $i < count($minArr); $i++) {
                    if($maxArr[$i] == 0){
                        if($total_price > $minArr[$i]){
                            $keyBypass = $i;
                            break;
                        }
                    } else {
                        if($total_price <= $maxArr[$i]){
                            $keyBypass = $i;
                            break;
                        }
                    }
                }

                if(isset($bypassArr[$keyBypass])){
                    if($bypassArr[$keyBypass] == 1){
                        $bypass = true;
                    }
                }

                $po_num = Asset_po::where('created_at', 'like', ''.date('Y')."-%")
                    ->where('po_num', 'like', "%".$tag."%")
                    ->orderBy('id', 'desc')
                    ->first();
                if (!empty($po_num)) {
                    $last_num = explode("/", $po_num->po_num);
                    $num = sprintf("%03d", (intval($last_num[0]) + 1));
                } else {
                    $num = sprintf("%03d", 1);
                }

                $supp_po = $request->vendor;

                $po = new Asset_po;

                $po->request_by = $pre_data->request_by;
                $po->supplier_id = $supp_po[$x];
                $po->po_date = date('Y-m-d');
                $po->po_type = $pre_data->fr_type;
                $po->po_num = $num."/".strtoupper($tag)."/PO/".$arrRomawi[date('n')]."/".date('y');
                $po->project = $pre_data->project;
                $po->division = $pre_data->division;
                $po->reference = $pre_data->pev_num;
                $po->deliver_to = $d_to[$x];
                $po->deliver_time = $d_time[$x];
                $po->currency = $curr_po[$x];
                $po->discount = $disc_po[$x];
                $po->dp = $dp_po[$x];
                if (isset($ppn_po[$x])){
                    $po->ppn = json_encode($ppn_po[$x]);
                }
                $po->payment_term = $pay_term[$x];
                $po->terms = $term_po[$x];
                $po->notes = $notes_po[$x];
                $po->fr_note = $pre_data->pev_approved_notes;
                $po->company_id = $pre->company_id;
                $po->tc_id = $pre->tc_id;
                $po->save();

                $notif['module'] = "po";
                $notif['module_prev'] = "pe";
                $notif['action'] = "approvedir";
                $notif['id']     = $po->id;
                $notif['id_prev'] = $pre->id;
                $notif['paper']  = $po->po_num;
                $notif['url']    = route('po.appr', $po->id);
                $notif['action_prev'] = "approvedir";

                if($bypass == true){
                    $po->approved_by = Auth::user()->username;
                    $po->approved_time = date("Y-m-d H:i:s");
                    $po->appr_notes = $po->notes;
                    $po->save();
                    $notif['last'] = 1;
                }

                Notification::save($notif);

                foreach ($pox as $idpo => $itempo){
                    $price = str_replace(",", "", $up_po[$itempo][$x]);
                    if($qty_po[$itempo] > 0 && $price > 0){
                        $po_det = new Asset_po_detail();
                        $det = Asset_pre_detail::where('id', $itempo)->first();

                        $po_det->item_id = $det->item_id;
                        $po_det->qty = $qty_po[$itempo];
                        $po_det->price = $price;
                        $po_det->po_num = $po->id;
                        $po_det->save();
                    }
                }
            }

        }
        if (!empty($quot)){
            $file_quot = (!empty($pre->attach1)) ? json_decode($pre->attach1) : array();
            for ($i = 0; $i < count($quot); $i++){
                if (isset($quot[$i])) {
                    $newName = "quotation(".$id.")(".$i.").".$quot[$i]->getClientOriginalExtension();
                    $file_quot[$i] = $newName;
                    $quot[$i]->move($dir, $newName);
                }
            }
            $pre->attach1 = json_encode($file_quot);
        }
        $pre->save();

        $rad = $request->radio;
        $ids = $request->id_item;
        $up = $request->unit_price;
        for ($i=0; $i < count($ids); $i++){
            $pre_det = Asset_pre_detail::find($ids[$i]);
            $pre_det->price = json_encode(str_replace(",", "", $up[$ids[$i]]));
            $idx = (!empty($rad[$ids[$i]])) ? explode("-", $rad[$ids[$i]]) : null;
            $pre_det->supp_idx = (!empty($rad[$ids[$i]])) ? end($idx) : null;
            $pre_det->save();
        }

        return redirect()->route('pe.index');
    }

    function rejectPev(Request $request){
        $id = $request->id;
        $pre = Asset_pre::find($id);
        $pre->pev_rejected_by = Auth::user()->username;
        $pre->pev_rejected_at = date('Y-m-d H:i:s');
        if ($pre->save()){
            $data['del'] = 1;
        } else {
            $data['del'] = 0;
        }

        return json_encode($data);
    }

    function see_detail($id){
        $qtyWh = Asset_qty_wh::where('item_id', $id)
            ->where("qty", '!=', 0)
            ->orderBy('qty', 'desc')
            ->get();
        $wh = Asset_wh::all()->pluck('name', 'id');
        $item = Asset_item::find($id);

        return view('fr._detail', compact('qtyWh', 'wh', 'item'));
    }

    function to_do($id){
        $items = Asset_item::all();
        $data_item = [];
        foreach($items as $item){
            $data_item[$item->item_code] = $item;
        }
        $dItems = [];

        $pre = Asset_pre::find($id);
        $detail = Asset_pre_detail::where('fr_id', $pre->id)
            ->where('qty_deliver', ">", 0)
            ->get();

        $gr = Asset_good_receive::all()->pluck('po_id', 'id');

        $po = Asset_po::where('reference', $pre->pev_num)->get();
        $item_pr = [];
        foreach($po as $ipo){
            $item_pr[] = (isset($gr[$ipo->id])) ? 1 : 0;
        }

        if(empty($pre->pre_num)){
            $status = "do_deliver";
            $add = true;
        } else {
            $status = "do_purchase";
            $add = false;
            if(count($item_pr) > 0){
                if(count($item_pr) == array_sum($item_pr)){
                    $status = "do_purchase";
                    $add = true;
                }
            }
        }

        return view('fr.do', compact('data_item', 'dItems', 'pre', 'detail', 'status', 'add'));
    }

}
