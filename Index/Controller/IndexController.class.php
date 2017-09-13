<?php
class IndexController extends Controller{
    public function __init(){
        echo "子类本身的初始化过程";
    }
    public function index(){
        //$this->error('失败', 'http://www.baidu.com', 10);
    }
}
