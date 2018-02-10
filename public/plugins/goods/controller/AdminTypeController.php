<?php
/**
 * 功能:
 * User: 林迪德
 * Date: 2018/2/7
 * Time: 14:18
 */
namespace plugins\goods\controller;
use cmf\controller\PluginAdminBaseController;
use plugins\goods\model\GoodsTypeModel;
class AdminTypeController extends PluginAdminBaseController{
    protected static $_instance;
    static public function getInstance() {
        if (is_null ( self::$_instance ) || !isset ( self::$_instance )) {
            self::$_instance = new GoodsTypeModel();
        }
        return self::$_instance;
    }
    /**
     * 类型管理
     * @adminMenu(
     *     'name'   => '类型管理',
     *     'parent' => 'goods/AdminIndex/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '类型列表',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $where=$this->buildWhere(['name:like']);
        $data=self::getInstance()->dataList($where);
        $this->assign('list',$data);
        return $this->fetch();
    }
    /**
     * 添加类型
     * @adminMenu(
     *     'name'   => '添加类型',
     *     'parent' => 'AdminType/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '类型列表',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        if($this->request->isPost()){
            $data=input();
            $validate=$this->validate($data,'Cat');
            if($validate !==true){
                $this->error($validate);
            }
            $result=self::getInstance()->add($data);
            if($result){
                $this->success('添加成功');
            }else{
                $this->error('添加失败');
            }
        }else{
            return $this->fetch();
        }
    }

    /**
     * 修改类型
     * @adminMenu(
     *     'name'   => '修改类型',
     *     'parent' => 'AdminType/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '类型列表',
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
            $data['type_id']=$id;
            $validate=$this->validate($data,'Type');
            if($validate !==true){
                $this->error($validate);
            }
            $result=self::getInstance()->edit($data);
            if($result){
                $this->success('修改成功');
            }else{
                $this->error('修改失败');
            }
        }else{
            $data=self::getInstance()->find($id);
            $this->assign('data',$data);
            return $this->fetch();
        }
    }
    /**
     * 删除类型
     * @adminMenu(
     *     'name'   => '删除类型',
     *     'parent' => 'AdminType/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '类型列表',
     *     'param'  => ''
     * )
     */
    public function delete(){
        $id=$this->request->param('id');
        if(empty($id)){
            $this->error('参数错误');
        }
        // 判断 商品属性
        $count = db("GoodsAttribute")->where("type_id = {$id}")->count("1");
        if($count>0){
            $this->error('该类型下有属性不能删除！');
        }
        $result=self::getInstance()->where('type_id',$id)->delete();
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    /**
     * 类型排序
     * @adminMenu(
     *     'name'   => '类型排序',
     *     'parent' => 'AdminType/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '类型列表',
     *     'param'  => ''
     * )
     */
    public function listOrder(){
        parent::listOrders(self::getInstance());
        $this->success("排序更新成功！", '');
    }
}