<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/6 0006
 * Time: 下午 1:45
 */

namespace App\Module\Book;


use Illuminate\Support\Facades\DB;

trait QuestionHandle
{
    public function question_list($tid,$sid,$page,$limit){
        $data=DB::table('book_question')
            ->where('tid',$tid)
            ->where('sid',$sid)
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->select('id','word','choice1','choice2','choice3','choice4','place','example','trans','w_trans','type','answer')
            ->get();
        return $data;
    }

    public function question_total($tid,$sid){
        $total=DB::table('book_question')
            ->where('tid',$tid)
            ->where('sid',$sid)
            ->count();
        return $total;
    }

    public function question_add($data){
        $res=DB::table('book_question')
            ->insert($data);
        $msg=$res==true?'success':'fail';
        return $msg;
    }

    public function question_edit($data){
        $res=DB::table('book_question')
            ->where('id',$data['id'])
            ->update([
                'word'=>$data['word'],'choice1'=>$data['choice1'],'choice2'=>$data['choice2'],
                'choice3'=>$data['choice3'],'choice4'=>$data['choice4'],'place'=>$data['place'],'answer'=>$data['answer'],
                'example'=>$data['example'],'trans'=>$data['trans'],'w_trans'=>$data['w_trans'],'type'=>$data['type']
            ]);
        $msg=$res==true?'success':'fail';
        return $msg;
    }

    public function question_del($id){
        $res=DB::table('book_question')
            ->where('id',$id)
            ->delete();
        $msg=$res==true?'success':'fail';
        return $msg;
    }

    public function random($type,$sid,$total,$uid){

        if(is_array($sid)){
            $question=DB::table('book_question')
                ->whereIn('sid',$sid)
                ->orderbyRaw("Rand()")
                ->limit($total)
                ->pluck('id');
            $sid=json_encode($sid);
        }else{
            $question=DB::table('book_question')
                ->where('sid',$sid)
                ->orderbyRaw("Rand()")
                ->limit($total)
                ->pluck('id');
        }
        $question=json_encode($question);
//        dump($question);
        $check=DB::table('q_list')
            ->where('uid',$uid)
            ->where('sid',$sid)
            ->where('type',$type)
            ->value('id');
//        dump($check);
        if($check){
            DB::table('q_list')
                ->where('uid',$uid)
                ->where('sid',$sid)
                ->where('type',$type)
                ->update(['qid'=>$question]);
        }else{
            DB::table('q_list')
                ->where('uid',$uid)
                ->insert([
                    'qid'=>$question,'uid'=>$uid,'sid'=>$sid,'type'=>$type
                ]);

        }
        return 'success';
    }

    public function questionGet($sid,$uid,$type){
        if(!is_array($sid)) {
            $list = DB::table('q_list')
                ->where('uid', $uid)
                ->where('sid', $sid)
                ->where('type', $type)
                ->value('qid');
        }else{
            $sid=json_encode($sid);
            $list = DB::table('q_list')
                ->where('uid', $uid)
                ->where('sid', $sid)
                ->where('type', $type)
                ->value('qid');
        }
        $list=json_decode($list);
        $qid=array_pop($list);
        if(empty($qid)){
            return false;
        }
        $list=json_encode($list);
        DB::table('q_list')
            ->where('uid',$uid)
            ->where('sid',$sid)
            ->where('type',$type)
            ->update(['qid'=>$list]);
        if($type==1){
            $data=DB::table('book_question')
                ->leftJoin('book_system','book_question.sid','=','book_system.id')
                ->where('book_question.id',$qid)
                ->select('book_system.choice_limit','book_question.id','book_question.word',
                    'book_question.choice1','book_question.choice2','book_question.choice3',
                    'book_question.choice4')
                ->get();
            $data[0]->limit=$data[0]->choice_limit;
        }else{
            $data=DB::table('book_question')
                ->leftJoin('book_system','book_question.sid','=','book_system.id')
                ->where('book_question.id',$qid)
                ->select('book_system.blank_limit','book_question.id','book_question.w_trans')
                ->get();
            $data[0]->limit=$data[0]->blank_limit;
        }
        return $data;
    }

