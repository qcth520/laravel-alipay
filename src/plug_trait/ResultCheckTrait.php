<?php

namespace qcth\alipay\plug_trait;

trait ResultCheckTrait{
    use ArrayToStringTrait,SignVerifyTrait;
    //同步或异步验证
    public function result_check($result_array){
        
        $sign=$result_array['sign'];  //$_GET或$_POST中的sign
        $result_array['sign_type']=null;
        $result_array['sign']=null;

        $string=$this->array_to_string($result_array);

        return $this->verify($string,$sign);  //自己加密与$_GET或$_POST中的sign比对

    }
}