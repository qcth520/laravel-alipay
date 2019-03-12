<?php

namespace qcth\alipay\plug;

use qcth\alipay\plug_trait\ArrayToStringTrait;
use qcth\alipay\plug_trait\BuildParamsTrait;

/**
 * Class Alipay
 * 支持PC及手机，支付
 */
class Pay{

   use BuildParamsTrait,ArrayToStringTrait;

    public $config; //支付宝配置
    public $order_info; //订单信息

    public $type;  //区分：是PC支付，还是手机支付

    public $build_params_method; //请求接口，例如： alipay.trade.wap.pay

    public $alipay_sdk_version='alipay-sdk-php-20161101'; //sdk版本号


    /**
     * @param $order_info 订单信息
     * @param string $method  支付宝下单，只支持POST或GET
     * @return string 如果是get则返出 url,如果是post返出post表单，其它则反出错误
     */
    public function go($order_info,$config=null,$type='pc',$method='GET'){

        //订单信息数组不能为空
        if(empty($order_info)){
            return '错误： 订单信息数组不能为空';
        }
        //全作全局属性，方便其它方法，获取订单信息
        $this->order_info=$order_info;   //订单信息，赋值给全局属性

        //配置项不能为空
        if(empty($config)){
            return '支付宝配置数组不能为空';
        }
        //配置项，赋值全局属性
        $this->config=$config;

        //分区是PC支付，还是手机支付
        $this->type=strtoupper(trim($type));
        switch ($this->type){
            case 'PC':  //PC构建参数，固定值
                $this->order_info['product_code'] = "FAST_INSTANT_TRADE_PAY"; //这个是个固定值，构建参数中需要
                $this->build_params_method='alipay.trade.page.pay'; //请求接口
                break;
            case 'MOBILE': //手机 构建参数，固定值
                $this->order_info['productCode'] = "QUICK_WAP_WAY"; //这个是个固定值，构建参数中需要
                $this->build_params_method='alipay.trade.wap.pay';  //请求接口
                break;
            default:
                die('错误 :下单接支付接口，只支持PC或mobile');
        }


        //判断请求方法，只支持get或post
        $method=strtoupper(trim($method)); //下单方式，get或post
        switch ($method){
            case 'GET':
                $data=$this->get_pay_url();  //get时为，返回的url地址，跳转后，即可到支付宝
                header('Location:'.$data);die;
                break;
            case 'POST':
                $data=$this->form_html(); //post表单，请求支付宝接口
                echo $data;die;
                break;
            default:
                die('错误 :支付宝下单请求方法,只支持GET或POST');
        }
    }

    //get方式下单，生成url下单的完整请求地址
    private function get_pay_url(){
        //构建请求参数的数组
        $param_array=$this->build_params(true);

        //数组转成请求字符串参数
        $query_string=$this->array_to_string($param_array,true);

        return $this->config['gatewayUrl'].'?'.$query_string;
    }

    /**
     * 以post方式，支付下单
     * 生成html表单
     * @return string
     */
    private function form_html(){
        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".$this->config['gatewayUrl']."?charset=".$this->config['charset']."' method='POST'>";
        $para_temp=$this->build_params(true);
        foreach($para_temp as $key=>$val){

            if (!empty($val)) {

                $val = str_replace("'","&apos;",$val);

                $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
            }
        }

        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml."<input type='submit' value='ok' style='display:none;''></form>";

        $sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";

        return $sHtml;
    }

}