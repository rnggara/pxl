<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset_po;
use App\Models\Asset_po_detail;
use App\Models\Asset_pre;
use App\Models\Asset_pre_detail;
use App\Models\Pref_tax_config;
use App\Models\Preference_config;
use App\Models\Procurement_vendor;
use Illuminate\Http\Request;

class AssetPreController extends BaseController
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }
    //pe
    public function approve(Request $request)
    {
        $items = explode(",", str_replace("{", "", str_replace("}", "", $request->x)));
        $newJs = "";
        foreach ($items as $item) {
            $nItem = explode(":", $item);
            $nItem[0] = '"' . $nItem[0] . '"';
            $newJs .= $nItem[0] . ":" . $nItem[1] . ",";
        }
        $jsFormat = "{" . rtrim($newJs, ", ") . "}";
        $itemsJs = json_decode($jsFormat, true);
        $row = [];
        $pe = Asset_pre::find($request->id);

        $pe->pev_approved_by = $request->username;
        $pe->pev_approved_at = date("Y-m-d H:i:s");
        $pe->pev_approved_notes = $request->notes;

        $supplier = (!empty($pe->suppliers)) ? json_decode($pe->suppliers, true) : [];
        $ppns = (!empty($pe->ppns)) ? json_decode($pe->ppns, true) : [];
        $dp = (!empty($pe->dps)) ? json_decode($pe->dps, true) : [];
        $disc = (!empty($pe->discs)) ? json_decode($pe->discs, true) : [];
        $tops = (!empty($pe->tops)) ? json_decode($pe->tops, true) : [];
        $notes = (!empty($pe->notes)) ? json_decode($pe->notes, true) : [];
        $curr = (!empty($pe->currencies)) ? json_decode($pe->currencies, true) : [];
        $deliver = (!empty($pe->delivers)) ? json_decode($pe->delivers, true) : [];
        $del_time = (!empty($pe->deliver_times)) ? json_decode($pe->deliver_times, true) : [];
        $terms = (!empty($pe->terms)) ? json_decode($pe->terms, true) : [];
        $paper = explode("/", $pe->pev_num);
        $tag = $paper[1];
        $arrRomawi    = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");

        $pref = Preference_config::where('id_company', $pe->company_id)->first();
        $po_signature = (!empty($pref->po_signature)) ? json_decode($pref->po_signature, true) : [];

        foreach($itemsJs as $n => $i){
            $row["$i"][] = intval($n);
        }

        $data = [];
        foreach ($row as $key => $value) {
            //create PO

            $po_num = Asset_po::where('created_at', 'like', '' . date('Y') . "-%")
                ->where('po_num', 'like', "%" . $tag . "%")
                ->where('company_id', $pe->company_id)
                ->orderBy('id', 'desc')
                ->first();
            if (!empty($po_num)) {
                $last_num = explode("/", $po_num->po_num);
                $num = sprintf("%03d", (intval($last_num[0]) + 1));
            } else {
                $num = sprintf("%03d", 1);
            }

            $po = new Asset_po();
            $po_num = $num . "/" . strtoupper($tag) . "/PO/" . $arrRomawi[date('n')] . "/" . date('y');
            $po->po_num = $po_num;
            $po->supplier_id = $supplier[$key];
            $po->po_type = $pe->fr_type;
            $po->po_date = date("Y-m-d H:i:s");
            $po->project = $pe->project;
            $po->division = $pe->division;
            $po->reference = $pe->pev_num;
            $po->deliver_to = (isset($deliver[$key])) ? $deliver[$key] : null;
            $po->deliver_time = (isset($del_time[$key])) ? $del_time[$key] : null;
            $po->currency = (isset($curr[$key])) ? $curr[$key] : null;
            $po->discount = (isset($disc[$key])) ? $disc[$key] : null;
            $po->dp = (isset($dp[$key])) ? $dp[$key] : null;
            $po->ppn = (isset($ppns[$key])) ? json_encode($ppns[$key]) : null;
            $po->payment_term = (isset($tops[$key])) ? $tops[$key] : null;
            $po->terms = (isset($terms[$key])) ? $terms[$key] : null;
            $po->notes = (isset($terms[$key])) ? $terms[$key] : null;
            $po->request_by = $pe->request_by;
            $po->created_by = $request->username;
            $po->fr_note = (isset($notes[$key])) ? $notes[$key] : null;
            $po->company_id = $pe->company_id;
            $po->save();

            $total_price = 0;
            foreach ($value as $det) {
                $detail = Asset_pre_detail::find($det);
                $unit_price = (!empty($detail->price)) ? json_decode($detail->price, true) : [];
                $total_price = $unit_price[$key] * $detail->qty_appr;

                // create PO Detail
                $po_detail = new Asset_po_detail();
                $po_detail->po_num = $po->id;
                $po_detail->item_id = $detail->item_id;
                $po_detail->qty = $detail->qty_appr;
                $po_detail->v1 = 0;
                $po_detail->price = (isset($unit_price[$key])) ? $unit_price[$key] : 0;
                $po_detail->company_id = $pe->company_id;
                $po_detail->created_by = $request->username;
                $po_detail->save();

                $detail->supp_idx = $key;
                $detail->save();
            }

            $bypass = false;
            $minArr = [];
            $maxArr = [];
            $bypassArr = [];
            if (is_array($po_signature) && !empty($po_signature)) {
                $minArr = $po_signature['min'];
                $maxArr = $po_signature['max'];
                if (isset($po_signature['bypass'])) {
                    $bypassArr = $po_signature['bypass'];
                }
            }

            $keyBypass = null;
            for ($i = 0; $i < count($minArr); $i++) {
                if ($maxArr[$i] == 0) {
                    if ($total_price > $minArr[$i]) {
                        $keyBypass = $i;
                        break;
                    }
                } else {
                    if ($total_price <= $maxArr[$i]) {
                        $keyBypass = $i;
                        break;
                    }
                }
            }

            if (isset($bypassArr[$keyBypass])) {
                if ($bypassArr[$keyBypass] == 1) {
                    $bypass = true;
                }
            }

            if ($bypass) {
                $po->approved_by = $request->username;
                $po->approved_time = date("Y-m-d H:i:s");
                $po->appr_notes = $po->notes;
            }
            $po->save();
        }

        if ($pe->save()) {
            return $this->sendResponse($pe, 'Approve Success');
        } else {
            return $this->sendError('Approve Failed');
        }
    }
    public function index($comp_id)
    {
        $whereDate = " (asset_pre.pre_approved_at like '" . date('Y') . "%' or asset_pre.pev_date like '" . date('Y') . "%')";
        $pev = Asset_pre::select(
            'asset_pre.id',
            'asset_pre.pev_num',
            'asset_pre.created_by',
            'asset_pre.request_by',
            'asset_pre.fr_approved_by',
            'asset_pre.fr_approved_at',
            'asset_pre.pre_approved_by',
            'asset_pre.pre_approved_at',
            'asset_pre.pev_approved_by',
            'asset_pre.pev_approved_at',
            'asset_pre.suppliers',
            'asset_pre.company_id',
            'asset_pre.division',
            'prj.agreement_title',
            'prj.prj_name'
        )
            ->leftJoin('marketing_projects as prj', 'prj.id', '=', 'asset_pre.project')
            ->where('asset_pre.company_id', $comp_id)
            ->whereNotNull('asset_pre.pev_num')
            ->whereRaw(' asset_pre.pre_num != ""')
            ->whereNotNull('asset_pre.pev_date')
            ->whereRaw($whereDate)
            ->orderBy('asset_pre.id', 'desc')
            ->get();

        if ($pev) {
            return $this->sendResponse($pev, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }
    public function getDetail($comp_id, $id)
    {
        $pe = Asset_pre::leftJoin('marketing_projects as prj', 'prj.id', '=', 'asset_pre.project')
            ->select('asset_pre.*', 'prj.agreement_title', 'prj.prj_name')
            ->where('asset_pre.company_id', $comp_id)
            ->where('asset_pre.id', $id)
            ->whereNull('asset_pre.deleted_at')
            ->first();

        $supplier = Procurement_vendor::all()->pluck('name', 'id');


        $js['terms_op'] = ($pe->tops != null) ? json_decode($pe->tops, true) : ["", "", ""];
        $js['suppliers'] = ($pe->suppliers != null) ? json_decode($pe->suppliers, true) : ["", "", ""];
        $js['ppns'] = ($pe->ppns != null) ? json_decode($pe->ppns, true) : ["", "", ""];
        $js['dp'] = ($pe->dp != null) ? json_decode($pe->dp, true) : ["", "", ""];
        $js['discs'] = ($pe->discs != null) ? json_decode($pe->discs, true) : ["", "", ""];
        $js['pev_notes'] = ($pe->pev_notes != null) ? json_decode($pe->pev_notes, true) : ["", "", ""];
        $js['delivers'] = ($pe->delivers != null) ? json_decode($pe->delivers, true) : ["", "", ""];
        $js['deliver_times'] = ($pe->deliver_times != null) ? json_decode($pe->deliver_times, true) : ["", "", ""];
        $js['terms'] = ($pe->terms != null) ? json_decode($pe->terms, true) : ["", "", ""];
        // $tax_ = ()

        $pe_detail = Asset_pre_detail::leftJoin('asset_items as item', 'item.item_code', '=', 'asset_pre_detail.item_id')
            ->select('asset_pre_detail.*', 'item.name as item_name', 'item.uom as item_uom')
            ->where('asset_pre_detail.pre_id', $id)
            ->get();

        $ppn_formula = Pref_tax_config::all()->pluck('formula', 'id');
        $ppn_name = Pref_tax_config::all()->pluck('tax_name', 'id');

        $details = [];
        foreach ($pe_detail as $key => $value) {
            $col = [];
            if (!empty($value->price)) {
                $price = json_decode($value->price, true);
                foreach ($price as $i => $dPrice) {
                    $row = [];
                    $row['price'] = $dPrice;
                    $row['suppliers'] = (isset($supplier[$js['suppliers'][$i]])) ? $supplier[$js['suppliers'][$i]] : "N/A";
                    $row['terms_op'] = $js['terms_op'][$i];
                    if (isset($js['ppns'][$i])) {
                        $arrPpn = [];
                        if(!empty($js['ppns'][$i]) && is_array($js['ppns'][$i])){
                            foreach ($js['ppns'][$i] as $ppn) {
                                if (isset($ppn_name[$ppn])) {
                                    $_ppn['name'] = $ppn_name[$ppn];
                                    $sum = ($dPrice * $value->qty_appr) - $js['discs'][$i];
                                    $eval = eval("return $ppn_formula[$ppn];");
                                    $_ppn['val'] = $eval;
                                    $arrPpn[] = $_ppn;
                                }
                            }
                        }
                        $row['ppns'] = $arrPpn;
                    } else {
                        $row['ppns'] = null;
                    }
                    $row['dp'] = ($js['dp'][$i] == "") ? 0 : $js['dp'][$i];
                    $row['discs'] = intval($js['discs'][$i]);
                    $row['pev_notes'] = $js['pev_notes'][$i];
                    $row['delivers'] = $js['delivers'][$i];
                    $row['deliver_times'] = $js['deliver_times'][$i];
                    $row['terms'] = $js['terms'][$i];
                    $col[$i] = $row;
                }
            }

            $_data['id'] = $value->id;
            $_data['item_name'] = $value->item_name;
            $_data['item_code'] = $value->item_id;
            $_data['item_qty'] = $value->qty_appr;
            $_data['item_uom'] = $value->item_uom;
            $_data['index'] = $value->supp_idx;


            $cell['details'] = $col;
            $cell['data'] = $_data;
            $details[] = $cell;
        }

        // dd($details);
        $data['success'] = true;
        $data['message'] = 'Success';
        $data['pe'] = $pe;
        $data['pe_detail'] = $details;

        if ($pe) {
            return response()->json($data, 200);
            //            return $this->sendResponse($data, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

    //pr
    public function indexPr($comp_id)
    {
        $whereDate = " (asset_pre.fr_approved_at like '" . date('Y') . "%' or asset_pre.pre_date like '" . date('Y') . "%')";
        $pre = Asset_pre::select(
            'asset_pre.id',
            'asset_pre.pre_num',
            'asset_pre.created_by',
            'asset_pre.request_by',
            'asset_pre.fr_approved_by',
            'asset_pre.fr_approved_at',
            'asset_pre.pre_approved_by',
            'asset_pre.pre_approved_at',
            'asset_pre.pev_approved_by',
            'asset_pre.pev_approved_at',
            'asset_pre.suppliers',
            'asset_pre.company_id',
            'asset_pre.division',
            'prj.agreement_title',
            'prj.prj_name'
        )
            ->leftJoin('marketing_projects as prj', 'prj.id', '=', 'asset_pre.project')
            ->where('asset_pre.company_id', $comp_id)
            ->whereNotNull('asset_pre.fr_approved_at')
            ->whereNotNull('asset_pre.pre_num')
            ->whereRaw(' asset_pre.fr_num != ""')
            ->whereRaw($whereDate)
            ->orderBy('asset_pre.id', 'desc')
            ->get();
        if ($pre) {
            return $this->sendResponse($pre, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }
    public function getDetailPr($comp_id, $id)
    {
        $pr = Asset_pre::leftJoin('marketing_projects as prj', 'prj.id', '=', 'asset_pre.project')
            ->select(
                'asset_pre.id',
                'asset_pre.pre_num',
                'asset_pre.created_by',
                'asset_pre.request_by',
                'asset_pre.fr_approved_by',
                'asset_pre.fr_approved_at',
                'asset_pre.pre_approved_by',
                'asset_pre.pre_approved_at',
                'asset_pre.pev_approved_by',
                'asset_pre.pev_approved_at',
                'asset_pre.suppliers',
                'asset_pre.company_id',
                'asset_pre.division',
                'prj.agreement_title',
                'prj.prj_name'
            )
            ->where('asset_pre.company_id', $comp_id)
            ->where('asset_pre.id', $id)
            ->whereNull('asset_pre.deleted_at')
            ->first();

        $pr_detail = Asset_pre_detail::leftJoin('asset_items as item', 'item.item_code', '=', 'asset_pre_detail.item_id')
            ->select('asset_pre_detail.*', 'item.name as item_name', 'item.uom as item_uom')
            ->where('asset_pre_detail.fr_id', $id)
            ->get();
        $suppliers = [];
        $supp = Procurement_vendor::where('company_id', $comp_id)->get();
        foreach ($supp as $value) {
            $suppliers[$value->id] = $value->name;
        }
        $data = [
            'pr' => $pr,
            'pr_detail' => $pr_detail,
            'supplier' => $suppliers

        ];

        if ($pr) {
            return $this->sendResponse($data, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }
    public function approvePr(Request $request)
    {
        $pr = Asset_pre::find($request->id);
        $pr->se_num = str_replace("PRE", "PEV", $request->pre_num);
        $pr->pre_approved_by = $request->username;
        $pr->pre_approved_at = date('Y-m-d H:i:s');
        $pr->pre_approved_notes = $request->notes;

        if ($pr->save()) {
            return $this->sendResponse($pr, 'Approve Success');
        } else {
            return $this->sendError('Approve Failed');
        }
    }

    //fr
    public function indexFr($comp_id)
    {
        $whereDate = " (asset_pre.request_at like '" . date('Y') . "%' or asset_pre.fr_date like '" . date('Y') . "%')";
        $pre = Asset_pre::select(
            'asset_pre.id',
            'asset_pre.fr_num',
            'asset_pre.created_by',
            'asset_pre.request_by',
            'asset_pre.fr_approved_by',
            'asset_pre.fr_approved_at',
            'asset_pre.fr_division_approved_by',
            'asset_pre.fr_division_approved_at',
            'asset_pre.pre_approved_by',
            'asset_pre.pre_approved_at',
            'asset_pre.pev_approved_by',
            'asset_pre.pev_approved_at',
            'asset_pre.suppliers',
            'asset_pre.company_id',
            'asset_pre.division',
            'prj.agreement_title',
            'prj.prj_name'
        )
            ->leftJoin('marketing_projects as prj', 'prj.id', '=', 'asset_pre.project')
            ->where('asset_pre.company_id', $comp_id)
            //            ->whereNotNull('fr_approved_at')
            ->whereRaw($whereDate)
            ->whereNotNull('asset_pre.fr_num')
            ->orderBy('asset_pre.id', 'desc')
            ->get();
        if ($pre) {
            return $this->sendResponse($pre, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }
    public function getDetailFr($comp_id, $id)
    {
        $fr = Asset_pre::leftJoin('marketing_projects as prj', 'prj.id', '=', 'asset_pre.project')
            ->select(
                'asset_pre.id',
                'asset_pre.fr_num',
                'asset_pre.created_by',
                'asset_pre.request_by',
                'asset_pre.fr_approved_by',
                'asset_pre.fr_approved_at',
                'asset_pre.fr_division_approved_by',
                'asset_pre.fr_division_approved_at',
                'asset_pre.pre_approved_by',
                'asset_pre.pre_approved_at',
                'asset_pre.pev_approved_by',
                'asset_pre.pev_approved_at',
                'asset_pre.suppliers',
                'asset_pre.company_id',
                'asset_pre.division',
                'prj.agreement_title',
                'prj.prj_name'
            )
            ->where('asset_pre.company_id', $comp_id)
            ->where('asset_pre.id', $id)
            ->whereNull('asset_pre.deleted_at')
            ->first();

        $fr_detail = Asset_pre_detail::leftJoin('asset_items as item', 'item.item_code', '=', 'asset_pre_detail.item_id')
            ->select('asset_pre_detail.*', 'item.name as item_name', 'item.uom as item_uom')
            ->where('asset_pre_detail.fr_id', $id)
            ->get();
        $suppliers = [];
        $supp = Procurement_vendor::where('company_id', $comp_id)->get();
        foreach ($supp as $value) {
            $suppliers[$value->id] = $value->name;
        }
        $data = [
            'fr' => $fr,
            'fr_detail' => $fr_detail,
            'supplier' => $suppliers

        ];

        if ($fr) {
            return $this->sendResponse($data, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }
    public function approveFr(Request $request)
    {
        $fr = Asset_pre::find($request->id);
        $fr->pre_num = str_replace("FR", "PRE", $request->fr_num);
        $fr->pre_date = date("Y-m-d H:i:s");
        $fr->fr_division_approved_by = $request->username;
        $fr->fr_division_approved_at = date('Y-m-d H:i:s');
        $fr->fr_approved_notes = $request->notes;

        if ($fr->save()) {
            return $this->sendResponse($fr, 'Approve Success');
        } else {
            return $this->sendError('Approve Failed');
        }
    }
}
