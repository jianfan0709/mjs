<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\portal\controller;

use cmf\controller\HomeBaseController;
use think\db;
class RankController extends HomeBaseController
{
	
   //排行榜
	public function index()
	{
		$zhouyi= date("Y-m-d",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
		$zhouri= date("Y-m-d",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y")));
		$list=DB::name('js_users')->alias('u')
			  ->join('js_skill s','s.userid=u.userid')
			  ->field('u.userid,u.name,u.avatar,(sum(s.sale)+sum(s.diligent)+sum(s.concern)+sum(s.active)+sum(s.score)) totalscore')
			  ->order('totalscore desc')
			  ->group('u.userid')
			  ->select();
		$this->assign('zhouyi',$zhouyi);
		$this->assign('zhouri',$zhouri);
		$this->assign('list',$list);
		return $this->fetch();
	}
	//个人页面需要的数据
	public function getDetail()
	{
		$i = 0;
		$uid = $_COOKIE['userid'];
		$arr = Db::name("mjs_lesson_category")->field('avatar,name,score,uid')->order("score desc")->find();
		foreach($arr as $val){
			if($val['id'] == $uid){
				$i++;
			}else{
				break;
			}
		}
		$arr['count'] = $this->countNum();
		$account['range'] = $i;
		$this->assign('arr',$arr);
		$this->fetch(); 
	}
}
