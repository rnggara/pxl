<?php

namespace App\Http\Controllers;

use Session;
use Mpdf\Mpdf;
use App\Models\Asset_wh;
use App\Models\Division;
use App\Models\Asset_item;
use App\Models\General_do;
use App\Models\Hrd_employee;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\Preference_ppe;
use App\Models\Hrd_employee_ppe;
use App\Models\General_do_detail;
use App\Models\Hrd_employee_type;
use App\Models\Preference_config;
use App\Models\Hrd_contract_fields;
use Illuminate\Support\Facades\Auth;
use App\Models\Hrd_contract_employee;
use App\Models\Hrd_contract_template;
use Illuminate\Support\Facades\Schema;


class HrdContract extends Controller
{
    function index(Request $request){
        $id_companies_type = [];
        $comp = ConfigCompany::find(Session::get('company_id'));
        if (empty($comp->id_parent)) {
            $childCompany = ConfigCompany::select("id")
                ->where('id_parent', $comp->id)
                ->get();
            foreach($childCompany as $ids){
                $id_companies_type[] = $ids->id;
            }
        } else {
            $id_companies_type[] = $comp->id_parent;
        }

        $id_companies_type[] = Session::get('company_id');

        $emptypes = Hrd_employee_type::whereIn('company_id', $id_companies_type)
            ->where('company_exclude', 'not like', '%"'.$comp->id.'"%')
            ->orWhereNull("company_exclude")
            ->get()
            ->pluck('name', 'id');

        $sl = [];
        $sl["text"] = "Text";
        $sl["int"] = "Number";
        $sl["currency"] = "Currency";
        $sl["time"] = "Time";
        $sl["date"] = "Date";
        $sl["position"] = "Position";

        if($request->ajax()){
            if($request->type == "add"){
                $fields = new Hrd_contract_fields();
                $fields->name = $request->f_name;
                $fields->type_data = $request->f_type;
                $fields->data_length = $request->f_length;
                $fields->created_by = Auth::user()->username;
                $fields->field_emp = $request->emp_field;
                $fields->description = $request->desc;
                $fields->company_id = Session::get('company_id');

                if($fields->save()){
                    return json_encode(array(
                        "success" => true
                    ));
                } else {
                    return json_encode(array(
                        "success" => false
                    ));
                }
            }

            if($request->type == "table"){
                $fields = Hrd_contract_fields::all();
                $row = [];
                foreach($fields as $i => $item){
                    $col = [];
                    $col[] = $i+1;
                    $col[] = $item->name;
                    $col[] = $sl[$item->type_data];
                    $col[] = $item->data_length;
                    $btn = "<button type='button' onclick='_delete($item->id)' class='btn btn-xs btn-icon btn-danger btn-delete' data-id='$item->id'><i class='fa fa-trash'></i></button>";
                    $col[] = $btn;
                    $row[] = $col;
                }

                $data = [
                    "data" => $row
                ];

                return json_encode($data);
            }

            if($request->type == "delete"){
                $fields = Hrd_contract_fields::find($request->id);
                if($fields->delete()){
                    return json_encode(array(
                        "success" => true
                    ));
                } else {
                    return json_encode(array(
                        "success" => false
                    ));
                }
            }

            if($request->type == "modal"){
                $emp_sel = Hrd_employee::find($request->id);
                // $tp = Hrd_contract_template::find($request->id);
                $tp = Hrd_contract_template::where('targets', $emp_sel->emp_type)
                    ->get();
                if(count($tp) == 0){
                    $tp = Hrd_contract_template::whereNull('targets')
                        ->get();
                }

                $emp = Hrd_employee::whereNull('expel')
                    ->where('company_id', Session::get('company_id'))
                    ->orderBy('emp_name')
                    ->get();

                return view("hrd.contract.modalFlds", compact('emp','tp'));
            }

            if($request->type == "modal-content"){
                $emp = Hrd_employee::find($request->id);
                // $tp = Hrd_contract_template::find($request->id);
                $tp = Hrd_contract_template::find($request->id_tp);

                $content = explode("((", $tp->content);
                $tag = [];
                for ($i=0; $i < count($content); $i++) {
                    $end = explode("))", $content[$i]);
                    if(isset($end[1])){
                        $tag[] = $end[0];
                    }
                }
                // $emp = Hrd_employee::whereNull('expel')
                //     ->where('company_id', Session::get('company_id'))
                //     ->orderBy('emp_name')
                //     ->get();
                // dd($tag);
                $flds = Hrd_contract_fields::where(function($query) use($tag){
                        for ($i=0; $i < count($tag); $i++) {
                            $_name = str_replace("_", " ", $tag[$i]);
                            $query->orWhere('name', $_name);
                        }
                    })
                    ->orderBy('name')->get();

                $id_companies_type = [];
                $comp = ConfigCompany::find(Session::get('company_id'));
                if (empty($comp->id_parent)) {
                    $childCompany = ConfigCompany::select("id")
                        ->where('id_parent', $comp->id)
                        ->get();
                    foreach($childCompany as $ids){
                        $id_companies_type[] = $ids->id;
                    }
                } else {
                    $id_companies_type[] = $comp->id_parent;
                }

                $id_companies_type[] = Session::get('company_id');

                $emptypes = Hrd_employee_type::whereIn('company_id', $id_companies_type)
                    ->where('company_exclude', 'not like', '%"'.$comp->id.'"%')
                    ->orWhereNull("company_exclude")
                    ->get();
                $etype = Hrd_employee_type::find($emp->emp_type);

                $div = Division::whereNotIn('name', ["admin", "President"])->get();
                return view("hrd.contract.modalContent", compact('emp', 'etype', 'flds', 'emptypes', 'div', 'tp'));
            }

            if($request->type == "emp"){
                $emp = Hrd_employee::find($request->id);
                return json_encode($emp);
            }
        }

        $templates = Hrd_contract_template::all();

        return view('hrd.contract.index', compact('templates', 'emptypes'));
    }

