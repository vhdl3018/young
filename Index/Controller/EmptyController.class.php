<?php
/**
 * Created by PhpStorm.
 * User: qsqxj
 * Date: 2017/9/19
 * Time: 19:12
 */
class EmptyController{

    public function index(){
        echo "I am a good boy!";
    }


    public function __empty(){
        echo "我是不存在的方法";
    }
}