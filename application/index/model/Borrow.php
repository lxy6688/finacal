<?php
namespace app\index\model;
use message\Message;
use think\Db;

/**
 * borrow相关明细的业务及数据层访问
 *
 * @author xiaoyang 
 */
class Borrow extends BaseModel{

    /**
     * 插入borrow记录
     * @param int    $uid       用户id
     * @param array  $postData  待插入的数组数据
     * @return Array
     */
    public function addBorrow($uid,$postData=[]){
        
        !$postData['type_id'] && Message::showError(0,'Lack of parameter:type');
        !$postData['income'] && Message::showError(0,'Lack of parameter:count');
        !empty($postData) || Message::showError(10,'insert failed, array data is null');

        //事务处理,收入表和总资产表
        Db::startTrans();
 
        //更新资产表
        if($postData['type'] == 1){
            //可用资产
            $updateAsset = [
                'total_asset'=>array('exp','total_asset+'.$postData["income"]),
                'avail_asset'=>array('exp','avail_asset+'.$postData["income"]),
            ];
        }else if($postData['type'] == 2){ 
            //锁定资产
            $updateAsset = [
                'total_asset'=>array('exp','total_asset+'.$postData["income"]),
                'lock_asset'=>array('exp','lock_asset+'.$postData["income"]),
            ];
        }
        unset($postData['type']);
        $postData['createtime'] = time();
        $updateRes = Db::table('licai_asset')->where('user_id',$uid)->update($updateAsset);

        //插入到收入表
        $addRes = Db::table('licai_income')->insert($postData); 
        if($updateRes && $addRes){
            Db::commit();
            return true;
        }else{
            Db::rollback();
            return false;
        }

    }

    /**
     * borrow 列表数据
     *
     * @param integer $uid      用户id
     * @param string  $limit    limit条件
     * @param string  $stime    起始时间
     * @param string  $etime    结束时间
     * @param string  $sort     排序顺序
     * @param string  $order    排序字段
     * @return Array
     */
    public function getList($uid,$limit,$stime,$etime,$sort,$order){
        $param = [];
        if($limit){
            $param['limit'] = $limit;
        }

        $where = ['user_id'=>$uid];
        if($stime && $etime){
            //时间范围查询
            $where['create_time'] = [['>=',$stime],['<=',$etime]];
        }

        $sort = ($sort)? $sort : 'asc';
        $order = ($order)? $order : 'create_time';
        $param['order'] = $order;
        $data = $this->get_list($this->sTable,$where,$param,$sort);

        /*
        if($data){
            array_walk($data,function(&$value,$key,$name){
                $value['name'] = $name;
            },$name);
        } 
         */
        return $data;
    }
} 
