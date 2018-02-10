<?php
// +----------------------------------------------------------------------
// | 布光科技 [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.布光科技.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace plugins\alidayu;//Demo插件英文名，改成你的插件英文就行了
use cmf\lib\Plugin;
use plugins\alidayu\model\SmsModel;
//Demo插件英文名，改成你的插件英文就行了
class AlidayuPlugin extends Plugin
{

    public $info = [
        'name'        => 'Alidayu',//Demo插件英文名，改成你的插件英文就行了
        'title'       => '阿里短信',
        'description' => '阿里短信管理工具',
        'status'      => 1,
        'author'      => '布光科技',
        'version'     => '1.0',
        'demo_url'    => '#',
        'author_url'  => 'http://www.ibuguang.cn'
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
    //实现的send_mobile_verification_code钩子方法
    public function sendMobileVerificationCode($param)
    {
        $mobile        = $param['mobile'];//手机号
        $code          = $param['code'];//验证码
        $scene=isset($param['scene'])?$param['scene']:7;
        $templates = cmf_get_option('SmTemplates');
        $sms_tpl=$templates[$scene];
        $config = cmf_get_option('alidayu_settings');
        $expire_time   = time() + intval($config['sms_time_out']);
        $data=['code'=>$code,'create_time'=>request()->time(),'phone'=>$mobile,'sign_name'=>$sms_tpl['sms_sign'],'template_code'=>$sms_tpl['sms_tpl_code'],'product'=>$sms_tpl['scene_name']];
        try{
            $res=SmsModel::sendSms($data);
            //判断是否发送正常
            if($res->Code!=="OK"){
                $result = [
                    'error'     => 1,
                    'message'=>$res->Message
                ];
            }else{
                $result = [
                    'error'     => 0,
                    'expire_time'=>$expire_time
                ];
            }
        } catch (\Exception $e) {
            $msg='错误代码：'.$e->getMessage();
            $result = [
                'error'     => 1,
                'message' =>$msg
            ];
        }
        return $result;
    }

}