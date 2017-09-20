<?php
class IndexController extends Controller{

    public function index(){


        $data =M('user')->field('username')->find();
        p($data);
    }
}
