<?php

namespace qcth\alipay\plug_trait;

trait BuildParamsTrait{

    use CreateSignTrait;
    
    //$is_pay 代表的是支付，
    protected function build_params($is_pay=false){
        
        //公共构建参数
        $params["app_id"] = $this->config['app_id']; //appid支付宝提供
        $params["version"] = '1.0'; //固定值
        $params["format"] = $this->config['format']; //支持xml或json格式。json兼容性好
        $params["sign_type"] = $this->config['sign_type']; //签名方式
        $params["method"] = $this->build_params_method; //接口
        $params["timestamp"] = date("Y-m-d H:i:s");
        $params["alipay_sdk"] = $this->config['alipay_sdk']; //sdk版本
        $params["charset"] = $this->config['charset'];
        
        if($is_pay){
            return $this->build_params_pay($params);
        }
        
        return $this->build_params_exec($params);
        
    }
    //构建下单参数
    private function build_params_pay($params){
        //自有参数
        $params["notify_url"] = $this->config['notify_url'];
        $params["return_url"] = $this->config['return_url'];
        
        $params['biz_content'] = json_encode($this->order_info,JSON_UNESCAPED_UNICODE);  //主要区别： $params中 带 biz_content元素

        //签名
        $params["sign"] = $this->make_sign($params);

        return $params;
    }
    
    //除了下单支付以外构建
    private function build_params_exec($params){

        $this->apiParams['biz_content']=json_encode($this->order_info,JSON_UNESCAPED_UNICODE); //主要区别： $params中 不带  biz_content元素

        $params["sign"] = $this->make_sign(array_merge($params,$this->apiParams));
        
        return $params;
    }











}