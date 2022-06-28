<?php

namespace App\Http\Controllers;

use App\Helpers\FileManagement;
use App\Models\File_Management;
use App\Models\Te_equipment_list;
use App\Models\Te_equipment_list_category;
use App\Models\Te_pd;
use App\Models\Te_pd_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;

class TeProjectDesignController extends Controller
{
    public function index(){
        $elCat = Te_pd_category::where('company_id', Session::get('company_id'))->get();
        return view('te.pd.index', [
            'elCats' => $elCat
        ]);
    }

    public function addCategory(Request $request){
        $elCat = new Te_pd_category();
        $elCat->category_name = $request->cat_name;
        $elCat->created_by = Auth::user()->username;
        $elCat->company_id = Session::get('company_id');

        if ($elCat->save()){
            return redirect()->route('te.pd.index');
        }
    }

    public function deleteCategory($id){
        $elCat = Te_pd_category::find($id);

        if ($elCat->delete()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    public function updateCategory(Request $request){
        $elCat = Te_pd_category::find($request->id_cat);
        $elCat->category_name = $request->cat_name;
        $elCat->tag = $request->tag;
        $elCat->updated_by = Auth::user()->username;

        if ($elCat->save()){
            return redirect()->route('te.pd.index');
        }
    }

    public function detail($id){
        $elCat = Te_pd_category::find($id);
        $el = Te_pd::where('company_id', Session::get('company_id'))
            ->where('category', $id)
            ->get();

        $file = File_Management::all();
        $file_name = array();
        foreach ($file as $item){
            $file_name[$item->hash_code] = str_replace("/", "\\", $item->file_name);
        }

        return view('te.pd.detail', [
            'elCat' => $elCat,
            'els' => $el,
            'json_els' => json_encode($el),
            'file_' => json_encode($file_name)
        ]);
    }

    public function add(Request $request){

//        dd($request);

        $el = new Te_pd();
        $el->company_name = $request->company_name;
        $el->project_name = $request->project_name;
        $el->type = $request->type;
        $el->capacity = $request->capacity;
        $el->diameter_separator = $request->diameter_separator;
        $el->description = $request->desc;
        $el->category = $request->id_category;
        $el->capacity_oil = $request->capacity_oil;
        $el->capacity_water = $request->capacity_water;
        $el->capacity_gas = $request->capacity_gas;
        $el->retention_time = $request->retention_time;
        $el->company_id = Session::get('company_id');
        $el->created_by = Auth::user()->username;

        if (!empty($request->file('thumbnail'))){
            $hash = $this->upload_file($request->file('thumbnail'));
            $el->thumbnail = $hash;
        }

        if (!empty($request->file('drawing'))){
            $hash = $this->upload_file($request->file('drawing'));
            $el->drawing = $hash;
        }

        if ($el->save()){
            return redirect()->route('te.pd.detail', $request->id_category);
        }

    }

    public function update(Request $request){

        $el = Te_pd::find($request->id_el);

        $el->company_name = $request->company_name;
        $el->project_name = $request->project_name;
        $el->type = $request->type;
        $el->capacity = $request->capacity;
        $el->diameter_separator = $request->diameter_separator;
        $el->description = $request->desc;
        $el->capacity_oil = $request->capacity_oil;
        $el->capacity_water = $request->capacity_water;
        $el->capacity_gas = $request->capacity_gas;
        $el->retention_time = $request->retention_time;
        $el->updated_by = Auth::user()->username;

        if (!empty($request->file('thumbnail'))){
            $hash = $this->upload_file($request->file('thumbnail'));
            $el->thumbnail = $hash;
        }

        if (!empty($request->file('drawing'))){
            $hash = $this->upload_file($request->file('drawing'));
            $el->drawing = $hash;
        }

        if ($el->save()){
            return redirect()->route('te.pd.detail', $el->category);
        }
    }

    public function delete($id){
        $elCat = Te_pd::find($id);

        if ($elCat->delete()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function upload_file($file_up){
        $file = $file_up;
        $filename = explode(".", $file->getClientOriginalName());
        array_pop($filename);
        $filename = str_replace(" ", "_", implode("_", $filename));

        $newFile = $filename."-".date('Y_m_d_H_i_s').".".$file->getClientOriginalExtension();
        $hashFile = Hash::make($newFile);
        $hashFile = str_replace("/", "", $hashFile);
        $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\\te\\el");

        return $hashFile;
    }

    function deleteFile($id, $type){
        $el = Te_pd::find($id);
        $el[$type] = null;

        if ($el->save()){
            File_Management::where('hash_code', $el[$type])->delete();
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function findItems($id){
        $pd = Te_pd::find($id);

        $items = Te_equipment_list::where('company_id', Session::get('company_id'))->get();
        $data_item = array();
        foreach ($items as $item){
            $data_item[$item->id] = $item;
        }

        $el_cat = Te_equipment_list_category::all();
        $cat = array();
        foreach ($el_cat as $item){
            $cat[$item->id] = $item->category_name;
        }

        $list = json_decode($pd->list_pfd);
        $data = array();
        if ($list != null){
            foreach ($list as $item){
                if (isset($data_item[$item])){
                    $data[] = $data_item[$item];
                }
            }
        } else {
            $data = null;
        }

        return view('te.pd._list_items', [
            'pd' => $pd,
            'items' => $data,
            'category' => $cat
        ]);
    }

    function updateItems(Request $request){
        $pd = Te_pd::find($request->id_pd);
        $pd->list_pfd = $request->items;
        if ($pd->save()){
            return redirect()->back();
        }
    }
}
