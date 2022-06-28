<?php

namespace App\Http\Controllers;

//use Faker\Provider\File;
use DB;
use Artisan;
use Session;
use App\Models\User;
use App\Models\Hrd_cv;
use App\Models\Asset_wh;
use App\Models\Division;
use App\Models\Hrd_cv_u;
use App\Models\Asset_item;
use App\Models\General_do;
use App\Models\Hrd_employee;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\Rms_divisions;
use App\Models\UserPrivilege;
use App\Models\Master_var_emp;
use App\Models\Preference_ppe;
use App\Helpers\FileManagement;
use App\Models\File_Management;
use App\Models\Hrd_employee_ppe;
use App\Models\General_do_detail;
use App\Models\Hrd_employee_loan;
use App\Models\Hrd_employee_type;
use App\Models\Preference_config;
use App\Models\Hrd_att_transaction;
use App\Models\Hrd_employee_history;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Models\Hrd_contract_employee;
use App\Models\Master_variables_model;
use App\Models\Hrd_employee_history_edit;
use App\Models\Hrd_employee_loan_payment;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\ConfigCompany as Config_Company;

class HrdEmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getEmp(Request $request){
        $type = $request->type;
        $divisions = Rms_divisions::where('name','not like','%admin%')
            ->get();
        if ($type == 0){
            $employees = Hrd_employee::whereNull('expel')
                ->where('company_id', Session::get('company_id'))
                ->orderBy('emp_name')
                ->get();
        } else {
            if ($type == -1) {
                $employees = Hrd_employee::whereNotNull('expel')
                    ->where('company_id', Session::get('company_id'))
                    ->orderBy('emp_name')
                    ->get();
            } else {
                $employees = Hrd_employee::whereNull('expel')
                    ->where('emp_type', $type)
                    ->where('company_id', Session::get('company_id'))
                    ->orderBy('emp_name')
                    ->get();
            }
        }

        $divName = [];
        foreach ($divisions as $key => $val){
            $divName['name'][$val->id] = $val->name;
        }

        $emptypes = Hrd_employee_type::all();
        $emp_type = [];
        foreach ($emptypes as $key => $val){
            $emp_type[$val->id] = $val->name;
        }

        $file_isset = File_Management::all()->pluck('file_name', 'hash_code');

        $row = [];
        $emp = [];

        $ct = Hrd_contract_employee::where('company_id', Session::get("company_id"))
            ->whereNull('approved_by')
            ->get();
        $ct_emp = [];
        $ctid = [];
        foreach($ct as $item){
            if(empty($item->approved_at)){
                $ct_emp[$item->emp_id] = $item->links;
            } else {
                $ctid[$item->id] = 1;
            }
        }

        // <button type='button' data-target='#modalcontract-".$value->id."' data-toggle='modal' class='btn btn-sm btn-success'>
        //                                         <i class='fa fa-plus icon-nm'></i> [add contract]
        //                                     </button>

