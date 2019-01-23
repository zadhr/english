<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/7 0007
 * Time: 下午 2:05
 */

namespace App\Module\Book;


use Illuminate\Support\Facades\DB;

trait TaskHandle
{
    public function task_list($page,$limit){
        $now=date('Y-m-d H:i:s');
        $data=DB::table('task')
            ->orderby('create_time','DESC')
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->select('id','sid','num','name','q_state','start','end',
                'create_time','limit_time')
            ->get();
        foreach ($data as $value){
            $value->sid=json_decode($value->sid);
            $sname=DB::table('book_system')
                ->leftJoin('book_title','book_title.id','=','book_system.tid')
                ->whereIn('book_system.id',$value->sid)
                ->orderby('book_title.gid','ASC')
                ->orderby('book_title.name')
                ->orderby('book_system.id')
                ->select('book_title.name','book_system.unit')
                ->get();
            foreach($sname as $val){
                $value->sname[]=$val->name.$val->unit;
            }
            if($now<$value->start){
                $value->status='未开始';
            }elseif($now>=$value->start&&$now<$value->end){
                $value->status='进行中';
            }else{
                $value->status='已结束';
            }
        }
        return $data;
    }

    public function task_total(){
        $total=DB::table('task')->count();
        return $total;
    }

    public function task_add($data){
        $data['sid']=json_encode($data['sid']);
        $res=DB::table('task')->insert($data);
        $msg=$res==true?'success':'fail';
        return $msg;
    }



    public function task_del($id){
        $res=DB::table('task')->where('id',$id)->delete();
        $msg=$res==true?'success':'fail';
        return $msg;
    }

