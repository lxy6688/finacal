<?php
namespace app\index\controller;
use app\index\model\Cost as CostModel;
use message\Message;

/**
 * 花销cost控制器controllers
 * @author  xiaoyang
 */
class Cost extends Base
{

    /**
     * 初始化
     */
    public function __construct(){
        parent::__construct();
        $this->cost = new CostModel;
    }

    /**
     * 跳转到花销cost总概况首页
     */
    public function index()
    {
        return "cost";
    
    }

    /**
     * 查询花销资产的全部类别和各个类别的总花销
     */
    public function getList(){
        $resp = $this->cost->getList($this->uid);
        ($resp)? Message::showSucc($resp) : Message::showError(5,'get data is null');
    }
    
    /**
     * 插入消费cost
     * @param integer  $tid    类别id, 关联类别表 
     * @param double   $count  消费金额
     * @param string   $card   账号/卡号
     * @param string   $from   消费来源
     * @param string   $rtime  消费时间
     * @param string   $desc   备注 
     * @return json
     */ 
    public function addCostAsset(){
        $postData = [
            'type_id'  => (input('tid') !==null)? input('tid'):'',
            'cost  '   => (input('count') !==null)? input('count'):0,
            'card_num' => (input('card') !==null)? input('card'):'',
            'payment_way'  => (input('from') !==null)? input('from'):'',
            'payment_time' => (input('rtime') !==null)? input('rtime'):'',
            'desc' => (input('desc') !==null)? input('desc'):''
        ];

        $resp = $this->cost->addCostAsset($this->uid,$postData);
        ($resp)? Message::showSucc($resp) : Message::showError(6,'add asset failed ');
    }

    /**
     * 某个消费类别的详细记录
     *
     * @param integer $id     类别id
     * @param string  $name   类别名称
     * @param string  $limit  查询限制数量
     * @param string  $offset 偏移量
     * @param string  $stime  起始时间
     * @param string  $etime  结束时间
     * @param string  $sort   排序顺序
     * @param string  $order  排序字段
     * @return json
     */
    public function getDetail(){
        $id = (input('id') !== null)? input('id') : 0;
        $name = (input('name') !== null)? input('name') : '';
        $limit = (input('limit') !== null)? input('limit') : 10;
        $offset = (input('offset') !== null)? input('offset') : 0;
        $limit = $offset.','.$limit;

        $stime = (input('stime') !== null)? input('stime') : '';
        $etime = (input('etime') !== null)? input('etime') : '';
        $sort =  input('sort');
        $order = input('order');
        $resp = $this->cost->getDetail($this->uid,$id,$name,$limit,$stime,$etime,$sort,$order);
        ($resp)? Message::showSucc($resp) : Message::showError(7,'get detail failed '); 
    }
    
}
