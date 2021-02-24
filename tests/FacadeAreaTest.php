<?php

/**
 * @Author: kidkang
 * @Date:   2021-02-24 10:31:20
 * @Last Modified by:   kidkang
 * @Last Modified time: 2021-02-24 10:32:53
 */
namespace Yjtec\Area\Test;
use Yjtec\Area\Facades\Area;
class FacadeAreaTest extends TestCase{
    public function testNormal(){
        $this->assertNotNull(Area::all());
    }
}