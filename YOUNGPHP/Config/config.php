<?php
/**
 * Created by PhpStorm.
 * User: qsqxj
 * Date: 2017/9/12
 * Time: 12:40
 */

return array(
    //验证码参数
    'CODE' =>  array(
        'code'                 => '',
        'codeLen'              => 4,
        'codeWidth'            => 70,
        'codeHeight'           => 40,
        'codeType'             => 1,       //0----纯数字，1----纯字母，2----数字与字母混合。
        'codeStr'              =>'abcdefghjkmnpqrstuvwsyz23456789',
        'codeBg'               =>'#000000'
    ),
    //默认时区
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
    //Smarty配置项
    'SMARTY_ON'             =>true,
    'LEFT_DELIMITER'        =>'{Yp',
    'RIGHT_DELIMITER'       =>'}',
    'CACHE_ON'              =>false,
    'CACHE_TIME'            =>60

);