<?php
/**
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
}

/**
 *打印用户自定义的常量
 */
function print_const(){
    $const = get_defined_constants(true);
    p($const['user']);
    
}






























?>