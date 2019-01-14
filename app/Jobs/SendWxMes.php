<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Redis;

class SendWxMes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $token;
    protected $url;
    protected $name;
    protected $start;
    protected $end;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($token,$url,$name,$start,$end)
    {
       $this->token=$token;
        $this->url=$url;
        $this->name=$name;
        $this->start=$start;
        $this->end=$end;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $keys=redis::command('keys',['*']);
        $len=sizeof($keys);
        $now=time();
        for($i=0;$i<$len;$i++){
           $data=json_decode(redis::lpop($keys[$i]));
            $formId=$data->FormId;
            $create_time=$data->create_time;
            $time=$now-$create_time;
            if(empty($formId)){
                return;
            }else{
                if($time>604800){
                    do{
                        $data=json_decode(redis::lpop($keys[$i]));
                        $formId=$data->FormId;
                        if(empty($formId)){
                            return;
                        }
                        $create_time=$data->create_time;
                        $time=$now-$create_time;
                    }while($time>=604800);

                }
                if(empty($formId)){
                    return;
                }
                $data = [
                    'touser' => $keys[$i],
                    'template_id' => '446WL99D8YcPGpiRQxF9Q4A-ocs5vm3wryTqOwvfHBw',
                    'page' => $this->url,
                    'form_id' => $formId,
                    'data' =>
                        [
                            'keyword1' =>
                                [
                                    'value' => $this->name
                                ],
                            'keyword2' =>
                                [
                                    'value' => $this->start

                                ],

                            'keyword3' =>
                                [
                                    'value' => $this->end

                                ],
                            'keyword4' =>
                                [
                                    'value' => '单词新任务，快来挑战吧！'

                                ]
                        ]
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
