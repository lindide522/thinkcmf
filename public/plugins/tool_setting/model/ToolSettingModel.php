<?php
/**
 * Created by PhpStorm.
 * User: lindd520
 * Date: 2018/2/23
 * Time: 10:09
 */
namespace plugins\tool_setting\model;
use think\Model;
class ToolSettingModel extends Model{
    protected $type=['more'=>'array'];
    public  function dataList($where=[])
    {
        $data=$this->where($where)->order('create_time')->select();
        return $data;
    }

    public function add($data){
        $result=$this->save($data);
        return $result;
    }

    public function edit($data){
        $result=$this->update($data);
        return $result;
    }
}
