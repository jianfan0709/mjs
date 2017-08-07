<?php

/**
 * Created by PhpStorm.
 * User: jiangfan
 * Date: 2017/5/8
 * Time: 上午9:45
 */
namespace app\mjs\model;

use think\Db;
use think\Model;
use traits\model\SoftDelete;

class Base extends Model
{
    //protected $resultSetType = 'collection';
    //开启自动写入创建时间与更新时间
    protected $autoWriteTimestamp = true;
    //定义时间戳字段名 int default 0
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    //软删除 引入SoftDelete trait
    use SoftDelete;
    //软删除 字段名称 tinyint  default NULL  1
    protected $deleteTime = 'isdelete';

    //自定义初始化
    public $_isdelete=['isdelete'=>1,'deleteby'=>''];
    public $_undelete=['isdelete'=>null,'deleteby'=>''];
    protected function initialize(){
        //需要调用model的intitalize方法
        parent::initialize();
        //TODO:自定义的初始化
    }

    public function getPLSql($pl_alias,$name='image_url',$type='!lists'){
                $local_url=getLocalUrl();
                $upy_url=getUpyUrl();
        return 'IFNULL(IF(
                    LENGTH(`'.$pl_alias.'`.`upy_url`),
                    CONCAT("'.$upy_url.'",`'.$pl_alias.'`.`upy_url`,"'.$type.'"),
                    CONCAT("'.$local_url.'",`'.$pl_alias.'`.`local_url`)
                   ),"") '.$name;
    }

    public function getLngLatDis($lng,$lat,$name='distance',$fieldLng='`store`.`lng`',$fieldlat='`store`.`lat`'){
        return "TRUNCATE(
            ROUND( 6378.138 *2 * ASIN( SQRT( POW( SIN( ( ".$lat." * PI( ) /180 - {$fieldlat} * PI( ) /180 ) /2 ) , 2 )
                    +
                    COS( ".$lat." * PI( ) /180 ) * COS(  {$fieldlat} * PI( ) /180 )
                    *
                    POW( SIN( ( ".$lng." * PI( ) /180 - {$fieldLng} * PI( ) /180 ) /2 ) , 2 ) ) ) *1000 )/1000,1)
                    {$name}";
    }

    public function getCreatedAtAttr($time){
        return date('Y-m-d H:i:s',$time);
    }

    public function getUpdatedAtAttr($time){
        return date('Y-m-d H:i:s',$time);
    }
}