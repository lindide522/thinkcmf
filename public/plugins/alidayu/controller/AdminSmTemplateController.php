<?php
// +----------------------------------------------------------------------
// | 布光科技 [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.布光科技.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace plugins\alidayu\controller; //Demo插件英文名，改成你的插件英文就行了

use cmf\controller\PluginAdminBaseController;

class AdminSmTemplateController extends PluginAdminBaseController
{
    protected $send_scene;

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $send_scene = array(
            '1'=>array('用户注册','验证码${code}，用户注册新账号, 请勿告诉他人，感谢您的支持!','regis_sms_enable'),
            '2'=>array('用户找回密码','验证码${code}，用于密码找回，如非本人操作，请及时检查账户安全','forget_pwd_sms_enable'),
            '3'=>array('客户下单','您有新订单，收货人：${consignee}，联系方式：${phone}，请您及时查收.','order_add_sms_enable'),
            '4'=>array('客户支付','客户下的单(订单ID:${order_id})已经支付，请及时发货.','order_pay_sms_enable'),
            '5'=>array('商家发货','尊敬的${user_name}用户，您的订单已发货，收货人${consignee}，请您及时查收','order_shipping_sms_enable'),
            '6'=>array('身份验证','尊敬的用户，您的验证码为${code}, 请勿告诉他人.','bind_mobile_sms_enable'),
            '7'=>array('布光服务','尊敬的用服务，您的验证码为${code}, 请勿告诉他人.','bind_mobile_sms_service'),
        );
        $this->send_scene = $send_scene;
        $this->assign('send_scene', $send_scene);
    }
    /**
     * 短信模型列表
     * @adminMenu(
     *     'name'   => '短信模型列表',
     *     'parent' => 'AdminIndex/index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '短信模型列表',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $templates = cmf_get_option('SmTemplates');
        $this->assign('templates',$templates);
        return $this->fetch();
    }
    /**
     * 添加短信模板
     * @adminMenu(
     *     'name'   => '添加短信模板',
     *     'parent' => 'AdminIndex/index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加短信模板',
     *     'param'  => ''
     * )
     */
    public function add()
    {

        $smsTemplate = [];
        $this->assign("smsTpl" , $smsTemplate );
        return $this->fetch();
    }

    /**
     * 添加短信模板提交保存
     * @adminMenu(
     *     'name'   => '添加短信模板提交保存',
     *     'parent' => 'AdminIndex/index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加短信模板提交保存',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        $data = $this->request->param();
        $data['create_time']=$this->request->time();
        $result = $this->validate($data, "SmTemplate");
        if ($result !== true) {
            $this->error($result);
        }
        $data['scene_name']=$this->send_scene[$data['send_scene']][0];
        $SmTemplates=cmf_get_option('SmTemplates');
        $SmTemplates[$data['send_scene']] = $data;
        cmf_set_option('SmTemplates', $SmTemplates,true);
        $this->success('添加成功');
    }

    /**
     * 编辑短信模板
     * @adminMenu(
     *     'name'   => '编辑短信模板',
     *     'parent' => 'AdminIndex/index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑短信模板',
     *     'param'  => ''
     * )
     */
    public function edit()
    {
        $scene = $this->request->param('id');
        $templates = cmf_get_option('SmTemplates');
        if (!empty($templates[$scene])) {
            $this->assign('smsTpl', $templates[$scene]);
        }
        return $this->fetch();
    }

    /**
     * 编辑短信模板提交保存
     * @adminMenu(
     *     'name'   => '编辑短信模板提交保存',
     *     'parent' => 'AdminIndex/index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑短信模板',
     *     'param'  => ''
     * )
     */
    public function editPost()
    {
        $data = $this->request->param();
        $data['create_time']=$this->request->time();
        $result = $this->validate($data, "SmTemplate");

        if ($result !== true) {
            $this->error($result);
        }
        $data['scene_name']=$this->send_scene[$data['send_scene']][0];
        $SmTemplates=cmf_get_option('SmTemplates');
        $SmTemplates[$data['send_scene']] = $data;
        cmf_set_option('SmTemplates', $SmTemplates,true);
        $this->success('修改成功');
    }

    /**
     * 删除短信模板
     * @adminMenu(
     *     'name'   => '删除短信模板',
     *     'parent' => 'AdminIndex/index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '删除短信模板',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        $scene = $this->request->param('id');
        $templates = cmf_get_option('SmTemplates');
        if (!empty($templates[$scene])) {
            unset($templates[$scene]);
        }
        cmf_set_option('SmTemplates', $templates,true);
        $this->success('删除成功！');
    }

}
