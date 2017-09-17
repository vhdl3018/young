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


}

?>