<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Module\user;

class UserinfoController extends Controller
{
    private $appId='wx30e4351e3fab2266';
    private $appSecret='efccfa22b12b1dbf63381e7ebbe81a87';

    private $handle;

    public function __construct()
    {
        $this->handle=new User();
    }

    /**
     * 用户信息列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function info_list(Request $request){
        $page=$request->get('page');
        $limit=$request->get('limit');
        $search=$request->get('search');
        $data=$this->handle->info_list($page,$limit,$search);
        foreach($data as $value){
            $value->mistake=$this->handle->mistake($value->id);
            $value->unit_score=$this->handle->unit_score($value->id);
        }
        $total=$this->handle->info_total($search);
        return response()->json([
            'data'=>$data,
            'total'=>$total
        ]);
    }

//    public function info_detail(Request $request){
//        $id=$request->get('id');
//        $data=$this->handle->info_detail($id);
//
//        return response()->json([
//           'data'=>$data
//        ]);
//    }

    public function wx_login(Request $post){
        $dataGet=$post->all();
        $res=$this->handle->wxlogin($dataGet['code'],$dataGet['encryptedData'],$dataGet['iv'],$this->appId,$this->appSecret);
        return response()->json([
            'data'=>$res
        ]);
    }

    public function info(Request $request){
        $token=$request->get('token');
        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $data=$this->handle->info_user($openid);
        return response()->json([
            'data'=>$data
        ]);

    }

    public function info_edit(Request $request){
        $data=$request->all();
        $openid=$this->handle->token_check($data['token']);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $msg=$this->handle->info_edit($data,$openid);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    public function login_check(Request $request){
        $token=$request->get('token');
        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $msg=$this->handle->login_check($openid);
        if($msg){
            return response()->json([
                'msg'=>'true'
            ]);
        }else{
            return response()->json([
                'msg'=>'false'
            ]);
        }
    }

    public function sign(Request $request){
        $token=$request->get('token');
        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $signCheck=$this->handle->sign_check($openid);
        if(!$signCheck){
            return response()->json([
                'msg'=>'本日已签到'
            ]);
        }
        $msg=$this->handle->sign($openid);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    public function signGet(Request $request){
        $token=$request->get('token');
        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $sign=$this->handle->signGet($openid);
        $has_sign=$this->handle->has_sign($openid);
        $point=$this->handle->pointGet($openid);
      //  dump($sign);
       return response()->json([
           'sign'=>$sign,
           'day'=>$has_sign,
           'point'=>$point
       ]);

    }

    public function mistake_list(Request $request){
        $token=$request->get('token');
        $page=$request->get('page');
        $limit=$request->get('limit');
        $state=$request->get('state');
        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $uid=$this->handle->uidGet($openid);
        $mistake=$this->handle->mistake_user($uid,$page,$limit,$state);
        $total=$this->handle->mistake_user_total($uid,$state);
        return response()->json([
            'data'=>$mistake,
            'total'=>$total
        ]);
    }

    public function mistake_del(Request $request){
        $token=$request->get('token');
        $id=$request->get('id');
        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $uid=$this->handle->uidGet($openid);
        $msg=$this->handle->mistake_del($id,$uid);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    /**
     * 词查找
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mistake_find(Request $request){
        $id=$request->get('id');
        $data= $this->handle->mistake_find($id);
        return response()->json([
            'data'=>$data
        ]);
    }

    public function mistake_add(Request $request){
        $token=$request->get('token');
        $mistake=$request->get('mistake');
        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $uid=$this->handle->uidGet($openid);
        $msg=$this->handle->mistake_add($uid,$mistake);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    public function user_mistake_add(Request $request){
        $token=$request->get('token');
        $id=$request->get('id');
        $type=$request->get('type');
        $now=date('Y-m-d H:i:s');
        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $uid=$this->handle->uidGet($openid);
        $msg=$this->handle->user_mistake_add($id,$type,$uid,$now);
        return response()->json([
           'msg'=>$msg
        ]);
    }
}
