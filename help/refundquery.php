<?php

//退款查询
include 'config.php';

include 'vendor/autoload.php';

$alipay=new qcth\alipay\index('AlipayExec',$config);

$order_info['out_trade_no']='1552038484';  //商户订单号
//$order_info['trade_no']='2019030622001440500500709358';  //支付宝单号


$order_info['out_request_no']='123';  //退款时，填的请求号，只有填对了，只能查出正确的信息，否则，只能查能success，具体的退款金额，及其它信息，没有



$data=$alipay->refund_query($order_info);

P($data);


