<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Asset_wo;
use App\Models\Division;
use App\Models\Asset_sre;
use App\Models\Finance_coa;
use App\Rms\RolesManagement;
use Illuminate\Http\Request;
use App\Helpers\Notification;
use App\Models\Asset_type_wo;
use App\Models\ConfigCompany;
use App\Helpers\ActivityConfig;
use App\Models\Asset_wo_detail;
use App\Models\Pref_tax_config;
use App\Models\Asset_sre_detail;
use App\Models\Marketing_project;
use App\Models\Preference_config;
use App\Models\Finance_coa_source;
use App\Models\Procurement_vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AssetSreController extends Controller
{
    function lists(Request $request, $type, $category){
        switch ($type) {
            case 'so':
                $whereType = " 1 and so_num is not null";
                $whereDate = " (request_at like '".date('Y')."%' or so_date like '".date('Y')."%')";
                break;
            case 'rfq':
                $whereType = " so_approved_at is not null and (so_num is not null and rfq_so_num is not null)";
                $whereDate = " (so_approved_at like '".date('Y')."%' or pre_date like '".date('Y')."%')";
                break;
            case 'se':
                $whereType = " rfq_approved_at is not null and ((rfq_so_num != '') and (se_num != ''))";
                $whereDate = " (rfq_approved_at like '".date('Y')."%' or pev_date like '".date('Y')."%')";
                break;
        }

        switch ($category) {
            case 'waiting':
                $whereCategory = " ".$type."_approved_at is null and ".$type."_rejected_at is null";
                break;
            case 'bank':
                $whereCategory = " ".$type."_approved_at is not null";
                if($type == "se"){
                    $whereCategory .= " and se_input_at is not null";
                }
                break;
            case 'reject':
                $whereCategory = " ".$type."_rejected_at is not null";
                break;
        }

        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
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

        $sreTotal = Asset_sre::whereIn('company_id', $id_companies)
            ->whereRaw($whereCategory)
            ->whereRaw($whereType)
            ->orderBy('id', 'desc')
            ->get();

        $od = $request->order;

        $tOrder = "desc";

        if ($od[0]['column'] == 0 || $od[0]['column'] == 1) {
            if ($od[0]['dir'] == "asc") {
                $tOrder = "desc";
            } else {
                $tOrder = "asc";
            }
        }


        $sre = Asset_sre::whereIn('company_id', $id_companies)
            ->whereRaw($whereCategory)
            ->whereRaw($whereType)
            ->orderBy('id', $tOrder)
            // ->offset($request->start)
            // ->limit($request->length)
            ->get();

        $details = Asset_sre_detail::all();
        $listItems = [];
        foreach ($details as $key => $value) {
            $listItems[$value->so_id][] = $value->qty;
        }

        $iNum = 1;
        $cLength = 0;
        $col = [];
        foreach ($sre as $key => $value) {
            $paper = (empty($value->so_num)) ? "--" : $value->so_num;
            $reference = "";
            $i_date = $value->so_date;
            $project = "#N/A";
            $company = "";
            $deleteBtn = "";
            $div_appr = "waiting";
            $dir_appr = "waiting";
            $input_date = "waiting";
            $notes = $value->so_notes;
            $item_amount = 0;
            if (empty($value->so_approved_at) && empty($value->so_rejected_at)) {
                if (RolesManagement::actionStart('so', 'approvediv1')){
                    $div_appr = "<a href='".URL::route('so.appr', $value->id)."' class='text-hover-danger'>waiting <i class='fa fa-clock'></i></a>";
                }
            } else {
                if (!empty($value->so_rejected_at)) {
                    $div_appr = "rejected at ".date('d F Y', strtotime($value->so_rejected_at));
                } else {
                    $div_appr = "approved at ".date('d F Y', strtotime($value->so_approved_at));
                }
            }
            $route = route('so.view', $value->id);

            if ($type == "so") {
                $act = 'so';
            } elseif ($type == "rfq") {
                $act = 'sr';
            } elseif ($type == "se") {
                $act = 'se';
            }

            $btnDelete = "";

            if (RolesManagement::actionStart($act, 'delete')) {
                $btnDelete = "<a href='".route('sre.delete', ["type" => $act, "id" => $value->id])."' class='btn btn-icon btn-xs btn-danger' onclick='return confirm(\"delete?\")'><i class='fa fa-trash'></i></a>";
            }

            if ($type == "rfq") {
                $reference = $value->so_num;
                $paper = $value->rfq_so_num;
                $i_date = $value->rfq_so_date;
                $notes = $value->so_approved_notes;
                if (empty($value->rfq_approved_at) && empty($value->rfq_rejected_at)) {
                    if (RolesManagement::actionStart('so', 'approvediv1')){
                        $div_appr = '<a href="'.route('sr.appr', $value->id).'" class="text-hover-danger">waiting <i class="fa fa-clock"></i></a>';
                    } else {
                        $div_appr = "waiting";
                    }
                } else {
                    if (!empty($value->rfq_rejected_at)) {
                        $div_appr = "rejected at ".date('d F Y', strtotime($value->rfq_rejected_at));
                    } else {
                        $div_appr = "approved at ".date('d F Y', strtotime($value->rfq_approved_at));
                    }
                }


                $route = route('sr.view', $value->id);
            } elseif($type == "se"){
                $reference = $value->rfq_so_num;
                $paper = $value->se_num;
                $i_date = $value->rfq_approved_at;
                $notes = $value->rfq_approved_notes;
                if (empty($value->se_approved_at) && empty($value->se_rejected_at)) {
                    if (RolesManagement::actionStart('se', 'approvedir')){
                        $dir_appr = '<a href="'.route('se.appr', $value->id).'" class="text-hover-danger">waiting <i class="fa fa-clock"></i></a>';
                    }
                } else {
                    if (!empty($value->se_rejected_at)) {
                        $dir_appr = "rejected at ".date('d F Y', strtotime($value->se_rejected_at));
                    } else {
                        $dir_appr = "approved at ".date('d F Y', strtotime($value->se_approved_at));
                    }
                }

                if(empty($value->se_input_at)){
                    if (RolesManagement::actionStart('se', 'approvediv1')){
                        $input_date = '<a href="'.route('se.appr', $value->id).'" class="text-hover-danger">waiting <i class="fa fa-clock"></i></a>';
                    }
                } else {
                    $input_date = "inputed at ".date('d F Y', strtotime($value->se_input_at));
                }

                $route = route('se.view', ['id' => $value->id]);
            }

            if (isset($listPrj[$value->project])) {
                $project = $listPrj[$value->project]['prj_name'];
            }

            if (isset($listItems[$value->id])) {
                $item_amount = array_sum($listItems[$value->id]);
            }


            $row['i'] = $iNum++;
            $row['reference'] = $reference;
            $row['paper'] = "<a href='".$route."'>".$paper."</a>";
            $row['date'] = $i_date;
            $row['type'] = $value->so_type;
            $row['req_by'] = $value->created_by;
            $row['division'] = $value->division;
            $row['project'] = $project;
            $row['company'] = (isset($tagCompany[$value->company_id])) ? $tagCompany[$value->company_id] : "N/A";
            $row['items'] = $item_amount;
            $row['notes'] = $notes;
            $row['div_appr'] = $div_appr;
            $row['dir_appr'] = $dir_appr;
            $row['input_date'] = $input_date;
            $row['action'] = $btnDelete;



            if (RolesManagement::actionStart($act, 'read')) {
                $col[] = $row;
                // $search = $request->search['value'];
                // if ($search != "") {
                //     if (strpos(strtolower($paper), strtolower($search)) !== false || strpos(strtolower($reference), strtolower($search)) !== false || strpos(strtolower($value->so_type), strtolower($search)) !== false || strpos(strtolower($project), strtolower($search)) !== false || strpos(strtolower($row['company']), strtolower($search)) !== false) {
                //         $col[] = $row;
                //     }
                // } else {
                //     $col[] = $row;
                // }
            }
        }

        return DataTables::collection($col)
            ->rawColumns(['paper', 'div_appr', 'dir_appr', 'input_date', 'action'])
            ->with('type', $category)
            ->make(true);

        // $row = [];
        // $irow = 0;
        // foreach ($col as $key => $value) {
        //     if ($key >= $request->start && $irow < $request->length) {
        //         $irow++;
        //         $row[] = $value;
        //     }
        // }

        // $result = array(
        //     "recordsTotal" => count($row),
        //     "recordsFiltered" => count($col),
        //     "draw" => $request->draw,
        //     "data" => $row,
        //     "type" => $category
        // );

        // return json_encode($result);
    }

    function getSoReject(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $so = Asset_sre::whereIn('company_id', $id_companies)
            ->whereNotNull('so_rejected_by')
            ->orderBy('id', 'desc')
            ->get();
        $so_det = Asset_sre_detail::all();
        $det = array();
        foreach ($so_det as $item){
            $det[$item->so_id][] = $item->id;
        }
        $project = Marketing_project::whereIn('company_id', $id_companies)->get();
        $pro_name = array();
        foreach ($project as $item){
            $pro_name[$item->id] = $item->prj_name;
        }
        $view_company = [];
        $comp = ConfigCompany::all();
        foreach ($comp as $key1 => $val){
            $view_company[$val->id] = $val->tag;
        }

        $row = [];
        $so_waiting = [];
        foreach ($so as $i => $item){
            $so_waiting['no'] = $i+ 1;
            $so_waiting['so_num'] = "<a href='".URL::route('so.view', $item->id)."' class='text-hover-danger'>".$item->so_num."</a>";
            $so_waiting['so_date'] = date('d F Y', strtotime($item->so_date));
            $so_waiting['so_type'] = $item->so_type;
            $so_waiting['created_by'] =$item->created_by;
            $so_waiting['division'] = $item->division;
            $so_waiting['project'] = $pro_name[$item->project];
            $so_waiting['company'] = $view_company[$item->company_id];
            $so_waiting['items'] = count($det[$item->id]);
            $so_waiting['notes'] = strip_tags($item->so_notes);
            if ($item->so_rejected_by == null){
                $so_waiting['appr'] = "<a href='".URL::route('so.appr', $item->id)."' class='text-hover-danger'>waiting <i class='fa fa-clock'></i></a>";
            } else {
                $so_waiting['appr'] = "rejected at ".date('Y-m-d', strtotime($item->so_rejected_at))." by <b>".$item->so_rejected_by."</b>";
            }
            $so_waiting['action'] = "<a href='".route('so.delete', ["type" => "so", "id" => $item->id])."' class='btn btn-xs btn-icon btn-danger'><i class='fa fa-trash'></i></a>";
            if (RolesManagement::actionStart('so','read')) {
                $row[] = $so_waiting;
            } else {
                $row[] = [];
            }

        }

        $data = [
            'data' => $row,
        ];
//        dd($data);
        return json_encode($data);
    }
    function getSoBank(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $so = Asset_sre::whereIn('company_id', $id_companies)
            ->whereNotNull('so_approved_by')
            ->orderBy('id', 'desc')
            ->get();
        $so_det = Asset_sre_detail::all();
        $det = array();
        foreach ($so_det as $item){
            $det[$item->so_id][] = $item->id;
        }
        $project = Marketing_project::whereIn('company_id', $id_companies)->get();
        $pro_name = array();
        foreach ($project as $item){
            $pro_name[$item->id] = $item->prj_name;
        }
        $view_company = [];
        $comp = ConfigCompany::all();
        foreach ($comp as $key1 => $val){
            $view_company[$val->id] = $val->tag;
        }

        $row = [];
        $so_waiting = [];
        foreach ($so as $i => $item){
            $so_waiting['no'] = $i+ 1;
            $so_waiting['so_num'] = "<a href='".URL::route('so.view', $item->id)."' class='text-hover-danger'>".$item->so_num."</a>";
            $so_waiting['so_date'] = date('d F Y', strtotime($item->so_date));
            $so_waiting['so_type'] = $item->so_type;
            $so_waiting['created_by'] =$item->created_by;
            $so_waiting['division'] = $item->division;
            $so_waiting['project'] = $pro_name[$item->project];
            $so_waiting['company'] = $view_company[$item->company_id];
            $so_waiting['items'] = count($det[$item->id]);
            $so_waiting['notes'] = strip_tags($item->so_notes);
            if ($item->so_approved_by == null){
                $so_waiting['appr'] = "<a href='".URL::route('so.appr', $item->id)."' class='text-hover-danger'>waiting <i class='fa fa-clock'></i></a>";
            } else {
                $so_waiting['appr'] = "approved at ".date('Y-m-d', strtotime($item->so_approved_at))." by <b>".$item->so_approved_by."</b>";
            }
            $so_waiting['action'] = "<a href='".route('so.delete', ["type" => "so", "id" => $item->id])."' class='btn btn-xs btn-icon btn-danger'><i class='fa fa-trash'></i></a>";

            if (RolesManagement::actionStart('so','read')) {
                $row[] = $so_waiting;
            } else {
                $row[] = [];
            }
        }

        $data = [
            'data' => $row,
        ];
//        dd($data);
        return json_encode($data);
    }
    function getSoWaiting(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $so = Asset_sre::whereIn('company_id', $id_companies)
            ->whereNull('so_rejected_by')
            ->whereNull('so_approved_by')
            ->orderBy('id', 'desc')
            ->get();
        $so_det = Asset_sre_detail::all();
        $det = array();
        foreach ($so_det as $item){
            $det[$item->so_id][] = $item->id;
        }
        $project = Marketing_project::whereIn('company_id', $id_companies)->get();
        $pro_name = array();
        foreach ($project as $item){
            $pro_name[$item->id] = $item->prj_name;
        }
        $view_company = [];
        $comp = ConfigCompany::all();
        foreach ($comp as $key1 => $val){
            $view_company[$val->id] = $val->tag;
        }

        $row = [];
        $so_waiting = [];
        foreach ($so as $i => $item){
            $so_waiting['no'] = $i+ 1;
            $so_waiting['so_num'] = "<a href='".URL::route('so.view', $item->id)."' class='text-hover-danger'>".$item->so_num."</a>";
            $so_waiting['so_date'] = date('d F Y', strtotime($item->so_date));
            $so_waiting['so_type'] = $item->so_type;
            $so_waiting['created_by'] =$item->created_by;
            $so_waiting['division'] = $item->division;
            $so_waiting['project'] = (isset($pro_name[$item->project]))?$pro_name[$item->project]:'';
            $so_waiting['company'] = $view_company[$item->company_id];
            $so_waiting['items'] = count($det[$item->id]);
            $so_waiting['notes'] = strip_tags($item->so_notes);
            if ($item->so_approved_by == null){
                $so_waiting['appr'] = "<a href='".URL::route('so.appr', $item->id)."' class='text-hover-danger'>waiting <i class='fa fa-clock'></i></a>";
            } else {
                $so_waiting['appr'] = "approved at ".date('Y-m-d', strtotime($item->so_approved_at))." by <b>".$item->so_approved_by."</b>";
            }
            $so_waiting['action'] = "<a href='".route('so.delete', ["type" => "so", "id" => $item->id])."' class='btn btn-xs btn-icon btn-danger'><i class='fa fa-trash'></i></a>";

            if (RolesManagement::actionStart('so','read')) {
                $row[] = $so_waiting;
            } else {
                $row[] = [];
            }
        }

        $data = [
            'data' => $row,
        ];
//        dd($data);
        return json_encode($data);
    }
    function so_index(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $so = Asset_sre::whereIn('company_id', $id_companies)->get();
        $so_det = Asset_sre_detail::all();
        $det = array();
        foreach ($so_det as $item){
            $det[$item->so_id][] = $item->id;
        }
        $type_wo = Asset_type_wo::all();
        $project = Marketing_project::whereIn('company_id', $id_companies)->get();
        $pro_name = array();
        foreach ($project as $item){
            $pro_name[$item->id] = $item->prj_name;
        }

        $user_division = DB::table('rms_roles_divisions')
        ->select('id', 'name', 'id_rms_divisions')
        ->where('id', Auth::user()->id_rms_roles_divisions)
        ->first();

        $division = Division::find($user_division->id_rms_divisions);
        $div = Division::where('name', '!=', 'admin')
            ->get();

        $src = Finance_coa_source::where('name', 'wo')->first();
        $tp_parent = Finance_coa::where('source', 'like', '%"'.$src->id.'"%')
            ->get()->pluck('code');
        $tp = Finance_coa::where(function($query) use($tp_parent){
            foreach ($tp_parent as $key => $value) {
                $parent_code = rtrim($value, 0);
                $query->where('parent_id', 'like', "$parent_code%");
            }
        })->orderBy('code')->get();

        return view('so.index', [
            'so' => $so,
            'type_wo' => $tp,
            'project' => $project,
            'pro_name' => $pro_name,
            'det' => $det,
            'division' => $division,
            'div' => $div
        ]);
    }

    function so_add(Request $request){
        ActivityConfig::store_point('so', 'create');
        $arrRomawi	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $sre = new Asset_sre();

        $request_date = date("Y-m-d", strtotime($request['request_date']));
        $so_num = Asset_sre::selectRaw("*, CAST(SUBSTRING(so_num, 1, LOCATE('/', so_num) - 1) as UNSIGNED) as so_last")
            ->where('so_date', 'like', ''.date('Y')."-%")
            ->where('company_id', Session::get('company_id'))
            ->orderBy('so_last', 'desc')
            ->first();
        if (!empty($so_num)) {
            $last_num = explode("/", $so_num->so_num);
            $num = sprintf("%03d", (intval($last_num[0]) + 1));
        } else {
            $num = sprintf("%03d", 1);
        }
        $so_num_id = sprintf('%03d',$num).'/'.strtoupper(\Session::get('company_tag')).'/SO/'.$arrRomawi[date("n")].'/'.date("y");

        $sre->so_type = $request->so_type;
        $sre->so_num = $so_num_id;
        $sre->division = $request->division;
        $sre->project = $request->project;
        $sre->so_date = date('Y-m-d H:i:s');
        $sre->reference = $request->reference;
        $sre->so_notes = $request->notes;
        $sre->deliver_to = $request->d_to;
        $sre->deliver_time = $request->d_time;
        $sre->company_id = Session::get('company_id');
        if (isset($request->payment_method)){
            $sre->bd = 1;
        } else {
            $sre->bd = 0;
        }
        $sre->created_by = Auth::user()->username;

        $sre->save();

        $notif['module'] = "so";
        $notif['action'] = "approvediv1";
        $notif['paper']  = $sre->so_num;
        $notif['url']    = route('so.appr', $sre->id);
        $notif['id']     = $sre->id;

        Notification::save($notif);

        $job_name = $request->name;
        $job_qty = $request->qty;
        foreach ($job_name as $i => $v){
            $sre_det = new Asset_sre_detail();

            $sre_det->so_id = $sre->id;
            $sre_det->job_desc = $v;
            $sre_det->qty = $job_qty[$i];
            $sre_det->created_by = Auth::user()->username;

            $sre_det->save();
        }

        return redirect()->route('general.so');
    }

    function so_view($id){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $so = Asset_sre::where('id', $id)->first();
        $wo = Asset_wo::where('reference', $so->se_num)->first();
        $project = Marketing_project::whereIn('company_id', $id_companies)->get();
        foreach ($project as $item){
            $pro_name[$item->id] = $item->prj_name;
        }
        $so_det = Asset_sre_detail::where('so_id', $id)->get();

        return view('so.view', [
            'so' => $so,
            'wo' => $wo,
            'pro' => $pro_name,
            'so_det' => $so_det
        ]);
    }

    public function nextDocNumber($code,$year){
        $cek = Asset_sre::where('so_num','like','%'.$code.'%')
            ->where('so_date','like','%'.date('y').'-%')
            ->where('company_id', \Session::get('company_id'))
            ->whereNull('deleted_at')
            ->orderBy('id','DESC')
            ->get();

//        dd($cek);
        if (count($cek) > 0){
            $frNum = $cek[0]->fr_num;
            $frDate = $cek[0]->fr_date;
            $str = explode('/', $frNum);
//            dd(date('y',strtotime($year)));
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

    function so_appr($id){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $so = Asset_sre::where('id', $id)->first();
        $project = Marketing_project::whereIn('company_id', $id_companies)->get();
        foreach ($project as $item){
            $pro_name[$item->id] = $item->prj_name;
        }
        $so_det = Asset_sre_detail::where('so_id', $id)->get();

        return view('so.appr', [
            'so' => $so,
            'pro' => $pro_name,
            'so_det' => $so_det
        ]);
    }

    function so_approve(Request $request){
        ActivityConfig::store_point('so', 'approve_div');
        $so = Asset_sre::find($request->id);
        $so->so_approved_by = Auth::user()->username;
        $so->so_approved_at = date('Y-m-d H:i:s');
        $so->so_approved_notes = $request->notes;

        $so_data = Asset_sre::where('id', $request->id)->first();

        $so_num = $so_data->so_num;
        $so->rfq_so_num = str_replace("SO", "RFQSO", $so_num);
        $so->rfq_so_date = date('Y-m-d H:i:s');

        $notif['module'] = "sr";
        $notif['module_prev'] = "so";
        $notif['action'] = "approvedir";
        $notif['paper']  = $so->rfq_so_num;
        $notif['url']    = route('sr.appr', $so->id);
        $notif['id']     = $so->id;
        $notif['action_prev'] = "approvediv1";

        Notification::save($notif);

        if ($so->save()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function so_reject(Request $request){
        $so = Asset_sre::find($request->id);
        $so->so_rejected_by = Auth::user()->username;
        $so->so_rejected_at = date('Y-m-d H:i:s');
        $so->so_rejected_notes = $request->notes;

        if ($so->save()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    // SR

    function sr_index(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $sr = Asset_sre::whereIn('company_id', $id_companies)
            ->orderBy('id', 'desc')
            ->get();
        $sr_det = Asset_sre_detail::all();
        $det = array();
        foreach ($sr_det as $item){
            $det[$item->so_id][] = $item->id;
        }
        $type_wo = Asset_type_wo::all();
        $project = Marketing_project::whereIn('company_id', $id_companies)->get();
        $pro_name = array();
        foreach ($project as $item){
            $pro_name[$item->id] = $item->prj_name;
        }
        return view('sr.index', [
            'sr' => $sr,
            'type_wo' => $type_wo,
            'project' => $project,
            'pro_name' => $pro_name,
            'det' => $det
        ]);
    }

    function sr_appr($id){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $so = Asset_sre::where('id', $id)->first();
        $project = Marketing_project::whereIn('company_id', $id_companies)->get();
        foreach ($project as $item){
            $pro_name[$item->id] = $item->prj_name;
        }
        $so_det = Asset_sre_detail::where('so_id', $id)->get();

        return view('sr.appr', [
            'so' => $so,
            'pro' => $pro_name,
            'so_det' => $so_det
        ]);
    }

    function sr_view($id){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $so = Asset_sre::where('id', $id)->first();
        $project = Marketing_project::whereIn('company_id', $id_companies)->get();
        foreach ($project as $item){
            $pro_name[$item->id] = $item->prj_name;
        }
        $so_det = Asset_sre_detail::where('so_id', $id)->get();

        return view('sr.view', [
            'so' => $so,
            'pro' => $pro_name,
            'so_det' => $so_det
        ]);
    }

    function sr_approve(Request $request){
        $so = Asset_sre::find($request->id);
        $so->rfq_approved_by = Auth::user()->username;
        $so->rfq_approved_at = date('Y-m-d H:i:s');
        $so->rfq_approved_notes = $request->notes;

        $so_data = Asset_sre::where('id', $request->id)->first();

        $so_num = $so_data->rfq_so_num;
        $so->se_num = str_replace("RFQSO", "SE", $so_num);
        $so->se_date = date('Y-m-d H:i:s');
        $items = $request->id_item;
        $qty = $request->qty;
        for ($i=0; $i < count($items); $i++){
            $so_det = Asset_sre_detail::find($items[$i]);
            $so_det->qty_appr = $qty[$i];
            $so_det->save();
        }

        if ($so->save()){
            $notif['module'] = "se";
            $notif['module_prev'] = "sr";
            $notif['action'] = "approvediv1";
            $notif['paper']  = $so->se_num;
            $notif['url']    = route('se.appr', $so->id);
            $notif['id']     = $so->id;
            $notif['action_prev'] = "approvedir";

            Notification::save($notif);
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function sr_reject(Request $request){
        $so = Asset_sre::find($request->id);
        $so->rfq_rejected_by = Auth::user()->username;
        $so->rfq_rejected_at = date('Y-m-d H:i:s');
        $so->rfq_rejected_notes = $request->notes;

        if ($so->save()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function se_index(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $sr = Asset_sre::whereIn('company_id', $id_companies)
            ->where('se_date', 'like', date('Y')."%")
            ->orderBy('id', 'desc')
            ->get();
        $idSr = [];
        foreach ($sr as $value) {
            $idSr[] = $value->id;
        }
        $sr_det = Asset_sre_detail::whereIn('so_id', $idSr)->get();
        $det = array();
        foreach ($sr_det as $item){
            $det[$item->so_id][] = $item->id;
        }
        $type_wo = Asset_type_wo::all();
        $project = Marketing_project::whereIn('company_id', $id_companies)->get();
        $pro_name = array();
        foreach ($project as $item){
            $pro_name[$item->id] = $item->prj_name;
        }
        return view('se.index', [
            'sr' => $sr,
            'type_wo' => $type_wo,
            'project' => $project,
            'pro_name' => $pro_name,
            'det' => $det
        ]);
    }

    function se_appr($id){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $so = Asset_sre::where('id', $id)->first();
        if (empty($so->se_input_at) && empty($so->se_approved_at)){
            $link = URL::route('se.input_post');
            $status = "input";
        } elseif (empty($so->se_approved_at) ){
            $link = URL::route('se.dir_post');
            $status = "dir";
        }  else {
            $link = "";
            $status = "";
        }
        $pro = Marketing_project::all();
        foreach ($pro as $value){
            $pro_name[$value->id] = $value->prj_name;
        }

        $so_detail = Asset_sre_detail::where('so_id', $id)->get();


        $tax = Pref_tax_config::all();
        foreach ($tax as $key => $value){
            $id_tax[] = $value->id;
            $conflict[$value->id] = json_decode($value->conflict_with);
            $formula[$value->id] = $value->formula;
        }

        $vendor = Procurement_vendor::whereIn('company_id', $id_companies)->get();

        return view('se.appr', [
            'so' => $so,
            'pro' => $pro_name,
            'vendors' => $vendor,
            'items' => $so_detail,
            'taxes' => $tax,
            'conflict' => json_encode($conflict),
            'formula' => json_encode($formula),
            'link_post' => $link,
            'id_tax' => $id_tax,
            'status' => $status
        ]);
    }

    function se_view($id){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $so = Asset_sre::where('id', $id)->first();
        if (empty($so->se_input_at) && empty($so->ack_time) && empty($so->se_approved_at)){
            $link = URL::route('se.input_post');
            $status = "input";
        } elseif (empty($so->ack_time) && empty($so->se_approved_at)){
            $link = URL::route('se.ack_post');
            $status = "ack";
        } elseif (empty($so->se_approved_at) ){
            $link = URL::route('se.dir_post');
            $status = "dir";
        }  else {
            $link = "";
            $status = "";
        }
        $pro = Marketing_project::all();
        foreach ($pro as $value){
            $pro_name[$value->id] = $value->prj_name;
        }

        $so_detail = Asset_sre_detail::where('so_id', $id)->get();


        $tax = Pref_tax_config::all();
        foreach ($tax as $key => $value){
            $id_tax[] = $value->id;
            $conflict[$value->id] = json_decode($value->conflict_with);
            $formula[$value->id] = $value->formula;
        }

        // $vendor = Procurement_vendor::all();
        $vendor = Procurement_vendor::whereIn('company_id', $id_companies)->get();

        return view('se.view', [
            'so' => $so,
            'pro' => $pro_name,
            'vendors' => $vendor,
            'items' => $so_detail,
            'taxes' => $tax,
            'conflict' => json_encode($conflict),
            'formula' => json_encode($formula),
            'link_post' => $link,
            'id_tax' => $id_tax,
            'status' => $status
        ]);
    }

    function se_approve(Request $request){
        $id = $request->id_fr;
        if (isset($request->edit)){
            $pre = Asset_sre::find($request->edit);

        } else {
            $pre = Asset_sre::find($id);
        }

        $dir = public_path('media/asset/');

        $pre->suppliers = json_encode($request->vendor);
        $pre->ppns = (empty($request->tax)) ? null : json_encode($request->tax);
        $pre->dps = json_encode($request->dp);
        $pre->discs = json_encode($request->discount);
        $pre->tops = json_encode($request->terms_pay);
        $pre->notes = json_encode($request->notes);
        $pre->currencies = json_encode($request->currency);
        $pre->delivers = json_encode($request->d_to);
        $pre->deliver_times = json_encode($request->d_time);
        $pre->terms = json_encode($request->terms);
        $quot = $request->file('file_quot');
        if ($request->status == "input"){
            $pre->se_input_at = date('Y-m-d H:i:s');
            $pre->se_input_by = Auth::user()->username;
            $notif['module'] = "se";
            $notif['action'] = "approvedir";
            $notif['paper']  = $pre->se_num;
            $notif['url']    = route('se.appr', $pre->id);
            $notif['id']     = $pre->id;
            $notif['action_prev'] = "approvediv1";

            Notification::save($notif);
        } elseif ($request->status == "ack"){
            $pre->ack_by = Auth::user()->username;
            $pre->ack_time = date('Y-m-d H:i:s');
        } elseif ($request->status == "dir"){

            $pre->se_approved_by = Auth::user()->username;
            $pre->se_approved_at = date('Y-m-d H:i:s');
            $pre->se_approved_notes = $request->pev_notes;
            $paper = explode("/", $pre->se_num);
            $tag = $paper[1];

            // save to PO
            $pre_data = Asset_sre::where('id', $id)->first();
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
//            dd($request->radio);
            $arrRomawi	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
            foreach ($arr_idx as $key => $value){
                $newidx = explode("-", $value);
                $newpo[end($newidx)][] = $key;
            }
//            dd($data);

            $pref = Preference_config::where('id_company', $pre->company_id)->first();
            $wo_signature = (!empty($pref->wo_signature)) ? json_decode($pref->wo_signature, true) : [];

            foreach ($newpo as $x => $pox){
                $total_price = 0;
                foreach($pox as $ise){
                    $total_price += str_replace(",", ",", $up_po[$ise][$x]) * $qty_po[$ise];
                }

                $bypass = false;
                $minArr = [];
                $maxArr = [];
                $bypassArr = [];
                if(is_array($wo_signature) && !empty($wo_signature)){
                    $minArr = $wo_signature['min'];
                    $maxArr = $wo_signature['max'];
                    if(isset($wo_signature['bypass'])){
                        $bypassArr = $wo_signature['bypass'];
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

                $po_num = Asset_wo::where('created_at', 'like', ''.date('Y')."-%")
                    ->where('wo_num', 'like', "%".$tag."%")
                    ->orderBy('id', 'desc')
                    ->first();
                if (!empty($po_num)) {
                    $last_num = explode("/", $po_num->wo_num);
                    $num = sprintf("%03d", (intval($last_num[0]) + 1));
                } else {
                    $num = sprintf("%03d", 1);
                }

                $supp_po = $request->vendor;

                $po = new Asset_wo();


                $po->created_by = $pre_data->created_by;
                $po->wo_type = $pre_data->so_type;
                $po->supplier_id = $supp_po[$x];
                $po->req_date = date('Y-m-d');
                $po->wo_num = $num."/".strtoupper($tag)."/WO/".$arrRomawi[date('n')]."/".date('y');
                $po->project = $pre_data->project;
                $po->division = $pre_data->division;
                $po->reference = $pre_data->se_num;
                $po->deliver_to = $d_to[$x];
                $po->deliver_time = $d_time[$x];
                $po->currency = $curr_po[$x];
                $po->discount = $disc_po[$x];
                $po->dp = $dp_po[$x];
                if (isset($ppn_po[$x])){
                    $po->ppn = json_encode($ppn_po[$x]);
                }
                $po->terms_payment = $pay_term[$x];
                $po->terms = $term_po[$x];
                $po->notes = $notes_po[$x];
                $po->so_note = $pre_data->so_notes;
                $po->company_id = $pre->company_id;
                $po->tc_id = $pre->tc_id;
                $po->save();
                $notif['module'] = "wo";
                $notif['module_prev'] = "se";
                $notif['action'] = "approvedir";
                $notif['paper']  = $po->wo_num;
                $notif['url']    = route('wo.appr', $po->id);
                $notif['id']     = $po->id;
                $notif['id_prev'] = $pre->id;
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
                        $po_det = new Asset_wo_detail();
                        $det = Asset_sre_detail::where('id', $itempo)->first();

                        $po_det->job_desc = $det->job_desc;
                        $po_det->qty = $qty_po[$itempo];
                        $po_det->unit_price = $price;
                        $po_det->wo_id = $po->id;
                        $po_det->save();
                    }
                }
            }

        }
        if (!empty($quot)){
            $file_quot = (!empty($pre->attach1)) ? json_decode($pre->attach1) : array();
            for ($i = 0; $i < count($quot); $i++){
                if (isset($quot[$i])) {
                    $newName = "quotation_WO(".$id.")(".$i.").".$quot[$i]->getClientOriginalExtension();
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
            $pre_det = Asset_sre_detail::find($ids[$i]);
            $pre_det->unit_price = json_encode(str_replace(",", "", $up[$ids[$i]]));
            $idx = (!empty($rad[$ids[$i]])) ? explode("-", $rad[$ids[$i]]) : null;
            $pre_det->supp_idx = (!empty($rad[$ids[$i]])) ? end($idx) : null;
            $pre_det->save();
        }

        return redirect()->route('se.index');
    }

    function se_reject(Request $request){
        $id = $request->id;
        $pre = Asset_sre::find($id);
        $pre->se_rejected_by = Auth::user()->username;
        $pre->se_rejected_at = date('Y-m-d H:i:s');
        if ($pre->save()){
            $data['del'] = 1;
        } else {
            $data['del'] = 0;
        }

        return json_encode($data);
    }

    function delete($type, $id){
        $sre = Asset_sre::find($id);
        $sre->deleted_by = Auth::user()->username;
        $sre->save();
        if ($sre->delete()) {
            return redirect()->back();
        }
    }
}
