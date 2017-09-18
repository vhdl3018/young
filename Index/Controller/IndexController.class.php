<?php
class IndexController extends Controller{
    public function __init(){
        //echo "子类本身的初始化过程";
    }
    public function index(){

        $ispost = $_SERVER['REQUEST_METHOD'] == 'post' ? true : false;
        p($ispost);

        if (IS_POST){
            $username = $_POST['username'];
            p($username);
        }

        $this->display();
    }
}
