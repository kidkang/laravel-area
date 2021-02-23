<?php

/**
 * @Author: kidkang
 * @Date:   2021-02-23 16:32:33
 * @Last Modified by:   kidkang
 * @Last Modified time: 2021-02-23 17:28:44
 */
namespace Yjtec\Area\Test;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
class CommandTest extends TestCase{
    use RefreshDatabase;
    
    public function testSeeder(){
        $table = config('area.table_names.area');
        Artisan::call('area:seeder');
        $data =json_decode(file_get_contents(__DIR__.'/../database/area.json'),true);
        $this->assertDatabaseCount($table,count($data) + 1);
    }
}