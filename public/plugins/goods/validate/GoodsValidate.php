<?php
namespace plugins\goods\validate;

use think\Validate;

class GoodsValidate extends Validate
{
    protected $rule = [
        'goods_name'=>'require|unique:goods',
    ];
    protected $message  =   [
        'goods_name.require'=>'商品名称不能为空',
        'goods_name.unique'=>'该商品已经存在不能重复添加',
    ];
}