<?php

namespace qcth\alipay;

/**
 * 微信插件统一入口
 */
class Index{
    
    //插件对象
    private $link;

    //实例化插件类 ,
    //$plug_name 是准备要实例化的插件名字,
    ///$construct 是 实例化的类的构造参数,可以不传
    public function __construct($plug_name,$construct=null){

        $class_name='\qcth\alipay\plug\\'.ucfirst($plug_name);
        if(!$this->link instanceof $class_name){
            $this->link=new $class_name($construct);
        }

    }
    //调用插件的某个方法
    public function __call($method,$params=null){
        if ( !method_exists( $this->link, $method ) ) {
            die('方法不存在,请创建');
        }
        return call_user_func_array( [ $this->link, $method ], $params );
    }
}

