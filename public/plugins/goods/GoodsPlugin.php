<?php
// +----------------------------------------------------------------------
// | 布光科技 [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.布光科技.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace plugins\goods;

use cmf\lib\Plugin;
use Qiniu\Auth;


class GoodsPlugin extends Plugin
{

    public $info = [
        'name'        => 'Goods',
        'title'       => '商城管理',
        'description' => '商城管理模块',
        'status'      => 1,
        'author'      => '布光科技',
        'version'     => '1.0'
    ];

    public $hasAdmin = 0;//插件是否有后台管理界面

    // 插件安装
    public function install()
    {
        $data=['parent_id'=>0,'type'=>0,'status'=>1,'list_order'=>30,'app'=>'goods','controller'=>'AdminIndex','action'=>'default','name'=>'商品管理','icon'=>'cubes'];
        $res=db('admin_menu')->insert($data);
        return $res;//安装成功返回true，失败false
    }

    // 插件卸载
    public function uninstall()
    {
        $where['app']='goods';
        $where['controller']='AdminIndex';
        $where['action']='default';
        $res=db('admin_menu')->where($where)->delete();
        return $res;//卸载成功返回true，失败false
    }

}