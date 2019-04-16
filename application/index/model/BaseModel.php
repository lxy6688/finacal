<?php
namespace app\index\model;
use think\Model;
use message\Message;
use think\Db;

class BaseModel extends Model {
    protected $sTable;    //Db类操作时，对应的数据库表，不走AR-ORM关系映射
    //初始化
    public function __construct(){
        parent::__construct();
        $this->sTable = 'licai_'.strtolower($this->name);
    }

    /**
     * 条件查询
     *
     * @param string $table  表名
     * @param Array  $param  参数数组
     * @param Array  $where  查询条件数组
     * @param string $sort   排序顺序，默认asc
     * @param string $pag    true | false  默认为true,代表and; false代表or
     * @return Array 
     *
     * where条件的用法
     * 数组查询：
     * $map['name'] = 'thinkphp';
     * $map['status'] = 1;
     * Db::table('think_user')->where($map)->select();    //查询条件按and相连
     *
     * 表达式查询：
     * $map['id']  = ['>',1];
     * $map['mail']  = ['like','%thinkphp@qq.com%'];
     *
     * 
     * order用法
     * ->order('id desc')
     * ->order('id desc,status asc')
     *
     *
     * group用法
     *  ->group('user_id')
     *  ->group('user_id,test_time')
     * 
     *  
     * limit用法
     * ->limit(10)
     * ->limit(10,25)
     */
    public function get_list($table,$where=array(),$param=array(),$sort='asc',$pag=TRUE){
        //判断查询fields是否存在
        if(!isset($param['fields'])){
            $param['fields'] = '*';
        }

        $result = Db::table($table)->field($param['fields']);

        //判断where条件是否存在
        if(count($where) > 0){
            $result = ($pag)? $result->where($where) : $result->whereOr($where);
        }

        //order by id  排序 
        if(isset($param['order'])){
            $orderStr = $param['order'].' asc';
            if($sort === 'desc'){
                $orderStr = $param['order'].' desc';
            }

            $result = $result->order($orderStr);
        }

        //判断group by是否存在
        if(isset($param['group'])){
            $result = $result->group($param['group']);
        }

        //limit限制
        if(isset($param['limit'])){
            $result = $result->limit($param['limit']);
        }
        return $result->select();
        //echo $result->select()->getLastSql();exit;
    }
}
