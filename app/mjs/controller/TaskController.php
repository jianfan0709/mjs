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
    public function classifyAdd(){
       echo '添加';
       exit();
    }
    public function classifyEdit(){
        echo '编辑';
        exit();
    }
    public function classifyDelete(){
        echo '删除';
        exit();
    }
}