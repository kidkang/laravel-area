<?php

/**
 * @Author: kidkang
 * @Date:   2021-02-23 15:09:47
 * @Last Modified by:   kidkang
 * @Last Modified time: 2021-02-23 16:37:38
 */
namespace Yjtec\Area;
use Illuminate\Support\ServiceProvider;
class AreaServiceProvider extends ServiceProvider
{

    public function boot(){

        $this->publishes([
            __DIR__.'/../config/area.php' => config_path('area.php'),
        ],'config');

        $this->commands([
            Commands\Seeder::class
        ]);
    } 

    public function register(){
        $this->mergeConfigFrom(
            __DIR__.'/../config/area.php',
            'area'
        );
    }
}