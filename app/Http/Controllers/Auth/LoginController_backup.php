<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Hrd_config;
use App\Models\Preference_config;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\ConfigCompany;
use Illuminate\Http\Request;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function username()
    {
        return 'username'; //or return the field which you want to use.
    }
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $config_company = ConfigCompany::where('id',1)->first();
//            dd($config_company->company_name);
            Session::put('company_id', $config_company->id);
            Session::put('company_name_parent',$config_company->company_name);
            Session::put('company_address',$config_company->address);
            Session::put('company_npwp',$config_company->npwp);
            Session::put('company_phone',$config_company->phone);
            Session::put('company_email',$config_company->email);
            Session::put('company_tag',$config_company->tag);
            Session::put('company_bgcolor',$config_company->bgcolor);
            Session::put('company_p_logo',$config_company->p_logo);
            Session::put('company_app_logo',$config_company->app_logo);
            Session::put('company_bgcolor', $config_company->bgcolor);
            Session::put('company_child', ConfigCompany::select('id')
                ->where('id_parent', $config_company->id)
                ->whereNotNull('inherit')
                ->get());

            $pref = Preference_config::where('id_company', 1)->first();
            if (!empty($pref)){
                Session::put('company_period_start', $pref->period_start);
                Session::put('company_period_end', $pref->period_end);
                Session::put('company_absence_deduction', $pref->absence_deduction);
                Session::put('company_bonus_period', $pref->bonus_period);
                Session::put('company_thr_period', $pref->thr_period);
                Session::put('company_odo_rate', $pref->odo_rate);
                Session::put('company_overtime_period', $pref->overtime_period);
                Session::put('company_overtime_start', $pref->overtime_start);
                Session::put('company_overtime_amt', $pref->overtime_amt);
                Session::put('company_penalty_amt', $pref->penalty_amt);
                Session::put('company_penalty_period', $pref->penalty_period);
                Session::put('company_penalty_start', $pref->penalty_start);
                Session::put('company_penalty_stop', $pref->penalty_stop);
                Session::put('company_performa_period', $pref->performa_period);
                Session::put('company_performa_start', $pref->performa_start);
                Session::put('company_performa_end', $pref->performa_end);
                Session::put('company_approval_start', $pref->approval_start);
                Session::put('company_btl_col', $pref->btl_col);
                Session::put('company_performa_amt1', $pref->performa_amt1);
                Session::put('company_performa_amt2', $pref->performa_amt2);
                Session::put('company_performa_amt3', $pref->performa_amt3);
                Session::put('company_performa_amt4', $pref->performa_amt4);
                Session::put('company_performa_amt5', $pref->performa_amt5);
                Session::put('company_wo_signature', $pref->wo_signature);
                Session::put('company_po_signature', $pref->po_signature);
                Session::put('company_to_signature', $pref->to_signature);
            } else {
                $hrd_config = Hrd_config::all();
                foreach ($hrd_config as $key => $value) {
                    $opt_val = json_decode($value->opt_value);
                    $count_opt = count(json_decode($value->opt_value));
                    for ($i = 0; $i < $count_opt; $i++){
                        if ($opt_val[$i]->id == $config_company->id) {
                            switch ($value->opt_name) {
                                case "period_start":
                                    Session::put('company_period_start', $opt_val[$i]->value);
                                    break;
                                case "period_end":
                                    Session::put('company_period_end', $opt_val[$i]->value);
                                    break;
                                case "absence_deduction":
                                    Session::put('company_absence_deduction', $opt_val[$i]->value);
                                    break;
                                case "bonus_period":
                                    Session::put('company_bonus_period', $opt_val[$i]->value);
                                    break;
                                case "thr_period":
                                    Session::put('company_thr_period', $opt_val[$i]->value);
                                    break;
                                case "odo_rate":
                                    Session::put('company_odo_rate', $opt_val[$i]->value);
                                    break;
                                case "penalty_amt":
                                    Session::put('company_penalty_amt', $opt_val[$i]->value);
                                    break;
                                case "penalty_period":
                                    Session::put('company_penalty_period', $opt_val[$i]->value);
                                    break;
                                case "penalty_start":
                                    Session::put('company_penalty_start', $opt_val[$i]->value);
                                    break;
                                case "penalty_stop":
                                    Session::put('company_penalty_stop', $opt_val[$i]->value);
                                    break;
                                case "performa_period":
                                    Session::put('company_performa_period', $opt_val[$i]->value);
                                    break;
                                case "performa_start":
                                    Session::put('company_performa_start', $opt_val[$i]->value);
                                    break;
                                case "performa_end":
                                    Session::put('company_performa_end', $opt_val[$i]->value);
                                    break;
                                case "approval_start":
                                    Session::put('company_approval_start', $opt_val[$i]->value);
                                    break;
                                case "btl_col":
                                    Session::put('company_btl_col', $opt_val[$i]->value);
                                    break;
                                case "performa_amt1":
                                    Session::put('company_performa_amt1', $opt_val[$i]->value);
                                    break;
                                case "performa_amt2":
                                    Session::put('company_performa_amt2', $opt_val[$i]->value);
                                    break;
                                case "performa_amt3":
                                    Session::put('company_performa_amt3', $opt_val[$i]->value);
                                    break;
                                case "performa_amt4":
                                    Session::put('company_performa_amt4', $opt_val[$i]->value);
                                    break;
                                case "performa_amt5":
                                    Session::put('company_performa_amt5', $opt_val[$i]->value);
                                    break;
                                case "wo_signature":
                                    Session::put('company_wo_signature', $opt_val[$i]->value);
                                    break;
                                case "po_signature":
                                    Session::put('company_po_signature', $opt_val[$i]->value);
                                    break;
                                case "to_signature":
                                    Session::put('company_to_signature', $opt_val[$i]->value);
                                    break;
                            }
                        }
                    }
                }
            }

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
    public function showLoginForm()
    {
        $all_company = ConfigCompany::all();
        $config_company = ConfigCompany::where('id',1)->first();
        $company_name = $config_company->company_name;
        return view('auth.login',[
            'company_holding' => $company_name,
            'companies' => $all_company,
        ]);
    }
}
