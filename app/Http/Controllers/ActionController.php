<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Action;

class ActionController extends Controller
{
	// public function index()
	// {
	// 	$number = 1;
	// 	$actions = Action::all();

	// 	return view ('editor.action.index', compact('actions','number'));
	// }

	public function store(Request $request)
	{
		$action = new Action;
		$action->name = strtolower($request->name);
		$action->desc = $request->desc;
		$action->save();

		return redirect()->route('company.detail', $request->coid);
	}

	public function update($id, Request $request)
	{
		$action = Action::find($id);
		$action->name = strtolower($request->name);
		$action->desc = $request->desc;
		$action->save();

		return redirect()->route('company.detail', $request->coid);
	}

	public function delete($id, Request $request)
	{
		Action::find($id)->delete();
		
		return redirect()->route('company.detail', $request->coid);
	}
}
