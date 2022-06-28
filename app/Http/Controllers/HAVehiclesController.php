<?php

namespace App\Http\Controllers;

use App\Helpers\FileManagement;
use App\Models\Asset_certificate;
use App\Models\Asset_vehicles;
use App\Models\Asset_vehicles_category;
use App\Models\Asset_vehicles_maintenance;
use App\Models\Division;
use App\Models\Procurement_vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Session;

class HAVehiclesController extends Controller
{
    function index(){
        $division = Division::all();
        $div = array();
        foreach ($division as $item){
            $div[$item->id] = $item->name;
        }
        $category = Asset_vehicles_category::where('company_id', Session::get('company_id'))->get();
        $vendor = Procurement_vendor::where('company_id', Session::get('company_id'))->get();
        $paper = Asset_certificate::where('type', 'STNK')
            ->select('asset_certificate.*','asset_vehicles.name as veName')
            ->leftJoin('asset_vehicles', 'asset_certificate.id', '=', 'asset_vehicles.certificate_id')
            ->where('asset_certificate.company_id', Session::get('company_id'))
            ->get();
        $ve = Asset_vehicles::where('company_id', Session::get('company_id'))->get();
        return view('ha.vehicles.index', [
            'category' => $category,
            'div' => $div,
            'vendors' => $vendor,
            'papers' => $paper,
            'vehicles' => $ve
        ]);
    }

    function view_vehicle($id){
        $ve = Asset_vehicles::find($id);
        $mt = Asset_vehicles_maintenance::where('id_vehicles', $id)
            ->orderBy('mt_date', 'desc')
            ->get();

        return view('ha.vehicles._maintenance', [
            'vehicle' => $ve,
            'mt' => $mt
        ]);
    }

    function edit_vehicle($id){
        $ve = Asset_vehicles::find($id);
        $category = Asset_vehicles_category::where('company_id', Session::get('company_id'))->get();
        $vendor = Procurement_vendor::where('company_id', Session::get('company_id'))->get();
        $cert = Asset_certificate::where('company_id', Session::get('company_id'))
            ->where('type', 'STNK')
            ->get();

        return view('ha.vehicles._edit_vehicles', compact('ve', 'category', 'vendor', 'cert'));
    }

    function edit_paper($id){
        $cert = Asset_certificate::find($id);
        return view('ha.vehicles._edit_paper', compact('cert'));
    }

    function edit_maintenance($id){
        $mt = Asset_vehicles_maintenance::find($id);
        $result = array(
            'data' => $mt
        );

        return json_encode($result);
    }

    function add_category(Request $request){
        $cat = new Asset_vehicles_category();
        $cat->name = $request->category_name;
        $cat->view = json_encode($request->view);
        $cat->created_by = Auth::user()->username;
        $cat->company_id = Session::get('company_id');
        $cat->save();

        return redirect()->back()->with(['msg' => 'Category has been added', 'tab' => 'vehicle']);
    }

    function add_maintenance(Request $request){
        if (isset($request->id_mt)){
            $mt = Asset_vehicles_maintenance::find($request->id_mt);
            $msg = "Item has been updated";
        } else {
            $mt = new Asset_vehicles_maintenance();
            $mt->id_vehicles = $request->id_ve;
            $msg = "New item has been added";
            $mt->created_by  = Auth::user()->username;
            $mt->company_id  = Session::get('company_id');
        }
        $mt->part_number = $request->part_number;
        $mt->description = $request->description;
        $mt->price       = str_replace(",", "", $request->price);
        $mt->mt_date     = $request->mt_date;

        $mt->save();
        return redirect()->back()->with('msg', $msg);
    }

