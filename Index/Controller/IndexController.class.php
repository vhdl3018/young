<?php
class IndexController extends Controller{
    public function index(){
        //p(C());
        $code = new Code();
        $code->show();
    }
}
