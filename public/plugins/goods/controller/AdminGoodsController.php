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
use plugins\goods\model\GoodsModel;
use plugins\goods\model\GoodsTypeModel;
use plugins\goods\model\GoodsCategoryModel;
use plugins\goods\model\SuppliersModel;
use plugins\goods\model\BrandModel;
use think\Db;
class AdminGoodsController extends PluginAdminBaseController{
    protected static $_instance;
    static public function getInstance() {
        if (is_null ( self::$_instance ) || !isset ( self::$_instance )) {
            self::$_instance = new GoodsModel();
        }
        return self::$_instance;
    }
    /**
     * 商品管理
     * @adminMenu(
     *     'name'   => '商品管理',
     *     'parent' => 'goods/AdminIndex/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '商品列表',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $where=$this->buildWhere(['cat_id','brand_id','suppliers_id','goods_name:like']);
        $data=self::getInstance()->PageList($where);
        $data->appends($this->request->param());
        $this->assign('list',$data);
        $this->otherGoods();
        return $this->fetch();
    }
    /**
     * 添加商品
     * @adminMenu(
     *     'name'   => '添加商品',
     *     'parent' => 'AdminGoods/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '商品列表',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        if($this->request->isPost()){
            $data=input();
            $goods_img_file=[];//相册和附件
            if (!empty($data['photo_names']) && !empty($data['photo_urls'])) {
                foreach ($data['photo_urls'] as $key => $url) {
                    $photoUrl = cmf_asset_relative_url($url);
                    array_push($goods_img_file, ["url" => $photoUrl, "name" => $data['photo_names'][$key],'type'=>1]);
                }
            }

            if (!empty($data['file_names']) && !empty($data['file_urls'])) {
                foreach ($data['file_urls'] as $key => $url) {
                    $fileUrl = cmf_asset_relative_url($url);
                    array_push($goods_img_file, ["url" => $fileUrl, "name" => $data['file_names'][$key],'type'=>2]);
                }
            }
            $validate=$this->validate($data,'Goods');
            if($validate !==true){
                $this->error($validate);
            }
            Db::startTrans();
            try{
                $good_id=self::getInstance()->add($data);
                //新增相册
                !empty($goods_img_file) && self::getInstance()->GoodsImages()->saveAll($goods_img_file);
                $this->addGoodsAttr($data,$good_id);
                // 提交事务
                Db::commit();
                $result=true;
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                $result=false;
            }
            $result?$this->success('添加成功'):$this->error('添加失败:');

        }else{
            $this->otherGoods();
            return $this->fetch();
        }
    }
    /**
     * 商品额外属性加载
     */
    protected function otherGoods(){
        $cat=new GoodsCategoryModel();
        $catTree=$cat->getCatTree();
        $this->assign('catTree',$catTree);
        $brandObj=new BrandModel();
        $brand=$brandObj->dataList();
        $this->assign('brand',$brand);
        $supplierObj=new SuppliersModel();
        $supplier=$supplierObj->dataList();
        $typeObj=new GoodsTypeModel();
        $type=$typeObj->dataList();
        $this->assign('type',$type);
        $this->assign('suppliers',$supplier);
    }

