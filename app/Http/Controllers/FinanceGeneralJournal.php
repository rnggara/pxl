<?php

namespace App\Http\Controllers;

use App\Helpers\FileManagement;
use App\Models\Finance_coa;
use App\Models\Finance_coa_history;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;

class FinanceGeneralJournal extends Controller
{
    function index(){
        $coa = Finance_coa_history::whereNotNull('md5')
            ->where('company_id', Session::get('company_id'))
            ->get();

        $coa_his = [];
        $debit = [];
        $credit = [];
        foreach ($coa as $item){
            $coa_his[$item->md5]['file_hash'] = $item->file_hash;
            $coa_his[$item->md5]['description'] = $item->description;
            $coa_his[$item->md5]['date'] = $item->coa_date;
            $coa_his[$item->md5]['md5'] = $item->md5;
            $coa_his[$item->md5]['approved_at'] = $item->approved_at;
            $coa_his[$item->md5]['approved_by'] = $item->approved_by;
            if ($item->debit){
                $debit['no_coa'] = $item->no_coa;
                $debit['debit'] = $item->debit;
                $coa_his[$item->md5]['debit'][] = $debit;
            }
             if ($item->credit){
                 $credit['no_coa'] = $item->no_coa;
                 $credit['credit'] = $item->credit;
                 $coa_his[$item->md5]['credit'][] = $credit;
             }
        }

        $val = [];
        $coa_his_tory = Finance_coa::select('id','code','name')
            ->whereNull('deleted_at')->get();
        foreach ($coa_his_tory as $value){
            $val[$value->code] = "[".$value->code."] ".$value->name;
        }

//        dd($coa_his);
        return view('finance.gj.index', [
            'coa' => $coa_his,
            'coa_name' => $val
        ]);
    }

    function add(Request $request){
//        dd($request);
        $last = Finance_coa_history::where('company_id', Session::get('company_id'))
            ->orderBy('md5', "desc")
            ->first();
        $hash = $last->md5 + 1;
        $debit = str_replace(",", "", $request->coa_code_debit);
        $de_amount = str_replace(",", "", $request->amount_debit);
        $credit = str_replace(",", "", $request->coa_code_credit);
        $cre_amount = str_replace(",", "", $request->amount_credit);

        $file = $request->file('file_upload');
        if (!empty($file)){
            $filename = str_replace(" ", "_", $file->getClientOriginalName());
            $hashFile = Hash::make($filename);
            $hashFile = str_replace("/", "", $hashFile);
            $upload = FileManagement::save_file_management($hashFile, $file, $filename, 'media/journal');
        }

        if (!empty($debit)){
            foreach ($debit as $key => $value){
                if ($value != null || !empty($value)){
                    $coa = explode(" ", $value);
                    $coa_code = str_replace(str_split('[]'), "", $coa[0]);
                    $iCoa = new Finance_coa_history();
                    $iCoa->md5 = $hash;
                    $iCoa->no_coa = $coa_code;
                    $iCoa->coa_date = $request->gj_date;
                    $iCoa->debit = $de_amount[$key];
                    if (isset($upload)){
                        $iCoa->file_hash = $hashFile;
                    }
                    $iCoa->description = $request->description;
                    $iCoa->created_by = Auth::user()->username;
                    $iCoa->company_id = Session::get('company_id');
                    $iCoa->save();
                }
            }
        }

        if (!empty($credit)){
            foreach ($credit as $key => $value){
                if ($value != null || !empty($value)){
                    $coa = explode(" ", $value);
                    $coa_code = str_replace(str_split('[]'), "", $coa[0]);
                    $iCoa = new Finance_coa_history();
                    $iCoa->md5 = $hash;
                    $iCoa->no_coa = $coa_code;
                    $iCoa->coa_date = $request->gj_date;
                    if (isset($upload)){
                        $iCoa->file_hash = $hashFile;
                    }
                    $iCoa->credit = $cre_amount[$key];
                    $iCoa->description = $request->description;
                    $iCoa->created_by = Auth::user()->username;
                    $iCoa->company_id = Session::get('company_id');
                    $iCoa->save();
                }
            }
        }

        return redirect()->route('gj.index');
    }

    function delete($x){
        if (Finance_coa_history::where('md5', $x)->delete()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function edit(Request $request){
        Finance_coa_history::where('md5', $request->md5)->delete();

        $debit = str_replace(",", "", $request->coa_code_debit);
        $de_amount = str_replace(",", "", $request->amount_debit);
        $credit = str_replace(",", "", $request->coa_code_credit);
        $cre_amount = str_replace(",", "", $request->amount_credit);
        $upload = false;
        if (!empty($request->file('file_upload'))){
            $file = $request->file('file_upload');

            $filename = str_replace(" ", "_", $file->getClientOriginalName());
            $hashFile = Hash::make($filename);
            $hashFile = str_replace("/", "", $hashFile);
            $upload = FileManagement::save_file_management($hashFile, $file, $filename, 'media/journal');
        }

        foreach ($debit as $key => $value){
            if ($value != null){
                $coa = explode(" ", $value);
                $coa_code = str_replace(str_split('[]'), "", $coa[0]);
                $iCoa = new Finance_coa_history();
                $iCoa->md5 = $request->md5;
                $iCoa->no_coa = $coa_code;
                $iCoa->coa_date = $request->gj_date;
                $iCoa->debit = $de_amount[$key];
                $iCoa->description = $request->description;
                if ($upload){
                    $iCoa->file_hash = $hashFile;
                }
                $iCoa->created_by = Auth::user()->username;
                $iCoa->company_id = Session::get('company_id');
                $iCoa->save();
            }
        }

        foreach ($credit as $key => $value){
            if ($value != null){
                $coa = explode(" ", $value);
                $coa_code = str_replace(str_split('[]'), "", $coa[0]);
                $iCoa = new Finance_coa_history();
                $iCoa->md5 = $request->md5;
                $iCoa->no_coa = $coa_code;
                $iCoa->coa_date = $request->gj_date;
                $iCoa->credit = $cre_amount[$key];
                $iCoa->description = $request->description;
                if ($upload){
                    $iCoa->file_hash = $hashFile;
                }
                $iCoa->created_by = Auth::user()->username;
                $iCoa->company_id = Session::get('company_id');
                $iCoa->save();
            }
        }

        return redirect()->route('gj.index');
    }

    function approve(Request $request){
        $gj = Finance_coa_history::where('md5', $request->hash)
            ->update([
                'approved_at' => date('Y-m-d H:i:s'),
                'approved_by' => Auth::user()->username
            ]);

        if ($gj){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }
}
