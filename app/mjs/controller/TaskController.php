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
namespace app\mjs\controller;

use think\Db;
use cmf\controller\AdminBaseController;

class TaskController extends AdminBaseController
{
    /**
     * @api {get} ds/Index/tableInfo 数据库字典
     * @apiVersion 1.0.0
     * @apiName tableInfo
     * @apiGroup A-Demo
     *
     * @apiDescription 描述信息。
     *
     * @apiParam {String} user Ds。
     * @apiParam {String} pass Dsjd。
     *
     * @apiSuccess {Number}   code        0:成功
     *
     */
   public function classify(){
       $model=new \app\mjs\model\JsClassifyModel;
       $result=$model->getClassifyList();
       $this->assign('TaskData',$result->items());
       $this->assign('page', $result->render());
       return $this->fetch();
   }

    public function classifyadd(){
        return $this->fetch();
    }

    public function classifyedit(){
        $model=new \app\mjs\model\JsClassifyModel;
        $result=$model->getClassifyFind(['id'=>$this->request->param('id')]);
        $this->assign('data',$result);
        return $this->fetch();
    }

    public function addPost()
    {
        $data = $this->request->param();
        $model = new \app\mjs\model\JsClassifyModel();
        $re=$model->classifyAdd($data['post']);
        if($re){
            $this->success('成功', url('Task/classify'));
        }else{
            $this->error('失败，请检查');
        }
    }

    public function editPost()
    {
        $data = $this->request->param();
        $model = new \app\mjs\model\JsClassifyModel();
        $re=$model->classifyEdd($data['post'],['id'=>$this->request->param('id')]);
        if($re){
            $this->success('成功', url('Task/classify'));
        }else{
            $this->error('失败，请检查');
        }
    }
    public function classifyDelete(){
        $model = new \app\mjs\model\JsClassifyModel();
        $re=$model->classifyDelete(['id'=>$this->request->param('id')]);
        if($re){
            $this->success('成功', url('Task/classify'));
        }else{
            $this->error('失败，请检查');
        }
    }

    public function taskList(){
        $model=new \app\mjs\model\JsTaskModel;
        $result=$model->getTaskList();
        $this->assign('TaskData',$result->items());
        $this->assign('page', $result->render());
        return $this->fetch();
    }
    public function taskadd(){
        $model=new \app\mjs\model\JsClassifyModel;
        $result=$model->getClassifyListAll();
        $this->assign('TaskData',$result);
        return $this->fetch();
    }
    public function taskaddPost(){
        $data = $this->request->param();
        $data['post']['start_time']=strtotime($data['post']['start_time']);
        if(empty($data['post']['end_time'])){
            $model = new \app\mjs\model\JsClassifyModel();
            $data['post']['end_time']=$data['post']['start_time']+$model->getClassifyFind(['id'=>$data['post']['classify_id']])['valid_name'];
        }
        $model = new \app\mjs\model\JsTaskModel();
        $re=$model->TaskAdd($data['post']);
        if($re){
            $this->success('成功', url('Task/taskList'));
        }else{
            $this->error('失败，请检查');
        }
    }
}