<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Module\user;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    private $handle;
    private $threshold=30;   //默认出题总数

    public function __construct()
    {
        $this->handle=new User();
    }

    public function book_title_list(Request $request){
        $page=$request->get('page');
        $limit=$request->get('limit');
        $search=$request->get('search');
        $data=$this->handle->book_title_list($page,$limit,$search);
        $total=$this->handle->book_title_total($search);
        return response()->json([
            'data'=>$data,
            'total'=>$total
        ]);
    }

    public function grade(){
        $data=$this->handle->grade();
        return response()->json([
            'data'=>$data
        ]);
    }

    public function grade_choice(){
        $data=$this->handle->grade_choice();
        return response()->json([
            'data'=>$data
        ]);
    }

    public function book_title_add(Request $request){
        $gid=$request->get('gid');
        $name=$request->get('name');
        $msg=$this->handle->book_title_add($gid,$name);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    public function book_title_edit(Request $request){
        $id=$request->get('id');
        $gid=$request->get('gid');
        $name=$request->get('name');
        $msg=$this->handle->book_title_edit($id,$gid,$name);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    public function book_title_del(Request $request){
        $id=$request->get('id');
        $msg=$this->handle->book_title_del($id);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    public function book_unit_list(Request $request){
        $tid=$request->get('tid');
        $data=$this->handle->book_unit_list($tid);
        foreach($data as $value){
            $value->choice_own=$this->handle->book_unit_question($value->tid,$value->id);
            $value->blank_own=$this->handle->book_unit_question($value->tid,$value->id);
        }
        return response()->json([
            'data'=>$data
        ]);
    }

    public function book_unit_all(){
        $data=$this->handle->book_unit_all();
        return response()->json([
            'data'=>$data
        ]);
    }

    public function book_unit_add(Request $request){
        $data=$request->all();
        $msg=$this->handle->book_unit_add($data['tid'],$data['unit'],$data['choice_limit'],$data['choice_num'],$data['blank_limit'],$data['blank_num']);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    public function book_unit_edit(Request $request){
        $data=$request->all();
        $msg=$this->handle->book_unit_edit(
            $data['id'],$data['unit'],$data['choice_limit'],$data['choice_num'],$data['blank_limit'],$data['blank_num']
        );
        return response()->json([
            'msg'=>$msg
        ]);
    }

    public function book_unit_del(Request $request){
        $id=$request->get('id');
        $msg=$this->handle->book_unit_del($id);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    public function question_list(Request $request){
        $tid=$request->get('tid');
        $sid=$request->get('sid');
        $page=$request->get('page');
        $limit=$request->get('limit');
        $data=$this->handle->question_list($tid,$sid,$page,$limit);
        $total=$this->handle->question_total($tid,$sid);
        return response()->json([
            'data'=>$data,
            'total'=>$total
        ]);
    }

    public function question_add(Request $request){
        $data=$request->all();
        $msg=$this->handle->question_add($data);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    public function question_edit(Request $request){
        $data=$request->all();
        $msg=$this->handle->question_edit($data);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    public function question_del(Request $request){
        $id=$request->get('id');
        $msg=$this->handle->question_del($id);
        return response()->json([
            'msg'=>$msg
        ]);
    }

//    public function blank_list(Request $request){
//        $tid=$request->get('tid');
//        $sid=$request->get('sid');
//        $page=$request->get('page');
//        $limit=$request->get('limit');
//        $data=$this->handle->blank_list($tid,$sid,$page,$limit);
//        $total=$this->handle->blank_total($tid,$sid);
//        return response()->json([
//            'data'=>$data,
//            'total'=>$total
//        ]);
//    }
//
//
//    public function blank_add(Request $request){
//        $data=$request->all();
//        $msg=$this->handle->blank_add($data);
//        return response()->json([
//            'msg'=>$msg
//        ]);
//    }
//
//    public function blank_edit(Request $request){
//        $data=$request->all();
//        $msg=$this->handle->blank_edit($data);
//        return response()->json([
//            'msg'=>$msg
//        ]);
//    }
//
//    public function blank_del(Request $request){
//        $id=$request->get('id');
//        $msg=$this->handle->blank_del($id);
//        return response()->json([
//            'msg'=>$msg
//        ]);
//    }

    public function task_list(Request $request){
        $page=$request->get('page');
        $limit=$request->get('limit');
        $data=$this->handle->task_list($page,$limit);
        $total=$this->handle->task_total();
        return response()->json([
            'data'=>$data,
            'total'=>$total
        ]);
    }

    public function task_add(Request $request){
        $data=$request->all();
        $msg=$this->handle->task_add($data);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    public function task_del(Request $request){
        $id=$request->get('id');
        $msg=$this->handle->task_del($id);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    /**
     * 随机出题
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function random(Request $request){
        $sid=$request->get('sid');
        $token=$request->get('token');
        $type=$request->get('type');
        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $uid=$this->handle->uidGet($openid);
        $qCheck=$this->handle->questionCheck($sid,$type);
        $numCheck=$this->handle->numCheck($sid,$type);
        if(!$qCheck || !$numCheck){
            return response()->json([
                'msg'=>'所选单元缺题！'
            ]);
        }
        if(is_array($sid)){
            $this->handle->mlistClear($uid,$sid,$type);
            if($type==1){
                $total=$this->handle->choice_array_total($sid,$this->threshold);
            }else{
                $total=$this->handle->blank_array_total($sid,$this->threshold);
            }
        }else {
            $this->handle->mlistClear($uid, $sid, $type);
            if ($type == 1) {
                $total = $this->handle->choice_question_total($sid);
            } else {
                $total = $this->handle->blank_question_total($sid);
            }
        }
        $check=$this->handle->unit_check($uid,$sid,$type);
        if(!$check){
            $this->handle->unit_mark_start($uid,$sid,$type);
        }


        $msg=$this->handle->random($type,$sid,$total,$uid);
        return response()->json([
            'msg'=>$msg,
            'total'=>$total
        ]);
    }

    /**
     * 题目获得
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function questionGet(Request $request){
        $sid=$request->get('sid');
        $token=$request->get('token');
        $type=$request->get('type');
        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $uid=$this->handle->uidGet($openid);

        $question=$this->handle->questionGet($sid,$uid,$type);

        return response()->json([
            'data'=>$question,
        ]);
    }

    /**
     * 结束
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function finish(Request $request){
        $token=$request->get('token');
        $sid=$request->get('sid');
        $type=$request->get('type');
        $answer=$request->get('answer');
//        $answer[0]['id']=1;
//        $answer[0]['answer']='success';
//        $answer[1]['id']=2;
//        $answer[1]['answer']='fail';
        $now=time();

        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $uid=$this->handle->uidGet($openid);
        $len=sizeof($answer);

        for ($i = 0; $i < $len; $i++) {
            $answerSid=$this->handle->sidGet($answer[$i]['id'],$type);
            $check = $this->handle->answer_check($answer[$i]['id'], $answer[$i]['answer'], $type);

            if ($check) {
                $this->handle->correctAnswerUpdate( $uid, $type,$sid);
            } else {
                $this->handle->mistakeSet($answer[$i]['id'], $uid, $type,$answerSid,$sid);
            }

        }
        $this->handle->unit_mark_end($uid, $sid, $type, $now);

        $this->handle->scoreSet($uid, $sid, $type);

        //排行榜
        if(!is_array($sid)){
            $tid=$this->handle->tidGet($sid);
            $msg=$this->handle->unitDone($uid,$tid);
            if($msg){
                $this->handle->total_score($uid,$tid);
            }
        }


        $data['mistake'] = $this->handle->mistakeGet($uid, $sid, $type);

        $data['score'] = $this->handle->scoreGet($uid, $sid, $type);

        $data['re_time'] = $this->handle->re_timeGet($uid, $sid, $type);
        $data['shortest_time'] = $this->handle->shortest_timeGet($uid, $sid, $type);
        $data['time'] = $this->handle->timeGet($uid, $sid, $now, $type);

        return response()->json([
            'data'=>$data
        ]);
    }

    public function answerFind(Request $request){
        $id=$request->get('id');
        $type=$request->get('type');
        $data=$this->handle->mistakeFind($id,$type);
        return response()->json([
            'data'=>$data
        ]);
    }

    public function gradeGet(){
        $data=$this->handle->gradeGet();
        return response()->json([
            'data'=>$data
        ]);
    }

    public function bookGet(Request $request){
        $gid=$request->get('gid');
        $book=$this->handle->bookGet($gid);
        foreach ($book as $value){
            $value->unit=$this->handle->unitGet($value->id);
            foreach($value->unit as $val){
                $val->selected=false;
            }
        }
        return response()->json([
            'data'=>$book
        ]);
    }

    /**
     * 特殊任务随机出题
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskRandom(Request $request){
        $token=$request->get('token');
        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $uid=$this->handle->uidGet($openid);
        $check=$this->handle->taskCheck();
        if(!$check){
            return response()->json([
                'msg'=>'任务未开始'
            ]);
        }
        $sid=$this->handle->taskSidGet();
        $sid=json_decode($sid);
        $kid=$this->handle->taskKidGet();
        $type=$this->handle->taskTypeGet();

        $donecheck=$this->handle->taskDoneCheck($uid,$kid);
        $total=$this->handle->taskTotalGet($kid);

        if(!$donecheck){
            $this->handle->taskStart($uid,$kid);
        }else{
            $this->handle->m_tlistClear($uid,$kid);
        }

        $this->handle->taskRandom($type,$sid,$total,$uid,$kid);
        return response()->json([
            'msg'=>'success',
            'total'=>$total,
            'type'=>$type
        ]);
    }

    /**
     * 特殊任务题目获得
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskQuestionGet(Request $request){
        $token=$request->get('token');
        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $uid=$this->handle->uidGet($openid);
        $kid=$this->handle->taskKidGet();
        $type=$this->handle->taskTypeGet();
        $question=$this->handle->taskGet($uid,$kid,$type);
        return response()->json([
            'data'=>$question
        ]);
    }

    /**
     * 任务结束
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskFinish(Request $request){
        $token=$request->get('token');
        $answer=$request->get('answer');
        $now=time();
        $openid=$this->handle->token_check($token);
        if(!$openid){
            return response()->json([
                'msg'=>'登录验证失败'
            ]);
        }
        $uid=$this->handle->uidGet($openid);
        $type=$this->handle->taskTypeGet();
        $kid=$this->handle->taskKidGet();
//        $sid=$this->handle->tasksidGet();

        $len=sizeof($answer);
        for($i=0;$i<$len;$i++){
            $sid=$this->handle->sidGet($answer[$i]['id'],$type);
            $check=$this->handle->answer_check($answer[$i]['id'], $answer[$i]['answer'],$type);
            if($check){
                $this->handle->taskCorrect($uid,$kid);
            }else{
                $this->handle->taskMistakeAdd($answer[$i]['id'],$uid,$kid,$sid,$type);
            }
        }
        $this->handle->taskEnd($uid,$kid,$now);

        $this->handle->taskScoreSet($uid,$kid);

        $data['mistake'] = $this->handle->taskMistakeGet($uid,$kid);
        $data['score'] = $this->handle->taskScoreGet($uid, $kid);
        $data['re_time'] = $this->handle->taskRetimeGet($uid, $kid);
        $data['shortest_time'] = $this->handle->taskShortestTimeGet($uid, $kid);
        $data['time'] = $this->handle->taskTimeGet($uid, $kid, $now);

        return response()->json([
            'data'=>$data
        ]);
    }

    public function taskCheck(){
        $msg=$this->handle->taskCheck();
        return response()->json([
           'msg'=>$msg
        ]);
    }
}

