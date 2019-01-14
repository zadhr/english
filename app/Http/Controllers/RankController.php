<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Module\user;

class RankController extends Controller
{
    private $handle;

    public function __construct()
    {
        $this->handle=new User();
    }

     public function Rank(Request $request){
         $tid=$request->get('tid');
         $data=$this->handle->Rank($tid);

         if(!isset($data[0])){

             $data='暂无排行信息！';
         }
         return response()->json([
            'data'=>$data
         ]);
     }

     public function RankGradeGet(){
         $data=$this->handle->rankGradeGet();
         return response()->json([
            'data'=>$data
         ]);
     }

    /**
     * 特殊任务排行
     * @return \Illuminate\Http\JsonResponse
     */
    public function RankTask(){
        $kid=$this->handle->taskKidGet();
        $data=$this->handle->rankTask($kid);
        if(!isset($data[0])){

            $data='暂无排行信息！';
        }
        return response()->json([
           'data'=>$data
        ]);
    }

}
