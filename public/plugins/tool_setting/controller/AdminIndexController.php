<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace plugins\tool_setting\controller; //Demo插件英文名，改成你的插件英文就行了

use cmf\controller\PluginAdminBaseController;
use plugins\tool_setting\model\ToolSettingModel;
use plugins\goods\model\GoodsCategoryModel;
class AdminIndexController extends PluginAdminBaseController
{
    protected static $_instance;
    static public function getInstance() {
        if (is_null ( self::$_instance ) || !isset ( self::$_instance )) {
            self::$_instance = new ToolSettingModel();
        }
        return self::$_instance;
    }

    /**
     * 工具方案配置
     * @adminMenu(
     *     'name'   => '方案配置',
     *     'parent' => 'admin/Setting/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '工具方案配置',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $where=$this->buildWhere(['cat_id','name:like']);
        $data=self::getInstance()->dataList($where);
        $this->assign('list',$data);
        $cat=new GoodsCategoryModel();
        $catTree=$cat->getCatTree();
        $this->assign('catTree',$catTree);
        return $this->fetch();
    }

    /**
     * 添加方案
     * @adminMenu(
     *     'name'   => '添加方案',
     *     'parent' => 'AdminIndex/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '方案列表',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        if($this->request->isPost()){
            $data=input();
            if(isset($data['check_formula'])){
                $ids=array_keys($data['check_formula']);
                $this->error('商品ID【'.implode(',',$ids).'】未设置公式');
            }
            $result=self::getInstance()->add($data);
            if($result){
                $this->success('添加成功');
            }else{
                $this->error('添加失败');
            }
        }else{
            $cat=new GoodsCategoryModel();
            $catTree=$cat->getCatTree();
            $this->assign('catTree',$catTree);
            return $this->fetch();
        }
    }

    /**
     * 修改方案
     * @adminMenu(
     *     'name'   => '修改方案',
     *     'parent' => 'AdminIndex/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '方案列表',
     *     'param'  => ''
     * )
     */
    public function edit(){
        $id=$this->request->param('id');
        if(empty($id)){
            $this->error('参数错误');
        }
        if($this->request->isPost()){
            $data=input();
            $result=self::getInstance()->edit($data);
            if($result){
                $this->success('修改成功');
            }else{
                $this->error('修改失败');
            }
        }else{
            $data=self::getInstance()->find($id);
            if(!empty($data)){
                $goods_ids=array_keys($data['more']);
                $where['goods_id']=['in',$goods_ids];
                $goods_list=db('goods')->where($where)->order('sort')->select();
                $this->assign('goods_list',$goods_list);
            }
            $this->assign('data',$data);
            $cat=new GoodsCategoryModel();
            $catTree=$cat->getCatTree();
            $this->assign('catTree',$catTree);
            return $this->fetch();
        }
    }
    /**
     * 删除方案
     * @adminMenu(
     *     'name'   => '删除方案',
     *     'parent' => 'AdminIndex/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '方案列表',
     *     'param'  => ''
     * )
     */
    public function delete(){
        $id=$this->request->param('id');
        if(empty($id)){
            $this->error('参数错误');
        }
        $result=self::getInstance()->where('id',$id)->delete();
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }

    /**
     * 公式编辑
     * @return mixed
     */
    public function formula_edit(){
        return $this->fetch();
    }
}
