<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityConfig;
use App\Models\Hrd_employee;
use App\Models\Hrd_performa_review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class GeneralPerformaReviewController extends Controller
{
    function index(){
        $emp = Hrd_employee::leftJoin('hrd_employee_type as cat','cat.id','=','hrd_employee.emp_type')
            ->select('hrd_employee.*','cat.name as empType')
            ->where('hrd_employee.company_id', Session::get('company_id'))
            ->get();
        $emp_by_id = array();
        foreach ($emp as $item){
            $emp_by_id[$item->id] = $item;
        }

        $performa_review = Hrd_performa_review::where('company_id',Session::get('company_id'))->get();
        $performa_by_id = array();
        foreach ($performa_review as $item){
            $performa_by_id[$item->emp_id] = $item;
        }

        return view('performa_review.index', [
            'emp' => $emp,
            'performa_ref' => $performa_by_id,
            'emp_id' => $emp_by_id
        ]);
    }

    function add(Request $request){
        ActivityConfig::store_point('performa_review', 'create');
        $pr = new Hrd_performa_review();
        $pr->emp_id = $request->id_emp;
        $pr->superior_id = Auth::user()->username;
        $pr->review_date = date('Y-m-d H:i:s');
        $pr->entry_point = json_encode($request->answer);
        $pr->entry_goal = json_encode($request->goal);
        $pr->entry_strength = json_encode($request->strength);
        $pr->review_type = "yearly";
        $pr->final_score = round(array_sum($request->answer)/(count($request->answer)*5), 1) * 10;
        $pr->company_id = Session::get('company_id');
        $pr->created_by = Auth::user()->username;
        $pr->save();

        return redirect()->route('general.pr.index');
    }

    function approve(Request $request){
        ActivityConfig::store_point('meeting_scheduler', 'approve');
        $pr = Hrd_performa_review::find($request->id_per);
        $pr->entry_point = json_encode($request->answer_edit);
        $pr->entry_goal = json_encode($request->goal_edit);
        $pr->entry_strength = json_encode($request->strength_edit);
        $pr->final_score = round(array_sum($request->answer_edit)/(count($request->answer_edit)*5), 1) * 10;
        $pr->approved_by = Auth::user()->username;
        $pr->approved_date = date('Y-m-d H:i:s');
        if ($pr->save()){
            $points = $request->answer_edit;
            $sumpoint = 0;
            $count = 0;
            foreach ($points as $po){
                $sumpoint += $po;
                $count++;
            }
            $avg = round($sumpoint / $count);

            $emp = Hrd_employee::where('id', $pr->emp_id)->first();
            $a = 0;
            $old = intval($emp->point);
            $a += ($old + $avg);
            Hrd_employee::where('id', $pr->emp_id)
                ->update([
                    'point' => $a,
                ]);
        }

        return redirect()->route('general.pr.index');
    }
}
