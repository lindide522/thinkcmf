<?php
/**
 * Created by PhpStorm.
 * User: lindd520
 * Date: 2018/2/7
 * Time: 14:53
 */
namespace plugins\goods\model;
use think\Model;
class GoodsModel extends Model
{
    /**
     * 商品列表
     * @param array $where
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function dataList($where=[]){
        $data=$this->where($where)->order('sort asc')->select();
        return $data;
    }
    /**
     * 商品列表分页
     * @param array $where
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function pageList($map=[]){
        $where = [
            'create_time' => ['>=', 0],
            'delete_time' => 0
        ];
        $where=array_merge($where,$map);
        $data=$this->where($where)->order('sort asc')->paginate();
        return $data;
    }
    /**
     * 增加商品
     * @param array $data
     * @return false|int
     */
    public function add($data=[]){
        $result=$this->save($data);
        if($result){
            return $this->goods_id;
        }
        return $result;
    }

    /**
     * 修改商品
     * @param $data
     * @return $this
     */
    public function edit($data){
        $result=$this->update($data);
        return $result;
    }

    /**
     * goods_content 自动转化
     * @param $value
     * @return string
     */
    public function getGoodsContentAttr($value)
    {
        return cmf_replace_content_file_url(htmlspecialchars_decode($value));
    }

    /**
     * goods_content 自动转化
     * @param $value
     * @return string
     */
    public function setGoodsContentAttr($value)
    {
        return htmlspecialchars(cmf_replace_content_file_url(htmlspecialchars_decode($value), true));
    }

    /**
     * 关联模型
     * @return \think\model\relation\HasMany
     */
    public function GoodsImages()
    {
        return $this->hasMany('GoodsImagesModel','goods_id','goods_id');
    }


}