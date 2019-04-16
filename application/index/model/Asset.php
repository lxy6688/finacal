<?php
    namespace app\index\model;
    use think\Model;
    /**
     * 首页资产数据相关业务及数据层访问
     */
    class Asset extends Model{

        /**
         * 获取总资产、可用资产等数据
         * @param int $uid 用户id
         * @return Array 
         */
        public function getAsset($uid=0){
            $data = self::get(['user_id'=>$uid]);
            return $data->toArray();
        }    
    } 
