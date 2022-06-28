<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Asset_po;
use App\Models\Asset_wo;
use App\Models\Finance_coa;
use App\Models\Finance_loan;
use Illuminate\Http\Request;
use App\Models\Asset_type_po;
use App\Models\Asset_type_wo;
use App\Models\ConfigCompany;
use App\Models\Finance_leasing;
use App\Models\General_cashbond;
use App\Models\General_reimburse;
use App\Models\Marketing_project;
use App\Models\Finance_coa_source;
use App\Models\Finance_invoice_in;
use App\Models\Finance_coa_history;
use App\Models\Finance_invoice_out;
use App\Models\Finance_util_master;
use Illuminate\Support\Facades\Auth;
use App\Models\General_cashbond_detail;
use App\Models\General_reimburse_detail;
use App\Models\Finance_invoice_out_detail;

class FinanceCOAController extends Controller
{
    public function index(){
        $coa = Finance_coa::whereNull('deleted_at')
            ->orderBy('code','ASC')->get();
        $parent_name = [];
        $id_parent = [];
        $code=[];
        $src = [];
        foreach ($coa as $key => $value){
            $parent_name[$value->code] = $value->name;
            $id_parent[$value->code] = $value->parent_id;
            $code[] = $value->code;
            if(!empty($value->source)){
                $sr = json_decode($value->source, true);
                foreach($sr as $s){
                    $src[] = $s;
                }
            }
        }

        $source = Finance_coa_source::whereNotIn('id', $src)->get()->pluck('description', 'id');
        $srcAll = Finance_coa_source::all()->pluck('description', 'id');
        $coa_show = Finance_coa::whereNull('parent_id')->orderBy('code')->get();
        return view('coa.index',[
            'coa' => $coa,
            'parents' => $parent_name,
            'id_parents' => $id_parent,
            'code' => $code,
            'source' => $source,
            'srcAll' => $srcAll,
            'coa_show' => $coa_show
        ]);
    }

    function edit_view($id){
        $coa = Finance_coa::find($id);
        $src = [];
        $coa_all = Finance_coa::whereNull('deleted_at')
            ->orderBy('code','ASC')->get();
        foreach ($coa_all as $key => $value){
            $parent_name[$value->code] = $value->name;
            $id_parent[$value->code] = $value->parent_id;
            $code[] = $value->code;
            if(!empty($value->source)){
                $sr = json_decode($value->source, true);
                foreach($sr as $s){
                    $src[] = $s;
                }
            }
        }

        $source = Finance_coa_source::whereNotIn('id', $src)->get()->pluck('description', 'id');
        $srcAll = Finance_coa_source::all()->pluck('description', 'id');
        return view('coa._edit',[
            'coa' => $coa_all,
            'source' => $source,
            'srcAll' => $srcAll,
            'value' => $coa
        ]);
    }

    function has_child($code){
        $childs = Finance_coa::where('parent_id', $code)->get();
        if(count($childs) > 0){
            return true;
        }

        return false;
    }

    function list_child(Request $request){
        $coa_parent = Finance_coa::find($request->parent);
        $coa = Finance_coa::where("parent_id", $coa_parent->code)->get();
        $data = [];
        foreach($coa as $item){
            $row = [];
            $row['id'] = $item->id;
            $bg = "text-danger";
            if($item->status == 1 || empty($item->status)){
                $bg = "text-warning";
            }
            $row['icon'] = "fa fa-folder icon-lg $bg";
            $child = false;
            if($this->has_child($item->code)){
                $child = true;
            }
            $row['children'] = $child;
            $row['status'] = $item->status;
            $row['text'] = "[$item->code] $item->name";
            $data[] = $row;
        }

        return $data;
    }

