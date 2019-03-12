<?php

namespace qcth\alipay\plug_trait;

trait CreateSignTrait{

    //生成签名
    protected function make_sign($params_array){

        $param_string=$this->array_to_string($params_array);
        return $this->sign($param_string);
    }



    /**
     * 加密串生成
     * 私有密钥，可以是字符串，也可以以文件形式
     * @param $data 要加密的字符串
     * @return string  返出加密后的字符串
     */
    protected function sign($data) {
        if(empty($this->config['merchant_private_key_file'])){
            $priKey=$this->config['merchant_private_key'];
            $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
                wordwrap($priKey, 64, "\n", true) .
                "\n-----END RSA PRIVATE KEY-----";
        }else {
            $priKey = file_get_contents($this->config['merchant_private_key_file']);
            $res = openssl_get_privatekey($priKey);
        }

        if(empty($res)){
            return '您使用的私钥格式错误，请检查RSA私钥配置';
        }


        if ("RSA2" == $this->config['sign_type']) {
            openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($data, $sign, $res);
        }

        if(!empty($this->config['merchant_private_key_file'])){
            openssl_free_key($res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }
}