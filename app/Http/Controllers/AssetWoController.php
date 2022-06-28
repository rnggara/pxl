<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Asset_po;
use App\Models\Asset_wo;
use App\Models\Asset_sre;
use App\Models\Asset_item;
use App\Models\Finance_coa;
use App\Rms\RolesManagement;
use Illuminate\Http\Request;
use App\Helpers\Notification;
use App\Models\Asset_type_po;
use App\Models\Asset_type_wo;
use App\Models\ConfigCompany;
use App\Helpers\FileManagement;
use App\Models\Asset_po_detail;
use App\Models\Asset_wo_detail;
use App\Models\Ha_paper_permit;
use App\Models\Pref_tax_config;
use App\Models\Marketing_project;
use App\Models\Preference_config;
use App\Models\Finance_coa_source;
use App\Models\Finance_invoice_in;
use App\Models\Procurement_vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Finance_invoice_in_pay;
use Yajra\DataTables\Facades\DataTables;

class AssetWoController extends Controller
{
    function lists(Request $request, $category){

        $whereSearch = " 1";

        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }

        if ($category == "waiting") {
            $whereCategory = " ba_at is null and rejected_time is null";
        } elseif($category == "bank") {
            $whereCategory = " ba_at is not null";
        } elseif($category == "reject") {
            $whereCategory = " rejected_time is not null";
        }

        $year = intval(date('Y'));
        $whereDate = " req_date > '".($year-1)."'-01-01";

        $poTotal = Asset_wo::whereIn('company_id', $id_companies)
            ->whereRaw($whereCategory)
            ->whereRaw($whereDate)
            ->whereRaw($whereSearch)
            ->where('req_date', '>=', '2020-01-01')
            ->orderBy('id', 'desc')
            ->count();

        $search = $request->search['value'];
        if($search != ""){
            $whereSearch = " (wo_num like '%$search%' or marketing_projects.prj_name like '%$search%' or asset_organization.name like '%$search%')";
        }

        $po = Asset_wo::select("asset_wo.*", 'marketing_projects.prj_name as project_name', 'asset_organization.name as supplier_name')
            ->whereIn('asset_wo.company_id', $id_companies)
            ->leftJoin('marketing_projects', 'asset_wo.project', '=', 'marketing_projects.id')
            ->leftJoin('asset_organization', 'asset_wo.supplier_id', '=', 'asset_organization.id')
            ->whereRaw($whereCategory)
            ->whereRaw($whereDate)
            // ->whereRaw($whereSearch)
            ->where('req_date', '>=', '2020-01-01')
            ->orderBy('asset_wo.id', 'desc')
            // ->offset($request->start)
            // ->limit($request->length)
            ->get();

        $detail = Asset_wo_detail::all();
        $listItem = [];
        $detItem = [];
        foreach ($detail as $key => $value) {
            $listItem[$value->wo_id][] = $value->qty * $value->unit_price;
            $detItem[$value->wo_id][] = $value->job_desc;
        }

        $project = Marketing_project::whereIn('company_id', $id_companies)->get();
        $listProject = [];
        foreach ($project as $key => $value) {
            $listProject[$value->id] = $value;
        }

        $vendor = Procurement_vendor::all();
        $listVendor = array();
        foreach ($vendor as $item) {
            $listVendor[$item->id] = $item;
        }

        $id_tax = [];
        $conflict = [];
        $formula = [];

        $tax = Pref_tax_config::all();
        foreach ($tax as $key => $value){
            $id_tax[] = $value->id;
            $conflict[$value->id] = json_decode($value->conflict_with);
            $formula[$value->id] = $value->formula;
        }

        $tagCompany = [];
        $comp = ConfigCompany::all();
        foreach ($comp as $key1 => $val){
            $tagCompany[$val->id] = $val->tag;
        }

