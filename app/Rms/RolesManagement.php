<?php

namespace App\Rms;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;
use App\Models\Module;
use App\Models\Action;
use App\Models\UserPrivilege;
use Session;

class RolesManagement extends Facade
{
	public static function user()
	{
        $id_user = (Auth::guard()->id() == 1) ? Auth::guard()->id() : Session::get('company_user_id');
        $userHasPriv = UserPrivilege::where('id_users', $id_user)->get();
        if(count($userHasPriv) == 0){
            $id = Auth::guard()->id();
        } else {
            $id = (Auth::guard()->id() == 1) ? Auth::guard()->id() : Session::get('company_user_id');
        }
		return $id;
	}

    public static function userId(){
        return Auth::guard()->id();
    }

	// for middleware

	public static function role($value1, $value2)
	{
		$valReturn = false;
		$id_module = Module::where('name', $value1)->pluck('id')->first();
		$id_action = Action::where('name', $value2)->pluck('id')->first();
		$roles = new UserPrivilege;
		$userHasModules = $roles->where('id_users', self::user())->where('id_rms_modules', $id_module)->where('id_rms_actions', $id_action)->get();
		if($userHasModules->count() > 0)
		{
			$valReturn = true;
		}
		return $valReturn;
	}

	// end of middleware

	public static function hasModule()
	{
	  	$roles = UserPrivilege::with('module');

		$userHasModules = $roles->where('id_users', self::user())->get();
        if(empty($userHasModules)){
            $userHasModules = $roles->where('id_users', self::userId())->get();
        }
		$modules = NULL;
		$id_modules = NULL;
		foreach ($userHasModules as $uhm)
		{
			if($id_modules != $uhm->id_modules)
			{
				$modules[] = $uhm->module->name;
			}
			$id_modules = $uhm->id_modules;
		}
		return $modules;
	}

	public static function hasAction($value)
	{
		$modules = Module::where('name', $value)->first();
		if ($modules)
		{
			$id_modules = $modules->id;


			$roles = UserPrivilege::with('action');
			$moduleHasActions = $roles->where('id_users', self::user())->where('id_rms_modules', $id_modules)->get();
            if(empty($moduleHasActions)){
                $moduleHasActions = $roles->where('id_users', self::userId())->where('id_rms_modules', $id_modules)->get();
            }
			$actions = NULL;
			foreach ($moduleHasActions as $mhs)
			{
				$actions[] = $mhs->action->name;
			}
			return $actions;
		}
		else
		{
			return FALSE;
		}
	}

	public static function moduleStart($value)
	{
		$valReturn = false;
		$hasModule = self::hasModule();
		if(!empty($hasModule))
		{
			if(in_array($value, $hasModule))
			{
				$valReturn = true;
			}
		}
		return $valReturn;
	}

	public static function actionStart($value1, $value2)
	{
        $valReturn =  false;
        $rc = \Session::get('company_user_rc');
        if(!empty($rc)){
            if(isset($rc[$value1])){
                $val2 = explode("|", $value2);
                $val = [];
                $mod = $rc[$value1];

                if(count($val2) > 1){
                    foreach($val2 as $v){
                        if(isset($mod[$v]) && $mod[$v] == 1){
                            $val[] = true;
                        } else {
                            $val[] = false;
                        }
                    }

                    $condition = '';
                    for ($x=0; $x < count($val); $x++)
                    {
                        if($x % 2 == 0 )
                        {
                            $condition .= " or ";
                        }
                        $condition .= $val[$x];
                    }
                    if($value1 == "announcement"){
                        dd($condition);
                    }
                    if ($condition)
                    {
                        $valReturn = true;
                    }
                } else {
                    if(isset($mod[$value2]) && $mod[$value2] == 1){
                        $valReturn = true;
                    }
                }
            }
        }

        return $valReturn;
		// $valReturn = false;
		// $unpack_value2 = explode("|", $value2);
		// $hasAction = self::hasAction($value1);
		// if(!empty($hasAction))
		// {
		// 	if(count($unpack_value2) < 2)
		// 	{
		// 		if(in_array($unpack_value2[0], $hasAction))
		// 		{
		// 			$valReturn = true;
		// 		}
		// 		else
		// 		{
		// 			$valReturn = false;
		// 		}
		// 	}
		// 	else
		// 	{
		// 		for ($i=0; $i < count($unpack_value2); $i++)
		// 		{
		// 			if(in_array($unpack_value2[$i], $hasAction))
		// 			{
		// 				$val[] = true;
		// 			}
		// 			else
		// 			{
		// 				$val[] = false;
		// 			}
		// 		}
		// 		$condition = '';
		// 		for ($x=0; $x < count($val); $x++)
		// 		{
		// 			if($x % 2 == 0 )
		// 			{
		// 				$condition .= " or ";
		// 			}
		// 			$condition .= $val[$x];
		// 		}
		// 		if ($condition)
		// 		{
		// 			$valReturn = true;
		// 		}
		// 		// print_r($condition);
		// 	}
		// 	return $valReturn;
		// }
		// else
		// {
		// 	return FALSE;
		// }
	}
}
