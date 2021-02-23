<?php

/**
 * @Author: kidkang
 * @Date:   2021-02-23 18:23:32
 * @Last Modified by:   kidkang
 * @Last Modified time: 2021-02-23 20:30:05
 */
namespace Yjtec\Area\Test;
use Yjtec\Area\Area;
use Yjtec\Area\Models\Area as AreaModel;
class AreaTest extends TestCase{

    public function setUp():void{
        parent::setUp();
        AreaModel::create([
            'name' => 'kidkang',
            'children' => [
                ['name' => 'yjtec']
            ]
        ]);
    }
    public function testAll(){
        $area = $this->getArea();
        $data  = $area->all();
        $this->assertEquals('kidkang',$data[0]['name']);
    }

    public function testAllFlat(){
        $area = $this->getArea();
        $data = $area->all(false);
        $this->assertCount(2,$data);
    }

    public function testCheckWithString(){
        $area = $this->getArea();
        $this->assertNotFalse($area->check('1'));
        $this->assertFalse($area->check('not exists'));
        $this->assertNotFalse($area->check('1,2'));
        $this->assertFalse($area->check('1,not exists'));
    }

    public function testCheckWithArray(){
        $area = $this->getArea();
        $this->assertNotFalse($area->check([1]));
        $this->assertNotFalse($area->check(['1']));
        $this->assertFalse($area->check(['not exists']));
        $this->assertNotFalse($area->check([1,2]));
        $this->assertFalse($area->check([1,10]));
    }

    public function testCheckTimes(){
        $area = $this->getArea();
        $this->assertNotFalse($area->check(['1']));
        $this->assertArrayHasKey('1',$area->checked());
    }
    public function testChecked(){
        $area = $this->getArea();
        $area->check(1);
        $this->assertCount(1,$area->checked(1));
        
        $key = '1,2';
        $area->check($key);
        $this->assertCount(2,$area->checked($key));

        $key =[1,2];
        $area->check($key);
        $this->assertCount(2,$area->checked($key));

        $key = [1,3];
        $area->check($key);
        $this->assertFalse($area->checked($key));
    }

    public function getArea(){
        return new Area($this->app['cache']);
    }

}