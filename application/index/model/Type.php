<?php
    namespace app\index\model;
    use message\Message;

    /**
     * 收入/消费类别相关业务及数据层访问
     */
    class Type extends BaseModel{

        /**
         * 添加某个用户的收入/消费类别
         * @param long    $uid     用户id
         * @param long    $type    类别(收入/消费)
         * @param string  $name    类别名称 
         * @return long(整型) 
         */
        public function addType($uid=0,$type=1,$name=''){
            $typeObj = new Type;

            $typeObj->data([
                'user_id' => $uid,
                'type' => $type,
                'name' => $name,
                'createtime' => time()
            ]);
            
            return ($typeObj->save())? $typeObj->id : 0;
             
        }    
        
        /**
         * 查询某个用户下的所有类别
         * @param int     $uid    用户id
         * @param string  $limit  limit条件, limit='10' or  limit = '10,20'
         * @param int     $type   1收入类别   2消费类别 ,若不传，则返回全部
         * @return Array
         */
        public function typeList($uid,$limit,$type){
            $param =[];
            $where = ['user_id'=>$uid];
            if($type){
                $where['type'] = $type;
            }
            if($limit){
                $param['limit'] = $limit;
            }
            //$param['limit'] = 10;
            return $this->get_list($this->sTable,$where,$param);
        }

        /**
         * 删除某个用户的收入/消费类别
         * @param long    $uid     用户id
         * @param int     $id      类别id
         * @return long(整型) 
         */
        public function delType($uid=0,$id=0){
            $typeObj = new Type;
            //判断此类别是否有关联的数据
            $isDel = Type::where(['id'=>$id,'user_id'=>$uid])->value('num');
            if($isDel){
                Message::showError(3,'此类别已关联数据，不能删除，请联系管理员');
            }

            $num = Type::destroy([
                'user_id' => $uid,
                'id' => $id
            ]);
            //返回删除成功的记录数,删除失败返回0
            return $num;
             
        }

        /**
         * 修改之前，获取原来的收入/消费类别
         * @param int $uid  用户id
         * @param int $id   类别id
         * @return Array
         */
        public function getOldType($uid=0,$id=0){
            $data = Type::get(['id'=>$id,'user_id'=>$uid]);
            return ($data)? $data->toArray() : [];
        }

        /**
         * 修改某个用户的收入/消费类别
         * @param long    $uid     用户id
         * @param string  $name    类别名称 
         * @return long(整型) 
         */
        public function updateType($uid=0,$id=0,$name=''){
            $typeObj = new Type;

            $num = $typeObj->save([
                'name' => $name
            ],['user_id'=>$uid,'id'=>$id]);

            //返回修改成功的记录数,修改失败时返回0 
            return $num;
             
        }
    } 
