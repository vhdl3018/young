<?php

/**
 * Created by PhpStorm.
 * User: qsqxj
 * Date: 2017/9/12
 * Time: 11:18
 */
final class Application{
    public static function run(){
        //框架参数相关初始化
        self::_init();
        //初始化框架的url路径
        self::_set_url();
        //
        spl_autoload_register(array(__CLASS__, '_autoload'));

        //
        self::_create_demo();

        //
        self::_app_run();
    }

    private static function _init(){
        //加载配置项
        C(include(CONFIG_PATH . DS . 'config.php'));
        //用户配置项
        $userPath = APP_CONFIG_PATH . DS .'config.php';
        $userConfig = <<<str
<?php
return array(
    //配置项
);
?>
str;
        if(!is_file($userPath)){
            file_put_contents($userPath, $userConfig);
        }
        //加载用户的配置项
        C(include $userPath);
        //设置默认时区

        date_default_timezone_set(C('DEFAULT_TIME_ZONE'));
        //echo DEFAULT_TIME_ZONE;
        //echo $config['DEFAULT_TIME_ZONE'];
        //echo C('CODE_LEN');
        //是否开启session
        C('SESSION_AUTO_START') && session_start();
    }

    /**
     *
     */
    private static function _set_url(){
        //p($_SERVER);
        $path = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];

        $path = str_replace('\\', '/', $path);

        //定义框架的url,也即是外部路径
        define('__APP__', $path);
        define('__ROOT__', dirname(__APP__));

        define('__TPL__', __ROOT__ . DS . APP_NAME . DS .'Tpl');
        define('__PUBLIC__', __TPL__ . DS . 'Public');


    }

    /**
     * 自动加载类功能
     * @param $className
     */
    private static function _autoload($className){
        //echo $className;
        include APP_CONTROLLER_PATH . DS . $className . ".class.php";

    }

    /**
     * 自动创建一个控制器
     */
    private static function _create_demo(){
        $path =  APP_CONTROLLER_PATH . DS .'IndexController.class.php';

        $str = <<<str
<?php
class IndexController extends Controller{
    public function index(){
        header("content-type:text/html;charset=utf-8");
        echo "<h2>欢迎使用YOUNGPHP框架。(:!</h2>";
    }
}

str;
        if(!is_file($path)){
            file_put_contents($path, $str);
        }

    }

    /**
     * 自动运行控制器
     */
    private static function _app_run(){
        //通过路由功能获取控制器名称
        $c = isset($_GET[C('VAR_CONTROLLER')]) ? $_GET[C('VAR_CONTROLLER')] : 'Index';
        //通过路由功能获取控制器方法
        $a = isset($_GET[C('VAR_ACTION')]) ? $_GET[C('VAR_ACTION')] : 'index';

        $c .= 'Controller';
        //实例化控制器
        $obj = new $c();
        //调用控制器方法
        $obj->$a();
    }
}