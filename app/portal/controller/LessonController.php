<?php  
namespace app\portal\controller;
use cmf\controller\HomeBaseController;
use think\db;
Class LessonController extends HomeBaseController
{
	public function index()
	{	
		// echo '<pre>';
		// $type = empty($_GET['type'])?0:$_GET['type']; //0代表无限极分类 1代表字母列表
		$sss=DB::name('js_lesson_category')->where(['parentid'=>0])->field('id,cname')->select();
		$arr = Db::name('js_lesson_category')->select();
		$ccc=array();
		foreach($sss as $key=>$val){
			$ccc[$val['id']]=$val['cname'];
		}
		
		
		$arr1 = $this->getTree($arr,0);

		$this->assign('arr',$arr1);
		$this->assign('sss',$ccc);
		return $this->fetch();
	}

	public function index2(){
		// echo '<pre>';
		$arr = Db::name('js_lesson_category')
		->where('parentid != 0')
		->order('letter asc')
		->select();
		$arr = $this->getKind($arr);

		$this->assign('arr',$arr);
		return $this->fetch();
	}
	//无限极分类
	public function getTree($arr,$pid)
	{	
		$tree = array();
		
		foreach($arr as $key=>$val)
		{
			if($val['parentid']  == $pid){
				$arrs=DB::name('js_lesson_category')->where(['parentid'=>$val['id']])->field('id,cname,thumb')->select();
					$arrr=array();
					foreach($arrs as $vv){
						$arrr[]=$vv;
					}
					$tree[$val['id']]=$arrr;
			}
		
		}
		return (array)$tree;
		
	}
	// 	exit;
	// 	return $tree;
	// }
	//课程分类
	public function getKind($arr)
	{
		$kind= array();
		foreach($arr as $val){
			foreach(range('A','Z') as $n){
				if($val['letter'] == $n){
					$kind[$n][] = $val; 
				}
			}
		} 
		return $kind;
	}
	//热门课程
	public function hotCourse()
	{	
		// echo '<pre>';
		$list=DB::name('js_lesson')->where(['hot'=>1])->order('create_time desc')->select();
		
		$this->assign('list',$list);
		
		return $this->fetch();
	} 

	//新课推荐
	public function introduce()
	{
		// echo '<pre>';
		$list=DB::name('js_lesson')->where(['recommend'=>1])->order('create_time desc')->select();
		
		$this->assign('list',$list);
		
		// $type = $_GET['type']; //0代表无限极分类 1代表字母列表
		// $arr  = Db::name('')->order('createtime')->limit('0','10')->select();
		// if($type == '0')
		// {
		// 	$arr = $this->getTree($arr,0);
		// }else{
		// 	$arr = $this->getKind($arr);
		// }
		// $this->assign('arr',$arr);
		return $this->fetch();
	}
	//产品列表
	public function brandlist()
	{
		// echo '<pre>';
		$id = $this->request->param('id', 0, 'intval');
		//$id = $_GET['id'];
		$arr = Db::name('js_lesson')->where(["subject_id "=> $id])->select();
		$info=DB::name('js_lesson_category')->where(['id'=>$id])->find();
		$this->assign('info',$info);
		$this->assign('introduce',$arr);
		return $this->fetch();
	}
	//获取详情
	public function getDetail()
	{
		$id  = $_GET['id'];
		$arr = Db::name()->where("id = $id")->find();
		$res = Db::name()->where("id = $id")->field()->find();
		$res = $res+1;
		$rep = Db::name()->where("id = $id")->update("see = $res");
		$this->assign('detail',$arr);
		$this->fetch(); 
	}
	//品牌介绍
	public function brand()
	{	
		session_start();
		$userid=session('userid');
		//$id = $_GET['id']; //品牌id
		$id = $this->request->param('id', 0, 'intval');
		$arr = Db::name('js_lesson')->where(["id "=>$id])->field('id,name,pic,description,hit,guide,speech')->find();
		$res=DB::name('js_lesson')->where(['id'=>$id])->update(['hit'=>$arr['hit']+1]);
		$phraise = Db::name('js_lesson_zan')->where(['userid'=>$userid,'lessonid'=>$id])->find();
		if(!empty($phraise)){
			$zan=1;
		}else{
			$zan=0;
		}
		
		$this->assign('brand',$arr);
		$this->assign('zan',$zan);
		return $this->fetch();
	}
	//点赞
	public function pharse()
	{	
		session_start();
		$userid=session('userid');
		
		$id = $this->request->param('lessonid', 0, 'intval');
		$phraise = Db::name('js_lesson_zan')->where(['userid'=>$userid,'lessonid'=>$id])->find();
		$info=DB::name('js_lesson')->where(['id'=>$id])->find();
		if(empty($phraise)){
			$re=DB::name('js_lesson_zan')->insert(['userid'=>$userid,'lessonid'=>$id,'create_time'=>date('Y-m-d H:i:s',time())]);
			
			$res=DB::name('js_lesson')->where(['id'=>$id])->update(['zan'=>$info['zan']+1]);
			$this->success('取消点赞',url('lesson/brand',array('id'=>$id)),['code'=>0]);
		}else{
			$re=DB::name('js_lesson_zan')->where(['id'=>$phraise['id']])->delete();
			$res=DB::name('js_lesson')->where(['id'=>$id])->update(['zan'=>$info['zan']-1]);
			$this->success('取消点赞',url('lesson/brand',array('id'=>$id)),['code'=>1]);
		}
		// $res = Db::name()->where("id = $id")->update("$phraise = $phraise+1");
	}
	//收藏
	public function collect()
	{	
		session_start();
		$userid=session('userid');
		
		$id = $this->request->param('lessonid', 0, 'intval');
		$phraise = Db::name('js_lesson_collect')->where(['userid'=>$userid,'lessonid'=>$id])->find();
		$info=DB::name('js_lesson')->where(['id'=>$id])->find();
		if(empty($phraise)){
			$re=DB::name('js_lesson_collect')->insert(['userid'=>$userid,'lessonid'=>$id,'create_time'=>date('Y-m-d H:i:s',time())]);
			$res=DB::name('js_lesson')->where(['id'=>$id])->update(['collect'=>$info['collect']+1]);
			$this->success('取消点赞',url('lesson/brand',array('id'=>$id)),['code'=>0]);
		}else{
			$re=DB::name('js_lesson_collect')->where(['id'=>$phraise['id']])->delete();
			$res=DB::name('js_lesson')->where(['id'=>$id])->update(['collect'=>$info['collect']-1]);
			$this->success('取消点赞',url('lesson/brand',array('id'=>$id)),['code'=>1]);
		}
		// $res = Db::name()->where("id = $id")->update("$phraise = $phraise+1");
	}
	//搜索课程
	public function checkLesson()
	{
		$key = $_GET['keywords'];
		$detail = Db::name()->like("title like %$keywords%")->find();
		$this->assign('detail',$detail);
		$this->fetch();
	}
	//随机抽取10道题目
	public function papers()
	{
		$id = Db::name()->order("createtime desc")->limit('1')->find();
		for($i=0;$i<10;$i++){
			$arr[$i] = range('1','$id');
		}
		$re  = implode(',',$arr);
		$re  = rtrim($re,',');
		//$res = Db::name()->insert("code = $re");
		return $re;

	}
	//kaoshi 获取题目详情
	public function examine()
	{
		// echo '<pre>';
		$data=array();
		$id = $this->request->param('id', 0, 'intval');
		$userid=session('userid');
		$lessonid=$id;
		$re=DB::name('js_user_examine')->where(['userid'=>$userid,'lessonid'=>$lessonid])->find();
		if(!empty($re)){
			$ids=$re['id'];
		}else{
			$data['userid']=session('userid');
			$data['lessonid']=$id;
			$data['examineid']=0;
			$data['type']=0;
			$data['create_time']=time();
			DB::name('js_user_examine')->insert($data);
			$ids = Db::name('user')->getLastInsID();
		}
		
		$list=DB::name('js_examine')->where(['lessonid'=>$id])->order('rand()')->limit(0,10)->select();

		$this->assign('list',$list);
		$this->assign('id',$id);
		$this->assign('ids',$ids);
		return $this->fetch();
	}

	//计算成绩
	public function countScore()
	{
		$lessonid=$_POST['lessonid'];
		$userid=session('userid');
		$userexamineid=$_POST['userexamineid'];
		$answer=$_POST['post'];
		$data=array();
		$score=0;
		foreach($answer as $val){
			unset($data);
			$shiti=DB::name('js_examine')->where(['id'=>$val['tid']])->find();

			if($val['type']==3){
				$data['panduan_answer']=$shiti['panduan_answer'];
				$data['question']=$shiti['question'];
				$data['userexamineid']=$userexamineid;
				$data['tixing']=$val['type'];
				$data['lessonid']=$val['tid'];	
				if(empty($val['ans'])){
					$data['user_answer']='';
					$data['type']=2;

				}else{

					$data['user_answer']=$val['ans'];
					if($val['ans']==$shiti['panduan_answer']){
						$data['type']=1;
						$score+=$shiti['score'];
					}else{
						$data['type']=0;
					}
				}
			}elseif($val['type']==2){
				$data['userexamineid']=$userexamineid;
				$data['question']=$shiti['question'];
				$data['answer1']=$shiti['answer1'];
				$data['answer2']=$shiti['answer2'];
				$data['answer3']=$shiti['answer3'];
				$data['answer4']=$shiti['answer4'];
				$data['tixing']=$val['type'];
				$data['lessonid']=$val['tid'];
				$data['check_answer']=$shiti['check_answer'];
				if(empty($val['ans'])){
					$user_answer='';
					$data['type']=2;
				}else{
					$user_answer=implode('|',$val['ans']);
					$data['user_answer']=$user_answer;
					if($shiti['check_answer']==$user_answer){
						$data['type']=1;
						$score+=$shiti['score'];
					}else{
						$data['type']=0;
					}
				}
			}elseif($val['type']==1){
				$data['userexamineid']=$userexamineid;
				$data['question']=$shiti['question'];
				$data['answer1']=$shiti['answer1'];
				$data['answer2']=$shiti['answer2'];
				$data['answer3']=$shiti['answer3'];
				$data['answer4']=$shiti['answer4'];
				$data['tixing']=$val['type'];
				$data['lessonid']=$val['tid'];
				$data['check_answer']=$shiti['check_answer'];
				if(empty($val['ans'])){
					$user_answer='';
					$data['type']=2;
				}else{
					$data['user_answer']=$val['ans'];
					if($shiti['check_answer']==$val['ans']){
						$data['type']=1;
						$score+=$shiti['score'];
					}else{
						$data['type']=0;
					}
				}
			}

			$re=DB::name('js_user_examine_detail')->insert($data);
			
		}
		$info=DB::name('js_user_examine')->where(['id'=>$userexamineid])->find();
		$data1['score']=$score;
		$data1['examinetime']=time()-$info['create_time'];
		$res=DB::name('js_user_examine')->where(['id'=>$userexamineid])->update($data1);
		if($re && $res){
			$this->assign('score',$score);
			$this->assign('lessonid',$lessonid);
			return $this->fetch();
		}else{
			$this->error('请求失败，请重试！');
		}


		
	}
	//查看错题
	public function wrongs(){
		$userid=session('userid');
		$id = $this->request->param('lessonid', 0, 'intval');
		$list=DB::name('js_user_examine')->alias('a')
			  ->join('js_user_examine_detail b','a.id=b.userexamineid')
			  ->where(['a.userid'=>$userid,'a.lessonid'=>$id])
			  ->select();
		$this->assign('list',$list);
		$this->assign('lessonid',$id);
		return $this->fetch();
	}

	//重答
	public function reanswer(){
		// echo '<pre>';
		$data=array();
		$id = $this->request->param('lessonid', 0, 'intval');
		$userid=session('userid');
		$lessonid=$id;
		$re=DB::name('js_user_examine')->where(['userid'=>$userid,'lessonid'=>$lessonid])->find();
		if(!empty($re)){
			$ids=$re['id'];
			$data['userid']=session('userid');
			$data['lessonid']=$lessonid;
			$data['examineid']=0;
			$data['type']=0;
			$data['create_time']=time();
			DB::name('js_user_examine')->where(['userid'=>$userid,'lessonid'=>$lessonid])->update($data);
		}else{
			$data['userid']=session('userid');
			$data['lessonid']=$lessonid;
			$data['examineid']=0;
			$data['type']=0;
			$data['create_time']=time();
			DB::name('js_user_examine')->insert($data);
			$ids = Db::name('user')->getLastInsID();
		}
		
		//$list=DB::name('js_examine')->where(['lessonid'=>$lessonid])->order('rand()')->limit(0,10)->select();
		$colum=DB::name('js_user_examine')->alias('a')
			  ->join('js_user_examine_detail b','a.id=b.userexamineid')
			  ->where(['a.id'=>$ids])
			  ->column('b.lessonid');
		$list=DB::name('js_examine')
				->where('id','in',$colum)
				->limit(0,10)->select();
		$re=DB::name('js_user_examine_detail')->where('lessonid','in',$colum)->where(['userexamineid'=>$ids])->delete();
		$this->assign('list',$list);
		$this->assign('id',$lessonid);
		$this->assign('ids',$ids);
		return $this->fetch('examine');
	}
}
