<?php
/**
 * Created by PhpStorm.
 * User: lindd520
 * Date: 2018/2/7
 * Time: 14:53
 */
namespace plugins\goods\model;
use think\Model;
class SuppliersModel extends Model
{
    /**
     * 供应商列表
     * @param array $where
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function dataList($where=[]){
        $data=$this->where($where)->order('sort asc')->select();
        return $data;
    }
    /**
     * 供应商列表分页
     * @param array $where
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function pageList($where=[]){
        $data=$this->where($where)->order('sort asc')->paginate();
        return $data;
    }
    /**
     * 增加供应商
     * @param array $data
     * @return false|int
     */
    public function add($data=[]){
        $result=$this->save($data);
        return $result;
    }

    /**
     * 修改供应商
     * @param $data
     * @return $this
     */
    public function edit($data){
        $result=$this->update($data);
        return $result;
    }
}