    function add_template($id=null){
        $id_companies_type = [];
        $comp = ConfigCompany::find(Session::get('company_id'));
        if (empty($comp->id_parent)) {
            $childCompany = ConfigCompany::select("id")
                ->where('id_parent', $comp->id)
                ->get();
            foreach($childCompany as $ids){
                $id_companies_type[] = $ids->id;
            }
        } else {
            $id_companies_type[] = $comp->id_parent;
        }

        $id_companies_type[] = Session::get('company_id');

        $emptypes = Hrd_employee_type::whereIn('company_id', $id_companies_type)
            ->where('company_exclude', 'not like', '%"'.$comp->id.'"%')
            ->orWhereNull("company_exclude")
            ->get();

        $tp = [];
        if(!empty($id)){
            $tp = Hrd_contract_template::find($id);
        }

        $fields = Hrd_contract_fields::orderBy('name')
            ->get();
        $row = [];
        foreach($fields as $item){
            $col = [];
            $col['text'] = str_replace("\"", "'", $item->description);
            $col['value'] = "((".str_replace(" ", "_", strtolower($item->name))."))";
            $row[] = $col;
        }

        return view("hrd.contract.add_template", compact("emptypes", "tp", "row"));
    }

    function get_fields(){
        $fields = Hrd_contract_fields::orderBy('name')
            ->get();
        $row = [];
        foreach($fields as $item){
            $col = [];
            $col['text'] = str_replace("\"", "'", $item->description);
            $col['value'] = "((".str_replace(" ", "_", strtolower($item->name))."))";
            $row[] = $col;
        }

        return json_encode($row);
    }

    function save_template (Request $request){
        $template = Hrd_contract_template::find($request->id_tp);
        if(empty($template)){
            $template = new Hrd_contract_template();
            $template->created_by = Auth::user()->username;
            $template->company_id = Session::get('company_id');
        } else {
            $template->updated_by = Auth::user()->username;
        }
        $template->name = $request->template_name;
        $template->targets = $request->template_target;
        $template->content = $request->content;
        $template->save();
        return redirect()->route('hrd.contract.index');
    }

