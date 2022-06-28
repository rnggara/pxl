<?php

namespace App\Http\Controllers\Api;

use App\Models\Asset_sre;
use Illuminate\Http\Request;
use App\Models\Pref_tax_config;
use App\Models\Asset_sre_detail;
use App\Models\Preference_config;
use App\Models\Procurement_vendor;
use App\Models\Asset_wo;
use App\Models\Asset_wo_detail;

class AssetSreController extends BaseController
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }
    //se
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
        $se = Asset_sre::find($request->id);

        $se->se_approved_by = $request->username;
        $se->se_approved_at = date("Y-m-d H:i:s");
        $se->se_approved_notes = $request->notes;

        $supplier = (!empty($se->suppliers)) ? json_decode($se->suppliers, true) : [];
        $ppns = (!empty($se->ppns)) ? json_decode($se->ppns, true) : [];
        $dp = (!empty($se->dps)) ? json_decode($se->dps, true) : [];
        $disc = (!empty($se->discs)) ? json_decode($se->discs, true) : [];
        $tops = (!empty($se->tops)) ? json_decode($se->tops, true) : [];
        $notes = (!empty($se->notes)) ? json_decode($se->notes, true) : [];
        $curr = (!empty($se->currencies)) ? json_decode($se->currencies, true) : [];
        $deliver = (!empty($se->delivers)) ? json_decode($se->delivers, true) : [];
        $del_time = (!empty($se->deliver_times)) ? json_decode($se->deliver_times, true) : [];
        $terms = (!empty($se->terms)) ? json_decode($se->terms, true) : [];
        $paper = explode("/", $se->se_num);
        $tag = $paper[1];
        $arrRomawi    = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");

        $pref = Preference_config::where('id_company', $se->company_id)->first();
        $wo_signature = (!empty($pref->wo_signature)) ? json_decode($pref->wo_signature, true) : [];

        foreach($itemsJs as $n => $i){
            $row["$i"][] = intval($n);
        }

        $data = [];
        foreach ($row as $key => $value) {
            //create WO

            $wo_num = Asset_wo::where('created_at', 'like', '' . date('Y') . "-%")
                ->where('wo_num', 'like', "%" . $tag . "%")
                ->where('company_id', $se->company_id)
                ->orderBy('id', 'desc')
                ->first();
            if (!empty($wo_num)) {
                $last_num = explode("/", $wo_num->wo_num);
                $num = sprintf("%03d", (intval($last_num[0]) + 1));
            } else {
                $num = sprintf("%03d", 1);
            }

            $wo = new Asset_wo();
            $wo->wo_type = $se->so_type;
            $wo->wo_num = $num . "/" . strtoupper($tag) . "/WO/" . $arrRomawi[date('n')] . "/" . date('y');
            $wo->division = $se->division;
            $wo->supplier_id = $supplier[$key];
            $wo->currency = $se->currency;
            $wo->req_date = date("Y-m-d H:i:s");
            $wo->project = $se->project;
            $wo->division = $se->division;
            $wo->reference = $se->pev_num;
            $wo->deliver_to = (isset($deliver[$key])) ? $deliver[$key] : null;
            $wo->deliver_time = (isset($del_time[$key])) ? $del_time[$key] : null;
            $wo->currency = (isset($curr[$key])) ? $curr[$key] : null;
            $wo->discount = (isset($disc[$key])) ? $disc[$key] : null;
            $wo->dp = (isset($dp[$key])) ? $dp[$key] : null;
            $wo->ppn = (isset($ppns[$key])) ? json_encode($ppns[$key]) : null;
            $wo->terms_payment = (isset($tops[$key])) ? $tops[$key] : null;
            $wo->terms = (isset($terms[$key])) ? $terms[$key] : null;
            $wo->notes = (isset($terms[$key])) ? $terms[$key] : null;
            $wo->so_note = (isset($notes[$key])) ? $notes[$key] : null;
            $wo->company_id = $se->company_id;
            $wo->created_by = $request->username;
            $wo->save();

            $total_price = 0;
            foreach ($value as $det) {
                $detail = Asset_sre_detail::find($det);
                $unit_price = (!empty($detail->unit_price)) ? json_decode($detail->unit_price, true) : [];
                $total_price = $unit_price[$key] * $detail->qty_appr;

                // create WO Detail
                $wo_detail = new Asset_wo_detail();
                $wo_detail->wo_id = $wo->id;
                $wo_detail->job_desc = $detail->job_desc;
                $wo_detail->qty = $detail->qty_appr;
                $wo_detail->unit_price = $unit_price[$key];
                $wo_detail->created_by = $request->username;
                $wo_detail->company_id = $wo->company_id;
                $wo_detail->save();

                $detail->supp_idx = $key;
                $detail->save();
            }

            $bypass = false;
            $minArr = [];
            $maxArr = [];
            $bypassArr = [];
            if (is_array($wo_signature) && !empty($wo_signature)) {
                $minArr = $wo_signature['min'];
                $maxArr = $wo_signature['max'];
                if (isset($wo_signature['bypass'])) {
                    $bypassArr = $wo_signature['bypass'];
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
                $wo->approved_by = $request->username;
                $wo->approved_time = date("Y-m-d H:i:s");
                $wo->appr_notes = $wo->notes;
            }
            $wo->save();
        }

        if ($se->save()) {
            return $this->sendResponse($se, 'Approve Success');
        } else {
            return $this->sendError('Approve Failed');
        }
    }

    public function index($comp_id)
    {
        $whereDate = " (asset_sre.rfq_approved_at like '" . date('Y') . "%' or asset_sre.se_date like '" . date('Y') . "%')";
        $se = Asset_sre::select(
            'asset_sre.id',
            'asset_sre.se_num',
            'asset_sre.so_approved_by',
            'asset_sre.so_approved_at',
            'asset_sre.rfq_approved_by',
            'asset_sre.rfq_approved_at',
            'asset_sre.se_approved_by',
            'asset_sre.se_approved_at',
            'asset_sre.company_id',
            'asset_sre.division',
            'prj.agreement_title',
            'prj.prj_name'
        )
            ->leftJoin('marketing_projects as prj', 'prj.id', '=', 'asset_sre.project')
            ->where('asset_sre.company_id', $comp_id)
            ->whereNotNull('asset_sre.se_num')
            ->whereNotNull('asset_sre.se_input_at')
            ->whereRaw($whereDate)
            ->orderBy('asset_sre.id', 'desc')
            ->get();

        if ($se) {
            return $this->sendResponse($se, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }
    public function getDetail($comp_id, $id)
    {
        $se = Asset_sre::leftJoin('marketing_projects as prj', 'prj.id', '=', 'asset_sre.project')
            ->select('asset_sre.*', 'prj.agreement_title', 'prj.prj_name')
            ->where('asset_sre.company_id', $comp_id)
            ->where('asset_sre.id', $id)
            ->whereNull('asset_sre.deleted_at')
            ->first();

        $supplier = Procurement_vendor::all()->pluck('name', 'id');


        $js['terms_op'] = ($se->tops != null) ? ($se->tops != null) ? json_decode($se->tops) : ["", "", ""] : ["", "", ""];
        $js['suppliers'] = ($se->suppliers != null) ? ($se->suppliers != null) ? json_decode($se->suppliers) : ["", "", ""] : ["", "", ""];
        $js['ppns'] = ($se->ppns != null) ? ($se->ppns != null) ? json_decode($se->ppns) : ["", "", ""] : ["", "", ""];
        $js['dp'] = ($se->dp != null) ? ($se->dp != null) ? json_decode($se->dp) : ["", "", ""] : ["", "", ""];
        $js['discs'] = ($se->discs != null) ? ($se->discs != null) ? json_decode($se->discs) : ["", "", ""] : ["", "", ""];
        $js['notes'] = ($se->notes != null) ? ($se->notes != null) ? json_decode($se->notes) : ["", "", ""] : ["", "", ""];
        $js['delivers'] = ($se->delivers != null) ? ($se->delivers != null) ? json_decode($se->delivers) : ["", "", ""] : ["", "", ""];
        $js['deliver_times'] = ($se->deliver_times != null) ? ($se->deliver_times != null) ? json_decode($se->deliver_times) : ["", "", ""] : ["", "", ""];
        $js['terms'] = ($se->terms != null) ? ($se->terms != null) ? json_decode($se->terms) : ["", "", ""] : ["", "", ""];
        // $tax_ = ()

        $se_detail = Asset_sre_detail::where('asset_sre_detail.so_id', $id)
            ->get();

        $ppn_formula = Pref_tax_config::all()->pluck('formula', 'id');
        $ppn_name = Pref_tax_config::all()->pluck('tax_name', 'id');

        $details = [];
        foreach ($se_detail as $key => $value) {
            $col = [];
            if (!empty($value->unit_price)) {
                $price = json_decode($value->unit_price, true);
                foreach ($price as $i => $dPrice) {
                    $row = [];
                    $row['price'] = $dPrice;
                    $row['suppliers'] = (isset($supplier[$js['suppliers'][$i]])) ? $supplier[$js['suppliers'][$i]] : "N/A";
                    $row['terms_op'] = $js['terms_op'][$i];
                    if (isset($js['ppns'][$i])) {
                        $arrPpn = [];
                        if (is_array($js['ppns'][$i])) {
                            foreach ($js['ppns'][$i] as $ppn) {
                                if (isset($ppn_name[$ppn])) {
                                    $_ppn['name'] = $ppn_name[$ppn];
                                    $sum = ($dPrice * $value->qty_appr) - $js['discs'][$i];
                                    $eval = eval("return $ppn_formula[$ppn];");
                                    $_ppn['val'] = $eval;
                                    $arrPpn[] = $_ppn;
                                }
                            }
                            $row['ppns'] = $arrPpn;
                        }
                    } else {
                        $row['ppns'] = null;
                    }
                    $row['dp'] = ($js['dp'][$i] == "") ? 0 : $js['dp'][$i];
                    $row['discs'] = intval($js['discs'][$i]);
                    $row['notes'] = $js['notes'][$i];
                    $row['delivers'] = $js['delivers'][$i];
                    $row['deliver_times'] = $js['deliver_times'][$i];
                    $row['terms'] = $js['terms'][$i];
                    $col[$i] = $row;
                }
            }

            $_data['id'] = $value->id;
            $_data['item_name'] = $value->job_desc;
            $_data['item_qty'] = $value->qty_appr;
            $_data['index'] = $value->supp_idx;


            $cell['details'] = $col;
            $cell['data'] = $_data;
            $details[] = $cell;
        }

        // dd($details);
        $data['success'] = true;
        $data['message'] = 'Success';
        $data['se'] = $se;
        $data['se_detail'] = $details;

        if ($se) {
            return response()->json($data, 200);
            //            return $this->sendResponse($data, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

    //sr
    public function approveSR(Request $request)
    {
        $sr = Asset_sre::find($request->id);
        $sr->se_num = str_replace("RFQSO", "SE", $sr->rfq_so_num);
        $sr->se_date = date("Y-m-d H:i:s");
        $sr->rfq_approved_by = $request->username;
        $sr->rfq_approved_at = date('Y-m-d H:i:s');
        $sr->rfq_approved_notes = $request->notes;

        $detail = Asset_sre_detail::where('so_id', $sr->id)->get();
        foreach ($detail as $item) {
            $item->qty_appr = $item->qty;
            $item->save();
        }

        if ($sr->save()) {
            return $this->sendResponse($sr, 'Approve Success');
        } else {
            return $this->sendError('Approve Failed');
        }
    }
    public function indexSR($comp_id)
    {
        $whereDate = " (asset_sre.so_approved_at like '" . date('Y') . "%' or asset_sre.rfq_so_date like '" . date('Y') . "%')";
        $se = Asset_sre::select(
            'asset_sre.id',
            'asset_sre.rfq_so_num',
            'asset_sre.created_by',
            'asset_sre.so_approved_by',
            'asset_sre.so_approved_at',
            'asset_sre.rfq_approved_by',
            'asset_sre.rfq_approved_at',
            'asset_sre.se_approved_by',
            'asset_sre.se_approved_at',
            'asset_sre.company_id',
            'asset_sre.division',
            'prj.agreement_title',
            'prj.prj_name'
        )
            ->leftJoin('marketing_projects as prj', 'prj.id', '=', 'asset_sre.project')
            ->where('asset_sre.company_id', $comp_id)
            ->whereNotNull('asset_sre.so_approved_at')
            ->whereNotNull('asset_sre.rfq_so_num')
            ->whereRaw($whereDate)
            ->orderBy('asset_sre.id', 'desc')
            ->get();

        if ($se) {
            return $this->sendResponse($se, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }
    public function getDetailSR($comp_id, $id)
    {
        $sr = Asset_sre::leftJoin('marketing_projects as prj', 'prj.id', '=', 'asset_sre.project')
            ->select(
                'asset_sre.id',
                'asset_sre.rfq_so_num',
                'asset_sre.created_by',
                'asset_sre.so_approved_by',
                'asset_sre.so_approved_at',
                'asset_sre.rfq_approved_by',
                'asset_sre.rfq_approved_at',
                'asset_sre.se_approved_by',
                'asset_sre.se_approved_at',
                'asset_sre.suppliers',
                'asset_sre.company_id',
                'asset_sre.division',
                'prj.agreement_title',
                'prj.prj_name'
            )
            ->where('asset_sre.company_id', $comp_id)
            ->where('asset_sre.id', $id)
            ->whereNull('asset_sre.deleted_at')
            ->first();

        $sr_detail = Asset_sre_detail::where('asset_sre_detail.so_id', $id)
            ->get();
        $suppliers = [];
        $supp = Procurement_vendor::where('company_id', $comp_id)->get();
        foreach ($supp as $value) {
            $suppliers[$value->id] = $value->name;
        }
        $data = [
            'sr' => $sr,
            'sr_detail' => $sr_detail,
            'supplier' => $suppliers
        ];

        if ($sr) {
            return $this->sendResponse($data, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }
    //so
    public function approveSO(Request $request)
    {
        $so = Asset_sre::find($request->id);
        $so->so_approved_by = $request->username;
        $so->so_approved_at = date('Y-m-d H:i:s');
        $so->so_approved_notes = $request->notes;
        $so_data = Asset_sre::where('id', $request->id)->first();
        $so_num = $so_data->so_num;
        $so->rfq_so_num = str_replace("SO", "RFQSO", $so_num);
        $so->rfq_so_date = date('Y-m-d H:i:s');

        if ($so->save()) {
            return $this->sendResponse($so, 'Approve Success');
        } else {
            return $this->sendError('Approve Failed');
        }
    }
    public function indexSO($comp_id)
    {
        $whereDate = " (asset_sre.created_at like '" . date('Y') . "%')";
        $so = Asset_sre::select(
            'asset_sre.id',
            'asset_sre.so_num',
            'asset_sre.so_type',
            'asset_sre.created_by',
            'asset_sre.so_approved_by',
            'asset_sre.so_approved_at',
            'asset_sre.rfq_approved_by',
            'asset_sre.rfq_approved_at',
            'asset_sre.se_approved_by',
            'asset_sre.se_approved_at',
            'asset_sre.company_id',
            'asset_sre.division',
            'prj.agreement_title',
            'prj.prj_name'
        )
            ->leftJoin('marketing_projects as prj', 'prj.id', '=', 'asset_sre.project')
            ->where('asset_sre.company_id', $comp_id)
            ->whereNull('asset_sre.so_approved_at')
            ->whereNotNull('asset_sre.so_num')
            ->whereRaw($whereDate)
            ->orderBy('asset_sre.id', 'desc')
            ->get();

        if ($so) {
            return $this->sendResponse($so, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }
    public function getDetailSO($comp_id, $id)
    {
        $sr = Asset_sre::leftJoin('marketing_projects as prj', 'prj.id', '=', 'asset_sre.project')
            ->select(
                'asset_sre.id',
                'asset_sre.so_num',
                'asset_sre.so_type',
                'asset_sre.created_by',
                'asset_sre.created_at',
                'asset_sre.so_approved_by',
                'asset_sre.so_approved_at',
                'asset_sre.rfq_approved_by',
                'asset_sre.rfq_approved_at',
                'asset_sre.se_approved_by',
                'asset_sre.se_approved_at',
                'asset_sre.suppliers',
                'asset_sre.company_id',
                'asset_sre.division',
                'prj.agreement_title',
                'prj.prj_name'
            )
            ->where('asset_sre.company_id', $comp_id)
            ->where('asset_sre.id', $id)
            ->whereNull('asset_sre.deleted_at')
            ->first();

        $sr_detail = Asset_sre_detail::where('asset_sre_detail.so_id', $id)
            ->get();
        $suppliers = [];
        $supp = Procurement_vendor::where('company_id', $comp_id)->get();
        foreach ($supp as $value) {
            $suppliers[$value->id] = $value->name;
        }
        $data = [
            'sr' => $sr,
            'sr_detail' => $sr_detail,
            'supplier' => $suppliers
        ];

        if ($sr) {
            return $this->sendResponse($data, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }
}
