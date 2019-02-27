<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/4 0004
 * Time: 下午 4:13
 */

namespace App\Module\Userinfo;


use Illuminate\Support\Facades\DB;

trait UserinfoHandle
{
    public function info_list($page,$limit,$search){
        $data=DB::table('userinfo')
            ->where(function ($query) use ($search) {
                if (!empty($search)) {
                    $query->where('phone', '=', '%' . $search . '%')
                        ->orWhere('nickname', 'like', '%' . $search . '%')
                        ->orWhere('address', 'like', '%' . $search . '%');
                }
            })
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->select('id','pic','nickname','sex','grade','id_card','phone','address','sign','point','identity')
            ->get();
        foreach($data as $value){
            $value->sex=$value->sex==1?'男':'女';
        }
        return $data;
    }

    public function info_total($search){
        $total=DB::table('userinfo')
            ->where(function ($query) use ($search) {
                if (!empty($search)) {
                    $query->where('phone', '=', '%' . $search . '%') ->orWhere('nickname', 'like', '%' . $search . '%');
                }
            })
            ->count();
        return $total;
    }

    public function mistake($id){
        $data=DB::table('mistake')
            ->where('uid',$id)
            ->select('words','date','id')
            ->get();

        return $data;
    }

    public function mistake_user($id,$page,$limit,$state){
        $data=DB::table('mistake')
            ->where('uid',$id)
            ->where('state',$state)
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->select('words','date','id')
            ->get();
         foreach($data as $value){
             $value->checked=false;
         }
        return $data;
    }

    public function mistake_user_total($id,$state){
        $data=DB::table('mistake')
            ->where('uid',$id)
            ->where('state',$state)
            ->count();

        return $data;
    }

    public function unit_score($id){
        $data=DB::table('unit_score')
            ->leftJoin('book_system','unit_score.sid','=','book_system.id')
            ->leftJoin('book_title','unit_score.tid','=','book_title.id')
            ->where('unit_score.uid','=',$id)
            ->orderby('book_title.id','ASC')
            ->orderby('book_system.id','ASC')
            ->select('unit_score.score','book_title.name','book_system.unit','unit_score.re_time','unit_score.shortest_time')
            ->get();
        return $data;
    }

    public function info_user($openid){
        $data=DB::table('userinfo')
            ->where('openid',$openid)
            ->select('pic','nickname','grade','phone','sex','address',
                'sign','point','id_card','name','identity')
            ->get();
        $data=$data[0];
        $data->gid=DB::table('grade')->where('grade',$data->grade)->value('id');
        $data->sex=$data->sex==1?'男':'女';
        return $data;
    }

    public function info_edit($data,$openid){
        if(isset($data['id_card']) && !empty($data['id_card'])) {
            $res = DB::table('userinfo')
                ->where('openid', $openid)
                ->update([
                    'pic' => $data['pic'], 'nickname' => $data['nickname'], 'grade' => $data['grade']
                    , 'phone' => $data['phone'], 'sex' => $data['sex'], 'address' => $data['address'],
                    'id_card' => $data['id_card'], 'name' => $data['name'],'identity'=>$data['identity']
                ]);
        }else{
            $res = DB::table('userinfo')
                ->where('openid', $openid)
                ->update([
                    'pic' => $data['pic'], 'nickname' => $data['nickname'], 'grade' => $data['grade']
                    , 'phone' => $data['phone'], 'sex' => $data['sex'], 'address' => $data['address'],
                    'name' => $data['name'],'identity'=>$data['identity']
                ]);
        }
        $msg='success';
        return $msg;
    }

    public function token_check($token){
        $openid=DB::table('midware')->where('token',$token)->value('openid');
        if($openid){
            return $openid;
        }else{
            return false;
        }
    }

    public function uidGet($openid){
        $uid=DB::table('userinfo')->where('openid',$openid)->value('id');
        return $uid;
    }

    public function login_check($openid){
        $grade=DB::table('userinfo')->where('openid',$openid)->value('grade');
        if($grade){
            return true;
        }else{
            return false;
        }
    }

    public function mistake_add($uid,$mistake){
        $now=date('Y-m-d H:i:s');
        $res=DB::table('mistake')->insert([
            'uid'=>$uid,'words'=>$mistake,'date'=>$now,'state'=>2
        ]);
        $msg=$res==true?'success':'fail';
        return $msg;
    }

    public function mistake_find($id){
        $mistake=DB::table('mistake')
            ->where('id',$id)
            ->value('words');
        $sid=DB::table('mistake')
            ->where('id',$id)
            ->value('sid');

        $unit=DB::table('book_system')->where('id',$sid)->value('unit');
        $tid=DB::table('book_system')->where('id',$sid)->value('tid');
        $title=DB::table('book_title')->where('id',$tid)->value('name');

        $data = DB::table('book_question')
            ->where('sid',$sid)
            ->where('word', 'like', '%' . $mistake . '%')
            ->select('place', 'example', 'trans','w_trans','word','id','type')
            ->first();
        if(!$data){
            return false;
        }

        $data->place=$title.$unit.$data->place;
        return $data;
    }

    public function pointGet($openid){
        $point=DB::table('userinfo')
            ->where('openid',$openid)
            ->value('point');
        return $point;
    }

    public function user_mistake_add($id,$type,$uid,$now){

        $sid=DB::table('book_question')
            ->where('id',$id)
            ->value('sid');
        $words=DB::table('book_question')
            ->where('id',$id)
            ->value('word');

        DB::table('mistake')->insert([
            'uid'=>$uid,'words'=>$words,'sid'=>$sid,'type'=>$type,'date'=>$now,'state'=>2
        ]);
        return 'success';
    }

    public function user_mistake_del($id){
        if(is_array($id)&&sizeof($id)==1){
            $id=$id[0];
            DB::table('mistake')->where('id',$id)->delete();
        }else{
            DB::table('mistake')->whereIn('id',$id)->delete();
        }
       return true;
    }
}