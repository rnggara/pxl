<?php

namespace App\Http\Controllers;

use App\Models\Finance_invoice_out;
use Illuminate\Http\Request;
use App\Models\Marketing_clients;
use Session;

class MarketingClients extends Controller
{
    public function index(){
        $clients = Marketing_clients::where('company_id', Session::get('company_id'))->get();

        return view('clients.index',[
            'clients' => $clients,
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
        $client = new Marketing_clients();
        $client->company_name = $request['name'];
        $client->address = $request['address'];
        $client->phone_1 = $request['phone1'];
        $client->phone_2 = (isset($request['phone2'])) ? $request['phone2'] : '';
        $client->fax = $request['fax'];
        $client->pic = $request['pic_name'];
        $client->pic_number = $request['pic_phone'];
        $client->company_id = Session::get('company_id');
        $client->save();

        return redirect()->route('marketing.client.index');
    }

    public function get_clients(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $clients = Marketing_clients::whereIn('company_id', $id_companies)->get();
        $data = [];
        $val = [];
        foreach ($clients as $item){
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
        $client = new Marketing_clients();
        $client->company_name = $request['company_name'];
        $client->address = $request['address'];
        $client->phone_1 = $request['phone_1'];
        $client->pic = $request['pic'];
        $client->pic_number = $request['pic_number'];
        $client->company_id = Session::get('company_id');
        if ($client->save()){
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }

        return json_encode($data);
    }

    public function update(Request $request){
        $client = Marketing_clients::find($request->id);
        $client['company_name'] = $request['name'];
        $client['address'] = $request['address'];
        $client['phone_1'] = $request['phone1'];
        $client['phone_2'] = (isset($request['phone2'])) ? $request['phone2'] : '';
        $client['fax'] = $request['fax'];
        $client['pic'] = $request['pic_name'];
        $client['pic_number'] = $request['pic_phone'];
        // dd($client);

        $client->save();

        // Marketing_clients::where('id',$request['id'])
        //     ->update([
        //         'company_name' => $request['name'],
        //         'address' => $request['address'],
        //         'phone_1' => $request['phone1'],
        //         'phone_2' => (isset($request['phone2'])) ? $request['phone2'] : '',
        //         'fax' => $request['fax'],
        //         'pic' => $request['pic_name'],
        //         'pic_number' => $request['pic_phone'],
        //     ]);
        return redirect()->back();
    }

    public function delete($id){
        Marketing_clients::where('id',$id)->delete();
        return redirect()->route('marketing.client.index');
    }
}
