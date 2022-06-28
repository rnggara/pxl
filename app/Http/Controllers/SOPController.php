<?php

namespace App\Http\Controllers;

use App\Models\ConfigCompany;
use App\Models\Rms_divisions;
use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Qhse_sop_category;
use App\Models\Qhse_sop_detail;
use App\Models\Qhse_sop_main;

class SOPController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function sop_category(){
        $category = Qhse_sop_category::where('company_id', Session::get('company_id'))
            ->orderBy('name_category','ASC')
            ->get();
        return view('sop.category',[
            'categories' => $category,
        ]);
    }

    public function deleteCategory($id){
        Qhse_sop_category::find($id)->delete();
        return redirect()->back();
    }

    public function saveCategory(Request $request){
        $sop_cat = new Qhse_sop_category();
        $sop_cat->name_category = $request->name;
        $sop_cat->created_by = Auth::user()->username;
        $sop_cat->company_id = Session::get('company_id');
        $sop_cat->save();
        return redirect()->back();
    }

    public function index(){
        $category = Qhse_sop_category::where('company_id', Session::get('company_id'))->get();
        $division = Rms_divisions::all();
        return view('sop.index',[
            'divisions' => $division,
            'categories' => $category,
        ]);
    }

    public function getSOPMainAjax(){
        $sop_main = DB::select("SELECT date_main, qhse_sop_main.id AS id_main, topic, location, name_category, 
		(SELECT id FROM qhse_sop_detail WHERE qhse_sop_detail.approved_by IS NOT NULL AND qhse_sop_detail.id_sop_main = id_main ORDER BY qhse_sop_detail.id DESC LIMIT 1) AS id_detail, 
		(SELECT date_detail FROM qhse_sop_detail WHERE qhse_sop_detail.approved_by IS NOT NULL AND id_sop_main = id_main ORDER BY qhse_sop_detail.id DESC LIMIT 1) AS date_detail, 
		(SELECT created_by FROM qhse_sop_detail WHERE qhse_sop_detail.approved_by IS NOT NULL AND id_sop_main = id_main ORDER BY qhse_sop_detail.id DESC LIMIT 1) AS created_by, 
		(SELECT revision FROM qhse_sop_detail WHERE qhse_sop_detail.approved_by IS NOT NULL AND id_sop_main = id_main ORDER BY qhse_sop_detail.id DESC LIMIT 1) AS revision, 
		(SELECT id FROM qhse_sop_detail WHERE id_sop_main = id_main ORDER BY id DESC LIMIT 1) AS child_count 
		FROM qhse_sop_main, qhse_sop_category WHERE qhse_sop_main.category = qhse_sop_category.id AND qhse_sop_main.deleted_at IS NULL AND qhse_sop_main.company_id = ".Session::get('company_id'));
        $companytag = ConfigCompany::all();
        $tag = [];
        $row = [];
        $sop = [];
//        dd($sop_main);
        foreach ($companytag as $key =>$value){
            $tag[$value->id][] = $value->tag;
        }

        foreach ($sop_main as $key => $value){
            $sop['no'] = ($key+1);
            $sop['date'] = ($value->date_detail != null || $value->date_detail != '')? date('d M Y | H:i',strtotime($value->date_detail)) :'NO DETAIL';
            $sop['sop_num'] = ($value->date_detail != null || $value->date_detail != '')? $value->id_detail.'/'.strtoupper(Session::get('company_tag')).'-SOP/'.date('m/y',strtotime($value->date_detail)) :'NO DETAIL';
            $sop['category'] = $value->name_category;
            $sop['title'] = ($value->date_detail != null || $value->date_detail != '')? "<a href='".route('sop.detail_view',['id_detail' => $value->id_detail])."' class='btn btn-link dttb'><i class='fa fa-search'></i> ".$value->topic."</a>" :$value->topic;
            $sop['created_by'] = ($value->created_by != null || $value->created_by != '')? $value->created_by: 'No Approved SOP';
            $sop['division'] = $value->location;
            if ($value->child_count > 0){
                $btn_rev ="<a href='".route('sop.detail',['id_main' => $value->id_main])."' class='btn btn-primary btn-xs btn-icon'title='Revision'><i class='fa fa-pencil-alt'></i></a>";
            } else {
                $btn_rev = "<a href='".route('sop.add_detail',['id_main' => $value->id_main,'status' => 0])."' class='btn btn-primary btn-xs btn-icon' title='Revision'><i class='fa fa-pencil-alt'></i></a>";
            }
            $sop['revision'] = ($value->revision != null || $value->revision != '') ? $value->revision." ".$btn_rev : "".$btn_rev;
            $sop['action'] = "<a onclick='return confirm(\"Delete Data?\")' href='".route('sop.deletemain',['id' => $value->id_main])."' class='btn btn-xs btn-default dttb'><i class='fa fa-trash'></i></a>";

            $row[] = $sop;
        }

        $data = [
            'data' => $row,
        ];

        return json_encode($data);
    }

    public function storeMain(Request $request){
        $sop_main = new Qhse_sop_main();
        $sop_main->topic = $request->title;
        $sop_main->location = $request->division;
        $sop_main->category = $request->category;
        $sop_main->company_id = Session::get('company_id');
        $sop_main->date_main = date("Y-m-d H:i:s");
        $sop_main->save();
        return redirect()->back();
    }

    public function deleteMain($id){
        Qhse_sop_main::where('id', $id)->delete();
        Qhse_sop_detail::where('id_sop_main', $id)->delete();
        return redirect()->route('sop.index');
    }
    public function getDetailSOP($id_main){
        $sop_main = Qhse_sop_main::where('id', $id_main)->first();
        $detail = Qhse_sop_detail::where('id_sop_main', $id_main)
            ->orderBy('id','DESC')
            ->get();

        return view('sop.detail',[
            'details' => $detail,
            'sop_main'=> $sop_main,
        ]);
    }

    public function getAddDetail($id_main,$status,$id_detail=null){
        $sop_main = Qhse_sop_main::where('id', $id_main)->first();
        if ($id_detail == null) {
            return view('sop.add_detail', [
                'sop_main' => $sop_main,
                'status' => $status
            ]);
        } else {
            $sop_detail = Qhse_sop_detail::where('id', $id_detail)->first();
            return view('sop.add_detail', [
                'sop_main' => $sop_main,
                'status' => $status,
                'sop_detail' => $sop_detail,
            ]);
        }
    }

    public function saveDetail(Request $request){
        $sop_detail = new Qhse_sop_detail();
        $sop_detail->id_sop_main = $request->id_main;
        $sop_detail->content = $request->topic;
        $sop_detail->content_eng = $request->topic_eng;
        $sop_detail->date_detail = date("Y-m-d H:i:s");
        $sop_detail->created_by = Auth::user()->username;
        $sop_detail->save();

        return redirect()->route('sop.detail',['id_main' => $request->id_main]);
    }

    public function getSOPDetailView($id_detail,$act =null){
        $sop_detail = Qhse_sop_detail::where('id', $id_detail)->first();
        $sop_main = Qhse_sop_main::where('id', $sop_detail->id_sop_main)->first();

        return view('sop.detail_view', [
            'sop_main' => $sop_main,
            'sop_detail' => $sop_detail,
            'act' => $act
        ]);
    }

    public function approval($id_detail,$id_main, $act){
        if ($act == 'ack'){
            Qhse_sop_detail::where('id', $id_detail)
                ->update([
                    'acknowledge_by' => Auth::user()->username,
                    'acknowledge_date' => date("Y-m-d"),
                ]);
        } else {
            $q = DB::select("SELECT COUNT(id) AS jml_rev FROM qhse_sop_detail WHERE approved_time IS NOT NULL AND id_sop_main =".$id_main." LIMIT 1");
            $jml_rev = $q[0]->jml_rev;
//             dd($q);

            Qhse_sop_detail::where('id', $id_detail)
                ->update([
                    'approved_by' => Auth::user()->username,
                    'approved_time' => date("Y-m-d"),
                ]);
        }

        return redirect()->route('sop.detail',['id_main' => $id_main]);
    }
}
