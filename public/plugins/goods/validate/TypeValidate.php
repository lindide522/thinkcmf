<?php
namespace plugins\goods\validate;

use think\Validate;

class TypeValidate extends Validate
{
    protected $rule = [
        'name'=>'require|unique:goods_type',
    ];
    protected $message  =   [
        'name.require'=>'类型名称不能为空',
        'name.unique'=>'该类型已经存在不能重复添加',
    ];
    
}