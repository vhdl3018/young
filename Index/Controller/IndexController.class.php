<?php
class IndexController extends Controller{

    public function index(){
        //$stu = new StudentModel();
        $data = K('student')->get_all_data();
        p($data);
    }
}
