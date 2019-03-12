<?php

namespace qcth\alipay\plug_trait;

trait ArrayToStringTrait{

    //数组转字符串
    protected function array_to_string($array,$urlencode=false){
        ksort($array);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($array as $k => $v) {
            if (!empty($v) && "@" != substr($v, 0, 1)) {

                if($urlencode){
                    $v=urlencode($v);
                }

                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }

        return $stringToBeSigned;
    }
}