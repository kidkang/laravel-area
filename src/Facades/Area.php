<?php

/**
 * @Author: kidkang
 * @Date:   2021-02-24 10:23:47
 * @Last Modified by:   kidkang
 * @Last Modified time: 2021-02-24 10:25:33
 */
namespace Yjtec\Area\Facades;
use Illuminate\Support\Facades\Facade;
class Area extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'area';
    }
}