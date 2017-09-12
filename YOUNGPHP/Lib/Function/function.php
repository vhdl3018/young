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
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
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