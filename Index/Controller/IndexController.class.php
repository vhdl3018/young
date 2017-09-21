<?php
class IndexController extends Controller{

    public function index(){
        $this->assign('var',"我是一个模板数值，请注意查收 。");
        $this->display();
    }
}
