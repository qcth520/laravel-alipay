<?php

namespace qcth\alipay\plug;

use qcth\alipay\plug_trait\ArrayToStringTrait;
use qcth\alipay\plug_trait\BuildParamsTrait;
use qcth\alipay\plug_trait\CurlTrait;
use qcth\alipay\plug_trait\SignVerifyTrait;

//支付宝交易查询
class Exe{
    use BuildParamsTrait,CurlTrait,ArrayToStringTrait,SignVerifyTrait;

    //配置数组
    public $config;
    //订单信息
    public $order_info;

    //sdk名称,固定的,构建参数用
    public $build_params_method='';
    //curl请求结果数组key
    public $sdk_response='';

    //构建参数，biz_content 订单信息转json
    public $apiParams;


    //查询
    public function query($order_info,$config){
        //sdk赋值
        $this->build_params_method='alipay.trade.query';
        $this->sdk_response='alipay_trade_query_response';

        return $this->common($order_info,$config);
    }
    //退款
    public function refund($order_info,$config){
        //sdk赋值
        $this->build_params_method='alipay.trade.refund';
        $this->sdk_response='alipay_trade_refund_response';

        return $this->common($order_info,$config);
    }
    //退款查询
    public function refund_query($order_info,$config){
        //sdk赋值
        $this->build_params_method='alipay.trade.fastpay.refund.query';
        $this->sdk_response='alipay_trade_fastpay_refund_query_response';

        return $this->common($order_info,$config);
    }
    //关闭交易
    public function close($order_info,$config){
        //sdk赋值
        $this->build_params_method='alipay.trade.close';
        $this->sdk_response='alipay_trade_close_response';

        return $this->common($order_info,$config);
    }
    //公共方法
    private function common($order_info,$config){
        if(empty($order_info)){
            die('查询的订单，至少有一项');
        }
        //赋值给全局属性
        $this->order_info=$order_info;

        //配置项不能为空
        if(empty($config)){
            return '支付宝配置数组不能为空';
        }
        //配置项，赋值全局属性
        $this->config=$config;

        //组装系统参数
        $sysParams=$this->build_params();

        //构建url请求地址
        $requestUrl = $this->config['gatewayUrl'] . "?";
        foreach ($sysParams as $sysParamKey => $sysParamValue) {
            $requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
        }
        $requestUrl = substr($requestUrl, 0, -1);  //去除最右边的 &

        //请求结果
        $resp = $this->curl($requestUrl, $this->apiParams); //curl请求资源


        if($this->config['format']=='json'){
            $data=json_decode($resp,true); //json转成数组
        }elseif ($this->config['format']=='xml'){
            $xml=simplexml_load_string($resp);
            $data=json_decode(json_encode($xml),true); //xml转数组
        }

        //判断交易是否成功
        if($data[$this->sdk_response]['code']!=10000){
            die("错误码：{$data[$this->sdk_response]['code']}<br>错误消息：{$data[$this->sdk_response]['sub_msg']},{$data[$this->sdk_response]['msg']}");
        }

        //验签
        $bool=$this->checkResponseSign($data);
        if(!$bool){
            die('签名验证失败');
        }
        return $data[$this->sdk_response];

    }

}