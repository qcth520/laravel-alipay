<?php

//查询订单

include 'vendor/autoload.php';

include 'config.php'; //加载配置项

$alipay=new qcth\alipay\index('AlipayExec',$config);


$order_info['out_trade_no']='1552038484';  //商户订单号
//$order_info['trade_no']='2019030822001440500500709992';  //支付宝单号


$data=$alipay->query($order_info);

P($data);