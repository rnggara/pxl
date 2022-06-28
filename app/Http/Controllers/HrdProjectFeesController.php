<?php

namespace App\Http\Controllers;

use App\Models\Finance_treasury;
use App\Models\Finance_treasury_history;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Marketing_project;
use App\Models\Marketing_projects_associates;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;

class HrdProjectFeesController extends Controller
{
    public function index(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $projects = Marketing_project::whereIn('company_id', $id_companies)->get();

        return view('project_fees.index',[
            'projects' => $projects
        ]);
    }

    public function detail($id){
        $project = Marketing_project::find($id);
        $associates = Marketing_projects_associates::where('id_project', $id)->get();
        $user_data = User::where('company_id', $project->company_id)->get();
        $user = array();
        foreach ($user_data as $item){
            $user[$item->id] = $item;
        }
        $treasury = Finance_treasury::where('company_id', Session::get('company_id'))->get();

        return view('project_fees.detail', [
            'project' => $project,
            'associates' => $associates,
            'user' => $user,
            'treasury' => $treasury
        ]);
    }

    public function approve(Request $request){
//        dd($request);
        $prj = Marketing_project::find($request->id_project);
        $prj->fee_approve_notes = $request->notes;
        $prj->fee_approve_at = date('Y-m-d H:i:s');
        $prj->fee_approve_by = Auth::user()->username;
        $prj->save();
        return redirect()->route('hrd.project_fees.index');
    }

    public function pay(Request $request){
//        dd($request);
        $prj = Marketing_project::find($request->id_project);
        $prj->paid_at = date('Y-m-d H:i:s');
        $prj->paid_by = Auth::user()->username;
        if ($prj->save()){
            $associates = Marketing_projects_associates::where('id_project', $request->id_project)->get();
            $user = User::all()->pluck('name', 'id');

            foreach ($associates as $associate) {
                $tre = new Finance_treasury_history();
                $tre->id_treasure = $request->source;
                $tre->date_input = $prj->paid_at;
                $tre->description = "Fee [".$prj->prj_name."] ".$user[$associate->id_user];
                $tre->IDR = $associate->fee_amount * -1;
                $tre->PIC = Auth::user()->username;
                $tre->created_by = Auth::user()->username;
                $tre->company_id = Session::get('company_id');
                $tre->save();
            }
        }

        return redirect()->route('hrd.project_fees.index');
    }
}