        $col = [];
        $iNum = 1 + $request->start;
        foreach ($po as $key => $value) {
            $prj = "N/A";
            $supplier = "N/A";
            $amount = 0;
            $ppn_sum = 0;
            $count_item = (isset($detItem[$value->id])) ?  count($detItem[$value->id]) : 0;

            if (isset($listProject[$value->project])) {
                $prj = $listProject[$value->project]['prj_name'];
            }

            if (isset($listVendor[$value->supplier_id])) {
                $supplier = $listVendor[$value->supplier_id]['name'];
            }

            if (isset($listItem[$value->id])) {
                $amount += array_sum($listItem[$value->id]);
            }

            $amount -= $value->discount;

            if (!empty($value->ppn)) {
                $taxPO = json_decode($value->ppn);
                if (is_array($taxPO)) {
                    foreach ($taxPO as $p) {
                        if (isset($formula[$p])) {
                            $sum = $amount;
                            $ma ='$sum * 0.1';
                            $res = eval('return '.$formula[$p].';');
                            $ppn_sum += $res;
                        }

                    }
                }
            }

            if(empty($value->ba_at)){
                $ba = "waiting";

                if(!empty($value->approved_time)){
                    $ba = '<a href="Javascript:ba_upload('.$value->id.', \''.$value->wo_num.'\')" class="btn btn-link btn-xs">waiting <i class="fa fa-clock"></i></a>';
                }
            } else {
                $ba = date("d F Y", strtotime($value->ba_at));
            }


            $total_amount = $amount + $ppn_sum - $value->dp;

            $btnDelete = "";
            $appr = "waiting";

            if (RolesManagement::actionStart('wo', 'delete')) {
                $btnDelete = "<button onclick='delete_po(".$value->id.")' class='btn btn-icon btn-xs btn-danger'><i class='fa fa-trash'></i></button>";
            }

            if (empty($value->approved_time)) {
                if (RolesManagement::actionStart('wo','approvedir')) {
                    $appr = '<a href="'.route('wo.appr', $value->id).'" class="btn btn-link btn-xs">waiting <i class="fa fa-clock"></i></a>';
                }
            } else {
                $appr = date("d F Y", strtotime($value->approved_time));
            }

            $row = [];
            $row['i'] = ($iNum++) - $request->start;
            $row['paper'] = "<a href='".route('wo.view', $value->id)."'>".$value->wo_num."</a>";
            $row['type'] = $value->wo_type;
            $row['req_by'] = $value->request_by;
            $row['req_date'] = $value->req_date;
            $row['description'] = (isset($detItem[$value->id])) ? $detItem[$value->id][0] : "";
            $row['project'] = $value->project_name;
            $row['company'] = (isset($tagCompany[$value->company_id])) ? $tagCompany[$value->company_id] : "N/A";
            $row['supplier'] = $value->supplier_name;
            $row['amount'] = number_format($total_amount, 2);
            $row['items'] = $count_item;
            $row['appr'] = $appr;
            $row['ba'] = $ba;
            $row['action'] = $btnDelete;
            if (RolesManagement::actionStart('wo', 'read')) {
                $col[] = $row;
            }
        }

        return DataTables::collection($col)
            ->rawColumns(['paper', 'appr', 'ba', 'action', 'description'])
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

        $search = $request->search['value'];

        $filtered = $poTotal;

        if($search != ""){
            $filtered = count($col);
        }

        $result = array(
            "recordsTotal" => count($col),
            "recordsFiltered" => $filtered,
            "draw" => $request->draw,
            "data" => $col,
            "type" => $category
        );

