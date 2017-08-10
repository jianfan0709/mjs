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
use think\Db;

class IndexController extends HomeBaseController
{
	
	public function getuserinfo($userid,$token){
		echo '<pre>';
		$data=file_get_contents("https://oapi.dingtalk.com/user/get?access_token=$token&userid=$userid");
		var_dump(json_decode($data));
		exit;
	}
	
    public function index()
    {
		if(empty($_GET['user_id'])){
			$userid='ceshi';
		}else{
			$userid=$_GET['user_id'];
		}
		session_start();
		session('userid',$userid);
		if(!empty($_GET['token'])){
			$token=$_GET['token'];
		}
		
		if(!empty($_GET['code'])){
			$code=$_GET['code'];
		}
		
		//var_dump($userid);
		$this->getuserinfo($userid,$token);
		
		//$this->getusersso($token,$code);
		
		$lesson=DB::name('js_lesson')->field('id,name,pic')->select();
		$this->assign('lesson',$lesson);
		//$data=$this->getuserinfo($userid,$token);
		//$data=$this->getuserinfos($userid,$token);
		//$url=$this->showurl($token,$userid);
		//$res=$this->redirect("https://oapi.dingtalk.com/user/get?access_token=$token&userid=$userid");
		//$res=header("location:https://oapi.dingtalk.com/user/get?access_token=$token&userid=$userid");
        return $this->fetch(':index');
    }
	
	public function search(){
		return $this->fetch('search');
	}
}
