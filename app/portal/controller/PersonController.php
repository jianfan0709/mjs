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
class PersonController extends HomeBaseController
{
	
    //获取用户信息 以学习的课程
	public function index()
	{	
		$identify=0;
		// $identify = $_GET['idetify'];
		if($identify == 0){
		$uid = session('userid');
		$arr = $this->countNum($uid);
		$this->assign('arr',$arr);
		$this->assign('uid',$uid);
		$list = Db::name("js_lesson_learn")->alias('l')
				->join('js_lesson s','s.id=l.lessonid')
				->field('l.id,s.name,l.time,s.pic')
				->where(["l.userid" => $uid])
				->select();
		
		$this->assign('list',$list);
		return $this->fetch('index');
		}else{
			$this->fetch();
		}

		

	}
	
	//已收藏的课程
	public function getProject()
	{
		$identify=0;
		// $identify = $_GET['idetify'];
		if($identify == 0){
		$uid = session('userid');
		$arr = $this->countNum($uid);
		$this->assign('arr',$arr);
		$this->assign('uid',$uid);
		$list = Db::name("js_lesson_collect")->alias('l')
				->join('js_lesson s','s.id=l.lessonid')
				->field('l.id,s.name,l.time,s.pic')
				->where(["l.userid" => $uid])
				->select();
		$this->assign('list',$list);
		}else{
			$this->fetch();
		}
		return $this->fetch();
	}
	//成绩单
	public function getScoreList()
	{
		$uid = session('userid');
		$arr = Db::name("js_lesson_category")->where(["id" => $uid])->find();
		$this->assign('arr',$arr);
		$this->fetch('person/index');
	}
	//雷达图数据
	public function countNum()
	{
		// echo '<pre>';
		$uid = session('userid');
		$score         = [];
		$score['sales']= 30;
		$time 		   = Db::name("js_lesson_learn")->where(["userid" => $uid])->field('learn_time')->find();
		$score['diligent']= $time['learn_time']/10;
		$arr		   = Db::name("js_task_user")->where(["user_id" => $uid])->find();
		$score['active']  = $arr['num']*5+$arr['complete']*10-$arr['cancel']*6-$arr['uncom']*12;

		$score['concern']=50;
		$score['score']=80;

		foreach($score as $key=>$val){
			if($score[$key]>100){
				$score[$key]=100;
			}
			if($score[$key]<0){
				$score[$key]=0;
			}
		}

		return $score;
	}
	//获取科目详情
	public function getDetail()
	{
		$id = $_GET['id'];
		$arr= Db::name('js_lesson_category')->select();
		$this->assign('arr',$arr);
		$this->fetch();
	}
	//删除课程
	public function delCourse()
	{
		
		$str=rtrim($_POST['str'],'|');
		$type=$_POST['type'];
		$idArr= explode('|',$str);
		
		
		if($type == '1'){
			foreach($idArr as $val){
				$arr = Db::name("js_lesson_learn")->where(["id" => $val])->delete();
			}
			if($arr){
				$this->success('删除成功',url('person/index'),['code'=>1]);
			}
		}else if($type == '2'){
			foreach($idArr as $val){
				$arr = Db::name("js_lesson_collect")->where(["id" => $val])->delete();
			}
			if($arr){
				$this->success('删除成功',url('person/getProject'),['code'=>1]);
			}
		}
		$this->fetch();
	}
	//查看错题
	public function checkWrong()
	{
		$uid = session('userid');
		$arr = Db::name("js_lesson_category")->where(["id" => $uid])->find();
		$this->assign();
		$this->fetch();
	}
	//重新考试
	public function restart()
	{	
		$id = $this->request->parm('id',0,'inval');
		$deatil = Db::name()->field("title,result")->where(["id" => $id])->find();
		$this->fetch();
	}
}
