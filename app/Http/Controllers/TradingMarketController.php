<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\Trading_market;

class TradingMarketController extends Controller
{
    public function index(){
        $markets = Trading_market::where('company_id', Session::get('company_id'))->get();

        return view('trading.markets.index',[
            'markets' => $markets,
        ]);
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'address' => 'required',
            'phone1' => 'required',
            'pic_name' => 'required',
            'pic_phone' => 'required',
        ]);
        $market = new Trading_market();
        $market->company_name = $request['name'];
        $market->address = $request['address'];
        $market->phone_1 = $request['phone1'];
        $market->phone_2 = (isset($request['phone2'])) ? $request['phone2'] : '';
        $market->fax = $request['fax'];
        $market->pic = $request['pic_name'];
        $market->pic_number = $request['pic_phone'];
        $market->company_id = Session::get('company_id');
        $market->save();

        return redirect()->route('trading.market.index');
    }

    public function get_markets(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $markets = Trading_market::whereIn('company_id', $id_companies)->get();
        $data = [];
        $val = [];
        foreach ($markets as $item){
            $data['id'] = $item->id;
            $data['text'] = $item->company_name;
            $val[] = $data;
        }

        $response = [
            'results' => $val,
            'pagination' => ["more" => true]
        ];

        return json_encode($response);
    }

    public function add_js(Request $request){
//        dd($request);
        $market = new Trading_market();
        $market->company_name = $request['company_name'];
        $market->address = $request['address'];
        $market->phone_1 = $request['phone_1'];
        $market->pic = $request['pic'];
        $market->pic_number = $request['pic_number'];
        $market->company_id = Session::get('company_id');
        if ($market->save()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    public function update(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'address' => 'required',
            'phone1' => 'required',
            'fax' => 'required',
            'pic_name' => 'required',
            'pic_phone' => 'required',
        ]);

        Trading_market::where('id',$request['id'])
            ->update([
                'company_name' => $request['name'],
                'address' => $request['address'],
                'phone_1' => $request['phone1'],
                'phone_2' => (isset($request['phone2'])) ? $request['phone2'] : '',
                'fax' => $request['fax'],
                'pic' => $request['pic_name'],
                'pic_number' => $request['pic_phone'],
            ]);
        return redirect()->route('trading.market.index');
    }

    public function delete($id){
        Trading_market::where('id',$id)->delete();
        return redirect()->route('trading.market.index');
    }
}