    function list($id){
        // $coa_parent = Finance_coa::find($id);
        $coa = Finance_coa::where("id", $id)->get();
        $data = [];
        foreach($coa as $item){
            $row = [];
            $row['id'] = $item->id;
            $bg = "text-danger";
            if($item->status == 1 || empty($item->status)){
                $bg = "text-warning";
            }
            $row['icon'] = "fa fa-folder icon-lg $bg";
            $child = false;
            if($this->has_child($item->code)){
                $child = true;
            }
            $row['children'] = $child;
            $row['status'] = $item->status;
            $row['text'] = "[$item->code] $item->name";
            $data[] = $row;
        }

        return $data;
    }

    function getCoa(){
        $t = $_GET['term'];
        $val = [];
        $coa = Finance_coa::select('id','code','name')
            ->where('code', 'like', "".$t."%")
            ->where('status', 1)
            ->orWhere('name', 'like', "%".$t."%")
            ->whereNull('deleted_at')->get();
        foreach ($coa as $value){
            $val[] = "[".$value->code."] ".$value->name;
        }
        return json_encode($val);
    }

    function view($x){
        $coa = Finance_coa::where('code', $x)->first();

        return view('coa.view', [
            'coa' => $coa
        ]);
    }

    function find(Request $request){
//        dd($request);
        $coa = Finance_coa::where('parent_id', $request->code)
            ->where('status', 1)
            ->get();
        $list_coa = [];
        $list_coa[] = $request->code;
        foreach ($coa as $item){
            $list_coa[] = $item->code;
        }
        $coa_his = Finance_coa_history::whereBetween('coa_date', [$request->start, $request->end])
            ->whereIn('no_coa', $list_coa)
            ->whereNotNull('approved_at')
            ->where('company_id', Session::get('company_id'))
            ->get();
        $val = [];
        $data = [];
        foreach ($coa_his as $key => $item){
            $row = [];
            $row[] = $key+1;
            $row[] = $item->coa_date;
            $row[] = $item->description;
            $row[] = number_format($item->credit, 2);
            $row[] = number_format($item->debit, 2);
            $data[] = $row;
        }

        $val['data'] = $data;

        return json_encode($val);
    }

    public function store(Request $request){
        $src = Finance_coa_source::all()->pluck('insert', 'id');
        $src_description = Finance_coa_source::all()->pluck('description', 'id');
        $company = ConfigCompany::all()->pluck('id');
        if (isset($request['edit'])){
            $coaUpdate = Finance_coa::find($request['id']);
            $coaUpdate->name = $request['name'];
            if($request['newcode'] != null){
                $coaUpdate->code = $request['newcode'];
            }
            $coaUpdate->source = (!empty($request['source'])) ? json_encode($request['source']) : null;
            $coaUpdate->save();
            if (isset($request['parentcode'])){
                if (!empty($request['parent_code'])) {
                    Finance_coa::where('id', $request['id'])
                    ->update([
                        'parent_id' => $request['parentcode'],
                    ]);
                }
            } else {
                Finance_coa::where('id', $request['id'])
                    ->update([
                        'parent_id' =>null,
                    ]);
            }
        } else {
            $coa = new Finance_coa();
            if($request['newcode'] != null){
                $coa->code = $request['newcode'];
            }
            $coa->name = $request['name'];
            $coa->source = (!empty($request['source'])) ? json_encode($request['source']) : null;

            if (isset($request['parentcode'])){
                $coa->parent_id = $request['parentcode'];
            } else {
                $coa->parent_id = null;
            }
            $coa->save();

            if(!empty($coa->source)){
                $jsCoa = json_decode($coa->source);
                foreach($jsCoa as $source){
                    if(isset($src[$source]) && $src[$source] == 1){
                        $desc = $src_description[$source];
                        foreach ($company as $key => $value) {
                            $history = new Finance_coa_history();
                            $history->no_coa = $coa->code;
                            $history->description = $desc;
                            $history->coa_date = date("Y-m-d");
                            $history->debit = 0;
                            $history->company_id = $value;
                            $history->save();
                        }
                    }
                }
            }
        }


        return redirect()->route('coa.index');

    }

    public function delete($id){
        Finance_coa::where('id',$id)->delete();
        Finance_coa::where('parent_id',$id)->delete();

        return redirect()->route('coa.index');
    }

