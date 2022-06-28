<?php

namespace App\Providers;


use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class RolesManagementServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \Blade::directive('moduleStart', function($expression){
            return "<?php if(RolesManagement::moduleStart({$expression})) : ?>";
        });

        \Blade::directive('moduleEnd', function(){
            return "<?php endif; ?>";
        });

        \Blade::directive('hasAction', function($expression){
            return "<?php print_r(RolesManagement::hasAction({$expression})); ?>" ;
        });

        \Blade::directive('actionStart', function($expression){
            $eE = explode(',', preg_replace("/[\(\)]/", '', $expression), 2);
            return "<?php if(RolesManagement::actionStart($eE[0], $eE[1])) : ;?>";
        });

        \Blade::directive('actionElse', function(){
            return "<?php else: ?>";
        });

        \Blade::directive('actionEnd', function(){
            return "<?php endif; ?>";
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('RolesManagement', function()
        {
            return new \App\Rms\RolesManagement;
        });
    }
}
