<?php
namespace app\index\model;
use message\Message;
use think\Db;

/**
 * 收入汇总及相关明细的业务及数据层访问
 *
 * @author xiaoyang 
 */
class Income extends BaseModel{

    /**
     * 首页展示不同的收入类型及各个类别的总收入
     * @param int $uid 用户id
     * @return Array 
     */
    public function getInComeList($uid=0){
        return Db::query("select SUM(income) as income,i.type_id,t.name from licai_income as i left join licai_type as t on i.type_id = t.id where i.user_id=? group by i.type_id",array($uid));
    }    

    /**
     * 增加收入资产
     * @param int    $uid       用户id
     * @param array  $postData  待插入的数组数据
     * @return Array
     */
    public function addAsset($uid,$postData=[]){
        
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
     * 某个收入类别的详细记录
     *
     * @param integer $uid      用户id
     * @param integer $id       类别id
     * @param string  $name     类别名称
     * @param string  $limit    limit条件
     * @param string  $stime    起始时间
     * @param string  $etime    结束时间
     * @param string  $sort     排序顺序
     * @param string  $order    排序字段
     * @return Array
     */
    public function getDetail($uid,$id,$name,$limit,$stime,$etime,$sort,$order){
        $param = [];
        if($limit){
            $param['limit'] = $limit;
        }

        $where = ['user_id'=>$uid,'type_id'=>$id];
        if($stime && $etime){
            //时间范围查询
            $where['createtime'] = [['>=',$stime],['<=',$etime]];
        }

        $sort = ($sort)? $sort : 'asc';
        $order = ($order)? $order : 'createtime';
        $param['order'] = $order;
        $data = $this->get_list($this->sTable,$where,$param,$sort);

        if($data){
            array_walk($data,function(&$value,$key,$name){
                $value['name'] = $name;
            },$name);
        } 
        return $data;
    }
} 