    public function taskCheck(){
        $now=date('Y-m-d H:i:s');
        $start=DB::table('task')->value('start');
        $end=DB::table('task')->value('end');
        if(strtotime($now)>=strtotime($start)&&strtotime($now)<=strtotime($end)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 任务随机出题
     * @param $type
     * @param $sid
     * @param $total
     * @param $uid
     * @param $kid
     * @return string
     */
    public function taskRandom($type,$sid,$total,$uid,$kid){

        $question=DB::table('book_question')
            ->whereIn('sid',$sid)
            ->orderbyRaw("Rand()")
            ->limit($total)
            ->pluck('id');
        $sid=json_encode($sid);
        $question=json_encode($question);
        $check=DB::table('q_tlist')
            ->where('uid',$uid)
            ->where('kid',$kid)
            ->value('id');
        if($check){
            DB::table('q_tlist')
                ->where('uid',$uid)
                ->where('kid',$kid)
                ->update(['qid'=>$question]);
        }else{
            $res= DB::table('q_tlist')
                ->where('uid',$uid)
                ->insert([
                    'qid'=>$question,'uid'=>$uid,'kid'=>$kid
                ]);

        }
        return 'success';
    }

    public function taskTotalGet($kid){
        $total=DB::table('task')
            ->where('id',$kid)
            ->value('num');
        return $total;
    }


    public function taskKidGet(){
        $kid=DB::table('task')->value('id');
        return $kid;
    }

    public function taskSidGet(){
        $sid=DB::table('task')
            ->value('sid');
        return $sid;
    }

    public function taskTypeGet(){
        $type=DB::table('task')
            ->value('q_state');
        return $type;
    }

    public function m_tlistClear($uid,$kid){
        DB::table('m_tlist')
            ->where('uid',$uid)
            ->where('kid',$kid)
            ->update(['qid'=>'[]']);
    }

    public function taskDoneCheck($uid,$kid){
        $id=DB::table('task_score')
            ->where('uid',$uid)
            ->where('kid',$kid)
            ->value('id');
        if($id){
            $now=time();
            DB::table('task_score')
                ->where('uid',$uid)
                ->where('kid',$kid)
                ->update(['num'=>0,'start'=>$now]);
            DB::table('task_score')
                ->where('uid',$uid)
                ->where('kid',$kid)
                ->increment('re_time');
            return true;
        }else{
            return false;
        }
    }

    public function taskStart($uid,$kid){
        $now=time();
        DB::table('task_score')->insert([
            'uid'=>$uid,'kid'=>$kid,'start'=>$now
        ]);
        DB::table('m_tlist')->insert([
            'uid'=>$uid,'kid'=>$kid,'qid'=>'[]'
        ]);
    }

    public function taskGet($uid,$kid,$type){
        $qid=DB::table('q_tlist')
            ->where('uid',$uid)
            ->where('kid',$kid)
            ->value('qid');
        $qid=json_decode($qid);
        $id=array_pop($qid);
        if(!$id){
            return false;
        }
        $qid=json_encode($qid);
        DB::table('q_tlist')
            ->where('uid',$uid)
            ->where('kid',$kid)
            ->update(['qid'=>$qid]);
        if($type==1){
            $data=DB::table('book_question')
                ->where('id',$id)
                ->select('id','choice1','choice2','choice3', 'choice4')
                ->get();
            $data[0]->question=DB::table('book_question')
                ->where('id',$id)
                ->value('word');
        }else{
            $data=DB::table('book_question')
                ->where('id',$id)
                ->select('id','w_trans')
                ->get();
            $data[0]->question=$data[0]->w_trans;
        }
        $data[0]->limit=DB::table('task')
            ->where('id',$kid)
            ->value('limit_time');
        return $data;
    }

    public function taskCorrect($uid,$kid){
        DB::table('task_score')
            ->where('uid',$uid)
            ->where('kid',$kid)
            ->increment('num');
    }

    public function taskMistakeAdd($id,$uid,$kid,$sid,$type){
        $id=intval($id);
        $now=date('Y-m-d H:i:s');
        $words=DB::table('book_question')->where('id',$id)->value('word');

        $is_check=DB::table('mistake')
            ->where('uid',$uid)
            ->where('sid',$sid)
            ->where('state',1)
            ->where('words',$words)
            ->value('id');
        if(!$is_check) {
            DB::table('mistake')->insert([
                'uid' => $uid, 'words' => $words, 'date' => $now, 'sid' => $sid, 'type' => $type
            ]);
        }
        $check=DB::table('m_tlist')
            ->where('uid',$uid)
            ->where('kid',$kid)
            ->value('id');
        if($check){
            $qid=DB::table('m_tlist')
                ->where('id',$check)
                ->value('qid');
            $qid=json_decode($qid);
            array_push($qid,$id);
            $qid=json_encode($qid);
            DB::table('m_tlist')
                ->where('id',$check)
                ->update(['qid'=>$qid]);
        }else{
            $id=json_encode(array($id));
            DB::table('m_tlist')->insert([
                'uid'=>$uid,'sid'=>$sid,'qid'=>$id,'kid'=>$kid
            ]);
        }
        return 'success';
    }


    public function  taskEnd($uid,$kid,$now){
        $start=DB::table('task_score')
            ->where('uid',$uid)
            ->where('kid',$kid)
            ->value('start');
        $time=$now-$start;
        $shortest_time=DB::table('task_score')
            ->where('uid',$uid)
            ->where('kid',$kid)
            ->value('shortest_time');
        if($shortest_time==0 || $time<$shortest_time){
            DB::table('task_score')
                ->where('uid',$uid)
                ->where('kid',$kid)
                ->update(['shortest_time'=>$time]);
        }
    }

    public function taskScoreSet($uid,$kid){
        $total=DB::table('task')
            ->where('id',$kid)
            ->value('num');
        $num=DB::table('task_score')
            ->where('uid',$uid)
            ->where('kid',$kid)
            ->value('num');
        if($total==$num){
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
        DB::table('task_score')
            ->where('uid',$uid)
            ->where('kid',$kid)
            ->update(['score'=>$score]);
    }

    public function taskScoreGet($uid,$kid){
        $score=DB::table('task_score')
            ->where('uid',$uid)
            ->where('kid',$kid)
            ->value('score');
        return $score;
    }

    public function taskRetimeGet($uid,$kid){
        $re_time=DB::table('task_score')
            ->where('uid',$uid)
            ->where('kid',$kid)
            ->value('re_time');
        return $re_time;
    }

    public function taskShortestTimeGet($uid,$kid){
        $shortestTime=DB::table('task_score')
            ->where('uid',$uid)
            ->where('kid',$kid)
            ->value('shortest_time');
        return $shortestTime;
    }

    public function taskTimeGet($uid,$kid,$now){
        $time=DB::table('task_score')
            ->where('uid',$uid)
            ->where('kid',$kid)
            ->value('start');
        $time=$now-$time;
        return $time;
    }

    public function taskMistakeGet($uid,$kid)
    {
        $qid = DB::table('m_tlist')
            ->where('uid', $uid)
            ->where('kid', $kid)
            ->value('qid');
        $qid = json_decode($qid);
        $data=array();
        $answer = DB::table('book_question')
            ->whereIn('id', $qid)
            ->select('word', 'id')
            ->get();
        foreach ($answer as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['words'] = $value->question;
        }

        return $data;
    }
}