<?php

/**
 * @Author: kidkang
 * @Date:   2021-02-23 18:23:32
 * @Last Modified by:   kidkang
 * @Last Modified time: 2021-02-25 14:00:21
 */
namespace Yjtec\Area\Test;
use Yjtec\Area\Area;
use Yjtec\Area\Models\Area as AreaModel;
class AreaTest extends TestCase{

    public function setUp():void{
        parent::setUp();
        $cache = $this->app['cache']->store('file');

        $cache->forget('AREA:ALL:TREE:1');
        $cache->forget('AREA:ALL:1');

        AreaModel::create([
            'name' => '中国',
            'children' => [
                [
                    'name' => '河南',
                    'children' =>[
                        ['name' => '郑州',
                            'children' => [
                                ['name' => '二七区'],
                                ['name' => '惠济区']
                            ]
                        ]
                    ]
                ],
                [
                    'name' => '辽宁',
                    'children' =>[
                        ['name' => '沈阳',
                                'children' => [
                                ['name' => '和平区'],
                                ['name' => '铁西区']
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }
    public function testAll(){
        $area = $this->getArea();
        $data  = $area->all();
        $this->assertEquals('河南',$data[0]['name']);
    }

    public function testAllWithParent(){
        $area = $this->getArea();
        $data = $area->all(true,1);
        $this->assertEquals('河南',$data[0]['name']);
    }

    public function testAllFlat(){
        $area = $this->getArea();
        $data = $area->all(false);
        $this->assertCount(8,$data);
    }

    public function testCheckWithString(){
        $area = $this->getArea();
        $this->assertNotFalse($area->check('2'));
        $this->assertFalse($area->check('not exists'));
        $this->assertNotFalse($area->check('2,3'));
        $this->assertFalse($area->check('2,not exists'));
    }

    public function testCheckWithArray(){
        $area = $this->getArea();
        $this->assertNotFalse($area->check([2]));
        $this->assertNotFalse($area->check(['2']));
        $this->assertFalse($area->check(['not exists']));
        $this->assertNotFalse($area->check([2,3]));
        $this->assertFalse($area->check([2,10000]));
    }

    public function testCheckTimes(){
        $area = $this->getArea();
        $this->assertNotFalse($area->check(['2']));
        $this->assertArrayHasKey('2',$area->checked());
    }

    public function testChecked(){
        $area = $this->getArea();
        $area->check(2);
        $this->assertCount(1,$area->checked(2));
        
        $key = '2,3';
        $area->check($key);
        $this->assertCount(2,$area->checked($key));

        $key =[2,3];
        $area->check($key);
        $this->assertCount(2,$area->checked($key));

        $key = [3,100];
        $area->check($key);
        $this->assertFalse($area->checked($key));
    }

    public function testSetCache(){
        $area = $this->getArea();
        $cache = $this->app['cache']->store('file');
        $area->setCache($cache);
        $this->assertEquals($cache,$area->getCache());

        $area->all();
        $this->assertTrue($cache->has('AREA:ALL:TREE:1'));
        $area->all(false);
        $this->assertTrue($cache->has('AREA:ALL:1'));
    }
    public function testAreaEnvTest(){
        $area = $this->app['area'];
        $cache = $this->app['cache']->store('file');
        $area->all();
        $this->assertTrue($cache->has('AREA:ALL:TREE:1'));
        $area->all(false);
        $this->assertTrue($cache->has('AREA:ALL:1'));
    }

    public function testRandom(){
        $area = $this->getArea();
        $re = $area->random();
        $this->assertNotNull($re);
    }

    public function getArea(){
        return new Area($this->app['cache']);
    }

}