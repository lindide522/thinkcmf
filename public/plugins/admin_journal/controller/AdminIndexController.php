<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace plugins\admin_journal\controller; //Demo插件英文名，改成你的插件英文就行了

use cmf\controller\PluginAdminBaseController;


class AdminIndexController extends PluginAdminBaseController
{

    protected function _initialize()
    {
        $adminId = cmf_get_current_admin_id();//获取后台管理员id，可判断是否登录
        if (!empty($adminId)) {
            $this->assign("admin_id", $adminId);
        } else {
            $this->error('未登录');
        }
    }

    /**
     * 操作日志
     * @adminMenu(
     *     'name'   => '操作日志',
     *     'parent' => 'admin/Plugin/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '操作日志',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $data      = $this->request->param();
        $date=isset($data['time']) ? $data['time'] : date('Y-m-d');
        $filename = CMF_ROOT . 'data/sys_log/'.$date.'.log';
        $logs = [];
        $num = 0;
        if(file_exists_case($filename)){
            $content=file_get_contents($filename);
            foreach (explode(PHP_EOL,$content) as $k=>$v){
                if($v){
                    $logs[$k] = json_decode( $v,true);
                }
            }
            $num=count($logs);
        }
        $this->assign("content",array_reverse($logs,true));
        $this->assign('time', $date);
        $this->assign("num",$num);
        return $this->fetch('/admin_index');
    }
}
