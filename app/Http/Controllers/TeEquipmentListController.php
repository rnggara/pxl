<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Te_pd;
use App\Models\Te_swt;
use App\Models\Te_subwt;
use App\Models\Te_slickline;
use Illuminate\Http\Request;
use App\Helpers\FileManagement;
use App\Models\File_Management;
use App\Models\Te_equipment_list;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Te_equipment_list_category;
use App\Models\Te_equipment_list_maintenance;

class TeEquipmentListController extends Controller
{
    public function index(){
        $elCat = Te_equipment_list_category::where('company_id', Session::get('company_id'))->get();
        $el = Te_equipment_list::where('company_id', Session::get('company_id'))
            ->orderBy('serial_number', 'desc')
            ->get();
        $serial_number = array();
        foreach ($el as $item){
            $serial_number[$item->category][] = $item->serial_number;
        }

        return view('te.el.index', [
            'elCats' => $elCat,
            'serial_number' => $serial_number
        ]);
    }

    public function addCategory(Request $request){
        $elCat = new Te_equipment_list_category();
        $elCat->category_name = $request->cat_name;
        $elCat->tag = $request->tag;
        $elCat->created_by = Auth::user()->username;
        $elCat->company_id = Session::get('company_id');

        if ($elCat->save()){
            return redirect()->route('te.el.index');
        }
    }

