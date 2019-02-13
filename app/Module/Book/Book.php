<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/6 0006
 * Time: 上午 11:35
 */

namespace App\Module\Book;




use Illuminate\Support\Facades\DB;

trait BookHandle
{
    public function book_title_list($page,$limit,$search){
        $data=DB::table('book_title')
            ->leftJoin('grade','book_title.gid','=','grade.id')
            ->where(function ($query) use ($search){
                if(!empty($search)){
                    $query->where('grade.grade','like','%'.$search.'%');
                }
            })
            ->orderby('grade.id','ASC')
            ->orderby('book_title.id','ASC')
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->select('book_title.id','book_title.name','grade.grade')
            ->get();
        foreach($data as $value){
            $value->gid=DB::table('grade')
                ->where('grade',$value->grade)
                ->value('id');
        }

        return $data;
    }

    public function book_title_total($search){
        $gid='';
        if(!empty($search)){
            $gid=DB::table('grade')
                ->where('grade','like','%'.$search.'%')
                ->pluck('id');

        }
        $total=DB::table('book_title')
            ->where(function ($query) use ($gid){
                if(!empty($gid)){
                    $query->whereIn('gid',$gid);
                }
            })
            ->count();
        return $total;
    }

    public function grade(){
        $data=DB::table('grade')->get();
        return $data;
    }

    public function grade_choice(){
        $data=DB::table('grade')->select('grade')->get();
        foreach ($data as $key=>$value){
            $dataArray[$key]=$value->grade;
        }
        $string='请选择年级';
        array_unshift($dataArray,$string);

        return $dataArray;
    }

    public function book_title_add($gid,$name){
        $res=DB::table('book_title')->insert([
            'gid'=>$gid,'name'=>$name
        ]);
        $msg=$res==true?'success':'fail';
        return $msg;
    }

    public function book_title_edit($id,$gid,$name){
        $res=DB::table('book_title')
            ->where('id',$id)
            ->update([
                'gid'=>$gid,'name'=>$name
            ]);
        $msg='success';
        return $msg;
    }

    public function book_title_del($id){
        $res=DB::table('book_title')
            ->where('id',$id)
            ->delete();
        $res1=DB::table('book_system')
            ->where('tid',$id)
            ->delete();
        $msg='success';
        return $msg;
    }

    public function book_unit_all(){
        $res=array();
        $data=DB::table('book_system')
            ->leftJoin('book_title','book_title.id','=','book_system.tid')
            ->orderby('book_title.gid','ASC')
            ->orderby('book_title.name')
            ->orderby('book_system.id')
            ->select('book_title.name','book_system.unit','book_system.id')
            ->get();
        $i=0;
        foreach($data as $value){
            $res[$i]['key']=$value->id;
            $res[$i]['label']=$value->name.$value->unit;

            $i++;
        }
        return $res;
    }

    public function book_unit_list($tid){
        $data=DB::table('book_system')
            ->where('tid',$tid)
            ->get();
        return $data;
    }

    public function book_unit_question($tid,$sid){
        $count=DB::table('book_question')
            ->where('tid',$tid)
            ->where('sid',$sid)
            ->count();
        return $count;
    }

    public function book_unit_add($tid,$unit,$choice_limit,$choice_num,$blank_limit,$blank_num){
        $res=DB::table('book_system')
            ->insert([
                'tid'=>$tid,'unit'=>$unit,'choice_limit'=>$choice_limit,'choice_num'=>$choice_num,
                'blank_limit'=>$blank_limit,'blank_num'=>$blank_num
            ]);
        $msg=$res==true?'success':'fail';
        return $msg;
    }

    public function book_unit_edit($id,$unit,$choice_limit,$choice_num,$blank_limit,$blank_num){
        $res=DB::table('book_system')
            ->where('id',$id)
            ->update([
                'unit'=>$unit,'choice_limit'=>$choice_limit,'choice_num'=>$choice_num,
                'blank_limit'=>$blank_limit,'blank_num'=>$blank_num
            ]);
        return 'success';
    }

    public function book_unit_del($id){
        $res=DB::table('book_system')
            ->where('id',$id)
            ->delete();
        $msg=$res==true?'success':'fail';
        return $msg;
    }

    public function unitGet($tid){
        $unit=DB::table('book_system')
            ->where('tid',$tid)
            ->get();

        return $unit;
    }

    public function bookGet($gid){
        $book=DB::table('book_title')
            ->where(function ($query) use ($gid){
                if(!empty($gid)) {
                    $query->where('gid', $gid);
                }
            })
            ->orderby('gid','ASC')
            ->select('id','name')

            ->get();
        return $book;
    }

    public function gradeGet(){
        $data=DB::table('grade')->get();
        return $data;
    }
}