    public function choice_array_total($sid,$threshold){
        $total=DB::table('book_system')
            ->whereIn('id',$sid)
            ->sum('choice_num');
        $total=intval($total);
        $total=$total>=$threshold?$threshold:$total;
        return $total;
    }

    public function blank_array_total($sid,$threshold){
        $total=DB::table('book_system')
            ->whereIn('id',$sid)
            ->sum('blank_num');
        $total=intval($total);
        $total=$total>=$threshold?$threshold:$total;
        return $total;
    }

    public function choice_question_total($sid){
        $total=DB::table('book_system')
            ->where('id',$sid)
            ->value('choice_num');
        return $total;
    }

    public function blank_question_total($sid){
        $total=DB::table('book_system')
            ->where('id',$sid)
            ->value('blank_num');
        return $total;
    }

    public function choice_limit($sid){
        $limit=DB::table('book_system')
            ->where('id',$sid)
            ->value('choice_limit');
        return $limit;
    }

    public function blank_limit($sid){
        $limit=DB::table('book_system')
            ->where('id',$sid)
            ->value('blank_limit');
        return $limit;
    }

//    public function UserMistakeAdd($uid,$sid,$type,$words,$state){
//         $now=date('Y-m-d H:i:s');
//         $res=DB::table('mistake')->insert([
//             'uid'=>$uid,'sid'=>$sid,'type'=>$type,
//             'words'=>$words,'date'=>$now,'state'=>$state
//         ]);
//        $msg=$res==true?'success':'fail';
//        return $msg;
//    }

    public function unit_check($uid,$sid,$type){
        if(is_array($sid)){
            $sid=json_encode($sid);
            $check=DB::table('units_score')
                ->where('uid',$uid)
                ->where('sid',$sid)
                ->where('type',$type)
                ->value('id');


            if($check){
                $now=time();
                DB::table('units_score')
                    ->where('id',$check)
                    ->update(['score'=>0,'num'=>0,'start'=>$now]);

                DB::table('units_score')
                    ->where('id',$check)
                    ->increment('re_time');
                return true;
            }else{
                return false;
            }
        }else{
            $check=DB::table('unit_score')
                ->where('uid',$uid)
                ->where('sid',$sid)
                ->where('type',$type)
                ->value('id');


            if($check){
                $now=time();
                DB::table('unit_score')
                    ->where('id',$check)
                    ->update(['score'=>0,'num'=>0,'start'=>$now]);

                DB::table('unit_score')
                    ->where('id',$check)
                    ->increment('re_time');

                return true;
            }else{
                return false;
            }
        }


    }

    public function unit_mark_start($uid,$sid,$state){
        if(is_array($sid)){
            $sid=json_encode($sid);
            $now = time();
            $res = DB::table('units_score')
                ->insert([
                    'uid' => $uid, 'sid' => $sid, 'type' => $state, 'start' => $now
                ]);
        }else {
            $tid = DB::table('book_system')->where('id', $sid)->value('tid');
            $tid = intval($tid);
            $now = time();
            $res = DB::table('unit_score')
                ->insert([
                    'uid' => $uid, 'tid'=>$tid,'sid' => $sid, 'type' => $state, 'start' => $now
                ]);
        }
    }

    public function unitScoreClear($uid,$sid,$type){
         if(is_array($sid)){
             $sid=json_encode($sid);
         }
        DB::table('units_score')
            ->where('sid',$sid)
            ->where('uid',$uid)
            ->where('type',$type)
            ->update(['num'=>0]);
    }

