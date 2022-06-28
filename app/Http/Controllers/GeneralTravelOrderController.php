<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App\Models\Asset_sre;
use App\Models\Hrd_employee;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Helpers\ActivityConfig;
use App\Models\Asset_sre_detail;
use App\Models\Finance_treasury;
use App\Models\Marketing_project;
use App\Models\General_travel_order;
use Illuminate\Support\Facades\Auth;
use App\Models\Pref_work_environment;
use App\Models\Finance_treasury_history;

class GeneralTravelOrderController extends Controller
{
    public function index(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            $comp_childs = ConfigCompany::where('id_parent', Session::get('company_id_parent'))->get();
            foreach($comp_childs as $item){
                array_push($id_companies, $item);
            }
            array_push($id_companies, Session::get('company_id_parent'));
        }
        $to = DB::table('general_to')
            ->select('general_to.*')
            // ->leftJoin('hrd_employee as employee','employee.id','=','general_to.employee_id')
            // ->leftJoin('marketing_projects as prj', 'prj.id','=','general_to.project')
            // ->whereIn('employee.company_id', $id_companies)
            // ->whereIn('general_to.company_id',$id_companies)
            // ->whereIn('prj.company_id',$id_companies)
            // ->where('employee.company_id', Session::get('company_id'))
            ->where('general_to.company_id',Session::get('company_id'))
            // ->where('prj.company_id',Session::get('company_id'))
            // ->whereNull('employee.expel')
            ->whereNull('general_to.deleted_at')
            ->orderBy('id', 'desc')
            ->get();
            // dd($to);
        $emp = Hrd_employee::whereNull('expel')
            ->where('company_id',\Session::get('company_id'))
            ->whereNull('hrd_employee.expel')
            ->get();
        $emp_name = [];
        foreach ($emp as $value) {
            $emp_name[$value->id] = $value->emp_name;
        }

        $comp_tag = ConfigCompany::all()->pluck('tag', 'id');

        $prj_all = Marketing_project::all();
        $data_prj['name'] = $prj_all->pluck('prj_name', 'id');
        $data_prj['comp'] = $prj_all->pluck('company_id', 'id');

