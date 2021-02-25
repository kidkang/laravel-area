<?php

/**
 * @Author: kidkang
 * @Date:   2021-02-23 18:22:41
 * @Last Modified by:   kidkang
 * @Last Modified time: 2021-02-25 11:26:37
 */
namespace Yjtec\Area;
use Yjtec\Area\Models\Area as AreaModel;
use Illuminate\Support\Arr;
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
     * @param  $cache
     */
    public function __construct($cache){
        $this->cache = $cache;
    }

    /**
     * Set cache driver
     * @param [type] $cache [description]
     */
    public function setCache($cache){
        $this->cache = $cache;
    }

    public function getCache(){
        return $this->cache;
    }

    /**
     * Get the nested or flat area
     * @param  boolean $tree
     * @param int $parent
     * @return array
     */
    public function all($tree = true,$parent = null){
        $parent = $parent ? $parent : config('area.default_parent_id',null);
        
        $key = 'AREA:ALL' 
            . ($tree ? ':TREE' : '')
            . ($parent ? ':'.$parent : '');
        if($this->cache->has($key)){
            $data = $this->cache->get($key);
            return $data ?? null;
        }

        $data = $this->getDatabaseData($parent);

        if($tree){

            $data = $this->getTreeData($data);

        }else{
            $data = $data->toFlatTree();
        }

        $data = $data->toArray();

        $this->cache->forever($key,$data);

        return $data;
    }

    protected function getTreeData($data){
        $data = $data->toTree();

        return $data;
    }

    protected function getDatabaseData($parent = null){
        $data = AreaModel::withDepth();
        if($parent){
            return $data->descendantsOf($parent);
        }else{
            return $data->get();
        }
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