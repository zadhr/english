<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Module\user;
use Illuminate\Support\Facades\DB;

class GiftController extends Controller
{
    private $handle;

    public function __construct()
    {
        $this->handle=new user();
    }

    public function gift_order(Request $request){
        $page=$request->get('page');
        $limit=$request->get('limit');
        $search=$request->get('search');
        $data=$this->handle->gift_order($page,$limit,$search);
        $total=$this->handle->gift_order_total($search);
        return response()->json([
            'data'=>$data,
            'total'=>$total
        ]);

    }

    public function gift_order_edit(Request $request){
        $id=$request->get('id');
        $no=$request->get('no');
        $this->handle->gift_order_edit($id,$no);
        return response()->json([
            'msg'=>'success'
        ]);
    }

    public function giftList(Request $request){
        $page=$request->get('page');
        $limit=$request->get('limit');
        $data=$this->handle->gift_list($page,$limit);
        $total=$this->handle->gift_total();
        return response()->json([
            'data'=>$data,
            'total'=>$total,
        ]);
    }

    public function gift_list(Request $request){
        $page=$request->get('page');
        $limit=$request->get('limit');
        $token=$request->get('token');
        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $data=$this->handle->gift_list($page,$limit);
        $total=$this->handle->gift_total();
        $point=$this->handle->pointGet($openid);
        return response()->json([
            'data'=>$data,
            'total'=>$total,
            'point'=>$point
        ]);
    }

    public function gift_detail(Request $request){
        $gid=$request->get('gid');
        $data=$this->handle->gift_detail($gid);
        return response()->json([
            'data'=>$data
        ]);
    }

    public function gift_add(Request $request){
        $pic=$request->get('pic');
        $num=$request->get('num');
        $point=$request->get('point');
        $introduce=$request->get('introduce');
        $name=$request->get('name');
        $msg=$this->handle->gift_add($pic,$num,$point,$introduce,$name);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    public function gift_edit(Request $request){
        $id=$request->get('id');
        $pic=$request->get('pic');
        $num=$request->get('num');
        $point=$request->get('point');
        $introduce=$request->get('introduce');
        $name=$request->get('name');
        $msg=$this->handle->gift_edit($id,$pic,$num,$point,$introduce,$name);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    public function gift_del(Request $request){
        $id=$request->get('id');
        $msg=$this->handle->gift_del($id);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    public function exchange(Request $request){
        $gid=$request->get('gid');
        $num=$request->get('num');
        $token=$request->get('token');
        $state=$request->get('state');
        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $uid=$this->handle->uidGet($openid);
        $check=$this->handle->point_check($gid,$uid,$num);
        if(!$check){
            return response()->json([
                'msg'=>'积分不够，无法兑换'
            ]);
        }

        $msg=$this->handle->exchange($gid,$uid,$num,$state);
        if(!$msg){
            return response()->json([
                'msg'=>'数量不足'
            ]);
        }
        return response()->json([
            'msg'=>$msg
        ]);

    }

    public function user_orders(Request $request){
        $token=$request->get('token');
        $status=$request->get('status');
        $page=$request->get('page');
        $limit=$request->get('limit');
        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $uid=$this->handle->uidGet($openid);
        $data=$this->handle->user_orders($uid,$status,$page,$limit);
        $total=$this->handle->user_order_total($uid,$status);
        return response()->json([
            'data'=>$data,
            'total'=>$total
        ]);
    }

    public function order_detail(Request $request){
        $oid=$request->get('oid');
        $data=$this->handle->order_detail($oid);
        return response()->json([
            'data'=>$data
        ]);
    }
}