    public function unit_mark_end($uid,$sid,$state,$now){
        if(is_array($sid)){
            $sid=json_encode($sid);
            $shortest_time=DB::table('units_score')
                ->where('uid',$uid)
                ->where('sid',$sid)
                ->where('type',$state)
                ->value('shortest_time');
            $start=DB::table('units_score')
                ->where('uid',$uid)
                ->where('sid',$sid)
                ->where('type',$state)
                ->value('start');
            $time=$now-$start;
            if($shortest_time==0 || $time<$shortest_time){

                DB::table('units_score')
                    ->where('uid',$uid)
                    ->where('sid',$sid)
                    ->where('type',$state)
                    ->update(['shortest_time'=>$time]);
            }
        }else{
            $shortest_time=DB::table('unit_score')
                ->where('uid',$uid)
                ->where('sid',$sid)
                ->where('type',$state)
                ->value('shortest_time');
            $start=DB::table('unit_score')
                ->where('uid',$uid)
                ->where('sid',$sid)
                ->where('type',$state)
                ->value('start');
            $time=$now-$start;
            if($shortest_time==0 || $time<$shortest_time){
                DB::table('unit_score')
                    ->where('uid',$uid)
                    ->where('sid',$sid)
                    ->where('type',$state)
                    ->update(['shortest_time'=>$time]);
            }
        }
    }

    /**
     * 对答案
     * @param $id
     * @param $answer
     * @param $type
     * @return bool
     */
    public function answer_check($id,$answer,$type){
        if($type==1) {
            $correct_answer = DB::table('book_question')
                ->where('id', $id)
                ->value('answer');
        }else{
            $correct_answer = DB::table('book_question')
                ->where('id', $id)
                ->value('word');
        }

        if($type==1){
            if($correct_answer==$answer){
                return true;
            }else{
                return false;
            }
        }else{
            if(strtolower($correct_answer)==strtolower($answer)){
                return true;
            }else{
                return false;
            }
        }


    }

    public function sidGet($id,$type){
        $sid=DB::table('book_question')->where('id',$id)->value('sid');
        return $sid;
    }

    public function mistakeSet($id,$uid,$type,$answerSid,$sid){
        $id=intval($id);

        $now=date('Y-m-d H:i:s');

        $words=DB::table('book_question')->where('id',$id)->value('word');
        if(is_array($sid)){
            $sid=json_encode($sid);
        }
        $is_check=DB::table('mistake')
            ->where('uid',$uid)
            ->where('sid',$answerSid)
            ->where('state',1)
            ->where('words',$words)
            ->value('id');
        if(!$is_check) {
            DB::table('mistake')->insert([
                'uid' => $uid, 'words' => $words, 'date' => $now, 'sid' => $answerSid, 'type' => $type
            ]);
        }
        $check=DB::table('m_list')
            ->where('uid',$uid)
            ->where('sid',$sid)
            ->where('type',$type)
            ->value('id');
        if($check){
            $qid=DB::table('m_list')
                ->where('id',$check)
                ->value('qid');
            $qid=json_decode($qid);
            array_push($qid,$id);
            $qid=json_encode($qid);
            DB::table('m_list')
                ->where('id',$check)
                ->update(['qid'=>$qid]);
        }else{
            $id=json_encode(array($id));
            DB::table('m_list')->insert([
                'uid'=>$uid,'sid'=>$sid,'qid'=>$id,'type'=>$type
            ]);
        }
        return 'success';
    }

    /**
     * 清除用户答题表
     * @param $uid
     * @param $sid
     * @param $type
     */
    public function mlistClear($uid,$sid,$type){
        if(is_array($sid)){
            $sid=json_encode($sid);
        }
        $check=DB::table('m_list')
            ->where('uid',$uid)
            ->where('sid',$sid)
            ->where('type',$type)
            ->value('id');
        if($check){
            DB::table('m_list')
                ->where('id',$check)
                ->update(['qid'=>'[]']);

        }
    }

