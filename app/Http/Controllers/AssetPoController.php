<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Asset_po;
use App\Models\Asset_pre;
use App\Models\Asset_item;
use App\Models\Finance_coa;
use App\Rms\RolesManagement;
use Illuminate\Http\Request;
use App\Helpers\Notification;
use App\Models\Asset_type_po;
use App\Models\ConfigCompany;
use App\Models\Asset_po_detail;
use App\Models\Ha_paper_permit;
use App\Models\Pref_tax_config;
use App\Models\Marketing_project;
use App\Models\Preference_config;
use App\Models\Asset_new_category;
use App\Models\Finance_coa_source;
use App\Models\Finance_invoice_in;
use App\Models\Procurement_vendor;
use App\Models\Finance_depreciation;
use Illuminate\Support\Facades\Auth;
use App\Models\Finance_invoice_in_pay;
use Yajra\DataTables\Facades\DataTables;

class AssetPoController extends Controller
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
            $whereCategory = " approved_time is null and rejected_time is null";
        } elseif($category == "bank") {
            $whereCategory = " approved_time is not null";
        } elseif($category == "reject") {
            $whereCategory = " rejected_time is not null";
        }

        $year = intval(date('Y'));
        $whereDate = " po_date > '".($year-1)."'-01-01";

        $poTotal = Asset_po::whereIn('company_id', $id_companies)
            ->whereRaw($whereCategory)
            ->whereRaw($whereDate)
            ->whereRaw($whereSearch)
            ->where('po_date', '>=', '2020-01-01')
            ->orderBy('id', 'desc')
            ->count();

        $po = Asset_po::whereIn('company_id', $id_companies)
            ->whereRaw($whereCategory)
            // ->whereRaw($whereDate)
            // ->whereRaw($whereSearch)
            ->where('po_date', '>=', '2020-01-01')
            ->orderBy('id', 'desc')
            // ->offset($request->start)
            // ->limit($request->length)
            ->get();

        $items_data = Asset_item::whereIn('company_id', $id_companies)->get();
        $arrItems = [];
        foreach ($items_data as $item){
            $arrITems[$item->id] = $item;
        }

        $detail = Asset_po_detail::all();
        $listItem = [];
        $detItem = [];
        foreach ($detail as $key => $value) {
            $listItem[$value->po_num][] = $value->qty * $value->price;
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
        $iNum = $request->start+1;
        foreach ($po as $key => $value) {
            $prj = "N/A";
            $supplier = "N/A";
            $amount = 0;
            $ppn_sum = 0;

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

            $total_amount = $amount + $ppn_sum - $value->dp;

            $btnDelete = "";
            $appr = "waiting";

            if (RolesManagement::actionStart('po', 'delete')) {
                $btnDelete = "<button onclick='delete_po(".$value->id.")' class='btn btn-icon btn-xs btn-danger'><i class='fa fa-trash'></i></button>";
            }

            if (empty($value->approved_time)) {
                if (RolesManagement::actionStart('po','approvedir')) {
                    $appr = '<a href="'.route('po.appr', $value->id).'" class="btn btn-link btn-xs">waiting <i class="fa fa-clock"></i></a>';
                }
            } else {
                $appr = date("d F Y", strtotime($value->approved_time));
            }

            if($category == "reject"){
                $appr = "Rejected";
                $appr .= "<br>at<br>".$value->rejected_time;
            }

            $row = [];
            $row['i'] = ($iNum++) - $request->start;
            $row['reference'] = $value->reference;
            $row['paper'] = "<a href='".route('po.view', $value->id)."'>".$value->po_num."</a>";
            $row['type'] = $value->po_type;
            $row['req_by'] = $value->request_by;
            $row['req_date'] = $value->po_date;
            $row['project'] = $prj;
            $row['company'] = (isset($tagCompany[$value->company_id])) ? $tagCompany[$value->company_id] : "N/A";
            $row['supplier'] = $supplier;
            $row['amount'] = number_format($total_amount, 2);
            $row['appr'] = $appr;
            $row['action'] = $btnDelete;
            if (RolesManagement::actionStart('po', 'read')) {
                $col[] = $row;
                // $search = $request->search['value'];
                // if ($search != "") {
                //     if (strpos(strtolower($value->po_num), strtolower($search)) !== false || strpos(strtolower($value->reference), strtolower($search)) !== false || strpos(strtolower($value->po_type), strtolower($search)) !== false || strpos(strtolower($prj), strtolower($search)) !== false || strpos(strtolower($row['company']), strtolower($search)) !== false || strpos(strtolower($supplier), strtolower($search)) !== false) {
                //         $col[] = $row;
                //     }
                // } else {
                //     $col[] = $row;
                // }
            }
        }

        return DataTables::collection($col)
            ->rawColumns(['paper', 'appr', 'action'])
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

        $result = array(
            "recordsTotal" => count($col),
            "recordsFiltered" => $poTotal,
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

        $id_tax = [];
        $conflict = [];
        $formula = [];

        $tax = Pref_tax_config::all();
        foreach ($tax as $key => $value){
            $id_tax[] = $value->id;
            $conflict[$value->id] = json_decode($value->conflict_with);
            $formula[$value->id] = $value->formula;
        }

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

        return view('po.index', [
            'pro_name' => $pro_name,
            'vendor_name' => $vendor_name,
            'formula' => $formula,
            'po_type' => $tp,
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
        $po = Asset_po::where('id', $id)->first();
        $po_detail = Asset_po_detail::where('po_num', $po->id)
            ->get();

        $vendor = Procurement_vendor::all();
        foreach ($vendor as $item) {
            $vendor_name[$item->id] = $item->name;
        }

        $tax = Pref_tax_config::all();
        $id_tax = [];
        $tax_name = [];
        $conflict = [];
        $formula = [];
        foreach ($tax as $key => $value){
            $id_tax[] = $value->id;
            $tax_name[$value->id] = $value->tax_name;
            $conflict[$value->id] = json_decode($value->conflict_with);
            $formula[$value->id] = $value->formula;
        }

        $pro = Marketing_project::all();
        $pro_name = [];
        foreach ($pro as $value){
            $pro_name[$value->id] = $value->prj_name;
        }

        $item = Asset_item::all();
        $item_name = [];
        $item_code = [];
        $item_uom = [];
        foreach ($item as $item) {
            $item_name[$item->item_code] = $item->name;
            $item_code[$item->item_code] = $item->item_code;
            $item_uom[$item->item_code] = $item->uom;
        }

        $item_id = $item->pluck('id', 'item_code');
        $item_type = $item->pluck('type_id', 'item_code');
        $item_deleted = $item->pluck('deleted_at', 'item_code');

        $dep = Finance_depreciation::all()->pluck('id', 'item_id');

        $item_cat = Asset_new_category::all();

        return view('po.view', [
            'po' => $po,
            'po_detail' => $po_detail,
            'pro_name' => $pro_name,
            'vendor_name' => $vendor_name,
            'formula' => $formula,
            'tax_name' => $tax_name,
            'tax' => $tax,
            'item_name' => $item_name,
            'item_code' => $item_code,
            'item_uom' => $item_uom,
            'item_id' => $item_id,
            'item_type' => $item_type,
            'dep' => $dep,
            'item_deleted' => $item_deleted,
            'cat' => $item_cat
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
        $po = Asset_po::where('id', $id)->first();
        $po_detail = Asset_po_detail::where('po_num', $po->id)
            ->get();

        $vendor = Procurement_vendor::all();
        foreach ($vendor as $item) {
            $vendor_name[$item->id] = $item->name;
        }
        $id_tax=[];
        $tax_name=[];
        $conflict=[];
        $formula =[];

        $tax = Pref_tax_config::all();
        foreach ($tax as $key => $value){
            $id_tax[] = $value->id;
            $tax_name[$value->id] = $value->tax_name;
            $conflict[$value->id] = json_decode($value->conflict_with);
            $formula[$value->id] = $value->formula;
        }

        $pro = Marketing_project::whereIn('company_id', $id_companies)->get();
        foreach ($pro as $value){
            $pro_name[$value->id] = $value->prj_name;
        }

        $item = Asset_item::withTrashed()->get();
        foreach ($item as $item) {
            $item_name[$item->item_code] = $item->name;
            $item_code[$item->item_code] = $item->item_code;
            $item_uom[$item->item_code] = $item->uom;
        }

        return view('po.appr', [
            'po' => $po,
            'po_detail' => $po_detail,
            'pro_name' => $pro_name,
            'vendor_name' => $vendor_name,
            'formula' => $formula,
            'tax_name' => $tax_name,
            'tax' => $tax,
            'item_name' => $item_name,
            'item_code' => $item_code,
            'item_uom' => $item_uom
        ]);
    }

    function delete($id){
        $po = Asset_po::find($id);
        $po->deleted_by = Auth::user()->username;
        $po->save();
        Asset_po_detail::where('po_num', $id)->delete();
        if ($po->delete()) {
            $data['error'] = 1;
        } else {
            $data['error'] = 0;
        }

        return json_encode($data);
    }

    function approve(Request $request){
        $po = Asset_po::find($request->id);
        $po->approved_by = Auth::user()->username;
        $po->approved_time = date('Y-m-d H:i:s');
        $po->appr_notes = $request->notes;

        $po_data = Asset_po::where('id', $request->id)->first();

        $total_val = str_replace(",", "", $request->val);

//        $inv_in = new Finance_invoice_in();
//        $inv_in->paper_id = $request->id;
//        $inv_in->paper_type = "PO";
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
            $notif['module'] = "po";
            $notif['action'] = "approvedir";
            $notif['id']     = $po->id;
            $notif['last'] = 1;
            $notif['paper']  = $po->po_num;
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
        $po = Asset_po::find($request->id);
        $po->rejected_by = Auth::user()->username;
        $po->rejected_time = date('Y-m-d H:i:s');
        $po->rejected_notes = $request->notes;

        $po_data = Asset_po::where('id', $request->id)->first();

        // Asset_pre::where('pev_num', $po_data->reference)
        //     ->update([
        //         'pev_rejected_notes' => $request->notes,
        //         'pev_approved_by' => null,
        //         'pev_approved_at' => null,
        //         'pev_approved_notes' => null,
        //         'pev_division_approved_by' => null,
        //         'pev_division_approved_at' => null,
        //         'pc_by' => null,
        //         'pc_time' => null
        //     ]);

        if ($po->save()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function revise(Request $request){
        $po = Asset_po::find($request->id);
        $po->rejected_by = Auth::user()->username;
        $po->rejected_time = date('Y-m-d H:i:s');
        $po->rejected_notes = $request->notes;

        Asset_pre::where('pev_num', $po->reference)
            ->update([
                // 'pev_rejected_notes' => $request->notes,
                'pev_approved_by' => null,
                'pev_approved_at' => null,
                'pev_approved_notes' => null,
                'pev_division_approved_by' => null,
                'pev_division_approved_at' => null,
                'pc_by' => null,
                'pc_time' => null
            ]);

        if ($po->save()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function addInstant(Request $request){

        $po_type = Asset_type_po::find($request->po_type);

        $ipo = new Asset_po();
        $ipo->supplier_id = $request->supplier;
        $ipo->po_type = $po_type->name;
        // PO NUM
        $po_num = Asset_po::where('created_at', 'like', ''.date('Y')."-%")
            ->orderBy('created_at', 'desc')
            ->first();
        if (!empty($po_num)) {
            $last_num = explode("/", $po_num->po_num);
            $num = sprintf("%03d", (intval($last_num[0]) + 1));
        } else {
            $num = sprintf("%03d", 1);
        }
        $arrRomawi	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $ipo->po_num = $num."/".strtoupper(Session::get('company_tag'))."/PO/".$arrRomawi[date('n')]."/".date('y');

        $ipo->po_date = $request->date;
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

        $ipo->payment_term = $request->p_terms;
        $ipo->terms = $request->terms;
        $ipo->notes = $request->notes;
        $ipo->approved_time = date("Y-m-d");
        $ipo->approved_by = Auth::user()->username . $cat;
        $ipo->request_by = Auth::user()->username;
        $ipo->created_by = Auth::user()->username.$cat;
        $ipo->company_id = Session::get('company_id');

        if ($ipo->save()){
            $code = $request->code;
            $name = $request->name;
            $uom = $request->uom;
            $qty = $request->qty;
            $price = $request->price;
            foreach ($request->id_item as $key => $item){
                $detail = new Asset_po_detail();
                $detail->po_num = $ipo->id;
                $detail->item_id = $code[$key];
                $detail->qty = $qty[$key];
                $detail->price = str_replace(",", "$price[$key]");
                $detail->created_by = Auth::user()->username;
                $detail->company_id = Session::get('company_id');
                $detail->save();
            }

            $paper = Ha_paper_permit::where('kode', $request->paper_code)->first();
            $paper->issued_date = date('Y-m-d');
            $paper->issued_by = Auth::user()->username;
            $paper->updated_by = Auth::user()->username;
            $paper->paper_num = $ipo->po_num;
            $paper->save();
        }

        return redirect()->route('po.index');
    }

    function print($id){
        $po = Asset_po::find($id);
        $details = Asset_po_detail::where('po_num', $id)->get();
        $items = Asset_item::withTrashed()->get();
        $arrItem = array();
        foreach ($items as $i){
            $arrItem[$i->item_code] = $i;
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
        $comp = ConfigCompany::find($po->company_id);

        $pref = Preference_config::where('id_company', $po->company_id)->first();
        $woVal = 0;
        foreach ($details as $key => $value) {
            $woVal += $value->qty * $value->price;
        }

        $wo_pref = json_decode($pref->po_signature);
        $img = "";

        for ($i=0; $i < 3; $i++) {
            if (isset($wo_pref->min[$i])) {
                // dd($wo_pref->img[$i]);
                if ($woVal > $wo_pref->min[$i]) {
                    if ($wo_pref->max[$i] != 0) {
                        if ($woVal <= $wo_pref->max[$i]) {
                            if (isset($wo_pref->img[$i])) {
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

        if ($img != "") {
           $img = str_replace("public", "public_html", asset('images/signature/'.$img));
        }

        return view('po.print', compact('po', 'details', 'arrItem', 'arrSup', 'tax', 'comp','img'));
    }

    function edit_notes(Request $request){
        $wo = Asset_po::find($request->id_po);
        $wo->notes = $request->notes;

        $wo->save();
        return redirect()->back();
    }

    function item_update(Request $request){
        $item = Asset_item::find($request->_item);
        $detail = Asset_po_detail::find($request->id_detail);
        $detail->item_id = $item->item_code;
        $detail->save();
        return redirect()->back();
    }
}
