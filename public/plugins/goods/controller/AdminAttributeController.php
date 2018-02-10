<?php
/**
 * 功能:
 * User: 林迪德
 * Date: 2018/2/7
 * Time: 14:18
 */
namespace plugins\goods\controller;
use cmf\controller\PluginAdminBaseController;
use plugins\goods\model\GoodsAttributeModel;
use plugins\goods\model\GoodsTypeModel;
class AdminAttributeController extends PluginAdminBaseController{
    protected static $_instance;
    static public function getInstance() {
        if (is_null ( self::$_instance ) || !isset ( self::$_instance )) {
            self::$_instance = new GoodsAttributeModel();
        }
        return self::$_instance;
    }
    /**
     * 属性管理
     * @adminMenu(
     *     'name'   => '属性管理',
     *     'parent' => 'goods/AdminIndex/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '属性列表',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $where=$this->buildWhere(['attr_name:like']);
        $data=self::getInstance()->PageList($where);
        $data->appends($this->request->param());
        $this->assign('list',$data);
        $obj=new GoodsTypeModel();
        $typeList=$obj->column('name','type_id');
        $this->assign('type_list',$typeList);
        $attr_input_type = array(0=>'手工录入',1=>' 从列表中选择',2=>' 多行文本框');
        $this->assign('attr_input_type',$attr_input_type);
        return $this->fetch();
    }
    /**
     * 添加属性
     * @adminMenu(
     *     'name'   => '添加属性',
     *     'parent' => 'AdminAttribute/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '属性列表',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        if($this->request->isPost()){
            $data=input();
            $attr_values = str_replace('_', '', $data['attr_values']); // 替换特殊字符
            $attr_values = str_replace('@', '', $attr_values); // 替换特殊字符
            $data['attr_values'] = trim($attr_values);
            $validate=$this->validate($data,'Attribute');
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
            $obj=new GoodsTypeModel();
            $typeList=$obj->dataList();
            $this->assign('type_list',$typeList);
            return $this->fetch();
        }
    }

    /**
     * 修改属性
     * @adminMenu(
     *     'name'   => '修改属性',
     *     'parent' => 'AdminAttribute/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '属性列表',
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
            $attr_values = str_replace('_', '', $data['attr_values']); // 替换特殊字符
            $attr_values = str_replace('@', '', $attr_values); // 替换特殊字符
            $data['attr_values'] = trim($attr_values);
            $data['attr_id']=$id;
            $validate=$this->validate($data,'Attribute');
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
            $obj=new GoodsTypeModel();
            $typeList=$obj->dataList();
            $this->assign('type_list',$typeList);
            return $this->fetch();
        }
    }
    /**
     * 删除属性
     * @adminMenu(
     *     'name'   => '删除属性',
     *     'parent' => 'AdminAttribute/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '属性列表',
     *     'param'  => ''
     * )
     */
    public function delete(){
        $id=$this->request->param('id');
        if(empty($id)){
            $this->error('参数错误');
        }
        // 判断 有无商品使用该属性
        $count = db("GoodsAttr")->where("attr_id = {$id}")->count("1");
        $count > 0 && $this->error('有商品使用该属性,不得删除!');
        $result=self::getInstance()->where('attr_id',$id)->delete();
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    /**
     * 属性排序
     * @adminMenu(
     *     'name'   => '属性排序',
     *     'parent' => 'AdminAttribute/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '属性列表',
     *     'param'  => ''
     * )
     */
    public function listOrder(){
        parent::listOrders(self::getInstance());
        $this->success("排序更新成功！", '');
    }
}