    function update($id){
        $coa = Finance_coa::find($id);

        $status = null;
        if($coa->status == 1 || empty($coa->status)){
            $status = 2;
        } else {
            $status = 1;
        }

        $coa->status = $status;
        $coa->save();
        return redirect()->back();
        // if ($request->act == "active"){
        //     $coa->status = 0;
        //     $data['status'] = "inactive";
        // } else {
        //     $coa->status = 1;
        //     $data['status'] = "active";
        // }

        // if ($coa->save()){
        //     $data['error'] = 0;
        // } else {
        //     $data['error'] = 1;
        // }

        // return json_encode($data);
    }

    function source(){
        $src = Finance_coa_source::where('view', 1)->get()->pluck('description', 'id');
        $coa = Finance_coa::all();
        return view('coa.source.index', compact('src', 'coa'));
    }

    function source_data(Request $request){
        $id_companies = $this->child(Session::get('company_id'));
        $id_companies[] = Session::get('company_id');
        $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $src = Finance_coa_source::find($request->id);
        $tc = Finance_coa::all()->pluck('code', 'id');
        $tc_name = Finance_coa::all()->pluck('name', 'id');
        $data = [];
        switch (strtolower($src->name)) {
            case 'po':
                $po = Asset_po::whereIn('company_id', $id_companies)
                    ->where('po_date', '>=', '2020-01-01')
                    ->orderBy('tc_id')
                    ->orderBy('po_date', 'desc')
                    ->get();
                foreach ($po as $key => $value) {
                    $row = [];
                    $row['i'] = $key + 1;
                    $row['id'] = $value->id;
                    $row['paper'] = $value->po_num;
                    $row['type'] = $request->id;
                    $row['date'] = date("d F Y", strtotime($value->po_date));
                    $row['url'] = route('po.view', $value->id);
                    $row['tc_id'] = $value->tc_id;
                    $row['code'] = (isset($tc[$value->tc_id])) ? "[".$tc[$value->tc_id]."] ".$tc_name[$value->tc_id] : null;
                    $data[] = $row;
                }
                break;
            case 'wo':
                $wo = Asset_wo::whereIn('company_id', $id_companies)
                    ->where('req_date', '>=', '2020-01-01')
                    ->orderBy('tc_id')
                    ->orderBy('req_date', 'desc')
                    ->get();
                foreach ($wo as $key => $value) {
                    $row = [];
                    $row['i'] = $key + 1;
                    $row['id'] = $value->id;
                    $row['paper'] = $value->wo_num;
                    $row['type'] = $request->id;
                    $row['date'] = date("d F Y", strtotime($value->req_date));
                    $row['url'] = route('wo.view', $value->id);
                    $row['tc_id'] = $value->tc_id;
                    $row['code'] = (isset($tc[$value->tc_id])) ? "[".$tc[$value->tc_id]."] ".$tc_name[$value->tc_id] : null;
                    $data[] = $row;
                }
                break;
            case 'ut':
                $wo = Finance_util_master::whereIn('company_id', $id_companies)
                    ->whereRaw("(created_at >= '2020-01-01' or recurrent_date >= '2020-01-01')")
                    ->orderBy('id', 'desc')
                    ->get();
                $company_tag = ConfigCompany::all()->pluck('tag', 'id');
                foreach ($wo as $key => $value) {
                    $date = (!empty($value->created_at)) ? $value->created_at : $value->recurrent_date;
                    $paper_id = $value->id;
                    $tag = strtoupper($company_tag[$value->company_id]);
                    if($value->id < 100){
                        $paper_id = sprintf("%03d", $value->id);
                    }
                    $paper = "$paper_id/$tag/UTILIZATION/".$array_bln[date("n", strtotime($date))]."/".date("y", strtotime($date));
                    $row = [];
                    $row['i'] = $key + 1;
                    $row['id'] = $value->id;
                    $row['paper'] = $paper;
                    $row['type'] = $request->id;
                    $row['date'] = date("d F Y", strtotime($date));
                    $row['url'] = route('util.view', $value->id);
                    $row['tc_id'] = $value->tc_id;
                    $row['code'] = (isset($tc[$value->tc_id])) ? "[".$tc[$value->tc_id]."] ".$tc_name[$value->tc_id] : null;
                    $data[] = $row;
                }
                break;
            case 'cb':
                $type = strtolower($src->name);
                $cb = General_cashbond::whereIn('company_id', $id_companies)->get();
                $detail = General_cashbond_detail::whereIn('id_cashbond', $cb->pluck('id'))
                    ->where('cashout', '>', 0)
                    ->get();
                $t_wo = [];
                foreach($detail as $item){
                    $t_wo[$item->category][] = $item->tc_id;
                }
                $typewo = Asset_type_wo::orderBy('name')->get()->pluck('name', 'id');
                $tid = $request->id;
                $category = "cashbond";
                return view('coa.source._table_alt', compact('type', 'typewo', 't_wo', 'tc', 'tid', 'category'));
                break;
            case 'rb':
                $type = strtolower($src->name);
                $cb = General_reimburse::whereIn('company_id', $id_companies)->get();
                $detail = General_reimburse_detail::whereIn('id_reimburse', $cb->pluck('id'))
                    ->where('cashout', '>', 0)
                    ->get();
                $t_wo = [];
                foreach($detail as $item){
                    $t_wo[$item->category][] = $item->tc_id;
                }
                $typewo = Asset_type_wo::orderBy('name')->get()->pluck('name', 'id');
                $tid = $request->id;
                $category = "reimburse";
                return view('coa.source._table_alt', compact('type', 'typewo', 't_wo', 'tc', 'tid', 'category'));
                break;
        }

        return view('coa.source._table', compact('data'));
    }

