<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class LessonController extends AdminBaseController
{

    /**
     * 管理员列表
     * @adminMenu(
     *     'name'   => '管理员',
     *     'parent' => 'default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '管理员管理',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        return $this->fetch();
    }

    //课程管理
    public function lesson(){
        $list=DB::name('js_lesson')->alias('l')
              ->join('js_lesson_category c',' l.subject_id=c.id')
              ->field('l.id,c.cname,l.name,l.description,l.ctype,l.type')
              ->select();
        // $arr=DB::name('js_lesson_category')->select();
        // $this->assign('arr',$arr);

        $this->assign('list',$list);
		return $this->fetch();
	}

    //添加课程
    public function addlesson(){
        $category=DB::name('js_lesson_category')->where(['parentid'=>0])->select();
        
        $this->assign('category',$category);
        return $this->fetch();
    }

    public function addlessonpost(){
        if($_POST['subject']==0){
            $this->error('科目课程必选！');
        }
        if(empty($_POST['description'])){
            $this->error('描述不能为空！');
        }

        $re=Db::name('js_lesson')->insert([
           "subject_id" => $_POST['ssubject'],
           // 'ctype'=>$_POST['ctype'],
           'pic'=>$_POST['pic'],
           //'photo'=>implode(';', $_POST['photo_urls']),
           'description'=>$_POST['description'],
           'zan'=>$_POST['zan'],
           'hit'=>$_POST['hit'],
           'create_time'=>date("Y-m-d H:i:s",time()),
           'hot'=>$_POST['hot'],
           'recommend'=>$_POST['recommend'],
           //'guide'=>$_POST['guide'],
           //'speech'=>$_POST['speech']
           ]);
        if($re){
            $this->success('添加成功！',url('lesson/lesson'));
        }else{
            $this->error('添加失败');
        }
        return $this->success('添加成功！');
    }

    public function lessontype(){
        $id = $this->request->param('id', 0, 'intval');
        $type=Db::name("js_lesson")->where(["id" => $id])->field('type')->find();
        if($type['type']==0){
            $types=1;
        }else{
            $types=0;
        }
        $result = DB::name('js_lesson')->where(['id'=>$id])->update(['type'=>$types]);
        if($result){
            $this->success('修改成功！');
        }else{
            $this->error('修改失败');
        }
    }

     public function lessondelete(){
       $id = $this->request->param('id', 0, 'intval');

        $re=Db::name("js_lesson")->where(["id" => $id])->delete();
        if($re){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败');
        }
    }

    //科目管理
    public function subject(){
         //$models=DB::name('js_lesson_category')->select();
        $users = Db::name('js_lesson_category')
            ->where('parentid','>','0')
            ->order("id DESC")
            ->paginate(10);
        // 获取分页显示
        $page = $users->render();

        $rolesSrc = Db::name('js_lesson_category')->select();
        $this->assign("page", $page);
        $this->assign("list", $users);
        return $this->fetch();
    }

    //添加科目
    public function addsubject(){
        return $this->fetch();
    }

    public function addsubjectpost(){
       $re=Db::name('js_lesson_category')->insert([
           "parentid" => $_POST['subject'],
           'cname'=>$_POST['name'],
           'thumb'=>$_POST['thumb'],
           'logo'=>$_POST['logo'],
           'letter'=>strtoupper($_POST['letter']),
           'description'=>$_POST['description'],
           ]);
        if($re){
            $this->success('添加成功！',url('lesson/subject'));
        }else{
            $this->error('添加失败');
        }
    }

    public function choosesub(){
        $id=$_POST['id'];
        $list=DB::name('js_lesson_category')->where(['parentid'=>$id])->select();
        if(!empty($list)){
            $data='';$code='';
            // $msg['code']=1;
            // $msg['data']='获取成功!';
            $this->success('添加成功',url('lesson/addsubject'),['list'=>$list]);
        }else{
            $msg['code']=0;
            $msg['data']='获取失败';
        }
        $this->error('添加失败',[$msg]);
    }

    public function subjecttype(){
        $id = $this->request->param('id', 0, 'intval');
        $type=Db::name("js_lesson_category")->where(["id" => $id])->field('status')->find();
        if($type['status']==0){
            $types=1;
        }else{
            $types=0;
        }
        $result = DB::name('js_lesson_category')->where(['id'=>$id])->update(['status'=>$types]);
        if($result){
            $this->success('修改成功！');
        }else{
            $this->error('修改失败');
        }
    }

    public function subjectdelete(){
        $id = $this->request->param('id', 0, 'intval');
        $list=DB::name("js_lesson")->where(['subject_id'=>$id])->find();
        if(!empty($list)){
            $this->error('您不能删除该科目，该科目下还有课程！');
        }
        $re=Db::name("js_lesson_category")->where(["id" => $id])->delete();
        if($re){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败');
        }
    }
}