        foreach ($employees as $key => $value){
            $nik = explode("-", $value->emp_id);
            $status = substr(end($nik),0,1);
            $emp['no'] = ($key+1);
            $emp['emp_type'] = (isset($emp_type[$value->emp_type])) ? $emp_type[$value->emp_type] : "-";
            $emp['emp_id'] = $value->emp_id;
            $emp['emp_position'] = $value->emp_position;
            $emp['division'] = (isset($divName['name'][$value->division])) ? $divName['name'][$value->division] : "";
            if ($status != 'K' && $status != 'C'){
                $emp['status'] = "<center><label class='text-center text-success'>Pegawai Tetap</label></center>";
                $btnBg = ($type == - 1) ? "btn-danger" : "btn-primary";
            } else {
                if ($value->expire == null){
                    $btnBg = ($type == - 1) ? "btn-danger" : "btn-primary";
                    if(isset($ct_emp[$value->id])){
                        $emp['status']  = "<center><button type='button' onclick='_link(this)' data-toggle='tooltip' data-link='".$ct_emp[$value->id]."' title='Click here to copy the link' class='btn btn-info btn-sm'>Waiting approval</button></center>";
                    } else {
                        $emp['status'] = "<center>";
                        $emp['status'] .= "<button type='button' data-target='#modalcontract-".$value->id."' data-toggle='modal' class='btn btn-sm btn-success'>
                                                    <i class='fa fa-plus icon-nm'></i> [add contract]
                                                </button><br><br>";
                        $emp['status'] .= "<button type='button' data-target='#modalGenerate' onclick='_contract($value->id)' data-toggle='modal' class='btn btn-sm btn-success'>
                                                <i class='fa fa-plus icon-nm'></i> create
                                            </button>";
                        $emp['status'] .= "</center>

                                        <div class='modal fade' id='modalcontract-".$value->id."' tabindex='-1' role='dialog' aria-labelledby='modalcontract-".$value->id."' aria-hidden='true'>
                                            <div class='modal-dialog modal-dialog-centered modal-xl' role='document'>
                                                <div class='modal-content'>
                                                    <div class='modal-header'>
                                                        <h5 class='modal-title' id='exampleModalLabel'>Add Contract</h5>
                                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                            <i aria-hidden='true' class='ki ki-close'></i>
                                                        </button>
                                                    </div>
                                                    <form method='post' action='".route('employee.addcontract')."' enctype='multipart/form-data'>
                                                        <input type='hidden' name='_token' value='".csrf_token()."'>
                                                        <input type='hidden' name='id' value='".$value->id."'>
                                                        <div class='modal-body'>
                                                            <br>
                                                            <h4>Upload a contract for $value->emp_name</h4><hr>
                                                            <div class='row'>
                                                                <div class='form col-md-12'>
                                                                    <div class='form-group'>
                                                                        <label>Document</label>
                                                                        <input type='file' class='form-control' name='contract_file' required id='contract_file' placeholder=''>
                                                                    </div>
                                                                    <div class='form-group'>
                                                                        <label>This contract expires on</label>
                                                                        <input type='date' class='form-control' required name='date_exp' placeholder='' />
                                                                    </div>
                                                                    <div class='form-group'>
                                                                        <label></label>
                                                                        <label or='as' class='control-label'>
                                                                            <input type='radio' name='opt' value='1' id='opt' checked />
                                                                            Renew Contract
                                                                        </label>
                                                                        &nbsp;&nbsp;
                                                                        <label for='int' class='control-label'>
                                                                            <input type='radio' name='opt' value='2' id='opt' />
                                                                            Permanent Employee
                                                                        </label>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class='modal-footer'>
                                                            <button type='button' class='btn btn-light-primary font-weight-bold' data-dismiss='modal'>Close</button>
                                                            <button type='submit' name='submit' value='1' class='btn btn-primary font-weight-bold'>
                                                                <i class='fa fa-check'></i>
                                                                Add Contract</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        ";
                    }
                } else {
                    $date2 = date('Y-m-d', strtotime('-1 month', strtotime($value->expire)));
                    $date1 = date('Y-m-d');

                    $_date1=date_create($date2);
                    $_date2=date_create($date1);
                    $_diff=date_diff($_date2,$_date1);
                    $_months = round(intval($_diff->format('%R%a'))/ 30);

                    $diff = abs(strtotime($date2) - strtotime($date1));
                    $years = floor($diff / (365*60*60*24));
                    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                    if ($_months <= 1) {
                        $bg = "text-danger";
                        $btnBg = "btn-danger";
                    } elseif($_months > 1 && $_months <= 6) {
                        $bg = ($type == - 1) ? "text-danger" : "text-warning";
                        $btnBg = ($type == - 1) ? "btn-danger" : "btn-warning";
                    } else {
                        $bg = ($type == - 1) ? "text-danger" : "text-primary";
                        $btnBg = ($type == - 1) ? "btn-danger" : "btn-primary";
                    }
                    $yeari = substr($value->expire, 0, 4);

                    $monthn = date("m");
                    $monthi = substr($value->expire,5,2);
                    $selmonth = $monthi - $monthn;

                    $contract = "";

                    $neednewcontract = 0;

                    $isctid = 0;

                    if(!empty($value->contract_file) && isset($file_isset[$value->contract_file])){
                        $_file = str_replace("public", "public_html", asset($file_isset[$value->contract_file]));
                        $f = str_replace(" ", "%20", $_file);
                        $handle = @fopen($f, 'r');
                        if($handle){
                            $contract = "<a href='" . route('download', $value->contract_file) . "' class='btn btn-xs btn-icon btn-light-success' target='_blank'><i class='fa fa-download'></i></a>";
                            clearstatcache();
                        } else {
                            $neednewcontract = 1;
                        }
                    } else {
                        if(empty($value->expire)){
                            $neednewcontract = 1;
                        } else {
                            $contract = "<a href='".route('hrd.contract.pdf', base64_encode($value->contract_file))."' class='btn btn-xs btn-icon btn-light-success' target='_blank'><i class='fa fa-download'></i></a>";
                        }
                    }

                    $emp['status'] = "<center>
                                            <label class='$bg font-weight-bolder'>exp: ".$value->expire."</label>
                                            $contract
                                        </center>";

                    if ((((date("Y") >= $yeari && $selmonth <= 1) || (date("Y") < $yeari && $selmonth <= -11)) && $value->expire != "0000-00-00") || $neednewcontract){
                        $_lb = ($neednewcontract == 1) ? "renew contract" : "renew contract";
                        $expire = ($neednewcontract == 1) ? $value->expire : "";
                        if(isset($ct_emp[$value->id])){
                            $emp['status']  = "<center><button type='button' onclick='_link(this)' data-toggle='tooltip' data-link='".$ct_emp[$value->id]."' title='Click here to copy the link' class='btn btn-info btn-sm'>Waiting approval</button></center>";
                        } else {
                            $emp['status'] .= "<br><center>
                                                <button type='button' data-target='#modalGenerate' onclick='_contract($value->id)' data-toggle='modal' class='btn btn-sm btn-success'>
                                                    <i class='fa fa-plus icon-nm'></i> [$_lb]
                                                </button>
                                            </center>

                                            <div class='modal fade' id='modalrenewcontract-".$value->id."' tabindex='-1' role='dialog' aria-labelledby='modalrenewcontract-".$value->id."' aria-hidden='true'>
                                                <div class='modal-dialog modal-dialog-centered modal-xl' role='document'>
                                                    <div class='modal-content'>
                                                        <div class='modal-header'>
                                                            <h5 class='modal-title' id='exampleModalLabel'>Renew Contract</h5>
                                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                <i aria-hidden='true' class='ki ki-close'></i>
                                                            </button>
                                                        </div>
                                                        <form method='post' action='".route('employee.addcontract')."' enctype='multipart/form-data'>
                                                            <input type='hidden' name='_token' value='".csrf_token()."'>
                                                            <input type='hidden' name='id' value='".$value->id."'>
                                                            <div class='modal-body'>
                                                                <br>
                                                                <h4>Upload a contract for ".$value->emp_name."</h4><hr>
                                                                <div class='row'>
                                                                    <div class='form col-md-12'>
                                                                        <div class='form-group'>
                                                                            <label>Document</label>
                                                                            <input type='file' class='form-control' name='contract_file' required id='contract_file' placeholder=''>
                                                                        </div>
                                                                        <div class='form-group'>
                                                                            <label>This contract expires on</label>
                                                                            <input type='date' class='form-control' value='$expire' name='date_exp' required placeholder='' />
                                                                        </div>
                                                                        <div class='form-group'>
                                                                            <label></label>
                                                                            <label or='as' class='control-label'>
                                                                                <input type='radio' name='opt' value='1' id='opt' checked />
                                                                                ".ucwords($_lb)."
                                                                            </label>
                                                                            &nbsp;&nbsp;
                                                                            <label for='int' class='control-label'>
                                                                                <input type='radio' name='opt' value='2' id='opt' />
                                                                                Permanent Employee
                                                                            </label>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class='modal-footer'>
                                                                <button type='button' class='btn btn-light-primary font-weight-bold' data-dismiss='modal'>Close</button>
                                                                <button type='submit' name='submit' value='1' class='btn btn-primary font-weight-bold'>
                                                                    <i class='fa fa-check'></i>
                                                                    Add Contract</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            ";
                        }


                    }
                }
            }
            $emp['emp_name'] = "<a href='".route('employee.detail',['id'=>$value->id])."' class='btn $btnBg btn-sm'>".$value->emp_name."</a>";
            $emp['cv'] = "<a href='".route('employee.detail',['id'=>$value->id])."#cv-management' class='btn btn-success btn-sm'><i class='fa fa-cog icon-nm'></i> manage</a>";
            $emp['document'] = "<a href='".route('employee.detail',['id'=>$value->id])."#attachment-management' class='btn btn-success btn-sm'><i class='fa fa-cog icon-nm'></i> manage</a>";
            $emp['quit'] = "<a href='".route('employee.expel',['id' =>$value->id])."' class='btn btn-sm btn-danger' onclick='return confirm(\"Pegawai ini DIPECAT?\"); '><i class='fa fa-times icon-nm'></i> Fired</a>";
            $emp['training_point'] = " <a href='' class='btn btn-light-dark btn-icon btn-sm'><i class='fa fa-eye text-white icon-nm'></i></a>";
            $emp['action'] = "<form method='post' action='".route('employee.delete',['id'=>$value->id])."'>
                                   <input type='hidden' name='_token' value='".csrf_token()."'>
                                    <button type='submit' class='btn btn-sm btn-icon btn-default' onclick='return confirm(\"Hapus data pegawai?\");'>
                                        <i class='fa fa-trash text-danger'></i>
                                    </button>
                              </form>";
            $row[] = $emp;
        }
        $data = [
            'data' => $row,
        ];
        return json_encode($data);

    }

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

        $divisions = Rms_divisions::where('name','not like','%admin%')
            ->whereNull('deleted_at')
            ->get();
        $employees = Hrd_employee::whereNull('expel')
            ->whereIn('company_id', $id_companies)
            ->get();
        $divName = [];
        foreach ($divisions as $key => $val){
            $divName['name'][$val->id] = $val->name;
        }

        $comp_ids = [];
        $comp = ConfigCompany::find(Session::get('company_id'));
        if (empty($comp->id_parent)) {
            $childCompany = ConfigCompany::select("id")
                ->where('id_parent', $comp->id)
                ->get();
            foreach($childCompany as $ids){
                $comp_ids[] = $ids->id;
            }
        } else {
            $comp_ids[] = $comp->id_parent;
        }

        $comp_ids[] = Session::get('company_id');


        $emptypes = Hrd_employee_type::whereIn('company_id', $comp_ids)
            ->where('company_exclude', 'not like', '%"'.$comp->id.'"%')
            ->orWhereNull("company_exclude")
            ->get();
        $emp_type = [];
        foreach ($emptypes as $key => $val){
            $emp_type[$val->id] = $val->name;
        }

        return view('employee.index',[
            'employees' => $employees,
            'emptypes' => $emptypes,
            'divisions' => $divisions,
            'divName' => $divName,
            'emp_type' => $emp_type,
        ]);
    }

    public function getIndexEmployeeLoan(){
        $loan_payment = Hrd_employee_loan_payment::orderBy('date_of_payment','DESC')
            ->where('date_of_payment', '<=' , date("Y-m-t", strtotime(date("Y-m"))))
            // ->orderBy('id', 'desc')
            ->get();

        $employees = Hrd_employee::where('company_id', \Session::get('company_id'))
            ->whereNull('expel')
            ->whereNull('deleted_at')
            ->get();
        $payment = array();
        foreach ($loan_payment as $item){
            $payment[$item->company_id][$item->loan_id][] = $item->amount;
        }
        // dd($payment[17][42]);
        $data_emp = array();
        foreach ($employees as $item){
            $data_emp[$item->id] = $item;
            $id[] = $item->id;
        }

        $loan = Hrd_employee_loan::where('company_id', \Session::get('company_id'))
            ->whereIn('emp_id', $id)
            ->get();

        return view('employee.loan',[
            'employees' => $employees,
            'loans' => $loan,
            'payments' => $payment,
            'data_emp' => $data_emp,
        ]);
    }
    public function loandelete($id){
        Hrd_employee_loan::find($id)->delete();
        Hrd_employee_loan_payment::where('loan_id',$id)->delete();
        return redirect()->route('employee.loan');

    }

    public function submitNeedsec(Request $request){
        $this->validate($request,[
            'searchInput' => 'required'
        ]);
        if ($request['searchInput'] == 'koi999'){
            Session::put('seckey_empfin', 99);
            return redirect()->back()->with('message_needsec_success_empfin', 'Access Granted!');
        } else {
            return redirect()->back()->with('message_needsec_fail_empfin', 'Access Denied!');
        }
    }

    public function nextDocNumber($code,$db){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        if ($db == "loan"){
            $cek = Hrd_employee_loan::where('loan_id','like','%'.$code.'%')
                ->whereIn('company_id', $id_companies)
                ->whereNull('deleted_at')
                ->orderBy('id','DESC')
                ->get();

            if (count($cek) > 0){
                $loanId = $cek[0]->loan_id;
                $str = explode('/', $loanId);
                $number = intval($str[0]);
                $number+=1;
                if ($number > 99){
                    $no = strval($number);
                } elseif ($number > 9) {
                    $no = "0".strval($number);
                } else {
                    $no = "00".strval($number);
                }
            } else {
                $no = "001";
            }
        } else {
            $cek = Hrd_employee_loan_payment::where('payment_id','like','%'.$code.'%')
                ->whereIn('company_id', $id_companies)
                ->whereNull('deleted_at')
                ->orderBy('id','DESC')
                ->get();

            if (count($cek) > 0){
                $payId = $cek[0]->payment_id;
                $str = explode('/', $payId);
                $number = intval($str[0]);
                $number+=1;
                if ($number > 99){
                    $no = strval($number);
                } elseif ($number > 9) {
                    $no = "0".strval($number);
                } else {
                    $no = "00".strval($number);
                }
            } else {
                $no = "001";
            }
        }
        return $no;

    }

    function monthDiff($date1, $date2) {
        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

        return $diff;
    }

    public function addContract(Request $request){
        $emp = Hrd_employee::where('id', $request['id'])->first();
        $file = $request->file('contract_file');
        if (!empty($file)){
            $file = $request->file('contract_file');

            $newFile = str_replace(" ", "_", $emp->emp_name).'_'.date('Y_m_d_H_i_s')."-contract_file.".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/employee_attachment");
            if ($upload == 1){
                Hrd_employee::where('id',$request['id'])
                    ->update([
                        'expire' =>$request['date_exp'],
                        'contract_file' => $hashFile,
                    ]);
            }
        }

        if ($request['opt'] == '2'){
            $str = explode('-',$emp->emp_id);

            $status = substr($emp->emp_id,4,1);
            $str1_new = str_replace($status,'',$str[1]);
            $new_empid = $str[0].'-'.$str1_new;
            Hrd_employee::where('id',$request['id'])
                ->update([
                    'emp_id' => $new_empid,
                ]);
        }


        return redirect()->back();
    }

    public function storeCV(Request $request){
        date_default_timezone_set('Asia/Jakarta');
        $emp = Hrd_employee::find($request->id_emp);
        if ($request->hasFile('document')){
            $file = $request->file('document');
            for ($i=0; $i < count($file); $i++) {
                $hrd_cv = new Hrd_cv_u();
                $hrd_cv->user_id = $request['id_emp'];
                $dup = date("Y_m_d_H_i_s");
                $emp_name = str_replace(" ", "_", $emp->emp_name);
                $newFile = "[$emp_name-$emp->id-$dup]_".$file[$i]->getClientOriginalName();
                $hashFile = Hash::make($newFile);
                $hashFile = str_replace("/", "", $hashFile);

                $upload = FileManagement::save_file_management($hashFile, $file[$i], $newFile, "media/employee_attachment");
                if ($upload == 1){
                    $hrd_cv->cv_address = $hashFile;
                    $hrd_cv->cv_name = $newFile;
                    $hrd_cv->date_time = date('Y-m-d H:i:s');
                    $hrd_cv->whom = Auth::user()->username;
                    $hrd_cv->created_at = date('Y-m-d H:i:s');
                }
                $hrd_cv->save();
            }
        }
        return redirect()->back();
    }

    public function deleteCV($id){
        Hrd_cv_u::find($id)->delete();
        return redirect()->back();
    }

    public function addLoan(Request $request){
        $this->validate($request,[
            'employee' => 'required',
            'start' => 'required',
            'end'=> 'required',
            'amount' => 'required'
        ]);

        $arrRomawi	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $loan_num = $this->nextDocNumber("LN","loan");
        $loanID = str_pad($loan_num, 3, '0', STR_PAD_LEFT).'/'.strtoupper(\Session::get('company_tag')).'/LN/'.$arrRomawi[date("n")].'/'.date("y");

        $loan = new Hrd_employee_loan();
        $loan->loan_id = $loanID;
        $loan->emp_id = $request['employee'];
        $loan->loan_amount = $request['amount'];
        $loan->loan_start = $request['start'];
        $loan->loan_end = $request['end'];
        $loan->notes = ($request['notes']!=null) ? $request['notes']:'';
        $loan->given_by = Auth::user()->username;
        $loan->given_time = date('Y-m-d H:i:s');
        $loan->date_given = date('Y-m-d');
        $loan->company_id = \Session::get('company_id');
        $loan->save();

        if (isset($request['autopay'])){
            list($d1, $m1, $y1) = explode('-', $request['start']);
            list($d2, $m2, $y2) = explode('-', $request['end']);

            $bonusStart = sprintf("%s-%02s-%02s", $y1, $m1, $d1);
            $bonusEnd = sprintf("%s-%02s-%02s", $y2, $m2, $d2);
            $monthDiff = $this->monthDiff($bonusStart, $bonusEnd);
            if($monthDiff == 0){
                $monthDiff = 1;
            }

            $balance = $loan->loan_amount;
            $cicil_draft = $loan->loan_amount / intval($monthDiff);

            // dd($cicil_draft,$loan->loan_amount, $monthDiff);

            for ($i = 0; $i < $monthDiff; $i++){
                $payment_num = $this->nextDocNumber("LNPAY","loan_payment");
                $id_loan = $loan->id;
                $payment_id = str_pad($payment_num, 3, '0', STR_PAD_LEFT).'/'.strtoupper(\Session::get('company_tag')).'/LNPAY/'.$arrRomawi[date("n")].'/'.date("y");
                $date_of_payment_repeat = strtotime($bonusStart);
                $dates = date('Y-m-d', strtotime("+".$i." month", $date_of_payment_repeat));
                $dates2 = explode('-',$dates);
                $date_of_payment = $dates2[0].'-'.$dates2[1].'-17';

                if(($i + 1) == $monthDiff){
                    $cicil_now = $balance;
                } else {
                    $cicil_now = $cicil_draft;
                }

                $amount = $cicil_now;

                $loan_pay = new Hrd_employee_loan_payment();
                $loan_pay->loan_id = $id_loan;
                $loan_pay->amount = round($amount);
                $loan_pay->payment_id = $payment_id;
                $loan_pay->date_of_payment = $date_of_payment;
                $loan_pay->remark = 'insert by autopay';
                $loan_pay->receive_by = Auth::user()->username;
                $loan_pay->receive_time = date('Y-m-d H:i:s');
                $loan_pay->company_id = \Session::get('company_id');
                $loan_pay->save();
                $balance -= $cicil_now;
            }
        }
        return redirect()->route('employee.loan');
    }

    function expelEmp($id){
        $emp = Hrd_employee::find($id);
        $emp->expel = date('Y-m-d');

        if ($emp->save()) {
            $history = new Hrd_employee_history();
            $history->emp_id = $emp->id;
            $history->activity = 'fired';
            $history->act_date = date('Y-m-d');
            $history->act_by   = Auth::user()->username;
            $history->company_id = Session::get('company_id');
            $history->save();

            // find user
            $user = User::where("emp_id", $emp->id)->first();
            if(!empty($user)){
                $user_id = $user->id;
                if(!empty($user)){
                    UserPrivilege::where('id_users', $user_id)->forceDelete();
                    $user->delete();
                }
            }
        }

        return redirect()->back();
    }

    public function getDetailLoan($id){
        $loan = Hrd_employee_loan::where('id',$id)
            ->whereNull('deleted_at')
            ->first();

        $emps = Hrd_employee::all();
        $data_emp = array();
        foreach ($emps as $item){
            $data_emp[$item->id] = $item;
        }

        $emp = $data_emp[$loan->emp_id];

        $loan_balance = intval($loan->loan_amount);

        $id_loan = (empty($loan->old_id)) ? $id : $loan->old_id;

        $loan_payments = Hrd_employee_loan_payment::where('loan_id', $id)
            // ->where('company_id', \Session::get('company_id'))
            ->whereNull('deleted_at')
            ->get();

        $paid_loan = Hrd_employee_loan_payment::where('loan_id', $id)
            ->where('date_of_payment', '<=', date('Y-m-t', strtotime(date("Y-m"))))
            // ->where('company_id', \Session::get('company_id'))
            ->whereNull('deleted_at')
            ->get();

        foreach ($paid_loan as $key => $val){
            $loan_balance -= intval($val->amount);
        }

        return view('employee.loan_payment',[
            'emp' => $emp,
            'payments' => $loan_payments,
            'balance' => $loan_balance,
            'loan' => $loan
        ]);
    }

    public function storeLoanPayment(Request $request){
        $arrRomawi	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $payment_num = $this->nextDocNumber("LNPAY","loan_payment");
        $payment_id = str_pad($payment_num, 3, '0', STR_PAD_LEFT).'/'.strtoupper(\Session::get('company_tag')).'/LNPAY/'.$arrRomawi[date("n")].'/'.date("y");
        $loan_pay = new Hrd_employee_loan_payment();
        $loan_pay->loan_id = $request->loan_id;
        $loan_pay->amount = str_replace(",", "", $request->amount);
        $loan_pay->payment_id = $payment_id;
        $loan_pay->date_of_payment = $request->date_of_payment;
        $loan_pay->remark = $request->memo;
        $loan_pay->receive_by = Auth::user()->username;
        $loan_pay->receive_time = date('Y-m-d H:i:s');
        $loan_pay->company_id = \Session::get('company_id');
        $loan_pay->save();
        return redirect()->route('employee.loan.detail',[$request['loan_id']]);
    }

    public function store(Request $request){
        $uploaddir = public_path('hrd\\uploads');
        $employee = new Hrd_employee();
        $employee_history = new Hrd_employee_history();

        if ($request->hasFile('picture')){
            $file = $request->file('picture');
            $newFile = stripslashes($request->input('emp_id'))."-picture.".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/employee_attachment");
            if ($upload == 1){
                $employee->picture = $newFile;
            }
        }

        if ($request->hasFile('ktp')){
            $file = $request->file('ktp');
            $newFile = stripslashes($request->input('emp_id'))."-ktp.".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/employee_attachment");
            if ($upload == 1){
                $employee->ktp = $newFile;
            }
        }

        if ($request->hasFile('serti1')){
            $file = $request->file('serti1');
            $newFile = stripslashes($request->input('emp_id'))."-serti1.".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/employee_attachment");
            if ($upload == 1){
                $employee->serti1 = $newFile;
            }
        }

        $employee->emp_id = stripslashes($request->input('emp_id'));
        $employee->emp_name = stripslashes($request->input('full_name'));


        $thp       = $request->input('thp');
        $SAL       = intval($thp*0.4);
        $HEALTH    = intval($thp*0.15);
        $TRANSPORT = intval($thp*0.15);
        $MEAL      = intval($thp*0.20);
        $HOUSE     = intval($thp*0.10);

        $employee->phoneh                = $request->input('phone_home');
        $employee->salary                = base64_encode($SAL);
        $employee->transport             = base64_encode($TRANSPORT);
        $employee->meal                  = base64_encode($MEAL);
        $employee->house                 = base64_encode($HOUSE);
        $employee->health                = base64_encode($HEALTH);
        $employee->emp_position          = $request->input('position');
        $employee->pension               = ($request->input('pensi')) ? $request->input('pensi') : 0;
        $employee->health_insurance      = ($request->input('hi')) ? $request->input('hi') : 0;
        $employee->jamsostek             = ($request->input('jam')) ? $request->input('jam') : 0;
        $employee->emp_type              = $request->input('emp_type');
        $employee->religion              = $request->input('religion');
        $employee->company_id            = Session::get('company_id');
        $employee->tax_status            = 0;
        $employee->fld_bonus             = ($request->input('fld_bonus')) ? $request->input('fld_bonus') : 0;
        $employee->division              = ($request->input('division')) ? $request->input('division') : 0;
        $employee->odo_bonus             = ($request->input('odo_bonus')) ? $request->input('odo_bonus') : 0;
        $employee->wh_bonus              = ($request->input('wh_bonus')) ? $request->input('wh_bonus') : 0;
        $employee->overtime              = $request->input('overtime');
        $employee->voucher               = $request->input('voucher');
        $employee->yearly_bonus          = ($request->input('yb')) ? $request->input('yb') : 0;
        $employee->allowance_office      = ($request->input('pa')) ? $request->input('pa') : 0;
        $employee->dom_meal              = $request->input('dom_meal');
        $employee->dom_spending          = $request->input('dom_spending');
        $employee->dom_overnight         = $request->input('dom_overnight');
        $employee->ovs_meal              = $request->input('ovs_meal');
        $employee->ovs_spending          = $request->input('ovs_spending');
        $employee->ovs_overnight         = $request->input('ovs_overnight');
        $employee->dom_transport_train   = $request->input('dom_transport_train');
        $employee->dom_transport_airport = $request->input('dom_transport_airport');
        $employee->dom_transport_bus     = $request->input('dom_transport_bus');
        $employee->dom_transport_cil     = $request->input('dom_transport_cil');
        $employee->ovs_transport_train   = $request->input('ovs_transport_train');
        $employee->ovs_transport_airport = $request->input('ovs_transport_airport');
        $employee->ovs_transport_bus     = $request->input('ovs_transport_bus');
        $employee->ovs_transport_cil     = $request->input('ovs_transport_cil');

        $employee->cuti_flag             = 0;
        $employee->max_loan              = 0;
        $employee->others                = 0;
        $employee->bank_code             = $request->input('bankCode');
        $employee->bank_acct             = $request->input('account');

        $employee->phone                 = $request->input('phone_1');
        $employee->phone2                = $request->input('phone_2');
        $employee->address               = $request->input('address');
        $employee->email                 = $request->input('email');
        $employee->emp_lahir             = $request->input('date_birth');

        $employee->save();

        $employee_history->emp_id        = $employee->id;
        $employee_history->activity      = "in";
        $employee_history->act_date      = date("Y-m-d");
        $employee_history->act_by        = Auth::user()->username;
        $employee_history->company_id    = \Session::get('company_id');

        $employee_history->save();

        return redirect()->route('employee.detail', $employee->id);


    }

    public function nikFunction(Request $request){
        $emp_status = $request->emp_status;
        switch($emp_status) {
            case "tetap": $type = ""; break;
            case "kontrak": $type = "K"; break;
            case "konsultan": $type = "C"; break;
        }
        $date = explode("-",date("Y-m-d"));
        $nik_exist = strtoupper(Session::get('company_tag'))."-".$type.$date[2].$date[1].$date[0];
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
            $NIK = strtoupper(Session::get('company_tag'))."-".$type.$date[2].$date[1].$date[0].$nextdigit;

        } else {
            $NIK = strtoupper(Session::get('company_tag'))."-".$type.$date[2].$date[1].$date[0]."01";
        }
        $data = [
            'data' => $NIK,
        ];
        return json_encode($data);
    }

    public function thpBreakdown(Request $request){
        $thp = $request->thp;
        $SAL = (intval($thp*0.4));
        $HEALTH = (intval($thp*0.15));
        $TRANSPORT = (intval($thp*0.15));
        $MEAL = (intval($thp*0.20));
        $HOUSE = (intval($thp*0.10));

        $data = [
            'data' => "<br>
                        <table class='table table-hover' width='20%'>
                            <thead>
                                <tr>
                                    <th class='text-center' colspan='3'><b>Break Down</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class='text-left' width='20px'>Salary</td>
                                    <td class='text-center'>&nbsp;:&nbsp;&nbsp;</td>
                                    <td class='text-right'>".number_format($SAL)."</td>
                                </tr>
                                <tr>
                                    <td class='text-left' width='20px'>Health</td>
                                    <td class='text-center'>&nbsp;:&nbsp;&nbsp;</td>
                                    <td class='text-right'>".number_format($HEALTH)."</td>
                                </tr>
                                <tr>
                                    <td class='text-left' width='20px'>Transport</td>
                                    <td class='text-center'>&nbsp;:&nbsp;&nbsp;</td>
                                    <td class='text-right'>".number_format($TRANSPORT)."</td>
                                </tr>
                                <tr>
                                    <td class='text-left' width='20px'>Meal</td>
                                    <td class='text-center'>&nbsp;:&nbsp;&nbsp;</td>
                                    <td class='text-right'>".number_format($MEAL)."</td>
                                </tr>
                                <tr>
                                    <td class='text-left' width='20px'>House</td>
                                    <td class='text-center'>&nbsp;:&nbsp;&nbsp;</td>
                                    <td class='text-right'>".number_format($HOUSE)."</td>
                                </tr>
                            </tbody>
                        </table>",
        ];

        return json_encode($data);
    }

    public function getDetail($id){
        $emp_cv_u = Hrd_cv_u::where('user_id', $id)
            ->where('vaccine', 0)->get();
        $id_companies = array();
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

        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $getDetailData = Hrd_employee::where('id', $id)->first();
        $getDetailData_history = Hrd_employee_history::where('emp_id',$id)
            ->where('activity','in')->first();
        $status = substr($getDetailData->emp_id,4,1);
        $divisions = Rms_divisions::where('name','not like','%admin%')
            ->get();

        $cv = Hrd_cv::where('emp_id', $id)
            ->orderBy('end_date')
            ->get();

        $office = Asset_wh::where('company_id', Session::get('company_id'))
            ->where('office', 1)
            ->get()->pluck('name', 'id');

        $vaccine = Hrd_cv_u::where('user_id', $id)
            ->where('vaccine', 1)->get();

        $variables = Master_variables_model::where("company_id", Session::get("company_id"))->get();

        $var_val = Master_var_emp::where("id_emp", $id)->get()->pluck('values', 'id_var');

        $curr_comp = Config_Company::find($getDetailData->company_id);

        if(!empty($curr_comp->id_parent)){
            $par_comp = Config_Company::find($curr_comp->id_parent);
        } else {
            $par_comp = $curr_comp;
        }

        $child_company = Config_Company::where('id', '!=', $getDetailData->company_id)
            ->where(function($query) use($par_comp){
                $query->orWhere('id', $par_comp->id);
                $query->orWhere('id_parent', $par_comp->id);
            })
            ->get()->pluck('company_name', 'id');

        $emp_sister = [];
        if(!empty($getDetailData->emp_id_sister)){
            $emp_sister = Hrd_Employee::find($getDetailData->emp_id_sister);
        }

        $file = File_Management::all()->pluck('file_name', 'hash_code');

        $ppe = Hrd_employee_ppe::where("emp_id", $id)->first();
        $do = [];
        if(!empty($ppe->do_id)){
            $do = General_do::find($ppe->do_id);
        }

        $users = User::where('company_id', Session::get('company_id'))
            ->whereNull("emp_id")
            ->orWhere('emp_id', $id)
            ->get();
        $empUser = User::where('emp_id', $getDetailData->id)->first();

        $clockin = null;
        $clockout = null;
        if(!empty($empUser)){
            $session = Hrd_att_transaction::where("emp_id", $getDetailData->id)->orderBy('id')->get();
            if(count($session) > 0){
                $clockin = $session[0]->trans_time;
                if(count($session) > 1){
                    $clockout = $session[count($session) - 1]->trans_time;
                }
            }
        }

        return view('employee.detail',[
            'emptypes' => $emptypes,
            'emp_detail' => $getDetailData,
            'emp_detail_history' => $getDetailData_history,
            'status' => $status,
            'divisions' => $divisions,
            'emp_cv' => $emp_cv_u,
            'cv' => $cv,
            'office' => $office,
            'vaccine' => $vaccine,
            'variables' => $variables,
            'var_val' => $var_val,
            'child_comp' => $child_company,
            'emp_sister' => $emp_sister,
            'file_name' => $file,
            "do" => $do,
            "ppe" => $ppe,
            'user_list' => $users,
            'user_emp' => $empUser,
            'clockin' => $clockin,
            'clockout' => $clockout
        ]);

    }

    public function empCompany($id, Request $request){
        $whereEmpName = " 1";
        if(isset($request->term) && !empty($request->term)){
            $whereEmpName = "emp_name like '%$request->term%'";
        }

        $emp = Hrd_Employee::where('company_id', $id)
            ->whereRaw($whereEmpName)
            ->whereNull('expel')
            ->orderBy('emp_name')
            ->get()->pluck('emp_name', 'id');

        $row = [];
        foreach($emp as $id => $name){
            $col = [];
            $col['id'] = $id;
            $col['text'] = $name;
            $row[] = $col;
        }

        $data = [
            "results" => $row
        ];

        return json_encode($data);
    }

    public function delete($id){
        $emp = Hrd_employee::where('id',$id)->first();
        $pict_path = "/hrd/uploads/".$emp->picture;
        $ktp_path = "/hrd/uploads/".$emp->ktp;
        $serti1_path = "/hrd/uploads/".$emp->serti1;
        if (File::exists($pict_path)){
            File::delete($pict_path);
        }
        if (File::exists($ktp_path)){
            File::delete($ktp_path);
        }
        if (File::exists($serti1_path)){
            File::delete($serti1_path);
        }
        Hrd_employee::find($id)->delete();
        return redirect()->route('employee.index');
    }

    public function update(Request $request,$id){
        $emp_sister = null;
        if(isset($request->emp_sister)){
            $emp_sister = $request->emp_sister;
        }
        $office = null;
        if(isset($request->office)){
            $office = $request->office;
        }
        Hrd_employee::where('id',$id)
            ->update([
                'emp_name' => $request->input('emp_name'),
                'email' => $request->input('email'),
                'address' => $request->input('address'),
                'religion' => $request->input('religion'),
                'emp_lahir' => $request->input('lahir'),
                'phone' => $request->input('phone'),
                'phone2' => $request->input('phone2'),
                'phoneh' => $request->input('phoneh'),
                'bank_code' => $request->input('bankCode'),
                'bank_acct' => $request->input('bank_acct'),
                'emp_id' => $request->input('emp_id'),
                'emp_position' => $request->input('emp_position'),
                'emp_type' => $request->input('emp_type'),
                'division' => $request->input('division'),
                'id_wh' => $office,
                'emp_id_sister' => $emp_sister
            ]);

        $param = $request->param;
        if(!empty($param)){
            foreach ($param as $key => $value) {
                $detail_variables = Master_var_emp::find($key);
                if(emptY($detail_variables)){
                    $detail_variables = new Master_var_emp();
                    $detail_variables->created_by = Auth::user()->username;
                    $detail_variables->company_id = Session::get('company_id');
                }
                $detail_variables->id_var = $key;
                $detail_variables->id_emp = $id;
                $detail_variables->values = $value;
                $detail_variables->updated_by = Auth::user()->username;
                $detail_variables->save();
            }
        }

        if(!empty($request->user_emp)){
            $user = User::find($request->user_emp);
            $user->emp_id = $id;
            if(empty($user->attend_code)){
                $code = random_int(100000, 999999);
                $attend_codeExist = User::where("attend_code", $code)->first();
                while(!empty($attend_codeExist)){
                    $code = random_int(100000, 999999);
                    $attend_codeExist = User::where("attend_code", $code)->first();
                }

                $user->attend_code = $code;
            }
            // $user->absence = $request->radabsen;
            $user->save();
        } else {
            $user = User::where('emp_id', $id)->first();
            if(!empty($user)){
                $user->emp_id = null;
                // $user->absence = 0;
                $user->save();
            }
        }

        return redirect()->route('employee.detail',['id'=>$id]);
    }

    public function updateAttach(Request $request,$id){
        Artisan::call('cache:clear');

        $employee = Hrd_employee::find($id);
        $employee = Hrd_employee::where('id',$id)->first();

        if ($request->hasFile('picture')){
            $file = $request->file('picture');

            $newFile = $employee->emp_id."-picture(".date('YmdHis').").".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/employee_attachment");
            if ($upload == 1){
                Hrd_employee::where('id',$id)
                    ->update([
                        'picture' => $newFile,
                    ]);
            }
        }

        if(isset($request->delete_picture)){
            Hrd_employee::where('id',$id)
                    ->update([
                        'picture' => null,
                    ]);
        }

        if(isset($request->delete_ktp)){
            Hrd_employee::where('id',$id)
                    ->update([
                        'ktp' => null,
                    ]);
        }

        if(isset($request->delete_sertif)){
            Hrd_employee::where('id',$id)
                    ->update([
                        'serti1' => null,
                    ]);
        }

        if ($request->hasFile('ktp')){
            $file = $request->file('ktp');

            $newFile = $employee->emp_id."-ktp(".date('YmdHis').").".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/employee_attachment");
            if ($upload == 1){
                Hrd_employee::where('id',$id)
                    ->update([
                        'ktp' =>$newFile,
                    ]);
            }
        }

        if ($request->hasFile('serti1')){
            $file = $request->file('serti1');

            $newFile = $employee->emp_id."-serti1(".date('YmdHis').").".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/employee_attachment");
            if ($upload == 1){
                Hrd_employee::where('id',$id)
                    ->update([
                        'serti1' =>$newFile,
                    ]);
            }
        }

        return redirect()->route('employee.detail',['id'=>$id]);
    }

    public function updateJoinDate(Request $request, $id){
        Hrd_employee_history::where('emp_id',$id)
            ->where('activity', 'in')
            ->update([
                'act_date' => $request['date']
            ]);
        return redirect()->route('employee.detail',['id'=>$id]);
    }

    public function updateFinMan(Request $request){
        $thp       = $request->input('thp');
        $SAL       = intval($thp*0.4);
        $HEALTH    = intval($thp*0.15);
        $TRANSPORT = intval($thp*0.15);
        $MEAL      = intval($thp*0.20);
        $HOUSE     = intval($thp*0.10);

        Hrd_employee::where('id',$request['id'])
            ->update([
                'salary' => base64_encode($SAL),
                'transport' => base64_encode($TRANSPORT),
                'meal' => base64_encode($MEAL),
                'house' => base64_encode($HOUSE),
                'health' =>base64_encode($HEALTH),
                'fld_bonus' => ($request->input('field_rate')) ? $request->input('field_rate') : 0,
                'wh_bonus' => ($request->input('wh_rate')) ? $request->input('wh_rate') : 0,
                'odo_bonus' => ($request->input('odo_rate')) ? $request->input('odo_rate') : 0,
                'pension' => ($request->input('pensi')) ? $request->input('pensi') : 0,
                'health_insurance' => ($request->input('hi')) ? $request->input('hi') : 0,
                'jamsostek' => ($request->input('jam')) ? $request->input('jam') : 0,
                'overtime' => $request->input('overtime'),
                'voucher' => $request->input('voucher'),
                'yearly_bonus' => ($request->input('yb')) ? $request->input('yb') : 0,
                'allowance_office' => ($request->input('pa')) ? $request->input('pa') : 0,
                'dom_meal' => ($request->input('dom_meal')) ? $request->input('dom_meal') : 0,
                'dom_spending' => ($request->input('dom_spending')) ? $request->input('dom_spending') : 0,
                'dom_overnight' => ($request->input('dom_overnight')) ? $request->input('dom_overnight') : 0,
                'dom_transport_airport' => ($request->input('dom_transport_airport')) ? $request->input('dom_transport_airport') : 0,
                'dom_transport_train' => ($request->input('dom_transport_train')) ? $request->input('dom_transport_train') : 0,
                'dom_transport_bus' => ($request->input('dom_transport_bus')) ? $request->input('dom_transport_bus') : 0,
                'dom_transport_cil' => ($request->input('dom_transport_cil')) ? $request->input('dom_transport_cil') : 0,
                'ovs_meal' => ($request->input('ovs_meal')) ? $request->input('ovs_meal') : 0,
                'ovs_spending' => ($request->input('ovs_spending')) ? $request->input('ovs_spending') : 0,
                'ovs_overnight' => ($request->input('ovs_overnight')) ? $request->input('ovs_overnight') : 0,
                'ovs_transport_airport' => ($request->input('ovs_transport_airport')) ? $request->input('ovs_transport_airport') : 0,
                'ovs_transport_train' => ($request->input('ovs_transport_train')) ? $request->input('ovs_transport_train') : 0,
                'ovs_transport_bus' => ($request->input('ovs_transport_bus')) ? $request->input('ovs_transport_bus') : 0,
                'ovs_transport_cil' => ($request->input('ovs_transport_cil')) ? $request->input('ovs_transport_cil') : 0,
            ]);
        return redirect()->route('employee.detail',['id'=>$request['id']]);
    }

    public function updateInsurance(Request $request){
        Hrd_employee::where('id',$request['id'])
            ->update([
                'allow_bpjs_tk' => ($request->input('allow_bpjs_tk')) ? $request->input('allow_bpjs_tk') : 0,
                'deduc_bpjs_tk' => ($request->input('deduc_bpjs_tk')) ? $request->input('deduc_bpjs_tk') : 0,
                'allow_bpjs_kes' => ($request->input('allow_bpjs_kes')) ? $request->input('allow_bpjs_kes') : 0,
                'deduc_bpjs_kes' => ($request->input('deduc_bpjs_kes')) ? $request->input('deduc_bpjs_kes') : 0,
                'allow_jshk' => ($request->input('allow_jshk')) ? $request->input('allow_jshk') : 0,
                'deduc_jshk' => ($request->input('deduc_jshk')) ? $request->input('deduc_jshk') : 0,
                'deduc_pph21' => ($request->input('deduc_pph21')) ? $request->input('deduc_pph21') : 0,
            ]);
        return redirect()->route('employee.detail',['id'=>$request['id']]);
    }

    public function generate_data($id){
        $emp = Hrd_employee::where('company_id', $id)
            ->orderBy('old_id')
            ->get();
        foreach ($emp as $value){
            if (!empty($value['old_id'])){
                $query = "UPDATE employee SET ";
                $query .= "dom_meal = '".$value['dom_meal']."', dom_spending = '".$value['dom_spending']."', dom_overnight = '".$value['dom_overnight']."', ";
                $query .= "ovs_meal = '".$value['ovs_meal']."', ovs_spending = '".$value['ovs_spending']."', ovs_overnight = '".$value['ovs_overnight']."', ";
                $query .= "dom_transport_airport = '".$value['dom_transport_airport']."', dom_transport_bus = '".$value['dom_transport_bus']."', dom_transport_cil = '".$value['dom_transport_cil']."', dom_transport_train = '".$value['dom_transport_train']."', ";
                $query .= "ovs_transport_airport = '".$value['ovs_transport_airport']."', ovs_transport_bus = '".$value['ovs_transport_bus']."', ovs_transport_cil = '".$value['ovs_transport_cil']."', ovs_transport_train = '".$value['ovs_transport_train']."' ";
                $query .= " where id = '".$value['old_id']."' ; <br>";
                echo $query;
            }
        }
    }

    // CV
    function cv(Request $request){
        dd($request);
        $cv = new Hrd_cv();
        $cv->emp_id = $request->emp_id;
        $cv->description = $request->description;
        $cv->start_date = $request->start_date;
        $cv->type = $request->type;
        $cv->end_date = $request->end_date;
        $cv->created_by = Auth::user()->username;
        $cv->company_id = Session::get('company_id');
        if ($request->file('document')){
            // do the upload file
            $file = $request->file('document');
            $filename = explode(".", $file->getClientOriginalName());
            array_pop($filename);
            $filename = str_replace(" ", "_", implode("_", $filename));

            $newFile = "(CV)".$filename."-".date('Y_m_d_H_i_s')."(".$request->emp_id.").".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);
            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "hrd\uploads");
            if ($upload == 1){
                $cv->document = $hashFile;
            }
        }

        if ($cv->save()){
            return redirect(route('employee.detail',['id'=>$request->emp_id])."#cv-management");
        }
    }

    function cv_delete($id){
        $cv = Hrd_cv::find($id);
        $empId = $cv->emp_id;

        if ($cv->delete()){
            return redirect(route('employee.detail',['id'=>$empId])."#cv-management");
        }
    }

    function cv_print($id){
        $emp = Hrd_employee::find($id);
        $cv = Hrd_cv::where('emp_id', $id)->get();
        $company = ConfigCompany::find($emp->company_id);

        return view('employee.print_cv', [
            "emp" =>$emp,
            "cv" =>$cv,
            'company' => $company
        ]);
    }

    function storeVaccine(Request $request){
        date_default_timezone_set('Asia/Jakarta');
        if ($request->hasFile('document')){
            $file = $request->file('document');

            $newFile = $file->getClientOriginalName();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/employee_attachment");
            if ($upload == 1){
                $hrd_cv = new Hrd_cv_u();
                $hrd_cv->user_id = $request['id_emp'];
                $hrd_cv->date_time = $request['_date'];
                $hrd_cv->vaccine = 1;
                $hrd_cv->vaccine_i = $request->_count;
                $hrd_cv->vaccine_type = $request->_type;
                $hrd_cv->vaccine = 1;
                $hrd_cv->cv_address = $hashFile;
                $hrd_cv->cv_name = $newFile;
                $hrd_cv->created_by = Auth::user()->username;
                $hrd_cv->company_id = Session::get('company_id');
                $hrd_cv->save();
            }
        }
        return redirect()->back();
    }

    // BPJS

    function bpjs(){

        $emp = Hrd_Employee::where('company_id', Session::get('company_id'))
            ->whereNull('expel')
            ->where(function($query){
                $query->WhereRaw('(allow_bpjs_kes != 0.00)');
                $query->orWhereRaw('(deduc_bpjs_kes != 0.00)');
            })
            ->orderBy('emp_name')
            ->get();

        return view("employee.bpjs", compact('emp'));
    }

    function bpjs_tk(){

        $emp = Hrd_Employee::where('company_id', Session::get('company_id'))
            ->whereNull('expel')
            ->where(function($query){
                $query->WhereRaw('(allow_bpjs_tk != 0)');
                $query->orWhereRaw('(deduc_bpjs_tk != 0)');
            })
            ->orderBy('emp_name')
            ->get();

        return view("employee.bpjs_tk", compact('emp'));
    }

    function disable_ppe(Request $request){
        $ppe = Hrd_employee_ppe::where("emp_id", $request->emp_id)->first();

        $data = [
            "success" => true
        ];

        if(!empty($ppe)){
            if($ppe->enable == 1){
                $ppe->enable = 0;
            } else {
                $ppe->enable = 1;
            }

            if($ppe->save()){
                $data = [
                    "success" => true,
                    "enable" => $ppe->enable
                ];
            } else {
                $data = [
                    "success" => false
                ];
            }
        }

        return json_encode($data);
    }

    function generate_ppe(Request $request){
        if($request->ajax()){
            $data = [];
            $ppe = Hrd_employee_ppe::where("emp_id", $request->emp_id)
                ->first();
            if(empty($ppe)){
                try {
                    $link = "";
                    $url = 'https://api-ssl.bitly.com/v4/shorten';
                    $ch = curl_init($url);
                    $post = [];
                    $post['domain'] = "bit.ly";
                    $post['long_url'] = route('hrd.ppe', $request->emp_id);

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
                        $emp = Hrd_employee::find($request->emp_id);
                        $link = $js['link'];
                        $ppe = new Hrd_employee_ppe();
                        $ppe->emp_id = $request->emp_id;
                        $ppe->link = $link;
                        $ppe->created_by = Auth::user()->username;
                        $ppe->company_id = $emp->company_id;
                        if($ppe->save()){
                            $data = [
                                "success" => true,
                                "link" => $ppe->link
                            ];
                        }
                    } else {
                        $data = [
                            "success" => false,
                        ];
                    }
                } catch (\Throwable $th) {
                    $data = [
                        "success" => false,
                        "message" => $th->getMessage()
                    ];
                }
            } else {
                $data = [
                    "success" => true,
                    "link" => $ppe->link
                ];
            }

            return json_encode($data);
        }
    }
}

