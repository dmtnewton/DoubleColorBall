<?php
/**
 * 微信公用配置
 *
 * @author newton <yushuainiu@51talk.com>
 * @date 2018/9/3
 **/

return [
    'wx_ball'    => [
        //双色球小程序APPID
        'appid'  => 'wx13259a333db193fe',
        //双色球小程序密钥
        'secret' => 'a5db982534579aad58e69cf2265c3b9b',
    ],
    //微信通用接口地址及参数
    'wx_api_url' => [
        /**
         * 获取TOKEN信息
         *
         * @param string appid      小程序唯一标识
         * @param string secret     小程序的 app secret
         **/
        'get_access_token'   => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',


        /**
         * 获取小程序码
         *
         * @param string path       小程序页面路径
         * @param int    width      二维码宽度
         * @param bool   auto_color 自动配置线条颜色
         * @param object line_color auth_color为false时生效，使用rgb设置颜色 {"r":"xxx","g":"xxx","b":"xxx"}
         * @param bool   is_hyaline 是否需要透明底色
         **/
        'get_miniapp_code'   => 'https://api.weixin.qq.com/wxa/getwxacode?access_token=%s',


        /**
         * 获取小程序二维码
         *
         * @param string scene  关键词字符串
         * @param string page   小程序页面路径
         **/
        'get_miniapp_qrcode' => 'https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=%s',

        
        /**
         * 获取登录校验信息(session-key|unionid)
         *
         * @param string appid      小程序唯一标识
         * @param string secret     小程序的 app secret
         * @param string js_code    登录时获取的 code
         * @param string grant_type 填写为 authorization_code
         **/
        'get_code2session'   => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',

    ]
];