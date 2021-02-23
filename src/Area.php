<?php

/**
 * @Author: kidkang
 * @Date:   2021-02-23 18:22:41
 * @Last Modified by:   kidkang
 * @Last Modified time: 2021-02-23 20:33:08
 */
namespace Yjtec\Area;
use Yjtec\Area\Models\Area as AreaModel;
use Illuminate\Support\Arr;
use Illuminate\Cache\CacheManager;
class Area{

    /**
     * cache driver
     * @var [type]
     */
    protected $cache;
    /**
     * The checked results
     * @var array
     */
    protected $checkedData = [];
    /**
     * set up
     * @param \Illuminate\Cache\CacheManager $cache
     */
    public function __construct(CacheManager $cache){
        $this->cache = $cache;
    }
    /**
     * Get the nested or flat area
     * @param  boolean $tree
     * @return array
     */
    public function all($tree = true){

        $key = 'AREA:ALL' . ($tree ? ':TREE' : '');

        if($this->cache->has($key)){
            $data = $this->cache->get($key);
            return $data ?? null;
        }


        $data = AreaModel::get();
        if($tree){
            $data = $data->toTree();
        }else{
            $data = $data->toFlatTree();
        }

        $data = $data->toArray();

        $this->cache->forever($key,$data);

        return $data;
    }
    /**
     * detemine the given values in area
     * @param  string|array $values
     * @param  string $key
     * @return bool|array
     */
    public function check($values,$key = 'id'){
        $values  = is_array($values)
             ? $values 
             : explode(',', $values);

        $cacheKey = $this->getCacheKey($values);

        if(isset($this->checkedData[$cacheKey])){
            return $this->checkedData[$cacheKey];
        }

        $all = $this->all(false);

        $results = [];

        foreach($values as $value){

            $first = Arr::first($all,function($item) use($key,$value){
                return $item[$key] == $value;
            });

            if(!$first){
                $results = [];
                break;
            }
            $results[] = $first;
        }

        return $this->checkedData[$cacheKey] = count($results) > 0 ? $results : false;
        
    }
    /**
     * get the cacheKey
     * @param  string|array $key
     * @return string
     */
    protected function getCacheKey($key){
        if(is_array($key)){
            return implode(',', $key);
        }
        return $key;
    }
    /**
     * Get the checked data
     * @param  $key
     * @return array|false
     */
    public function checked($key = null){

        if(!$key){
            return $this->checkedData;
        }
        $key = $this->getCacheKey($key);
        
        return $this->checkedData[$key];
    }
}