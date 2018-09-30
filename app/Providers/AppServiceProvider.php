<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //定制个性化路由
//        Route::resourceVerbs([
//            'create' => 'add',
//            'edit' => 'update'
//        ]);
        if (defined('SQL_DEBUG') && SQL_DEBUG) {
            DB::listen(function ($query){
                echo 'SQL:' . PHP_EOL;
                print_r($query->sql);
                echo PHP_EOL;

                echo 'Bindings:' . PHP_EOL;
                print_r($query->bindings);
                echo PHP_EOL;

                echo 'Time:<pre>' . PHP_EOL;
                print_r($query->time);
                echo PHP_EOL;
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
