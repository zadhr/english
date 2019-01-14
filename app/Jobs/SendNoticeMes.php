<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Redis;

class SendNoticeMes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $token;
    protected $url;
    protected $openid;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($token,$url,$openid)
    {
        $this->token=$token;
        $this->url=$url;
        $this->openid=$openid;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $keys=redis::command('keys',['*']);
        $len=sizeof($this->openid);
        for($i=0;$i<$len;$i++){
            if(in_array($this->openid[$i],$keys)){
                $formId=json_decode(Redis::lpop($this->openid[$i]));
                $formId=$formId->FormId;
                if(empty($formId)){
                    return ;
                }
                $data = [
                    'touser' => $this->openid[$i],
                    'template_id' => '446WL99D8YcPGpiRQxF9Q88K2XXANMresIf3lZomnXc',
                    'page' => $this->url,
                    'form_id' => $formId,
                    'data' =>
                        [
                            'keyword1' =>
                                [
                                    'value' => '单词小测'
                                ],
                            'keyword2' =>
                                [
                                    'value' => '您已有三天没有进行单词测试了哟。'

                                ]
                        ],

                ];
                $json = json_encode($data, JSON_UNESCAPED_UNICODE);
                $sendUrl = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $this->token;
                $surl = $sendUrl;
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $surl);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
                if (!empty($json)) {
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
                }
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($curl);
//            if ($output === FALSE) {
//                return false;
//            }
                curl_close($curl);

            }
        }
    }
}
