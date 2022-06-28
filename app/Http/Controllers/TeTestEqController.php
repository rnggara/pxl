<?php

namespace App\Http\Controllers;

use App\Helpers\FileManagement;
use App\Models\Te_testeq;
use App\Models\Te_testeq_update;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use Session;

class TeTestEqController extends Controller
{
    public function getInstrumentList(){
        $uom = array("kg", "unit", "buah", "meter", "pack", "roll", "ea", "buku", "inch", "lusin", "set", "rim", "gallon", "feet", "litre", "can", "lbs", "joint", "box", "bottle", "gram", "lembar", "drum", "lot");
        $instruments = Te_testeq::where('company_id', \Session::get('company_id'))->orderBy('name')->get();
        $row = [];
        $te_instrument = [];
        foreach ($instruments as $key => $value){
            $te_instrument['no'] = ($key+1);
            $uom_array = "";
            foreach($uom as $v){
                if ($v == $value->uom){
                    $uom_array .= '<option value= "'.$v.'" SELECTED>'.$v.'</option>';
                } else {
                    $uom_array .= '<option value= "'.$v.'">'.$v.'</option>';
                }
            }
            if ($value->data_sheet != null){
                $datasheet = '<a href="'.route('download', $value->data_sheet).'" class="btn btn-xs btn-light-primary"><i class="fa fa-download"></i> download</a>';
            } else {
                $datasheet = '';
            }
//            dd($uom_array);
            $te_instrument['item_name'] = '<button type="button" class="btn btn-link btn-xs" data-toggle="modal" data-target="#editItem'.$value->id.'"><i class="fa fa-search"></i>'.stripslashes($value->name).'</button>
                        <div class="modal fade" id="editItem'.$value->id.'" tabindex="-1" role="dialog" aria-labelledby="editItem'.$value->id.'" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Edit Test Equipment Item Detail</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <i aria-hidden="true" class="ki ki-close"></i>
                                        </button>
                                    </div>
                                    <form method="post" action="'.route('te.testeq.update').'" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="'.csrf_token().'">
                                        <input type="hidden" name="id" value="'.$value->id.'">
                                        <input type="hidden" name="item_code" value="'.$value->item_code.'">
                                        <div class="modal-body">
                                            <h4>Basic Information</h4>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-md-2 col-form-label text-right">Item Detail Code</label>
                                                <div class="col-md-6">
                                                    <label class="control-label mt-4"><b>'.$value->item_code.'</b></label>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-2 col-form-label text-right">Item Name</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="Item name" name="item_name" value="'.$value->name.'" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-2 col-form-label text-right">Item Series</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="Item Series" value="'.$value->item_series.'" name="item_series" >
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-2 col-form-label text-right">Serial Number</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="Serial Number" value="'.$value->serial_number.'" name="serial_number">
                                                </div>
                                            </div>

                                            <br>
                                            <h4>Detail Info</h4>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-md-2 col-form-label text-right">UoM</label>
                                                <div class="col-md-6">
                                                    <select name="uom" class="form-control" required>
                                                        <option value="">- Select UOM -</option>
                                                        '.$uom_array.'
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-2 col-form-label text-right">Picture</label>
                                                <div class="col-md-6">
                                                    <input type="file" name="picture" class="custom-file" >
                                                </div>

                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-2 col-form-label text-right">Data Sheet</label>
                                                <div class="col-md-2">
                                                    <input type="file" name="data_sheet" class="custom-file" >
                                                </div>
                                                <br>
                                                <div class="col-md-4 mr-10">
                                                    '.$datasheet.'
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-2 col-form-label text-right">Notes</label>
                                                <div class="col-md-6">
                                                    <textarea name="notes" class="form-control" cols="30" rows="10">'.$value->notes.'</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-2 col-form-label text-right">Specification</label>
                                                <div class="col-md-6">
                                                    <textarea name="specification" class="form-control" cols="30" rows="10">'.$value->specification.'</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                            <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                                <i class="fa fa-check"></i>
                                                Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>';
            if ($value->picture == null){
                $picture = 'No Picture';
            } else {
                $picture = '<img src="'.str_replace('public','public_html',asset('/media/te_testeq/')).'/'.$value->picture.'" class="img-responsive center-block" height="15%">';
            }
            $te_instrument['picture'] = $picture;
            if ($value->category_id == 1){
                $category = 'Main Equipment';
            } else {
                $category = 'Sparepart';
            }
            $te_instrument['category'] = $category;
            $te_instrument['code'] = stripslashes($value->item_code);
            $te_instrument['uom'] = $value->uom;
            $te_instrument['action'] = "<a href='".route('te.testeq.delete',['id'=>$value->id])."' class='btn btn-danger btn-xs' title='Delete' onclick='return confirm(\"Are you sure you want to delete?\")'><i class='fa fa-trash'></i></a>";
            $row[] = $te_instrument;
        }
        $data = [
            'data' => $row,
        ];

        return json_encode($data);
    }
    public function index(){
        $revisions = Te_testeq_update::where('company_id', \Session::get('company_id'))
            ->whereNull('approved_by')
            ->get();
        $count_revision = count($revisions);
        return view('te.testeq.index',[
            'revisions' => $count_revision
        ]);
    }

