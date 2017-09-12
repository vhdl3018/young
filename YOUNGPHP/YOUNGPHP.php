<?php
/**
 * Created by PhpStorm.
 * User: qsqxj
 * Date: 2017/9/12
 * Time: 10:07
 */
final class YOUNGPHP{
    public static function run(){

        //定义网站框架目录，以及相关APP开发目录
        self::_set_const();
        //自动创建网站应用目录
        self::_create_dir();
        //自动载入框架所需的文件
        self::_import_file();
        Application::run();
        //echo "YOUNGPHP";
    }

    /**
     * 配置框架目录参数，以及相关网站应用常量
     */
    private static function _set_const(){
        //echo __FILE__;
        //获取当前框架目录，并转换成支持window,linux和unix系统。
        $path = str_replace('\\', "/", __FILE__);

        //目录分隔符
        define('DS', DIRECTORY_SEPARATOR);

        //框架根目录     D:/wamp64/www/young/YOUNGPHP
        define('YOUNGPHP_PATH', dirname($path));
        //框架配置目录   
        define('CONFIG_PATH', YOUNGPHP_PATH . '/Config');
        //框架公共数据目录
        define('DATA_PATH', YOUNGPHP_PATH . '/Data');
        //框架库目录
        define('LIB_PATH', YOUNGPHP_PATH . '/Lib');

        //框架核心类目录
        define('CORE_PATH', LIB_PATH . '/Core');
        //框架公共函数目录
        define('FUNCTION_PATH', LIB_PATH . '/Function');

        //项目根目录
        define('ROOT_PATH', dirname(YOUNGPHP_PATH));

        //应用目录
        define('APP_PATH', ROOT_PATH .DS. APP_NAME);
        define('APP_CONFIG_PATH', APP_PATH . DS . 'Config');
        define('APP_CONTROLLER_PATH', APP_PATH . DS .'Controller');
        define('APP_TPL_PATH', APP_PATH . DS .'Tpl');
        define('APP_PUBLIC_PATH', APP_TPL_PATH . DS .'Public');

    }

    /**
     * 自动创建网站应用目录
     */
    private static function _create_dir(){
        //将需要创建的目录，放到一个数组中去。
        $arr = array(
            APP_PATH,
            APP_CONFIG_PATH,
            APP_CONTROLLER_PATH,
            APP_TPL_PATH,
            APP_PUBLIC_PATH
        );
        //循环创建目录
        foreach ($arr as $v){
            if (!is_dir($v)){
                mkdir($v, 0777, true);
            }
        }
    }

    /**
     * 自动加载核心类文件
     */
    private static function _import_file(){
        $fileArr = array(
            FUNCTION_PATH . DS .'function.php',
            CORE_PATH . DS . 'Application.php'
        );

        foreach ($fileArr as $v){
            require_once $v;
        }
    }
}

YOUNGPHP::run();