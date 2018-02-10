<?php
/**
 * 功能:
 * User: 林迪德
 * Date: 2018/2/7
 * Time: 14:18
 */
namespace plugins\goods\controller;
use cmf\controller\PluginAdminBaseController;
use plugins\goods\model\SuppliersModel;
class AdminSuppliersController extends PluginAdminBaseController{
    protected static $_instance;
    static public function getInstance() {
        if (is_null ( self::$_instance ) || !isset ( self::$_instance )) {
            self::$_instance = new SuppliersModel();
        }
        return self::$_instance;
    }
    /**
     * 供应商管理
     * @adminMenu(
     *     'name'   => '供应商管理',
     *     'parent' => 'goods/AdminIndex/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '供应商列表',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $where=$this->buildWhere(['name:like']);
        $data=self::getInstance()->PageList($where);
        $data->appends($this->request->param());
        $this->assign('list',$data);
        return $this->fetch();
    }
    /**
     * 添加供应商
     * @adminMenu(
     *     'name'   => '添加供应商',
     *     'parent' => 'AdminSuppliers/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '供应商列表',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        if($this->request->isPost()){
            $data=input();
            $validate=$this->validate($data,'Suppliers');
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
     * 修改供应商
     * @adminMenu(
     *     'name'   => '修改供应商',
     *     'parent' => 'AdminSuppliers/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '供应商列表',
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
            $data['suppliers_id']=$id;
            $validate=$this->validate($data,'Suppliers');
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
     * 删除供应商
     * @adminMenu(
     *     'name'   => '删除供应商',
     *     'parent' => 'AdminSuppliers/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '供应商列表',
     *     'param'  => ''
     * )
     */
    public function delete(){
        $id=$this->request->param('id');
        if(empty($id)){
            $this->error('参数错误');
        }
        // 判断 有无商品使用该供应商
        $count = db("goods")->where("suppliers_id = {$id}")->count("1");
        $count > 0 && $this->error('有商品使用该供应商,不得删除!');
        $result=self::getInstance()->where('suppliers_id',$id)->delete();
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    /**
     * 供应商排序
     * @adminMenu(
     *     'name'   => '供应商排序',
     *     'parent' => 'AdminSuppliers/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '供应商列表',
     *     'param'  => ''
     * )
     */
    public function listOrder(){
        parent::listOrders(self::getInstance());
        $this->success("排序更新成功！", '');
    }
}