    function generate(Request $request){
        $data  = [];
        $data['fld'] = $request->fld;
        $data['tempat_lahir'] = $request->tempat_lahir;
        $data['tanggal_lahir'] = $request->tanggal_lahir;
        $data['jk'] = $request->jk;
        $data['address'] = $request->address;
        $data['nik'] = $request->nik;

        $file_name = "";

        $ct = new Hrd_contract_employee();
        $ct->emp_id = $request->emp_name;
        $ct->template_id = $request->id_template;
        $ct->pihak_pertama = ucwords($request->pihak_pertama);
        $ct->jabatan = $request->jabatan_pihak_pertama;
        $ct->contents = json_encode($data);
        $ct->created_by = Auth::user()->username;
        $ct->company_id = Session::get('company_id');

        $assign_ppe = $request->assign_ppe;

        $emp = Hrd_employee::find($ct->emp_id);
        $etyp = Hrd_employee_type::find($emp->emp_type);
        if(!empty($assign_ppe) && $assign_ppe == 1){
            $link = "";
            $url = 'https://api-ssl.bitly.com/v4/shorten';
            $ch = curl_init($url);
            $post = [];
            $post['domain'] = "bit.ly";
            $post['long_url'] = route('hrd.ppe', $emp->id);

            $_post = json_encode($post);

            $authorization = "Authorization: Bearer ed7f3babde7c15d258ee1501a84360e99bea0c12";

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$_post);
            $result = curl_exec($ch);
            $js = json_decode($result, true);
            $link = $js['link'];
            Hrd_employee_ppe::where("emp_id", $emp->id)
                ->whereNull('ppe_index')
                ->whereNull('do_id')
                ->delete();
            $ppe = new Hrd_employee_ppe();
            $ppe->emp_id = $emp->id;
            $ppe->link = $link;
            $ppe->created_by = Auth::user()->username;
            $ppe->company_id = $emp->company_id;
            $ppe->save();
        }

        if(!empty($request->signature)){
            $folderPath = public_path("media/user/signature/");

            $image_parts = explode(";base64,", $request->signature);

            $image_type_aux = explode("image/", $image_parts[0]);

            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);

            $file_name = uniqid() . '.'.$image_type;

