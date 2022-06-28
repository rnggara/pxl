<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityConfig;
use App\Helpers\FileManagement;
use App\Models\ConfigCompany;
use App\Rms\RolesManagement;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Session;
use App\Models\Qhse_safe_absence;
use App\Models\Qhse_safe_main;
use App\Models\Qhse_safe_mom;
use Illuminate\Support\Facades\Auth;

class SafetyMeetingController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index(){
        return view('sm.index');
    }

    public function getAbsence($id){
        $mtg_absence = Qhse_safe_absence::where('id_main', $id)->get();
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
                    $thumbnail = "<div class='d-flex align-item-center'><div class='symbol symbol-90 mr-3'><img src='".str_replace('public/','',url('storage/media/sign_safe/')).'/'.$value->sig_address."' class='img-responsive center-block' height='15%'></div></div>";
                } else {
                    $thumbnail = "<div class='d-flex align-item-center'><div class='symbol symbol-90 mr-3'><img src='".str_replace('public','public_html',asset('/media/sign_safe/')).'/'.$value->sig_address."' class='img-responsive center-block' height='15%'></div></div>";
                }
            }
            $absence['signature'] = $thumbnail;
            $absence['action'] = "<a href='".route('sm.delete.attd',['id' => $value->id_absence,'id_main' => $value->id_main])."' onclick='return confirm(\"Delete this data?\");' class='btn btn-danger btn-xs btn-icon'><i class='fa fa-trash icon-sm'></i></a>";
            $row[] = $absence;
        }

        $data = [
            'data' => $row,
        ];

        return json_encode($data);
    }

    public function getMom($id){
        $mtg_mom = Qhse_safe_mom::where('id_main', $id)->get();

        $row = [];
        $mom = [];

        foreach ($mtg_mom as $key => $value){
            $mom['no'] = ($key+1);
            $mom['time'] = date('d F Y | H:i',strtotime($value->input_time));
            $mom['speaker'] = $value->floor;
            $mom['PIC'] = $value->pic2;
            $mom['minute'] = $value->content;
            $mom['action'] = $value->pic;
            $mom['deadline'] = date('d F Y', strtotime($value->deadline));
            $mom['deledit'] = "<button type='button' class='btn btn-primary btn-xs btn-icon' data-toggle='modal' data-target='#editMOM".$value->id_sm."'><i class='fa fa-edit'></i></button> &nbsp;&nbsp;&nbsp;"."<a href='".route('sm.delete.delMOM',['id' => $value->id_mom,'id_main' => $value->id_main])."' onclick='return confirm(\"Delete this data?\");' class='btn btn-danger btn-xs btn-icon'><i class='fa fa-trash icon-sm'></i></a>
                <div class='modal fade' id='editMOM".$value->id_sm."' tabindex='-1' role='dialog' aria-labelledby='editMOM".$value->id_sm."' aria-hidden='true'>
                                <div class='modal-dialog modal-dialog-centered modal-xl' role='document'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='exampleModalLabel'>Edit </h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <i aria-hidden='true' class='ki ki-close'></i>
                                            </button>
                                        </div>
                                        <form method='post' id='form-add' action='".route('sm.detail.updateMOM')."' enctype='multipart/form-data'>
                                            <input type='hidden' name='_token' value='".csrf_token()."'>
                                            <input type='hidden' name='id' value='".$value->id_mom."'>
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
                                                                <input type='text' class='form-control' placeholder='PIC' name='pic2' value='".$value->pic2."' required>
                                                            </div>
                                                        </div>
                                                        <div class='form-group row'>
                                                            <label class='col-md-2 col-form-label text-right'>Minutes</label>
                                                            <div class='col-md-10'>
                                                                <textarea name='minute' class='form-control' rows='5' placeholder='Write Minutes Of Meeting Here . .'>".$value->content."</textarea>
                                                            </div>
                                                        </div>
                                                        <div class='form-group row'>
                                                            <label class='col-md-2 col-form-label text-right'>Action</label>
                                                            <div class='col-md-10'>
                                                                <textarea name='action' class='form-control' rows='5' placeholder='Write Action Here . .'>".$value->pic."</textarea>
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
                            <script>tinymce.init({ selector:'textarea', height: 250 });</script>";
            $row[] = $mom;
        }

        $data = [
            'data' => $row,
        ];

        return json_encode($data);
    }

    public function deletAttd($id,$id_main){
        Qhse_safe_absence::where('id_absence', $id)->delete();

        return redirect()->route('sm.detail',['id' => $id_main]);
    }

    public function deletDelMOM($id,$id_main){
        Qhse_safe_mom::where('id_mom', $id)->delete();

        return redirect()->route('sm.detail',['id' => $id_main]);
    }

    public function getMtgAjax(){
        $mtgs = Qhse_safe_main::where('company_id',\Session::get('company_id'))
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
//            $meeting['estimate'] = date('d M Y | H:i A', strtotime($value->date_end));
            $tg = '';
            foreach ($tag as $key2 => $value2){
                if ($key2 == $value->company_id){
                    $tg = strtoupper($value2[0]);
                }
            }
            $meeting['meeting_num'] = $value->id_main.'/'.$tg.'-SM/'.date('m/y',strtotime($value->date_main));
            $meeting['topic'] = ($value->progress == 'created') ? "<a class='btn-link' href='".route('sm.detail',['id' => $value->id_main])."'><i class='fa fa-search'></i>&nbsp;&nbsp;" . $value->topic . "</a>" : $value->topic;
            $meeting['location'] = $value->location;
            $meeting['created_by'] = $value->created_by;
            $meeting['action'] = ($value->progress == 'created') ? "<a class='btn btn-success btn-icon btn-xs' href='".route('sm.action.progress',['id' => $value->id_main])."' onclick='return confirm(\"Are you sure?\")'><i class='fa fa-check-square'></i></a>" : "<a class='btn btn-icon btn-xs btn-primary' href='".str_replace('public','public_html',asset('/media/safe_attachment/'))."/".str_replace('safe_archive/','',$value->progress)."'><i class='fa fa-download'></i></a>";
            if (RolesManagement::actionStart('mom','delete')){
                $meeting['guest'] = "<a class='btn btn-danger btn-xs btn-icon' title='Delete' href='".route('sm.delete.main', ['id'=>$value->id_main])."' onclick='return confirm(\"Are you sure you want to delete ?\")'><i class='fa fa-trash'></i></a>";
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

    public function deleteMain($id){
        Qhse_safe_main::where('id_main', $id)->delete();
        Qhse_safe_absence::where('id_main', $id)->delete();
        Qhse_safe_mom::where('id_main', $id)->delete();
        return redirect()->route('sm.index');
    }

    public function storeMain(Request $request){
//        ActivityConfig::store_point('mom', 'create');
        $mtg_main = new Qhse_safe_main();
        $mtg_main->topic = $request['topic'];
        $mtg_main->location = $request['location'];
        $mtg_main->created_by = Auth::user()->username;
        $mtg_main->date_main = date("Y-m-d H:i:s", strtotime($request['start_date'] . ' ' . $request['start_time']));
        $mtg_main->date_end = (isset($request['end_date']))? date("Y-m-d H:i:s", strtotime($request['end_date'] . ' ' . $request['end_time'])):null;
        $mtg_main->progress = 'created';
        $mtg_main->company_id = \Session::get('company_id');
        $mtg_main->save();

        return redirect()->route('sm.index');
    }

    public function setActionProgress($id){

        Qhse_safe_main::where('id_main', $id)
            ->update([
                'progress' => 'Done'
            ]);

        return redirect()->route('sm.index');
    }

    public function getDetail($id){
        $detail_main = Qhse_safe_main::where('id_main', $id)->first();
//        $mom = Mtg_mom::where('id_main', $id)->get();
        return view('sm.view',[
            'detail'=> $detail_main,
            'id_main' => $id,
//            'moms' =>$mom,
        ]);
    }

    public function signatureFileSave(Request $request){

        $absence = new Qhse_safe_absence();
        $absence->id_main = $request['id'];
        $absence->emp_name = $request['name'];
        $absence->emp_position = $request['emp_position'];
        $absence->plan_name = $request['company'];
        $absence->phone = $request['phone'];
        $absence->email = $request['email'];
        $absence->company_id = \Session::get('company_id');
        $absence->created_by = Auth::user()->username;
        if ($request->hasFile('file')){
            $file = $request->file('file');
            $newFile = date('Y_m_d_H_i_s')."_file_".$request['name'].'.'.$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);
            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/sign_safe");
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
        $mom = new Qhse_safe_mom();
        $mom->id_main = $request['id'];
        $mom->content = strip_tags($request['minute']);
        $mom->pic = strip_tags($request['action']);
        $mom->deadline = $request['deadline'];
        $mom->input_time = date("Y-m-d H:i:s");
        $mom->created_at = date("Y-m-d H:i:s");
        $mom->floor = $request['speaker'];
        $mom->pic2 = $request['pic2'];
        $mom->company_id = \Session::get('company_id');
        $mom->created_by = Auth::user()->username;
        $mom->save();

        return redirect()->route('sm.detail',['id' => $request['id']]);
    }

    public function updateMOM(Request $request){
        Qhse_safe_mom::where('id_mom', $request['id'])
            ->update([
                'content' => strip_tags($request['minute']),
                'pic' => strip_tags($request['minute']),
                'deadline' =>  $request['deadline'],
                'floor' => $request['speaker'],
                'pic2' => $request['pic2'],
                'updated_by' => Auth::user()->username,
            ]);


        return redirect()->route('sm.detail',['id' => $request['id_main']]);
    }

    public function signatureSave(Request $request){
        if (isset($request['imageData'])) {

            $image = $request['imageData'];
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);

            $image_name = date('Y_m_d_H_i_s')."_signature_" .$request['name'] . '.png';

            $absence = new Qhse_safe_absence();
            $absence->id_main = $request['id'];
            $absence->emp_name = $request['name'];
            $absence->emp_position = $request['emp_position'];
            $absence->plan_name = $request['company'];
            $absence->phone = $request['phone'];
            $absence->email = $request['email'];
            $absence->company_id = \Session::get('company_id');
            $absence->created_by = Auth::user()->username;

            if (Storage::disk('sign_safe')->put($image_name,base64_decode($image))){
                $absence->sig_address = $image_name;
                echo 'success';
            } else {
                echo 'unsuccess';
            }
            $absence->save();

        } else {
            echo 'unsuccess';
        }
    }
}
