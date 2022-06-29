<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Cuti;
use App\Models\Role;
use App\Models\User;
use App\Models\Module;
use App\Models\Mtg_mom;
use App\Models\Asset_po;
use App\Models\Asset_wh;
use App\Models\Asset_wo;
use App\Models\Division;
use App\Models\Mtg_main;
use App\Models\Asset_pre;
use App\Models\Asset_sre;
use App\Models\Frm_forum;
use App\Models\Frm_topik;
use App\Models\Asset_item;
use App\Models\Master_name;
use App\Models\Asset_qty_wh;
use App\Models\Hrd_employee;
use App\Models\RoleDivision;
use App\Models\Storage_user;
use App\Rms\RolesManagement;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\Item_qty_user;
use App\Models\General_report;
use App\Models\File_Management;
use App\Models\Asset_qty_rumors;
use App\Models\Finance_business;
use App\Models\Notification_log;
use App\Models\General_documents;
use App\Models\Item_sell_history;
use App\Models\Marketing_project;
use App\Models\Preference_config;
use App\Models\Asset_new_category;
use Illuminate\Support\Facades\DB;
use App\Models\General_meeting_zoom;
use App\Models\General_travel_order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Models\General_covid_employee;
use App\Models\Finance_business_detail;
use Spatie\Activitylog\Models\Activity;
use App\Models\Finance_treasury_history;
use App\Models\Asset_item_classification;
use App\Http\Controllers\Auth\LoginController;
use App\Models\General_meeting_scheduler_book;
use App\Models\General_meeting_scheduler_room;
use App\Models\General_meeting_scheduler_topic;
use App\Models\General_meeting_zoom_participant;
use App\Models\General_meeting_scheduler_timecheck;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (get_config() == 0){
            return redirect()->route('install');
        }
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if(empty(Session::get('company_user_id'))){
            if(empty(Auth::id())){
                return redirect('/');
            } else {
                Auth::logout();
                return redirect('/');
            }
        }
        $notif = Notification_log::where('id_users', 'like', '%"'.Auth::id().'"%')
            ->orderBy('created_at', 'desc')
            ->whereNull('action_at')
            ->distinct()
            ->get(['text', 'id', 'id_item', 'item_type', 'url']);

        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }

        $fr_items = Asset_pre::whereIn('company_id', $id_companies)->get();
        $so_items = Asset_sre::whereIn('company_id', $id_companies)->get();
        $po_items = Asset_po::whereIn('company_id', $id_companies)->get();
        $wo_items = Asset_wo::whereIn('company_id', $id_companies)->get();
        $fr = array();
        $po = [];
        $sre = [];
        $wo = [];

        foreach ($po_items as $key => $value) {
            $po[$value->id] = $value;
        }

        foreach ($so_items as $key => $value) {
            $sre[$value->id] = $value;
        }

        foreach ($wo_items as $key => $value) {
            $wo[$value->id] = $value;
        }

        foreach ($fr_items as $item){
            $fr[$item->id] = $item;
        }

        $rNotif = array();
        $rc = Session::get('company_user_rc');


        //get last mom
        $mom = Mtg_main::whereIn('company_id', $id_companies)
            ->orderBy('id_main', 'desc')
            ->first();

        $division = Division::all();
        $div = [];
        foreach ($division as $key => $value) {
            $div[$value->id] = $value->name;
        }

        //get 3 last daily report
        $report = General_report::whereIn('company_id', $id_companies)
            ->orderBy('id')
            ->limit(3)
            ->get();

        //cuti
        $leave = Cuti::whereIn('company_id', $id_companies)
            ->whereNotNull('div_date')
            ->whereNotNull('hrd_date')
            ->where('awal', '>', date('Y-m-d'))
            ->orderBy('c_id', 'desc')
            ->get();

        $emp = Hrd_employee::whereIn('company_id', $id_companies)
            ->get();

        $employee = [];
        foreach ($emp as $key => $value) {
            $employee[$value->id] = $value;
        }


        // request ajax
        if($request->ajax()){
            if($request->t == "meeting"){
                $meeting = General_meeting_scheduler_book::where('tanggal','>=',date('Y-m-d'))->orderBy("tanggal", "DESC")->get();
                $topic = General_meeting_scheduler_topic::all();
                $time = General_meeting_scheduler_timecheck::all();
                $room = General_meeting_scheduler_room::all();
                $detail_meeting = array();
                foreach ($room as $value){
                    $detail_meeting['room'][$value->id] = $value;
                }
                foreach ($time as $value){
                    $detail_meeting['time'][$value->id_book][] = $value;
                }
                foreach ($topic as $value){
                    $detail_meeting['topic'][$value->id_book] = $value;
                }

                $meeting_zoom = General_meeting_zoom::where("meeting_date", ">=", date("Y-m-d"))->orderBy('meeting_date', 'desc')->get();

                $_meeting = [];

                foreach($meeting_zoom as $item){
                    $row = [];
                    $date1 = date_create($item->meeting_date);
                    $date2 = date_create(date('Y-m-d'));
                    $diff = date_diff($date2, $date1);
                    $diff_num = intval($diff->format("%a"));
                    if ($diff_num < 3){
                        $bg = "danger";
                    } elseif ($diff_num >= 3 && $diff_num <= 5){
                        $bg = "warning";
                    } elseif ($diff_num > 5){
                        $bg = "success";
                    }

                    $participant = General_meeting_zoom_participant::where("meeting_id", $item->id)
                        ->where("user_id", Auth::id())
                        ->first();
                    $checked = "";
                    if(!empty($participant)){
                        $checked = "CHECKED";
                    }

                    $link = "";
                    if(!empty($participant)){
                        $link = $item->link_zoom;
                        $row['topic'] = $item->description;
                        $row['room'] = '<div class="checkbox-list"><label class="checkbox checkbox-outline checkbox-primary checkbox-outline-2x"><input type="checkbox" onclick="zoom_join(this)" '.$checked.' name="cb" data-id="'.$item->id.'" /><span></span><a href="#" onclick="window.open(\''.$link.'\', \'_blank\', \'location=yes,height=570,width=520,scrollbars=yes,status=yes\');" class="meeting-span" style="word-break : break-all">'.$link.'</a></label></div>';
                        $row['jam_in'] = $item->meeting_time;
                        $row['jam_out'] = null;
                        $row['url'] = route("mz.view", $item->id);
                        $row['type'] = "z";
                        $row['date'] = $item->meeting_date;
                        $row['bg'] = $bg;
                        $_meeting[] = $row;
                    }
                }

                foreach($meeting_zoom as $item){
                    $row = [];
                    $date1 = date_create($item->meeting_date);
                    $date2 = date_create(date('Y-m-d'));
                    $diff = date_diff($date2, $date1);
                    $diff_num = intval($diff->format("%a"));
                    if ($diff_num < 3){
                        $bg = "danger";
                    } elseif ($diff_num >= 3 && $diff_num <= 5){
                        $bg = "warning";
                    } elseif ($diff_num > 5){
                        $bg = "success";
                    }

                    $participant = General_meeting_zoom_participant::where("meeting_id", $item->id)
                        ->where("user_id", Auth::id())
                        ->first();
                    $checked = "";
                    if(!empty($participant)){
                        $checked = "CHECKED";
                    }

                    $link = "";
                    if(empty($participant)){
                        $link = $item->link_zoom;
                        $row['topic'] = $item->description;
                        $row['room'] = '<div class="checkbox-list"><label class="checkbox checkbox-outline checkbox-primary checkbox-outline-2x"><input type="checkbox" onclick="zoom_join(this)" '.$checked.' name="cb" data-id="'.$item->id.'" /><span></span><a href="#" onclick="window.open(\''.$link.'\', \'_blank\', \'location=yes,height=570,width=520,scrollbars=yes,status=yes\');" class="meeting-span" style="word-break : break-all">'.$link.'</a></label></div>';
                        $row['jam_in'] = $item->meeting_time;
                        $row['jam_out'] = null;
                        $row['url'] = route("mz.view", $item->id);
                        $row['type'] = "z";
                        $row['date'] = $item->meeting_date;
                        $row['bg'] = $bg;
                        $_meeting[] = $row;
                    }
                }

                foreach($meeting as $item){
                    $row = [];
                    $topic = "";
                    $room = "";
                    $jam_in = "";
                    $jam_out = "";
                    if(isset($detail_meeting['topic'][$item->id])){
                        $topic = $detail_meeting['topic'][$item->id]['topic_meeting'];
                    }

                    if(isset($detail_meeting['room'][$item->id])){
                        $room = $detail_meeting['room'][$item->id]['nama_ruangan'];
                    }

                    if(isset($detail_meeting['time'][$item->id])){
                        $jam_in = $detail_meeting['time'][$item->id][0]['jam'];
                        $jam_out = end($detail_meeting['time'][$item->id])['jam'];
                    }

                    $date1 = date_create($item->tanggal);
                    $date2 = date_create(date('Y-m-d'));
                    $diff = date_diff($date2, $date1);
                    $diff_num = intval($diff->format("%a"));
                    if ($diff_num < 3){
                        $bg = "danger";
                    } elseif ($diff_num >= 3 && $diff_num <= 5){
                        $bg = "warning";
                    } elseif ($diff_num > 5){
                        $bg = "success";
                    }

                    if($topic != ""){
                        $row['topic'] = $topic;
                        $row['room'] = $room;
                        $row['jam_in'] = $jam_in;
                        $row['jam_out'] = $jam_out;
                        $row['url'] = "#";
                        $row['type'] = "m";
                        $row['bg'] = $bg;
                        $row['date'] = $item->tanggal;
                        $_meeting[] = $row;
                    }
                }

                return view("zoom._list", compact("meeting", "detail_meeting", "_meeting"));
            }

            if(isset($request->v)){
                if($request->v == "town"){
                    $view = htmlspecialchars_decode(view('zmenu._town'));

                    $img = htmlspecialchars_decode(view('zmenu._img'));

                    $data = [
                        "img" => $img,
                        "view" => $view,
                        "title" => ucwords(str_replace("_", " - ", $request->v))
                    ];

                    return Response::json($data);
                }

                // START::MARKET
                if($request->v == "market"){
                    $view = htmlspecialchars_decode(view('zmenu._market'));

                    $data = [
                        "img" => "",
                        "view" => $view,
                        "title" => ucwords(str_replace("_", " - ", $request->v))
                    ];

                    return Response::json($data);
                }

                if($request->v == "market_sell"){
                    $xenolot_name = "[Xenolot_name]";
                    $view = htmlspecialchars_decode(view('zmenu._market_sell', compact("xenolot_name")));

                    $data = [
                        "img" => "",
                        "view" => $view,
                        "title" => ucwords(str_replace("_", " - ", $request->v))
                    ];

                    return Response::json($data);
                }

                if($request->v == "market_buy"){
                    $xenolot_id = Auth::user()->emp_id;
                    $view = htmlspecialchars_decode(view('zmenu._market_buy', compact("xenolot_id")));

                    $data = [
                        "img" => "",
                        "view" => $view,
                        "title" => ucwords(str_replace("_", " - ", $request->v))
                    ];

                    return Response::json($data);
                }

                if($request->v == "market_buy_item"){
                    $items = [
                        "A" => 500,
                        "B" => 250,
                        "C" => 100,
                    ];
                    $view = htmlspecialchars_decode(view('zmenu._market_buy_item', compact("items")));

                    $data = [
                        "img" => "",
                        "view" => $view,
                        "title" => ucwords(str_replace("_", " - ", $request->v))
                    ];

                    return Response::json($data);
                }
                // END::MARKET

                // START::LABORATORY
                if($request->v == "laboratory"){
                    $view = htmlspecialchars_decode(view('zmenu._laboratory'));

                    $data = [
                        "img" => "",
                        "view" => $view,
                        "title" => ucwords(str_replace("_", " - ", $request->v))
                    ];

                    return Response::json($data);
                }

                if($request->v == "laboratory_manage"){

                    $myXenolot = [
                        "A" => 0,
                        "B" => 1,
                        "C" => 0,
                    ];

                    $view = htmlspecialchars_decode(view('zmenu._laboratory_manage', compact("myXenolot")));

                    $data = [
                        "img" => "",
                        "view" => $view,
                        "title" => ucwords(str_replace("_", " - ", $request->v))
                    ];

                    return Response::json($data);
                }

                if($request->v == "laboratory_combine"){

                    $myXenolot = [
                        "A" => 0,
                        "B" => 1,
                        "C" => 0,
                    ];

                    $view = htmlspecialchars_decode(view('zmenu._laboratory_combine', compact("myXenolot")));

                    $data = [
                        "img" => "",
                        "view" => $view,
                        "title" => ucwords(str_replace("_", " - ", $request->v))
                    ];

                    return Response::json($data);
                }
                // END:::LABORATORY

                if($request->v == "summoning altar"){
                    $view = htmlspecialchars_decode(view('zmenu._altar'));

                    $data = [
                        "img" => "",
                        "view" => $view,
                        "title" => ucwords(str_replace("_", " - ", $request->v))
                    ];

                    return Response::json($data);
                }

                if($request->v == "home"){
                    $view = htmlspecialchars_decode(view('zmenu._home'));

                    $data = [
                        "img" => "",
                        "view" => $view,
                        "title" => ucwords(str_replace("_", " - ", $request->v))
                    ];

                    return Response::json($data);
                }
            }
        }

        $weekago = date("Y-m-d", strtotime("-7 days"));
        $covid = General_covid_employee::where('tanggal_infeksi', '>=', $weekago)
            ->whereNull('tanggal_negatif')
            ->orderBy('id', 'desc')
            ->first();
        $ccomp = ConfigCompany::all()->pluck('company_name', 'id');

        // dd(Session::all());

        $_user = User::find(Auth::id());

        $business = false;

        $_role = RoleDivision::find($_user->id_rms_roles_divisions);

        $_div = Division::find($_role->id_rms_divisions);

        $_roles_rms = Role::find($_role->id_rms_roles);

        $bs_detail = [];
        $bs_name = [];
        $bs_penalty = [];

        if(in_array($_roles_rms->name, ["Director", "admin"])){
            if(in_array($_div->name, ["admin", "HRD", "Finance"])){
                $business = true;
            }
        }

        if($business){
            $bs = Finance_business::where('company_id', Session::get('company_id'))->get();

            $bs_name = $bs->pluck('bank', 'id');
            $bs_penalty = $bs->pluck('own_amount', 'id');

            $bs_detail = Finance_business_detail::whereIn('id_business', $bs->pluck('id'))
                ->where('plan_date', '<', date("Y-m-d"))
                ->where('status', 'Planned')
                ->get();
        }

        $_to = [];

        $div_name = "";

        if(!empty($_div->name)){
            $div_name = $_div->name;
            if(in_array(strtolower($_div->name), ["admin", "hrd"])){
                $to_list = General_travel_order::where('company_id', Session::get("company_id"))
                    ->where('return_dt', '>', date("Y-m-d"))
                    ->orderBy('departure_dt', 'desc')
                    ->get();
                foreach ($to_list as $key => $value) {
                    $row = [];
                    $d1 = date_create($value->departure_dt);
                    $d2 = date_create(date("Y-m-d"));
                    $diff = date_diff($d1, $d2);
                    $days = 0;
                    $days += $diff->format("%d");
                    // if($diff("%m") > 0){
                    //     $days += ($diff)
                    // }

                        $prj = Marketing_project::find($value->project);

                    $default_diff = 25;
                    if(!empty($prj)){
                        $default_diff = $prj->crew_notification;
                    }

                    $row['emp_name'] = Hrd_employee::find($value->employee_id)->emp_name;
                    $row['days'] = $days;
                    $row['departure_dt'] = $value->departure_dt;
                    $row['month'] = $diff->format("%m");
                    $value->days = $days;
                    if($diff->format("%m") > 0 || ($diff->format("%m") == 0 && $days >= $default_diff)){
                        $_to[] = $row;
                    }
                }
            }
        }

        $towns = Asset_wh::get()->pluck("name", "id");

        $leaderboards = User::selectRaw("*, CAST(do_code as UNSIGNED) as bl")
            ->whereNotNull('discord_id')
            ->orderBy('bl', 'desc')
            // ->limit(10)
            ->get();

        foreach($leaderboards as $item){
            $hometown = "";
            if(isset($towns[$item->home_id])){
                $hometown = $towns[$item->home_id];
            }

            $item->hometown = $hometown;
        }

        $plant = [];
        if(!empty(Auth::user()->item_ripe_id)){
            $plant = Asset_item::find(Auth::user()->item_ripe_id);
        }

        $currentLocation = Storage_user::where("user_id", Auth::id())->first();
        $currentCity = [];
        $friends = [];
        if(!empty($currentLocation)){
            $currentCity = Asset_wh::find($currentLocation->wh_id);
            $friends = Storage_user::selectRaw("users.name as name, users.id as id")->where("wh_id", $currentCity->id)
                ->where("user_id", "!=", $currentLocation->user_id)
                ->leftJoin("users", 'storage_user.user_id', "users.id")
                ->get()->pluck("name", 'id');
        }

        $mycity = Asset_wh::find(Auth::user()->home_id);

        $forum = Frm_forum::find(1);
        $changelogs = [];
        if(!empty($forum)){
            $changelogs = Frm_topik::where("id_forum", $forum->id)
                ->orderBy("date_topik","desc")
                ->get();
        }

        $rumors = Asset_qty_rumors::where("user_id", Auth::id())
            ->where("date_heard", date("Y-m-d"))
            ->first();

        $event = round(mt_rand(1, (1/20) * 100));
        $isEvent = 0;
        $currentEvent = (empty(Auth::user()->current_event)) ? 0 : Auth::user()->current_event;
        if($event == 1 && !empty(Auth::user()->item_ripe_id) && empty(Auth::user()->current_event)){
            $isEvent = 1;
            $eventUser = User::find(Auth::id());
            if($eventUser->event_credit > 0){
                $eventUser->current_event = rand(1, 3);
                $eventUser->save();
                $currentEvent = $eventUser->current_event;
            }
        }

        $roster = Asset_item::where("old_id", Auth::id())
            ->whereRaw("LEFT(item_code, 6) = 'ADVCHR'")
            ->get();

        $driver_caravan = Asset_item::find(Auth::user()->roster_driver);

        $champion = Asset_item::find(Auth::user()->roster_champion);

        return view('_home', compact('bs_name', 'rumors', 'roster', 'champion', 'driver_caravan', 'currentEvent', 'event', 'friends', 'leaderboards', 'currentLocation', 'changelogs', 'currentCity', 'mycity', 'plant', 'bs_detail', 'bs_penalty', '_to', 'business', 'div_name', 'rNotif', 'mom', 'report', 'div', 'leave', 'employee', 'covid', 'ccomp'));
    }

    function activity_log(){
        //get activity log
        $ddate = date("Y-m-d", strtotime("-7 days"));
        $activity = Activity::where('causer_id', Auth::id())
            ->where('created_at', '>=', $ddate)
            ->get();

        $actByDay = [];
        foreach ($activity as $key => $value) {
            $dday = date("Y_m_d", strtotime($value->created_at));
            $actByDay[$dday][] = date("Y-m-d H:i:s", strtotime($value->created_at));
            sort($actByDay[$dday]);
        }

        $act = [];
        if (count($actByDay) > 0) {
            $i = 0;
            foreach ($actByDay as $key => $value) {
                $start = strtotime($value[0]);
                $end = strtotime($value[count($value) - 1]);
                $hour = round(abs($end - $start)/(60*60)) . " hour(s)";
                $act[$i]['start'] = $value[0];
                $act[$i]['end'] = $value[count($value) - 1];
                $act[$i]['hours'] = $hour;
                $act[$i]['date'] = date("Y-m-d", strtotime($value[0]));
                $i++;
            }
        }

        $success = false;
        $data = "Data not found";

        if (count($act) > 0) {
            $success = true;
            $data = $act;
        }

        $result = array(
            "success" => $success,
            "data" => $data
        );

        return json_encode($result);
    }

    function menu_list(){
        $menu = Module::whereNotNull('route')->get();

        $hasAction = Session::get('company_user_rc');

        $data = [];
        foreach($menu as $item){
            $txt = ucwords($item->desc).", ".$item->name;
            if(isset($hasAction[$item->name])){
                $action = $hasAction[$item->name];
                if (isset($action['access'])) {
                    $data[] = $txt;
                }
            }
        }

        return json_encode($data);
    }

    function menu_redirect(Request $request){
        $txt = explode(",", $request->menu);
        $menu = Module::where('name', str_replace(" ", "", end($txt)))->first();

        if(!empty($menu)){
            return redirect()->route($menu->route);
        }

        return redirect()->back->with('menu-back', 'no menu');
    }

    function notif_clear($t, $i){
        $type = base64_decode($t);
        $id = base64_decode($i);

        $notif = Notification_log::find($id);
        if($type == "clear"){
            $notif->deleted_by = Auth::user()->username;
            $notif->save();
            $notif->delete();
        }

        return redirect()->back();
    }

    function reset_daily(Request $request){
        // randomize item_qty
        $wh = Asset_wh::where("company_id", 1)->get()->pluck("id");
        $qty_wh = Asset_qty_wh::all();
        $_qtywh = [];
        foreach($qty_wh as $item){
            $_qtywh[$item->wh_id][] = $item->item_id;
        }
        $items = Asset_item::whereRaw("LEFT(item_code, 6) = 'EVTGME'")->get();
        $items_id = $items->pluck("id");
        $items_qty = $items->pluck("minimal_stock", 'id');

        $row = [];
        $now = date("Y-m-d H:i:s");
        // foreach($wh as $val){
        //     if(!isset($_qtywh[$val])){
        //         foreach($items_qty as $itid => $it){
        //             $col = [];
        //             $col['item_id'] = $itid;
        //             $col['wh_id'] = $val;
        //             $col['qty'] = $it;
        //             $col['created_at'] = $now;
        //             $row[] = $col;
        //         }
        //     } else {
        //         foreach($items_qty as $itid => $it){
        //             if(!in_array($itid, $_qtywh[$val])){
        //                 $col = [];
        //                 $col['item_id'] = $itid;
        //                 $col['wh_id'] = $val;
        //                 $col['qty'] = $it;
        //                 $col['created_at'] = $now;
        //                 $row[] = $col;
        //             }
        //         }
        //     }
        // }

        if(count($row) > 0){
            Asset_qty_wh::insert($row);
        }

        $userLb = User::whereNotNull('discord_id')
            ->where('do_code', ">", 0)
            ->get();
        $coUser = count($userLb);

        $query = "update asset_qty_wh set qty = FLOOR(1 + (RAND() * price_min)),";
        $query .= " demand = FLOOR(((select count(*) from users where discord_id is not null and do_code > 0) - 3) + (RAND() * (select count(*) from users where discord_id is not null and do_code > 0))) * FLOOR(3 + (RAND() * 5)),";
        $query .= " quota = FLOOR(3 + (RAND() * 7))";
        $query .= " where item_id in ((SELECT id from asset_items where left(item_code, 6) = 'EVTGME'))";

        DB::statement($query);

        // $qtywh = Asset_qty_wh::whereIn('item_id', $items_id)->get();
        // foreach($qtywh as $item){
        //     $currVal = $item->qty;
        //     // $min = abs($item->price_volatile) * -1;
        //     // $max = abs($item->price_volatile);
        //     // $volatile = rand($min, $max);
        //     // $newVal = $currVal + $volatile;
        //     // $newVal += $item->price_behavior;

        //     $newVal = rand(1,$item->price_min);

        //     // if($newVal < $item->price_min){ // never lower than price min
        //     //     $newVal = $item->price_min;
        //     // } elseif($newVal > ($item->price_min * 10)) { // price max = 10x price min.
        //     //     $newVal = $item->price_min * 10;
        //     // }

        //     $demand = rand(($coUser-3), $coUser) * rand(3,5);
        //     if($demand < 5){
        //         $demand = 30;
        //     }
        //     $item->demand = $demand;
        //     $item->quota = rand(3, 7);

        //     $item->qty = $newVal;
        //     $item->save();
        // }

        // char
        $chars = Asset_item::whereRaw("LEFT(item_code, 6) = 'ADVCHR'")
            ->whereNotNull("old_id")
            ->get();
        foreach($chars as $char){
            if(empty($char->uom2)){
                $char->conversion = 0;
                $char->old_id = null;
            }

            $char->uom2 = null;
            $char->save();

            if($char->price2 > $char->price){
                $char->deleted_by = "Aged";
                $char->save();
                $char->delete();
            }
        }

        $myroster = $chars->pluck("old_id", 'id');

        $notes = [];

        foreach($chars as $char){
            $nt = json_decode($char->notes, true);
            $notes[$char->id] = $nt;
        }

        $pref = Preference_config::where("id_company", 1)->first();
        $user = User::whereNotNull("discord_id")->get();
        foreach($user as $item){
            $daily = (empty($item->attend_code)) ? 0 : intval($item->attend_code);
            $daily += intval($pref->period_start);
            if($daily > $pref->period_end){
                $daily = intval($pref->period_end);
            }
            $this->ActivityLog("Daily energy", ($daily), "energy");
            $item->open_shop_credit = 5;
            $item->attend_code = $daily;
            $item->event_credit = rand(4, 7);
            $item->current_event = null;
            $item->rumor_credit = 1;
            $item->share_rumor_credit = 1;
            $item->daily_skip_credit = 3;
            $item->last_char = null;
            $item->shops = null;
            $item->access = null;
            $item->daily_seed = 1;
            if(isset($notes[$item->roster_driver])){
                $item->trip_credit = $notes[$item->roster_driver]['vit'];
            }
            if(isset($notes[$item->roster_champion])){
                $item->training_credit = $notes[$item->roster_champion]['vit'];
            }

            if(!isset($myroster[$item->roster_driver]) || (isset($myroster[$item->roster_driver]) && $myroster[$item->roster_driver] != $item->id)){
                $item->roster_driver = null;
            }

            if(!isset($myroster[$item->roster_champion]) || (isset($myroster[$item->roster_champion]) && $myroster[$item->roster_champion] != $item->id)){
                $item->roster_champion = null;
            }

            $item->save();
        }

        $method = $request->method();

        if($method == "POST"){
            return redirect()->back()->with("reset", "Success");
        }
    }

    function daily_seed(Request $request){
        if($request->ajax()){
            $user = User::find(Auth::id());
            if($user->daily_seed > 0){
                $seed = Asset_item::whereRaw("LEFT(item_code, 6) = 'EVTSED'")
                    ->inRandomOrder()
                    ->first();
                if(!empty($seed)){
                    $store = Item_qty_user::where("user_id", $user->id)
                        ->where("item_id", $seed->id)
                        ->first();
                    if(empty($store)){
                        $store = new Item_qty_user();
                        $store->item_id = $seed->id;
                        $store->user_id = $user->id;
                    }
                    $qty = (empty($store->qty)) ? 0 : $store->qty;
                    $store->qty = $qty + 1;
                    $store->save();

                    $user->daily_seed -= 1;
                    $user->save();

                    $data = [
                        'success' => 1,
                        "message" => "You get 1 $seed->name"
                    ];
                } else {
                    $data = [
                        'success' => 0,
                        "message" => "There is no seed available"
                    ];
                }
            } else {
                $data = [
                    'success' => 0,
                    "message" => "Your daily seed is already claimed!"
                ];
            }

            return json_encode($data);
        }
    }

    function _get_char_name(){
        $first_name = Master_name::where("type", 1)->inRandomOrder()->first();
        $last_name = Master_name::where("type", 2)->inRandomOrder()->first();
        $name = $first_name->name." ".$last_name->name;
        $isUsed = Asset_item::where("name", $name)->first();
        while(!empty($isUsed)){
            $first_name = Master_name::where("type", 1)->inRandomOrder()->first();
            $last_name = Master_name::where("type", 2)->inRandomOrder()->first();
            $name = $first_name->name." ".$last_name->name;
            $isUsed = Asset_item::where("name", $name)->first();
        }

        return $name;
    }

    function _is_file_exist($dir){
        $file = 0;
        if(\File::exists($dir)){
            $file = 1;
        }

        return $file;
    }

    function generate_char(Request $request){
        $num = 1;
        $row = [];
        $first_name = Master_name::where("type", 1)->inRandomOrder()->first();
        $last_name = Master_name::where("type", 2)->inRandomOrder()->first();
        $cat = Asset_new_category::find(63);
        $class = Asset_item_classification::find(627);

        $last_num = Asset_item::selectRaw("CAST(RIGHT(item_code,4) as unsigned) as last_num")
                ->where("category_id", $cat->id)
                ->where("class_id", $class->id)
                ->orderBy('last_num', 'desc')
                ->first();

        $file_count = 0;
        $filem = File_Management::all()->pluck('file_name', "hash_code");
        $dir = '../public_html/media/asset/documents/';

        $images = glob($dir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        $img_name = [];
        foreach($images as $img){
            $exp = explode("/", $img);
            $img_name[] = end($exp);
        }
        if($last_num->last_num >= 100){
            $document = General_documents::where("file_type", 'DOCUMENT')
                ->get();
            if(count($document) > 0){
                foreach($document as $item){
                    if(isset($filem[$item->file_code])){
                        $exp = explode("/", str_replace("\\", "/", $filem[$item->file_code]));
                        $img = end($exp);
                        if(in_array($img, $img_name)){
                            $file_count++;
                        }
                    }
                }
            }
            if($file_count < 10){
                return redirect()->back()->with("error", "Image < 10");
            }
        }


        for ($i=0; $i < 10; $i++) {
            $item = new Asset_item();
            $last_num = Asset_item::selectRaw("CAST(RIGHT(item_code,4) as unsigned) as last_num")
                ->where("category_id", $cat->id)
                ->where("class_id", $class->id)
                ->orderBy('last_num', 'desc')
                ->first();

            $_num = $i + 1;
            if(!empty($last_num)){
                $_num = $last_num->last_num + 1;
            }
            $item_code = $cat->code.$class->classification_code.sprintf("%04d", $_num);
            $name = $this->_get_char_name();
            $item->item_code = $item_code;
            $item->category_id = $cat->id;
            $item->class_id = $class->id;
            $item->price = rand(50, 70);
            $item->price2 = rand(17, 22);

            $notes = [];
            $notes['vit'] = rand(3,10);
            $notes['spd'] = rand(1,10);
            $notes['luc'] = rand(1,10);
            $item->notes = json_encode($notes);


            $specification = [];
            $specification['agi'] = rand(5,15);
            $specification['ki'] = rand(5,15);
            $specification['str'] = rand(5,15);
            $specification['hp'] = rand(20,40);

            $wage = $notes['vit'] + $notes['spd'] + $notes['luc'];
            if($wage > 30){
                $wage = 30;
            }

            $item->wage_day = $wage;
            $item->wage_start = $wage * 2;

            $item->specification = json_encode($specification);

            $item->name = $name;
            if($_num <= 100){
                $item->picture = $_num.".png";
            } else {
                $doc = General_documents::where("file_type", 'DOCUMENT')->inRandomOrder()->first();
                if(isset($filem[$doc->file_code])){
                    $dir = $filem[$doc->file_code];
                    $ext = explode(".", $dir);
                    $from = "../public_html/".str_replace("\\", "/", $dir);
                    // $isExist = $this->_is_file_exist($from);
                    // while(!$isExist){
                    //     $doc = General_documents::where("file_type", 'DOCUMENT')->inRandomOrder()->first();
                    //     if(isset($filem[$doc->file_code])){
                    //         $dir = $filem[$doc->file_code];
                    //         $ext = explode(".", $dir);
                    //         $from = public_path($dir);
                    //         $isExist = $this->_is_file_exist($from);
                    //     }
                    // }
                    $to = $_SERVER['DOCUMENT_ROOT']."/byr/public/characters/images/".$_num.".".end($ext);
                    // dd($from, $to);
                    File::move($from, $to);
                    $item->picture = $_num.".".end($ext);
                    $doc->delete();
                }
            }
            $item->save();
            $row[] = $item;
        }

        return redirect()->back()->with("char", "Success");
    }

    function train_char(Request $request){
        $char = Asset_item::find(Auth::user()->roster_champion);
        $spec = json_decode($char->specification, true);
        $str = 0;
        $agi = 0;
        $hp = 0;
        $ki = 0;
        $train = $request->train;
        if($train == "punch"){
            $str = rand(4,6);
            $hp = rand(2,4);
            $ki = rand(2,4) * -1;
        } elseif($train == "run"){
            $agi = rand(4,6);
            $str = rand(2,4);
            $hp = rand(2,4) * -1;
        } elseif($train == "meditate"){
            $ki = rand(4,6);
            $hp = rand(2,4);
            $str = rand(2,4) * -1;
        } elseif($train == "weight"){
            $hp = rand(4,6);
            $str = rand(2,4);
            $agi = rand(2,4) * -1;
        }

        $newSpec = [];
        $newSpec['str'] = $spec['str'] + $str;
        $newSpec['agi'] = $spec['agi'] + $agi;
        $newSpec['hp'] = $spec['hp'] + $hp;
        $newSpec['ki'] = $spec['ki'] + $ki;

        if($newSpec['str'] < 0) { $newSpec['str'] = 1; }
        if($newSpec['agi'] < 0) { $newSpec['agi'] = 1; }
        if($newSpec['hp'] < 0) { $newSpec['hp'] = 1; }
        if($newSpec['ki'] < 0) { $newSpec['ki'] = 1; }

        $char->specification = json_encode($newSpec);
        $char->price2 += 1;
        $char->save();
        $user = User::find(Auth::id());
        $user->training_credit -= 1;
        if($user->training_credit < 0){
            $user->training_credit = 0;
        }
        $user->save();

        return redirect()->back()->with('train', 1);
    }

    function assign_char(Request $request){
        $user = User::find(Auth::id());
        $char = Asset_item::find($request->id);
        $notes = json_decode($char->notes, true);
        if($request->type == "driver"){
            $user->trip_credit = $notes['vit'];
            $user->roster_driver = $request->id;
        } elseif ($request->type == "champion"){
            $user->roster_champion = $request->id;
            $user->training_credit = $notes['vit'];
        }
        $user->save();

        return 1;
    }

    function list_char(Request $request){
        $roster = Asset_item::where("old_id", Auth::id())
            ->where("id", "!=", Auth::user()->roster_driver)
            ->where("id", "!=", Auth::user()->roster_champion)
            ->whereRaw("LEFT(item_code, 6) = 'ADVCHR'")
            ->get();

        $type = $request->type;

        return view("zpointer._list_roster", compact("roster", "type"));
    }

    function buy_caravan(Request $request){
        $amount = 200;
        $bl = \Fnh::instance()->checkBalance($amount);
        if($bl){
            $user = User::find(Auth::id());
            $user->own_caravan = 1;
            $user->save();

            $description = "Buy a Caravan for $amount Nc";

            $this->ActivityLog("$description", ($amount * -1), "sell");

            return redirect()->back()->with("caravan", 1);
        } else {
            return redirect()->back()->with("balance", $amount);
        }
    }

    function town_caravan(){
        $id_wh = Storage_user::where("user_id", Auth::id())->get()->pluck("wh_id");
        $city = Asset_wh::whereNotIn("id", $id_wh)
            ->whereNotIn("id", [Auth::user()->home_id])
            ->get();

        return view("zpointer/_list_trip_town", compact("city"));
    }

    function town_confirmation_caravan(Request $request){
        $user = User::find(Auth::id());
        $driver = Asset_item::find(Auth::user()->roster_driver);
        $notes = json_decode($driver->notes, true);
        $duration = 30 - $notes['spd'];
        $user->trip_destination = $request->city_id;
        $user->trip_duration = $duration;
        $user->trip_start = date("Y-m-d H:i:s");
        $user->trip_credit -= 1;
        $user->save();

        $wh = Asset_wh::find($request->city_id);
        $message = "Your destionation is $wh->name and will arrive in $duration seconds";

        $data = [
            'success' => 1,
            'message' => $message
        ];

        return json_encode($data);
    }

    function trip_checkin(Request $request){
        $user = User::find(Auth::id());

        $wh = Asset_wh::find($user->trip_destination);
        $storage = Storage_user::where('user_id', $user->id)->first();

        $storage->wh_id = $user->trip_destination;
        $storage->save();

        $user->trip_destination = null;
        $user->trip_start = null;
        $user->trip_duration = null;
        $user->save();

        return redirect()->back()->with("checkin", $wh->name);
    }

    function trip_countdown(){
        $user = Auth::user();
        if(!empty($user->trip_destination)){
            $date_start = $user->trip_start;
            $wh = Asset_wh::find($user->trip_destination);
            $duration = $user->trip_duration;
            $date_end = date("Y-m-d H:i:s", strtotime($date_start." +$duration seconds"));

            $data = [
                'start' => $date_start,
                'destination' => $wh->name,
                'end' => $date_end,
                'now' => date("Y-m-d H:i:s")
            ];

            $response = [
                "success" => 1,
                "data" => $data
            ];
        } else {
            $response = [
                "success" => 0,
                "data" => []
            ];
        }

        return json_encode($response);
    }

    function hire_char(Request $request){
        $char = Asset_item::find($request->id);
        $amount = 60;
        $user = User::find(Auth::id());
        $first = false;
        if($user->first_hire == 1){
            $amount = 0;
            $first = true;
        }
        $bl = \Fnh::instance()->checkBalance($amount);
        if($bl){
            $char->old_id = Auth::id();
            if($first){
                $char->conversion = 1;
            }
            $char->save();

            if($first){
                $user->first_hire = 0;
                $user->save();
            }

            $description = "Hire $char->name for $amount Nc";

            $this->ActivityLog("$description", ($amount * -1), "sell");

            return redirect()->back()->with("hired", $char->name);
        } else {
            return redirect()->back()->with("balance", $amount);
        }
    }

    function dismiss_char(Request $request){
        $char = Asset_item::find($request->id);
        $char->old_id = null;
        $char->conversion = null;
        if($char->save()){
            return 1;
        } else {
            return 0;
        }
    }

    function pay_char(Request $request){
        $char = Asset_item::find($request->id);
        $amount = $char->wage_day;
        if($char->conversion == 1){
            $amount = 0;
        }
        $bl = \Fnh::instance()->checkBalance($amount);
        if($bl){
            $char->uom2 = 1;
            $char->save();

            $description = "Daily wage $char->name : $amount Nc";
            $this->ActivityLog("$description", ($amount * -1), "sell");

            $data = [
                'success' => 1,
                'data' => "Wage of $char->name has been paid"
            ];
            return json_encode($data);
        } else {
            $data = [
                'success' => 0,
                'data' => "You need $amount Nc to complete this request"
            ];
            return json_encode($data);
        }
    }

    function find_char(Request $request){
        $user = User::find(Auth::id());
        $char = Asset_item::whereRaw("LEFT(item_code, 6) = 'ADVCHR'")
            ->whereNull('old_id')
            ->inRandomOrder()
            ->first();
        if(!empty($user->last_char)){
            $char = Asset_item::whereNull("old_id")->where('id', $user->last_char)->first();
            if(empty($char)){
                $char = Asset_item::whereRaw("LEFT(item_code, 6) = 'ADVCHR'")
                    ->whereNull('old_id')
                    ->inRandomOrder()
                    ->first();
            }
        }
        $user->last_char = $char->id;
        $skip = $request->skip;
        if(!empty($skip)){
            $char = Asset_item::whereRaw("LEFT(item_code, 6) = 'ADVCHR'")
                ->where("id", "!=", $user->last_char)
                ->whereNull('old_id')
                ->inRandomOrder()
                ->first();
            $credit = $user->daily_skip_credit - 1;
            $user->daily_skip_credit = ($credit < 0) ? 0 : $credit;
        }
        $user->save();

        $skip = $user->daily_skip_credit;

        $notes = json_decode($char->notes, true);
        $spec = json_decode($char->specification, true);

        return view("zpointer._hire_char", compact("char", 'notes', 'spec', "skip"));
    }

    function list_city(Request $request){
        $id_wh = Storage_user::where("user_id", Auth::id())->get()->pluck("wh_id");
        $city = Asset_wh::whereNotIn("id", $id_wh)
            ->whereNotIn("id", [Auth::user()->home_id])
            ->get();

        return view("zpointer/_list_city", compact("city"));
    }

    function store_city(Request $request){
        $en = \Fnh::instance()->checkEnergy();
        if($en !== false){
            $wh = Asset_wh::find($request->city_id);
            $this->ActivityLog("-1 Energy for Teleport to $wh->name", -1, "energy");
            $storage = Storage_user::firstOrNew(
                ["user_id" => Auth::id()],
                ["wh_id" => $request->city_id]
            );

            $storage->wh_id = $request->city_id;
            if($storage->save()){
                return json_encode($en);
            } else {
                return json_encode(0);
            }
        } else {
            return json_encode(-1);
        }
    }

    function user_city(){
        $storage = Storage_user::where("user_id", Auth::id())->first();
        $row = [];
        if(!empty($storage)){
            $city = Asset_wh::find($storage->wh_id);
            $col[0] = "<img alt='".$city->name."' src='".$city->address."' width='100px'>&nbsp;&nbsp;".$city->name."";
            $col[1] = '<button type="button" onclick="_open_city('.$city->id.')" data-target="#modalCities" data-toggle="modal" class="btn btn-primary btn-sm">Visit Shops</button>';
        } else {
            $col[0] = '';
            $col[1] = "";
        }
        $row[] = $col;

        return json_encode($row);
    }

    function open_city($id){
        $city = Asset_wh::find($id);
        $items = Asset_item::whereRaw("LEFT(item_code, 6) = 'EVTGME'")->get();
        $item = $items->pluck("id");
        $_item = [];
        foreach($items as $i){
            $_item[$i->id] = $i;
        }

        $qty = Asset_qty_wh::where("wh_id", $id)
            ->whereIn("item_id", $item)
            ->get();
        foreach($qty as $item){
            if(isset($_item[$item->item_id])){
                $i = $_item[$item->item_id];
                $item->item_name = $i->name;
                $item->picture = $i->picture;
            }
        }

        $shops = [];
        if(!empty(Auth::user()->shops)){
            $shops = json_decode(Auth::user()->shops, true);
        }


        return view("zpointer._open_city", compact("city", "qty", "shops"));
    }

    function visit_city(Request $request){

        $qty_wh = Asset_qty_wh::find($request->id_qty_wh);

        $min = $qty_wh->price_min;
        $amount = floor($min / 1);

        if($request->submit == "energy"){
            $en = \Fnh::instance()->checkEnergy();
        } else {
            $en = \Fnh::instance()->checkBalance($amount);
        }
        if($en !== false){
            $wh = Asset_wh::find($qty_wh->wh_id);
            $plant = Asset_item::find($qty_wh->item_id);
            if($request->submit == "energy"){
                $this->ActivityLog("-1 Energy for Opening price $plant->name at $wh->name", -1, "energy");
            } else {
                $this->ActivityLog("-$amount Nc from your balance for Opening shop price $plant->name at $wh->name", $amount * -1, "sell");
            }
            $shops = [];

            if(!empty(Auth::user()->shops)){
                $shops = json_decode(Auth::user()->shops, true);
            }

            $shops[] = $request->id_qty_wh;

            $user = User::find(Auth::id());
            $user->open_shop_credit = $user->open_shop_credit - 1;
            $user->shops = json_encode($shops);
            $user->save();

            return redirect()->back()->with("open_shop", $qty_wh->wh_id);
        } else {
            return redirect()->back()->with("balance", $request->amount);
        }
    }

    function list_plants(){
        $plants = Asset_item::where("company_id", Session::get('company_id'))
            ->whereRaw("LEFT(item_code, 6) = 'EVTGME'")
            ->get();

        $seeds = Asset_item::where("company_id", Session::get('company_id'))
            ->whereRaw("LEFT(item_code, 6) = 'EVTSED'")
            ->get();

        $seed_plant = $seeds->pluck("old_company_id", 'id');

        $seed_own = Item_qty_user::whereIn("item_id", $seeds->pluck("id"))
            ->where("user_id", Auth::id())
            ->whereRaw("qty >= 1")
            ->get();

        $my_seed = [];
        foreach($seed_own as $see){
            if(isset($seed_plant[$see->item_id])){
                $my_seed[$seed_plant[$see->item_id]][] = $see->qty;
            }
        }

        return view("zpointer._list_plants", compact("plants", 'my_seed'));
    }

    function select_plants(Request $request){
        $plant = "false";
        if($request->item_id != 1){
            $seeds = Asset_item::where("old_company_id", $request->item_id)->first();
            $my_seed = Item_qty_user::where("item_id", $seeds->id)
                ->where('user_id', Auth::id())
                ->first();
            $my_seed->qty -= 1;
            $my_seed->save();
            $plant = true;
        } else {
            $plant = true;
        }
        if($plant){
            $plant = Asset_item::find($request->item_id);
            $user = User::find(Auth::id());
            $user->item_ripe_id = $plant->id;
            $user->item_ripe_duration = $plant->minimal_stock;
            $user->item_ripe_start = date("Y-m-d H:i:s");
            $user->save();

            return redirect()->back()->with("farm", $user->item_ripe_duration);
        } else {
            return redirect()->back()->with("energy", "insufficient");
        }
    }

    function countdown_plants(){
        $user = Auth::user();
        if(!empty($user->item_ripe_id)){
            $date_start = $user->item_ripe_start;
            $item = Asset_item::find($user->item_ripe_id)->picture;
            $item_thumb = "t_".$item;
            $duration = $user->item_ripe_duration;
            $date_end = date("Y-m-d H:i:s", strtotime($date_start." +$duration minutes"));

            $data = [
                'start' => $date_start,
                'img' => str_replace("public", "public_html", asset("media/asset/".$item)),
                'img_thumb' => str_replace("public", "public_html", asset("media/asset/".$item_thumb)),
                'end' => $date_end,
                'now' => date("Y-m-d H:i:s")
            ];

            $response = [
                "success" => 1,
                "data" => $data
            ];
        } else {
            $response = [
                "success" => 0,
                "data" => []
            ];
        }

        return json_encode($response);
    }

    function farm_plant(){
        if(!empty(Auth::user()->item_ripe_id)){
            $ripe_start = Auth::user()->item_ripe_start;
            $ripe_duration = Auth::user()->item_ripe_duration;
            $ripe_end = date("Y-m-d H:i:s", strtotime($ripe_start." +$ripe_duration minutes"));
            $now = date("Y-m-d H:i:s");

            if($now >= $ripe_end){
                $item_user = Item_qty_user::where("user_id", Auth::id())
                    ->where("item_id", Auth::user()->item_ripe_id)
                    ->first();
                if(!empty($item_user)){
                    $item_user->qty = $item_user->qty + 1;
                } else {
                    $item_user = new Item_qty_user();
                    $item_user->item_id = Auth::user()->item_ripe_id;
                    $item_user->user_id = Auth::id();
                    $item_user->qty = 1;
                }

                $plant = Asset_item::find(Auth::user()->item_ripe_id);

                if($item_user->save()){
                    $user = User::find(Auth::id());
                    $user->item_ripe_id = null;
                    $user->item_ripe_duration = null;
                    $user->item_ripe_start = null;
                    $user->save();
                }

                return redirect()->back()->with("riped", $plant->id);
            } else {
                return redirect()->back()->with("error", "This plant cannot be riped yet!");
            }
        } else {
            return redirect()->back()->with("error", "This plant already RIPED!");
        }
    }

    function farmed_plant($id){
        $plant = Asset_item::find($id);

        return view("zpointer._farm_success", compact("plant"));
    }

    function table_seeds(){
        $items = Asset_item::whereRaw("LEFT(item_code, 6) = 'EVTSED'")->get();
        $item = $items->pluck("id");
        $user_plant = Item_qty_user::where("user_id", Auth::id())
            ->whereIn('item_id', $item)
            ->get();
        $row = [];

        $currentLocation = Storage_user::where("user_id", Auth::id())->first();
        $currentCity = [];
        if(!empty($currentLocation)){
            $currentCity = Asset_wh::find($currentLocation->wh_id);
        }

        foreach($user_plant as $item){
            $plant = Asset_item::find($item->item_id);
            $item->plant = ucwords(strtolower($plant->name));
            $col['plant'] = ucwords(strtolower($plant->name));
            $col['qty']  = $item->qty." &nbsp; <img width='20px' src='".asset("images/".$plant->picture)."' />";
            if($item->qty > 0){
                $row[] = $col;
            }
        }

        return json_encode($row);
    }

    function table_plants(){
        $items = Asset_item::whereRaw("LEFT(item_code, 6) = 'EVTGME'")->get();
        $item = $items->pluck("id");
        $user_plant = Item_qty_user::where("user_id", Auth::id())
            ->whereIn('item_id', $item)
            ->get();
        $row = [];

        $currentLocation = Storage_user::where("user_id", Auth::id())->first();
        $currentCity = [];
        if(!empty($currentLocation)){
            $currentCity = Asset_wh::find($currentLocation->wh_id);
        }

        foreach($user_plant as $item){
            $plant = Asset_item::find($item->item_id);
            $item->plant = ucwords(strtolower($plant->name));
            $col['plant'] = ucwords(strtolower($plant->name));
            $col['qty']  = $item->qty." &nbsp; <img width='20px' src='../public_html/media/asset/".$plant->picture."' />";
            // $col['picture']  = $plant->picture;
            if($currentCity->id == Auth::user()->home_id){
                $col['sell'] = "must be sold in another city";
            } else {
                $col['sell'] = "<button type='button' class='btn btn-warning btn-sm' onclick='_sell_plant(this)' data-id='$item->id' data-plant='$item->plant'>Sell !</button>";
            }
            if($item->qty > 0){
                $row[] = $col;
            }
        }

        return json_encode($row);
    }

    function sell_form_plant($id){

        $item_user = Item_qty_user::find($id);

        $max = $item_user->qty;

        $cities = Storage_user::where('user_id', Auth::id())->first();

        $ppstock = Asset_qty_wh::where('item_id', $item_user->item_id)
            ->where("wh_id", $cities->wh_id)
            ->first();
        $city = Asset_wh::find($cities->wh_id);
        $plant = Asset_item::find($item_user->item_id);
        $shops = [];
        if(!empty(Auth::user()->shops)){
            $shops = json_decode(Auth::user()->shops, true);
        }

        return view("zpointer._sell_form", compact("city", 'ppstock', 'plant', 'max', 'shops'));
    }

    function sell_confirmation(Request $request){
        $qty = $request->qty;
        $item_user = Item_qty_user::where("user_id", Auth::id())
            ->where("item_id", $request->plant_id)
            ->first();
        $plant = [];
        $city = [];
        $price_city = [];
        $balance = (empty(Auth::user()->do_code)) ? 0 : Auth::user()->do_code;
        $daily_quota = 0;
        $quota = 1;
        if($item_user->qty > 0){
            $confirmation = 1;
            $plant = Asset_item::find($request->plant_id);
            $city = Asset_wh::find($request->city_id);
            $price_city = Asset_qty_wh::where("wh_id", $city->id)
                ->where('item_id', $plant->id)
                ->first();
            $daily_quota = $price_city->quota;

            $user = User::find(Auth::id());
            if(!empty($user->access)){
                $access = json_decode($user->access, true);
                if(isset($access[$price_city->id])){
                    if($access[$price_city->id] >= $daily_quota){
                        $quota = 0;
                    } elseif(($qty + $access[$price_city->id]) > $daily_quota){
                        $quota = -1;
                    }
                } else {
                    if($qty > $daily_quota){
                        $quota = -1;
                    }
                }
            } else {
                if($qty > $daily_quota){
                    $quota = -1;
                }
            }
        } else {
            $confirmation = 0;
        }

        return view("zpointer.confirmation", compact("confirmation", 'quota', 'plant', 'city', 'price_city', 'item_user', 'balance', 'qty'));
    }

    function sell_plant(Request $request){
        $qty = $request->qty;
        $item_user = Item_qty_user::where("user_id", Auth::id())
            ->where("item_id", $request->plant_id)
            ->first();
        $qty_wh = Asset_qty_wh::find($request->pcity);
        $plant = Asset_item::find($request->plant_id);
        $wh = Asset_wh::find($qty_wh->wh_id);
        if($item_user->qty > 0){
            if($item_user->qty >= $qty){
                $amount = $qty * $qty_wh->qty;
                $description = "Sells $qty of $plant->name in $wh->name for $qty_wh->qty Nc/piece";
                $this->ActivityLog($description, $amount, "sell");
                $item_user->qty = $item_user->qty - $qty;
                $sellHistory = new Item_sell_history();
                $sellHistory->item_id = $plant->id;
                $sellHistory->wh_id = $request->city_id;
                $sellHistory->user_id = Auth::id();
                $sellHistory->qty = $qty;
                $sellHistory->save();
                $item_user->save();

                $user = User::find(Auth::id());
                $balance = $user->do_code + $amount;
                $access = [];
                if(!empty($user->access)){
                    $access = json_decode($user->access, true);
                }
                $limit = 0;
                if(isset($access[$qty_wh->id])){
                    $limit = $access[$qty_wh->id];
                }

                $limit += $qty;
                $access[$qty_wh->id] = $limit;
                $user->access = json_encode($access);
                $user->do_code = $balance;
                $user->save();

                // $this->ActivityLog()

                return redirect()->route("home")->with("sell", "$qty $plant->name were sold successfully");
            } else {
                return redirect()->route("home")->with("error", "Insufficient Quantity");
            }
        } else {
            return redirect()->route("home")->with("error", "You don't have $plant->name in your bag");
        }
    }

    function move_city(Request $request){
        $amount = 10;
        $bl = \Fnh::instance()->checkBalance($amount);
        if($bl){
            $user = User::find(Auth::id());
            $user->home_id = $request->wh_id;
            $user->save();

            return redirect()->back()->with("home", 'success');
        } else {
            return redirect()->back()->with("balance", $amount);
        }
    }

    function home_city(){
        $storage = Storage_user::where("user_id", Auth::id())->first();
        $storage->wh_id = Auth::user()->home_id;
        $storage->save();

        return redirect()->back();
    }

    function ActivityLog($description, $amount, $type){
        $log = new Finance_treasury_history();
        $log->id_treasure = Auth::id();
        $log->date_input = date("Y-m-d H:i:s");
        $log->description = $description;
        $log->IDR = $amount;
        $log->PIC = $type;
        $log->save();
    }

    function buy_beer(Request $request){
        $amount = 30;
        $bl = \Fnh::instance()->checkBalance($amount);
        if($bl){
            $for = "me";
            if($request->submit == "me"){
                $user = User::find(Auth::id());
                $user->attend_code = $user->attend_code + 1;
                $user->beer_credit = 0;
                $user->save();

                $description = "Buy a beer for -$30";
            } else {
                $user = User::find(Auth::id());
                $user->beer_credit = 0;
                $user->beer_to = $request->friend;
                $user->save();

                $friend = User::find($request->friend);
                $friend->attend_code = $friend->attend_code + 1;
                $friend->save();
                $for = $friend->name;

                $description = "Buy a beer for -$30 to $friend->name";
            }
            $this->ActivityLog($description, ($amount * -1), "sell");

            return redirect()->back()->with("beer", $for);
        } else {
            return redirect()->back()->with("balance", $amount);
        }
    }

    function share_rumors(){
        $user = User::find(Auth::id());
        $user->share_rumor_credit = 0;
        $user->save();

        return redirect()->back()->with("share_rumor", 1);
    }

    function get_rumors(Request $request){
        $amount = 10;
        $bl = \Fnh::instance()->checkBalance($amount);
        if($bl){
            $plant = Asset_item::whereRaw("LEFT(item_code, 6) = 'EVTGME'")->inRandomOrder()->first();
            $zonk = rand(1,100);

            $rumors = new Asset_qty_rumors();
            $rumors->user_id = Auth::id();
            $rumors->date_heard = date("Y-m-d");

            if($zonk <= 75){
                $hilo = rand(1,100);
                $ishilo = "";
                if($hilo <= 40){
                    $plant_city = Asset_qty_wh::where("item_id", $plant->id)
                        ->orderBy('qty', 'desc')
                        ->first();
                    $ishilo = "sky high";
                } else {
                    $plant_city = Asset_qty_wh::where("item_id", $plant->id)
                        ->orderBy('qty', 'asc')
                        ->first();
                    $ishilo = "kinda bad";
                }

                $wh = Asset_wh::find($plant_city->wh_id);
                $description = "I hear that the price of $plant->name's in $wh->name right now is $ishilo";

                $rumors->plant_id = $plant->id;
                $rumors->wh_id = $wh->id;
                $rumors->description = $description;

            } else {
                $description = "I'm so sorry, I have nothing to share yet.. Thanks for the Nc though";
                $rumors->plant_id = 0;
                $rumors->wh_id = 0;
                $rumors->description = $description;
            }

            $rumors->save();

            $user = User::find(Auth::id());
            $user->rumor_credit = 0;
            $user->save();

            $this->ActivityLog("Buy a rumor : $description", ($amount * -1), "sell");

            return redirect()->back()->with("rumors", 1);
        } else {
            return redirect()->back()->with("balance", $amount);
        }
    }

    function trigger_event(Request $request){
        $event = $request->ev;
        $user = User::find(Auth::id());
        if($event == 1){
            $newDuration = $user->item_ripe_duration - 5;
            $user->event_credit = $user->event_credit - 1;
            $user->current_event = null;
            $user->item_ripe_duration = ($newDuration < 0) ? 0 : $newDuration;
            $user->save();
            return redirect()->back()->with("event", "Successfully removing the bug");
        } elseif($event == 2){
            $en = \Fnh::instance()->checkEnergy();
            if($en !== false){
                $user->event_credit = $user->event_credit - 1;
                $user->item_ripe_duration = 0;
                $user->current_event = null;
                $user->save();
                return redirect()->back()->with("event", "The fairy give you her blessing");
            } else {
                return redirect()->back()->with("energy", "insufficient");
            }
        } elseif($event == 3){
            $amount = 10;
            $bl = \Fnh::instance()->checkBalance($amount);
            if($bl){
                $user->event_credit = $user->event_credit - 1;
                $newDuration = $user->item_ripe_duration - 15;
                $user->item_ripe_duration = ($newDuration < 0) ? 0 : $newDuration;
                $user->current_event = null;
                $user->do_code = $user->do_code - 10;
                $user->save();

                $this->ActivityLog("Used for watering event", ($amount * -1), "sell");
                return redirect()->back()->with("event", "The water nourishes the plant");
            } else {
                return redirect()->back()->with("balance", $amount);
            }
        }
    }

    function hunt(Request $request){
        $en = \Fnh::instance()->checkEnergy();
        if($en !== false){
            $loc_arr = ['plains', 'dungeon', 'forest', 'swamp', 'desert', 'mountain'];
            $loc = array_rand($loc_arr);
            $encounter_monsters = rand(1,3);
            $encounter = [];

            $get_seed = Asset_item::selectRaw("id, name, old_company_id, picture")->whereRaw("LEFT(item_code, 6) = 'EVTSED'")
                ->inRandomOrder()
                ->first();
            $champion = Asset_item::selectRaw("id, name, picture, specification")->where('id',Auth::user()->roster_champion)->first();

            $champ_stats = json_decode($champion->specification,true);

            $currentLocation = Storage_user::where("user_id", Auth::id())->first();
            $currentCity = [];
            if(!empty($currentLocation)){
                $currentCity = Asset_wh::find($currentLocation->wh_id);
            }
            $level_min = $currentCity->longitude;
            $level_max = $currentCity->latitude;
            for($i = 0; $i < $encounter_monsters; $i++){
                $monster_level = rand($level_min,$level_max);
                $dir = 'assets/byr/monsters/';

                $images = glob($dir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

                $randomImage = $images[array_rand($images)];

                $exp = explode("/", $randomImage);
                $file_name = end($exp);
                $fname = explode(".", $file_name);
                $name = explode("_", $fname[0]);

                //calculate result
                //1, success kill
                //2, run
                //3, wounded
                $char_dmg = $champ_stats['str']+$champ_stats['ki'];
                $prob_fail = $monster_level - ($char_dmg / 2);

                if($char_dmg > ($monster_level*2)){
                    $res_success = 1; //success kill
                } else {
                    $res_success_random = rand(0,100);
                    if($res_success_random <= $prob_fail){
                        $res_success = 0; // fail
                    } else {
                        $res_success = 1; //success kill
                    }
                }

                if($res_success == 0){
                    $rand_agi = rand(1,100);
                    if($champ_stats['agi'] > $rand_agi){
                        $res_success = 2; //success run
                    } else {
                        $rand_hp = rand(1,200);
                        if($champ_stats['hp'] > $rand_hp){
                            $res_success = 2; //success run
                        } else {
                            $res_success = 3; //wounded
                        }
                    }
                }
                if($res_success == 0){
                    $res_success = 2;
                }
                $drop_curr = rand(round($monster_level / 2,0),(round($monster_level / 2,0) + 5));

                $row['res'] = $res_success;

                $row['monsters'] = strtoupper(end($name))." [Lv. $monster_level]";
                $row['image'] = $randomImage;
                $row['drop'] = $drop_curr; //rand(1, 20);
                $encounter[$i] = $row;
            }

            // $get_seed = Asset_item::selectRaw("id, name, old_company_id, picture")->whereRaw("LEFT(item_code, 6) = 'EVTSED'")
            //     ->inRandomOrder()
            //     ->first();
            // $champion = Asset_item::selectRaw("id, name, picture")->where('id',Auth::user()->roster_champion)->first();

            $data = [
                'success' => 1,
                'location' => strtoupper($loc_arr[$loc]),
                'encounter' => $encounter,
                'seed' => $get_seed,
                'champion' => $champion,
                'energy' => $en
            ];

            return json_encode($data);
        } else {
            return $data = [
                'success' => 0,
            ];
        }
    }

    function hunt_done(Request $request){
        $seed = Item_qty_user::where("user_id", Auth::id())
            ->where("item_id", $request->seed)->first();
        if(empty($seed)){
            $seed = new Item_qty_user();
        }
        $qty = $seed->qty + 1;
        $seed->item_id = $request->seed;
        $seed->user_id = Auth::id();
        $seed->qty = $qty;
        $seed->save();
        $wounded = 0;

        foreach($request->encounter as $i => $encounter){
            if($encounter == 1){
                $user = User::find(Auth::id());
                $user->do_code += $request->nc[$i];
                $user->save();
                $this->ActivityLog("Obtained from hunting", $request->nc[$i], "sell");

            } elseif($encounter == 2){
                $champion = Asset_item::find(Auth::user()->roster_champion);
                $champion->price2 += 1;
                $champion->save();
                $wounded = 1;

                $user = User::find(Auth::id());
                $user->do_code += $request->nc[$i];
                $user->save();
                $this->ActivityLog("Obtained from hunting", $request->nc[$i], "sell");
            }
        }

        $user = User::find(Auth::id());
        if($wounded != 1){
            $user->last_hunt = date("Y-m-d H:i:s",strtotime("-300 seconds"));
        } else {
            $user->last_hunt = date("Y-m-d H:i:s");
        }
        $user->save();

        return redirect()->back()->with("hunt", 1);
    }

    function user_profile($id){
        $uid = base64_decode($id);
        $user = User::find($uid);
        $town = Asset_wh::find($user->home_id);
        $current = Storage_user::where("user_id", $uid)->first();
        $location = Asset_wh::find($current->wh_id);

        $rosters = Asset_item::where("old_id", $uid)->get();

        return view("zpointer.profile", compact("user", "town", "location", "rosters"));
    }
}
