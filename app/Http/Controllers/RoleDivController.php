<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPrivilege;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\RoleDivision;
use App\Models\Role;
use App\Models\Division;
use App\Models\Module;
use App\Models\Action;
use App\Models\RolePrivilege;
use DB;
use Session;

class RoleDivController extends Controller
{
	// public function index()
	// {
	// 	$number = 1;
	// 	$roleList = Role::pluck('name', 'id');

	// 	$divList = Division::pluck('name', 'id');

	// 	$parentLists = RoleDivision::all();

	// 	$roleDivsList = RoleDivision::select('rms_roles.name AS roleName', 'rms_divisions.name AS divName', 'rms_roles.id AS roleId', 'rms_divisions.id AS divId', 'rms_roles_divisions.*')
	// 	->join('rms_roles', 'rms_roles.id', '=', 'rms_roles_divisions.id_rms_roles')
	// 	->join('rms_divisions', 'rms_divisions.id', '=', 'rms_roles_divisions.id_rms_divisions')
	// 	->get();

	// 	$parentPosition = [];

	// 	foreach ($roleDivsList as $key => $roleDivList)
	// 	{
	// 		$parentPosition [$roleDivList->id] = RoleDivision::find($roleDivList->id_rms_roles_divisions_parent);
	// 	}

	// 	//ROLE
	// 	$numberRole = 1;
	// 	$roles = Role::all();

	// 	//DIVISION
	// 	$numberDiv = 1;
	// 	$divisions = Division::all();

	// 	return view ('company.detail', compact('number', 'roleDivsList', 'roleList', 'divList', 'parentLists','parentPosition','numberRole','roles','numberDiv','divisions'));
	// }

	public function store(Request $request)
	{
		//Role name
		$roleName = Role::find($request->id_rms_roles);

		//Division name
		$divName = Division::find($request->id_rms_divisions);

		//Role Division name
		$roleDivName = $roleName->name." ".$divName->name;

		$roleDiv = new RoleDivision;
		$roleDiv->id_company = base64_decode($request->coid);
		$roleDiv->id_rms_roles_divisions_parent = $request->id_rms_roles_divisions_parent;
		$roleDiv->id_rms_roles = $request->id_rms_roles;
		$roleDiv->id_rms_divisions = $request->id_rms_divisions;
		$roleDiv->name = $roleDivName;
		$roleDiv->save();

		return redirect()->back();
	}

	public function update($id, Request $request)
	{
		//Role name
		$roleName = Role::find($request->id_rms_roles);

		//Division name
		$divName = Division::find($request->id_rms_divisions);

		//Role Division name
		$roleDivName = $roleName->name." ".$divName->name;

		$roleDiv = RoleDivision::find($id);
		$roleDiv->id_company = base64_decode($request->coid);
		$roleDiv->id_rms_roles_divisions_parent = $request->id_rms_roles_divisions_parent;
		$roleDiv->id_rms_roles = $request->id_rms_roles;
		$roleDiv->id_rms_divisions = $request->id_rms_divisions;
		$roleDiv->name = $roleDivName;
		$roleDiv->save();

		return redirect()->back();
	}

	public function delete($id, Request $request)
	{
		RoleDivision::find($id)->delete();

		return redirect()->back();
	}

	public function editPrivilege($id)
	{
		$rolePriv = RoleDivision::find($id);
		$roleDiv = RoleDivision::select('rms_roles.name AS roleName', 'rms_divisions.name AS divName', 'rms_roles_divisions.id AS id', 'rms_roles.id AS roleId', 'rms_divisions.id AS divId')
		->join('rms_roles', 'rms_roles.id', '=', 'rms_roles_divisions.id_rms_roles')
		->join('rms_divisions', 'rms_divisions.id', '=', 'rms_roles_divisions.id_rms_divisions')
		->where('rms_roles_divisions.id', $id)
		->first();

		$companyId = base64_encode($rolePriv->id_company);

		$moduleList = Module::orderBy('name','asc')->pluck('name', 'id');
		$actionList = Action::pluck('name', 'id');

		$getModules = Module::all();
		foreach ($getModules as $keyModule => $getModule)
		{
			$moduleName [$getModule->id] = $getModule->name;
			$moduleDesc [$getModule->id] = $getModule->desc;
		}

		$getActions = Action::all();
		foreach ($getActions as $keyAction => $getAction)
		{
			$actionName [$getAction->id] = $getAction->name;
			$actionDesc [$getAction->id] = $getAction->desc;
		}

		return view ('position.privilege', compact('companyId','roleDiv', 'rolePriv', 'moduleList', 'actionList','moduleName','moduleDesc','actionName','actionDesc'));
	}

	public function updatePrivilege($id, Request $request)
	{
//	    dd($request);
		if($request->privilege)
		{
			RolePrivilege::where('id_rms_roles_divisions', $id)->forceDelete();
			foreach($request->privilege as $moduleId => $actionList)
			{
				foreach($actionList as $actionId => $value)
				{
					$rolePrivilege = new RolePrivilege;
					$rolePrivilege->id_rms_roles_divisions = $id;
					$rolePrivilege->id_rms_modules = $moduleId;
					$rolePrivilege->id_rms_actions = $actionId;
					$rolePrivilege->save();
				}
			}
		}
		else
		{
			RolePrivilege::where('id_rms_roles_divisions', $id)->forceDelete();
		}

        if (isset($request->to_child) && $request->to_child == "on"){
//			    $parent = RoleDivision::where('id', $id)->first();
            $childs = RoleDivision::where('id_rms_roles_divisions_parent', $id)->get();
            foreach ($childs as $child){
                RolePrivilege::where('id_rms_roles_divisions', $child->id)->forceDelete();
                foreach ($request->privilege as $moduleId => $actionList){
                    foreach($actionList as $actionId => $value)
                    {
                        $rolePrivilegeChild = new RolePrivilege;
                        $rolePrivilegeChild->id_rms_roles_divisions = $child->id;
                        $rolePrivilegeChild->id_rms_modules = $moduleId;
                        $rolePrivilegeChild->id_rms_actions = $actionId;
                        $rolePrivilegeChild->save();
                    }
                }
            }
        }

        if (isset($request->to_user) && $request->to_user == "on"){
            $users = User::where('id_rms_roles_divisions', $id)->get();
            foreach ($users as $user){
                UserPrivilege::where('id_users', $user->id)->forceDelete();
                foreach ($request->privilege as $moduleId => $actionList){
                    foreach($actionList as $actionId => $value)
                    {
                        $userPrivilage = new UserPrivilege();
                        $userPrivilage->id_users = $user->id;
                        $userPrivilage->id_rms_modules = $moduleId;
                        $userPrivilage->id_rms_actions = $actionId;
                        $userPrivilage->save();
                    }
                }
            }
        }

		return redirect()->route('rprivilege.edit', $id);
	}
}
