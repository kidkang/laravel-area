<?php

/**
 * @Author: kidkang
 * @Date:   2021-02-23 16:54:09
 * @Last Modified by:   kidkang
 * @Last Modified time: 2021-02-23 17:44:48
 */
namespace Yjtec\Area\Test;
use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
class MigrationTest extends TestCase{
    use RefreshDatabase;

    public function testExample(){
        // $this->assertTrue(true);
        $table = config('area.table_names.area');
        
        $this->assertDatabaseCount($table,0);

        DB::table('area')->insert([
            'name' => 'kidang',
        ]);

        $this->assertDatabaseCount($table,1);
    }
}