    public function scoreSet($uid,$sid,$type){
        if(is_array($sid)){
            if ($type == 1) {
                $total = DB::table('book_system')
                    ->whereIn('id', $sid)
                    ->sum('choice_num');
            } else {
                $total = DB::table('book_system')
                    ->whereIn('id', $sid)
                    ->sum('blank_num');
            }
            $sid=json_encode($sid);
            $num=DB::table('units_score')
                ->where('uid',$uid)
                ->where('sid',$sid)
                ->where('type',$type)
                ->value('num');
            $score=number_format($num/$total*100,1);
            DB::table('units_score')
                ->where('uid',$uid)
                ->where('sid',$sid)
                ->where('type',$type)
                ->update(['score'=>$score]);
        }else {
            if ($type == 1) {
                $total = DB::table('book_system')
                    ->where('id', $sid)
                    ->value('choice_num');
            } else {
                $total = DB::table('book_system')
                    ->where('id', $sid)
                    ->value('blank_num');
            }
            $num=DB::table('unit_score')
                ->where('uid',$uid)
                ->where('sid',$sid)
                ->where('type',$type)
                ->value('num');
            //数量清0
            DB::table('unit_score')
                ->where('uid',$uid)
                ->where('sid',$sid)
                ->where('type',$type)
                ->update(['num'=>0]);

            if($num==$total){
                $fullPoint=DB::table('point_get')
                    ->where('id',2)
                    ->value('point');
                $point=DB::table('userinfo')
                    ->where('id',$uid)
                    ->value('point');
                $point=$point+$fullPoint;
                DB::table('userinfo')
                    ->where('id',$uid)
                    ->update(['point'=>$point]);
            }
            $score=number_format($num/$total*100,1);

            DB::table('unit_score')
                ->where('uid',$uid)
                ->where('sid',$sid)
                ->where('type',$type)
                ->update(['score'=>$score]);
        }


        return $score;
    }

    public function correctAnswerUpdate($uid,$type,$sid){
        if(is_array($sid)){
            $sid=json_encode($sid);
            $res= DB::table('units_score')
                ->where('sid',$sid)
                ->where('uid',$uid)
                ->where('type',$type)
                ->increment('num');
        }

        $res= DB::table('unit_score')
            ->where('sid',$sid)
            ->where('uid',$uid)
            ->where('type',$type)
            ->increment('num');
    }

//    public function correctAnswerGet($id,$state){
//        if($state==1) {
//            $correct_answer = DB::table('book_question')
//                ->where('id', $id)
//                ->value('');
//            $correct_answer=intval($correct_answer);
//        }else{
//            $correct_answer = DB::table('book_blank')
//                ->where('id', $id)
//                ->value('answer');
//        }
//        return $correct_answer;
//    }

    public function mistakeGet($uid,$sid,$type){
        if(is_array($sid)){
            $sid=json_encode($sid);
        }
        $data=array();
        $qid=DB::table('m_list')
            ->where('uid',$uid)
            ->where('sid',$sid)
            ->where('type',$type)
            ->value('qid');
        $qid=json_decode($qid);
        $res=DB::table('book_question')
            ->whereIn('id',$qid)
            ->select('word','id')
            ->get();

        foreach ($res as $key=>$val){
            $data[$key]['id']=$val->id;
            $data[$key]['words']=$val->word;
        }


        return $data;
    }

    /**
     * 答题结束错题查找
     * @param $id
     * @param $type
     * @return mixed
     */
    public function mistakeFind($id){

        $data=DB::table('book_question')
            ->where('id',$id)
            ->select('place','example',
                'trans','w_trans')
            ->get();
        $answer=DB::table('book_question')->where('id',$id)->value('word');

        $tid=DB::table('book_question')->where('id',$id)->value('tid');
        $sid=DB::table('book_question')->where('id',$id)->value('sid');
        $data=$data[0];
        $data->word=$answer;
        $unit=DB::table('book_system')->where('id',$sid)->value('unit');
        $title=DB::table('book_title')->where('id',$tid)->value('name');

        $data->place=$title.$unit.$data->place;
        return $data;
    }

    public function scoreGet($uid,$sid,$state){
        if(is_array($sid)){
            $sid=json_encode($sid);
            $score = DB::table('units_score')
                ->where('uid', $uid)
                ->where('sid', $sid)
                ->where('type', $state)
                ->value('score');
        }else {
            $score = DB::table('unit_score')
                ->where('uid', $uid)
                ->where('sid', $sid)
                ->where('type', $state)
                ->value('score');
        }
        $score = number_format($score, 1);
        return $score;

    }

