<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Module\user;

class DocumentController extends Controller
{
    private $handle;

    public function __construct()
    {
        $this->handle=new user();
    }

    public function doc_list(){
        $data=$this->handle->doc_all_detail();
        return response()->json([
            'data'=>$data
        ]);
    }

    public function doc_app_list(){
         $data=$this->handle->doc_app_list();
        return response()->json([
           'data'=>$data
        ]);
    }

    public function doc_app_detail(Request $request){
        $id=$request->get('id');
        $data=$this->handle->doc_app_detail($id);
        return response()->json([
            'data'=>$data
        ]);
    }

    public function doc_add(Request $request){
        $title=$request->get('title');
        $content=$request->get('content');
        $msg=$this->handle->doc_add($title,$content);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    public function doc_edit(Request $request){
        $title=$request->get('title');
        $content=$request->get('content');
        $id=$request->get('id');
        $msg=$this->handle->doc_edit($id,$title,$content);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    public function doc_del(Request $request){
        $id=$request->get('id');
        $msg=$this->handle->doc_del($id);
        return response()->json([
            'msg'=>$msg
        ]);
    }
}
