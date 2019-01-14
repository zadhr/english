<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18 0018
 * Time: 下午 4:43
 */

namespace App\Module\Rank;


use Illuminate\Support\Facades\DB;

trait RankHandle
{
    public function Rank($tid){
        $data=DB::table('total_score')
            ->leftJoin('userinfo','userinfo.id','=','total_score.uid')
            ->where('total_score.tid',$tid)
            ->orderby('total_score.total_score','DESC')
            ->orderby('total_score.ave_time','ASC')
            ->limit(10)
            ->select('total_score.total_score','userinfo.nickname','userinfo.pic',
                'total_score.ave_time')
            ->get();
        foreach($data as $key=>$value){
            $value->no=$key+1;
            $value->ave_time=round($value->ave_time);
        }
        return $data;
    }

    public function rankGradeGet(){
        $res=DB::table('book_title')
            ->orderby('gid','ASC')
            ->orderby('id','ASC')
            ->select('id','name')
            ->get();
        $task=DB::table('task')
            ->select('id')
            ->get();
        foreach($res as $key=>$value){
            $data[$key]['id']=$value->id;
            $data[$key]['name']=$value->name;
        }
        if(isset($task[0])){
            $str=array("name"=>'特殊任务');

            array_push($data,$str);
        }
        return $data;
    }

    public function rankTask($kid){
        $data=DB::table('task_score')
            ->leftJoin('userinfo','userinfo.id','=','task_score.uid')
            ->where('task_score.kid',$kid)
            ->orderby('task_score.score','DESC')
            ->orderby('task_score.re_time','ASC')
            ->limit(10)
            ->select('task_score.score','userinfo.nickname','userinfo.pic',
                'task_score.re_time')
            ->get();
        foreach($data as $key=>$value){
            $value->no=$key+1;
            $value->ave_time=$value->re_time;
        }
        return $data;
    }
}