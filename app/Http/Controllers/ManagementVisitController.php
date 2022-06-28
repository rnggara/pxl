<?php

namespace App\Http\Controllers;

use App\Helpers\FileManagement;
use App\Models\ConfigCompany;
use App\Models\Qhse_mv_absence;
use App\Models\Qhse_mv_attach;
use App\Models\Qhse_mv_main;
use App\Models\Qhse_mv_mom;
use App\Rms\RolesManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;
use Session;

class ManagementVisitController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index(){
        return view('mv.index');
    }

    public function getMtgAjax(){
        $mtgs = Qhse_mv_main::where('company_id',\Session::get('company_id'))
            ->orderBy('date_main', 'DESC')
            ->get();
        $companytag = ConfigCompany::all();
        $tag = [];
        $row = [];
        $meeting = [];
        foreach ($companytag as $key =>$value){
            $tag[$value->id][] = $value->tag;
        }

        foreach ($mtgs as $key =>$value){
            $meeting['no'] = ($key+1);
            $meeting['meeting'] = date('d M Y | H:i A', strtotime($value->date_main));
            $tg = '';
            foreach ($tag as $key2 => $value2){
                if ($key2 == $value->company_id){
                    $tg = strtoupper($value2[0]);
                }
            }
            $meeting['meeting_num'] = $value->id_main.'/'.$tg.'-MV/'.date('m/y',strtotime($value->date_main));
            $meeting['topic'] = ($value->progress == '' || $value->progress == null) ?"<a class='btn-link' href='".route('mv.detail',['id' => $value->id_main])."'><i class='fa fa-search'></i>&nbsp;&nbsp;" . $value->topic . "</a>": $value->topic;
            $meeting['location'] = $value->location;
            $meeting['created_by'] = $value->created_by;
            $meeting['action'] = ($value->progress == '' || $value->progress == null) ?"<a class='btn btn-success btn-icon btn-xs' href='".route('mv.action.progress',['id' => $value->id_main])."' onclick='return confirm(\"Are you sure?\")'><i class='fa fa-check-square'></i></a>":"<a class='btn btn-icon btn-xs btn-primary' target='_blank' href='".route('mv.printMv', $value->id_main)."'><i class='fa fa-download'></i></a>";
            if (RolesManagement::actionStart('mom','delete')){
                $meeting['guest'] = "<a class='btn btn-danger btn-xs btn-icon' title='Delete' href='".route('mv.delete.main', ['id'=>$value->id_main])."' onclick='return confirm(\"Are you sure you want to delete ?\")'><i class='fa fa-trash'></i></a>";
            } else {
                $meeting['guest'] = "-";
            }

            if (RolesManagement::actionStart('mom','read')){
                $row[] = $meeting;
            } else {
                $row[] = [];
            }

        }
        $data = [
            'data' => $row,
        ];

        return json_encode($data);
    }

    public function getAbsence($id){
        $mtg_absence = Qhse_mv_absence::where('id_main', $id)->get();
        $row = [];
        $absence = [];

        foreach ($mtg_absence as $key => $value){
            $absence['no'] = ($key+1);
            $absence['name'] = $value->emp_name;
            $absence['position'] = $value->emp_position;
            $absence['email'] = $value->email;
            $absence['phone'] = $value->phone;
            $thumbnail = "";
            if (!empty($value->sig_address)) {
                if (strpos($value->sig_address,'signature')){
                    $thumbnail = "<div class='d-flex align-item-center'><div class='symbol symbol-90 mr-3'><img src='".str_replace('public/','public_html/',asset('/media/sign_mv/')).'/'.$value->sig_address."' class='img-responsive center-block' height='15%'></div></div>";
                } else {
                    $thumbnail = "<div class='d-flex align-item-center'><div class='symbol symbol-90 mr-3'><img src='".str_replace('public/','public_html/',asset('/media/sign_mv/')).'/'.$value->sig_address."' class='img-responsive center-block' height='15%'></div></div>";
                }
            }
            $absence['signature'] = $thumbnail;
            $absence['action'] = "<a href='".route('mv.delete.attd',['id' => $value->id,'id_main' => $value->id_main])."' onclick='return confirm(\"Delete this data?\");' class='btn btn-danger btn-xs btn-icon'><i class='fa fa-trash icon-sm'></i></a>";
            $row[] = $absence;
        }

        $data = [
            'data' => $row,
        ];

        return json_encode($data);
    }

    public function getMom($id){
        $mtg_mom = Qhse_mv_mom::where('id_main', $id)->orderBy('id', 'desc')->get();

        $row = [];
        $mom = [];

        foreach ($mtg_mom as $key => $value){
            $mom['no'] = ($key+1);
            $mom['time'] = date('d F Y | H:i',strtotime($value->input_time));
            $mom['speaker'] = $value->floor;
            $mom['PIC'] = $value->pic;
            $mom['minute'] = $value->content;
            $mom['deadline'] = date('d F Y', strtotime($value->deadline));
            $mom['deledit'] = "<button type='button' class='btn btn-primary btn-xs btn-icon' data-toggle='modal' data-target='#editMOM".$value->id."'><i class='fa fa-edit'></i></button> &nbsp;&nbsp;&nbsp;"."<a href='".route('mv.delete.delMOM',['id' => $value->id,'id_main' => $value->id_main])."' onclick='return confirm(\"Delete this data?\");' class='btn btn-danger btn-xs btn-icon'><i class='fa fa-trash icon-sm'></i></a>
                <div class='modal fade' id='editMOM".$value->id."' tabindex='-1' role='dialog' aria-labelledby='editMOM".$value->id."' aria-hidden='true'>
                                <div class='modal-dialog modal-dialog-centered modal-xl' role='document'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='exampleModalLabel'>Edit </h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <i aria-hidden='true' class='ki ki-close'></i>
                                            </button>
                                        </div>
                                        <form method='post' id='form-add' action='".route('mv.detail.updateMOM')."' enctype='multipart/form-data'>
                                            <input type='hidden' name='_token' value='".csrf_token()."'>
                                            <input type='hidden' name='id' value='".$value->id."'>
                                            <input type='hidden' name='id_main' value='".$value->id_main."'>
                                            <div class='modal-body'>
                                                <div class='row'>
                                                    <div class='col-md-12'>
                                                        <div class='form-group row'>
                                                            <label class='col-md-2 col-form-label text-right'>Speaker</label>
                                                            <div class='col-md-10'>
                                                                <input type='text' class='form-control' placeholder='Speaker' name='speaker' value='".$value->floor."' required>
                                                            </div>
                                                        </div>
                                                        <div class='form-group row'>
                                                            <label class='col-md-2 col-form-label text-right'>PIC</label>
                                                            <div class='col-md-10'>
                                                                <input type='text' class='form-control' placeholder='PIC' name='pic2' value='".$value->pic."' required>
                                                            </div>
                                                        </div>
                                                        <div class='form-group row'>
                                                            <label class='col-md-2 col-form-label text-right'>Minutes</label>
                                                            <div class='col-md-10'>
                                                                <textarea name='minute' class='form-control' rows='5' placeholder='Write Minutes Of Meeting Here . .'>".$value->content."</textarea>
                                                            </div>
                                                        </div>

                                                        <div class='form-group row'>
                                                            <label class='col-md-2 col-form-label text-right'>Deadline</label>
                                                            <div class='col-md-10'>
                                                                <input type='date' name='deadline' class='form-control' value='".date('Y-m-d',strtotime($value->deadline))."' required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='modal-footer'>
                                                <button type='button' class='btn btn-light-primary font-weight-bold' data-dismiss='modal'>Close</button>
                                                <button type='submit' name='submit_mom' value='save' id='savemom' class='btn btn-primary font-weight-bold'>
                                                    <i class='fa fa-plus'></i>
                                                    Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <script src='".asset('theme/tinymce/tinymce.min.js')."'></script>
                            <script>
                                $(document).ready(function() {
                                    tinymce.init({
                                        selector:'textarea', height: 250
                                    })
                                })

                            </script>";
            $row[] = $mom;
        }

        $data = [
            'data' => $row,
        ];

        return json_encode($data);
    }

    public function deletAttd($id,$id_main){
        Qhse_mv_absence::where('id', $id)->delete();

        return redirect()->route('mv.detail',['id' => $id_main]);
    }

    public function deletDelMOM($id,$id_main){
        Qhse_mv_mom::where('id', $id)->delete();

        return redirect()->route('mv.detail',['id' => $id_main]);
    }

    public function storeMain(Request $request){
//        ActivityConfig::store_point('mom', 'create');
        $mtg_main = new Qhse_mv_main();
        $mtg_main->topic = $request['topic'];
        $mtg_main->location = $request['location'];
        $mtg_main->created_by = Auth::user()->username;
        $mtg_main->date_main = date("Y-m-d H:i:s", strtotime($request['start_date'] . ' ' . $request['start_time']));
        $mtg_main->date_end = (isset($request['end_date']))? date("Y-m-d H:i:s", strtotime($request['end_date'] . ' ' . $request['end_time'])):null;
        $mtg_main->progress = '';
        $mtg_main->company_id = \Session::get('company_id');
        $mtg_main->save();

        return redirect()->route('mv.index');
    }

    public function deleteMain($id){
        Qhse_mv_main::where('id_main', $id)->delete();
        Qhse_mv_absence::where('id_main', $id)->delete();
        Qhse_mv_mom::where('id_main', $id)->delete();
        Qhse_mv_attach::where('id_main', $id)->delete();
        return redirect()->route('mv.index');
    }

    public function setActionProgress($id){

        Qhse_mv_main::where('id_main', $id)
            ->update([
                'progress' => 'Done'
            ]);

        return redirect()->route('mv.index');
    }

    public function getDetail($id){
        $detail_main = Qhse_mv_main::where('id_main', $id)->first();
        $detail = Qhse_mv_mom::where('id_main', $id)->get();
        $attach = Qhse_mv_attach::where('id_main', $id)->get();
//        $mom = Mtg_mom::where('id_main', $id)->get();
        return view('mv.view',[
            'detail'=> $detail_main,
            'id_main' => $id,
            'report' => $detail,
            'attach' => $attach
//            'moms' =>$mom,
        ]);
    }

    public function signatureFileSave(Request $request){

        $absence = new Qhse_mv_absence();
        $absence->id_main = $request['id'];
        $absence->emp_name = $request['name'];
        $absence->emp_position = $request['emp_position'];
//        $absence->plan_name = $request['company'];
        $absence->phone = $request['phone'];
        $absence->email = $request['email'];
        $absence->company_id = \Session::get('company_id');
//        $absence->created_by = Auth::user()->username;
        if ($request->hasFile('file')){
            $file = $request->file('file');
            $newFile = date('Y_m_d_H_i_s')."_file_".$request['name'].'.'.$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);
            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/sign_mv");
            if ($upload == 1){
                $absence->sig_address = $newFile;
                echo 'success';
            } else {
                echo 'unsuccess';
            }
        } else {
            echo 'unsuccess';
        }
        $absence->save();
    }

    public function storeMOM(Request $request){
        $mom = new Qhse_mv_mom();
        $mom->id_main = $request['id'];
        $mom->content = $request['minute'];
        $mom->pic = $request['action'];
        $mom->deadline = $request['deadline'];
        $mom->input_time = date("Y-m-d H:i:s");
        $mom->floor = $request['speaker'];
        $mom->company_id = \Session::get('company_id');
        $mom->save();

        return redirect()->route('mv.detail',['id' => $request['id']]);
    }

    public function updateMOM(Request $request){
        Qhse_mv_mom::where('id', $request['id'])
            ->update([
                'content' => strip_tags($request['minute']),
                'pic' => strip_tags($request['minute']),
                'deadline' =>  $request['deadline'],
                'floor' => $request['speaker'],
                'updated_at' => date("Y-m-d H:i:s"),
            ]);


        return redirect()->route('mv.detail',['id' => $request['id_main']]);
    }

    public function signatureSave(Request $request){
        if (isset($request['imageData'])) {

            $folderPath = public_path("media/sign_mv/");

            $image_parts = explode(";base64,", $request->imageData);

            $image_type_aux = explode("image/", $image_parts[0]);

            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);

            $file_name = date('Y_m_d_H_i_s')."_signature_" .$request['id'] . '.'.$image_type;

            $file = $folderPath . $file_name;
            $up = file_put_contents($file, $image_base64);

            // $image = $request['imageData'];
            // $image = str_replace('data:image/png;base64,', '', $image);
            // $image = str_replace(' ', '+', $image);

            // $image_name = date('Y_m_d_H_i_s')."_signature_" .$request['name'] . '.png';

            $absence = new Qhse_mv_absence();
            $absence->id_main = $request['id'];
            $absence->emp_name = $request['name'];
            $absence->emp_position = $request['emp_position'];
            $absence->phone = $request['phone'];
            $absence->email = $request['email'];
            $absence->company_id = \Session::get('company_id');

            if ($up){
                $absence->sig_address = $file_name;
                echo 'success';
            } else {
                echo 'unsuccess';
            }
            $absence->save();

        } else {
            echo 'unsuccess';
        }
    }

    function uploadAttach(Request $request){
        $target = "media/mv_attachment/";
        $file_attach = $request->file('file_attach');
        $type = explode(".", $file_attach->getClientOriginalName());
        $move = $file_attach->move(public_path($target), $file_attach->getClientOriginalName());
        if ($move){
            $mv_attach = new Qhse_mv_attach();
            $mv_attach->id_main = $request->id;
            $mv_attach->type = end($type);
            $mv_attach->attach_pic = $target.$file_attach->getClientOriginalName();
            $mv_attach->created_by = Auth::user()->username;
            $mv_attach->company_id = Session::get('company_id');
            $mv_attach->date_time = date('Y-m-d H:i:s');
            $mv_attach->save();
            $success = 1;
        } else {
            $success = 2;
        }

        return redirect()->back()->withMessage('msg', $success);
    }

    function deleteAttach($id){
        Qhse_mv_attach::find($id)->delete();

        return redirect()->back();
    }

    function printMv($id){

        $mv = Qhse_mv_main::find($id);

        $comp = ConfigCompany::find($mv->company_id);

        $attendence = Qhse_mv_absence::where('id_main', $id)->get();

        $mom = Qhse_mv_mom::where('id_main', $id)->get();

        $pict = Qhse_mv_attach::where('id_main', $id)->get();

        $view = view("mv.print", compact('mv', 'comp', 'attendence', 'mom', 'pict'));

        $mpdf = new Mpdf(['en-GB-x','A4','','',10,10,10,10,6,3]);

        $mpdf->SetAuthor($comp->company_name);
        $mpdf->SetTitle($comp->tag.'&nbsp;Management Visit');
        $mpdf->SetKeywords('archive, PDF');
        $mpdf->SetDisplayMode('fullpage');

        $mpdf->list_indent_first_level = 0;
        $mpdf->SetHeader('{DATE j M Y}|{PAGENO}/{nb}|MV-Rev.00');
        $mpdf->SetFooter('{PAGENO}');

        $mpdf->WriteHtml($view);

        $mpdf->output();
    }

}