    public function re_timeGet($uid,$sid,$state){
        if(is_array($sid)) {
            $sid = json_encode($sid);
            $re_time = DB::table('units_score')
                ->where('uid', $uid)
                ->where('sid', $sid)
                ->where('type', $state)
                ->value('re_time');
        }else {
            $re_time = DB::table('unit_score')
                ->where('uid', $uid)
                ->where('sid', $sid)
                ->where('type', $state)
                ->value('re_time');
        }
        return $re_time;
    }

    public function shortest_timeGet($uid,$sid,$state){
        if(is_array($sid)){
            $sid=json_encode($sid);
            $shortest_time = DB::table('units_score')
                ->where('uid', $uid)
                ->where('sid', $sid)
                ->where('type', $state)
                ->value('shortest_time');
        }else {
            $shortest_time = DB::table('unit_score')
                ->where('uid', $uid)
                ->where('sid', $sid)
                ->where('type', $state)
                ->value('shortest_time');
        }
        return $shortest_time;
    }

    public function timeGet($uid,$sid,$now,$state){
        if(is_array($sid)) {
            $sid = json_encode($sid);
            $start = DB::table('units_score')
                ->where('uid', $uid)
                ->where('sid', $sid)
                ->where('type', $state)
                ->value('start');
        }else {
            $start = DB::table('unit_score')
                ->where('uid', $uid)
                ->where('sid', $sid)
                ->where('type', $state)
                ->value('start');
        }
        $time=$now-$start;
        return $time;
    }

    public function questionCheck($sid,$type){
        if(is_array($sid)){
            $len=sizeof($sid);

            $id=DB::table('book_question')
                ->whereIn('sid',$sid)
                ->groupby('sid')
                ->select('id')
                ->get();

            $idlen=sizeof($id);
            if($len==$idlen){
                return true;
            }else{
                return false;
            }
        }else{
            $id=DB::table('book_question')
                ->where('sid',$sid)
                ->select('id')
                ->first();
            if($id){
                return true;
            }else{
                return false;
            }
        }

    }

    public function numCheck($sid,$type){
        switch ($type){
            case 1:
                $num=DB::table('book_system')
                    ->where('id',$sid)
                    ->value('choice_num');
                $has_num=DB::table('book_question')
                    ->where('sid',$sid)
                    ->count('id');
                break;
            case 2:
                $num=DB::table('book_system')
                    ->where('id',$sid)
                    ->value('blank_num');
                $has_num=DB::table('book_question')
                    ->where('sid',$sid)
                    ->count('id');
                break;
        }
        if($num<=$has_num){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 排行榜总分
     * @param $uid
     * @param $tid
     */
    public function total_score($uid,$tid){
        $score=DB::table('unit_score')
            ->where('uid',$uid)
            ->where('tid',$tid)
            ->sum('score');
        $ave_time=DB::table('unit_score')
            ->where('uid',$uid)
            ->where('tid',$tid)
            ->avg('re_time');
        $check=DB::table('total_score')
            ->where('uid',$uid)
            ->where('tid',$tid)
            ->value('id');
        if($check){
            DB::table('total_score')
                ->where('uid',$uid)
                ->where('tid',$tid)
                ->update([
                    'total_score'=>$score,'ave_time'=>$ave_time
                ]);
        }else{
            DB::table('total_score')
                ->insert([
                    'total_score'=>$score,'ave_time'=>$ave_time,
                    'uid'=>$uid,'tid'=>$tid
                ]);
        }
    }

    public function unitDone($uid,$tid){
        $questionLen=DB::table('book_question')
            ->where('tid',$tid)
            ->groupby('sid')
            ->select('id')
            ->get();
        $len=sizeof($questionLen)*2;
        $done=DB::table('unit_score')
            ->where('uid',$uid)
            ->where('tid',$tid)
            ->count('sid');
        if($len==$done){
            return true;
        }else{
            return false;
        }
    }

    public function tidGet($sid){
        $tid=DB::table('book_system')
            ->where('id',$sid)
            ->value('tid');
        return $tid;
    }
}