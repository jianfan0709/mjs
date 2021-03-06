<?php
/**
 * Created by PhpStorm.
 * User: jiangfan
 * Date: 2017/8/8
 * Time: 下午11:03
 */

namespace app\mjs\model;
use app\mjs\model\Base;

class JsTaskModel extends Base
{
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
    }

    public function getTaskList($where=[]){
        return self::alias('t')
            ->where($where)
            ->field('t.id,
                    t.name,
                    t.image_url,
                    t.explain,
                    t.start_time,
                    t.end_time,
                    t.info,
                    t.num,
                    t.status,
                    t.status status_info,
                    t.remark,
                    t.listorder,
                    t.created_at,
                    t.updated_at,
                    t.classify_id,
                    c.name as class_name,
                    c.short_name class_short_name,
                    c.valid_name class_valid_name,
                    c.status class_status')
            ->join('JsClassify c','t.classify_id=c.id','LEFT')
            ->paginate(10);
    }
    public function getTaskListAll($where=[]){
        return self::alias('t')
            ->where($where)
            ->field('t.id,
                    t.name,
                    t.image_url,
                    t.explain,
                    t.start_time,
                    t.end_time,
                    t.info,
                    t.num,
                    t.status,
                    t.status status_info,
                    t.remark,
                    t.listorder,
                    t.created_at,
                    t.updated_at,
                    t.classify_id,
                    c.name as class_name,
                    c.short_name class_short_name,
                    c.valid_name class_valid_name,
                    c.status class_status')
            ->join('JsClassify c','t.classify_id=c.id','LEFT')
            ->select();
    }
    public function getTaskFind($where){
        return self::where($where)->field('*')->find();
    }
    public function TaskAdd($data){
        try{
            return self::save($data);
        }catch (\Exception $e){
            return false;
        }
    }
    public function TaskEdd($data,$where){
        try{
            return self::save($data,$where);
        }catch (\Exception $e){
            return false;
        }
    }
    public function TaskDelete($where){
        try{
            return self::save($this->_isdelete,$where);
        }catch (\Exception $e){
            return false;
        }
    }
    public function getStatusInfoAttr($value){
        return ['上线','下线'][$value];
    }
    public function getEndTimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }
    public function getStartTimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }
}