    function add_paper(Request $request){
        $rules = array(
            'paper_name' => 'required',
            'paper_number' => 'required',
            'paper_date' => 'required',
            'paper_value' => 'required',
            'paper_owner' => 'required',
            'paper_holder' => 'required',
            'paper_spec' => 'required',
            'stnk_y_c' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){
            return Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ));
        }

        $paper = new Asset_certificate();
        $paper->name = $request->paper_name;
        $paper->view = 1;
        $paper->exp_date = $request->paper_date;
        $paper->type = "STNK";
        $paper->certificate_no = $request->paper_number;
        $paper->certificate_owner = $request->paper_owner;
        $paper->certificate_holder = $request->paper_holder;
        $paper->certificate_value = str_replace(",", "", $request->paper_value);
        $paper->description = $request->paper_spec;
        $paper->others = $request->stnk_y_c;
        $paper->company_id = Session::get('company_id');
        $paper->created_by = Auth::user()->username;
        $paper->save();

        if ($request->_action == "ajax"){
            return Response::json(array(
                'success' => true,
                'messages' => "Papers has been saved"
            ));
        } else {
            return redirect()->back()->with(['msg' => 'Papers has been added', 'tab' => 'paper']);
        }
    }

    function update_paper(Request $request){
        $paper = Asset_certificate::find($request->id_paper);
        $paper->name = $request->paper_name;
        $paper->view = 1;
        $paper->exp_date = $request->paper_date;
        $paper->certificate_no = $request->paper_number;
        $paper->certificate_owner = $request->paper_owner;
        $paper->certificate_holder = $request->paper_holder;
        $paper->certificate_value = str_replace(",", "", $request->paper_value);
        $paper->description = $request->paper_spec;
        $paper->others = $request->stnk_y_c;
        $paper->save();

        return redirect()->back()->with(['msg' => 'Papers has been updated', 'tab' => 'paper']);
    }

    function upload_paper(Request $request){
        $file = $request->file('picture');
        $filename = explode(".", $file->getClientOriginalName());
        array_pop($filename);
        $filename = str_replace(" ", "_", implode("_", $filename));

        $newFile = $filename."-".date('Y_m_d_H_i_s')."(".$request->id.").".$file->getClientOriginalExtension();
        $hashFile = Hash::make($newFile);
        $hashFile = str_replace("/", "", $hashFile);
        $upload = FileManagement::save_file_management($hashFile, $file, $newFile, 'media\asset\vehicles');
        if ($upload == 1){
            $document = Asset_certificate::find($request->id_paper);
            $document->picture = $hashFile;
            $document->save();
        }

        return redirect()->back()->with(['msg' => 'File has been uploaded', 'tab' => 'paper']);
    }

    function add_vehicle(Request $request){
        $ve = new Asset_vehicles();
        $ve->name = $request->name;
        $ve->category = $request->category;
        $ve->vendor_id = $request->vendor;
        $ve->description = $request->specification;
        $ve->bpkb_no = $request->bpkb;
        $ve->bpkb_name = $request->bpkb_name;
        $ve->certificate_id = $request->stnk;
        $ve->status = 1;
        $ve->created_by = Auth::user()->username;
        $ve->company_id = Session::get('company_id');
        $ve->save();

        return redirect()->back()->with(['msg' => 'Vehicle has been added', 'tab' => 'vehicle']);
    }

    function update_vehicle(Request $request){
        $ve = Asset_vehicles::find($request->id_ve);
        $ve->name = $request->name;
        $ve->category = $request->category;
        $ve->vendor_id = $request->vendor;
        $ve->description = $request->specification;
        $ve->bpkb_no = $request->bpkb;
        $ve->bpkb_name = $request->bpkb_name;
        $ve->certificate_id = $request->stnk;
        $ve->used_by = $request->used_by;
        $ve->location = $request->location;
        $ve->save();

        return redirect()->back()->with(['msg' => 'Vehicle has been updated', 'tab' => 'vehicle']);

    }

    function papers_js(Request $request){
        $data = array();
        $vehicles = Asset_vehicles::where('company_id', Session::get('company_id'))->get();
        $id_papers = array();
        foreach ($vehicles as $item){
            $id_papers[] = $item->certificate_id;
        }
        if ($request->_action == "add"){
            $paper = Asset_certificate::where('type', 'STNK')
                ->where('company_id', Session::get('company_id'))
                ->whereNotIn('id', $id_papers)
                ->get();
        } else {
            $paper = Asset_certificate::where('type', 'STNK')
                ->where('company_id', Session::get('company_id'))
                ->get();
        }
        foreach ($paper as $item){
            $row = array();
            $row['id'] = $item->id;
            $row['text'] = $item->name;
            $data[] = $row;
        }
        $row['id'] = 'new';
        $row['text'] = 'Add new paper';
        $data[] = $row;

        $result = array(
            "results" => $data
        );

        return json_encode($result);
    }

    function vehicles_js(Request $request){
        $paper = Asset_certificate::all();
        $cert = array();
        foreach ($paper as $item){
            $cert[$item->id] = $item;
        }

        if ($request->_c == "all"){
            $whereCat = "1";
        } elseif ($request->_c == "other"){
            $whereCat = "category = 0";
        } else {
            $whereCat = "category = ".$request->_c;
        }

        $pro_vendor = Procurement_vendor::all();
        $vendor = array();
        foreach ($pro_vendor as $item){
            $vendor[$item->id] = $item;
        }

        $vehicles = Asset_vehicles::where('company_id', Session::get('company_id'))
            ->whereRaw($whereCat)
            ->get();
        $data = array();
        foreach ($vehicles as $i => $item){
            $row = array();
            $row['i'] = $i+1;
            $row['name'] = "<a href='".route('ha.ve.view.vehicle', $item->id)."' class='btn btn-link'>".strip_tags($item->name)."</a>";
            $row['paper'] = (isset($cert[$item->certificate_id])) ? $cert[$item->certificate_id]->name : "";
            $row['paper_no'] = (isset($cert[$item->certificate_id])) ? $cert[$item->certificate_id]->certificate_no : "";
            $row['paper_holder'] = (isset($cert[$item->certificate_id])) ? $cert[$item->certificate_id]->certificate_holder : "";
            $row['bpkb_no'] = $item->bpkb_no;
            $row['used_by'] = $item->used_by;
            $row['location'] = $item->location;
            $row['description'] = (isset($cert[$item->certificate_id])) ? $cert[$item->certificate_id]->others : "";
            $row['vendor'] = (isset($vendor[$item->vendor_id])) ? $vendor[$item->vendor_id]->name : "";
            $row['phone'] = (isset($vendor[$item->vendor_id])) ? $vendor[$item->vendor_id]->telephone : "";
            $row['exp_date'] = (isset($cert[$item->certificate_id])) ? date('d F Y', strtotime($cert[$item->certificate_id]->exp_date)) : "";
            $row['action'] = "<button type='button' data-toggle='modal' data-target='#editVehicles' onclick='edit_vehicles(".$item->id.")' class='btn btn-xs btn-icon btn-primary'><i class='fa fa-edit'></i></button>
            <button type='button' onclick='delete_vehicles(".$item->id.")' class='btn btn-xs btn-icon btn-danger'><i class='fa fa-trash'></i></button>";
            $data[] = $row;
        }

        $result = array(
            'data' => $data
        );

        return json_encode($result);
    }

    function delete_category($id){
        $cat = Asset_vehicles_category::find($id);
        if ($cat->delete()){
            Asset_vehicles::where('category', $id)
                ->update([
                    "category" => 0
                ]);
            return redirect()->back()->with(['msg' => 'Category has been deleted', 'tab' => 'vehicle']);
        }
    }

    function delete_vehicle($id){
        $ve = Asset_vehicles::find($id);
        if ($ve->delete()){
            return redirect()->back()->with(['msg' => 'Vehicle has been deleted', 'tab' => 'vehicle']);
        }
    }

    function delete_maintenance($id){
        $ve = Asset_vehicles_maintenance::find($id);
        if ($ve->delete()){
            return redirect()->back()->with('msg', 'Item has been deleted');
        }
    }

    function delete_paper($id){
        $cert = Asset_certificate::find($id);
        if ($cert->delete()){
            Asset_vehicles::where('certificate_id', $id)
                ->update([
                    "certificate_id" => 0
                ]);
            return redirect()->back()->with(['msg' => 'Paper has been deleted', 'tab' => 'paper']);
        }
    }
}
