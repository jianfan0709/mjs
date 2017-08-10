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

class ExamineController extends AdminBaseController
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
        $list=DB::name('js_examine')->select();
        $this->assign('list',$list);
        return $this->fetch();
    }

    public function addexamine(){
        $list=DB::name('js_lesson')->select();
        $this->assign('list',$list);
        return $this->fetch();
    }

    public function addexaminepost(){
        
        if(empty($_POST['subjectid'])){
            $this->error('请选择课程！');
        }
        if(empty($_POST['type'])){
            $this->error('请选择题型!');
        }
        $type=$_POST['type'];
        if($type==1 || $type==2){
            if($type==1){
                $check_answer=$_POST['check_answer'];
            }elseif($type==2){
                $check_answer=implode('|', $_POST['check_answer']);
            }
            $data=array(
                'lessonid'=>$_POST['subjectid'],
                'type'=>$_POST['type'],
                'question'=>$_POST['question'],
                'answer1'=>$_POST['answer1'],
                'answer2'=>$_POST['answer2'],
                'answer3'=>$_POST['answer3'],
                'answer4'=>$_POST['answer4'],
                'check_answer'=>$check_answer,
                'score'=>$_POST['score'],
                'jiexi'=>$_POST['jiexi'],
                'create_time'=>date('Y-m-d H:i:s',time())
            );
        }else{
            $data=array(
                'lessonid'=>$_POST['subjectid'],
                'type'=>$_POST['type'],
                'question'=>$_POST['question'],
                'panduan_answer'=>$_POST['panduan_answer'],
                'score'=>$_POST['score'],
                'jiexi'=>$_POST['jiexi'],
                'create_time'=>date('Y-m-d H:i:s',time())
            );
        }
        $re=DB::name('js_examine')->insert($data);
        if($re){
            $this->success('添加成功！',url('examine/index'));
        }else{
            $this->error('添加失败！');
        }
    }

}