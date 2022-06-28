<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset_po;
use App\Models\Asset_po_detail;
use App\Models\Asset_type_po;
use App\Models\Marketing_project;
use App\Models\Pref_tax_config;
use App\Models\Procurement_vendor;
use Illuminate\Http\Request;

class AssetPoController extends BaseController
{
    public function approve(Request $request){
        $po = Asset_po::find($request->id);
        $po->approved_by = $request->username;
        $po->approved_time = date('Y-m-d H:i:s');
        $po->appr_notes = $request->notes;

        if ($po->save()){
            return $this->sendResponse($po,'Approve Success');
        } else {
            return $this->sendError('Approve Failed');
        }
    }

    public function index($comp_id){
        $po = Asset_po::leftJoin('asset_organization as supplier','supplier.id','=','asset_po.supplier_id')
            ->leftJoin('marketing_projects as prj','prj.id','=','asset_po.project')
            ->select('asset_po.*','supplier.name as supplier_name','prj.prj_name as project_name')
            ->where('asset_po.company_id', $comp_id)
            ->whereNull('asset_po.deleted_at')
            ->get();
        if ($po){
            return $this->sendResponse($po, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

    public function getDetail($comp_id, $id){
        $po = Asset_po::leftJoin('asset_organization as supplier','supplier.id','=','asset_po.supplier_id')
            ->leftJoin('marketing_projects as prj','prj.id','=','asset_po.project')
            ->select('asset_po.*','supplier.name as supplier_name','prj.prj_name as project_name','supplier.address as supplier_address')
            ->where('asset_po.company_id', $comp_id)
            ->where('asset_po.id', $id)
            ->whereNull('asset_po.deleted_at')
            ->first();

        $po_detail = Asset_po_detail::leftJoin('asset_items as item','item.item_code','=','asset_po_detail.item_id')
            ->select('asset_po_detail.*','item.name as item_name','item.uom as item_uom')
            ->where('asset_po_detail.po_num', $id)
            ->get();
        $data = [
            'po' => $po,
            'po_detail' => $po_detail
        ];

        if ($po){
            return $this->sendResponse($data, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

}
