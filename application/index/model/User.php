<?php
    namespace app\index\model;
    use think\Model;
    /**
     * 用户权限相关业务及数据访问层的操作
     */
    class User extends Model{
        
        /**
         * 验证用户账号和密码
         * @param string name  用户
         * @param string pwd  密码
         * @return bool
         */        
        public function valiLogin($name='',$pwd=0){
            $where = ['login_un'=> $name,'login_pw'=>$pwd];
            $res = self::get($where);
            if($res){
                return true;
            }else{
                return false;
            }
        }
    } 
