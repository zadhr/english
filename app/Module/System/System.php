<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/4 0004
 * Time: 下午 4:50
 */

namespace  App\Module\System;


use Cron\DayOfMonthField;
use Illuminate\Support\Facades\DB;

trait SystemHandle
{
    public function upload($file,$radio){
        $name=uniqid();
        $ext=$file->getClientOriginalExtension();
        $allow =  [
            'jpg',
            'png',
            'jpeg',
        ];
        if (!in_array(strtolower($ext),$allow)){
            $msg='不支持的文件格式';
            return $msg;

        }
        $ext=strtolower($ext);
        $name=$name.'.'.$ext;
        $path = $file->storeAs('pic', $name);
        $path1='../public/uploads/'.$path;
        $path=ImageCompression($path1,$ext,$radio);
        unlink($path1);
        return $path;

    }

    public function sign_list(){
        $data=DB::table('sign_in')
            ->orderby('start','ASC')
            ->get();
        return $data;
    }

    public function sign_add($start,$end,$rate){
        $check=DB::table('sign_in')
            ->where(function ($query) use ($start){
                $query->where('start','<=',$start)->where('end','>=',$start);
            })
            ->orWhere(function ($query) use ($end){
                $query->where('start','<=',$end)->where('end','>=',$end);
            })
            ->value('id');
        if($check){
            $msg='fail';
            return $msg;
        }
        $res=DB::table('sign_in')->insert([
            'start'=>$start,'end'=>$end,'rate'=>$rate
        ]);
        $msg=$res==true?'success':'fail';
        return $msg;
    }

    public function sign_edit($id,$rate){
        DB::table('sign_in')
            ->where('id',$id)
            ->update([
                'rate'=>$rate
            ]);
    }

    public function sign_del($id){
        $res=DB::table('sign_in')
            ->where('id',$id)
            ->delete();
        $msg=$res==true?'success':'fail';
        return $msg;
    }

    public function sign($openid){
        $sign_token=DB::table('userinfo')
            ->where('openid',$openid)
            ->value('sign_token');
        $has_point=DB::table('userinfo')
            ->where('openid',$openid)
            ->value('point');
        $last=strtotime('-1 day',strtotime(date('Y-m-d')));
        if($last>$sign_token){
            $sign=0;
        }else{
            $sign=DB::table('userinfo')
                ->where('openid',$openid)
                ->value('sign');
        }
        if($sign<30){
            $sign++;
            $point=$this->calculation($sign);
        }else{
            $sign=30;
            $point=$this->calculation($sign);
        }
        $point=$point+$has_point;
        $sign_token=time();
        $res=DB::table('userinfo')->where('openid',$openid)->update([
            'point'=>$point,'sign'=>$sign,'sign_token'=>$sign_token
        ]);
        $msg=$res==true?'success':'fail';
        return $msg;

    }

    public function signGet($openid){
        $data=array();
        $rate=DB::table('sign_in')
            ->select('rate')
            ->get();
        $sign_token=DB::table('userinfo')
            ->where('openid',$openid)
            ->value('sign_token');
        $last=strtotime('-1 day',strtotime(date('Y-m-d')));
        if($last>$sign_token){
            $sign=0;
            DB::table('userinfo')
                ->where('openid',$openid)
                ->update(['sign'=>0]);
        }else{
            $sign=DB::table('userinfo')
                ->where('openid',$openid)
                ->value('sign');
        }
        for($i=1;$i<=30;$i++){
            $data[$i-1]['date']=$i;
            if($i>$sign) {
                $data[$i-1]['signin'] = false;
            }else{
                $data[$i-1]['signin'] = true;
            }
            if($i<=10) {
                $data[$i-1]['point'] = $i*$rate[0]->rate;
            }elseif($i>10 && $i<=20){
                $data[$i-1]['point'] =10*$rate[0]->rate + ($i-10)*$rate[1]->rate;
            }else{
                $data[$i-1]['point'] =10*$rate[0]->rate+10*$rate[1]->rate+($i-20)*$rate[2]->rate;
            }
        }
        return $data;
    }

    public function has_sign($openid){
        $sign_token=DB::table('userinfo')
            ->where('openid',$openid)
            ->value('sign_token');
        $sign=DB::table('userinfo')
            ->where('openid',$openid)
            ->value('sign');
        $last=strtotime('-1 day',strtotime(date('Y-m-d')));
        $now=strtotime(date('Y-m-d'));
//        dump($sign_token,$now,$last);
        if($last>$sign_token){
            $sign=1;
        }elseif($sign_token<$now && $sign_token>$last){
            $sign++;
        }

        return $sign;
    }

    private function calculation($sign){
        $swit=ceil($sign/10);
        $rate=DB::table('sign_in')->pluck('rate');
        switch ($swit){
            case 1:
                $point=$sign*$rate[0];
                break;
            case 2:
                $point=10*$rate[0]+($sign-10)*$rate[1];
                break;
            case 3:
                $point=10*$rate[0]+10*$rate[1]+($sign-20)*$rate[2];
                break;
            default;
                $point=0;
                break;
        }
        return $point;
    }

    public function sign_check($openid){
        $sign_token=DB::table('userinfo')->where('openid',$openid)->value('sign_token');
        $start=strtotime('+1 day',strtotime(date('Y-m-d',$sign_token)));
        $now=time();
        if($now<$start){
            return false;
        }else{
            return true;
        }
    }

    public function pointGet_list(){
        $data=DB::table('point_get')->get();

        return $data;
    }

    public function pointGet_edit($id,$point){
        DB::table('point_get')
            ->where('id',$id)
            ->update([
                'point'=>$point
            ]);

    }

    public function figure_list(){
        $data=DB::table('figure')->get();
        return $data;
    }

    public function figure_add($pic){
        $res=DB::table('figure')->insert(['pic'=>$pic]);
        $msg=$res==true?'success':'fail';
        return $msg;
    }

    public function figure_del($id){
        $res=DB::table('figure')->where('id',$id)->delete();
        $msg=$res==true?'success':'fail';
        return $msg;
    }

    public function figure(){
        $data=DB::table('figure')->pluck('pic');
        return $data;
    }

    public function share($openid){
        $point=DB::table('point_get')
            ->where('id',1)
            ->value('point');
        $u_point=DB::table('userinfo')
            ->where('openid',$openid)
            ->value('point');
        $u_point=$u_point+$point;
        $now=time();
        DB::table('userinfo')
            ->where('openid',$openid)
            ->update([
                'point'=>$u_point,'share_token'=>$now
            ]);
        return 'success';
    }

    public function share_check($openid){
        $share_token=DB::table('userinfo')
            ->where('openid',$openid)
            ->value('share_token');
        $now=strtotime(date('Y-m-d'));
        if($share_token<=$now){
            return true;
        }else{
            return false;
        }
    }

    public function postersList(){
         $data=DB::table('posters')
             ->select('id','pic')
             ->get();

        return $data;
    }

    public function postersEdit($pic){
         $res=DB::table('posters')
             ->where('id',1)
             ->update(['pic'=>$pic]);
        $msg=$res==true?'success':'fail';
        return $msg;
    }
}