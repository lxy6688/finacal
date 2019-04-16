<?php
namespace app\index\model;
use message\Message;
use think\Db;

/**
 * 消费汇总及相关明细的业务及数据层访问
 */
class Cost extends Model{

    /**
     * 首页展示不同的消费类型及各个类别的总消费
     * @param int $uid 用户id
     * @return Array 
     */
    public function getList($uid=0){
        return Db::query("select SUM(cost) as cost,c.type_id,t.name from licai_cost as c left join licai_type as t on c.type_id = t.id where c.user_id=? group by c.type_id",array($uid));
    }    

    /**
     * 添加消费
     * @param int    $uid       用户id
     * @param array  $postData  待插入的数组数据
     * @return Array
     */
    public function addCostAsset($uid,$postData=[]){
        
        !$postData['type_id'] && Message::showError(0,'Lack of parameter:type');
        !$postData['cost'] && Message::showError(0,'Lack of parameter:count');
        !empty($postData) || Message::showError(10,'insert failed, array data is null');

        //事务处理,收入表和总资产表
        Db::startTrans();
 
        //更新资产表,更新已花费资产,总资产、可用资产
        $updateAsset = [
            'total_asset'=>array('exp','total_asset-'.$postData['cost']),
            'avail_asset'=>array('exp','avail_asset-'.$postData['cost']),
            'cost_asset'=>array('exp','cost_asset+'.$postData['cost']),
        ];

        $updateRes = Db::table('licai_asset')->where('user_id',$uid)->update($updateAsset);

        $postData['createtime'] = time();
        //插入到消费表
        $addRes = Db::table('licai_cost')->insert($postData); 
        if($updateRes && $addRes){
            Db::commit();
            return true;
        }else{
            Db::rollback();
            return false;
        }

    }

    /**
     * 某个消费类别的详细记录
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
