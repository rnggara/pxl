<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hrd_announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HrdAnnouncementController extends Controller
{
    function index(){
        $announcement = Hrd_announcement::where('company_id', Session::get('company_id'))
            ->orderBy('created_at', 'desc')
            ->get();
        return view('announcement.index', compact('announcement'));
    }

    function add(Request $request){
        $ann = new Hrd_announcement();
        $ann->title = $request->title;
        $ann->description = $request->description;
        $ann->created_by = Auth::user()->username;
        $ann->company_id = Session::get('company_id');
        $ann->save();

        return redirect()->back();
    }

    function detail($id){
        $ann = Hrd_announcement::find($id);

        if (!empty($ann)) {
            $success = true;
            $data = $ann;
        } else {
            $success = false;
            $data = "Not fount";
        }


        $result = array(
            'success' => $success,
            'data' => $data
        );

        return json_encode($result);
    }

    function delete($id){
        $ann = Hrd_announcement::find($id);
        $ann->deleted_by = Auth::user()->username;
        $ann->save();
        $ann->delete();

        return redirect()->back();
    }

    function activate($id){
        $ann = Hrd_announcement::find($id);

        Hrd_announcement::where('company_id', Session::get('company_id'))
            ->update([
                'status' => 0
            ]);

        $ann->status = 1;
        $ann->save();
        return redirect()->back();
    }
}
