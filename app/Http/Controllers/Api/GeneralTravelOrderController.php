<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ActivityConfig;
use App\Models\Pref_work_environment;
use Illuminate\Http\Request;
use App\Models\General_travel_order;
use DB;
use Session;
use Illuminate\Support\Facades\Auth;

class GeneralTravelOrderController extends BaseController
{
    public function index($comp_id){
        $to = General_travel_order::select('general_to.id', 'general_to.doc_num','general_to.doc_date','general_to.destination','general_to.action','general_to.action_by',
            'general_to.action_time','general_to.status', 'employee.emp_name as emp_name','prj.prj_name as prj_name')
            ->join('hrd_employee as employee','employee.id','=','general_to.employee_id')
            ->join('marketing_projects as prj', 'prj.id','=','general_to.project')
            ->where('employee.company_id', $comp_id)
            ->where('general_to.company_id',$comp_id)
            ->where('prj.company_id',$comp_id)
            ->whereNull('employee.expel')
            ->whereNull('general_to.deleted_at')
            ->orderBy('general_to.id', 'desc')
            ->get();

        if ($to){
            return $this->sendResponse($to, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

    public function get($id){
        $to = General_travel_order::select('general_to.doc_num','general_to.doc_date','general_to.destination','general_to.dest_type','general_to.travel_type','general_to.action','general_to.action_by', 'general_to.departure_dt', 'general_to.return_dt', DB::raw("DATEDIFF(general_to.return_dt, general_to.departure_dt) as days"),
            'general_to.action_time','general_to.status', 'employee.emp_name as emp_name', 'employee.emp_id as emp_id', 'prj.prj_name as prj_name')
            ->join('hrd_employee as employee','employee.id','=','general_to.employee_id')
            ->join('marketing_projects as prj', 'prj.id','=','general_to.project')
            ->where('general_to.id', $id)
            ->first();

        if ($to) {
            return $this->sendResponse($to, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

    public function approve(Request $request){
        $to = General_travel_order::find($request->id);
        $to->action = 'approve';
        $to->action_by = $request->username;
        $to->action_time = date('Y-m-d H:i:s');
        $to->action_notes = $request->notes;
        $to->status = 0;
        if ($to->save()){
            return $this->sendResponse($to,'Approve Success');
        } else {
            return $this->sendError('Approve Failed');
        }
    }

}
