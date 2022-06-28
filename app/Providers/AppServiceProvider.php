<?php

namespace App\Providers;

use App\Models\AppSetting;
use App\Models\ConfigCompany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('path.public', function() {
            return base_path('public_html');
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {
        if (!$this->app->runningInConsole()){
            if (get_config() == 1){
                $company = ConfigCompany::all();
                view()->share('comp', $company);

                $parent = ConfigCompany::whereNull('id_parent')->orderBy('id')->get();

                if(!empty(Session::get("company_id_parent"))){
                    $comp_parent = $parent->where('id', Session::get("company_id_parent"))->first();
                } else {
                    $comp_parent = $parent->first();
                }

                $app_setting = AppSetting::where('id', 1)->first();
                view()->share('app_comp', $comp_parent);
                view()->share('login_logo', $app_setting->login_logo);
                view()->share('dashboard_logo', $app_setting->dashboard_logo);
                view()->share('footer_tag', $app_setting->footer_tag);
            }


            $uom = array("kg", "unit", "buah", "meter", "pack", "roll", "ea", "buku", "inch", "lusin", "set", "rim", "gallon", "feet", "litre", "can", "lbs", "joint", "box", "bottle", "gram", "lembar", "drum", "lot", "day", "lumpsum", "monthly", "kali");
            view()->share('uom', $uom);
            $list_currency = '{"AUD": "Australian Dollar", "CNY": "Chinese Yuan", "EUR": "Euro", "GBP": "British Pound Sterling", "IDR": "Indonesian Rupiah", "JPY": "Japanese Yen", "SGD": "Singapore Dollar", "USD": "United States Dollar"}';
            view()->share('list_currency', $list_currency);
            $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
            view()->share('mnth_array', $array_bln);

            DB::connection()->enableQueryLog();

            $file_env = app_path('Config/.env', true);
            $env = explode("\n", file_get_contents($file_env));
            for ($i=0; $i < count($env); $i++){
                $content_env = explode("=", $env[$i]);
                if ($content_env[0] == "ACCOUNTING_MODE"){
                    $accounting_mode = end($content_env);
                }
                if ($content_env[0] == "DEBUG"){
                    $debug = end($content_env);
                }
            }

            $comp = ConfigCompany::all();
            $companies = array();
            foreach ($comp as $item) {
                $companies[$item->id] = $item;
            }

            view()->share('accounting_mode', $accounting_mode);
            view()->share('debug', $debug);
            view()->share('view_company', $companies);
        }


//        DB::connection()->enableQueryLog();
//        $queries = DB::getQueryLog();
//        $last_query = end($queries);
//        activity("query")
//            ->log($last_query);

//        if (env('APP_DEBUG')){
//            DB::listen(function ($query){
//                activity("query")
//                    ->log($query->sql);
//            });
//        }
    }
}
