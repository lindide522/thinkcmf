<?php
// +----------------------------------------------------------------------
// | 布光科技 [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.布光科技.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace plugins\alidayu\controller;

use cmf\controller\PluginAdminBaseController;

class AdminIndexController extends PluginAdminBaseController
{

    public function _initialize()
    {
        $adminId = cmf_get_current_admin_id();//获取后台管理员id，可判断是否登录
        if (!empty($adminId)) {
            $this->assign("admin_id", $adminId);
        } else {
            $this->error('未登录');
        }
    }

    /**
     * 阿里大于短信配置
     * @adminMenu(
     *     'name'   => '短信配置',
     *     'parent' => 'admin/Setting/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '阿里短信配置',
     *     'param'  => ''
     * )
     */
    public function index()
    {

        $alidayu_settings = cmf_get_option('alidayu_settings');
        $this->assign('alidayu_settings', $alidayu_settings);
        return $this->fetch('/admin_index');
    }
    /**
     * 阿里大于短信配置
     * @adminMenu(
     *     'name'   => '短信配置修改',
     *     'parent' => 'AdminIndex/index',
     *     'display'=> 0,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '阿里短信配置',
     *     'param'  => ''
     * )
     */
    public function editPost()
    {
        $data=$this->request->param();
        $result = $this->validate($data, "Alidayu");

        if ($result !== true) {
            $this->error($result);
        }
        cmf_set_option('alidayu_settings', $data);
        $this->success('配置成功');
    }
}
