<?php

$config = array (
    //应用ID,您的APPID。
    'app_id' => "",

    //商户私钥
    'merchant_private_key' => "",
    'merchant_private_key_file'=>'',
    //异步通知地址
    'notify_url' => "",

    //同步跳转
    'return_url' => "",

    //编码格式
    'charset' => "UTF-8",

    //签名方式
    'sign_type'=>"RSA2",

    //支付宝网关
    //'gatewayUrl' => "https://openapi.alipay.com/gateway.do", 正式网关
    'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",   //测试网关

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key' => "",
    'alipay_public_key_file'=>'',

    'format'=>'json',  // 支持xml或json格式。
    'alipay_sdk'=>'alipay-sdk-php-20161101'
);