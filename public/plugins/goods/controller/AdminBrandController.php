<?php
/**
 * 功能:
 * User: 林迪德
 * Date: 2018/2/7
 * Time: 14:18
 */
namespace plugins\goods\controller;
use cmf\controller\PluginAdminBaseController;
use plugins\goods\model\BrandModel;
class AdminBrandController extends PluginAdminBaseController{
    protected static $_instance;
    static public function getInstance() {
        if (is_null ( self::$_instance ) || !isset ( self::$_instance )) {
            self::$_instance = new BrandModel();
        }
        return self::$_instance;
    }
    /**
     * 品牌管理
     * @adminMenu(
     *     'name'   => '品牌管理',
     *     'parent' => 'goods/AdminIndex/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '品牌列表',
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
     * 添加品牌
     * @adminMenu(
     *     'name'   => '添加品牌',
     *     'parent' => 'AdminBrand/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '品牌列表',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        if($this->request->isPost()){
            $data=input();
            $validate=$this->validate($data,'Brand.add');
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
     * 修改品牌
     * @adminMenu(
     *     'name'   => '修改品牌',
     *     'parent' => 'AdminBrand/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '品牌列表',
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
            $data['brand_id']=$id;
            $validate=$this->validate($data,'Brand.edit');
            if($validate !==true){
                $this->error($validate);
            }
            $result=self::getInstance()->edit($data,$id);
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
     * 删除品牌
     * @adminMenu(
     *     'name'   => '删除品牌',
     *     'parent' => 'AdminBrand/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '品牌列表',
     *     'param'  => ''
     * )
     */
    public function delete(){
        $id=$this->request->param('id');
        if(empty($id)){
            $this->error('参数错误');
        }
        $result=self::getInstance()->where('brand_id',$id)->delete();
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    /**
     * 品牌排序
     * @adminMenu(
     *     'name'   => '品牌排序',
     *     'parent' => 'AdminBrand/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '品牌列表',
     *     'param'  => ''
     * )
     */
    public function order(){
        parent::listOrders(self::getInstance());
        $this->success("排序更新成功！", '');
    }
}