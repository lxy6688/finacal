<?php
namespace app\index\controller;
use app\index\model\Type as TypeModel;
use message\Message;

/**
 * 收入/消费类别控制器controller层
 */
class Type extends Base
{
    /**
     * 初始化
     */
    public function __construct(){
        parent::__construct();
        $this->type = new TypeModel;
    } 

    /**
     * 添加某个用户的收入/消费类别
     * @param int    $type  类别(收入/消费)
     * @param string $name  类别名称
     * @return json 
     */
    public function addType(){
        //初始化返回条件
        $resp = array(
            'status' => ['code'=>'0','msg'=>''],
            'data'=>[]
        );

        $type = input('type');
        $name = input('name');
        if(!$type || !$name){
            $resp['status']['msg'] = 'Error: Lack of parameter';
            return json($resp);
        }

        $result = $this->type->addType($this->uid,$type,$name);
        if($result){ 
            $resp['data'] = $result;
            $resp['status']['code'] = '200';
        }
        return json($resp);
    }

    /**
     * 查询某个用户下的所有(收入/消费)类别
     *
     * @param int     $type   1收入类别   2消费类别 ,若不传，则返回全部
     * @param string  $offset 偏移量
     * @param string  $limit  limit条件, limit='10' or  limit = '10,20'
     * @return json
     */
    public function typeList(){
        //初始化返回条件
        $resp = array(
            'status' => ['code'=>'0','msg'=>''],
            'data'=>[]
        );
        $type = input('type');
        $limit = input('limit');
        $offset = input('offset');
        $limit = $offset.','.$limit;

        $result = $this->type->typeList($this->uid,$limit,$type);
        if(count($result)>0){ 
            $resp['data'] = $result;
            $resp['status']['code'] = '200';
        }
        return json($resp);
    }

    /**
     * 删除某个用户下的(收入/消费)类别
     * @param int $id  类别id
     * @return json
     */ 
    public function delType(){
        $id = input('id');
        if(!$id){
            Message::showError();
        } 
        $result = $this->type->delType($this->uid,$id);
        ($result)? Message::showSucc($result) : Message::showError(2,'删除失败,请重试...');
    }

    /**
     * 修改之前，获取原来的收入/消费类别
     * @param int $id  类别id
     * @return json
     */
    public function getOldType(){
        $id = input('id');
        if(!$id){
            Message::showError();
        }
        $result = $this->type->getOldType($this->uid,$id);
        ($result)? Message::showSucc($result) : Message::showError(1,'获取类别数据失败');
    }


    /**
     * 修改某个用户的收入/消费类别
     * @param string $name  类别名称
     * @param int    $id    类别id
     * @return json 
     */
    public function updateType(){
        //初始化返回条件
        $resp = array(
            'status' => ['code'=>'0','msg'=>''],
            'data'=>[]
        );

        $name = input('name');
        $id = input('id');
        if(!$name){
            $resp['status']['msg'] = 'Error: Lack of parameter';
            return json($resp);
        }

        $result = $this->type->updateType($this->uid,$id,$name);
        if($result){ 
            $resp['data'] = $result;
            $resp['status']['code'] = '200';
        }
        return json($resp);
    }
    
}
