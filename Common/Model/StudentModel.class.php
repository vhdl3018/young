<?php

/**
 * Created by PhpStorm.
 * User: qsqxj
 * Date: 2017/9/21
 * Time: 20:52
 */
class StudentModel extends Model{
    protected $table = 'student';

    public function get_all_data(){
        return $this->all();
    }

}