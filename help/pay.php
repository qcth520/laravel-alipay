<?php


//支付

include 'config_bak.php';


include 'vendor/autoload.php';

$alipay=new qcth\alipay\index('Alipay',$config);


$order_info['out_trade_no']=time();
//$order_info['out_trade_no']='31551864052';
$order_info['subject']='测试单';
$order_info['total_amount']=0.02;
$order_info['body']='商品描述';
//$order_info['timeout_express']='60m'; //可选参数; 超时时间,该笔订单允许的最晚付款时间，逾期将关闭交易。取值范围：1m～15d。m-分钟，h-小时，d-天，1c-当天（1c-当天的情况下，无论交易何时创建，都在0点关闭）。 该参数数值不接受小数点， 如 1.5h，可转换为 90m。

$data=$alipay->pay($order_info,'pc','post');  //pc 或 mobile 代表是PC支付还是手机支付

P($data);