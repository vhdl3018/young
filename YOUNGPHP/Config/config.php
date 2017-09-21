<?php
/**
 * Created by PhpStorm.
 * User: qsqxj
 * Date: 2017/9/12
 * Time: 12:40
 */

return array(
    'CODE_LEN'              => 4,
    'DEFAULT_TIME_ZONE'     => 'PRC',
    //是否开启SESSION功能
    'SESSION_AUTO_START'    => TRUE,
    'VAR_CONTROLLER'        => 'c',
    'VAR_ACTION'            => 'a',

    //是否开启日志功能
    'SAVE_LOG'              => TRUE,

    //错误跳转地址
    'ERROR_RUL'             => '',
    'ERROR_MSG'             => '网站出错了，请稍候再试...',
    //自动加载Common目录下面的文件
    'AUTO_LOAD_FILE'        => array(),
    //数据库参数配置
    'DB_CHARSET'            => 'utf8',
    'DB_HOST'               => 'localhost',
    'DB_PORT'               => 3306,
    'DB_USER'               => 'root',
    'DB_PASSWORD'           => '123456',
    'DB_DATABASE'           => '',
    'DB_PREFIX'             => '',
    //Smarty配置荐
    'SMARTY_ON'             =>true,
    'LEFT_DELIMITER'        =>'{Yp',
    'RIGHT_DELIMITER'       =>'}',
    'CACHE_ON'              =>false,
    'CACHE_TIME'            =>60

);