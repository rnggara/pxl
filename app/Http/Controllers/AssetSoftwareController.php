<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\SoftwareModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\session;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables;

class AssetSoftwareController extends Controller
{
    public function index(Request $request) {

        $software = SoftwareModel::orderBy('id', 'desc')
            ->where('company_id', Session::get('company_id'))
            ->get();
        $data = SoftwareModel::get();
        if ($request->ajax ()) {
            $data = SoftwareModel::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn = '';
                    return $btn;
                })
                ->rawColumn(['action'])
                ->make (true);
        }
        return view('software.index', compact('software'));
    }

    public function add(Request $request) {

        //
        $software = new SoftwareModel();
        $software['software_name'] = $request->software_name;
        $software['software_publisher'] = $request->software_publisher;
        $software['software_version'] = $request->software_version;
        $software['software_year'] = $request->software_year;
        $software['software_buy_date'] = $request->software_buy_date;
        $software['software_license_key'] = $request->software_license_key;
        $software['software_price'] = $request->software_price;
        $software['company_id'] = Session::get('company_id');
        $software->save();

        SoftwareModel::create([
            'software_name' => $request->software_name,
            'software_publisher' => $request->software_publisher,
            'software_version' => $request->software_version,
            'software_year' => $request->software_year,
            'software_buy_date' => $request->software_buy_date,
            'software_license_key' => $request->software_license_key,
            'software_price' => $request->software_price,
            'company_id' => Session::get('company_id')
        ]);
        return redirect()->back();
    }

    public function delete($id)
    {
        $softwares = SoftwareModel::find($id);
        if ($softwares->delete()){
            $data['delete'] = 1;
        } else {
            $data['delete'] = 0;
        }
        return json_encode($data);
    }

    public function edit(Request $request){

        $software = SoftwareModel::find($request->id);
        $software['software_name'] = $request->software_name;
        $software['software_publisher'] = $request->software_publisher;
        $software['software_version'] = $request->software_version;
        $software['software_year'] = $request->software_year;
        $software['software_buy_date'] = $request->software_buy_date;
        $software['software_license_key'] = $request->software_license_key;
        $software['software_price'] = $request->software_price;
        $software['company_id'] = Session::get('company_id');
        $software->save();

        SoftwareModel::where('id', $request['id'])
            ->update([
                'software_name' => $request['software_name'],
                'software_publisher' => $request['software_publisher'],
                'software_version' => $request['software_version'],
                'software_year' => $request['software_year'],
                'software_buy_date' => $request['software_buy_date'],
                'software_license_key' => $request['software_license_key'],
                'software_price' => $request['software_price']
            ]);

            return redirect()->back();
    }

    public function new_item(){
        $data = [];
        $now = strtotime(date("Y-m-d"));
        $end = strtotime("2022-12-31");
        $str = 'abcdefghijklmnopqrstuvwxyz';
        for ($i=0; $i < 3000; $i++) {
            $col = [];

            $mt_rand = mt_rand($now, $end);
            $col['software_name'] = $this->generateRandomString();
            $col['software_publisher'] = $this->generateRandomString();
            $col['software_version'] = rand(1,100);
            $col['software_year'] = date("Y");
            $col['software_buy_date'] = date("Y-m-d", $mt_rand);
            $col['software_license_key'] = $this->generateRandomString();
            $col['software_price'] = rand(100000,999999);
            $col['company_id'] = Session::get('company_id');
            $data[] = $col;
            # code...
        }

        SoftwareModel::insert($data);

        echo json_encode($data);
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