            $file = $folderPath . $file_name;
            $up = file_put_contents($file, $image_base64);
            if($up){
                $ct->hr_signature = $file_name;
            }
        }

        $msg = "";
        if($ct->save()){
            $url = 'https://api-ssl.bitly.com/v4/shorten';
            $ch = curl_init($url);
            $post = [];
            $post['domain'] = "bit.ly";
            $post['long_url'] = route('hrd.contract.view', $ct->id);

            $_post = json_encode($post);

            $authorization = "Authorization: Bearer ed7f3babde7c15d258ee1501a84360e99bea0c12";

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$_post);
            $result = curl_exec($ch);
            $js = json_decode($result, true);
            if(isset($js['link'])){
                $data = [
                    "success" => true,
                    "link" => $js['link']
                ];
                $ct->links = $js['link'];
                $ct->save();
                $msg = $js['link'];
            } else {
                $data = [
                    "success" => false,
                ];
                $msg = "error";
            }
        } else {
            $data = [
                "success" => false,
            ];
            $msg = "error";
        }

        return redirect()->back()->with('link', $msg);


    }

    function tgl_indo($tanggal){
        $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun

        return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
    }

    function view($id){
        $ctid = Hrd_contract_employee::find($id);

        if(empty($ctid)){
            $ctid = Hrd_contract_employee::where('id',$id)->withTrashed()->first();
            return redirect()->route('hrd.contract.landing', ["type" => "not-available", "id" => base64_encode($ctid->company_id)]);
        }

        if(!empty($ctid->approved_at)){
            return redirect()->route('hrd.contract.landing', ["type" => "not-available", "id" => base64_encode($ctid->company_id)]);
        }

        $_flds = Hrd_contract_fields::all();
        $flds = [];
        foreach($_flds as $item){
            $flds[strtolower(str_replace(" ", "_", $item->name))] = $item->type_data;
        }

        $tp = Hrd_contract_template::find($ctid->template_id);
        $content = explode("((", $tp->content);
        $tag = [];
        $ct = "";

        $cont = json_decode($ctid->contents, true);
        for ($i=0; $i < count($content); $i++) {
            $end = explode("))", $content[$i]);

            if(isset($end[1])){
                if($end[0] != "position"){
                    $txt = $cont['fld'][$end[0]];
                    if (isset($flds[$end[0]]) && $flds[$end[0]] == "date") {
                        $ct .= date("d F Y", strtotime($txt));
                    } else {
                        $ct .= $txt;
                    }
                    $ct .= $end[1];
                } else {
                    $end[0] = $cont['fld'][$end[0]];
                    $emptype = Hrd_employee_type::find($end[0]['emp_type']);
                    $empdiv = Division::find($end[0]['emp_div']);
                    $ct .= $emptype->name." ".$empdiv->name;
                    $ct .= $end[1];
                }
            } else {
                $ct .= $content[$i];
            }
        }

        $fld_emp = Hrd_contract_fields::whereNotNull('field_emp')
            ->get();

        $tmpt_lahir = $cont['tempat_lahir'];
        $tgl_lahir = $cont['tanggal_lahir'];
        $jk = $cont['jk'];

        $emp = Hrd_employee::find($ctid->emp_id);
        $emp->position = $emp->emp_position." ".Division::find($emp->division)->name;
        $address = $cont['address'];
        $nik = $cont['nik'];

        $etype = Hrd_employee_type::all()->pluck('name', 'id');
        $ediv = Division::all()->pluck('name', 'id');

        $pkwt = Hrd_contract_employee::selectRaw("*, CAST(SUBSTRING(pkwt_num, 1, LOCATE('/', pkwt_num) - 1) as UNSIGNED) as pkwt_last")
            ->where('approved_at', 'like', ''.date('Y')."-%")
            ->orderBy('pkwt_last', 'desc')
            ->first();

        $arrRomawi	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        if(empty($pkwt)){
            $pkwt_num = "001/PWKT-HRD/".$arrRomawi[date("n")]."/".date("Y");
        } else {
            $pkwt_num = sprintf("%03d", $pkwt->pkwt_last + 1)."/PWKT-HRD/".$arrRomawi[date("n")]."/".date("Y");
        }

        $comp = ConfigCompany::find($ctid->company_id);

        return view("hrd.contract.view", compact("ct", "pkwt_num", "ctid", 'etype', 'ediv', "fld_emp", "comp", "emp", "address", 'nik', 'jk', 'tmpt_lahir', 'tgl_lahir'));
    }

    function approve(Request $request){
        if($request->submit == "appr"){
            if(!empty($request->signature)){
                $folderPath = public_path("media/user/signature/");

                $image_parts = explode(";base64,", $request->signature);

                $image_type_aux = explode("image/", $image_parts[0]);

                $image_type = $image_type_aux[1];

                $image_base64 = base64_decode($image_parts[1]);

                $file_name = uniqid() . '.'.$image_type;

                $file = $folderPath . $file_name;
                $up = file_put_contents($file, $image_base64);
                if($up){
                    $ctid = Hrd_contract_employee::find($request->ctid);
                    $emp = Hrd_employee::find($ctid->emp_id);
                    $content = json_decode($ctid->contents, true);

                    $emp->gender = $content['jk'];
                    $emp->address = $content['address'];
                    $emp->nik = $content['nik'];
                    $emp->emp_lahir = $content['tanggal_lahir'];
                    $emp->emp_tmpt_lahir = $content['tempat_lahir'];
                    $keyfld = [];
                    $fld = Hrd_contract_fields::all();
                    $keytype = [];
                    foreach($fld as $item){
                        $key = str_replace(" ", "_", strtolower($item->name));
                        $keyfld[$key] = $item->field_emp;
                        $keytype[$key] = $item->type_data;
                    }

                    $jsfld = $content['fld'];
                    $emp->contract_file = $ctid->id;
                    foreach($jsfld as $key => $item){
                        if(isset($keyfld[$key])){
                            $fldemp = $keyfld[$key];
                            if($fldemp == "position"){
                                $emp['division'] = $item['emp_div'];
                                $emp['emp_type'] = $item['emp_type'];
                                $etype = Hrd_employee_type::find($item['emp_type']);
                                $ediv = Division::find($item['emp_div']);
                                $emp['emp_position'] = $etype->name." ".$ediv->name;
                            } elseif($fldemp == "salary"){
                                $sal = floatval(str_replace(",", "", $item)) * 0.4;
                                $meal = floatval(str_replace(",", "", $item)) * 0.2;
                                $health = floatval(str_replace(",", "", $item)) * 0.15;
                                $transport = floatval(str_replace(",", "", $item)) * 0.15;
                                $house = floatval(str_replace(",", "", $item)) * 0.1;
                                $emp[$fldemp] = base64_encode($sal);
                                $emp['meal'] = base64_encode($meal);
                                $emp['health'] = base64_encode($health);
                                $emp['transport'] = base64_encode($transport);
                                $emp['house'] = base64_encode($house);
                            } elseif($fldemp == "join_date"){
                                $nik = explode("-", $emp->emp_id);
                                $st = $nik[1][0];
                                $co = ConfigCompany::find($emp->company_id);
                                $date = explode("-",$item);
                                $nik_exist = strtoupper($co->tag)."-".$st.$date[2].$date[1].$date[0];

                                $r_s1 = Hrd_employee::select('emp_id')
                                    ->where('emp_id','like','%'.$nik_exist.'%')
                                    ->whereNull('expel')
                                    ->orderBy('id','DESC')
                                    ->get();

                                $count_emp_id = $r_s1->count();
                                if ($count_emp_id > 0){
                                    $emp_id =$r_s1[0]['emp_id'];
                                    $lastdigit = substr($emp_id, -2);
                                    $nextdigit = intval($lastdigit)+1;
                                    if($nextdigit < 10)
                                    {
                                        $nextdigit = "0".$nextdigit;
                                    }
                                    $NIK = strtoupper($co->tag)."-".$st.$date[2].$date[1].$date[0].$nextdigit;

                                } else {
                                    $NIK = strtoupper($co->tag)."-".$st.$date[2].$date[1].$date[0]."01";
                                }

                                $emp->emp_id = $NIK;
                            } else {
                                if($keytype[$key] == "currency"){
                                    $emp[$fldemp] = str_replace(",", "", $item);
                                } else {
                                    $emp[$fldemp] = $item;
                                }
                            }
                        }
                    }

                    $ctid->emp_signature = $file_name;
                    $ctid->pkwt_num = $request->pkwt;
                    $ctid->approved_at = date("Y-m-d H:i:s");
                    $ctid->approved_by = $emp->emp_name;
                    if($ctid->save()){
                        $emp->save();
                        $ppe = Hrd_employee_ppe::where("emp_id", $emp->id)
                            ->whereNull('ppe_index')
                            ->whereNull('do_id')
                            ->first();
                        if(!empty($ppe)){
                            return redirect()->to($ppe->link);
                        } else {
                            return redirect()->route('hrd.contract.landing', ["type" => "success", "id" => base64_encode($ctid->company_id)]);
                        }
                    }
                }
            }
        } else {
            $ctid = Hrd_contract_employee::find($request->ctid);
            $comp = $ctid->company_id;
            $ctid->delete();
            return redirect()->route('hrd.contract.landing', ["type" => "delete", "id" => base64_encode($comp)]);
        }
    }

    function landing_page($type, $id){
        $comp = ConfigCompany::find(base64_decode($id));
        return view("hrd.contract.landing", compact('type', 'comp'));
    }

    function pdf($id){
        $ctid = Hrd_contract_employee::find(base64_decode($id));

        $tp = Hrd_contract_template::find($ctid->template_id);
        $content = explode("((", $tp->content);
        $tag = [];
        $ct = "";

        $cont = json_decode($ctid->contents, true);
        $ff = [];
        for ($i=0; $i < count($content); $i++) {
            $end = explode("))", $content[$i]);

            if(isset($end[1])){
                $whereName = str_replace("_", " ", $end[0]);
                $fl = Hrd_contract_fields::selectRaw("id, REPLACE(LOWER(name), ' ', '_') as name, type_data, data_length")
                        ->where('name', $whereName)
                        ->first();
                $ff[] = (empty($fl)) ? $end[0] : $fl;
                if($end[0] != "position"){
                    $end[0] = $cont['fld'][$end[0]];
                    $ct .= (!empty($fl) && $fl->type_data == "date") ? date("d F Y", strtotime($end[0])) : $end[0];
                    $ct .= $end[1];
                } else {
                    $end[0] = $cont['fld'][$end[0]];
                    $emptype = Hrd_employee_type::find($end[0]['emp_type']);
                    $empdiv = Division::find($end[0]['emp_div']);
                    $ct .= $emptype->name." ".$empdiv->name;
                    $ct .= $end[1];
                }
            } else {
                $ct .= $content[$i];
            }
        }

        $fld_emp = Hrd_contract_fields::whereNotNull('field_emp')
            ->get();

        $tmpt_lahir = $cont['tempat_lahir'];
        $tgl_lahir = $cont['tanggal_lahir'];
        $jk = $cont['jk'];

        $emp = Hrd_employee::find($ctid->emp_id);
        $emp->position = $emp->emp_position." ".Division::find($emp->division)->name;
        $address = $cont['address'];
        $nik = $cont['nik'];

        $etype = Hrd_employee_type::all()->pluck('name', 'id');
        $ediv = Division::all()->pluck('name', 'id');

        $comp = ConfigCompany::find($ctid->company_id);

        $view =  view("hrd.contract.pdf", compact("ct", "ctid", 'etype', 'ediv', "fld_emp", "comp", "emp", "address", 'nik', 'jk', 'tmpt_lahir', 'tgl_lahir'));

        $mpdf = new Mpdf([
            'tempDir'=>storage_path('tempdir')
        ]);

        $header = view("hrd.contract.pdf_header", compact("comp"));

        // return $header;
        $mpdf->setHTMLHeader($header);

        $mpdf->AddPage('', // L - landscape, P - portrait
        '', '', '', '',
        5, // margin_left
        5, // margin right
       40, // margin top
       30, // margin bottom
        3, // margin header
        0); // margin foot
        $mpdf->writeHtml($view);
        $mpdf->Output();
    }

    function ppe_emp($id){
        $emp = Hrd_employee::find($id);
        $ppe = Hrd_employee_ppe::where("emp_id", $id)->first();
        $do = [];
        $qr = "";
        if(!empty($ppe->do_id)){
            $do = General_do::find($ppe->do_id);
            $uri = route('do.detail',['id' => $ppe->do_id,'type' => 'appr']);
            $qr = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=";
            $qr .= $uri;
        }

        $template = Preference_ppe::all();
        $_items = Asset_item::all()->pluck("name", 'id');

        $comp = ConfigCompany::find($emp->company_id);

        return view("employee.ppe", compact("emp", "ppe", "comp", "template", "_items", "do", "qr"));
    }

    function ppe_do(Request $request){
        $ppe = Hrd_employee_ppe::find($request->id_ppe);
        $emp = Hrd_employee::find($ppe->emp_id);
        $pref = Preference_config::where('id_company', $emp->company_id)->first();
        $comp = ConfigCompany::find($emp->company_id);

        $cek = General_do::selectRaw("*, CAST(SUBSTRING(no_do, 1, LOCATE('/', no_do) - 1) as UNSIGNED) as do_last")
            ->where('deliver_date','like','%'.date('y').'-%')
            ->where('company_id', $pref->id_company)
            ->orderBy('do_last','DESC')
            ->first();

        $no_do = 1;
        if(!empty($cek)){
            $no_do = $cek->do_last + 1;
        }

        $div = Division::where('is_ppe', 1)->first();

        // create new Storage
        $wh = Asset_wh::where("name", "Personel : $emp->emp_name")
            ->where('office', 4)
            ->where('company_id', $emp->company_id)
            ->first();
        if(empty($wh)){
            $wh = new Asset_wh();
            $wh->name = "Personel : $emp->emp_name";
            $wh->address = $emp->address;
            $wh->telephone = $emp->phone;
            $wh->pic = $emp->emp_name;
            $wh->created_at = date('Y-m-d H:i:s');
            $wh->company_id = $emp->company_id;
            $wh->office = 4;
            $wh->emp_id;
            $wh->save();
        }

        $arrRomawi	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $do = new General_do();
        $deliver_time = date("Y-m-d");
        $no_do_id = sprintf('%03d',$no_do).'/'.strtoupper($comp->tag).'/DO/'.$arrRomawi[date("n")].'/'.date("y");
        $do->no_do = $no_do_id;
        $do->company_id = $emp->company_id;
        $do->division = $div->name;
        $do->notes = "PPE for employee : ".$emp->emp_name;
        $do->location = "Lt. 2";
        $do->deliver_by = "Asset";
        $do->departure_by = $emp->emp_name;
        $do->deliver_date = date("Y-m-d");
        $do->from_id = $pref->ppe_wh;
        $do->to_id = $wh->id;

        if($do->save()){
            foreach ($request->item as $key => $itemCode){
                $iPref = Preference_ppe::find($key);
                $item = Asset_item::find($itemCode);
                $do_detail = new General_do_detail();
                $do_detail->do_id = $do->id;
                $do_detail->item_id = $item->item_code;
                $do_detail->qty = $iPref->qty;
                $do_detail->type = "Transfer";
                $do_detail->save();
            }
        }

        $ppe->ppe_index = json_encode($item);
        $ppe->do_id = $do->id;
        $ppe->save();

        return redirect()->back();
    }
}
