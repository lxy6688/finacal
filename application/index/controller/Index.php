<?php
namespace app\index\controller;
use app\index\model\User;
use app\index\model\Asset;
use think\Cache;

/**
 * 首页控制器controllers方法
 * @author xiaoyang
 */
class Index extends Base
{
    /**
     * 初始化
     */
    public function __construct(){
        parent::__construct();
        $this->asset = new Asset;
        $this->user = new User;
    }
    

    /**
     * 跳转到网站首页
     */
    public function index()
    {
	//echo '网站首页';	
       	return $this->fetch('index');
    }

    /**
     * 网站首页，获取总资产、可用资产等数据
     */
    public function getAsset(){
        //初始化返回条件
        $resp = array(
            'status' => ['code'=>'0','msg'=>''],
            'data'=>[]
        );
        $result = $this->asset->getAsset($this->uid);
        if(count($result)>0){ 
            $resp['data'] = $result;
            $resp['status']['code'] = '200';
        }
        return json($resp);
    }

    /**
     * 测试redis
     */
    public function cacheTest(){
        $url = $_SERVER['REQUEST_URI'];
        echo $url;exit;

        $redis = Cache::getHandler(); //tp5封装的redis对象,返回的是tp自己写的redis类的对象
        $redis->set('name','jack');   //这是tp5封装redis,可以自由的扩展，所以只能调用类中的方法

        //如果用原生的redis,则如下
        $res = $redis->handler()->lpush('namelist','jack list');  //$redis->handler() 这是实例化对象调用类中的属性，这个属性就是redis的原生对象
        echo $res;
        //print_r($redis);

    }
}
