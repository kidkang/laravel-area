<?php

/**
 * @Author: kidkang
 * @Date:   2021-02-23 17:14:52
 * @Last Modified by:   kidkang
 * @Last Modified time: 2021-02-23 17:18:54
 */
namespace Yjtec\Area\Models;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
class Area extends Model {
    use NodeTrait;
    protected $guarded = ['id'];

    public function getTable(){
        return config('area.table_names.area',parent::getTable());
    }
}