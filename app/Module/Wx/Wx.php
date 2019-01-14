<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/10 0010
 * Time: 上午 9:39
 */

namespace App\Module\Wx;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Jobs\SendWxMes;
use App\Jobs\SendNoticeMes;

trait WxHandle
{
    /**
     * 微信登录
     * @param
     * @return \Illuminate\Http\JsonResponse
     */
    public function wxlogin($code,$encryptedData,$iv,$appId,$appSecret){
        $Getsessionkey='https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code';
        $url = sprintf($Getsessionkey, $appId, $appSecret, $code);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($output, JSON_UNESCAPED_UNICODE);
        if(isset($data->errcode)){
            return response()->json([
                'msg'=>'登录失败！'
            ]);
        }
        $openid=$data['openid'];
        $session_key=$data['session_key'];
        $secret_data=$this->decryptData($encryptedData,$iv,$appId,$session_key);
        if($secret_data==false){
            $msg=  '解密失败！';
            return $msg;

        }

        $secret_data=json_decode($secret_data);
        $res['nickname']=$secret_data->nickName;
        $res['sex']=$secret_data->gender;
        $res['pic']=$secret_data->avatarUrl;
        $check=$this->check($openid);
        $now=time();
        if(!$check) {

            DB::table('userinfo')->insert([
                'pic' => $res['pic'], 'nickname' => $res['nickname'], 'openid' => $openid,'sex'=>$res['sex'],'date'=>$now
            ]);
            $res['token'] = sha1('bihu' . $openid);
            DB::table('midware')->insert(['token' => $res['token'], 'openid' => $openid]);
        }else{
            $res['token']=DB::table('midware')->where('openid',$openid)->value('token');
            DB::table('userinfo')->where('id',$check)->update(['date'=>$now]);
        }
        $res['point']=DB::table('userinfo')->where('openid',$openid)->value('point');
        return $res;
    }

    private function check($openid){
        $id=DB::table('userinfo')->where('openid',$openid)->value('id');
        return $id;
    }

    /**
     * 数据解密
     * @param $encryptedData
     * @param $iv
     * @param $appid
     * @param $sessionKey
     * @return bool|string
     */
    private function decryptData($encryptedData, $iv,$appid, $sessionKey){
        if (strlen($sessionKey) != 24) {
            return false;
        }
        $aesKey=base64_decode($sessionKey);


        if (strlen($iv) != 24) {
            return false;
        }
        $aesIV=base64_decode($iv);

        $aesCipher=base64_decode($encryptedData);

        $result=openssl_decrypt($aesCipher,"AES-128-CBC",$aesKey,1,$aesIV);

        $dataObj=json_decode($result);
        if( $dataObj  == NULL )
        {
            return false;
        }
        if( $dataObj->watermark->appid != $appid )
        {
            return false;
        }
        $data = $result;
        return $data;
    }

    public function MesSend($token,$url){
        $data=DB::table('task')->select('start','end','name')->get();
        dispatch( new SendWxMes($token,$url,$data[0]->name,$data[0]->start,$data[0]->end))->onQueue('Mes');
    }

    public function NoticeSend($token,$url,$openid){
        dispatch(new SendNoticeMes($token,$url,$openid))->onQueue('Notice');
    }

   public function noticeCheck(){
       $data=array();
       $least=strtotime('-3 days',strtotime(date('Y-m-d')));
       $openid=DB::table('userinfo')
           ->where('date','<',$least)
           ->where('identity','=','学生')
           ->select('openid')
           ->get();
       foreach($openid as $key=>$value){
           $data[$key]=$value->openid;
       }
       return $data;
   }

    public function MesCheck(){
        $now=time();
        $start=strtotime(DB::table('task')->value('start'));
        $end=strtotime("+1 day",strtotime(DB::table('task')->value('end')));
        $state=DB::table('task')->value('state');
        if($now>=$start&&$now<$end&&$state==0){
            DB::table('task')->update(['state'=>1]);
            return true;
        }else{
            return false;
        }
    }

    public function FromIdSave($openid,$FormId){
        $time=time();
        $str=json_encode(array("FormId"=>$FormId,'create_time'=>$time));
        Redis::rpush($openid,$str);
        return 'success';
    }

    public function getToken($appId,$appSecret){
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';
        $url = sprintf($url, $appId, $appSecret);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        if ($output === FALSE) {
            return false;
        }
        curl_close($curl);
        $accessToken = json_decode($output, JSON_UNESCAPED_UNICODE);
        if(isset($accessToken['errcode'])){
            if($accessToken['errcode'] == -1){
                 $this->getToken($appId,$appSecret);
            }
        }
        return $accessToken;
    }
}