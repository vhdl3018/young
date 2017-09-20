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
        //设置用户自定义错误处理函数（警告类错误提示）
        set_error_handler(array(__CLASS__, 'error'));
        register_shutdown_function(array(__CLASS__, 'fatal_error'));
        //加载用户自动定义的文件（Common目录下的文件）
        self::_user_import();
        //初始化框架的url路径
        self::_set_url();
        //
        spl_autoload_register(array(__CLASS__, '_autoload'));
        //创建一个默认的控制器
        self::_create_demo();
        //自动运行一个默认的应用
        self::_app_run();
    }

    /**
     * 配置项加载
     */
    private static function _init(){
        //1.加载配置项--框架的配置
        C(include(CONFIG_PATH . DS . 'config.php'));

        //2.加载公共配置项
        $commonPath = COMMON_CONFIG_PATH . DS . 'config.php';
        $commonConfig = <<<str
<?php
return array(
    //配置项
);
?>
str;
        if(!is_file($commonPath)){
            file_put_contents($commonPath, $commonConfig);
        }
        //加载用户的配置项
        C(include $commonPath);
        //3.加载用户配置项
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
     * 定义框架的url，外部路径。
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
        switch (true) {
            //判断是否是控制器--controller
            case strlen($className)>10 && substr($className, -10) == 'Controller':
                $path = APP_CONTROLLER_PATH . DS . $className . ".class.php";
                //判断当前控制器是否存在
                if(!is_file($path)){
                    $emptyPath = APP_CONTROLLER_PATH . DS .'EmptyController.class.php';
                    if(is_file($emptyPath)){
                        include $emptyPath;
                        return;
                    }else{
                        halt($path . '控制器未找到。');
                    }

                }
                include $path;
                break;
            default:
                $path = TOOL_PATH . DS . $className . '.class.php';
                if(!is_file($path)) halt($path . '类未找到。');
                include $path;
                break;
        }


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

        //将默认的控制器和方法，设置为动态的常量
        define('CONTROLLER', $c);
        define('ACTION', $a);
        

        $c .= 'Controller';
        if (class_exists($c)){  //如果调用的控制器类存在，则直接实例化当前控制器类
            //实例化控制器
            $obj = new $c();
            //调用控制器方法
            if(!method_exists($obj, $a)){
                if(method_exists($obj, '__empty')){
                    $obj->__empty();
                }else{
                    halt($c . "控制器" . $a . "方法不存在！");
                }
            }else{
                $obj->$a();
            }

        }else{  //调用的当前控制器类不存在，则直接实例化空控制器类
            $obj = new EmptyController();
            $obj->index();
        }


    }
    /**
     * 加载Common目录中，用户自定义的文件
     */

    private static function _user_import(){
        $fileArr = C('AUTO_LOAD_FILE');

        if(is_array($fileArr) && !empty($fileArr)){
            foreach ($fileArr as $v) {
                require_once COMMON_LIB_PATH . DS . $v;
            }
        }
    }

    /**
     * 用户自定义错误处理函数
     * @param $errno
     * @param $error
     * @param $file
     * @param $line
     */
    public static function error($errno, $error, $file, $line){
        switch($errno){
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                $msg = $error . $file . "第{$line}行";
                halt($msg);
                break;
            case E_STRICT:
            case E_USER_WARNING:
            case E_USER_NOTICE:                
            default:
                if(DEBUG){
                    include DATA_PATH . DS . 'Tpl/notice.html';
                }
                break;
        }
    }

    public static function fatal_error(){
        if($e = error_get_last()){
            //p($e);
            self::error($e['type'],$e['message'], $e['file'],$e['line']);
        }
    }

}
?>