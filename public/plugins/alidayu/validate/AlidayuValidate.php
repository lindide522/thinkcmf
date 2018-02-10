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

class AlidayuValidate extends Validate
{
    protected $rule = [
        // 用|分开
        'sms_appkey'     => 'require',
        'sms_secretKey' => 'require'
    ];

    protected $message = [
        'sms_appkey.require'     => "appkey不能为空!",
        'sms_secretKey.require' => 'secretKey不能为空!'
    ];


}