    /**
     * 添加商品属性
     * @param $data
     * @param $good_id
     * @return bool
     */
    protected function addGoodsAttr($data,$good_id){
        if(isset($data['attr'])){
            $attr=$data['attr'];
            $good_attr_data_all=[];
            foreach ($attr as $attr_id=>$value) {
                $good_attr_data['attr_id']=$attr_id;
                $good_attr_data['attr_value']=$value;
                $good_attr_data['goods_id']=$good_id;
                array_push($good_attr_data_all,$good_attr_data);
            }
            db('goods_attr')->insertAll($good_attr_data_all);
        }
        return true;
    }
    /**
     * 获取商品属性
     * @param string $type_id
     */
    public function ajaxGoodAttribute($type_id='',$goods_id=''){
        if(empty($type_id)){
            $this->error('参数错误');
        }
        $good_attribute_obj=new GoodsAttributeModel();
        $good_attribute=$good_attribute_obj->where('type_id',$type_id)->order('sort')->select();
        if(!empty($good_attribute)){
            $str='';
            $goodAttrVal=$this->getGoodsAttrVal(null,$goods_id);
            foreach ($good_attribute as $k=>$v){
                //判断商品是否有属性值，有则加载属性值，否则使用默认属性值
                if(isset($goodAttrVal[$v['attr_id']])){
                    $goodsAttr=$goodAttrVal[$v['attr_id']];
                }else{
                    $goodsAttr=$v['attr_values'];
                }
                switch ($v['attr_input_type']){
                    case 0:
                        $str.="<div class=\"form-group\">
                            <label class=\"col-sm-2 control-label\">{$v['attr_name']}</label>
                            <div class=\"col-md-6 col-sm-10\">
                                <input type=\"text\" class=\"form-control\" name=\"attr[{$v['attr_id']}]\" value=\"{$goodsAttr}\" >
                            </div>
                            </div>";
                        break;
                    case 1:
                        $tmp_option_val = explode(PHP_EOL, $v['attr_values']);
                        $str_option='';
                        foreach ($tmp_option_val as $v2){
                            if($v2 === $goodsAttr){
                                $selected="selected";
                            }else{
                                $selected='';
                            }
                            $str_option.="<option value=\"{$v2}\" $selected>{$v2}</option>";
                        }
                        $str.="<div class=\"form-group\">
                        <label class=\"col-sm-2 control-label\">{$v['attr_name']}</label>
                        <div class=\"col-md-6 col-sm-10\">
                            <select name=\"attr[{$v['attr_id']}]\" class=\"form-control\">
                                <option value=\"\">请选择</option>
                                {$str_option}
                            </select>
                        </div>
                    </div>";
                        break;
                    case 2:
                        $str.="<div class=\"form-group\">
                        <label class=\"col-sm-2 control-label\">{$v['attr_name']}</label>
                        <div class=\"col-md-6 col-sm-10\">
                            <textarea name=\"attr[{$v['attr_id']}]\"  class=\"form-control\">{$goodsAttr}</textarea>
                        </div>
                        </div>";
                        break;
                    default:
                        break;
                };
            }
            echo $str;
        }
    }
    /**
     * 获取 tp_goods_attr 表中指定 goods_id  指定 attr_id  或者 指定 goods_attr_id 的值 可是字符串 可是数组
     * @param int $goods_attr_id tp_goods_attr表id
     * @param int $goods_id 商品id
     * @param int $attr_id 商品属性id
     * @return array 返回数组
     */
    public function getGoodsAttrVal($goods_attr_id = 0 ,$goods_id = 0, $attr_id = 0)
    {
        $GoodsAttr = db('goods_attr');
        if($goods_attr_id > 0)
            return $GoodsAttr->where("goods_attr_id = $goods_attr_id")->select();
        if($goods_id > 0)
            return $GoodsAttr->where("goods_id = $goods_id")->column('attr_value','attr_id');
    }
    /**
     * 修改商品
     * @adminMenu(
     *     'name'   => '修改商品',
     *     'parent' => 'AdminGoods/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '商品列表',
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
            $data['goods_id']=$id;
            $goods_img_file=[];//相册和附件
            if (!empty($data['photo_names']) && !empty($data['photo_urls'])) {
                foreach ($data['photo_urls'] as $key => $url) {
                    $photoUrl = cmf_asset_relative_url($url);
                    array_push($goods_img_file, ["url" => $photoUrl, "name" => $data['photo_names'][$key],'type'=>1]);
                }
            }

            if (!empty($data['file_names']) && !empty($data['file_urls'])) {
                foreach ($data['file_urls'] as $key => $url) {
                    $fileUrl = cmf_asset_relative_url($url);
                    array_push($goods_img_file, ["url" => $fileUrl, "name" => $data['file_names'][$key],'type'=>2]);
                }
            }
            $validate=$this->validate($data,'Goods');
            if($validate !==true){
                $this->error($validate);
            }

            Db::startTrans();
            try{
                $find=self::getInstance()->find($id);
                if(empty($find)){
                    $this->error('商品不存在');
                }
                $find->GoodsImages()->delete();
                self::getInstance()->edit($data);
                //新增相册
                $find->GoodsImages()->saveAll($goods_img_file);
                db('goods_attr')->where('goods_id',$id)->delete();
                $this->addGoodsAttr($data,$id);
                // 提交事务
                Db::commit();
                $result=true;
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                $result=false;
            }
            $result?$this->success('修改成功'):$this->error('修改失败:');
        }else{
            $data=self::getInstance()->find($id);
            $goods_image=db('goods_images')->where('goods_id',$id)->select();
            $this->assign('goods_image',$goods_image);
            $this->assign('data',$data);
            $this->otherGoods();
            return $this->fetch();
        }
    }
    /**
     * 删除商品
     * @adminMenu(
     *     'name'   => '删除商品',
     *     'parent' => 'AdminGoods/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '商品列表',
     *     'param'  => ''
     * )
     */
    public function delete(){
        $param           = $this->request->param();
        $goodsModel = self::getInstance();
        $delete_time=request()->time();
        if (isset($param['id'])) {
            $id           = $this->request->param('id', 0, 'intval');
            $result       = $goodsModel->where(['goods_id' => $id])->find();
            $data         = [
                'object_id'   => $result['goods_id'],
                'create_time' => $delete_time,
                'table_name'  => 'goods',
                'name'        => $result['goods_name'],
                'user_id'=>cmf_get_current_admin_id()
            ];
            $resultPortal = $goodsModel
                ->where(['goods_id' => $id])
                ->update(['delete_time' => $delete_time]);
            if ($resultPortal) {
                Db::name('recycleBin')->insert($data);
            }
            $this->success("删除成功！", '');

        }

        //批量删除
        if (isset($param['ids'])) {
            $ids     = $this->request->param('ids/a');
            $recycle = $goodsModel->where(['goods_id' => ['in', $ids]])->select();
            $result  = $goodsModel->where(['goods_id' => ['in', $ids]])->update(['delete_time' => time()]);
            if ($result) {
                foreach ($recycle as $value) {
                    $data = [
                        'object_id'   => $value['goods_id'],
                        'create_time' => $delete_time,
                        'table_name'  => 'goods',
                        'name'        => $value['goods_name'],
                        'user_id'=>cmf_get_current_admin_id()
                    ];
                    Db::name('recycleBin')->insert($data);
                }
                $this->success("删除成功！", '');
            }
        }
    }
    /**
     * 商品排序
     * @adminMenu(
     *     'name'   => '商品排序',
     *     'parent' => 'AdminGoods/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '商品列表',
     *     'param'  => ''
     * )
     */
    public function listOrder(){
        parent::listOrders(self::getInstance());
        $this->success("排序更新成功！", '');
    }

    /**
     * 商品上下架
     * @adminMenu(
     *     'name'   => '商品上下架',
     *     'parent' => 'AdminGoods/index',
     *     'display'=> false,
     *     'hasView'=> 1,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '商品列表',
     *     'param'  => ''
     * )
     */
    public function publish(){
        $data=input();
        if(!isset($data['yes']) || !isset($data['ids'])){
            $this->error('参数错误');
        }

        foreach ($data['ids'] as $id) {
            $saveData=['goods_id'=>$id,'is_on_sale'=>$data['yes']];
            self::getInstance()->edit($saveData);
        }
        $this->success('操作成功');
    }

    /**
     * 商品选择框
     * @return mixed
     */
    public function select()
    {
        $ids                 = $this->request->param('ids');
        $selectedIds         = explode(',', $ids);
        $portalCategoryModel = self::getInstance();
        $where=$this->buildWhere(['cat_id','brand_id','suppliers_id','goods_name:like']);
        $goodsList = $portalCategoryModel->pageList($where);
        $this->otherGoods();
        $this->assign('selectedIds', $selectedIds);
        $this->assign('goodsList', $goodsList);
        return $this->fetch();
    }
}