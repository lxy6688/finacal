<?php
namespace app\index\controller;
use app\index\model\Income as IncomeModel;
use message\Message;

/**
 * 资产收入控制器controllers
 * @author  xiaoyang
 */
class Income extends Base
{

    /**
     * 初始化
     */
    public function __construct(){
        parent::__construct();
        $this->income = new IncomeModel;
    }

    /**
     * 跳转到资产收入总概况首页
     */
    public function index()
    {
	return $this->fetch('income');
        //return "income";
    
    }

    /**
     * 查询收入资产的全部类别和各个类别的总收入
     */
    public function getInComeList(){
	//测试数据
	$data = array(
                [
                    'name' =>     'yangzai样',
                    "position" =>  "System Architect",
                    "salary"  =>   "$3,120",
                    "start_date" => "2011/04/25",
                    "office" => "Edinburgh",
                    "extn"   => "5421"
                ],
                [
                    "name"  =>     "Garrett Winters",
                    "position" =>  "Director",
                    "salary"   =>  "$5,300",
                    "start_date" =>  "2011/07/25",
                    "office"  =>   "Edinburgh",
                    "extn"    =>   "8422"
                ]
        );
	//$data = []; dataTable显示暂无数据
	return json_encode($data);
	exit; 


        $resp = $this->income->getInComeList($this->uid);
        ($resp)? Message::showSucc($resp) : Message::showError(5,'get data is null');
    }
    
    /**
     * 增加收入资产
     * @param integer  $tid    类别id, 关联类别表 
     * @param integer  $type   1可用资产  2冻结资产
     * @param double   $count  收入金额
     * @param string   $card   账号/卡号
     * @param string   $from   收入来源
     * @param string   $rtime  收入时间
     * @param string   $desc   备注 
     * @return json
     */ 
    public function addAsset(){
        $postData = [
            'type'  => input('type') ?: 1,
            'type_id'  => (input('tid') !==null)? input('tid'):'',
            'income'   => input('count') ?: 0,
            'card_num' => (input('card') !==null)? input('card'):'',
            'from_in'  => (input('from') !==null)? input('from'):'',
            'receive_time' => (input('rtime') !==null)? input('rtime'):'',
            'desc' => (input('desc') !==null)? input('desc'):''
        ];

        $resp = $this->income->addAsset($this->uid,$postData);
        ($resp)? Message::showSucc($resp) : Message::showError(6,'add asset failed ');
    }

    /**
     * 某个收入类别的详细记录
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
	返回添加成功示例: {"status":{"code":"200","msg":""},"data":true}
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
        $resp = $this->income->getDetail($this->uid,$id,$name,$limit,$stime,$etime,$sort,$order);
        ($resp)? Message::showSucc($resp) : Message::showError(7,'get detail failed '); 
    }
    
}
