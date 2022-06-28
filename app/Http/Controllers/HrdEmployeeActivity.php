<?php

namespace App\Http\Controllers;

use App\Models\Activity_log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HrdEmployeeActivity extends Controller
{
    function index(){
        return view('employee.activity.index');
    }

    function get(Request $request){

        $emp = User::where('company_id', Session::get('company_id'))->get();

        $emp_ids = $emp->pluck('id');
        // dd($emp_ids);
        $dateNow = date("Y-m-d");
        $activity = Activity_log::whereIn('causer_id', $emp_ids)
            ->where('created_at', 'like', "$dateNow%")
            ->orderBy('created_at')
            ->get();

        $emp_act = [];
        foreach($activity as $item){
            $emp_act[$item->causer_id][] = date("Y-m-d H:i", strtotime($item->created_at." +7 hours"));
        }

        $emp_time = [];

        foreach($emp_act as $i => $item){
            $n = array_unique($item);
            $r = [];
            $t = [];
            foreach($n as $m){
                $r[] = $m;
                $ntime = explode(" ", $m);
                $t[] = intval(explode(":", end($ntime))[0]);
            }
            $emp_act[$i] = $r;
            $emp_time[$i] = $t;
        }

        foreach($emp_time as $i => $item){
            $n = array_unique($item);
            $r = [];
            foreach($n as $m){
                $r[] = $m;
            }
            $emp_time[$i] = $r;
        }
        // dd($emp_act, $emp_time);
        $row = [];

        foreach($emp as $i => $item){
            $status = "<span class='label label-inline'>inactive</span>";
            $m = 0;
            $hours = 0;
            $tr = 0;
            if(isset($emp_act[$item->id])){
                $last_online = date_create($emp_act[$item->id][count($emp_act[$item->id]) - 1]);
                $date1 = date_create(date("Y-m-d H:i"));
                $diff = date_diff($date1, $last_online);

                $m = $diff->format("%i");
                if($m <= 10){
                    $status = "<span class='label label-inline label-success'>active</span>";
                    $tr++;
                }
            }

            if(isset($emp_time[$item->id])){
                $emptime = $emp_time[$item->id];
                foreach($emptime as $t => $n){
                    if(isset($emptime[$t + 1])){
                        $di = $emptime[$t+1] - $n;
                        if($di == 1){
                            $hours++;
                        } else {
                            $hours++;
                        }
                    }
                }
            }

            if($hours > 0){
                $tr++;
            }

            $link = route('employee.activity.detail',$item->id);
            $name = "<a href='$link' class='label label-inline label-primary bg-hover-primary-o-3'>$item->name</a>";

            if(isset($request->chart)){
                $name = $item->name;
            }

            $col = [];
            $col['i'] = $i+1;
            $col['name'] = $name;
            $col['username'] = $item->username;
            $col['status'] = $status;
            $col['hour'] = $hours;
            if(isset($request->chart) && $request->chart == "on"){
                if($hours > 0){
                    $row[] = $col;
                }
            } else {
                $row[] = $col;
            }

        }

        if(isset($request->chart)){
            usort($row, function($a, $b){
                return $a['hour'] < $b['hour'] ? 1 : -1;
            });
        }

        $result = array(
            "data" => $row
        );

        return json_encode($result);
    }

    function detail($id){
        $user = User::find($id);

        $start = date("Y-m-d", strtotime("-7 days"));

        return view('employee.activity.detail', compact('user', 'start'));
    }

    function detail_chart(Request $request, $id){
        $data = [];

        if($request->type == "w"){
            $date1 = date("Y-m-d", strtotime("-7 days"));
        } elseif ($request->type == "m"){
            $date1 = date("Y-m-d", strtotime("-1 month"));
        } elseif ($request->type == "y"){
            $date1 = date("Y-m-d", strtotime("-1 year"));
        }


        $date2 = date("Y-m-d", strtotime("+1 day"));

        $activity = Activity_log::where('causer_id', $id)
            ->whereBetween('created_at', [$date1, $date2])
            ->get();

        $time = [];

        foreach($activity as $item){
            $row = [];
            $time[date("Y-m-d", strtotime($item->created_at))][] = date("H", strtotime($item->created_at));
            // $date
        }

        $ntime = [];

        foreach($time as $i => $item){
            $n = array_unique($item);
            $t = [];
            foreach($n as $m){
                $t[] = $m;
            }
            $ntime[$i] = $t;
        }

        $gtime = [];

        foreach($ntime as $i => $item){
            $row = [];
            $row['date'] = $i;
            $row['hour'] = intval(end($item)) - intval($item[0]) + 1;
            $gtime[$i] = intval(end($item)) - intval($item[0]) + 1;
            $data[] = $row;
        }

        $tgl = [];
        $dd1 = $date1;
        while($dd1 <= $date2){
            $tgl[] = $dd1;
            $dd1 = date("Y-m-d", strtotime($dd1." +1 day"));
        }

        $res = [];
        foreach($tgl as $item){
            $row = [];
            $hour = 0;
            if(isset($gtime[$item])){
                $hour = $gtime[$item];
            }
            $row['date'] = $item;
            $row['hour'] = $hour;
            $res[] = $row;
        }



        $result = array(
            "data" => $res,
            "from" => date("d/m/Y", strtotime($date1)),
            "to" => date("d/m/Y")
        );

        return json_encode($result);
    }
}
