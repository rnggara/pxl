<?php

namespace App\Http\Controllers;

use App\Models\Hrd_employee;
use App\Models\Hrd_point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class HrdPointController extends Controller
{
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

        $users = Hrd_employee::whereIn('company_id', $id_companies)
            ->orderBy('emp_name', 'asc')
            ->whereNull('expel')
            ->get();
        $emp_data = array();
        foreach ($users as $item){
            $emp_data[$item->id] = $item;
        }

        $points = Hrd_point::whereIn('company_id', $id_companies)
            ->where('created_by', '!=', 'system')
            ->get();

        return view('point.index', [
            'users' => $users,
            'points' => $points,
            'emp_data' => $emp_data
        ]);
    }

    function add(Request $request){
        $point = new Hrd_point();
        $point->id_p = $request->informer;
        $point->id_t = $request->defendant;
        $point->gp = $request->gp;
        $point->bp = $request->bp;
        $point->keterangan = $request->explain;
        $point->date_of_case = $request->dateofcase;
        $point->created_by = Auth::user()->username;
        $point->company_id = Session::get('company_id');
        $point->status = 0;
        $point->save();

        return redirect()->route('point.index');
    }

    function approve(Request $request){
        // dd($request);
        $point = Hrd_point::find($request->id_point);
        $point->gp = $request->gp;
        $point->bp = $request->bp;
        $point->keterangan = $request->notes;
        if ($request->type_appr == "hrd"){
            $point->hrd_approved_at = date('Y-m-d H:i:s');
            $point->hrd_approved_by = Auth::user()->username;
            $point->status = 1;
        } else {
            $point->bod_approved_at = date('Y-m-d H:i:s');
            $point->bod_approved_by = Auth::user()->username;
            $point->status = 2;

            $empIdInformer = $point->id_p;
            $empIdDefendant = $point->id_t;
            $inf = Hrd_employee::where('id',$empIdInformer)->first();
            $def = Hrd_employee::where('id',$empIdDefendant)->first();
            $a = 0;$b=0;
            $oldInf = intval($inf->point);
            $a += ($oldInf + intval($point->gp));
            Hrd_employee::where('id', $empIdInformer)
                ->update([
                    'point' => $a,
                ]);
            if (!empty($def)) {
                $oldDef = intval($def->point);
                $b += ($oldDef - intval($point->bp));
                Hrd_employee::where('id', $empIdDefendant)
                ->update([
                    'point' => $b,
                ]);
            }
        }

        $point->save();
        return redirect()->route('point.index');
    }

    function delete($id){
        if (Hrd_point::find($id)->delete()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }
}
