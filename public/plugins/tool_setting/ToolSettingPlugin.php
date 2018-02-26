<?php
/**
 * Created by PhpStorm.
 * User: lindd520
 * Date: 2018/2/23
 * Time: 9:41
 */

namespace plugins\tool_setting;

use cmf\lib\Plugin;

class ToolSettingPlugin extends Plugin
{
    public $info = [
        'name'        => 'ToolSetting',
        'title'       => '工具设置',
        'description' => '工具设置',
        'status'      => 1,
        'author'      => '林迪德',
        'version'     => '1.0',
        'demo_url'    => 'http://test.ibuguang.cn/',
        'author_url'  => 'http://test.ibuguang.cn/'
    ];

    public $hasAdmin = 1;//插件是否有后台管理界面

    // 插件安装
    public function install()
    {
        return true;//安装成功返回true，失败false
    }

    // 插件卸载
    public function uninstall()
    {
        return true;//卸载成功返回true，失败false
    }
}