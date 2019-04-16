<?php
namespace app\index\controller;
use app\index\model\User;
use think\Controller;

class Login extends Controller
{

    public function hello(){
        $con = 'aaaa';
        $this->assign('name',$con);
        return $this->fetch('index/hello');
    }

    /**
     * 跳转到logo登录页面
     */
    public function index(){
        return $this->fetch('index/login');
    }

    /**
     * 验证用户和密码
     * @param string username  用户
     * @param string password  密码
     * @return json
     */
    public function valiLogin(){
        //初始化返回条件
        $resp = array(
            'status' => ['code'=>'200','msg'=>''],
            'data'=>[]
        );

        $username = input('username');
        $passwd = input('password');

        $user = new User;
        $res = $user->valiLogin($username,$passwd);
        //return $res->toJson();
        if($res){
            $resp['status']['msg'] = '登录成功!'; 
        }else{
            $resp['status']['code'] = '1';
            $resp['status']['msg'] = '用户名或密码错误';
        }
        return json_encode($resp);
    }


    /**
     * 用户退出
     */
    public function logout(){
        //清除当前用户的session登录状态
        //
        
        $this->redirect('/index/index/login');
    }
    
}
