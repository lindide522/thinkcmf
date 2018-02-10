<?php
namespace plugins\goods\validate;

use think\Validate;

class BrandValidate extends Validate
{
    protected $rule = [
        'name'=>'require|unique:brand',
        'brand_id' =>'require'
    ];
    protected $message  =   [
        'name.require'=>'品牌名称不能为空',
        'name.unique'=>'该品牌已经存在不能重复添加',
        'brand_id.require'  =>'参数不正确'
    ];

    protected $scene = [
        'edit'  =>  ['brand_id','name'],
        'add'   =>['name'],
    ];
}