        $prj = Marketing_project::whereIn('company_id', $id_companies)
            ->get();
        $prj_name = [];
        foreach ($prj as $value) {
            $prj_name[$value->id] = $value->prj_name;
        }
        return view('to.index', [
            'emp' => $emp,
            'prj' => $prj,
            'to' => $to,
            'emp_name' => $emp_name,
            'prj_name' => $prj_name,
            'data_prj' => $data_prj,
            'comp_tag' => $comp_tag
        ]);
    }

    public function ticketing(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $to = DB::table('general_to')
            ->select('general_to.*')
            ->where('general_to.company_id',Session::get('company_id'))
            ->whereNull('general_to.deleted_at')
            ->orderBy('id', 'desc')
            ->get();
        $emp = Hrd_employee::whereNull('expel')
            ->where('company_id',\Session::get('company_id'))
            ->whereNull('hrd_employee.expel')
            ->get();
        $emp_name = [];
        foreach ($emp as $value) {
            $emp_name[$value->id] = $value->emp_name;
        }
        $prj = Marketing_project::where('company_id', \Session::get('company_id'))
            ->get();
        $prj_name = [];
        foreach ($prj as $value) {
            $prj_name[$value->id] = $value->prj_name;
        }

        $se = Asset_sre::whereIn('company_id', $id_companies)
            ->whereNotNull('se_num')
            ->get();

        $departure_no = [];
        foreach ($se as $key => $value) {
            $departure_no[$value->id] = $value->se_num;
        }
        return view('to.ticketing', [
            'emp' => $emp,
            'prj' => $prj,
            'to' => $to,
            'emp_name' => $emp_name,
            'prj_name' => $prj_name,
            'se' => $departure_no
        ]);
    }

    public function delete($id){
        $to = General_travel_order::find($id);
        if(!empty($to->id_sister)){
            General_travel_order::where('id', $to->id_sister)->delete();
        }
        General_travel_order::where('id',$id)->delete();
        return redirect()->route('to.index');
    }
    public function addFirst(Request $request){
        $emp_detail = Hrd_employee::where('id', $request['emp'])->first();
        $prj_detail = Marketing_project::where('id', $request['project'])->first();
        $we = Pref_work_environment::all();

        return view('to.add_detail',[
            'emp' => $emp_detail,
            'prj' => $prj_detail,
            'type' => $request['type_travel'],
            'we' => $we
        ]);
    }
    public function nextDocNumber($code,$comp_id){
        $cek = General_travel_order::where('doc_num','like','%'.$code.'%')
            ->where('company_id',$comp_id)
            ->where('year', date('y'))
            ->orderBy('num','DESC')
            ->get();

        if (count($cek) > 0){
            $frNum = $cek[0]->doc_num;
            $str = explode('/', $frNum);
            $number = intval($str[0]);
            $number+=1;
            $no = $number;
        } else {
            $no = 1;
        }
        return $no;
    }

    public function store(Request $request){
        ActivityConfig::store_point('to', 'create');
        $arrRomawi	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");

        $emp = Hrd_Employee::find($request->emp_id);

        $id_to_sister = null;

        if(!empty($emp->emp_id_sister)){
            $empSister = Hrd_Employee::find($emp->emp_id_sister);
            if(!empty($empSister)){
                $compSister = ConfigCompany::find($empSister->company_id);
                $to_num = $this->nextDocNumber(strtoupper($compSister->tag)."/TO",$compSister->id);
                $doc_num = sprintf("%03d", $to_num) . '/' . $compSister->tag . '/TO/' . $arrRomawi[date("n")] . '/' . date("y");
                $to = new General_travel_order();
                $to->employee_id =  $empSister->id;
                $to->doc_num = $doc_num;
                $to->num = $to_num;
                $to->year = date('y');
                $to->doc_date = date('Y-m-d');
                $to->destination = $request['destination'];
                $to->dest_type = $request['destination_type'];
                $to->departure_dt = $request['departs_on'];
                $to->return_dt = $request['returns_on'];
                $to->purpose = $request['purpose'];
                $to->location_rate = $request['working_environment_condition'];
                $to->travel_type = $request['type_travel'];
                $to->type_of_travel = $request['type_of_travel'];
                $to->project = $request['project'];
                $to->sppd_type = $request['sppd_type'];
                $to->location = $request['from_airport'];
                $to->tolocation = $request['to_airport'];
                $to->duration = $request['duration'];
                $to->created_by = Auth::user()->username;
                $to->created_at = date('Y-m-d H:i:s');
                $to->status = 3;
                $to->to_cektransport = $request['to_transport'];
                $to->company_id = $compSister->id;

                $to->to_transport = null;

                if ($request['to_transport'] == "1"){
                    $to->to_transport = $request['to_transport_train_val'];
                } elseif ($request['to_transport'] == "2"){
                    $to->to_transport = $request['to_transport_air_val'];
                } elseif ($request['to_transport'] == "3"){
                    $to->to_transport = $request['to_transport_bus_val'];
                } elseif ($request['to_transport'] == "4"){
                    $to->to_transport = $request['to_transport_cil_val'];
                }
                if (isset($request['to_spending'])){
                    $to->to_cekspending = 1;
                    $to->to_spending = $request['to_spending_val'];
                }

                if (isset($request['to_overnight'])){
                    $to->to_cekovernight = 1;
                    $to->to_overnight = $request['to_overnight_val'];
                }
                if (isset($request['to_meal'])){
                    $to->to_cekmeal = 1;
                    $to->to_meal = $request['to_meal_val'];
                }
                if (isset($request['travel_boat'])){
                    $to->transport = $request['travel_boat_val'];
                }
                if (isset($request['taxi'])){
                    $to->taxi = $request['taxi_val'];
                }
                if (isset($request['rent'])){
                    $to->rent = $request['rent_val'];
                }
                if (isset($request['airtax'])){
                    $to->airtax = $request['airtax_val'];
                }
                $to->save();
                $id_to_sister = $to->id;
            }
        }

        $to_num = $this->nextDocNumber(strtoupper(\Session::get('company_tag'))."/TO",Session::get('company_id'));
        $tag = strtoupper(\Session::get('company_tag'));
        $doc_num = sprintf("%03d", $to_num) . '/' . $tag . '/TO/' . $arrRomawi[date("n")] . '/' . date("y");

        $to = new General_travel_order();
        $to->employee_id =  $request['emp_id'];
        $to->doc_num = $doc_num;
        $to->num = $to_num;
        $to->id_sister = $id_to_sister;
        $to->year = date('y');
        $to->doc_date = date('Y-m-d');
        $to->destination = $request['destination'];
        $to->dest_type = $request['destination_type'];
        $to->departure_dt = $request['departs_on'];
        $to->return_dt = $request['returns_on'];
        $to->purpose = $request['purpose'];
        $to->location_rate = $request['working_environment_condition'];
        $to->travel_type = $request['type_travel'];
        $to->type_of_travel = $request['type_of_travel'];
        $to->project = $request['project'];
        $to->sppd_type = $request['sppd_type'];
        $to->location = $request['from_airport'];
        $to->tolocation = $request['to_airport'];
        $to->duration = $request['duration'];
        $to->created_by = Auth::user()->username;
        $to->created_at = date('Y-m-d H:i:s');
        $to->status = 3;
        $to->to_cektransport = $request['to_transport'];
        $to->company_id = \Session::get('company_id');

        $to->to_transport = null;

        if ($request['to_transport'] == "1"){
            $to->to_transport = $request['to_transport_train_val'];
        } elseif ($request['to_transport'] == "2"){
            $to->to_transport = $request['to_transport_air_val'];
        } elseif ($request['to_transport'] == "3"){
            $to->to_transport = $request['to_transport_bus_val'];
        } elseif ($request['to_transport'] == "4"){
            $to->to_transport = $request['to_transport_cil_val'];
        }
        if (isset($request['to_spending'])){
            $to->to_cekspending = 1;
            $to->to_spending = $request['to_spending_val'];
        }

        if (isset($request['to_overnight'])){
            $to->to_cekovernight = 1;
            $to->to_overnight = $request['to_overnight_val'];
        }
        if (isset($request['to_meal'])){
            $to->to_cekmeal = 1;
            $to->to_meal = $request['to_meal_val'];
        }
        if (isset($request['travel_boat'])){
            $to->transport = $request['travel_boat_val'];
        }
        if (isset($request['taxi'])){
            $to->taxi = $request['taxi_val'];
        }
        if (isset($request['rent'])){
            $to->rent = $request['rent_val'];
        }
        if (isset($request['airtax'])){
            $to->airtax = $request['airtax_val'];
        }
        $to->save();

        General_travel_order::where('id', $id_to_sister)
            ->update([
                "id_sister" => $to->id
            ]);

        return redirect()->route('to.index');
    }

    public function edit($id){
        $to_detail = General_travel_order::where('id',$id)->first();
        $prj_detail = Marketing_project::where('id', $to_detail->project)->first();
        $emp_detail = Hrd_employee::where('id',$to_detail->employee_id)->first();

        return view('to.edit_detail',[
            'emp' => $emp_detail,
            'prj' => $prj_detail,
            'to' => $to_detail,
            'type' => $to_detail->type_of_travel
        ]);
    }

    public function update(Request $request){

        $to = General_travel_order::find($request->id_to);
        $whereRaw = " id = $to->id";
        if(!empty($to->id_sister)){
            $whereRaw = " id = $to->id or id = $to->id_sister";
        }

        General_travel_order::whereRaw($whereRaw)
            ->update([

            ]);

        $to_update = General_travel_order::whereRaw($whereRaw)->first();
        $to_update['departure_dt'] = $request['departs_on'];
        $to_update['return_dt'] = $request['returns_on'];
        $to_update['duration'] = $request['duration'];
        $to_update['location'] = $request['from_airport'];
        $to_update['tolocation'] = $request['to_airport'];
        $to_update['destination'] = $request['destination'];
        $to_update['dest_type'] = $request['destination_type'];
        $to_update['travel_type'] = $request['type_travel'];
        $to_update['location_rate'] = $request['working_environment_condition'];
        $to_update['purpose'] = $request['purpose'];
        $to_update['updated_by'] = Auth::user()->username;
        $to_update->save();

        if ($request['to_transport'] == "1"){
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'to_transport' => $request['to_transport_train_val'],
                    'to_cektransport' => 1
                ]);
        } elseif ($request['to_transport'] == "2"){
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'to_transport' => $request['to_transport_air_val'],
                    'to_cektransport' => 2
                ]);
        } elseif ($request['to_transport'] == "3"){
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'to_transport' => $request['to_transport_bus_val'],
                    'to_cektransport' => 3
                ]);
        } elseif ($request['to_transport'] == "4"){
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'to_transport' => $request['to_transport_cil_val'],
                    'to_cektransport' => 4
                ]);
        } else {
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'to_transport' => 0,
                    'to_cektransport' => null
                ]);
        }

        if (isset($request['to_spending'])){
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'to_cekspending' => 1,
                    'to_spending' => $request['to_spending_val']
                ]);
        } else {
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'to_cekspending' => 0,
                    'to_spending' => 0
                ]);
        }

        if (isset($request['to_overnight'])){
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'to_cekovernight' => 1,
                    'to_overnight' => $request['to_overnight_val']
                ]);
        } else {
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'to_cekovernight' => 0,
                    'to_overnight' => 0
                ]);
        }

        if (isset($request['to_meal'])){
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'to_cekmeal' => 1,
                    'to_meal' => $request['to_meal_val']
                ]);
        } else {
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'to_cekmeal' => 0,
                    'to_meal' => 0
                ]);
        }
        if (isset($request['travel_boat'])){
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'transport' => $request['travel_boat_val']
                ]);
        } else {
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'transport' => 0
                ]);
        }
        if (isset($request['taxi'])){
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'taxi' => $request['taxi_val']
                ]);
        } else {
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'taxi' => 0
                ]);
        }
        if (isset($request['rent'])){
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'rent' => $request['rent_val']
                ]);
        } else {
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'rent' => 0
                ]);
        }
        if (isset($request['airtax'])){
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'airtax' => $request['airtax_val']
                ]);
        } else {
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'airtax' => 0
                ]);
        }

        return redirect()->route('to.index');
    }

    public function getFTdetail($id){
        $to_detail = General_travel_order::where('id',$id)->first();
        $prj_detail = Marketing_project::where('id', $to_detail->project)->first();
        $emp_detail = Hrd_employee::where('id',$to_detail->employee_id)->first();

        return view('to.ft_detail',[
            'emp' => $emp_detail,
            'prj' => $prj_detail,
            'to' => $to_detail,
        ]);
    }

    public function getTimeSheetAppr($id,$code){
        $to_detail = General_travel_order::where('id',$id)->first();
        $prj_detail = Marketing_project::where('id', $to_detail->project)->first();
        $emp_detail = Hrd_employee::where('id',$to_detail->employee_id)->first();
        $source = Finance_treasury::where('company_id', Session::get('company_id'))->get();

        return view('to.time_sheet_appr',[
            'emp' => $emp_detail,
            'prj' => $prj_detail,
            'to' => $to_detail,
            'code' => $code,
            'source' => $source
        ]);
    }
    public function doTSAppr(Request $request){
        $to = General_travel_order::find($request->id_to);
        $whereRaw = " id = $to->id";
        if(!empty($to->id_sister)){
            $whereRaw = " id = $to->id or id = $to->id_sister";
        }
        General_travel_order::whereRaw($whereRaw)
            ->update([
                'action' => $request['action'],
                'action_by' => Auth::user()->username,
                'action_time' => date('Y-m-d H:i:s'),
                'action_notes' => $request['action_notes'],
                'status' => 0
            ]);

        return redirect()->route('to.index');
    }

    public function doCheckAppr(Request $request){
        $rt_date = strtotime($request['returns']);
        $dp_date = strtotime( $request['departs']);
        $date_diff = $rt_date - $dp_date;
        $duration = round($date_diff / (60 * 60 * 24));

        $to = General_travel_order::find($request->id_to);
        $whereRaw = " id = $to->id";
        if(!empty($to->id_sister)){
            $whereRaw = " id = $to->id or id = $to->id_sister";
        }

        if ($request->type_check == "check") {
            General_travel_order::whereRaw($whereRaw)
            ->update([
                'departure_dt' => $request['departs'],
                'return_dt' => $request['returns'],
                'admin_time' => date("Y-m-d H:i:s"),
                'admin' => Auth::user()->username,
                'recheck_notes' => $request->c_notes,
                'duration' => $duration,
            ]);
        } else {
            General_travel_order::whereRaw($whereRaw)
            ->update([
                'departure_dt' => $request['departs'],
                'return_dt' => $request['returns'],
                'status' => 3,
                'admin_time' => date("Y-m-d H:i:s"),
                'admin' => Auth::user()->username,
                'action' => null,
                'action_by' => null,
                'action_time' => null,
                'action_notes' => null,
                'recheck_notes' => $request->c_notes,
                'duration' => $duration,
            ]);
        }
        if (isset($request['spending_half'])){
            General_travel_order::whereRaw($whereRaw)
                ->update([
                    'to_cekspending' => '1',
                    'to_spending' => $request['to_spending'],
                ]);
        }
        return redirect()->route('to.index');
    }

    public function doPayAppr(Request $request){
        ActivityConfig::store_point('reimburse', 'approve');
        $to = General_travel_order::find($request->id);
        $whereRaw = " id = $to->id";
        if(!empty($to->id_sister)){
            $whereRaw = " id = $to->id or id = $to->id_sister";
        }
        General_travel_order::whereRaw($whereRaw)
            ->update([
                'paid_by'=> Auth::user()->username,
                'paid_time' => date("Y-m-d H:i:s"),
                'last_payment' => $request['sum'],
            ]);
        $to = General_travel_order::find($request['id']);
        $text = "FT: ".$to->doc_num." (".$to->id.")";
        $his = new Finance_treasury_history();
        $his->id_treasure = $request->bank_sel;
        $his->description = $text;
        $his->project = $to->project;
        $his->date_input  = date('Y-m-d');
        $his->IDR         = $request->sum * -1;
        $his->pic         = Auth::user()->username;
        $his->company_id  = $to->company_id;
        $his->save();

        return redirect()->route('to.index');
    }

    function print_to($type, $id){
        $sql  = array('employee_id', 'emp_name', 'emp_id', 'emp_position', 'doc_num', 'doc_date', 'destination', 'dest_type', 'action', 'travel_type', 'departure_dt', 'return_dt', 'purpose', 'general_to.create_by', 'general_to.created_at','to_transport', 'to_meal', 'to_overnight', 'to_spending', 'sppd_type', 'to_cektransport', 'general_to.transport', 'general_to.taxi', 'general_to.rent', 'airtax', 'general_to.company_id');
        $to = General_travel_order::where('general_to.id', $id)
            ->select($sql)
            ->leftJoin('hrd_employee', 'general_to.employee_id', 'hrd_employee.id')
            ->first();
        $company = ConfigCompany::find($to->company_id);

        if ($to['dest_type'] == 'wh')
        {
            $t = round(((strtotime($to['return_dt']) - strtotime($to['departure_dt']))/86400) + 1);
        }
        else
        {
            $t = round((strtotime($to['return_dt']) - strtotime($to['departure_dt']))/86400);
        }

        $sppd_type = $to['sppd_type'];
        if($sppd_type == 'dom')
        {
            $data['mata_uang'] = 'Rp ';
        }
        else
        {
            $data['mata_uang'] = '$ ';
        }

        $data['subcost_meal'] = ((!empty($to['to_meal'])) ? $to['to_meal'] : 0) * $t;
        $data['subcost_transport'] = ((!empty($to['to_transport'])) ? $to['to_transport'] : 0);
        $data['subcost_overnight'] = ((!empty($to['to_overnight'])) ? $to['to_overnight'] : 0) * $t;
        $data['subcost_spending'] = ((!empty($to['to_spending'])) ? $to['to_spending'] : 0) * $t;
        $data['timetravel'] = (intval($to['transport']) + intval($to['taxi']) + intval($to['rent']) + intval($to['airtax']));

        if ($type == "to") {
            $view = "to.print_to";
        } else {
            $view = "to.print_sppd";
        }

        return view($view, [
            "to" => $to,
            "company" => $company,
            "data" => $data
        ]);
    }

    function ticket_se($type, Request $request){
        $to = General_travel_order::find($request->id_to);
        $emp = Hrd_employee::find($to->employee_id);
        $company = ConfigCompany::find($to->company_id);
        $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");


        $dir = ($type == "departure") ? "[D]" : "[R]";

        switch ($request->dt_time ) {
            case '1':
                $dt_time = "PAGI";
                break;
            case '2':
                $dt_time = "SIANG";
                break;
            case '3':
                $dt_time = "MALAM";
                break;
            default:
                $dt_time = "PAGI";
                break;
        }

        if ($type == "departure") {
            $desc1 = "TICKET PESAWAT TUJUAN ".$to->location." - ".$to->tolocation;
            $dt = date("d-m-Y", strtotime($to->departure_dt));
            $id_se = $to->departure_no;
        } else {
            $desc1 = "TICKET PESAWAT TUJUAN ".$to->tolocation." - ".$to->location;
            $dt = date("d-m-Y", strtotime($to->return_dt));
            $id_se = $to->return_no;
        }

        $description = "$dir $desc1 $dt ($dt_time) A/N : ".$emp->emp_name;

        // CREATE SO-SE
        $so_num = Asset_sre::selectRaw("*, CAST(SUBSTRING(so_num, 1, LOCATE('/', so_num) - 1) as UNSIGNED) as so_last")
            ->where('so_date', 'like', ''.date('Y')."-%")
            ->where('company_id', Session::get('company_id'))
            ->orderBy('so_last', 'desc')
            ->first();

        if(!empty($so_num)){
            $_num = explode("/", $so_num->so_num);
            $num = intval($_num[0]) + 1;
        } else {
            $num = 1;
        }

        $bln = date("n");
        $new_so_num = sprintf("%03d", $num)."/".$company->tag."/SO/".$array_bln[$bln]."/".date("Y");
        $new_rfq_so_num = sprintf("%03d", $num)."/".$company->tag."/RFQSO/".$array_bln[$bln]."/".date("Y");
        $new_se_num = sprintf("%03d", $num)."/".$company->tag."/SE/".$array_bln[$bln]."/".date("Y");

        if($request->post_type == "delete"){
            $so = Asset_sre::find($id_se);
            if(!empty($so)){
                $so->delete();

                if($type == "departure"){
                    $to->departure_no = null;
                    $to->departure_time = null;
                } else {
                    $to->return_no = null;
                    $to->return_time = null;
                }
            }
        } else {
            $so = new Asset_sre();
            $now = date("Y-m-d H:i:s");
            $so->so_type = "ACCOMMODATION";
            $so->so_num = $new_so_num;
            $so->rfq_so_num = $new_rfq_so_num;
            $so->se_num = $new_se_num;
            $so->division = "HRD";
            $so->project = $to->project;
            $so->so_date = $now;
            $so->rfq_so_date = $now;
            $so->so_approved_at = $now;
            $so->so_approved_by = "wto";
            $so->rfq_approved_at = $now;
            $so->rfq_approved_by = "wto";
            $so->created_by = "wto";
            $so->company_id = $to->company_id;
            $so->save();

            $so_detail = new Asset_sre_detail();
            $so_detail->so_id = $so->id;
            $so_detail->job_desc = $description;
            $so_detail->qty = 1;
            $so_detail->qty_appr = 1;
            $so_detail->save();

            if($type == "departure"){
                $to->departure_no = $so->id;
                $to->departure_time = $request->dt_time;
            } else {
                $to->return_no = $so->id;
                $to->return_time = $request->dt_time;
            }
        }

        $to->save();

        return redirect()->back();
    }
}
