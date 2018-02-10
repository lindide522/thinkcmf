<?php
namespace plugins\goods\validate;

use think\Validate;

class AttributeValidate extends Validate
{
    protected $rule = [
        'attr_name'=>'require|unique:goods_attribute',
    ];
    protected $message  =   [
        'attr_name.require'=>'属性名称不能为空',
        'attr_name.unique'=>'该属性已经存在不能重复添加',
    ];
    
}