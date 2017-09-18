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
    protected $var = array();
    public function __construct(){
        //echo "父类本身的初始化过程";
        //给父类增加一个本类的初始化方法
        if(method_exists($this, '__init')){
            $this->__init();
        }

        if(method_exists($this, '__auto')){
            $this->__auto();
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

    /**
     * 载入模板函数
     * @param null $tpl
     */
    protected function display($tpl=null){
        //默认自动载入当前操作方法对应的模板
        if(is_null($tpl)){
            //如果为空，则自动加载当前应用模板目录中对应的方法名模板
            $path = APP_TPL_PATH .DS . CONTROLLER . DS .ACTION . '.html';
        }else{
            $suffix = strrchr($tpl, '.');
            $tpl = empty($suffix) ? $tpl.'.html' : $tpl;
            $path = APP_TPL_PATH . DS . CONTROLLER . DS . $tpl;
        }

        if (!is_file($path)) halt($path . "模板文件不存在！") ;
        //将模板数组数据，分解成关联数组对的变量和值。
        extract($this->var);
        //载入当前模板文件
        include $path;
    }

    /**
     * 为模板分配显示数据
     * @param $var
     * @param $value
     */
    protected function assign($var, $value){
        $this->var[$var] = $value;
    }

}

?>