    function child($id){
        $child = ConfigCompany::where('id_parent', $id)->get();
        $_child = [];
        foreach($child as $item){
            $_child[] = $item->id;
            $nChild = $this->child($item->id);
            if(!empty($nChild)){
                // $_child[] = $nChild;
                foreach($nChild as $nI){
                    $_child[] = $nI;
                }
            }
        }

        return $_child;
    }

    function source_items($id, Request $request){
        $src = Finance_coa_source::find($id);
        $tc = Finance_coa::where('source', 'like', '%"'.$src->id.'"%')->get()->pluck('code');
        // $parent_code = rtrim($tc[0], 0);
        $childs = Finance_coa::where(function($query) use($tc){
            foreach ($tc as $key => $value) {
                $parent_code = rtrim($value, 0);
                $query->where('parent_id', 'like', "$parent_code%");
            }
        })
        ->whereRaw("(code like '%$request->q%' or name like '%$request->q%')")
        ->orderBy('code')->get();

        // if(in_array($src->name, ['cb', 'rb'])){
        //     $childs = Finance_coa::where('parent_id', $tc)
        //         ->whereRaw("(code like '$request->q%' or name like '%$request->q%')")
        //         ->orderBy('code')->get();
        // }
            // ->whereRaw("(code like '$request->q%' or name like '%$request->q%')")
            // ->get();

        // dd($childs->toSql());

        $data = [];
        $row = [];
        $row['id'] = "";
        // $row['text'] = "Select";
        // $row['text'] .= (!empty(\Session::get('company_tc_name'))) ? \Session::get('company_tc_name') : "Transaction Code";
        $data[] = $row;
        foreach($childs as $item){
            $row = [];
            $row['id'] = $item->id;
            $row['text'] = "[$item->code] $item->name";
            $data[] = $row;
        }

        $result = array(
            "results" => $data
        );

        return json_encode($result);
    }

