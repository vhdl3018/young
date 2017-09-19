<?php
/**
 * Created by PhpStorm.
 * User: qsqxj
 * Date: 2017/9/12
 * Time: 10:07
 */

/**
 * Class YOUNGPHP  框架单一入口文件
 */
final class YOUNGPHP{
    public static function run(){
        //定义网站框架目录，以及相关APP开发目录
        self::_set_const();
        //是否开启调试模式
        defined('DEBUG') || define('DEBUG', false);
        if(DEBUG) {
            //自动创建网站应用目录
            self::_create_dir();
            //自动载入框架所需的文件
            self::_import_file();
        }else{
            //不是调试模式，则关闭错误提示
            error_reporting(0);
            //引入核心文件整合的单一文件
            require_once(TEMP_PATH . DS .'~boot.php');
        }
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
        //框架扩展目录
        define('EXTENDS_PATH', YOUNGPHP_PATH . DS .'Extends');
        //框架工具类目录
        define('TOOL_PATH', EXTENDS_PATH . DS .'Tool');
        //框架外包目录
        define('ORG_PATH', EXTENDS_PATH . DS .'org');



        //项目根目录
        define('ROOT_PATH', dirname(YOUNGPHP_PATH));
        //临时目录
        define('TEMP_PATH', ROOT_PATH . DS . 'Temp');
        //日志目录
        define('LOG_PATH', ROOT_PATH . DS . 'Log');

        //应用目录
        define('APP_PATH', ROOT_PATH .DS. APP_NAME);
        define('APP_CONFIG_PATH', APP_PATH . DS . 'Config');
        define('APP_CONTROLLER_PATH', APP_PATH . DS .'Controller');
        define('APP_TPL_PATH', APP_PATH . DS .'Tpl');
        define('APP_PUBLIC_PATH', APP_TPL_PATH . DS .'Public');

        //创建公共
        define('COMMON_PATH', ROOT_PATH . DS . 'Common');
        //公共配置项目录
        define('COMMON_CONFIG_PATH', COMMON_PATH . DS .'Config');
        //公共模型目录
        define('COMMON_MODEL_PATH', COMMON_PATH . DS .'Model');
        //公共库目录
        define('COMMON_LIB_PATH', COMMON_PATH . DS .'Lib');



        //判断数据的传输方式
        define("IS_POST", $_SERVER['REQUEST_METHOD'] == 'POST' ? true :false);
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
            define("IS_AJAX",true);
        }else{
            define("IS_AJAX",false);
        }

    }

    /**
     * 自动创建网站应用目录
     */
    private static function _create_dir(){
        //将需要创建的目录，放到一个数组中去。
        $arr = array(
            COMMON_CONFIG_PATH,
            COMMON_MODEL_PATH,
            COMMON_LIB_PATH,
            APP_PATH,
            APP_CONFIG_PATH,
            APP_CONTROLLER_PATH,
            APP_TPL_PATH,
            APP_PUBLIC_PATH,
            TEMP_PATH,
            LOG_PATH
        );
        //循环创建目录
        foreach ($arr as $v){
            if (!is_dir($v)){
                mkdir($v, 0777, true);
            }
        }

        //将需要的公共模板，复制到网站的应用目录。
        is_file(APP_TPL_PATH . DS . 'success.html')
        || copy(DATA_PATH . DS . 'Tpl/success.html' , APP_TPL_PATH . DS . 'success.html');
        is_file(APP_TPL_PATH . DS . 'error.html')
        || copy(DATA_PATH . DS . 'Tpl/error.html' , APP_TPL_PATH . DS . 'error.html');



    }

    /**
     * 自动加载核心类文件
     */
    private static function _import_file(){
        $fileArr = array(
            CORE_PATH . DS .'Log.class.php',
            FUNCTION_PATH . DS .'function.php',
            CORE_PATH . DS .'Controller.class.php',
            CORE_PATH . DS . 'Application.php'
        );

        //将核心的文件内容，写入到一个文件中去
        $str = '';
        foreach ($fileArr as $v){
            $str .= trim(substr(file_get_contents($v), 5, -2));
            require_once $v;
        }
        $str = "<?php\r\n" . $str;

        file_put_contents(TEMP_PATH . DS . '~boot.php', $str) || die('access not allow');
    }
}

YOUNGPHP::run();