<?php
namespace plugins\goods\validate;

use think\Validate;

class SuppliersValidate extends Validate
{
    protected $rule = [
        'name'=>'require|unique:suppliers',
    ];
    protected $message  =   [
        'name.require'=>'供应商名称不能为空',
        'name.unique'=>'该供应商已经存在不能重复添加',
    ];
    
}