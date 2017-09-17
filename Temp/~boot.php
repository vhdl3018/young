<?php
/**
 * Created by PhpStorm.
 * User: qsqxj
 * Date: 2017/9/16
 * Time: 19:47
 */
class Log{
    /**
     * 将程序运行错误，写入到错误日志Log文件中
     * @param $msg
     * @param string $level
     * @param int $type
     * @param null $dest
     */
    static public function write($msg, $level='ERROR', $type=3, $dest=null){
        //判断是否开启日志功能
        if(!C('SAVE_LOG')) return;

        //判断存放日志路径是否存在
        if(is_null($dest)){
           $dest = LOG_PATH . DS . date('Y_m_d') . '.log';
        }
        //日志路径存在，则把日志存放到对应的目录中去。
        if(is_dir(LOG_PATH)) error_log("[TIME]".date('y-m-g H:i:s') . "{$level}:" .$msg."\r\n",  $type, $dest);
    }


}/**
 * Created by PhpStorm.
 * User: qsqxj
 * Date: 2017/9/12
 * Time: 11:16
 */

/**
 * @param $arr  需要格式化打印的数据
 */
function p($arr){
    if(is_bool($arr)){
        var_dump($arr);
    }elseif(is_null($arr)){
        var_dump($arr);
    }else{
        echo '<pre style="padding:10px;border-radius: 5px;background: #f5f5f5;border: 1px solid #ccc;font-size: 14px;">' . print_r($arr,true) .'</pre>';
    }
}

//1.加载配置项
//C($sysConfig)  C($userConfig)
//2.读取配置项
//C('CODE_LEN')
//3.临时动态改变配置项
//C('CODE_LEN', 20)
//4.C()   直接读取配置项内容
function C($var=null, $value=null){
    //常态化保存配置项
    static $config = array();
    //p($config);
    if(is_array($var)){
        $config = array_merge($config, array_change_key_case($var, CASE_UPPER));
        return;
    }

    if(is_string($var)){
        $var = strtoupper($var);
        //两个参数都传递

        if(!is_null($value)){
            $config[$var] = $value;
            return;
        }else{
            //echo "------";
            return isset($config[$var]) ? $config[$var] : NULL;
        }
    }

    //如果两个参数都为空，刚返回所有的配置项。
    if(is_null($var) && is_null($value)){
        return $config;
    }
}

/**
 * 跳转页面函数
 * @param $url
 * @param int $time
 * @param string $msg
 */
function go($url, $time=0, $msg=''){
    //headers_sent  检测HTTP头是否已经发送
    if(!headers_sent()){
        $time == 0 ? header('Location' . $url) : header("Refresh:{$time};url={$url}");
        die($msg);
    }else{
        //http头已经发送，刚通过meta方式进行页面跳转。
        echo "<meta http-equiv='refresh' content='{$time}';url={$url} />";
        if($time) die($msg);
    }
}

/**
 * 框架错误日志打印功能
 * @param $error
 * @param string $level
 * @param int $type
 * @param null $dest
 */
function halt($error, $level='error', $type=3, $dest=null){
    if(is_array($error)){
        Log::write($error['message'], $level, $type, $dest);
    }else{
        Log::write($error, $level, $type, $dest);
    }

    //将错误信息，按照格式进行整理
    $e = array();
    if(DEBUG){
        if(!is_array($error)){
            $trace = debug_backtrace();
            $e['message'] = $error;
            $e['file']    = $trace[0]['file'];
            $e['line']    = $trace[0]['line'];
            $e['class']   = isset($trace[0]['class']) ? $trace[0]['class'] : '';
            $e['function']   = isset($trace[0]['function']) ? $trace[0]['function'] : '';

            //开启缓存
            ob_start();
            debug_print_backtrace();
            $e['trace'] = htmlspecialchars(ob_get_clean());
        }else{
            $e = $error;
        }
    }else{
        if($url = C('ERROR_URL')){
            go($url);
        }else{
            $e['message'] = C('ERROR_MSG');
        }
    }

    include DATA_PATH . DS . 'Tpl/halt.html';
    die;
}/**
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

}/**
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