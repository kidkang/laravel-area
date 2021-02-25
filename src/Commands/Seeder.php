<?php

/**
 * @Author: kidkang
 * @Date:   2021-02-23 16:35:06
 * @Last Modified by:   kidkang
 * @Last Modified time: 2021-02-25 11:30:54
 */
namespace Yjtec\Area\Commands;
use Illuminate\Console\Command;
use Yjtec\Area\Models\Area as AreaModel;
class Seeder extends Command{
    protected $signature = 'area:seeder';

    protected $description = 'area seed';

    public function handle()
    {
        if(!AreaModel::where('name','中国')->first()){

            $data =json_decode(file_get_contents(__DIR__.'/../../database/area.json'),true);
            $data = collect($data)->map(function($item){
                return [
                    'id' => $item['area_id'],
                    'name' => $item['area_name'],
                    'pid' => $item['area_parent_id']
                ];
            })->toArray();
            
            $data = self::unlimitedForlayer($data,'children');
            
            $root = [
                'name' => '中国',
                'children' => $data
            ];

            $re = AreaModel::create($root);


            $area = $this->laravel['area'];

            $area->setCache($this->laravel['cache']->store('file'));
            $area->all();
            $area->all(false);
        }



    }

    public static function unlimitedForlayer($cate, $name = 'child', $pid = 0, $level = 1)
    {
        $arr = array();
        foreach ($cate as &$v) {
            if ($v['pid'] == $pid) {
                $v['level'] = $level;
                $v[$name]   = self::unlimitedForlayer($cate, $name, $v['id'], $level + 1);
                if (count($v[$name]) == 0) {
                    unset($v[$name]);
                }
                $arr[] = $v;
            }
        }
        return $arr;
    }
}