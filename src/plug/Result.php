<?php

namespace qcth\alipay\plug;

//同步通知或异步通知
use qcth\alipay\plug_trait\ResultCheckTrait;

class AlipayResult{
    use ResultCheckTrait;

    //配置项数组
    protected $config;

    public function check($data,$config){
        //配置项不能为空
        if(empty($config)){
            return '支付宝配置数组不能为空';
        }
        //配置项，赋值全局属性
        $this->config=$config;
        return $this->result_check($data);
    }

}