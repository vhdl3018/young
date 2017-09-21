<?php

/**
 * Created by PhpStorm.
 * User: qsqxj
 * Date: 2017/9/21
 * Time: 22:42
 */
class SmartyView{
    private static $smarty = null;
    public function __construct(){
        if (!is_null(self::$smarty)){
            return;
        }
        $smarty = new Smarty();

        //定义模板目录
        $smarty->template_dir = APP_TPL_PATH . DS . CONTROLLER . '/';
        //定义编译目录
        $smarty->compile_dir = APP_COMPILE_PATH;
        //定义缓存
        $smarty->cache_dir = APP_CACHE_PATH;
        $smarty->left_delimiter = C('LEFT_DELIMITER');
        $smarty->right_delimiter = C('RIGHT_DELIMITER');
        $smarty->caching = C('CACHE_ON');
        $smarty->cache_lifetime = C('CACHE_TIME');
        self::$smarty = $smarty;

    }

    /**
     * 调用Smarty类的显示方法。
     * @param null $tpl
     */
    protected function display($tpl = null){
        self::$smarty->display($tpl, $_SERVER['REQUEST_URI']);
    }

    /**
     * 调用Smarty类的，分配数值方法。
     * @param $var
     * @param $value
     */
    protected function assign($var, $value){
        self::$smarty->assign($var, $value);
    }

    /**
     * 设置模板缓存的失效时间
     * @param null $tpl
     * @return mixed
     */
    protected function is_cached($tpl=null){
        if(!C('SMARTY_ON')) halt("请先开户Smarty");
        $tpl = $this->get_tpl($tpl);
        return self::is_cached($tpl, $_SERVER['REQUEST_URI']);
    }
}