    public function deleteCategory($id){
        $elCat = Te_equipment_list_category::find($id);

        if ($elCat->delete()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    public function updateCategory(Request $request){
        $elCat = Te_equipment_list_category::find($request->id_cat);
        $elCat->category_name = $request->cat_name;
        $elCat->tag = $request->tag;
        $elCat->updated_by = Auth::user()->username;

        if ($elCat->save()){
            return redirect()->route('te.el.index');
        }
    }

    public function detail($id){
        $elCat = Te_equipment_list_category::find($id);
        $el = Te_equipment_list::where('company_id', Session::get('company_id'))
            ->where('category', $id)
            ->get();

        $file = File_Management::all();
        $file_name = array();
        foreach ($file as $item){
            $file_name[$item->hash_code] = str_replace("/", "\\", $item->file_name);
        }

        $data_mt = Te_equipment_list_maintenance::where("company_id", Session::get('company_id'))
            ->whereNotNull('mt_next_date')
            ->orderBy('mt_next_date')
            ->get();
        $mt = [];
        foreach($data_mt as $item){
            $mt[$item->id_el][] = $item->mt_next_date;
        }

        return view('te.el.detail', [
            'elCat' => $elCat,
            'els' => $el,
            'json_els' => json_encode($el),
            'file_' => json_encode($file_name),
            'mt' => $mt
        ]);
    }

    public function add(Request $request){

//        dd($request);

        $elCount = Te_equipment_list::where('category', $request->id_category)
            ->orderBy('created_at', 'desc')
            ->first();

        $elCat = Te_equipment_list_category::find($request->id_category);

        if (empty($elCount)){
            $serial = Session::get('company_tag')."/".strtoupper($elCat->tag)."/001";
        } else {
            $ser_num_last = explode("/", $elCount->serial_number);
            $num = intval(end($ser_num_last)) + 1;
            $serial = Session::get('company_tag')."/".strtoupper($elCat->tag)."/".sprintf("%03d", $num);
        }



        $el = new Te_equipment_list();
        $el->serial_number = $serial;
        $el->subject = $request->label;
        $el->type = $request->type;
        $el->param1 = $request->param1;
        $el->coi_expiry = $request->coi_expiry;
        $el->description = $request->desc;
        $el->category = $request->id_category;
        $el->status = $request->status;
        $el->company_id = Session::get('company_id');
        $el->created_by = Auth::user()->username;

        if (isset($request->param2)){
            $el->param2 = $request->param2;
        }

        if ($elCat->tag == "SEP"){
            $json = array();
            $json['capacity_oil'] = $request->capacity_oil;
            $json['capacity_water'] = $request->capacity_water;
            $json['capacity_gas'] = $request->capacity_gas;
            $json['retention_time'] = $request->retention_time;
            $add_info = json_encode($json);

            $el->additional_information = $add_info;
        }

        if (!empty($request->file('coi_file'))){
            $hash = $this->upload_file($request->file('coi_file'));
            $el->coi = $hash;
        }

        if (!empty($request->file('thumbnail'))){
            $hash = $this->upload_file($request->file('thumbnail'));
            $el->thumbnail = $hash;
        }

        if (!empty($request->file('drawing'))){
            $hash = $this->upload_file($request->file('drawing'));
            $el->drawing = $hash;
        }

        if (!empty($request->file('datasheet'))){
            $hash = $this->upload_file($request->file('datasheet'));
            $el->data_sheet = $hash;
        }

        if ($el->save()){
            return redirect()->route('te.el.detail', $request->id_category);
        }

    }

    public function update(Request $request){

        $el = Te_equipment_list::find($request->id_el);

        $elCat = Te_equipment_list_category::find($el->category);

        $el->subject = $request->label;
        $el->type = $request->type;
        $el->param1 = $request->param1;
        $el->coi_expiry = $request->coi_expiry;
        $el->description = $request->desc;
        $el->status = $request->status;
        $el->updated_by = Auth::user()->username;

        if (isset($request->param2)){
            $el->param2 = $request->param2;
        }

        if ($elCat->tag == "SEP"){
            $json = array();
            $json['capacity_oil'] = $request->capacity_oil;
            $json['capacity_water'] = $request->capacity_water;
            $json['capacity_gas'] = $request->capacity_gas;
            $json['retention_time'] = $request->retention_time;
            $add_info = json_encode($json);

            $el->additional_information = $add_info;
        }

        if (!empty($request->file('coi_file'))){
            $hash = $this->upload_file($request->file('coi_file'));
            $el->coi = $hash;
        }

        if (!empty($request->file('thumbnail'))){
            $hash = $this->upload_file($request->file('thumbnail'));
            $el->thumbnail = $hash;
        }

        if (!empty($request->file('drawing'))){
            $hash = $this->upload_file($request->file('drawing'));
            $el->drawing = $hash;
        }

        if (!empty($request->file('datasheet'))){
            $hash = $this->upload_file($request->file('datasheet'));
            $el->data_sheet = $hash;
        }

        if ($el->save()){
            return redirect()->route('te.el.detail', $el->category);
        }
    }

    public function delete($id){
        $elCat = Te_equipment_list::find($id);

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
        $el = Te_equipment_list::find($id);
        $el[$type] = null;

        if ($el->save()){
            File_Management::where('hash_code', $el[$type])->delete();
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    function items($id, $type){
        if ($type == "pd"){
            $otherPd = Te_pd::where('id', '!=', $id)->get();
            $id_te = array();
            foreach ($otherPd as $value){
                if (!empty($value->list_pfd)){
                    foreach (json_decode($value->list_pfd) as $item){
                        $id_te[] = $item;
                    }
                }
            }

//            $swt = Te_swt::all();
//            foreach ($swt as $value){
//                if (!empty($value->additional_information)){
//                    foreach (json_decode($value->additional_information) as $item){
//                        $id_te[] = $item;
//                    }
//                }
//            }
//
//            $subwt = Te_subwt::all();
//            foreach ($subwt as $value){
//                if (!empty($value->additional_information)){
//                    foreach (json_decode($value->additional_information) as $item){
//                        $id_te[] = $item;
//                    }
//                }
//            }
//
//            $slickline = Te_slickline::all();
//            foreach ($slickline as $value){
//                if (!empty($value->additional_information)){
//                    foreach (json_decode($value->additional_information) as $item){
//                        $id_te[] = $item;
//                    }
//                }
//            }

            $pd = Te_pd::find($id);
            $pfd = json_decode($pd->list_pfd);
        } elseif ($type == "swt"){
//            $otherPd = Te_pd::all();
//            $id_te = array();
//            foreach ($otherPd as $value){
//                if (!empty($value->list_pfd)){
//                    foreach (json_decode($value->list_pfd) as $item){
//                        $id_te[] = $item;
//                    }
//                }
//            }

            $swt = Te_swt::where('id', '!=', $id)->get();
            foreach ($swt as $value){
                if (!empty($value->additional_information)){
                    foreach (json_decode($value->additional_information) as $item){
                        $id_te[] = $item;
                    }
                }
            }

//            $subwt = Te_subwt::all();
//            foreach ($subwt as $value){
//                if (!empty($value->additional_information)){
//                    foreach (json_decode($value->additional_information) as $item){
//                        $id_te[] = $item;
//                    }
//                }
//            }
//
//            $slickline = Te_slickline::all();
//            foreach ($slickline as $value){
//                if (!empty($value->additional_information)){
//                    foreach (json_decode($value->additional_information) as $item){
//                        $id_te[] = $item;
//                    }
//                }
//            }

            $pd = Te_swt::find($id);
            $pfd = json_decode($pd->additional_information);
        } elseif ($type == "subwt"){
//            $otherPd = Te_pd::all();
//            $id_te = array();
//            foreach ($otherPd as $value){
//                if (!empty($value->list_pfd)){
//                    foreach (json_decode($value->list_pfd) as $item){
//                        $id_te[] = $item;
//                    }
//                }
//            }
//
//            $swt = Te_swt::all();
//            foreach ($swt as $value){
//                if (!empty($value->additional_information)){
//                    foreach (json_decode($value->additional_information) as $item){
//                        $id_te[] = $item;
//                    }
//                }
//            }

            $subwt = Te_subwt::where('id', '!=', $id)->get();
            foreach ($subwt as $value){
                if (!empty($value->additional_information)){
                    foreach (json_decode($value->additional_information) as $item){
                        $id_te[] = $item;
                    }
                }
            }

//            $slickline = Te_slickline::all();
//            foreach ($slickline as $value){
//                if (!empty($value->additional_information)){
//                    foreach (json_decode($value->additional_information) as $item){
//                        $id_te[] = $item;
//                    }
//                }
//            }

            $pd = Te_subwt::find($id);
            $pfd = json_decode($pd->additional_information);
        } elseif ($type == "slickline"){
//            $otherPd = Te_pd::all();
//            $id_te = array();
//            foreach ($otherPd as $value){
//                if (!empty($value->list_pfd)){
//                    foreach (json_decode($value->list_pfd) as $item){
//                        $id_te[] = $item;
//                    }
//                }
//            }
//
//            $swt = Te_swt::all();
//            foreach ($swt as $value){
//                if (!empty($value->additional_information)){
//                    foreach (json_decode($value->additional_information) as $item){
//                        $id_te[] = $item;
//                    }
//                }
//            }
//
//            $subwt = Te_subwt::all();
//            foreach ($subwt as $value){
//                if (!empty($value->additional_information)){
//                    foreach (json_decode($value->additional_information) as $item){
//                        $id_te[] = $item;
//                    }
//                }
//            }

            $slickline = Te_slickline::where('id', '!=', $id)->get();
            foreach ($slickline as $value){
                if (!empty($value->additional_information)){
                    foreach (json_decode($value->additional_information) as $item){
                        $id_te[] = $item;
                    }
                }
            }

            $pd = Te_slickline::find($id);
            $pfd = json_decode($pd->additional_information);
        }



        $id_te = array_unique($id_te);
        $el = Te_equipment_list::where('company_id', Session::get('company_id'))
//            ->whereNotIn('id', $id_te)
            ->get();


        $el_cat = Te_equipment_list_category::all();
        $cat = array();
        foreach ($el_cat as $item){
            $cat[$item->id] = $item->category_name;
        }

        $items = array();
        foreach ($el as $key => $item){
            if (isset($pfd) && !empty($pfd)){
                if (in_array($item->id, $pfd)){
                    $selected = "checked";
                } else {
                    $selected = "";
                }
            } else {
                $selected = "";
            }
            $column['key'] = "<input type='checkbox' class='checks' name='checks[]' onclick='items_check(this)' value='".$item->id."' $selected>";
            $column['serial_number'] = $item->serial_number;
            $column['category'] = (isset($cat[$item->category])) ? $cat[$item->category] : "N/A";
            if ($item->type == 1){
                $column['type'] = "MAIN EQUIPMENT";
            } elseif ($item->type == 2){
                $column['type'] = "ACCESORIES";
            } else {
                $column['type'] = "SAFETY EQUIPMENT";
            }
            $column['label'] = $item->subject;
            $column['status'] = ($item->status == 1) ? "READY" : "NOT READY";
            $items[] = $column;
        }

        $val = [
            'data' => $items,
            'items' => $pfd,
            'type' => $type
        ];

        return json_encode($val);
    }

    function items_detail($id){
        $item = Te_equipment_list::find($id);
        $cat = array();
        $el_cat = Te_equipment_list_category::all();
        foreach ($el_cat as $value){
            $cat[$value->id] = $value;
        }

        return view('te.el._view', [
            'item' => $item,
            'cat' => $cat
        ]);
    }

    function view_item($id){
        $item = Te_equipment_list::find($id);
        $elCat = Te_equipment_list_category::find($item->category);
        $file = File_Management::where('hash_code', $item->drawing)->first();
        $thumbnail = File_management::where('hash_code', $item->thumbnail)->first();

        $mt = Te_equipment_list_maintenance::where("id_el", $item->id)
            ->orderBy('id', 'desc')->get();
        $files = File_Management::all()->pluck('file_name', 'hash_code');
        return view('te.el.item_view', compact('item', 'elCat', 'file', 'files', 'mt','thumbnail'));
    }

    function add_maintenance(Request $request){
        $mt = new Te_equipment_list_maintenance();
        $mt->id_el = $request->_id_el;
        $mt->mt_date = $request->_mt_date;
        $mt->mt_description = $request->_description;
        $mt->mt_fol_up = $request->_follow_up;
        $mt->mt_next_date = $request->_next_mt_date;
        if($request->hasFile('_report_file')){
            $file = $request->file('_report_file');
            $filename = explode(".", $file->getClientOriginalName());
            array_pop($filename);
            $filename = str_replace(" ", "_", implode("_", $filename));

            $newFile = "[MT]".$filename."-".date('Y_m_d_H_i_s')."($request->_id_el).".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);
            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media\\te\\el");
            if($upload){
                $mt->mt_report_file = $hashFile;
            }
        }
        $mt->created_by = Auth::user()->username;
        $mt->company_id = Session::get('company_id');
        if($mt->save()){
            return redirect()->back()->with('msg', 'Success');
        } else {
            return redirect()->back()->with('error', 'Failed');
        }
    }

    function delete_maintenance($id){
        if(Te_equipment_list_maintenance::find($id)->delete()){
            return redirect()->back()->with('delete', 'Deleted');
        } else {
            return redirect()->back()->with('error', 'Failed');
        }
    }
}
