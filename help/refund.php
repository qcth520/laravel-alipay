<?php

//退款，可以全额退，也可以部分退。每次的退款码不一样，代表 新的一次退款。

include 'config.php';

include 'vendor/autoload.php';

$alipay=new qcth\alipay\index('AlipayExec',$config);

$order_info['out_trade_no']='1552038484';  //商户订单号
//$order_info['trade_no']='2019030622001440500500709358';  //支付宝单号
$order_info['refund_amount']=0.01;  //退款金额
//如果退款中，遇到网络错误，可以用上次的退款号，重新发起退款请求,一个退款号，视为一次退款请求
$order_info['out_request_no']='123';  //如果多次退款，需要有多个退款号，如果还用上次的退款号，只是重新发起上次的请求,如全额退款，可以不填
$order_info['refund_reason']='退款原因';  //退款原因


$data=$alipay->refund($order_info);

P($data);