<?php

//交易关闭， 支付宝未支付的订单，可以取消
include 'config.php';

include 'vendor/autoload.php';

$alipay=new qcth\alipay\index('AlipayExec',$config);

$order_info['out_trade_no']='31551864052';  //商户订单号
//$order_info['trade_no']='2019030622001440500500709359';  //支付宝单号


$data=$alipay->close($order_info);

P($data);


