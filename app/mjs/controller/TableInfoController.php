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

class TableInfoController extends AdminBaseController
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
    public function tableinfo(){
        if(config('app_debug')==false){
            echo 'app_debug已关闭';
            exit();
        }
        $dbserver =config('database.hostname');
        $dbusername =config('database.username');
        $dbpassword = config('database.password');
        $database= config('database.database');
        $title = 'Ds 数据字典';

        if (!preg_match('/^[\w-]+$/', $database)) {
            die('Database name error.');
        }

        $mysqli = @mysqli_connect($dbserver, $dbusername, $dbpassword) or die("Mysql connect is error.");

        mysqli_select_db($mysqli, $database);
        mysqli_query($mysqli, 'SET NAMES UTF8');
        $result = mysqli_query($mysqli, 'show tables', MYSQLI_STORE_RESULT);

        //取得所有表名
        $tempTables = array();
        while ($row = mysqli_fetch_array($result)) {
            $tables[]['TABLE_NAME'] = $row[0];
        }


        //循环取得所有表的备注及表中列消息
        foreach ($tables as $k => $v) {
            $sql = 'SELECT * FROM ';
            $sql .= 'INFORMATION_SCHEMA.TABLES ';
            $sql .= 'WHERE ';
            $sql .= "table_name = '{$v['TABLE_NAME']}' AND table_schema = '{$database}'";
            $table_result = mysqli_query($mysqli, $sql);
            while ($t = mysqli_fetch_array($table_result)) {
                $tables[$k]['TABLE_COMMENT'] = $t['TABLE_COMMENT'];
            }
            $sql = 'SELECT * FROM ';
            $sql .= 'INFORMATION_SCHEMA.COLUMNS ';
            $sql .= 'WHERE ';
            $sql .= "table_name = '{$v['TABLE_NAME']}' AND table_schema = '{$database}'";

            $fields = array();
            $field_result = mysqli_query($mysqli, $sql);
            while ($t = mysqli_fetch_array($field_result)) {
                $fields[] = $t;
            }
            $tables[$k]['COLUMN'] = $fields;
        }
        mysqli_close($mysqli);

        $html = '';
        $daohang='<ol>';
        //循环所有表
        foreach ($tables as $k => $v) {
            $daohang.='<li><a href="#'.$k.'ttt" class="aaa">'.$v['TABLE_NAME'].($v['TABLE_COMMENT'] ? "【<span class='TABLE_COMMENT'>{$v['TABLE_COMMENT']}</span>】" : '【<span style="color:20F856;">请尽快补充表注释</span>】!!!!!') .'</a></li>';


            $num=$k+1;
            $html .= '<table name="'.$k.'ttt" id="'.$k.'ttt" border="1" cellspacing="0" cellpadding="0" align="center">';
            $html .= '<caption>'."<span clss='tt'>{$num}</span>. " . $v['TABLE_NAME'] . ' ' . ($v['TABLE_COMMENT'] ? "【<span class='TABLE_COMMENT'>{$v['TABLE_COMMENT']}</span>】" : '【<span style="color:20F856;">请尽快补充表注释</span>】!!!!!') . '</caption>';
            $html .= '<tbody><tr><th>字段名</th><th>数据类型</th><th>默认值</th><th>允许非空</th><th>自动递增</th><th>备注</th></tr>';
            foreach ($v['COLUMN'] AS $f) {
                $style='';
                if($f['COLUMN_NAME']==='created_at'){
                    $f['COLUMN_NAME']="<span class='red'>{$f['COLUMN_NAME']}</span>";
                    $html .= '<tr style="background-color:#90A691;">';
                }elseif ($f['COLUMN_NAME']==='isdelete'){
                    $f['COLUMN_NAME']="<span class='red'>{$f['COLUMN_NAME']}</span>";
                    $html .= '<tr style="background-color:#90A691;">';
                }elseif ($f['COLUMN_NAME']==='update_at'){
                    $f['COLUMN_NAME']="<span class='red'>{$f['COLUMN_NAME']}</span>";
                    $html .= '<tr style="background-color:#90A691;">';
                }elseif ($f['COLUMN_NAME']==='deleteby'){
                    $f['COLUMN_NAME']="<span class='red'>{$f['COLUMN_NAME']}</span>";
                    $html .= '<tr style="background-color:#90A691;">';
                }else{
                    $html .='<tr>';
                    $style='style="background-color:#FFF;"';
                }

                $html .= '<td class="c1" '.$style.'>' . $f['COLUMN_NAME'] . '</td>';
                $html .= '<td class="c2" '.$style.'>' . $f['COLUMN_TYPE'] . '</td>';
                $html .= '<td class="c3" '.$style.'>' . ($f['COLUMN_DEFAULT']===NULL ? '<span class="COLUMN_DEFAULT">NULL</span>' : $f['COLUMN_DEFAULT']) . '</td>';
                $html .= '<td class="c4" '.$style.'>' . ($f['IS_NULLABLE']==='NO' ? '否' : '<span class="IS_NULLABLE">是</span>') . '</td>';
                $html .= '<td class="c5" '.$style.'>' . ($f['EXTRA'] == 'auto_increment' ? '是' : ' ') . '</td>';
                $html .= '<td class="c6" '.$style.'>' . $f['COLUMN_COMMENT'] . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table></p>';
        }
        echo '<html>
            <meta charset="utf-8">
            <title>' . $title . '</title>
            <style>
                body,td,th {font-family:"宋体"; font-size:15px;}
                table{border-collapse:collapse;border:1px solid #CCC;background:#efefef;}
                table caption{text-align:left; background-color:#fff; line-height:2em; font-size:15px; font-weight:bold; }
                table th{text-align:left; font-weight:bold;height:26px; line-height:26px; font-size:15px; border:1px solid #CCC;}
                table td{height:20px; font-size:15px; border:1px solid #CCC;}
                .c1{ width: 170px;}
                .c2{ width: 130px;}
                .c3{ width: 70px;}
                .c4{ width: 80px;}
                .c5{ width: 80px;}
                .c6{ width: 300px;}
                .TABLE_COMMENT{color:#E08031;}
                .COLUMN_DEFAULT{color:#F09cc6;font-weight:700;}
                .IS_NULLABLE{color:#B31833;font-weight:700;}
                .red{color:red;}
                .tt{font-weight:700;font-size:19px;}
                .aaa{text-decoration:none;color:#000;}
                a:hover{background-color:yellow;}
            </style>
            <body>';
        echo '<h1 style="text-align:center;">' . $title . '</h1>';
        echo '<div id="dg" style="z-index: 9999; position: fixed ! important; left:0; top:1%;bottom:0;overflow:auto;font-size:11px;">
               '.$daohang.'</ol>
                </div>  ';
        echo $html;
        echo '</body></html>';
        exit();
    }
}