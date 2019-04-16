<?php
namespace app\index\controller;
use app\index\model\Wage as WageModel;
use message\Message;

/**
 * wage controllers
 *
 * @author  xiaoyang
 */
class Wage extends Base
{

    /**
     * 初始化
     */
    public function __construct(){
        parent::__construct();
        $this->wage = new WageModel;
    }

    /**
     * 跳转到 wage 首页
     */
    public function index()
    {
        return "wage";
    
    }

    
    /**
     * 插入 wage信息
     * @param decimal(10,2)     $total_pretax    税前wage 
     * @param decimal(10,2)     $real_wage       实发wage
     * @param double   $count  收入金额
     * @param string   $card   账号/卡号
     * @param string   $from   收入来源
     * @param string   $rtime  收入时间
     * @param string   $desc   备注 
     * @return json
     */ 
    public function addWage(){
        $postData = [
            'type'  => (input('type') !==null)? input('type'):1,
            'type_id'  => (input('tid') !==null)? input('tid'):'',
            'income'   => (input('count') !==null)? input('count'):0,
            'card_num' => (input('card') !==null)? input('card'):'',
            'from_in'  => (input('from') !==null)? input('from'):'',
            'receive_time' => (input('rtime') !==null)? input('rtime'):'',
            'desc' => (input('desc') !==null)? input('desc'):''
        ];

        $resp = $this->wage->addWage($this->uid,$postData);
        ($resp)? Message::showSucc($resp) : Message::showError(6,'add asset failed ');
    }

    /**
     * wage 列表
     *
     * @param int     $gid    公司id(查询所属公司的wage列表)
     * @param string  $limit  limit条件
     * @param string  $offset 偏移量
     * @param string  $stime  起始时间
     * @param string  $etime  结束时间
     * @param string  $sort   排序顺序
     * @param string  $order  排序字段
     * @return json
     */
    public function getList(){
        $gid = (input('gid') !== null)? input('gid') : 0;
        $limit = (input('limit') !== null)? input('limit') : 10;
        $offset = (input('offset') !== null)? input('offset') : 0;
        $limit = $offset.','.$limit;

        $stime = (input('stime') !== null)? input('stime') : '';
        $etime = (input('etime') !== null)? input('etime') : '';
        $sort =  input('sort');
        $order = input('order');
        $resp = $this->wage->getList($this->uid,$gid,$name,$limit,$stime,$etime,$sort,$order);
        ($resp)? Message::showSucc($resp) : Message::showError(7,'get detail failed '); 
    }
    
}
