<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset_wo;
use App\Models\Asset_wo_detail;
use Illuminate\Http\Request;

class AssetWoController extends BaseController
{
    public function approve(Request $request){
        $po = Asset_wo::find($request->id);
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
        $po = Asset_wo::leftJoin('asset_organization as supplier','supplier.id','=','asset_wo.supplier_id')
            ->leftJoin('marketing_projects as prj','prj.id','=','asset_wo.project')
            ->select('asset_wo.*','supplier.name as supplier_name','prj.prj_name as project_name')
            ->where('asset_wo.company_id', $comp_id)
            ->whereNull('asset_wo.deleted_at')
            ->get();
        if ($po){
            return $this->sendResponse($po, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

    public function getDetail($comp_id, $id){
        $po = Asset_wo::leftJoin('asset_organization as supplier','supplier.id','=','asset_wo.supplier_id')
            ->leftJoin('marketing_projects as prj','prj.id','=','asset_wo.project')
            ->select('asset_wo.*','supplier.name as supplier_name','prj.prj_name as project_name','supplier.address as supplier_address')
            ->where('asset_wo.company_id', $comp_id)
            ->where('asset_wo.id', $id)
            ->whereNull('asset_wo.deleted_at')
            ->first();

        $po_detail = Asset_wo_detail::where('asset_wo_detail.wo_id', $id)
            ->get();
//        dd($po_detail);
        $data = [
            'wo' => $po,
            'wo_detail' => $po_detail
        ];

        if ($po){
            return $this->sendResponse($data, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }
}