        return json_encode($result);
    }

    function index(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $po = Asset_wo::whereIn('company_id', $id_companies)
            ->where('req_date', '>=', '2019-01-01')
            ->orderBy('id', 'desc')
            ->get();
        $pro = Marketing_project::whereIn('company_id', $id_companies)->get();
        $pro_name = array();
        foreach ($pro as $value){
            $pro_name[$value->id] = $value->prj_name;
        }

        $vendor = Procurement_vendor::all();
        $vendor_name = array();
        foreach ($vendor as $item) {
            $vendor_name[$item->id] = $item->name;
        }

        $po_det = Asset_wo_detail::all();
        $det = [];
        foreach ($po_det as $value){
            $det[$value->wo_id][] = $value;
        }

        $id_tax = [];
        $conflict = [];
        $formula = [];

        $tax = Pref_tax_config::all();
        foreach ($tax as $key => $value){
            $id_tax[] = $value->id;
            $conflict[$value->id] = json_decode($value->conflict_with);
            $formula[$value->id] = $value->formula;
        }

        $wo_type = Asset_type_wo::all();

        $src = Finance_coa_source::where('name', 'wo')->first();
        $tp_parent = Finance_coa::where('source', 'like', '%"'.$src->id.'"%')
            ->get()->pluck('code');
        $tp = Finance_coa::where(function($query) use($tp_parent){
            foreach ($tp_parent as $key => $value) {
                $parent_code = rtrim($value, 0);
                $query->where('parent_id', 'like', "$parent_code%");
            }
        })->orderBy('code')->get();

        return view('wo.index', [
            'po' => $po,
            'pro_name' => $pro_name,
            'vendor_name' => $vendor_name,
            'formula' => $formula,
            'det' => $det,
            'wo_type' => $tp,
            'tax' => $tax
        ]);
    }

    function detail($id){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $po = Asset_wo::where('id', $id)->first();
        $po_detail = Asset_wo_detail::where('wo_id', $id)
            ->get();

        $vendor = Procurement_vendor::all();
        foreach ($vendor as $item) {
            $vendor_name[$item->id] = $item->name;
        }

        $tax = Pref_tax_config::all();
        foreach ($tax as $key => $value){
            $id_tax[] = $value->id;
            $tax_name[$value->id] = $value->tax_name;
            $conflict[$value->id] = json_decode($value->conflict_with);
            $formula[$value->id] = $value->formula;
        }

        $pro = Marketing_project::all();
        foreach ($pro as $value){
            $pro_name[$value->id] = $value->prj_name;
        }


        return view('wo.view', [
            'po' => $po,
            'po_detail' => $po_detail,
            'pro_name' => $pro_name,
            'vendor_name' => $vendor_name,
            'formula' => $formula,
            'tax_name' => $tax_name,
            'tax' => $tax,
        ]);
    }

    function appr($id){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $po = Asset_wo::where('id', $id)->first();
        $po_detail = Asset_wo_detail::where('wo_id', $id)
            ->get();

        $vendor = Procurement_vendor::all();
        foreach ($vendor as $item) {
            $vendor_name[$item->id] = $item->name;
        }

        $tax = Pref_tax_config::all();
        foreach ($tax as $key => $value){
            $id_tax[] = $value->id;
            $tax_name[$value->id] = $value->tax_name;
            $conflict[$value->id] = json_decode($value->conflict_with);
            $formula[$value->id] = $value->formula;
        }

        $pro = Marketing_project::all();
        foreach ($pro as $value){
            $pro_name[$value->id] = $value->prj_name;
        }


        return view('wo.appr', [
            'po' => $po,
            'po_detail' => $po_detail,
            'pro_name' => $pro_name,
            'vendor_name' => $vendor_name,
            'formula' => $formula,
            'tax_name' => $tax_name,
            'tax' => $tax,
        ]);
    }

    function approve(Request $request){
        $po = Asset_wo::find($request->id);
        $po->approved_by = Auth::user()->username;
        $po->approved_time = date('Y-m-d H:i:s');
        $po->appr_notes = $request->notes;

        $po_data = Asset_wo::where('id', $request->id)->first();

        $total_val = str_replace(",", "", $request->val);

//        $inv_in = new Finance_invoice_in();
//        $inv_in->paper_id = $request->id;
//        $inv_in->paper_type = "WO";
//        $inv_in->amount = $total_val;
//        $inv_in->amount_left = (int)$total_val - $po_data->dp;
//        $inv_in->pay_date = date('Y-m-d');
//        $inv_in->app_date = date('Y-m-d');
//        $inv_in->status = 'input';
//        $inv_in->project = $po_data->project;
//
//        $inv_in->save();
//        if ($po_data->dp > 0){
//            $in_pay = new Finance_invoice_in_pay();
//            $in_pay->inv_id = $inv_in->id;
//            $in_pay->pay_num = 1;
//            $in_pay->amount = $po_data->dp;
//            $in_pay->pay_date = date('Y-m-d');
//            $in_pay->description = "Down Payment";
//            $in_pay->save();
//        }

        if ($po->save()){
            $notif['module'] = "wo";
            $notif['action'] = "approvedir";
            $notif['id']     = $po->id;
            $notif['last'] = 1;
            $notif['paper']  = $po->wo_num;
            $notif['url']    = route('po.appr', $po->id);
            $notif['action_prev'] = "approvedir";
            Notification::save($notif);
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function reject(Request $request){
        $po = Asset_wo::find($request->id);
        $po->rejected_by = Auth::user()->username;
        $po->rejected_time = date('Y-m-d H:i:s');



        if ($po->save()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function revise(Request $request){
        $po = Asset_wo::find($request->id);
        $po->rejected_by = Auth::user()->username;
        $po->rejected_time = date('Y-m-d H:i:s');

        $po_data = Asset_wo::where('id', $request->id)->first();

        Asset_sre::where('se_num', $po_data->reference)
            ->update([
                'se_rejected_notes' => $request->notes,
                'se_approved_by' => null,
                'se_approved_at' => null,
                'se_approved_notes' => null,
                'se_input_at' => null,
                'se_input_by' => null,
                'ack_by' => null,
                'ack_time' => null
            ]);

        if ($po->save()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function addInstant(Request $request){

        $po_type = Asset_type_wo::find($request->wo_type);

        $ipo = new Asset_wo();
        $ipo->supplier_id = $request->supplier;
        $ipo->wo_type = $po_type->name;
        // WO NUM
        $po_num = Asset_wo::where('created_at', 'like', ''.date('Y')."-%")
            ->orderBy('created_at', 'desc')
            ->first();
//        dd($po_num);
        if (!empty($po_num)) {
            $last_num = explode("/", $po_num->wo_num);
            $num = sprintf("%03d", (intval($last_num[0]) + 1));
        } else {
            $num = sprintf("%03d", 1);
        }

        $arrRomawi	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $ipo->wo_num = $num."/".strtoupper(Session::get('company_tag'))."/WO/".$arrRomawi[date('n')]."/".date('y');

        $ipo->req_date = $request->date;
        $ipo->project = $request->project;
        $ipo->deliver_to = $request->d_to;
        $ipo->deliver_time = $request->d_time;
        $ipo->reference = $request->paper_code;
        $ipo->currency = $request->currency;
        $ipo->discount = $request->discount;
        $ipo->dp = $request->dp;

        $tax = array();
        if (isset($request->tax)){
            foreach ($request->tax as $item){
                $tax[] = $item;
            }
            $ipo->ppn = json_encode($tax);
        }

        $cat = (isset($request->category) && $request->category != null) ? "|".$request->category : "";

        $ipo->terms_payment = $request->p_terms;
        $ipo->terms = $request->terms;
        $ipo->notes = $request->notes;
        $ipo->approved_time = date("Y-m-d");
        $ipo->approved_by = Auth::user()->username . $cat;
        $ipo->created_by = Auth::user()->username.$cat;
        $ipo->company_id = Session::get('company_id');

        if ($ipo->save()){
            $qty = $request->qty;
            $price = $request->price;
            foreach ($request->desc_item as $key => $item){
                $detail = new Asset_wo_detail();
                $detail->wo_id = $ipo->id;
                $detail->job_desc = $item;
                $detail->qty = $qty[$key];
                $detail->unit_price = str_replace(",", "", $price[$key]);
                $detail->created_by = Auth::user()->username;
                $detail->company_id = Session::get('company_id');
                $detail->save();
            }

            $paper = Ha_paper_permit::where('kode', $request->paper_code)->first();
            $paper->issued_date = date('Y-m-d');
            $paper->issued_by = Auth::user()->username;
            $paper->updated_by = Auth::user()->username;
            $paper->paper_num = $ipo->wo_num;
            $paper->save();
        }

        return redirect()->route('general.wo');
    }

    function ba(Request $request){
       // dd($request);
        $file = $request->file('ba_file');
        $filename = explode(".", $file->getClientOriginalName());
        array_pop($filename);
        $filename = str_replace(" ", "_", implode("_", $filename));

        $newFile = "(".$request->id_wo.")".$filename."-".date('Y_m_d_H_i_s').").".$file->getClientOriginalExtension();
        $hashFile = Hash::make($newFile);
        $hashFile = str_replace("/", "", $hashFile);
        $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\asset\ba");
        if ($upload == 1){
            $wo = Asset_wo::find($request->id_wo);
            $wo->ba_at = date('Y-m-d H:i:s');
            $wo->ba_by = Auth::user()->username;
            $wo->ba_file = $hashFile;
            $wo->save();

            return redirect()->back();
        }
    }

    function print($id){
        $wo = Asset_wo::find($id);
        $details = Asset_wo_detail::where('wo_id', $id)->get();
        $items = Asset_item::all();
        $arrItem = array();
        foreach ($items as $i){
            $arrItem[$i->id] = $i;
        }
        $supplier = Procurement_vendor::all();
        $arrSup = array();
        foreach ($supplier as $item){
            $arrSup[$item->id] = $item;
        }
        $taxes = Pref_tax_config::all();
        $tax = array();
        foreach ($taxes as $item){
            $tax[$item->id] = $item;
        }
        $comp = ConfigCompany::find($wo->company_id);
        $pref = Preference_config::where('id_company', $wo->company_id)->first();
        $woVal = 0;
        foreach ($details as $key => $value) {
            $woVal += $value->qty * $value->unit_price;
        }

        $img = "";

        if (!empty($pref->wo_signature)) {
            $wo_pref = json_decode($pref->wo_signature);


            for ($i=0; $i < 3; $i++) {
                if (isset($wo_pref->min[$i])) {
                    // dd($wo_pref->img[$i]);
                    if ($woVal > $wo_pref->min[$i]) {
                        if ($wo_pref->max[$i] != 0) {
                            if ($woVal <= $wo_pref->max[$i]) {
                                if(isset($wo_pref->img[$i])){
                                    $img = $wo_pref->img[$i];
                                    break;
                                }
                            }
                        } else {
                            if (isset($wo_pref->img[$i])) {
                                $img = $wo_pref->img[$i];
                                break;
                            }
                        }

                    }
                }
            }
        }


        if ($img != "") {
            $img = str_replace("public", "public_html", asset('images/signature/'.$img));
        }


        return view('wo.print', compact('wo', 'details', 'arrItem', 'arrSup', 'tax', 'comp', 'img'));
    }

    function delete($id){
        $wo = Asset_wo::find($id);
        $wo->deleted_by = Auth::user()->username;
        $wo->save();
        if ($wo->delete()) {
            return redirect()->back();
        }
    }

    function edit_notes(Request $request){
        $wo = Asset_wo::find($request->id_wo);
        $wo->notes = $request->notes;

        $wo->save();
        return redirect()->back();
    }
}
