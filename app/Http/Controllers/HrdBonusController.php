<?php

namespace App\Http\Controllers;

use foo\Foo;
use Illuminate\Http\Request;
use App\Models\Hrd_employee;
use App\Models\Hrd_bonus;
use App\Models\Hrd_bonus_payment;
use DB;
use Illuminate\Support\Facades\Auth;
use Session;

class HrdBonusController extends Controller
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
        $bonus = DB::table('hrd_bonus')
            ->select('hrd_bonus.*', 'employee.emp_name as emp_name')
            ->join('hrd_employee as employee','employee.id','=','hrd_bonus.emp_id')
            ->whereIn('employee.company_id', $id_companies)
            ->whereNull('hrd_bonus.deleted_at')
            ->whereNull('employee.expel')
            ->orderBy('date_given','DESC')
            ->get();

        $bonus_payment = Hrd_bonus_payment::orderBy('date_of_payment','DESC')
            ->whereNull('hrd_bonus_payment.deleted_at')
            ->get();
        $employees = Hrd_employee::whereIn('company_id', $id_companies)
            ->whereNull('expel')
            ->whereNull('deleted_at')
            ->orderBy('emp_name')
            ->get();
        return view('bonus.index',[
            'employees' => $employees,
            'bonus' => $bonus,
            'payments' => $bonus_payment,
        ]);
    }



    public function nextDocNumber($code,$db){
        if ($db == "bonus"){

            $cek = Hrd_bonus::where('bonusID','like','%'.$code.'%')
                ->where('company_id', Session::get('company_id'))
                ->whereNull('deleted_at')
                ->orderBy('id','DESC')
                ->get();


            if (count($cek) > 0){
                $bonusId = $cek[0]->bonusID;
                $str = explode('/', $bonusId);
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

            $cek = Hrd_bonus_payment::where('payment_id','like','%'.$code.'%')
                ->where('company_id', Session::get('company_id'))
                ->whereNull('deleted_at')
                ->orderBy('id','DESC')
                ->get();
//            dd($cek);
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

    public function addSubsidies(Request $request){
        $this->validate($request,[
            'emp_name' => 'required',
            'start' => 'required',
            'end'=> 'required',
            'amount' => 'required'
        ]);
        $arrRomawi	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $bonus_num = $this->nextDocNumber(strtoupper(\Session::get('company_tag'))."/BN","bonus");
        $bonusID = str_pad($bonus_num, 3, '0', STR_PAD_LEFT).'/'.strtoupper(\Session::get('company_tag')).'/BN/'.$arrRomawi[date("n")].'/'.date("y");

        $bonus = new Hrd_bonus();
        $bonus->bonusID = $bonusID;
        $bonus->emp_id = $request['emp_name'];
        $bonus->bonus_amount = $request['amount'];
        $bonus->bonus_start = $request['start'];
        $bonus->bonus_end = $request['end'];
        $bonus->notes = ($request['notes']!=null) ? $request['notes']:'';
        $bonus->given_by = Auth::user()->username;
        $bonus->given_time = date('Y-m-d H:i:s');
        $bonus->date_given = date('Y-m-d');
        $bonus->company_id = \Session::get('company_id');
        $bonus->save();

        if (isset($request['autopay'])){
            list($d1, $m1, $y1) = explode('-', $request['start']);
            list($d2, $m2, $y2) = explode('-', $request['end']);

            $bonusStart = sprintf("%s-%02s-%02s", $y1, $m1, $d1);
            $bonusEnd = sprintf("%s-%02s-%02s", $y2, $m2, $d2);
            $monthDiff = $this->monthDiff($bonusStart, $bonusEnd);


            for ($i = 0; $i < $monthDiff; $i++){
                $payment_num = $this->nextDocNumber(strtoupper(\Session::get('company_tag'))."/BNPAY","bonus_payment");
                $id_bonus = $bonus->id;
                $payment_id = str_pad($payment_num, 3, '0', STR_PAD_LEFT).'/'.strtoupper(\Session::get('company_tag')).'/BNPAY/'.$arrRomawi[date("n")].'/'.date("y");
                $date_of_payment_repeat = strtotime($bonusStart);
                $dates = date('Y-m-d', strtotime("+".$i." month", $date_of_payment_repeat));
                $dates2 = explode('-',$dates);
                $date_of_payment = $dates2[0].'-'.$dates2[1].'-17';
//                echo $date_of_payment."<br>";
                $amount = $request['amount']/$monthDiff;

                $bonus_pay = new Hrd_bonus_payment();
                $bonus_pay->bonus_id = $id_bonus;
                $bonus_pay->amount = round($amount);
                $bonus_pay->payment_id = $payment_id;
                $bonus_pay->date_of_payment = $date_of_payment;
                $bonus_pay->remark = 'insert by autopay';
                $bonus_pay->receive_by = Auth::user()->username;
                $bonus_pay->receive_time = date('Y-m-d H:i:s');
                $bonus_pay->company_id = \Session::get('company_id');

                $bonus_pay->save();
            }
        }
       return redirect()->route('subsidies.index');
    }

    public function delete($id){
        Hrd_bonus::find($id)->delete();
        Hrd_bonus_payment::where('bonus_id', $id)->delete();
        return redirect()->route('subsidies.index');

    }

    public function getDetailBonus($id){
        $bonus = Hrd_bonus::where('id',$id)
            ->whereNull('deleted_at')
            ->first();
        $emp = Hrd_employee::select('emp_name')
            ->where('id', $bonus->emp_id)
            ->where('company_id', \Session::get('company_id'))
            ->whereNull('expel')
            ->whereNull('deleted_at')
            ->first();
        $bonus_balance = intval($bonus->bonus_amount);

        $bonus_payments = Hrd_bonus_payment::where('bonus_id', $id)
            ->whereNull('deleted_at')
            ->get();

        foreach ($bonus_payments as $key => $val){
            $bonus_balance -= intval($val->amount);
        }

        return view('bonus.payment',[
            'emp' => $emp,
            'payments' => $bonus_payments,
            'balance' => $bonus_balance,
            'bonus' => $bonus
        ]);
    }

    public function storePayment(Request $request){
        $this->validate($request,[
            'amount' => 'required',
            'date_of_payment' => 'required',
        ]);

        $arrRomawi	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $payment_num = $this->nextDocNumber(strtoupper(\Session::get('company_tag'))."/BNPAY","bonus_payment");
        $id_bonus = $request['bonus_id'];
        $payment_id = str_pad($payment_num, 3, '0', STR_PAD_LEFT).'/'.strtoupper(\Session::get('company_tag')).'/BNPAY/'.$arrRomawi[date("n")].'/'.date("y");
        $bonus_pay = new Hrd_bonus_payment();
        $bonus_pay->amount = $request['amount'];
        $bonus_pay->payment_id = $payment_id;
        $bonus_pay->bonus_id = $id_bonus;
        $bonus_pay->date_of_payment = $request['date_of_payment'];
        $bonus_pay->receive_by = Auth::user()->username;
        $bonus_pay->receive_time = date('Y-m-d H:i:s');
        $bonus_pay->remark = 'insert by '.Auth::user()->username;
        $bonus_pay->save();
//        echo $request['bonus_id'];

        return redirect()->route('subsidies.payment',[$request['bonus_id']]);
    }
}
