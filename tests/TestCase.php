<?php

/**
 * @Author: kidkang
 * @Date:   2021-02-23 15:59:18
 * @Last Modified by:   kidkang
 * @Last Modified time: 2021-02-23 17:24:26
 */
namespace Yjtec\Area\Test;
use Orchestra\Testbench\TestCase as Orchestra;
use Yjtec\Area\AreaServiceProvider;
abstract class TestCase extends Orchestra{
    public function setUp():void
    {
        parent::setUp();
        $this->setUpDatabases($this->app);
    }
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            AreaServiceProvider::class,
        ];
    }
    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        //sqlite
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        
        //mysql
        
        // $app['config']->set('database.default','mysql');
        // $app['config']->set('database.connections.mysql', [
        //     'driver' => 'mysql',
        //     'host' => '127.0.0.1',
        //     'port' => '3306',
        //     'database' => 'area',
        //     'username' => 'root',
        //     'password' => 'root',
        //     'unix_socket' => env('DB_SOCKET', ''),
        //     'charset' => 'utf8mb4',
        //     'collation' => 'utf8mb4_unicode_ci',
        //     'prefix' => '',
        //     'prefix_indexes' => true,
        //     'strict' => true,
        //     'engine' => null,
        // ]);
        
    }

    protected function setUpDatabases($app){
        include_once __DIR__.'/../database/migrations/create_tables.php.stub';
        (new \CreateAreaTables())->up();
    }
}