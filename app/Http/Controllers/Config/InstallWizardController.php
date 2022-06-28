<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class InstallWizardController extends Controller
{
    private $error;
    function index(){
        if ($this->error == 1){
            $message = "Error with DB Connection";
//            unset($_SESSION['errors']);
        } else {
            $message = null;
        }
        return view('config.index', [
            'message' => $message
        ]);
    }

    function submit(Request $request){
//        dd($request);
        $comp_name = $request->company_name;
        $db_name  = $request->company_tag;
        $username = $request->root_username;
        $password = $request->root_password;
        $address  = $request->address;
        $npwp     = $request->npwp;
        $phone    = $request->phone;
        $email    = $request->email;

        // menyimpan data file yang diupload ke variabel $file
        $fileComp = $request->file('p_logo');
        $fileApp  = $request->file('ap_logo');
        $p_logo   = "p_logo_".$db_name.".".$fileComp->getClientOriginalExtension();
        $app_logo = "app_logo_".$db_name.".".$fileApp->getClientOriginalExtension();

        // MIGRATE DB
        include app_path('Config/migrate.php');

        if ($success == 1) {

            // isi dengan nama folder tempat kemana file diupload
            $tujuan_upload = public_path('images');

            // upload file
            $fileComp->move($tujuan_upload,$p_logo);
            $fileApp->move($tujuan_upload,$app_logo);

            $path = app_path('Config/.env');

            $env = explode("\n",file_get_contents($path, true));

            for($i = 0; $i < count($env); $i++){
                if (strpos($env[$i], "DB_USERNAME") !== false){
                    $env[$i] = "DB_USERNAME=".$username;
                } elseif (strpos($env[$i], "DB_PASSWORD") !== false){
                    $env[$i] = "DB_PASSWORD=".$password;
                } elseif (strpos($env[$i], "DB_DATABASE") !== false){
                    $env[$i] = "DB_DATABASE=".$db_name;
                } elseif (strpos($env[$i], "DB_CONFIG") !== false){
                    $env[$i] = "DB_CONFIG=1";
                } elseif (strpos($env[$i], "APP_LOGO") !== false){
                    $env[$i] = "APP_LOGO=".$app_logo;
                } elseif (strpos($env[$i], "COMPANY_LOGO") !== false){
                    $env[$i] = "COMPANY_LOGO=".$p_logo;
                }
            }

            $newenv = implode("\n", $env);

            file_put_contents($path, $newenv);

            // EDIT ENV LARAVEL

            $larpath = base_path()."/.env";

            $larenv = explode("\n",file_get_contents($larpath, true));

            for($i = 0; $i < count($larenv); $i++){
                if (strpos($larenv[$i], "DB_USERNAME") !== false){
                    $larenv[$i] = "DB_USERNAME=".$username;
                } elseif (strpos($larenv[$i], "DB_PASSWORD") !== false){
                    $larenv[$i] = "DB_PASSWORD=".$password;
                } elseif (strpos($larenv[$i], "DB_DATABASE") !== false){
                    $larenv[$i] = "DB_DATABASE=".$db_name;
                }
            }

            $newlarenv = implode("\n", $larenv);

            file_put_contents($larpath, $newlarenv);

            return Redirect::route('install.success');
        } else {
            $this->error = 1;
            return Redirect::route('install');
        }
    }

    function success(){
        $user = User::where('username', 'admin')->get();
        return view('config.success', [
            'user' => $user
        ]);
    }
}
