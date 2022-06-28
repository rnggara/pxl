<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hrd_employee;
use App\Models\Hrd_sanction;
use App\Models\Hrd_sanction_payment;
use DB;
use Illuminate\Support\Facades\Auth;
use Session;

class HrdSanctionController extends Controller
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
        $sanction = DB::table('hrd_sanction')
            ->select('hrd_sanction.*', 'employee.emp_name as emp_name')
            ->leftJoin('hrd_employee as employee','employee.id','=','hrd_sanction.emp_id')
            ->whereIn('employee.company_id', $id_companies)
            ->whereNull('hrd_sanction.deleted_at')
            ->whereNull('employee.expel')
            ->orderBy('date_given','DESC')
            ->get();


        $employees = Hrd_employee::whereIn('company_id', $id_companies)
            ->whereNull('expel')
            ->whereNull('deleted_at')
            ->orderBy('emp_name')
            ->get();

        return view('sanction.index',[
            'employees' => $employees,
            'sanction' => $sanction,
        ]);
    }

    public function nextDocNumber($code,$db){

        if ($db == "sanction"){
            $cek = Hrd_sanction::where('sanctionID','like','%'.$code.'%')
                ->where('company_id', Session::get('company_id'))
                ->whereNull('deleted_at')
                ->orderBy('id','DESC')
                ->get();

            if (count($cek) > 0){
                $sanctionId = $cek[0]->sanctionID;
                $str = explode('/', $sanctionId);
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
            $cek = Hrd_sanction_payment::where('payment_id','like','%'.$code.'%')
                ->where('company_id', Session::get('company_id'))
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

    public function addDeduction(Request $request){
        $this->validate($request,[
            'emp_name' => 'required',
            'date' => 'required',
            'amount' => 'required'
        ]);
        $arrRomawi	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $sanction_num = $this->nextDocNumber(strtoupper(\Session::get('company_tag'))."/SA","sanction");
        $sanctionID = str_pad($sanction_num, 3, '0', STR_PAD_LEFT).'/'.strtoupper(\Session::get('company_tag')).'/SA/'.$arrRomawi[date("n")].'/'.date("y");

        $sanction = new Hrd_sanction();
        $sanction->sanctionID = $sanctionID;
        $sanction->emp_id = $request['emp_name'];
        $sanction->sanction_amount = str_replace(",", "", $request['amount']);
        $sanction->sanction_date = $request['date'];
        $sanction->notes = ($request['notes']!=null) ? $request['notes']:'';
        $sanction->given_by = Auth::user()->username;
        $sanction->given_time = date('Y-m-d H:i:s');
        $sanction->date_given = date('Y-m-d');
        $sanction->company_id = \Session::get('company_id');
        $sanction->save();

        return redirect()->route('sanction.index');
    }

    public function delete($id){
        Hrd_sanction::find($id)->delete();
        // Hrd_sanction_payment::where('sanction_id', $id)->delete();
        return redirect()->route('sanction.index');

    }

    public function approveDeduction(Request $request,$id){
        if (isset($request['approve']) && $request['approve'] == 1){
            Hrd_sanction::where('id', $id)
                ->update([
                    'approved_by' => Auth::user()->username,
                    'approved_time' => date('Y-m-d H:i:s'),
                ]);
        }

        return redirect()->route('sanction.index');
    }

}
