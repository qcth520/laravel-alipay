<?php

namespace qcth\alipay;

use Illuminate\Support\ServiceProvider;

class AlipayServiceProvider extends ServiceProvider
{

    //服务提供者延迟加载
    protected $defer=true;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('Alipay',function ($app){
            return new Index($app);
        });


    }

    /**
     * 
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

       
    }

    public  function  provides()
    {
        return ['Alipay'];
    }
}
