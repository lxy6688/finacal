<?php
namespace app\index\controller;
use app\index\model\Invest as InvestModel;
use message\Message;

/**
 * borrow controllers
 *
 * @author  xiaoyang
 */
class Invest extends Base
{

    /**
     * 初始化
     */
    public function __construct(){
        parent::__construct();
        $this->invest = new InvestModel;
    }

    /**
     * 跳转到 borrow 首页
     */
    public function index()
    {
        return "borrow";
    
    }

    
    /**
     * 插入invest信息
     * @param decimal(10,2)     $total_pretax    税前wage 
     * @param decimal(10,2)     $real_wage       实发wage
     * @param double   $count  收入金额
     * @param string   $card   账号/卡号
     * @param string   $from   收入来源
     * @param string   $rtime  收入时间
     * @param string   $desc   备注 
     * @return json
     */ 
    public function addInvest(){
        $postData = [
            'type'  => (input('type') !==null)? input('type'):1,
            'type_id'  => (input('tid') !==null)? input('tid'):'',
            'income'   => (input('count') !==null)? input('count'):0,
            'card_num' => (input('card') !==null)? input('card'):'',
            'from_in'  => (input('from') !==null)? input('from'):'',
            'receive_time' => (input('rtime') !==null)? input('rtime'):'',
            'desc' => (input('desc') !==null)? input('desc'):''
        ];

        $resp = $this->invest->addAsset($this->uid,$postData);
        ($resp)? Message::showSucc($resp) : Message::showError(6,'add asset failed ');
    }

    /**
     * invest 列表
     *
     * @param string  $limit  limit条件
     * @param string  $offset 偏移量
     * @param string  $stime  起始时间
     * @param string  $etime  结束时间
     * @param string  $sort   排序顺序
     * @param string  $order  排序字段
     * @return json
     */
    public function getList(){

        $limit = (input('limit') !== null)? input('limit') : 10;
        $offset = (input('offset') !== null)? input('offset') : 0;
        $limit = $offset.','.$limit;

        $stime = (input('stime') !== null)? input('stime') : '';
        $etime = (input('etime') !== null)? input('etime') : '';
        $sort =  input('sort');
        $order = input('order');
        $resp = $this->invest->getList($this->uid,$limit,$stime,$etime,$sort,$order);
        ($resp)? Message::showSucc($resp) : Message::showError(7,'get detail failed '); 
    }
    
}
