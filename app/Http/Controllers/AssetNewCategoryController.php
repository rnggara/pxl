<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App\Models\Asset_item;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\Asset_new_category;
use App\Models\Finance_depreciation;
use App\Models\Asset_item_classification;

class AssetNewCategoryController extends Controller
{

    public function search(Request $request){
        $stringsearch = $request['search_val'];

        $category1 = Asset_new_category::where('company_id','like','%"'.Session::get('company_id').'"%')
            ->where('standard',1)
            ->get();

        //child category
        $comp_id = [];
        $company_child = ConfigCompany::where('id_parent',Session::get('company_id'))
            ->get();
        foreach ($company_child as $key => $value){
            array_push($comp_id,"".$value->id."");
        }
        $category2 = [];
        foreach ($comp_id as $key => $value){
            $category2 = Asset_new_category::where('company_id','like','%'.$value.'%')
                ->where('standard',0)
                ->get();
        }
        //non standard punya company sendiri
        $category3 = Asset_new_category::where('company_id','like','%'.Session::get('company_id').'%')
            ->where('standard',0)
            ->get();

        $all_category = Asset_new_category::all();
        $det_category = [];
        foreach ($all_category as $key => $value) {
            $det_category[$value->id] = $value->name;
        }

        $all_class = Asset_item_classification::all();
        foreach ($all_class as $key => $value) {
            $det_class[$value->id] = $value->classification_name;
        }

        $category_id = [];
        foreach ($category1 as $key => $value){
            array_push($category_id, $value->id);
        }
        foreach ($category2 as $key => $value){
            array_push($category_id, $value->id);
        }
        foreach ($category3 as $key => $value){
            array_push($category_id, $value->id);
        }

        $searchArr = [];
        $countSearch = 0;
        if ($stringsearch == trim($stringsearch) && strpos($stringsearch, ' ') !== false) {
            $searchArr = explode(' ',$stringsearch);
            $countSearch += count($searchArr);
        } else {
            $searchArr = json_decode('["'.$request['search_val'].'"]');
            $countSearch += 1;
        }

        $searchValues = preg_split('/\s+/', $stringsearch, -1, PREG_SPLIT_NO_EMPTY);
        $items = Asset_item::whereIn('category_id', $category_id)
            ->where(function($q) use ($searchValues){
                foreach ($searchValues as $value) {
                    $q->orWhere('name', 'like', '%'.$value.'%');
                }
            })->get();

        $arrItems = [];

        foreach ($items as $key => $item) {
            $score = 0;
            for ($j = 0; $j < $countSearch; $j++) {
                if (strpos(strtolower($item->name), strtolower($searchArr[$j])) !== false) {
                    $score++;
                }
            }
            $arrItems[$item->id]['score'] = $score;
            $arrItems[$item->id]['name'] = $item->name;
            $arrItems[$item->id]['code'] = $item->item_code;
            $arrItems[$item->id]['id'] = $item->id;
            $arrItems[$item->id]['cat'] = (isset($det_category[$item->category_id])) ? $det_category[$item->category_id] : "";
            $arrItems[$item->id]['class'] = (isset($det_class[$item->class_id])) ? $det_class[$item->class_id] : "";
            $arrItems[$item->id]['cat_id'] = $item->category_id;
            $arrItems[$item->id]['class_id'] = $item->class_id;

            // $items_array['score'][$item->id] = $score;
            // $items_array['name'][$item->id] = $item->name;
            // $items_array['code'][$item->id] = $item->item_code;
            // $items_array['id'][$item->id] = $item->id;
            // $items_array['id_count'][] = $item->id;
            // $items_array['cat'][$item->id] = (isset($det_category[$item->category_id])) ? $det_category[$item->category_id] : "";
            // $items_array['class'][$item->id] = (isset($det_class[$item->class_id])) ? $det_class[$item->class_id] : "";
            // $items_array['cat_id'][$item->id] = $item->category_id;
            // $items_array['class_id'][$item->id] = $item->class_id;
        }
        // dd($countSearch, $items);

        // $items_array = [];
        // $arrItems = [];
        // for ($i = 0; $i < $countSearch; $i++){
        //     $items = Asset_item::whereIn('category_id',$category_id)
        //         ->where('name','like','%'.$searchArr[$i].'%')
        //         ->get();

        // }

        if(!empty($arrItems)){
            usort($arrItems, function($a, $b){
                return $b['score'] - $a['score'];
            });
        }

        $dep = Finance_depreciation::all()->pluck('id', 'item_id');

        return view('category.searchresult',[
            'searchArr' =>$searchArr,
            'items_array' => $arrItems,
            'dep' => $dep
        ]);
    }

    public function getCategory(){
        $category = Asset_new_category::all();
        $data = [];
        foreach ($category as $value){
            $data[] = array(
                "id" => $value->id,
                "text" => $value->name.'/'.$value->code,
            );
        }
        return response()->json($data);
    }
    public function index(){

        //standard category
        $category1 = Asset_new_category::where('company_id','like','%"'.Session::get('company_id').'"%')
            ->where('standard',1)
            ->get();

        //child category
        $comp_id = [];
        $company_child = ConfigCompany::where('id_parent',Session::get('company_id'))
            ->get();
        foreach ($company_child as $key => $value){
            array_push($comp_id,"".$value->id."");
        }
        $category2 = [];
        foreach ($comp_id as $key => $value){
            $category2 = Asset_new_category::where('company_id','like','%'.$value.'%')
                ->where('standard',0)
                ->get();
        }
        //non standard punya company sendiri
        $category3 = Asset_new_category::where('company_id','like','%'.Session::get('company_id').'%')
            ->where('standard',0)
            ->get();

        $parent_name = [];
        $id_parent = [];
        foreach ($category1 as $key => $value){
            $parent_name[$value->id] = $value->name;
            $id_parent[$value->id] = $value->id_parent;
        }
        foreach ($category2 as $key => $value){
            $parent_name[$value->id] = $value->name;
            $id_parent[$value->id] = $value->id_parent;
        }
        foreach ($category3 as $key => $value){
            $parent_name[$value->id] = $value->name;
            $id_parent[$value->id] = $value->id_parent;
        }

        return view('category.index',[
            'categories' => $category1,
            'categories2' => $category2,
            'categories3' => $category3,
            'parents' => $parent_name,
            'id_parents' => $id_parent,
        ]);
    }

    public function loadData(Request $request)
    {
        $t = $_GET['term'];
        $data = Asset_new_category::select('id','name')
            ->where('id', 'like', "%".$t."%")
            ->where('name', 'like', "%".$t."%")
            ->whereNull('deleted_at')->get();
        foreach ($data as $value){
            $val[] = "[".$value->id."] ".$value->name;
        }
        return json_encode($val);
    }

    public function store(Request $request){
        $category = new Asset_new_category();
        $category->id_parent = $request['id_parent'];
        $category->name = $request['name'];
        $category->code = $request['code'];
        $category->standard = 0;
        $category->company_id = '["'.Session::get('company_id').'"]';
        $category->save();
        return redirect()->route('category.index');

    }

    public function update(Request $request){
        Asset_new_category::where('id', $request['id'])
            ->update([
                'name' => $request['name'],
                'code' => $request['code'],
                'id_parent' => $request['id_parent']
            ]);

        return redirect()->route('category.index');
    }
    public function delete($id){
        Asset_new_category::where('id',$id)->delete();
        Asset_new_category::where('id_parent', $id)->delete();
        return redirect()->route('category.index');
    }
}
