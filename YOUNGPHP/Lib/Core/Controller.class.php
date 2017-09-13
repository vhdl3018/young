<?php
/**
 * Created by PhpStorm.
 * User: qsqxj
 * Date: 2017/9/12
 * Time: 21:31
 */

/**
 * Class Controller  控制器父类
 */
class Controller{
    public function __construct(){
        echo "父类本身的初始化过程";
        //给父类增加一个本类的初始化方法
        if(method_exists($this, '__init')){
            $this->__init();
        }
    }
    /**
     * 操作成功进行下一步操作。
     * @param $msg          提示信息
     * @param null $url     跳转地址
     * @param int $time     跳转时间
     */
    protected function success($msg, $url=null, $time=3){
        $url = $url ? "window.location.href='" . $url . "'" : 'window.location.history.back(-1)';
        include APP_TPL_PATH . DS . 'success.html';
    }

    /**
     * 操作失败进行一步操作
     * @param $msg          提示信息
     * @param null $url     跳转地址
     * @param int $time     跳转时间
     */
    protected function error($msg, $url=null, $time=3){
        $url = $url ? "window.location.href='" . $url . "'" : 'window.location.history.back(-1)';
        include APP_TPL_PATH . DS . 'error.html';
    }

}