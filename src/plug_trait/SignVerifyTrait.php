<?php

namespace qcth\alipay\plug_trait;

trait SignVerifyTrait{

    //加密串比对，返出布尔值
    //比对两个加密串，是否相等
    protected function verify($string, $sign) {

        if(!empty($this->config['alipay_public_key'])){

            $pubKey= $this->config['alipay_public_key'];
            $res = "-----BEGIN PUBLIC KEY-----\n" .
                wordwrap($pubKey, 64, "\n", true) .
                "\n-----END PUBLIC KEY-----";
        }else {
            if(!empty($this->config['alipay_public_key_file'])){
                //读取公钥文件
                $pubKey = file_get_contents($this->config['alipay_public_key_file']);
                //转换为openssl格式密钥
                $res = openssl_get_publickey($pubKey);
            }
        }

        if(empty($res)){
            return 'error:支付宝RSA公钥错误。请检查公钥文件格式是否正确';
        }

        //调用openssl内置方法验签，返回bool值

        if ("RSA2" == $this->config['sign_type']) {
            $result = (bool)openssl_verify($string, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
        } else {
            $result = (bool)openssl_verify($string, base64_decode($sign), $res);
        }

        if(empty($this->config['alipay_public_key'])) {
            //释放资源
            openssl_free_key($res);
        }

        return $result;
    }

    //curl请求结果，验签
    //结果中有数组参数及支付宝加密好的sign,
    //用数组参数，加上自己的公钥，进行加密，然后与支付宝，返回的sign ，比对。
    private function checkResponseSign($data){

        if(empty($this->config['alipay_public_key']) && empty($this->config['alipay_public_key_file'])){
            return 'error:公钥字符串或公钥文件，至少有一个配置好';
        }

        //$this->sdk_response  类似 alipay_trade_query_response
        //说明，支付宝返回数组中包括两项，1,不同的sdk连上_response,作为键，对应的是参数数组 ; 2,$data[sign] 加密串

        $data[$this->sdk_response]=json_encode($data[$this->sdk_response]);

        $checkResult=$this->verify($data[$this->sdk_response], $data['sign']);  //第一个是字符串，由数组构建成字符串，第二个是，已加密好的密钥

        if($checkResult){ //验证通过
            return true;
        }

        //再次尝试
        if (strpos($data[$this->sdk_response], "\\/") > 0) {

            $data[$this->sdk_response] = str_replace("\\/", "/", $data[$this->sdk_response]);

            $checkResult = $this->verify($data[$this->sdk_response], $data['sign']);

            if (!$checkResult) {
                return false;  // 验证签名失败
            }else{
                return true;
            }

        }else{
            return false;
        }

    }
}