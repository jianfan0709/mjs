<?php
/**
 * Created by PhpStorm.
 * User: jiangfan
 * Date: 2017/8/11
 * Time: 上午12:47
 */

namespace app\mjs\model;


class JsTaskUserModel extends Base
{
  function getTaskUserFind($where){
      return self::where($where)->find();
  }
    function getTasklingquAdd($data){
        return self::save($data);
    }
    function getAll($where){
        return self::alias('tu')
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
                    c.status class_status,
                    tu.status tu_status,
                    tu.num tu_num')
            ->join('JsTask t','t.id=tu.task_id','LEFT')
            ->join('JsClassify c','t.classify_id=c.id','LEFT')
            ->select();
    }
    public function getEndTimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }
    public function getStartTimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }
}