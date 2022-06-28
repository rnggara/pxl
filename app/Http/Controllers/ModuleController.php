<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Module;

class ModuleController extends Controller
{
	// public function index()
	// {
	// 	$number = 1;
	// 	$modules = Module::all();

	// 	return view ('editor.module.index', compact('modules','number'));
	// }

	public function store(Request $request)
	{
		$module = new Module;
		$module->name = strtolower($request->name);
		$module->desc = $request->desc;
		$module->save();

		return redirect()->back();
	}

	public function update($id, Request $request)
	{
		$module = Module::find($id);
		$module->name = strtolower($request->name);
		$module->desc = $request->desc;
		$module->save();

		return redirect()->back();
	}

	public function delete($id, Request $request)
	{
		Module::find($id)->delete();
		
		return redirect()->back();
	}
}
