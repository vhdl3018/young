<?php
class IndexController extends Controller{

    public function index(){


        $data =M('user')->exe('INSE1RT INTO qx_user SET username="young111"');
        p($data);
    }
}
