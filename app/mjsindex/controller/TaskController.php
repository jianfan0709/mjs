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

namespace app\mjsindex\controller;
use cmf\controller\HomeBaseController;
use cmf\lib\Upload;
use think\Db;

class TaskController extends HomeBaseController
{
   public function index(){
       $model=new \app\mjs\model\JsClassifyModel;
       $list=$model->getClassifyListAll();
       $this->assign('TaskData',$list);
       $model_1=new \app\mjs\model\JsTaskModel;
       $list_1=$model_1->getTaskListAll(['c.status'=>1,'end_time'=>['>',time()]]);
       $model_2=new \app\mjs\model\JsTaskUserModel();
       session_start();
       $userid=session('userid');
       foreach ($list_1 as &$v){
           if($model_2->getTaskUserFind(['user_id'=>$userid,'task_id'=>$v['id']])){
            $v['task_user']=1;
           }else{
            $v['task_user']=0;
           }
       }
       $this->assign('TaskDataAll',$list_1);

       $list_2=$model_1->getTaskListAll(['c.status'=>2,'end_time'=>['>',time()]]);
        foreach ($list_2 as $v){
            if($model_2->getTaskUserFind(['user_id'=>$userid,'task_id'=>$v['id']])){
                $v['task_user']=1;
            }else{
                $v['task_user']=0;
            }

        }unset($v);
       $this->assign('TaskDataAll_1',$list_2);

       $model_3=new \app\mjs\model\JsTaskUserModel;
       $list_3=$model_3->getAll(['tu.user_id'=>$userid]);
       foreach ($list_3 as $v){
          $v['use_num']=Db::table('m_js_task_record')->where(['user_id'=>$userid,'task_id'=>$v['id']])->sum('num');
           $v['use_num']=$v['use_num']? $v['use_num']:0;
           if($v['use_num']>=$v['num']){
            $v['ceil_num']=100;
               $v['tu_status']=1;
           }else{
               $v['ceil_num']=$v['use_num']/$v['num'];
               $v['tu_status']=0;
           }
       }unset($v);
       $this->assign('TaskDataAll_3',$list_3);
       return $this->fetch();
   }

    public function taskuserAdd()
    {
        session_start();
        $userid=session('userid');
        $data=$this->request->param();
        $data['user_id']=$userid;
        $file = request()->file('file');
        if($file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $data['images_ids']=DS . 'uploads'.$info->getSaveName();

            }else{
                $this->error('上传失败');
            }
        }
        $data['audit_status']=1;
        $data['audit_num']=$data['num'];
        $data['created_at']=time();
        $data['updated_at']=time();
        Db::table('m_js_task_record')->insert($data);
        $this->redirect('index');
    }
    public function linqutask(){
        session_start();
        $userid=session('userid');
        $data=$this->request->param();
        $data['user_id']=$userid;
        $model_1=new \app\mjs\model\JsTaskModel;
        $data['num']=$model_1->getTaskFind(['id'=>$data['task_id']])['num'];
        $model_2=new \app\mjs\model\JsTaskUserModel();
        $model_2->getTasklingquAdd($data);
        if($model_2->getTasklingquAdd($data)){
            exit();
            $this->error('领取失败');
        }else{
            $this->redirect('index');
        }


    }
}