<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Action;
use App\Models\Module;
use Illuminate\Support\Str;
use App\Models\RoleDivision;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\User_activity;
use App\Models\UserPrivilege;
use App\Models\Notification_log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\BaseController;


class LoginController extends BaseController
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function getnotif($comp_id,$user_id){
        $notif = Notification_log::where('id_users', 'like', '%"'.$user_id.'"%')
            ->orderBy('created_at', 'desc')
            ->where('company_id', $comp_id)
            ->whereNull('action_at')
            ->get();

        if ($notif){
            return $this->sendResponse($notif, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

    public function login(Request $request){
        $username = $request->username;
        $company = $request->company;
        try {
            if (strtolower($company) == "dispatch" || $username == "cypher") {
                $whereCompany = " 1";
            } else {
                $whereCompany = " users.company_id = $company";
            }


            $user = User::where('users.username', $username)
                ->whereRaw($whereCompany)
                ->first();

            $password = Hash::check($request->password,$user->password);

            // return $this->sendResponse($password, 'User login Successfully');

            if(!empty($user)){
                if ($password){
                    $_user = User::leftJoin('config_company as comp','comp.id','=','users.company_id')
                        ->leftJoin('rms_roles_divisions as roles','roles.id','=','users.id_rms_roles_divisions')
                        ->leftJoin('rms_roles as role','role.id','=','roles.id_rms_roles')
                        ->select('users.*','role.name as role_name')
                        ->where('users.username', $username)
                        ->whereRaw($whereCompany)
                        ->first();
                    $_user->_token = Str::random(32);
                    if(strtolower($company) == "dispatch"){
                        $_user->view = "dispatcher";
                    }
                    $id_comp = ($company == "dispatch") ? $_user->company_id : $company;

                    $dcomp = ConfigCompany::where('id', $id_comp)->first();
                    $_user->company_name = $dcomp->company_name;
                    if($_user->username == "cypher"){
                        $_user->company_id = $dcomp->id;
                    }
                    $this->update_api_token($_user->_token, $user);

                    //RoleManagement
                    $modules = Module::all()->pluck('name', 'id');
                    $actions = Action::all()->pluck('name', 'id');
                    $userPriv = UserPrivilege::where('id_users', $_user->id)->get();
                    $pr = [];
                    foreach($userPriv as $priv){
                        if(isset($modules[$priv->id_rms_modules])){
                            if(isset($actions[$priv->id_rms_actions])){
                                $pr[$modules[$priv->id_rms_modules]][$actions[$priv->id_rms_actions]] = 1;
                            }
                        }
                    }

                    $_user->rc = $pr;

                    // return $this->sendResponse($user, 'User login Successfully');
                    // User::where("username", $username)
                    //     ->whereRaw($whereCompany)
                    //     ->update([
                    //         'api_token' => $_user->_token
                    //     ]);
                    // $roles = RoleDivision::where(['id' =>$_user->id_rms_roles_divisions,'id_company' => $_user->company_id])->first();
                    return $this->sendResponse($_user, 'User login Successfully');
                } else {
                    return $this->sendError('Invalid Credentials (Password)');
                }
            } else {
                return $this->sendError('Invalid Credentials (Username)');
            }
        }catch (\Exception $exception){
            return $this->sendError('Invalid Credentials (User not found in this company)');
        }
    }

    public function getCompany(){
        $company = ConfigCompany::select('id','company_name')->get();
        if ($company){
            return $this->sendResponse($company, 'Success');
        } else {
            return $this->sendError('Failed');

        }
    }

    function update_api_token($token, $user){
        $user->api_token = $token;
        // return $user;
        return $user->save();
    }

    public function getUser($comp_id){
        $user = User::where('company_id',$comp_id)->get();
        if ($user){
            return $this->sendResponse($user, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

    public function getUserActivty($comp_id,$user_id){
        $user = User::select('id','company_id','name','email','username','do_code')
            ->where('id', $user_id)->first();
        $u_act = User_activity::leftJoin('users as u','u.id' ,'=','user_activity.user_id')
            ->select('user_activity.id','user_activity.user_id','user_activity.location','user_activity.notes','user_activity.created_at','user_activity.latitude','user_activity.longitude')
            ->where('user_activity.user_id', $user_id)
            ->where('u.company_id', $comp_id)
            ->orderBy('user_activity.created_at','DESC')
            ->get();

        $data = [
            'user' => $user,
            'user_activity' => $u_act
        ];

        if ($user){
            return $this->sendResponse($data, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

    public function addActivity(Request $request){
        $u_act = new User_activity();
        $u_act->user_id = $request['user_id'];
        $u_act->location = $request['address'];
        $u_act->latitude = $request['latitude'];
        $u_act->longitude = $request['longitude'];
        $u_act->created_by = $request['username'];
        $u_act->notes = $request['notes'];
        $u_act->created_at = date('Y-m-d H:i:s');
        if ($u_act->save()){
            return $this->sendResponse($u_act,'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

}
