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
        if(is_dir(LOG_PATH)) error_log("[TIME]".date('y-m-d H:i:s') . "{$level}:" .$msg."\r\n",  $type, $dest);
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
function halt($error, $level='ERROR', $type=3, $dest=null){
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
}

/**
 *打印用户自定义的常量
 */
function print_const(){
    $const = get_defined_constants(true);
    p($const['user']);
    
}

/**
 * 自动创建一个模型对象实例
 * @param $table
 * @return Model
 */
function M($table){
    $obj = new Model($table);
    return $obj;
}

/**
 * 自动返回Model扩展类
 * @param $model
 * @return mixed
 */
function K($model){
    $model .= "Model";
    return new $model();
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
            //判断当前类是否为Model扩展类
            case strlen($className)>5 && substr($className, -5) == 'Model' :
                $path = COMMON_MODEL_PATH . DS . $className .".class.php";
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