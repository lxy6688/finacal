<?php
namespace app\index\controller;
use think\Controller;

class Base extends Controller
{
    //当前登录用户的id
    public $uid;

    public function __construct(){
        parent::__construct();
        $this->uid = 1;
    }

    public function index()
    {
        
        return '我是基础控制器';
    }
}