    public function store(Request $request){
//        dd($request);
        $instruments = new Te_testeq();
        $instruments->item_code = $request->item_code;
        $instruments->name = $request->item_name;
        $instruments->item_series = $request->item_series;
        $instruments->uom = $request->uom;
        $instruments->serial_number = $request->serial_number;
        $instruments->specification = $request->specification;
        $instruments->notes = $request->notes;
        $instruments->category_id = $request->category_id;
        if ($request->hasFile('picture')){
            $file = $request->file('picture');
            $newFile = stripslashes($request->item_code)."-picture.".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/te_testeq");
            if ($upload == 1){
                $instruments->picture = $newFile;
            }
        }
        if ($request->hasFile('data_sheet')){
            $file = $request->file('data_sheet');
            $newFile = stripslashes($request->item_code)."-data_sheet.".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/te_testeq");
            if ($upload == 1){
                $instruments->data_sheet = $hashFile;
            }
        }
        $instruments->created_at = date('Y-m-d H:i:s');
        $instruments->created_time = date('Y-m-d H:i:s');
        $instruments->created_by = Auth::user()->username;
        $instruments->company_id = \Session::get('company_id');
        $instruments->save();

        return redirect()->back();
    }

    public function delete($id){
        Te_testeq::find($id)->delete();
        return redirect()->back();
    }

    public function update(Request $request){
        $instrument_update = new Te_testeq_update();
        $instrument_update->item_id = $request->id;
        $instrument_update->item_code = $request->item_code;
        $instrument_update->name = $request->item_name;
        $instrument_update->item_series = $request->item_series;
        $instrument_update->uom = $request->uom;
        $instrument_update->serial_number = $request->serial_number;
        $instrument_update->specification = $request->specification;
        $instrument_update->notes = ($request->notes)?$request->notes:'';
        $instrument_update->created_at = date('Y-m-d H:i:s');
        $instrument_update->created_time = date('Y-m-d H:i:s');
        $instrument_update->created_by = Auth::user()->username;
        $instrument_update->company_id = \Session::get('company_id');

        if ($request->hasFile('picture')){
            $file = $request->file('picture');
            $newFile = stripslashes($request->item_code)."-picture.".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/te_testeq_update");
            if ($upload == 1){
                $instrument_update->picture = $newFile;
            }
        }
        if ($request->hasFile('data_sheet')){
            $file = $request->file('data_sheet');
            $newFile = stripslashes($request->item_code)."-data_sheet.".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/te_testeq_update");
            if ($upload == 1){
                $instrument_update->data_sheet = $hashFile;
            }
        }
        $instrument_update->save();
        return redirect()->back();
    }

    public function revision(){
        $revisions_list = Te_testeq_update::where('company_id',\Session::get('company_id'))
            ->whereNull('approved_by')
            ->get();
//        dd($revisions_list);

        return view('te.testeq.rev',[
            'revisions' => $revisions_list,
        ]);
    }

    public function revision_detail($id){
        $revision = Te_testeq_update::where('id',$id)
            ->first();
        $instrument = Te_testeq::where('id',$revision->item_id)->first();

        return view('te.testeq.rev_detail',[
            'old' => $instrument,
            'new' => $revision,
        ]);
    }

    public function revision_approve(Request $request){
        if (isset($request->approve)){
            $new = Te_testeq_update::where('id', $request->id_update)->first();
            $instrument = Te_testeq::find($new->item_id);
            $instrument->item_code = $new->item_code;
            $instrument->name = $new->name;
            $instrument->item_series = $new->item_series;
            $instrument->uom = $new->uom;
            $instrument->notes = $new->notes;
            $instrument->serial_number = $new->serial_number;
            $instrument->specification = $new->specification;
            $instrument->picture = $new->picture;
            if (file_exists(base_path().'\\public_html\\media\\te_testeq_update\\'.$new->picture)){
                rename(base_path().'\\public_html\\media\\te_testeq_update\\'.$new->picture,base_path().'\\public_html\\media\\te_testeq\\'.$new->picture);
            }
//            dd(base_path().'\\public_html\\media\\te_testeq_update\\'.$new->picture);
            if ($instrument->save()){
                Te_testeq_update::where('id', $request->id_update)
                    ->update([
                        'approved_by'=>Auth::user()->username,
                        'approved_time'=>date('Y-m-d'),
                    ]);
            }
        } else {
            Te_testeq_update::find($request->id_update)->delete();
        }

        return redirect()->route('te.testeq.revision');
    }
}
