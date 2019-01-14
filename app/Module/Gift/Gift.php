<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/4 0004
 * Time: 下午 5:22
 */

namespace App\Module\Gift;



use Illuminate\Support\Facades\DB;

trait GiftHandle
{
    public function gift_order($page,$limit,$search){
        $data=DB::table('gift_order')
            ->where(function ($query) use ($search) {
                if (!empty($search)) {
                    $query->where('address', 'like', '%' . $search . '%')
                        ->orWhere('nickname', 'like', '%' . $search . '%')
                        ->orwhere('no', 'like', '%' . $search . '%');
                }
            })
            ->orderby('id','DESC')
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->get();
        foreach($data as $value){
            $value->status=$value->status==2?'未发放':'已发放';
            $value->state=$value->state==1?'快递':'现场取';
        }
        return $data;
    }

    public function  gift_order_edit($id,$no){
        if($no) {
            DB::table('gift_order')
                ->where('id', $id)
                ->update([
                    'no' => $no, 'status' => 1
                ]);
        }else{
            DB::table('gift_order')
                ->where('id', $id)
                ->update([
                    'status' => 1
                ]);
        }
    }

    public function gift_order_total($search){
        $total=DB::table('gift_order')
            ->where(function ($query) use ($search) {
                if (!empty($search)) {
                    $query->where('address', 'like', '%' . $search . '%')
                        ->orWhere('nickname', 'like', '%' . $search . '%')
                        ->orwhere('no', 'like', '%' . $search . '%');
                }
            })
            ->count();
        return $total;
    }

    public function gift_list($page,$limit){
        $data=DB::table('gift')
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->get();
        return $data;
    }

    public function gift_detail($gid){
        $data=DB::table('gift')
            ->where('id',$gid)
            ->get();
        return $data;
    }

    public function gift_total(){
        $total=DB::table('gift')->count();
        return $total;
    }

    public function gift_add($pic,$num,$point,$introduce,$name){
        $res=DB::table('gift')->insert([
            'pic'=>$pic,'num'=>$num,'point'=>$point,
            'introduce'=>$introduce,'name'=>$name
        ]);
        $msg=$res==true?'success':'fail';
        return $msg;
    }

    public function gift_edit($id,$pic,$num,$point,$introduce,$name){

        $res=DB::table('gift')
            ->where('id',$id)
            ->update([
                'pic'=>$pic,'num'=>$num,'point'=>$point,
                'introduce'=>$introduce,'name'=>$name
            ]);
        $msg=$res==true?'success':'fail';
        return $msg;
    }

    public function gift_del($id){
        $res=DB::table('gift')
            ->where('id',$id)
            ->delete();
        $msg=$res==true?'success':'fail';
        return $msg;
    }

    public function user_orders($uid,$status,$page,$limit){
        $data=DB::table('gift_order')
            ->where('uid',$uid)
            ->where('status',$status)
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->select('nickname','name','gift','point','address','status','state','no','num','phone','pic','id')
            ->get();
        foreach($data as $value){
            $value->state=$value->state==1?'快递':'现场取';
            $value->status=$value->status==2?'未发放':'已发放';
        }
        return $data;
    }

    public function user_order_total($uid,$status){
        $total=DB::table('gift_order')
            ->where('uid',$uid)
            ->where('status',$status)
            ->count();
        return $total;
    }

    public function exchange($gid,$uid,$num,$state){
        $g_num=DB::table('gift')->where('id',$gid)->value('num');
        $g_num=$g_num-$num;
        $now=date('Y-m-d H:i:s');
        if($g_num<0){
            return false;
        }else{
            DB::table('gift')->where('id',$gid)->update(['num'=>$g_num]);
        }

        $g_point=DB::table('gift')
            ->where('id',$gid)
            ->value('point');
        $total=$g_point*$num;
        $u_point=DB::table('userinfo')
            ->where('id',$uid)
            ->value('point');
        $u_point=$u_point-$total;

        DB::table('userinfo')
            ->where('id',$uid)
            ->update(['point'=>$u_point]);
        $gift=DB::table('gift')->where('id',$gid)->value('name');
        $userinfo=DB::table('userinfo')
            ->where('id',$uid)
            ->select('nickname','name','address','phone')
            ->get();
        $userinfo=$userinfo[0];
        $pic=DB::table('gift')->where('id',$gid)->value('pic');
        DB::table('gift_order')->insert([
            'point'=>$g_point,'uid'=>$uid,'num'=>$num,'state'=>$state,
            'gift'=>$gift,'nickname'=>$userinfo->nickname,'address'=>$userinfo->address,
            'name'=>$userinfo->name,'phone'=>$userinfo->phone,'pic'=>$pic,'date'=>$now

        ]);

        return 'success';
    }

    public function point_check($gid,$uid,$num){
        $g_point=DB::table('gift')
            ->where('id',$gid)
            ->value('point');
        $total=$g_point*$num;
        $u_point=DB::table('userinfo')
            ->where('id',$uid)
            ->value('point');
        if($u_point>=$total){
            return true;
        }else{
            return false;
        }
    }

    public function order_detail($oid){
        $data=DB::table('gift_order')
            ->where('id',$oid)
            ->select('id','nickname','gift','point','address','name','state',
                'status','no','num','phone','pic','date')
            ->get();
        $data=$data[0];
        $data->state=$data->state==1?'快递':'现场取';
        $data->status=$data->status==2?'未发放':'已发放';
        return $data;
    }
}