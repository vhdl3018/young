<?php
class IndexController extends Controller{
    public function index(){
        header("content-type:text/html;charset=utf-8");
        echo "<h2>欢迎使用YOUNGPHP框架。(:!</h2>";
    }
}
