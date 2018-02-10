<?php
namespace plugins\goods\validate;

use think\Validate;

class CatValidate extends Validate
{
    protected $rule = [
        'name'=>'require|unique:goods_category',
    ];
    protected $message  =   [
        'name.require'=>'类别名称不能为空',
        'name.unique'=>'该类别已经存在不能重复添加',
    ];
    
}