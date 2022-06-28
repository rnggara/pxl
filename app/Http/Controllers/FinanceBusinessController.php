<?php

namespace App\Http\Controllers;

use App\Models\Finance_business;
use App\Models\Finance_business_detail;
use App\Models\ConfigCompany;
use App\Models\Finance_business_investor_detail;
use App\Models\Finance_business_investors;
use App\Models\Finance_business_master_investors;
use App\Models\Finance_business_master_partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class FinanceBusinessController extends Controller
{
    function index(){
        $business = Finance_business::where('company_id', Session::get('company_id'))
            ->get();

        $partners = Finance_business_master_partner::all()->pluck('name', 'id');
        return view('finance.business.index', [
            'business' => $business,
            'partners' => $partners
        ]);
    }

    function add(Request $request){
        $nBusiness = new Finance_business();
        $nBusiness->bank = $request->prj_name;
        // $nBusiness->description = $request->partner_name;
        $nBusiness->partner = $request->partner_name;
        $nBusiness->value = str_replace(",", "", $request->amount);
        $nBusiness->bunga = str_replace(",", "", $request->percentage);
        $nBusiness->start = $request->start_at;
        $nBusiness->moneydrop = $request->given_at;
        $nBusiness->period = $request->duration;
        $nBusiness->type = $request->proportional;
        $nBusiness->currency = $request->currency;
        $nBusiness->cicil_start = $request->cicil_start;
        $nBusiness->own_amount = str_replace(",", "", $request->own_amount);
        $nBusiness->own_remarks = $request->own_remarks;
        $nBusiness->account_info = $request->account_info;
        $nBusiness->company_id = Session::get('company_id');
        $nBusiness->created_by = Auth::user()->username;
        $nBusiness->save();

        $cicil_pokok = str_replace(",", "", $request->amount) / $request->duration;
        $bungaAmt = $cicil_pokok * (str_replace(",", "", $request->percentage) / 100);
        $cicilAmt = $cicil_pokok + $bungaAmt;

        $datetime1 = date_create($request->given_at);
        $datetime2 = date_create($request->start_at);

        $interval = date_diff($datetime1, $datetime2);

        $datediff = $interval->format('%a');

        if($request->proportional == "PRO"){
            $periodSum = $request->duration + 1;
            $n[0] = ((str_replace(",", "", $request->amount) / $request->duration) * ($datediff/30));
        } else { // LUMPSUM
            $periodSum = $request->duration;
            $n[0] = ((str_replace(",", "", $request->amount) / $request->duration));
        }

        $bungaMulti = (1 + str_replace(",", "", $request->percentage) / 100);
        $bungaRate = str_replace(",", "", $request->percentage);
        $balanceNow = str_replace(",", "", $request->amount);

        list($yStart,$mStart,$dStart) = explode("-",$request->start_at);
        for($i = 0; $i < $periodSum; $i++){
            $cicilDraft = (str_replace(",", "", $request->amount) / $request->duration);
            if($i == 0)	{
                $cicilNow = $n[0];
            } elseif($balanceNow >= $cicilDraft) {
                $cicilNow = $cicilDraft;
            } elseif($balanceNow < $cicilDraft) {
                $cicilNow = $balanceNow;
            } else {
                $cicilNow = 0;
            }
            // $cicilNow = floor($cicilNow);
            $cicilNow = round($cicilNow,0,PHP_ROUND_HALF_UP);
            $balanceNow = $balanceNow - $cicilNow;
            $bungaNow = str_replace(",", "", $request->amount) * ($bungaMulti - 1);
            $bungaNow = round($bungaNow,0,PHP_ROUND_HALF_UP);
            $tanggalNow = $yStart."-".(str_pad($mStart, 2, "0", STR_PAD_LEFT))."-".$dStart;
            $nDetail = new Finance_business_detail();
            $nDetail->id_business = $nBusiness->id;
            $nDetail->cicilan = $cicilNow;
            $nDetail->bunga_rate = $bungaRate;
            $nDetail->bunga = $bungaNow;
            $nDetail->status = 'Planned';
            $nDetail->n_cicil = ($i+1);
            $nDetail->plan_date = $tanggalNow;
            $nDetail->company_id = Session::get('company_id');
            $nDetail->created_by = Auth::user()->username;
            $nDetail->save();

            $mStart++;
            if($mStart > 12){
                $mStart = 1;
                $yStart++;
            }
        }

        return redirect()->back();
    }

    function edit($id){
        $business = Finance_business::find($id);
        $partners = Finance_business_master_partner::all()->pluck('name', 'id');
        return view('finance.business.edit', [
            'business' => $business,
            'partners' => $partners
        ]);
    }

    function update(Request $request){
        $nBusiness = Finance_business::find($request->id_business);
        $nBusiness->bank = $request->prj_name;
        $nBusiness->description = $request->partner_name;
        $nBusiness->value = str_replace(",", "", $request->amount);
        $nBusiness->bunga = str_replace(",", "", $request->percentage);
        $nBusiness->start = $request->start_at;
        $nBusiness->moneydrop = $request->given_at;
        $nBusiness->period = $request->duration;
        $nBusiness->type = $request->proportional;
        $nBusiness->currency = $request->currency;
        $nBusiness->cicil_start = $request->cicil_start;
        $nBusiness->own_amount = $request->own_amount;
        $nBusiness->own_remarks = $request->own_remarks;
        $nBusiness->account_info = $request->account_info;
        $nBusiness->save();

        $cicil_pokok = str_replace(",", "", $request->amount) / $request->duration;
        $bungaAmt = str_replace(",", "", $request->amount) * (str_replace(",", "", $request->percentage) / 100);

        $datetime1 = date_create($request->given_at);
        $datetime2 = date_create($request->start_at);

        $interval = date_diff($datetime1, $datetime2);

        $datediff = $interval->format('%a');

        if($request->proportional == "PRO"){
            $n[0] = ((str_replace(",", "", $request->amount) / $request->duration) * ($datediff/30));
        } else { // LUMPSUM
            $n[0] = ((str_replace(",", "", $request->amount) / $request->duration));
        }

        Finance_business_detail::where('id_business', $request->id_business)
            ->update([
                'cicilan' => $n[0],
                'bunga_rate' => str_replace(",", "", $request->percentage),
                'bunga' => $bungaAmt
            ]);

        return redirect()->back();
    }

    function delete($id){
        Finance_business::find($id)->delete();
        Finance_business_detail::where('id_business')->delete();
        $data['error'] = 0;
        return json_encode($data);
    }

    function addInvestor(Request $request){
        $business = Finance_business::find($request->id_business);
        $json = (empty($business->investors)) ? array() : json_decode($business->investors);
        $detail['name'] = $request->investor_name;
        $detail['amount'] = str_replace(",", "", $request->amount);
        $detail['percentage'] = str_replace(",", "", $request->profit_rate);
        $json[] = $detail;

        $business->investors = json_encode($json);
        $business->save();

        return redirect()->back();
    }

    function updateRate(Request $request){
        $business = Finance_business::find($request->business);
        if ($request->type == "company"){
            $business->own_percent = str_replace(",", "", $request->profit_rate);
        } else {
            $investor = json_decode($business->investors);
            $investor[$request->index]->percentage = str_replace(",", "", $request->profit_rate);
            $business->investors = json_encode($investor);
        }
        $business->save();
        return redirect()->back();
    }

    function updateAmount(Request $request){
        $business = Finance_business::find($request->business);
        $investor = json_decode($business->investors);
        $amount = str_replace(",", "", $request->amount) - $investor[$request->index]->amount;
        $key = 0;
        if(isset($investor[$request->index]->payments)){
            $key = count($investor[$request->index]->payments);
        }
        $row['amount'] = $amount;
        $row['month'] = $key;
        //$investor[$request->index]->update[] = $row;
        $investor[$request->index]->amount = str_replace(",", "", $request->amount);
        $business->investors = json_encode($investor);
        $business->save();
        return redirect()->back();
    }

    function updateText(Request $request){
        $business = Finance_business::find($request->business);
        $investor = json_decode($business->investors);
        $investor[$request->index]->unusedText = $request->unusedText;

        $business->investors = json_encode($investor);
        $business->save();

        return redirect()->back();
    }

    function addInvesment(Request $request){
        $business = Finance_business::find($request->business);
        if ($request->type == "company"){
            $company = json_decode($business->company);
            $detail['currency'] = $request->currency;
            $detail['amount'] = str_replace(",", "", $request->amount);
            $idr = str_replace(",", "", $request->amount) * str_replace(",", "", $request->rate);
            $detail['IDR'] = $idr;
            $detail['exchange'] = str_replace(",", "", $request->rate);
            $company[] = $detail;
            $business->company = json_encode($company);
        } else {
            $investor = json_decode($business->investors);
            $detail['currency'] = $request->currency;
            $detail['amount'] = str_replace(",", "", $request->amount);
            $idr = str_replace(",", "", $request->amount) * str_replace(",", "", $request->rate);
            $detail['IDR'] = $idr;
            $detail['exchange'] = str_replace(",", "", $request->rate);
            $investor[$request->index]->details[] = $detail;
            $business->investors = json_encode($investor);
        }
        $business->save();
        return redirect()->back();
    }

    function detail($id){
        $business = Finance_business::find($id);
        $detail = Finance_business_detail::where('id_business', $id)->orderBy('n_cicil')->get();
        $field = ["month#", "payment_date", "interest_rate", "balance", "installment", "profit", "penalty", "total_amount", "grand_total"];
        $admin = 0;
        $ownInvest = $business->value;
        $interest    = $business->bunga;
        $duration    = $business->period;
        $admin = 0;
        if (!empty($business->investors)){
            foreach (json_decode($business->investors) as $key => $value){
                $inv_amount = $value->amount;
                // $ownInvest -= $value->amount;
                $inv_rate   = $value->percentage;
                // $v_a = floor($inv_amount+$inv_amount * ($interest / 100) * $duration);
                // $v_b = floor(intval($inv_amount)+intval($inv_amount) * (intval($inv_rate) / 100) * intval($duration));
                // $admin += ($v_a - $v_b) / $duration;
            }
        }

        // admin company
        if(!$business->own_percent) { $business->own_percent = $interest; }
        $v_a = floor($ownInvest+($ownInvest * ($interest / 100) * $duration));
        $v_b = floor($ownInvest+($ownInvest * ($business->own_percent / 100) * $duration));
        $admin = ($v_a - $v_b) / $duration;
        // $admin = $business->own_percent;

        // $sumAdmin = $admin * $duration;
        // dd($sumAdmin);

        $partners = Finance_business_master_partner::all()->pluck('name', 'id');

        return view('finance.business.detail', [
            'business' => $business,
            'details' => $detail,
            'fields' => $field,
            'administration' => $admin,
            // 'sum_admin' => $sumAdmin,
            'partner' => $partners
        ]);
    }

    function detail_edit($id, Request $request){
        $business = Finance_business::find($id);
        $detail = Finance_business_detail::where('id_business', $id)->orderBy('n_cicil')->get();
        $field = ["month#", "payment_date", "interest_rate", "balance", "installment", "profit", "penalty", "total_amount", "grand_total"];
        $admin = 0;
        $ownInvest = $business->value;
        $interest    = $business->bunga;
        $duration    = $business->period;
        $admin = 0;
        if (!empty($business->investors)){
            foreach (json_decode($business->investors) as $key => $value){
                $inv_amount = $value->amount;
                // $ownInvest -= $value->amount;
                $inv_rate   = $value->percentage;
                // $v_a = floor($inv_amount+$inv_amount * ($interest / 100) * $duration);
                // $v_b = floor(intval($inv_amount)+intval($inv_amount) * (intval($inv_rate) / 100) * intval($duration));
                // $admin += ($v_a - $v_b) / $duration;
            }
        }

        // admin company
        if(!$business->own_percent) { $business->own_percent = $interest; }
        $v_a = floor($ownInvest+($ownInvest * ($interest / 100) * $duration));
        $v_b = floor($ownInvest+($ownInvest * ($business->own_percent / 100) * $duration));
        $admin = ($v_a - $v_b) / $duration;
        // $admin = $business->own_percent;

        // $sumAdmin = $admin * $duration;
        // dd($sumAdmin);

        $partners = Finance_business_master_partner::all()->pluck('name', 'id');

        if($request->ajax()){
            $id_detail = $request->id_detail;
            $installment = $request->installments;
            for ($i=0; $i < count($id_detail); $i++) {
                $detail = Finance_business_detail::find($id_detail[$i]);
                $detail->cicilan = $installment[$i];
                $detail->save();
            }

            return json_encode(array(
                "success" => true
            ));
        }

        return view('finance.business.edit_detail', [
            'business' => $business,
            'details' => $detail,
            'fields' => $field,
            'administration' => $admin,
            // 'sum_admin' => $sumAdmin,
            'partner' => $partners
        ]);
    }

    function investor($id){
        $business = Finance_business::find($id);
        $detail = Finance_business_detail::where('id_business', $id)->get();
        $field = ["month#", "payment_date", "interest_rate", "balance", "installment", "profit", "penalty", "total_amount", "grand_total"];
        $fieldInvestor = ["month#", "payment_date", "interest_rate", "balance", "installment", "profit", "total_amount", "status"];

        return view('finance.business.investor', [
            'business' => $business,
            'details' => $detail,
            'fields' => $field,
            'fieldInvestor' => $fieldInvestor
        ]);
    }

    function pay($id){
        $detail = Finance_business_detail::find($id);

        return view('finance.business.pay', [
            'detail' => $detail
        ]);
    }

    function payConfirm(Request $request){
        $detail = Finance_business_detail::find($request->id);
        $detail->penalty_paid = str_replace(",", "", $request->penalty);
        $detail->status = "Paid";
        $detail->save();
        return redirect()->back();
    }

    function print($id, Request $request){
        $business = Finance_business::find($id);
        $detail = Finance_business_detail::where('id_business', $id)->get();
        $balance = $business->value;
        $total = 0;
        $nCicil = 0;
        $nProfit = 0;
        $nPenalty = 0;
        $field = json_decode(base64_decode($request->c));
        $row = array();
        foreach ($detail as $key => $item){
            $bunga = $item->cicilan + $item->bunga;
            if ($key == count($detail) - 1){
                $cicil = $balance;
            } else {
                $cicil = $item->cicilan;
            }
            $bunga1 = $bunga - $cicil;
            $total += $cicil + $bunga1;
            $data['month#'] = $key + 1;
            $data['payment_date'] = date("d F Y", strtotime($item->plan_date));
            $data['interest_rate'] = $item->bunga_rate;
            $data['balance'] = number_format($balance, 2)."-value";
            $data['installment'] = number_format($cicil, 2)."-value";
            $data['profit'] = number_format($bunga1, 2)."-value";
            $data['penalty'] = number_format($item->penalty_paid, 2)."-value";
            $data['total_amount'] = number_format($cicil + $bunga1, 2)."-value";
            $data['grand_total'] = number_format($total, 2)."-value";
            $row[] = $data;
            $balance -= $cicil;
            $nCicil += $cicil;
            $nProfit += $bunga;
            $nPenalty += $item->penalty_paid;
        }

        $total = [
            'installment' => number_format($nCicil, 2),
            'profit' => number_format($nProfit, 2),
            'penalty' => number_format($nPenalty, 2),
            'total' => number_format($total, 2)
        ];

        return view('finance.business.print', [
            'business' => $business,
            'details' => $detail,
            'fields' => $field,
            'data' => $row,
            'foot' => $total
        ]);
    }

    function deleteInvestor(Request $request){
        $business = Finance_business::find($request->b);
        $investor = json_decode($business->investors);
        array_splice($investor, $request->i, 1);
        $business->investors = json_encode($investor);
        $business->save();
        return redirect()->back();
    }

    function deleteInvesment(Request $request){
        $business = Finance_business::find($request->b);
        if ($request->t == "i"){
            $investor = json_decode($business->investors);
            $detail = $investor[$request->i]->details;
            array_splice($detail, $request->p, 1);
            $investor[$request->i]->details = $detail;
            $business->investors = json_encode($investor);
        } else {
            $detail = json_decode($business->company);
            array_splice($detail, $request->p, 1);
            $business->company = json_encode($detail);
        }

        $business->save();
        return redirect()->back();
    }

    function investorPay(Request $request){
        $business = Finance_business::find($request->b);
        if ($request->t == "i"){
            $investor = json_decode($business->investors);
            $i = $request->i;
            $investor[$i]->payments[$request->p] = date('Y-m-d');
            $business->investors = json_encode($investor);
        } else {
            $archive = json_decode($business->archive_by);
            $i = $request->i;
            $archive[$i] = date('Y-m-d');
            $business->archive_by = json_encode($archive);
        }

        $business->save();
        return redirect()->back();
    }

    function payment_schedule(){
        $mnth = array();
        for ($i=1; $i < 13; $i++) {
            $mnth[$i] = date('F', strtotime(date("Y")."-".$i));
        }

        $yearbefore = intval(date('Y')) - 5;
        $yearafter = intval(date('Y')) + 5;

        return view('finance.business.payment', compact('mnth', 'yearbefore', 'yearafter'));
    }

    function payment_search(Request $request){
        $plan_date = $request->year."-".sprintf("%02d", $request->mnth);

        $data_business = Finance_business::where('company_id', Session::get('company_id'))
            ->get();
        $business = array();
        foreach ($data_business as $value){
            $business[$value->id] = $value;
        }

        $business_detail = Finance_business_detail::where('plan_date', 'like', "$plan_date%")->get();
        $col = array();
        foreach ($business_detail as $key => $value) {
            // echo "<pre>".print_r($key,1 )."</pre>";
            $period = $key;
            if (isset($business[$value['id_business']])){
                $bs = $business[$value['id_business']];
                $investors = (!empty($bs['investors'])) ? json_decode($bs['investors']) : array();
                $duration = $bs['period'];
                $bunga    = $bs['bunga'];
                $amount   = $bs['value'];
                $type     = $bs['type'];
                $datetime1 = date_create($bs['moneydrop']);
                $datetime2 = date_create($bs['start']);
                $interval = date_diff($datetime1, $datetime2);
                $datediff = $interval->format('%a');

                $investOwn = $bs->value;
                $investInv = [];

                if (!empty($investors)) {
                    foreach ($investors as $investor) {
                        $additional = 0;
                        $per = $duration;
                        $amount2 = $investor->amount;
                        $investInv[] = $investor->amount;
                        $nci = $value['n_cicil'] - 1;

                        $balanceNow = $amount2;

                        $cicilDraft = floor(($amount2) / $per);
                        $bungaMulti = (1 + $investor->percentage / 100);

                        $bungaNow = $amount2 * ($bungaMulti - 1);
                        $balanceNow = round($balanceNow,0,PHP_ROUND_HALF_UP);

                        if($type == "PRO"){
                            $n = (($amount2 / $per) * ($datediff/30)) ;
                        } else { // LUMPSUM
                            $n = (($amount2 / $per) );
                        }
                        $cicilPaid = $cicilDraft * $nci;

                        $nni = 0;

                        if(isset($investor->additional)){
                            if (isset($investor->newKey) && $nci >= $investor->newKey) {
                                $balanceNow -= $cicilPaid;
                                $balanceNow += $investor->additional;
                                $per = $duration - $investor->newKey;
                                $amount2 += $investor->additional;
                                $cicilPaid = 0;
                                $cicilDraft = floor(($balanceNow) / $per);
                                $bungaNow = $amount2 * ($bungaMulti - 1);
                                $nni = $investor->newKey;
                            }
                        }

                        $cicilLeft = $balanceNow - $cicilPaid;
                        if ($balanceNow >= $cicilPaid) {
                            $cicilNow = $cicilDraft;
                        } elseif ($balanceNow < $cicilDraft) {
                            $cicilNow = $cicilLeft;
                        } elseif ($cicilLeft <= 0) {
                            $cicilNow = 0;
                        }

                        $bungaNow = round($bungaNow,0,PHP_ROUND_HALF_UP);
                        $cicilNow = round($cicilNow,0,PHP_ROUND_HALF_UP);
                        $row['nii'] = $nni;
                        $row['n_cicil'] = $nci;
                        $row['date'] = $value['plan_date'];
                        $row['amount'] = $cicilNow + $bungaNow;
                        $name = strtolower(str_replace(" ", "_", str_replace(".", "", $investor->name)));
                        $col[$value['plan_date']][$name]['name'] = $investor->name;
                        $col[$value['plan_date']][$name]['data'][$bs->id] = $row;
                    }
                }

                $investOwn = $investOwn - array_sum($investInv);
                $cicilanOwn = floor($investOwn / $bs->period);
                $bungaOwn = floor($investOwn * $bs->own_percent / 100);
                if ($value->n_cicil == ($bs->period)){
                    $cicilPaid = $cicilanOwn * $value->n_cicil;
                    $cicil = $investOwn - $cicilPaid;
                } else {
                    $cicil = $cicilanOwn;
                }

                $row = [];
                $row['date'] = $value['plan_date'];
                $row['amount'] = $cicil + $bungaOwn;
                $comp = ConfigCompany::find($bs->company_id);
                $name = $comp->company_name;
                $col[$value['plan_date']][$comp->tag]['name'] = $name;
                $col[$value['plan_date']][$comp->tag]['data'][$bs->id] = $row;
            }
        }

        return view('finance.business.payment_search', compact('col'));
    }

    function transfer(Request $request){

        $business = Finance_business::find($request->id_business);

        $investors = json_decode($business->investors);

        $from = $investors[$request->key_investor];

        $amount = str_replace(",", "", $request->amount);

        $to = (object)[];
        if (empty($request->inv_name)){
            $to = $investors[$request->to];
            $to->additional = $amount * 1;
            $to->newKey = intval($request->key_detail);
        } else {
            $to->amount = 0;
            $to->percentage = $request->profit_rate;
            $to->name = $request->inv_name;
            $to->additional = $amount * 1;
            $to->payments = $from->payments;
            $to->newKey = intval($request->key_detail);
            $investors[] = $to;
        }

        $from->additional = $amount * -1;
        $from->newKey = intval($request->key_detail);


        $business->investors = json_encode($investors);

        $business->save();

        return redirect()->back();
    }

    function balance(){
        $mnth = array();
        for ($i=1; $i < 13; $i++) {
            $mnth[$i] = date('F', strtotime(date("Y")."-".$i));
        }

        $yearbefore = intval(date('Y')) - 5;
        $yearafter = intval(date('Y')) + 5;

        return view('finance.business.balance', compact('mnth', 'yearbefore', 'yearafter'));
    }

    function balance_search__(Request $request){
        $plan_date = $request->year."-".sprintf("%02d", $request->mnth);

        $data_business = Finance_business::where('company_id', Session::get('company_id'))
            ->get();
        $business = array();
        foreach ($data_business as $value){
            $business[$value->id] = $value;
        }

        $p_l_date = date("Y-m-t", strtotime($plan_date));

        $business_detail = Finance_business_detail::where('plan_date', 'like', "$plan_date%")->get();
        $col = array();
        foreach ($business_detail as $key => $value) {
            // echo "<pre>".print_r($key,1 )."</pre>";
            $period = $key;
            if (isset($business[$value['id_business']])){
                $bs = $business[$value['id_business']];
                $investors = (!empty($bs['investors'])) ? json_decode($bs['investors']) : array();
                $duration = $bs['period'];
                $bunga    = $bs['bunga'];
                $amount   = $bs['value'];
                $type     = $bs['type'];
                $datetime1 = date_create($bs['moneydrop']);
                $datetime2 = date_create($bs['start']);
                $interval = date_diff($datetime1, $datetime2);
                $datediff = $interval->format('%a');

                $investOwn = $bs->value;
                $investInv = [];

                if (!empty($investors)) {
                    foreach ($investors as $investor) {
                        $additional = 0;
                        $per = $duration;
                        $amount2 = $investor->amount;
                        $investInv[] = $investor->amount;
                        $nci = $value['n_cicil'] - 1;

                        //administration
                        $v_a = floor($amount2 + ($amount2 * $bs->bunga / 100) * $bs->period);
                        $v_b = floor($amount2 + ($amount2 * (str_replace("%", "", $investor->percentage)) / 100) * $bs->period);
                        $v_c = floor(($amount2 * (str_replace("%", "", $investor->percentage)) / 100) * ($bs->period));
                        if(isset($investor->newKey) && $nci >= $investor->newKey){
                            $invAmount = $amount2 + $investor->additional;
                            $v_a = floor(($investor->amount * $bs->bunga / 100) * $investor->newKey);
                            $v_a += floor($invAmount + ($invAmount * $bs->bunga / 100) * ($bs->period - $investor->newKey));
                            $v_c = floor(($investor->amount * (str_replace("%", "", $investor->percentage)) / 100) * $investor->newKey);
                            $v_c += floor(($invAmount * (str_replace("%", "", $investor->percentage)) / 100) * ($bs->period - $investor->newKey));
                            $v_b = $invAmount + $v_c;
                        }
                        //

                        $balanceNow = $amount2;

                        $cicilDraft = floor(($amount2) / $per);
                        $bungaMulti = (1 + $investor->percentage / 100);

                        $bungaNow = $amount2 * ($bungaMulti - 1);
                        $balanceNow = round($balanceNow,2,PHP_ROUND_HALF_UP);

                        if($type == "PRO"){
                            $n = (($amount2 / $per) * ($datediff/30)) ;
                        } else { // LUMPSUM
                            $n = (($amount2 / $per) );
                        }

                        $nni = 0;

                        $paid = 0;

                        $payments = [];

                        if(isset($investor->payments)){
                            $payments = $investor->payments;

                            if(isset($payments[$nci])){
                                $paid = 1;
                            }
                        }

                        $mni = $nci + 1;

                        if(isset($investor->newKey) && $nci >= $investor->newKey){
                            $mni = $investor->newKey;
                        }

                        if($paid == 1){
                            $cicilPaid = ($cicilDraft) * $mni;
                            $cPaid = ($cicilDraft + $bungaNow) * $mni;
                        } else {
                            if(isset($investor->newKey) && $nci >= $investor->newKey){
                                $cicilPaid = ($cicilDraft) * ($mni);
                                $cPaid = ($cicilDraft + $bungaNow) * $mni;
                            } else {
                                $cicilPaid = ($cicilDraft) * count($payments);
                                $cPaid = ($cicilDraft + $bungaNow) * count($payments);
                            }
                        }

                        $totalAm = ($cicilDraft) * ($mni);
                        $cTotalAm = ($cicilDraft + $bungaNow) * ($mni);

                        $cicilDraftBefore = $cicilDraft;
                        $cicilBefore = $cicilPaid;
                        $cBefore = $cPaid;

                        $cicilLeft = $balanceNow - ($cicilPaid);

                        if(isset($investor->additional)){
                            if (isset($investor->newKey) && $nci >= $investor->newKey) {
                                $balanceNow = $investor->amount - $cPaid;
                                $balanceNow += $investor->additional;
                                $per = $duration - $investor->newKey;
                                $amount2 += $investor->additional;
                                $cicilPaid = 0;
                                $cicilDraft = floor(($balanceNow) / $per);
                                $bungaNow = $amount2 * ($bungaMulti - 1);
                                $nni = $investor->newKey;
                                $newTotalAm = ($balanceNow);
                                // $cTotalAm = $newTotalAm;
                                $totalAm = $newTotalAm;

                                if(isset($payments[$nci + 1])){
                                    $paid = 1;
                                } else {
                                    $paid = 0;
                                }

                                if($paid == 1){
                                    $cicilPaid = (($cicilDraft) * ($nci - ($nni - 1))) + $cicilBefore;
                                    $cPaid = (($cicilDraft + $bungaNow) * ($nci - ($nni - 1))) + $cBefore;
                                } else {
                                    $cicilPaid = (($cicilDraft) * (count($payments) - $nni)) + $cicilBefore;
                                    $cPaid = (($cicilDraft + $bungaNow) * (count($payments) - $nni)) + $cBefore;
                                }

                                $cTotalAm += ($cicilDraft + $bungaNow) * ($nci - $investor->newKey + 1);

                                $cicilLeft = $balanceNow - ($cicilPaid - $cicilBefore);
                            }
                        }

                        if(isset($investor->update)){
                            $last_update = end($investor->update);
                            if ($nci >= $last_update->key) {
                                $balanceNow = $investor->amount - $cPaid;
                                $balanceNow += $last_update->amount;
                                $per = $duration - $last_update->key;
                                $amount2 += $last_update->amount;
                                $cicilPaid = 0;
                                $cicilDraft = floor(($balanceNow) / $per);
                                $bungaNow = $amount2 * ($bungaMulti - 1);
                                $nni = $last_update->key;
                                $newTotalAm = ($balanceNow);
                                $totalAm = $newTotalAm;

                                if(isset($payments[$nci + 1])){
                                    $paid = 1;
                                } else {
                                    $paid = 0;
                                }

                                if($paid == 1){
                                    $cicilPaid = (($cicilDraft) * ($nci - ($nni - 1))) + $cicilBefore;
                                    $cPaid = (($cicilDraft + $bungaNow) * ($nci - ($nni - 1))) + $cBefore;
                                } else {
                                    $cicilPaid = (($cicilDraft) * (count($payments) - $nni)) + $cicilBefore;
                                    $cPaid = (($cicilDraft + $bungaNow) * (count($payments) - $nni)) + $cBefore;
                                }

                                $cTotalAm += ($cicilDraft + $bungaNow) * ($nci - $last_update->key + 1);

                                $cicilLeft = $balanceNow - ($cicilPaid - $cicilBefore);
                            }
                        }

                        if ($balanceNow >= $cicilPaid) {
                            $cicilNow = $cicilDraft;
                        } elseif ($balanceNow < $cicilDraft) {
                            $cicilNow = $cicilLeft;
                        } elseif ($cicilLeft <= 0) {
                            $cicilNow = 0;
                        }

                        $frstKey = 0;
                        if(isset($investor->newKey)){
                            $frstKey = $investor->newKey;
                        }

                        $adm = $v_a - $v_b;
                        $adm_paid = 0;
                        $adm_balance = ($adm / $bs->period) * ($nci + 1);
                        if(isset($investor->payments)){
                            $adm_paid = ($adm / $bs->period) * count($investor->payments);
                            if(count($investor->payments) > ($nci + 1)){
                                $adm_paid = ($adm / $bs->period) * ($nci + 1);
                            }
                        }

                        $adm_percentage = $bs->bunga - $investor->percentage;

                        $bungaNow = round($bungaNow,2,PHP_ROUND_HALF_UP);
                        $cicilNow = round($cicilNow,2,PHP_ROUND_HALF_UP);

                        $ltAmount = 1000 - intval(substr($cTotalAm, -3));
                        if($ltAmount > 0 && $ltAmount <= 5){
                            $cTotalAm += $ltAmount;
                        }

                        $ltPaid = 1000 - intval(substr($cPaid, -3));
                        if($ltPaid > 0 && $ltPaid <= 5){
                            $cPaid += $ltPaid;
                        }

                        $row['adm_percentage'] = $adm_percentage;
                        $row['adm_balance'] = $adm_balance;
                        $row['adm_paid'] = $adm_paid;
                        $row['balance'] = $balanceNow;
                        $row['c_total_am'] = $cTotalAm;
                        $row['total_am'] = $totalAm;
                        $row['cpaid'] = $cPaid;
                        $row['paid'] = $cicilPaid;
                        $row['interest'] = $investor->percentage;
                        $row['date'] = $value['plan_date'];
                        $row['amount'] =$cicilDraft + $bungaNow;
                        $name = strtolower(str_replace(" ", "_", str_replace(".", "", $investor->name)));
                        $col[$value['plan_date']][$name]['name'] = $investor->name;
                        $col[$value['plan_date']][$name]['data'][$bs->id] = $row;
                    }
                }

                $investOwn = $investOwn - array_sum($investInv);
                $cicilanOwn = floor($investOwn / $bs->period);
                $bungaOwn = floor($investOwn * $bs->own_percent / 100);
                if ($value->n_cicil == ($bs->period)){
                    $cicilPaid = $cicilanOwn * $value->n_cicil;
                    $cicil = $investOwn - $cicilPaid;
                } else {
                    $cicil = $cicilanOwn;
                }

                $v_a = floor($investOwn + ($investOwn * $bs->bunga / 100) * $bs->period);
                $v_b = floor($investOwn + ($investOwn * $bs->own_percent / 100) * $bs->period);
                $adm = $v_a - $v_b;
                $adm_balance = ($adm / $bs->period) * ($nci + 1);
                $adm_percentage = $bs->bunga - $bs->own_percent;

                $c_own = $cicilanOwn + $bungaOwn;

                $cTotalAm = $c_own * ($nci + 1);
                $cPaid = $c_own * ($nci + 1);

                $ltAmount = 1000 - intval(substr($cTotalAm, -3));
                if($ltAmount > 0 && $ltAmount <= 5){
                    $cTotalAm += $ltAmount;
                }

                $ltPaid = 1000 - intval(substr($cPaid, -3));
                if($ltPaid > 0 && $ltPaid <= 5){
                    $cPaid += $ltPaid;
                }

                $row = [];
                $row['adm_percentage'] = $adm_percentage;
                $row['adm_balance'] = $adm_balance;
                $row['adm_paid'] = $adm_balance;
                // $row['balance'] = $balanceNow;
                $row['c_total_am'] = $cTotalAm;
                $row['cpaid'] = $cPaid;
                $row['interest'] = $bs->own_percent;
                $row['date'] = $value['plan_date'];
                $row['amount'] = $cicil + $bungaOwn;
                $comp = ConfigCompany::find($bs->company_id);
                $name = $comp->company_name;
                // if($row['amount'] > 0){
                    $col[$value['plan_date']][$comp->tag]['data'][$bs->id] = $row;
                // }
                $col[$value['plan_date']][$comp->tag]['name'] = $name;
            }
        }

        $view = "";

        if(isset($request->view) && $request->view == "export"){
            $view = "export";
        }

        $period = $plan_date;

        $business_data = Finance_business::where('company_id', Session::get('company_id'))->get();
        $bs_data['name'] = $business_data->pluck('bank', 'id');
        $bs_data['period'] = $business_data->pluck('period', 'id');

        return view('finance.business.balance_investors', compact('col', 'period', 'bs_data', 'view'));
    }

    // Investors Function

    function balance_search(Request $request)
    {
        $mnth = $request->mnth;
        $year = $request->year;

        $investor = Finance_business_master_investors::pluck('name', 'id');
        $investor_info = Finance_business_master_investors::pluck('account_info', 'id');
        $data_bs = Finance_business::where('company_id', Session::get("company_id"));
        $id_bs = $data_bs->select('id')->pluck('id');
        $bs = $data_bs->select('*')->pluck('bank', 'id');

        $key_date = $year . "-" . sprintf("%02d", $mnth);

        $end_date = date("Y-m-t", strtotime($key_date));

        $bs_detail = Finance_business_detail::whereIn('id_business', $id_bs)
        ->where('plan_date',
            '<=',
            $end_date
        )
        ->get();
        $bDetail = [];
        $isbDetail = [];
        foreach ($bs_detail as $item) {
            $bDetail[$item->id_business][] = $item;
            $isbDetail[$item->id_business][date("Y-m", strtotime($item->plan_date))] = $item->id;
        }

        $data_bs_investor = Finance_business_investors::where('company_id', Session::get('company_id'));
        $bs_investor_id = $data_bs_investor->select('id')->pluck('id');
        $bs_investor = $data_bs_investor->select("*")->get();
        $bInvestor = [];
        foreach ($bs_investor as $item) {
            $bInvestor[$item->id_investor][] = $item;
        }

        $bs_inv_det = Finance_business_investor_detail::whereIn('id_business_investor', $bs_investor_id)
            ->where('plan_date',
                '<=',
                $end_date
            )
            ->orderBy('plan_date')
            ->get();
        $bidet = [];
        foreach ($bs_inv_det as $item) {
            $bidet[$item->id_business_investor][] = $item;
        }

        $row = [];

        $detail_inv = [];

        $business_data = Finance_business::where('company_id', Session::get('company_id'))->get();
        $bs_data = [];
        foreach ($business_data as $item) {
            $bs_data[$item->id] = $item;
        }

        $cicilInv = [];

        $closed_at = [];

        foreach ($investor as $key => $inv) {
            if (isset($bInvestor[$key])) {
                foreach ($bInvestor[$key] as $bsInv) {
                    if (isset($bs[$bsInv->id_business])) {
                        $bsName = $bs[$bsInv->id_business];
                        $col = [];
                        $amount = 0;
                        $paid = 0;
                        $adm = [];
                        $adm_amount = 0;
                        $nPaid = 0;
                        $unPaid = [];
                        $cicilan = [];
                        $profit = [];
                        $adm_balance = $bsInv->adm;
                        $inpaid = 0;
                        if (isset($bidet[$bsInv->id])) {
                            $monthly = 0;
                            $sumBalance = 0;
                            $payMonthly = [];
                            foreach ($bidet[$bsInv->id] as $value) {
                                $nPaid += $value->cicilan;
                                if(!empty($value->paid_at)){
                                    $inpaid += $value->cicilan;
                                }
                                $plan_date = date("Y-m", strtotime($value->plan_date));
                                // $amount += $value->cicilan + $value->bunga;
                                if($value->closed != 1){
                                    $amount += $value->cicilan + $value->bunga;
                                    $payMonthly[str_replace("-", "_", $plan_date)] = $value->cicilan + $value->bunga;
                                    $cicilan[str_replace("-", "_", $plan_date)] = $value->cicilan;
                                    $profit[str_replace("-", "_", $plan_date)] = $value->bunga;
                                    $adm[str_replace("-", "_", $plan_date)] = $value->adm;
                                    if (strtotime($plan_date) < strtotime($key_date)) {
                                        // $amount += $value->cicilan + $value->bunga;
                                        if (!empty($value->paid_at)) {
                                            $paid += $value->cicilan + $value->bunga;
                                            // $adm += $value->adm;
                                        }
                                    } else {
                                        if (strtotime($plan_date) == strtotime($key_date)) {
                                            // $amount += $value->cicilan + $value->bunga;
                                            if (!empty($value->paid_at)) {
                                                $paid += $value->cicilan + $value->bunga;
                                                // $adm = $value->adm;
                                            }
                                        }
                                    }

                                    if(empty($value->paid_at)){
                                        $unPaid[$value->plan_date] = $value->cicilan + $value->bunga;
                                        $adm_amount += $value->adm;
                                    }
                                }
                                $adm_balance -= $value->adm;

                                if($value->closed != 1){
                                    $cicilInv[date("Y_m", strtotime($value->plan_date))][$bsInv->id_business][] = $value->cicilan;
                                    // if(isset($isbDetail[$bsInv->id_business][date("Y-m", strtotime($value->plan_date))])){
                                    //     $cicilInv[date("Y_m", strtotime($value->plan_date))][$bsInv->id_business][] = $value->cicilan;
                                    // }
                                }

                                $detail_inv[str_replace("-", "_", $plan_date)][$bsInv->id_business]['cicilan'][] = $value->cicilan;
                                $actual_amount = $bsInv->amount;
                                if ($value->closed) {
                                    if (!empty($bsInv->actual_amount)) {
                                        $actual_amount = $bsInv->actual_amount;
                                    }
                                }
                                if (!empty($bsInv->close)) {
                                    $next_date = date("Y-m-d", strtotime("+1 month " . $bsInv->close));
                                }

                                $close_date = date("Y-m", strtotime($bsInv->close));

                                if(strtotime($plan_date) <= strtotime($close_date)){
                                    // $closed_at[$bsInv->id_business][str_replace("-", "_", $plan_date)][$bsInv->id] = $bsInv->closing_amount;
                                    $closed_at[$bsInv->id_business][$close_date][$bsInv->id] = $bsInv->closing_amount;
                                }

                                $_row['amount'] = $actual_amount;
                                $_row['date'] = $value->plan_date;
                                $detail_inv[str_replace("-", "_", $plan_date)][$bsInv->id_business]['amount'][] = $actual_amount;
                            }

                            $subAmount = 1000 - intval(substr($amount, -3));
                            if ($subAmount > 0 && $subAmount < 5) {
                                $amount += $subAmount;
                            }

                            $subAdmAmount = 1000 - intval(substr($adm_amount, -3));
                            if ($subAdmAmount > 0 && $subAdmAmount < 5) {
                                $adm_amount += $subAdmAmount;
                            }

                            $subPaid = 1000 - intval(substr($paid, -3));
                            if($subPaid > 0 && $subAmount < 5){
                                $paid += $subPaid;
                            }

                            $closing_value = 0;
                            if(!empty($bsInv->close)){
                                $closed_date = date("Y-m", strtotime($bsInv->close));
                                if(strtotime($key_date) >= strtotime($closed_date)){
                                    $closing_value = $bsInv->closing_amount;
                                }
                            }

                            $summary = $bsInv->amount - $nPaid - $closing_value - $inpaid;

                            $col['unpaid'] = $unPaid;
                            $col['name'] = $bsInv->investment_name;
                            $col['period'] = $bs_data[$bsInv->id_business]->period;
                            $col['cicilan'] = (isset($cicilan[str_replace("-", "_", $key_date)])) ? $cicilan[str_replace("-", "_", $key_date)] : 0;
                            $col['profit'] = (isset($profit[str_replace("-", "_", $key_date)])) ? $profit[str_replace("-", "_", $key_date)] : 0;
                            $col['monthly'] = (isset($payMonthly[str_replace("-", "_", $key_date)])) ? $payMonthly[str_replace("-", "_", $key_date)] : 0;
                            $col['rate'] = $bsInv->rate;
                            $col['amount'] = $amount;
                            $col['paid'] = $paid;
                            $col['adm'] = (isset($adm[str_replace("-", "_", $key_date)])) ? $adm[str_replace("-", "_", $key_date)] : 0;;
                            $col['sum_balance'] = ($summary < 0) ? 0 : $summary;
                            $col['adm_amount'] = $adm_amount;
                            $col['adm_balance'] = $adm_balance;
                        }

                        if ($amount > 0) {
                            $row[str_replace(" ", "_", $investor[$key])][$bsInv->id_business]['account_info'] = $investor_info[$key];
                            $row[str_replace(" ", "_", $investor[$key])][$bsInv->id_business]['name'] = $bsName;
                            $row[str_replace(" ", "_", $investor[$key])][$bsInv->id_business]['investor_name'] = $inv;
                            $row[str_replace(" ", "_", $investor[$key])][$bsInv->id_business]['details'][$bsInv->id] = $col;
                        }
                    }
                }
            }
        }

        $t_amoun = [];

        foreach ($data_bs->get() as $key => $value) {

            $bunga = (!empty($value->own_percent)) ? $value->own_percent : $value->bunga;
            $profit = ($bunga / 100) * $amount;
            if (isset($bDetail[$value->id])) {
                $amount = 0;
                $monthly = 0;
                $paid = 0;
                $adm = 0;
                $adm_amount = 0;
                $nPaid = 0;
                $nCicil = 0;
                $unPaid = [];
                $cicilan = 0;
                $profit = 0;
                $am_arr = [];
                $v_a = floor($value->value + ($value->value * $value->bunga / 100) * $value->period);
                $v_b = floor($value->value + ($value->value * $value->own_percent / 100) * $value->period);
                $adm_balance = $v_a - $v_b;
                foreach ($bDetail[$value->id] as $det) {
                    $plan_date = date("Y-m", strtotime($det->plan_date));
                    $dekeydate = str_replace("-", "_", $plan_date);
                    $totalAmount = $value->value;
                    if (isset($detail_inv[$dekeydate])) {
                        if(isset($detail_inv[$dekeydate][$value->id])){
                            $totalAmount -= array_sum($detail_inv[$dekeydate][$value->id]['amount']);
                        }
                    }

                    $v_a = floor($totalAmount + ($totalAmount * $value->bunga / 100) * $value->period);
                    $v_b = floor($totalAmount + ($totalAmount * $value->own_percent / 100) * $value->period);
                    $adm_balance = $v_a - $v_b;
                    $am_adm = round(($v_a - $v_b) / $value->period);
                    $t_amount[$dekeydate][$det->id_business][] = $totalAmount;

                    $profit_rate = round(($bunga / 100) * $totalAmount);

                    // dd($key_date, $plan_date);

                    $cicil = 0;

                    if (strtotime($plan_date) < strtotime($key_date)) {
                        $cicilSum = 0;
                        if(isset($cicilInv[$dekeydate][$det->id_business])){
                            $cicilSum = array_sum($cicilInv[$dekeydate][$det->id_business]);
                        }
                        $cicil = $det->cicilan - $cicilSum;
                        if (!empty($det->paid_investment)) {
                            $jsDetail = json_decode($det->paid_investment, true);
                            // $profit_rate = $jsDetail['profit'];
                            // $cicil = $jsDetail['installment'];
                            $paid += $cicil + $profit_rate;
                        }
                        // $adm_amount += $am_adm;
                        // $amount += $cicil + $profit_rate;
                    } else {
                        $cicilSum = 0;
                        if (isset($cicilInv[$dekeydate][$det->id_business])) {
                            $cicilSum = array_sum($cicilInv[$dekeydate][$det->id_business]);
                            // dd($cicilInv[$dekeydate][$det->id_business]);
                        }
                        $cicil = $det->cicilan - $cicilSum;
                        // dd($cicil);
                        if (!empty($det->paid_invesment)) {
                            // $cicil = $jsDetail['installment'];
                            // $paid += $cicil + $jsDetail['profit'];
                            $paid += $cicil + $profit_rate;
                        }
                        $adm = $am_adm;
                        // $adm_amount += $am_adm;
                        // $amount += ($cicil) + $profit_rate;
                        $monthly = $cicil + $profit_rate;
                        $cicilan = $cicil;
                        $profit = $profit_rate;
                    }

                    if (empty($det->paid_investment)) {
                        $unPaid[$det->plan_date] = $cicil + $profit_rate;
                        $adm_amount += $am_adm;
                    }


                    $amount += $cicil + $profit_rate;
                    // if(isset($isbDetail[$det->id_business][$plan_date])){
                    //     $amount += $cicil + $profit_rate;
                    // } else {
                    //     $amount = 0;
                    // }
                    $adm_balance -= $am_adm;
                    $am_arr[$det->id_business][$plan_date] = $cicil + $profit_rate;

                    if(isset($closed_at[$det->id_business][$key_date])){
                        $totalAmount += array_sum($closed_at[$det->id_business][$key_date]);
                    }
                    $nCicil += $cicil;
                    $nPaid = $totalAmount - $nCicil;
                }

                // if(!isset($isbDetail[$det->id_business][$key_date])){
                //     $amount = 0;
                // }

                $subAmount = 1000 - intval(substr($amount, -3));
                if ($subAmount > 0 && $subAmount < 5) {
                    $amount += $subAmount;
                }

                $subAdmAmount = 1000 - intval(substr($adm_amount, -3));
                if ($subAdmAmount > 0 && $subAdmAmount < 5) {
                    $adm_amount += $subAdmAmount;
                }

                $monthly = floor($monthly);
                $amount    = floor($amount);

                $col['unpaid'] = ($unPaid < 0) ? 0 : $unPaid;
                $col['period'] = ($value->period < 0) ? 0 : $value->period;
                $col['cicilan'] = ($cicilan < 0) ? 0 : $cicilan;
                $col['profit'] = ($profit < 0) ? 0 : $profit;
                $col['monthly'] = ($monthly < 0) ? 0 : $monthly;
                $col['rate'] = ($bunga < 0) ? 0 : $bunga;
                $col['amount'] = ($amount < 0) ? 0 : $amount;
                $col['paid'] = ($paid < 0) ? 0 : $paid;
                $col['adm'] = ($adm < 0) ? 0 : $adm;
                $col['sum_balance'] = ($nPaid < 0) ? 0 : floor($nPaid);
                $col['adm_amount'] = ($adm_amount < 0) ? 0 : $adm_amount;
                $col['adm_balance'] = ($adm_balance < 0) ? 0 : $adm_balance;
                $pBalance = $amount - $paid;

               if($amount > 0){
                    $row[str_replace(" ", "_", Session::get('company_name_parent'))][$value->id]['account_info'] = "";
                    $row[str_replace(" ", "_", Session::get('company_name_parent'))][$value->id]['name'] = $value->bank;
                    $row[str_replace(" ", "_", Session::get('company_name_parent'))][$value->id]['investor_name'] = $inv;
                    $row[str_replace(" ", "_", Session::get('company_name_parent'))][$value->id]['details'][$value->id] = $col;
               }
            }
        }

        $view = "";

        if (isset($request->view) && $request->view == "export"
        ) {
            $view = "export";
        }

        $period = $year . "-" . $mnth;

        return view('finance.business.balance_investors', compact('row', 'view', 'period', 'investor', 'bs', 'bs_data'));
    }

    function investors(){
        $investors = Finance_business_master_investors::all();
        return view('finance.business.investor_master', compact('investors'));
    }

    function add_investors(Request $request){
        if(isset($request->id_delete)){
            $investor = Finance_business_master_investors::find($request->id_delete);
            $investor_business = Finance_business_investors::where('id_investor', $investor->id)->get();
            if(count($investor_business) > 0){
                $deleted = 0;
                $msg = $investor->name." is investing in one or more business";
            } else {
                $investor->delete();
                $deleted = 1;
                $msg = "Investor ".$investor->name." has been deleted";
            }
            return redirect()->back()->with(['msg' => $msg, 'investor_deleted' => $deleted]);
        }
        $investor = new Finance_business_master_investors();
        $investor->created_by = Auth::user()->username;
        if(isset($request->id_investor)){
            $investor = Finance_business_master_investors::find($request->id_investor);
            $investor->updated_by = Auth::user()->username;
        }
        $investor->name = $request->name;
        $investor->account_info = $request->account_info;
        $investor->save();

        return redirect()->back();
    }

    function investors_list($id){
        $investors_master = Finance_business_master_investors::all()->pluck('name', 'id');
        $business = Finance_business::find($id);
        $bs_detail = Finance_business_detail::where('id_business', $business->id)
            ->orderBy('n_cicil')
            ->get();

        $investors_data = Finance_business_investors::where('id_business', $business->id)
            ->orderBy('id')
            ->get();

        $investor_item = [];
        $investor_ids = [];
        $ids = [];
        foreach($investors_data as $item){
            $investor_item[$item->id_investor][] = $item;
            $investor_ids[] = $item->id_investor;
            $ids[] = $item->id;
        }

        $inv_master = Finance_business_master_investors::whereNotIn('id', $investor_ids)->get()->pluck('name', 'id');

        $investor_details = [];

        $inv_dt = Finance_business_investor_detail::whereIn('id_business_investor', $ids)
            // ->whereRaw('(closed = 0 or closed is null)')
            ->orderBy('n_cicil')
            ->get();
        foreach($inv_dt as $item){
            $investor_details[$item->id_business_investor][] = $item;
        }

        $partner = Finance_business_master_partner::all()->pluck('name', 'id');

        return view('finance.business.investor_list', compact('business', 'investors_master', 'bs_detail', 'investor_item', 'investor_details', 'inv_master', 'partner'));
    }

    function investors_list_add(Request $request){
        $inv_name = null;
        if(isset($request->investment_name)){
            $inv_name = $request->investment_name;
        }
        $this->create_comp_investment($request->id_business, $request->investor, $request->start_from, $request->profit_rate, $request->amount, $inv_name, null);

        return redirect()->back()->with('msg', 'Investor has been add');
    }

    function investors_list_delete(Request $request){
        $investasi = Finance_business_investors::select('id')
            ->where('id_business', $request->bus)
            ->where('id_investor', $request->val)
            ->get()->pluck('id');

        $detail = Finance_business_investor_detail::whereIn('id_business_investor', $investasi)->delete();

        $invDel = $investasi = Finance_business_investors::select('id')
            ->where('id_business', $request->bus)
            ->where('id_investor', $request->val)
            ->delete();

        if($invDel){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function investors_pay_list(Request $request){
        $id = $request->x;
        $type = $request->y;

        $detail = Finance_business_investor_detail::find($id);
        return view('finance.business._investor_pay_modal', compact('detail', 'type'));
    }

    function investors_close_list(Request $request){
        $id = $request->x;
        $type = $request->y;

        $detail = Finance_business_investor_detail::find($id);
        $paid_data = Finance_business_investor_detail::where('id_business_investor', $detail->id_business_investor)
            ->where('n_cicil', '<=', ($detail->n_cicil))
            ->get();

        $paid = 0;
        $npaid = 0;
        foreach($paid_data as $item){
            $npaid += $item->cicilan;
            if(!empty($item->paid_at)){
                $paid += $item->cicilan + $item->bunga;
            }

        }

        $investor = Finance_business_investors::find($detail->id_business_investor);
        $inv_name = Finance_business_master_investors::find($investor->id_investor);

        return view('finance.business._investor_close_modal', compact('detail', 'type', 'paid', 'investor', 'inv_name', 'npaid'));
    }

    function investors_pay(Request $request){
        if ($request->type == "investor") {
            $detail = Finance_business_investor_detail::find($request->id);
            $detail->paid_at = date("Y-m-d H:i:s");
            $detail->paid_by = Auth::user()->username;
            $detail->save();
        } else {
            $detail = Finance_business_detail::find($request->id);
            $row['profit_rate'] = $request->profit_rate;
            $row['installment'] = str_replace(',', "", $request->installment);
            $row['profit'] = str_replace(',', "", $request->profit);
            $row['date'] = date("Y-m-d H:i:s");
            $detail->paid_investment = json_encode($row);
            $detail->save();
        }

        return redirect()->back()->with('msg', 'Payment Success');
    }

    function investors_close(Request $request){
        // dd($request);
        $detail = Finance_business_investor_detail::find($request->id);
        $newAmount = Finance_business_investor_detail::where('id_business_investor', $detail->id_business_investor)
            ->where('n_cicil', '<=', $detail->n_cicil)
            ->sum('cicilan');

        $left_amount = str_replace(",", "", $request->left_amount) - str_replace(",", "", $request->amount);

        $investment = Finance_business_investors::find($detail->id_business_investor);

        $bs = Finance_business::find($investment->id_business);
        $am = $newAmount;
        $mDiff = Finance_business_investor_detail::where('id_business_investor', $detail->id_business_investor)
            ->where('n_cicil', '<', $detail->n_cicil)
            ->count();
        $v_a = floor($am + ($am * $bs->bunga / 100) * intval($mDiff));
        $v_b = floor($am + ($am * (str_replace("%", "", $investment->rate)) / 100) * intval($mDiff));
        $adm = $v_a - $v_b;
        // $investment->amount = $newAmount;

        $detail->paid_at = date("Y-m-d H:i:s");
        $detail->paid_by = Auth::user()->username;
        $detail->save();

        $investment->adm = $adm;
        $investment->adm_a = $v_a;
        $investment->adm_b = $v_b;
        $investment->closing_amount = str_replace(",", "", $request->amount);
        $investment->close = $detail->plan_date;
        $investment->actual_amount = $newAmount;
        $closeAmount = $investment->amount - str_replace(",", "", $request->amount);
        $investment->save();

        Finance_business_investor_detail::where('id_business_investor', $detail->id_business_investor)
            ->where('n_cicil', '>', $detail->n_cicil)
            ->update([
                "closed" => 1
            ]);


        if ($left_amount > 0) {
            $inv_name = $request->investment_name;
            if (empty($request->investment_name)) {
                $inv_name = $investment->name . " #Rev";
            }
            $this->create_comp_investment($request->id_business, $request->investor, $request->start_from, $request->profit_rate, $left_amount, $inv_name, $closeAmount);
        }
        return redirect()->back()->with('msg', 'Investment Closed');
    }

    function investors_list_addInvesment(Request $request){
        if ($request->type == "company") {
            $business = Finance_business::find($request->id);
            $company = json_decode($business->company);
            $detail['currency'] = $request->currency;
            $detail['amount'] = str_replace(",", "", $request->amount);
            $idr = str_replace(",", "", $request->amount) * str_replace(",", "", $request->rate);
            $detail['IDR'] = $idr;
            $detail['exchange'] = str_replace(",", "", $request->rate);
            $company[] = $detail;
            $business->company = json_encode($company);
            $business->save();
        } else {
            $investor = Finance_business_investors::find($request->id);
            $row['currency'] = $request->currency;
            $row['amount'] = str_replace(",", "", $request->amount);
            $row['exchange_rate'] = str_replace(",", "", $request->rate);
            $row['rate'] = $investor->rate;
            $row['idr'] = $row['amount'] * $row['exchange_rate'];
            $details = [];
            if(!empty($investor->details)){
                $details = json_decode($investor->details, true);
            }
            $details['details'][] = $row;
            $investor->details = json_encode($details);
            $investor->save();
        }

        return redirect()->back()->with('msg', 'Success');
    }

    function investors_list_deleteInvesment($id, $index){
        $investor = Finance_business_investors::find($id);
        $d = json_decode($investor->details, true);
        $detail = $d['details'];
        array_splice($detail, $index, 1);
        $d['details'] = $detail;
        $investor->details = json_encode($d);
        $investor->save();
        return redirect()->back()->with('msg', 'Success');
    }

    function investors_list_save_text(Request $request){
        $investor = Finance_business_investors::find($request->id);
        $details = [];
        if(!empty($investor->details)){
            $details = json_decode($investor->details, true);
        }
        $details['unusedPayment'] = $request->content;
        $investor->details = json_encode($details);
        $investor->save();

        return redirect()->back()->with('msg', 'Success');
    }

    function investors_list_editInvestment(Request $request){
        $detail = Finance_business_investor_detail::find($request->id);
        $invest = Finance_business_investors::find($detail->id_business_investor);
        $detail->bunga_rate = str_replace(",", "", $request->profit_rate);
        $detail->bunga = str_replace(",", '', $request->profit);
        $installment = str_replace(",", "", $request->installment);
        if($installment != $detail->amount){
            $paid = Finance_business_investor_detail::where("id_business_investor", $invest->id)
                ->where('n_cicil', '<', $detail->n_cicil)
                ->sum('cicilan');
            $newAmount = $invest->amount - ($paid + $installment);
            $count = Finance_business_investor_detail::where("id_business_investor", $invest->id)
                ->where('n_cicil', '>', $detail->n_cicil)
                ->orderBy('n_cicil')
                ->get();
            try {
                $co = (count($count) == 0) ? 1 : 0;
                $newCicilan = round($newAmount / $co);
                $balance = $newAmount;
                foreach($count as $i => $item){
                    if($balance < $newCicilan){
                        $cicil = $balance;
                    } else {
                        if(($i + 1) == $co){
                            $cicil = $balance;
                        } else {
                            $cicil = $newCicilan;
                        }
                    }
                    $item->cicilan = $cicil;
                    $item->save();
                    $balance -= $newCicilan;
                }
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', $th->getMessage()." Line ".$th->getLine());
            }
        }
        $detail->cicilan = $installment;
        $detail->save();

        return redirect()->back()->with('msg', 'Updated');
    }

    function create_comp_investment($id_business, $investor, $start_from, $profit_rate, $amount, $investment_name, $closeAmount){
        $inv = Finance_business_investors::where('id_investor', $investor)
            ->where('id_business', $id_business)
            ->count();

        $bs = Finance_business::find($id_business);

        $end_date = date("Y-m-d", strtotime("+".$bs->period." months ".$bs->start));

        $inv_num = 1;
        $inv_name = "Main Investment";
        if($inv >= 1){
            $inv_num = $inv + 1;
        }

        $date1 = date_create($start_from);
        $date2 = date_create($end_date);

        $diff = date_diff($date1, $date2);

        $fDiff = explode("-", $diff->format("%y-%m"));
        $mDiff = ($fDiff[0] * 12) + end($fDiff);

        $plan_date = $start_from;

        $new_inv = new Finance_business_investors();
        if(!empty($investment_name)){
            $inv_name = $investment_name;
        }

        $am = str_replace(",", "", $amount);
        $rt = str_replace(",", "", $profit_rate);

        $v_a = floor($am + ($am * $bs->bunga / 100) * intval($mDiff));
        $v_b = floor($am + ($am * (str_replace("%", "", $rt)) / 100) * intval($mDiff));
        $adm = $v_a - $v_b;

        $new_inv->investment_name = $inv_name;
        $new_inv->id_business = $id_business;
        $new_inv->id_investor = $investor;
        $new_inv->investment_num = $inv_num;
        $new_inv->start_date = $start_from;
        $new_inv->rate = str_replace(",", "", $profit_rate);
        if (!empty($closeAmount)) {
            $new_inv->amount_before = $closeAmount;
        }
        $new_inv->amount = str_replace(",", "", $amount);
        $new_inv->adm = $adm;
        $new_inv->adm_a = $v_a;
        $new_inv->adm_b = $v_b;
        $new_inv->created_by = Auth::user()->username;
        $new_inv->company_id = Session::get('company_id');
        $new_inv->save();

        $balance = $new_inv->amount;

        $adm_mnth = $adm / $mDiff;

        for ($i=0; $i < $mDiff ; $i++) {
            //cicilan
            $cicilan = round($new_inv->amount / $mDiff);
            if($cicilan > $balance){
                $cicilan = $balance;
            } else {
                if(($i + 1) == $mDiff){
                    $cicilan = $balance;
                }
            }

            $balance -= $cicilan;
            $bunga_rate = $new_inv->rate;
            $bungaAmount = $new_inv->amount;
            if(!empty($closeAmount)){
                $bungaAmount = $closeAmount;
            }
            $bunga = $bungaAmount * ($bunga_rate / 100);


            $inv_detail = new Finance_business_investor_detail();
            $inv_detail->id_business_investor = $new_inv->id;
            $inv_detail->n_cicil = $i + 1;
            $inv_detail->plan_date = $plan_date;
            $inv_detail->cicilan = $cicilan;
            $inv_detail->bunga_rate = $bunga_rate;
            $inv_detail->bunga = $bunga;
            $inv_detail->adm = $adm_mnth;
            $inv_detail->created_by = $new_inv->created_by;
            $inv_detail->save();

            //plan_date
            $ex_date = explode("-", $plan_date);

            $nMonth = $ex_date[1] + 1;
            $nYear = $ex_date[0];
            $nDate = end($ex_date);
            if($nMonth > 12){
                $nYear += 1;
                $nMonth = 1;
            }

            $maxDate = date("t", strtotime($nYear."-".$nMonth));
            if($nDate > $maxDate){
                $nDate = $maxDate;
            }

            $plan_date = $nYear."-".sprintf("%02d", $nMonth)."-".$nDate;
        }
    }


    function partners()
    {
        $investors = Finance_business_master_partner::all();
        return view('finance.business.partner_master', compact('investors'));
    }

    function add_partners(Request $request)
    {
        if (isset($request->id_delete)) {
            $investor = Finance_business_master_partner::find($request->id_delete);
            $investor_business = Finance_business::where('partner', $investor->id)->get();
            if (count($investor_business) > 0) {
                $deleted = 0;
                $msg = $investor->name . " is investing in one or more business";
            } else {
                $investor->delete();
                $deleted = 1;
                $msg = "Partner " . $investor->name . " has been deleted";
            }
            return redirect()->back()->with(['msg' => $msg, 'investor_deleted' => $deleted]);
        }
        $investor = new Finance_business_master_partner();
        $investor->created_by = Auth::user()->username;
        if (isset($request->id_investor)) {
            $investor = Finance_business_master_partner::find($request->id_investor);
            $investor->updated_by = Auth::user()->username;
        }
        $investor->name = $request->name;
        $investor->account_info = $request->account_info;
        $investor->save();

        return redirect()->back();
    }

    function balance_partners(){
        $mnth = array();
        for ($i = 1; $i < 13; $i++) {
            $mnth[$i] = date('F', strtotime(date("Y") . "-" . $i));
        }

        $yearbefore = intval(date('Y')) - 5;
        $yearafter = intval(date('Y')) + 5;

        return view('finance.business.partners', compact('mnth', 'yearbefore', 'yearafter'));
    }

    function balance_partners_search(Request $request){
        $search_date = $request->year."-".sprintf("%02d", $request->mnth);
        $end_date = date("Y-m-t", strtotime($search_date));

        $business = Finance_business::where('company_id', Session::get('company_id'))->get();
        $bs_id = Finance_business::where('company_id', Session::get('company_id'))->get()->pluck('id');

        $detail = [];

        $bs_detail = Finance_business_detail::whereIn('id_business', $bs_id)
            ->where('plan_date', "<=", $end_date)
            ->get();

        foreach($bs_detail as $item){
            $detail[$item->id_business][] = $item;
        }

        $row = [];

        $partners = Finance_business_master_partner::all()->pluck('name', 'id');

        foreach($business as $item){
            $amount = 0;
            $paid = 0;
            $unpaid = [];
            $monthly = 0;
            $bl = 0;
            $n = 0;
            $summary = $item->value;
            if(isset($detail[$item->id])){
                foreach($detail[$item->id] as $det){
                    $p_date = date("Y-m", strtotime($det->plan_date));
                    $amount = $det->cicilan + $det->bunga;
                    if(strtolower($det->status) == "paid"){
                        $paid += $det->cicilan + $det->bunga;
                    } else {
                        $unpaid[date("Y-m", strtotime($det->plan_date))] = round($det->cicilan + $det->bunga);
                        $bl += $det->cicilan + $det->bunga;
                    }

                    if(strtotime($p_date) == strtotime($search_date)){
                        $monthly = $det->cicilan + $det->bunga;
                        $n = $det->n_cicil;
                    } else {
                        $summary -= $det->cicilan;
                    }
                    // $summary = $det->cicilan;
                }
            }
            $totalAmount = round($amount * $item->period);
            $sAmount = 1000 - intval(substr($totalAmount, -3));
            if($sAmount  <= 5 && $sAmount > 0){
                $totalAmount += $sAmount;
            }
            $paid = round($paid);
            $sPaid = 1000 - intval(substr($paid, - 3));
            if($sPaid <= 5 && $sPaid > 0){
                $paid += $sPaid;
            }

            $col['n'] = $n;
            $col['monthly'] = round($monthly);
            $col['bl'] = round($bl);
            $col['amount'] = $totalAmount;
            $col['paid'] = round($paid);
            $col['unpaid'] = $unpaid;
            $col['name'] = $item->bank;
            $col['info'] = $item->account_info;
            $col['rate'] = $item->bunga;
            $col['period'] = $item->period;
            $col['summary'] = round($summary);
            $col['last'] = date("Y-m", strtotime("+".($item->period - 1)." months ".$item->start));
            if(isset($partners[$item->partner]) && ($col['bl'] > 0 || $col['summary'] > 0)){
                $row[$item->partner][$item->id] = $col;
            }
        }
        // dd($row);

        $view = "";

        if (
            isset($request->view) && $request->view == "export"
        ) {
            $view = "export";
        }

        // dd($row);

        $period = $search_date;

        return view('finance.business.balance_partners', compact('row', 'partners', 'view', 'period'));
    }

    function detail_close($id){
        $detail = Finance_business_detail::find($id);
        if(!empty($detail)){
            $id_business = $detail->id_business;
            $detail_after = Finance_business_detail::where('id_business', $id_business)
                ->where('n_cicil', '>', $detail->n_cicil)
                ->get();
            $sum_detail_after = (!empty($detail_after)) ? $detail_after->sum('cicilan') : 0;

            $bs_inv = Finance_business_investors::where('id_business', $id_business)->get();
            $bs_inv_id = $bs_inv->pluck('id');
            $bs_inv_detail = Finance_business_investor_detail::whereIn('id_business_investor', $bs_inv_id)
                ->where('closed', 0)
                ->get();
            $bs_inv_detail_cicil = $bs_inv_detail->where('plan_date', $detail->plan_date);
            if(!emptY($bs_inv_detail_cicil)){
                foreach($bs_inv_detail_cicil as $item){
                    $bs_inv_detail_after = $bs_inv_detail->where('plan_date', '>', $item->plan_date)
                        ->where('id_business_investor', $item->id_business_investor);

                    $sum = (!empty($bs_inv_detail_after)) ? $bs_inv_detail_after->sum('cicilan') : 0;

                    $item->cicilan = $item->cicilan + $sum;
                    $item->save();
                    Finance_business_investor_detail::where('id_business_investor', $item->id_business_investor)
                        ->where('plan_date', '>', $item->plan_date)
                        ->update([
                            'closed' => 1
                        ]);
                }
            }

            $detail->cicilan = $detail->cicilan + $sum_detail_after;
            $detail->status = "Paid";
            if($detail->save()){
                Finance_business_detail::where('id_business', $id_business)
                    ->where('n_cicil', '>', $detail->n_cicil)
                    ->delete();

                return redirect()->back()->with('msg', 'success');
            }
        }

        return redirect()->back()->with('error', 'not found');
    }

    function investorEdit ($id){
        $detail = Finance_business_investor_detail::where('id_business_investor', $id)->get();
        dd($detail);
    }

}
