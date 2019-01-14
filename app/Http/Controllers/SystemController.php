<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Module\user;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class SystemController extends Controller
{
    private $appId='wx30e4351e3fab2266';
    private $appSecret='efccfa22b12b1dbf63381e7ebbe81a87';
    private $url='pages/index/index';
    private $radio=75;
    private $handle;

    public function __construct()
    {
        $this->handle=new User();
    }

    public function upload(Request $post){
        if (!$post->hasFile('pic')){
            return response()->json([
                'msg'=>'空文件'
            ]);
        }
        $pic = $post->file('pic');
        if($pic->isValid()) {
            $data=$this->handle->upload($pic,$this->radio);
            return response()->json([
                'data'=>$data
            ]);
        }else{
            return response()->json([
                'msg'=>'fail'
            ]);
        }
    }

    public function sign_list(){
        $data=$this->handle->sign_list();
        return response()->json([
            'data'=>$data
        ]);
    }

//    public function sign_add(Request $request){
//        $start=$request->get('start');
//        $end=$request->get('end');
//        $rate=$request->get('rate');
//        $msg=$this->handle->sign_add($start,$end,$rate);
//        return response()->json([
//            'msg'=>$msg
//        ]);
//    }


    public function sign_edit(Request $request){
        $rate=$request->get('rate');
        $id=$request->get('id');
        $this->handle->sign_edit($id,$rate);
        return response()->json([
            'msg'=>'success'
        ]);
    }

//    public function sign_del(Request $request){
//        $id=$request->get('id');
//        $msg=$this->handle->sign_del($id);
//        return response()->json([
//           'msg'=>$msg
//        ]);
//    }

    public function pointGet_list(){
        $data=$this->handle->pointGet_list();
        return $data;
    }

    public function pointGet_edit(Request $request){
        $id=$request->get('id');
        $point=$request->get('point');
        $this->handle->pointGet_edit($id,$point);
        return response()->json([
           'msg'=>'success'
        ]);
    }

    public function figure_list(){
        $data=$this->handle->figure_list();
        return response()->json([
           'data'=>$data
        ]);
    }

    public function figure_add(Request $request){
        $pic=$request->get('pic');
        $msg=$this->handle->figure_add($pic);
        return response()->json([
           'msg'=>$msg
        ]);
    }

    public function figure_del(Request $request){
        $id=$request->get('id');
        $msg=$this->handle->figure_del($id);
        return response()->json([
           'msg'=>$msg
        ]);
    }

    public function figure(){
        $data=$this->handle->figure();
        return response()->json([
           'data'=>$data
        ]);
    }

    public function share(Request $request){
        $token=$request->get('token');
        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $check=$this->handle->share_check($openid);
        if(!$check){
            return response()->json([
               'msg'=>'本日已领分享积分'
            ]);
        }
        $msg=$this->handle->share($openid);
        return response()->json([
           'msg'=>$msg
        ]);
    }

    /**
     * 海报列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function postersList(){
        $data=$this->handle->postersList();
        return response()->json([
           'data'=>$data
        ]);
    }

    public function postersEdit(Request $request){
        $pic=$request->get('pic');
        $msg=$this->handle->postersEdit($pic);
        return response()->json([
           'msg'=>$msg
        ]);
    }

    /**
     * formid存储
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function FormIdSave(Request $request){
        $token=$request->get('token');
        $FormId=$request->get('FormId');
        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $msg=$this->handle->FromIdSave($openid,$FormId);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    /**
     * 特殊任务上线提醒
     * @return \Illuminate\Http\JsonResponse
     */
    public function MesSend(){
        $token=$this->handle->getToken($this->appId,$this->appSecret);
        $token=$token['access_token'];
        $check=$this->handle->MesCheck();
        if(!$check){
            return response()->json([
               'msg'=>'fail'
            ]);
        }

        $url=$this->url;
        $this->handle->MesSend($token,$url);
        return response()->json([
           'msg'=>'success'
        ]);
    }


    /**
     * 不常用用户上线提醒
     * @return \Illuminate\Http\JsonResponse
     */
    public function NoticeSend(){
         $token=$this->handle->getToken($this->appId,$this->appSecret);
        $token=$token['access_token'];
        $url=$this->url;
        $openid=$this->handle->noticeCheck();
        $this->handle->NoticeSend($token,$url,$openid);
        return response()->json([
            'msg'=>'success'
        ]);
    }



}
