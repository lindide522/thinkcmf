<?php
// +----------------------------------------------------------------------
// | 布光科技 [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.布光科技.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace plugins\alidayu\validate;

use think\Validate;

class SmTemplateValidate extends Validate
{
    protected $rule = [
        // 用|分开
        'sms_sign'     => 'require',
        'sms_tpl_code' => 'require',
        'send_scene' => 'require'
    ];

    protected $message = [
        'sms_sign.require'     => "短信签名不能为空!",
        'sms_tpl_code.require' => '短信模板ID不能为空!',
        'send_scene.require' => '发送场景:不能为空!'
    ];


}