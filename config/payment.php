<?php
return [

    /**
     * 接口请求超时时间
     */
    'timeout' => 5.0,

    /**
     * 支付渠道配置
     */
    'channels' => [

        //微信支付配置
        'wechat' => [
            'app_id' => '',
            'api_key' => '',
            'mch_id' => '',
            'private_key' => '',
            'public_key' => '',

            'timeout' => 5.0
        ],

        //支付宝支付配置
        'alipay' => [

        ],
    ],
];