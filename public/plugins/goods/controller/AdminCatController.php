<?php
/**
 * 功能:
 * User: 林迪德
 * Date: 2018/2/7
 * Time: 14:18
 */
namespace plugins\goods\controller;
use cmf\controller\PluginAdminBaseController;
use plugins\goods\model\GoodsCategoryModel;
class AdminCatController extends PluginAdminBaseController{
    protected static $_instance;
    static public function getInstance() {
        if (is_null ( self::$_instance ) || !isset ( self::$_instance )) {
            self::$_instance = new GoodsCategoryModel();
        }
        return self::$_instance;
    }
    /**
     * 类别管理
     * @adminMenu(
     *     'name'   => '类别管理',
     *     'parent' => 'goods/AdminIndex/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '类别列表',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $where=$this->buildWhere(['name:like']);
        $data=self::getInstance()->getCatTree($where);
        $this->assign('list',$data);
        return $this->fetch();
    }
    /**
     * 添加类别
     * @adminMenu(
     *     'name'   => '添加类别',
     *     'parent' => 'AdminCat/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '类别列表',
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
            $id=$this->request->param('id');
            $this->assign('id',$id);
            $cat=self::getInstance()->getCatTree();
            $this->assign('cat',$cat);
            return $this->fetch();
        }
    }

    /**
     * 修改类别
     * @adminMenu(
     *     'name'   => '修改类别',
     *     'parent' => 'AdminCat/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '类别列表',
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
            $data['cat_id']=$id;
            $validate=$this->validate($data,'Cat');
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
            $cat=self::getInstance()->getCatTree();
            $this->assign('cat',$cat);
            $data=self::getInstance()->find($id);
            $this->assign('data',$data);
            return $this->fetch();
        }
    }
    /**
     * 删除类别
     * @adminMenu(
     *     'name'   => '删除类别',
     *     'parent' => 'AdminCat/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '类别列表',
     *     'param'  => ''
     * )
     */
    public function delete(){
        $id=$this->request->param('id');
        if(empty($id)){
            $this->error('参数错误');
        }
        $result=self::getInstance()->where('cat_id',$id)->delete();
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    /**
     * 类别排序
     * @adminMenu(
     *     'name'   => '类别排序',
     *     'parent' => 'AdminCat/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '类别列表',
     *     'param'  => ''
     * )
     */
    public function listOrder(){
        parent::listOrders(self::getInstance());
        $this->success("排序更新成功！", '');
    }
}