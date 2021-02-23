<?php

/**
 * @Author: kidkang
 * @Date:   2021-02-23 15:09:47
 * @Last Modified by:   kidkang
 * @Last Modified time: 2021-02-23 20:43:53
 */
namespace Yjtec\Area;
use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
class AreaServiceProvider extends ServiceProvider
{

    public function boot(Filesystem $filesystem){
        //config
        $this->publishes([
            __DIR__.'/../config/area.php' => config_path('area.php'),
        ],'config');
        //migration
        $this->publishes([
                __DIR__.'/../database/migrations/create_area_tables.php.stub' => $this->getMigrationFileName($filesystem, 'create_area_tables.php'),
            ], 'migrations');
        
        $this->commands([
            Commands\Seeder::class
        ]);


        Validator::extend('area', function ($attribute, $value, $parameters, $validator) {
            return app('area')->check($value) ? true : false;
        });
    } 

    public function register(){
        $this->mergeConfigFrom(
            __DIR__.'/../config/area.php',
            'area'
        );

        $this->app->singleton('area',function($app){
            return new Area($app['cache']);
        });
    }
    protected function getMigrationFileName(Filesystem $filesystem, $migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem) {
                return $filesystem->glob($path.'*_create_area_tables.php');
            })->push($this->app->databasePath()."/migrations/{$timestamp}_create_area_tables.php")
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path.'*_'.$migrationFileName);
            })
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}