    function source_sign(Request $request){
        $tc = Finance_coa::all()->pluck('name', 'id');
        $src = Finance_coa_source::find($request->type);
        $return = 0;
        if($src->name == "po"){
            $po = Asset_po::find($request->id);
            $po->po_type = $tc[$request->code];
            $po->tc_id = $request->code;
            $return = ($po->save()) ? 1 : 0;
        } elseif ($src->name == "wo") {
            $wo = Asset_wo::find($request->id);
            $wo->wo_type = $tc[$request->code];
            $wo->tc_id = $request->code;
            $return = ($wo->save()) ? 1 : 0;
        } elseif ($src->name == "ut") {
            $util = Finance_util_master::find($request->id);
            $util->tc_id = $request->code;
            $return = ($util->save()) ? 1 : 0;
        } elseif ($src->name == "cb") {
            $co = Finance_coa::find($request->code);
            $co_parent = Finance_coa::where('code', $co->parent_id)->first();
            $detail = General_cashbond_detail::find($request->id);
            $detail->tc_id = $request->code;
            $detail->tc_id_parent = $request->code;
            if(!empty($co_parent) && empty($co_parent->source)){
                $detail->tc_id_parent = $co_parent->id;
            }
            if($detail->save()){
                $return = 1;
            }
        } elseif ($src->name == "rb") {
            $co = Finance_coa::find($request->code);
            $co_parent = Finance_coa::where('code', $co->parent_id)->first();
            $detail = General_reimburse_detail::find($request->id);
            $detail->tc_id = $request->code;
            $detail->tc_id_parent = $request->code;
            if(!empty($co_parent) && empty($co_parent->source)){
                $detail->tc_id_parent = $co_parent->id;
            }
            if($detail->save()){
                $return = 1;
            }
        }

        if($return == 1){
            return redirect()->back()->with('msg', "#nav_".$src->id);
        }
    }

    function assignment($type, $id){
        $id_companies = $this->child(Session::get('company_id'));
        $id_companies[] = Session::get('company_id');
        $data['typewo'] = Asset_type_wo::find($id);
        $company_tag = ConfigCompany::all()->pluck('tag', 'id');
        $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $cat = strtoupper($type);
        if ($type == "cashbond") {
            $parent_detail = General_cashbond::whereIn('company_id', $id_companies)->get();
            $detail = General_cashbond_detail::whereIn('id_cashbond', $parent_detail->pluck('id'))
                ->where('category', $id)
                ->where('cashout', '>', '0')
                ->orderBy('tc_id')->get();
            $src_code = "cb";
        } else {
            $parent_detail = General_reimburse::whereIn('company_id', $id_companies)->get();
            $detail = General_reimburse_detail::whereIn('id_reimburse', $parent_detail->pluck('id'))
            ->where('category', $id)
            ->where('cashout', '>', '0')
            ->orderBy('tc_id')->get();
            $src_code = "rb";
        }

        $parent_tag = $parent_detail->pluck('company_id', 'id');
        $table = [];
        foreach($detail as $item){
            $row = [];
            $parent = "id_$type";
            $paper_tag = $company_tag[$parent_tag[$item->$parent]];
            $m = $array_bln[date("n", strtotime($item->tanggal))];
            $y = date("y", strtotime($item->tanggal));
            $row['description'] = $item->deskripsi;
            $row['paper'] = sprintf("%03d", $item->$parent)."/$paper_tag/$cat/$m/$y";
            $row['amount'] = $item->cashout;
            $row['id'] = $item->id;
            $row['category'] = $item->category;
            $row['tc_id'] = $item->tc_id;
            $table[] = $row;
        }

        $data['category'] = $type;

        $data['coa'] = Finance_coa::all()->pluck('code', 'id');
        $data['coa_name'] = Finance_coa::all()->pluck('name', 'id');

        $data['src'] = Finance_coa_source::where('name', $src_code)->first();

        $data['table'] = $table;
        // dd($data, $detail);
        return view('coa.source._assignment', compact('data'));
    }

