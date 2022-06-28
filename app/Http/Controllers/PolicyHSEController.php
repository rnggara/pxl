<?php

namespace App\Http\Controllers;

use App\Helpers\FileManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use DB;
use App\Models\Hse_policy_category;
use App\Models\Hse_policy_detail;
use App\Models\Hse_policy_main;

class PolicyHSEController extends Controller
{
    public function index(){
        $category = Hse_policy_category::where('company_id', \Session::get('company_id'))->get();
        $main = Hse_policy_main::join('hse_policy_category as cat','cat.id_category','=','hse_policy_main.category')
            ->select('hse_policy_main.*','cat.name_category as catName')
            ->where('hse_policy_main.company_id', \Session::get('company_id'))->get();

        $detailID = [];
        $detailRevision= [];
        $detailDate = [];
        $details = Hse_policy_detail::all();


        foreach ($details as $key => $value){
            $detailID[$value->id_policy_main] = $value->id_detail;
            $detailRevision[$value->id_policy_main] = $value->revision;
            $detailDate[$value->id_policy_main] = $value->date_detail;
        }
//        dd($detailDate);
//        dd($detailRevision);
//        dd($main);
        return view('qhse.policy.index',[
            'policy' => $main,
            'categories' => $category,
            'detailID' => $detailID,
            'detailRev' => $detailRevision,
            'detailDate' => $detailDate,
        ]);
    }

    public function printView($id, $type = null){
        $detail = Hse_policy_detail::where('id_detail', $id)->first();
        $main = Hse_policy_main::where('id_main', $detail->id_policy_main)->first();
//        dd($main);
        return view('qhse.policy.print',[
            'detail' => $detail,
            'main' => $main,
            'type' => $type,
        ]);
    }

    public function getDetailPolicy($id){
        $detail = Hse_policy_detail::where('id_policy_main', $id)->get();
        $main = Hse_policy_main::where('id_main', $id)->first();
        return view('qhse.policy.detail',[
            'details' => $detail,
            'main' => $main,
        ]);
    }

    public function viewApprove($id, $type = null){
        $detail = Hse_policy_detail::where('id_detail', $id)->first();
        $main = Hse_policy_main::where('id_main', $detail->id_policy_main)->first();
//        dd($main);
        return view('qhse.policy.view',[
            'detail' => $detail,
            'main' => $main,
            'type' => $type,
        ]);

    }

    public function approve(Request $request){
//        dd($request);
        if ($request['type'] == 'ack'){
            Hse_policy_detail::where('id_detail', $request['id'])
                ->update([
                    'acknowledge_by' => Auth::user()->username,
                    'acknowledge_date' => date('Y-m-d'),
                ]);
        } else {
            $d = DB::table('policy_detail')->where('id_policy_main',$request['main_id'])
                ->get();
            $countD = count($d);
//            dd($countD);
            if ($countD <= 0){
                $rev = 0;
            } else {
                $rev = $countD-1;
            }

            Hse_policy_detail::where('id_detail', $request['id'])
                ->update([
                    'acknowledge_by' => Auth::user()->username,
                    'acknowledge_date' => date('Y-m-d'),
                    'approved_by' => Auth::user()->username,
                    'approved_time' => date('Y-m-d'),
                    'revision' => $rev
                ]);
        }

        return redirect()->route('policy.hse.detail',['id' => $request['main_id']]);
    }
    public function storeDetailPolicy(Request $request){
        if (isset($request['edit'])){
            Hse_policy_detail::where('id_detail', $request['edit'])
                ->update([
                    'content' => $request['ed_topic'],
                    'content_eng' => $request['ed_topic_eng']
                ]);
            if ($request->hasFile('attachment_policy')){
                $file = $request->file('attachment_policy');
                $newFile = date('Y_m_d_H_i_s')."_attachment_policy.".$file->getClientOriginalExtension();
                $hashFile = Hash::make($newFile);
                $hashFile = str_replace("/", "", $hashFile);
                $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/policy_attachment");
                if ($upload == 1){
                    Hse_policy_detail::where('id_detail', $request['edit'])
                        ->update([
                            'attachment' => $newFile

                        ]);
                }
            }
        } else {
            $detail = new Hse_policy_detail();
            $detail->id_policy_main = $request['id_main'];
            $detail->content = $request['topic'];
            $detail->content_eng = $request['topic_eng'];
            $detail->created_by = Auth::user()->username;
            $detail->date_detail = date('Y-m-d H:i:s');
            $detail->created_at = date('Y-m-d H:i:s');
            if ($request->hasFile('attachment_policy')){
                $file = $request->file('attachment_policy');
                $newFile = date('Y_m_d_H_i_s')."_attachment_policy.".$file->getClientOriginalExtension();

                $hashFile = Hash::make($newFile);
                $hashFile = str_replace("/", "", $hashFile);

                $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/policy_attachment");
                if ($upload == 1){
                    $detail->attachment = $newFile;
                }
            }
            $detail->save();
        }

        return redirect()->route('policy.hse.detail', $request['id_main']);
    }

    public function deleteDetail($id,$id_main){

        Hse_policy_detail::where('id_detail',$id)->delete();

        return redirect()->route('policy.hse.detail',$id_main);
    }
    public function indexCategory(){
        $category = Hse_policy_category::where('company_id', \Session::get('company_id'))->get();
        return view('qhse.policy.category',[
            'categories' => $category,
        ]);
    }

    public function delete($id){
        Hse_policy_main::where('id_main',$id)
            ->update([
                'deleted_by' => Auth::user()->username
            ]);
        Hse_policy_main::where('id_main',$id)->delete();
        return redirect()->route('policy.hse.index');
    }
    public function store(Request $request){
        $policy = new Hse_policy_main();
        $policy->topic = $request['topic'];
        $policy->location = $request['location'];
        $policy->category = $request['category'];
        $policy->date_main = date("Y-m-d H:i:s");
        $policy->created_at = date("Y-m-d H:i:s");
        $policy->main_created_by = Auth::user()->username;
        $policy->company_id = \Session::get('company_id');
        $policy->save();
        return redirect()->route('policy.hse.index');
    }

    public function storeCategory(Request $request){
        if (isset($request['id'])){
            Hse_policy_category::where('id_category', $request['id'])
                ->update([
                    'name_category' => $request['name'],
                    'last_update_by' => Auth::user()->username,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } else {
            $category = new Hse_policy_category();
            $category->name_category = $request['name'];
            $category->created_by = Auth::user()->username;
            $category->created_at = date('Y-m-d H:i:s');
            $category->company_id = \Session::get('company_id');
            $category->save();
        }

        return redirect()->route('policy.hse.category');
    }

    function deleteCategory($id){
        $pol = Hse_policy_category::findOrFail($id);
        $pol->delete();
        return redirect()->back();
    }
}
