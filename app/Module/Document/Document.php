<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5 0005
 * Time: 上午 10:23
 */

namespace App\Module\Document;


use Illuminate\Support\Facades\DB;

trait DocumentHandle
{
    public function doc_app_list(){
        $data=DB::table('document')->select('id','title')->get();
        return $data;
    }

    public function doc_all_detail(){
        $data=DB::table('document')->get();
        return $data;
    }

    public function doc_app_detail($id){
        $data=DB::table('document')
            ->where('id',$id)
            ->select('title','content')
            ->get();
        $data=$data[0];
        return $data;
    }

    public function doc_add($title,$content){
        $res=DB::table('document')->insert([
            'title'=>$title,'content'=>$content
        ]);
        $msg=$res==true?'success':'fail';
        return $msg;
    }

    public function doc_edit($id,$title,$content){
        $res=DB::table('document')->where('id',$id)->update([
            'title'=>$title,'content'=>$content
        ]);
        $msg=$res==true?'success':'fail';
        return $msg;
    }

    public function doc_del($id){
        $res=DB::table('document')->where('id',$id)->delete();
        $msg=$res==true?'success':'fail';
        return $msg;
    }
}