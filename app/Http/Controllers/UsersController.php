<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App\Models\User;
use App\Models\Action;
use App\Models\Module;
use App\Models\Users_zakat;
use App\Models\Hrd_employee;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\RolePrivilege;
use App\Models\UserPrivilege;
use App\Models\Hrd_salary_archive;
use App\Models\Hrd_att_transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function getCompany(Request $request){
        $users = ConfigCompany::where('company_name','like','%'.$request->searchTerm.'%')
            ->where('id', '!=', $request->comp)
            ->get();

        $data = [];
        foreach ($users as $value){
            $data[] = array(
                "id" => $value->id,
                "text" => $value->company_name
            );
        }
        return response()->json($data);
    }

    public function getUsers($id_company,Request $request){
        $arr = array(
            'company_id' => $id_company,
        );

        $usersComp = User::where('company_id', $request->comp)->get();
        $userExist = [];
        foreach($usersComp as $user){
            $userExist[] = $user->username;
        }

        $users = User::where($arr)
            ->whereNotIn('username', $userExist)
            ->where('name','like','%'.$request->searchTerm.'%')
            ->get();
        $data = [];
        foreach ($users as $value){
            $data[] = array(
                "id" => $value->id,
                "text" => $value->name
            );
        }
        return response()->json($data);
    }

    public function getDetailUser($id){
        $user = User::where('id',$id)->first();
        $userComp = User::where('username', $user->username)
            ->where('company_id', Session::get('company_id'))
            ->first();
        $mnths = [];
        for ($i=date('n'); $i >= 1 ; $i--) {
            $mnths[$i] = date('F', strtotime(date('Y')."-".$i));
        }

        $emp = null;
        $arch = [];

        if (!empty($userComp)) {
            if (!empty($userComp->emp_id)) {
                $emp = Hrd_employee::find($userComp->emp_id);
                $archive = Hrd_salary_archive::where('emp_id', $userComp->emp_id)->get();
                foreach ($archive as $key => $value) {
                    $mn = explode("-", $value->archive_period);
                    if (date('Y') == 2021) {
                        if ($mn[0] >= 3) {
                            $arch[$value->archive_period] = explode("-", $value->archive_period);
                        }
                    }
                }
            }
        }

        $userEmp = User::where('username', $user->username)
            ->whereNotNull('emp_id')
            ->first();

        $clockin = null;
        $clockout = null;
        if(!empty($userEmp)){
            $session = Hrd_att_transaction::where("user_id", $userEmp->id)->orderBy('id')->get();
            if(count($session) > 0){
                $clockin = $session[0]->trans_time;
                if(count($session) > 1){
                    $clockout = $session[count($session) - 1]->trans_time;
                }
            }
        }

        return view('users.detail',[
            'user' => $user,
            'mnths' => $mnths,
            'emp' => $emp,
            'arch' => $arch,
            'userComp' => $userEmp,
            'clockin' => $clockin,
            'clockout' => $clockout
        ]);
    }

    function randomize(Request $request){
        $user = User::find($request->id);
        $code = random_int(100000, 999999);
        $codeExist = User::where("attend_code", $code)->first();
        while(!empty($codeExist)){
            $code = random_int(100000, 999999);
            $codeExist = User::where("attend_code", $code)->first();
        }
        $user->attend_code = $code;
        if($user->save()){
            $data =['success' => 1];
            return json_encode($data);
        } else {
            return json_encode("error");
        }
    }

    public function updatePasswordAccount(Request $request){
        $this->validate($request,[
            'password' => 'required'
        ]);

        $user = User::find($request['id']);

        User::where('username',$user->username)
            ->where('id_batch', $user->id_batch)
            ->update([
                'password' => Hash::make($request['password']),
            ]);

        Auth::logout();
        return redirect()->route('home');
    }

    public function updateAccountInfo(Request $request){
        $uploaddir = public_path('theme\\assets\\media\\users');

        $pictureInput = $request->file('user_img');
        if ($pictureInput!= null) {
            $picture = $request['id'] . "-profile." . $pictureInput->getClientOriginalExtension();


            $path = $uploaddir . '\\' . $picture;
            if (file_exists($path)) {
                @unlink($path);
            }
            $pictureInput->move($uploaddir, $picture);
            $emp_picture = $picture;
            User::where('id', $request['id'])
                ->update([
                    'user_img' => $emp_picture,
                ]);
        }
        return redirect()->route('account.info',['id'=>$request['id']]);
    }

    function user_exist($username, $company_id){
        $user = User::where('username', $username)
            ->where('company_id', $company_id)
            ->first();

        $exist = 0;

        if(!empty($user)){
            $exist = 1;
        }

        return $exist;
    }

    function add(Request $request){
         if (isset($request->export)){
//            dd(base64_decode($request->coid));
//            dd($request);
            $user = User::where('id', $request->user_company)->first();
//            dd($user);

            if($this->user_exist($user->username, base64_decode($request->coid)) == 0){
                $userNew = new User();
                $userNew->id_batch = $user->id_batch;
                $userNew->name = $user->name;
                $userNew->password = $user->password;
                $userNew->username = $user->username;
                $userNew->email = $user->email;
                $userNew->company_id = base64_decode($request->coid);
                $userNew->id_rms_roles_divisions = $user->id_rms_roles_divisions;
                $userNew->save();

                $upriv = UserPrivilege::select("id_rms_modules", "id_rms_actions")
                    ->where('id_users', $user->id)
                    ->get();

                $roleDivPriv = RolePrivilege::select('id_rms_modules', 'id_rms_actions')
                ->where('id_rms_roles_divisions', $userNew->id_rms_roles_divisions)
                ->get();
                if(!empty($upriv)){
                    foreach ($upriv as $key => $valDivPriv) {
                        $addUserRole = new UserPrivilege;
                        $addUserRole->id_users = $userNew->id;
                        $addUserRole->id_rms_modules = $valDivPriv->id_rms_modules;
                        $addUserRole->id_rms_actions = $valDivPriv->id_rms_actions;
                        $addUserRole->save();
                    }
                }
            } else {
                return redirect()->back()->with('msg', 'User Exist');
            }

        } else {
            $name = $request->name;
            $email = $request->email;
            $username = $request->username;
            $password = Hash::make($request->password);
            // $position = $request->position;
            $id = base64_decode($request->coid);

            $usernameExist =  User::selectRaw("*, CAST(RIGHT(id_batch, (LENGTH(id_batch) - LENGTH(username))) as UNSIGNED) batch_num")->where("username", $username)->orderBy('batch_num', 'desc')->get();

            if (count($usernameExist) == 0) {
                $user = new User;
                $user->id_batch = $username."1";
                $user->emp_id = $request->empId;
                $user->name = $name;
                $user->email = $email;
                $user->username = $username;
                $user->password = $password;
                if(!empty($request->dispatch_name)){
                    $user->dispatch_name = $request->dispatch_name;
                }
                // $user->position = $position;
                $user->id_rms_roles_divisions = $request->userRoleAdd;
                $user->company_id = $id;
                $user->save();

                //Add user privilege based on position
                $roleDivPriv = RolePrivilege::select('id_rms_modules', 'id_rms_actions')
                ->where('id_rms_roles_divisions', $user->id_rms_roles_divisions)
                ->get();
                foreach ($roleDivPriv as $key => $valDivPriv) {
                    $addUserRole = new UserPrivilege;
                    $addUserRole->id_users = $user->id;
                    $addUserRole->id_rms_modules = $valDivPriv->id_rms_modules;
                    $addUserRole->id_rms_actions = $valDivPriv->id_rms_actions;
                    $addUserRole->save();
                }
            } else {
                $userBatch = User::selectRaw("*, CAST(RIGHT(id_batch, (LENGTH(id_batch) - LENGTH(username))) as UNSIGNED) batch_num")->where("username", $username)->orderBy('batch_num', 'desc')->get();
                $id_batch = $userBatch[0]->batch_num + 1;
                $user = new User;
                $user->id_batch = $username."$id_batch";
                $user->emp_id = $request->empId;
                $user->name = $name;
                $user->email = $email;
                $user->username = $username;
                $user->password = $password;
                if(!empty($request->dispatch_name)){
                    $user->dispatch_name = $request->dispatch_name;
                }
                // $user->position = $position;
                $user->id_rms_roles_divisions = $request->userRoleAdd;
                $user->company_id = $id;
                $user->save();

                //Add user privilege based on position
                $roleDivPriv = RolePrivilege::select('id_rms_modules', 'id_rms_actions')
                ->where('id_rms_roles_divisions', $user->id_rms_roles_divisions)
                ->get();
                foreach ($roleDivPriv as $key => $valDivPriv) {
                    $addUserRole = new UserPrivilege;
                    $addUserRole->id_users = $user->id;
                    $addUserRole->id_rms_modules = $valDivPriv->id_rms_modules;
                    $addUserRole->id_rms_actions = $valDivPriv->id_rms_actions;
                    $addUserRole->save();
                }
                return redirect()->back();;
            }
        }

        return redirect()->back();
    }

    function edit(Request $request){
        $user = User::find($request->id_u);

        $user->username = $request->username;
        $user->email = $request->name;
        $user->email = $request->email;
        $user->emp_id = $request->empId;

        User::where('username', $user->username)
            ->update([
                'username' => $request->username,
                'name' => $request->name,
                'email' => $request->email
            ]);
        if (!empty($request->password) || $request->password != "" || $request->password != null){
            // $user->password = Hash::make($request->password);
            // $userPass = User::where("username", $user->username);
            // $userPass->password = Hash::make($request->password);
            User::where('username', $user->username)
                ->update([
                    'password' => Hash::make($request->password)
                ]);
        }
        if (!empty($request->do_code) || $request->do_code != "" || $request->do_code != null){
            $user->do_code = $request->do_code;
        } elseif (empty($request->do_code) && isset($request->delete_code) && $request->delete_code == 1) {
            $user->do_code = null;
        }
        // $user->position = $request->position;
        $user->save();

        // Change user position
        if($request->userRoleEdit != $request->userRoleEditOld)
        {
            $userRole = User::find($request->id_u);
            $userRole->id_rms_roles_divisions = $request->userRoleEdit;
            $userRole->save();

            //Delete existing user privilege
            UserPrivilege::where('id_users', $request->id_u)->forceDelete();

            //Edit user privilege based on new position
            $roleDivPriv = RolePrivilege::select('id_rms_modules', 'id_rms_actions')
            ->where('id_rms_roles_divisions', $request->userRoleEdit)
            ->get();

            foreach ($roleDivPriv as $key => $valDivPriv)
            {
                $editUserRole = new UserPrivilege;
                $editUserRole->id_users = $request->id_u;
                $editUserRole->id_rms_modules = $valDivPriv->id_rms_modules;
                $editUserRole->id_rms_actions = $valDivPriv->id_rms_actions;
                $editUserRole->save();
            }
        }

        if(Auth::id() == $user->id){
            //RoleManagement
            $modules = Module::all()->pluck('name', 'id');
            $actions = Action::all()->pluck('name', 'id');
            $userPriv = UserPrivilege::where('id_users', $user->id)->get();
            $pr = [];
            foreach($userPriv as $priv){
                if(isset($modules[$priv->id_rms_modules])){
                    if(isset($actions[$priv->id_rms_actions])){
                        $pr[$modules[$priv->id_rms_modules]][$actions[$priv->id_rms_actions]] = 1;
                    }
                }
            }
            Session::put('company_user_rc', $pr);
        }

        return redirect()->back();
    }
    function delete($id){
        $rms = DB::table('rms_users_privileges')->where('id_users', $id)->get();
        $count_rms = count($rms);
        // dd($rms);
        if ($count_rms > 0) {
            DB::table('rms_users_privileges')->where('id_users', $id)->delete();
        }
        $user = User::find($id);

        if ($user->delete()){
            $data['del'] = 1;
        } else {
            $data['del'] = 0;
        }

        // return json_encode($data);
        return redirect()->back();
    }

    public function getUserPrivilege($id){
        $user = User::where('id',$id)->first();
        $companyId = base64_encode($user->company_id);
        $moduleList = Module::orderBy('name','asc')->pluck('desc', 'id');
        $actionList = Action::pluck('name', 'id');

        return view('users.privilege',compact('companyId','user','actionList','moduleList'));
    }

    public function updatePrivilege($id, Request $request){
        if($request->privilege)
        {
            UserPrivilege::where('id_users', $id)->forceDelete();
            foreach($request->privilege as $moduleId => $actionList)
            {
                foreach($actionList as $actionId => $value)
                {
                    $privilege = new UserPrivilege;
                    $privilege->id_users = $id;
                    $privilege->id_rms_modules = $moduleId;
                    $privilege->id_rms_actions = $actionId;
                    $privilege->save();
                }
            }
        }
        else
        {
            UserPrivilege::where('id_users', $id)->forceDelete();
        }

        if(Auth::id() == $id){
            //RoleManagement
            $modules = Module::all()->pluck('name', 'id');
            $actions = Action::all()->pluck('name', 'id');
            $userPriv = UserPrivilege::where('id_users', $id)->get();
            $pr = [];
            foreach($userPriv as $priv){
                if(isset($modules[$priv->id_rms_modules])){
                    if(isset($actions[$priv->id_rms_actions])){
                        $pr[$modules[$priv->id_rms_modules]][$actions[$priv->id_rms_actions]] = 1;
                    }
                }
            }
            Session::put('company_user_rc', $pr);
        }

        return redirect()->route('user.privilege', $id);
    }

    function signAdd(Request $request, $id){
        $user = User::find($id);

        $type = strtolower($request->type);

        if ($request->rb_sign == 1) {
            $folderPath = public_path("media/user/".$type."/");

            $image_parts = explode(";base64,", $request->imageData);

            $image_type_aux = explode("image/", $image_parts[0]);

            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);

            $file_name = uniqid() . '.'.$image_type;

            $file = $folderPath . $file_name;
            $up = file_put_contents($file, $image_base64);

            // $image = $request['imageData'];
            // $image = str_replace('data:image/png;base64,', '', $image);
            // $image = str_replace(' ', '+', $image);

            // $file_name = "u-(".$user->id.")_" .$type . '.png';
            // $up = Storage::disk('sign_account')->put($file_name,base64_decode($image));
        } else {
            $file = $request->file('file_upload');
            $target = public_path("media/user/".$type."/");
            $file_name = "(".$user->id.")".$file->getClientOriginalName();
            $up = $file->move($target, $file_name);
        }

        if ($up) {
            $user['file_'.$type] = $file_name;
            User::where('username', $user->username)
                ->update([
                    "file_".$type => $file_name
                ]);
            // $user->save();
            $msg = "File uploaded";
            $success = true;
        } else {
            $msg = "Failed to upload";
            $success = false;
        }

        $result = array(
            "success" => $success,
            "message" => $msg
        );

        return json_encode($result);


    }

    public function inherit($id){
        $user = User::find($id);

        //Add user privilege based on position
        $roleDivPriv = RolePrivilege::select('id_rms_modules', 'id_rms_actions')
            ->where('id_rms_roles_divisions', $user->id_rms_roles_divisions)
            ->get();
        foreach ($roleDivPriv as $key => $valDivPriv)
        {
            $addUserRole = new UserPrivilege;
            $addUserRole->id_users = $user->id;
            $addUserRole->id_rms_modules = $valDivPriv->id_rms_modules;
            $addUserRole->id_rms_actions = $valDivPriv->id_rms_actions;
            $addUserRole->save();
        }

        return redirect()->back();
    }

    public function userModule(Request $request){

        $user = User::find($request->id);
        $module = Module::find($request->module);
        $action = Action::all()->pluck('name', 'id');

        $otherUsers = User::where('username', $user->username)->get()->pluck('company_id', 'id');

        $companies = ConfigCompany::all()->pluck('company_name', 'id');

        return view('users._module_modal', [
            "user" => $user,
            "module" => $module,
            "actions" => $action,
            'companies' => $companies,
            'userComp' => $otherUsers
        ]);
    }

    public function userModuleSave(Request $request){
        $id_comp = [];
        foreach($request->company as $compKey => $item){
            $id_comp[] = $compKey;
        }

        $user = User::find($request->id_user);

        $users = User::where('username', $user->username)
            ->whereIn('company_id', $id_comp)
            ->get();


        foreach ($users as $itemUser) {
            $priv = UserPrivilege::where('id_users', $itemUser->id)
                ->where('id_rms_modules', $request->id_module);

            if(!empty($priv->get())){
                $priv->forceDelete();
            }
            if(isset($request->privilege)){
                foreach ($request->privilege as $key => $value) {
                    $newPriv = new UserPrivilege();
                    $newPriv->id_users = $itemUser->id;
                    $newPriv->id_rms_modules = $request->id_module;
                    $newPriv->id_rms_actions = $key;
                    $newPriv->save();
                }
            }
        }

        return redirect()->back();
    }

    function my_zakat(){
        $emp = Hrd_employee::find(Auth::user()->emp_id);
        $balance = 0;
        $zakat = [];
        if(!empty($emp)){
            $zakat = Users_zakat::where('emp_id', $emp->id)
                ->orderBy('created_at', 'desc')
                ->get();
            foreach ($zakat as $key => $value) {
            $balance += $value->amount - $value->paid;
        }
        }


        return view('users.zakat', compact('emp', 'zakat', 'balance'));
    }

    function pay_zakat(Request $request){
        $amount = str_replace(",", "", $request->payment_amount);

        $zakat = new Users_zakat();
        $zakat->emp_id = Auth::user()->emp_id;
        $zakat->description = "Payment Zakat at ".date("F Y");
        $zakat->amount = $amount * -1;
        $zakat->company_id = Auth::user()->company_id;
        $zakat->save();
        return redirect()->back()->with('msg', 'success');
    }

    function update_metamask(Request $request){
        $user = User::find($request->id);
        if($request->type == "disconnect"){
            $user->metamask_id = null;
        } else {
            $user->metamask_id = $request->metamask;
        }
        $user->save();

        return json_encode(1);
    }

    function check_metamask(Request $request){
        $user = User::where('metamask_id',$request->metamask)->first();

        if(!empty($user)){
            if($user->metamask_id != Auth::user()->metamask_id){
                return json_encode(0);
            } else {
                return json_encode(1);
            }
        } else {
            return json_encode(1);
        }
    }
}
