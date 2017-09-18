<?php
class IndexController extends Controller{
    public function __init(){
        //echo "子类本身的初始化过程";
    }
    public function index(){
       halt(array('message'=> "出错了",
                  'url'    => 'http:www.baidu.com'
       ));
    }
}