    function assign($type, $id){

        $coa_his = [];

        $row = [];
        if($type == "invoice-in"){
            $inv = Finance_invoice_in::find($id);
            if($inv->paper_type == "PO"){
                $desc = Asset_po::find($inv->paper_id)->po_num;
                $prj = Asset_po::find($inv->paper_id)->project;
                $curr = Asset_po::find($inv->paper_id)->currency;
            } else {
                $desc = Asset_wo::find($inv->paper_id)->wo_num;
                $prj = Asset_wo::find($inv->paper_id)->project;
                $curr = Asset_wo::find($inv->paper_id)->currency;
            }
            $row['description'] = "INVOICE IN : $desc [$prj]";
            $row['amount'] = $inv->amount;
            $row['company_id'] = $inv->company_id;
            $row['currency'] = $curr;
            $row['id'] = $inv->id;
        } elseif($type == "loan"){
            $loan = Finance_loan::find($id);
            $row['description'] = "LOAN : $loan->bank - $loan->description ";
            $row['amount'] = $loan->value;
            $row['company_id'] = $loan->company_id;
            $row['currency'] = $loan->currency;
            $row['id'] = $loan->id;
        } elseif($type == "leasing"){
            $leasing = Finance_leasing::find($id);
            $row['description'] = "Leasing : $leasing->subject - $leasing->vendor ";
            $row['amount'] = $leasing->value;
            $row['company_id'] = $leasing->company_id;
            $row['currency'] = $leasing->currency;
            $row['id'] = $leasing->id;
        } elseif($type == "invoice-out"){
            $inv_detail = Finance_invoice_out_detail::find($id);
            $inv = Finance_invoice_out::find($inv_detail->id_inv);

            $prj = Marketing_project::find($inv->id_project);

            $row['description'] = "INVOICE OUT : ".$inv_detail->no_inv." [$prj->id]";
            $row['amount'] = $inv_detail->value_d;
            $row['company_id'] = $inv->company_id;
            $row['currency'] = $prj->currency;
            $row['taxes'] = (!empty($inv_detail->taxes)) ? json_decode($inv_detail->taxes, true) : [];
            $row['id'] = $inv_detail->id;
        }

        $coa_his = Finance_coa_history::where('paper_type', $type)
            ->where('paper_id', $id)
            ->get();

        $coa_name = Finance_coa::get()->pluck('name', 'code');

        return view('coa.assign', compact('coa_his', 'row', 'type', 'coa_name'));
    }

    function assign_post($type, $id, Request $request){
        $debit = $request->debit;
        $credit = $request->credit;
        $de_amount = $request->de_amount;
        $cre_amount = $request->cre_amount;

        Finance_coa_history::where('paper_type', $type)
            ->where('paper_id', $id)
            ->forceDelete();

        for ($i=0; $i < count($debit); $i++) {
            if(!empty($debit[$i]) && !empty($de_amount[$i])){
                $coa = explode(" ", $debit[$i]);
                $coa_code = str_replace(str_split('[]'), "", $coa[0]);

                $coa_his = new Finance_coa_history();
                $coa_his->no_coa = $coa_code;
                $coa_his->paper_type = $type;
                $coa_his->paper_id = $id;
                $coa_his->description = $request->_desc;
                $coa_his->debit = abs($de_amount[$i]);
                $coa_his->currency = $request->currency;
                $coa_his->company_id = $request->_comp_id;
                $coa_his->coa_date = date("Y-m-d");
                $coa_his->created_by = Auth::user()->username;
                $coa_his->save();
            }
        }

        for ($i=0; $i < count($credit); $i++) {
            if(!empty($credit[$i]) && !empty($cre_amount[$i])){
                $coa = explode(" ", $credit[$i]);
                $coa_code = str_replace(str_split('[]'), "", $coa[0]);

                $coa_his = new Finance_coa_history();
                $coa_his->no_coa = $coa_code;
                $coa_his->paper_type = $type;
                $coa_his->paper_id = $id;
                $coa_his->description = $request->_desc;
                $coa_his->credit = abs($cre_amount[$i]);
                $coa_his->currency = $request->currency;
                $coa_his->company_id = $request->_comp_id;
                $coa_his->coa_date = date("Y-m-d");
                $coa_his->created_by = Auth::user()->username;
                $coa_his->save();
            }
        }

        return redirect()->back();
    }

}
