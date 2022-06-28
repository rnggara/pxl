<?php

namespace App\Http\Controllers;
use App\Models\Master_variables_model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Contracts\DataTable;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class EmployeeVariables extends Controller
{
    public function employee_variables(Request $request)
    {
        $employeevar = Master_variables_model::orderBy('id', 'desc')
            ->where('company_id', Session::get('company_id'))
            ->get();
        $data = Master_variables_model::get();
        if ($request->ajax ()) {
            $data = Master_variables_model::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn = '';

                    return $btn;

                })
                ->rawColumns(['action'])
                ->make (true);
        }
        return view('preference.employee.index', compact('employeevar'));
    }

    public function add(Request $request)
    {
        $variables = new Master_variables_model();
        $variables->parameter_name = $request->parameter_name;
        $variables->parameter_type = strtolower($request->parameter_type);
        $variables->parameter_length = $request->parameter_length;
        $variables->created_by = Auth::user()->username;
        $variables->company_id = Session::get('company_id');
        $variables->save();
        return redirect()->back();
    }

    public function edit(Request $request)
    {
        // $employeevars = Master_variables_model::find($id);
        // $employeevars->parameter_name = $request->parameter_name;
        // $employeevars->parameter_type = $request->parameter_type;
        // $employeevars->parameter_length = $request->parameter_length;
        // $employeevars->updated_by = Auth::user()->username;
        // $employeevars->user();

        // return redirect()->back();

        Master_variables_model::where('id', $request['id'])
            ->update([
                'parameter_name' => $request->parameter_name,
                'parameter_type' => $request->parameter_type,
                'parameter_length' => $request->parameter_length,
                'updated_by' => Auth::user()->username,
                'company_id' => Session::get('company_id')
            ]);
            return redirect()->back();
    }

    public function delete($id)
    {
        $employeevars = Master_variables_model::find($id);
        if ($employeevars->delete()){
            $data['delete'] = 1;
        } else {
            $data['delete'] = 0;
        }
        return json_encode($data);
    }
}
