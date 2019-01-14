<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Module\user;

class AdminController extends Controller
{

    private $handle;

    public function __construct()
    {
        $this->handle=new user();
    }

    public function login(Request $request){
        $username=$request->get('username');
        $password=$request->get('password');
        $data=$this->handle->login($username,$password);
        return response()->json([
           'msg'=>$data
        ]);
    }

    public function logout(){
         $this->handle->logout();
        return response()->json([
           'msg'=>'success'
        ]);
    }

    public function admin_list(){
        $data=$this->handle->admin_list();
        return response()->json([
           'data'=>$data
        ]);
    }

    public function admin_add(Request $request){
         $username=$request->get('username');
        $password=$request->get('password');
        $msg=$this->handle->admin_add($username,$password);
        return response()->json([
            'msg'=>$msg
        ]);
    }

    public function admin_check(Request $request){
         $username=$request->get('username');
        $id=$request->get('id');
        $msg=$this->handle->admin_check($username,$id);
        return response()->json([
           'msg'=>$msg
        ]);
    }

    public function admin_edit(Request $request){
        $id=$request->get('id');
        $username=$request->get('username');
        $password=$request->get('password');
        $msg=$this->handle->admin_edit($id,$username,$password);
        return response()->json([
           'msg'=>$msg
        ]);
    }

    public function admin_del(Request $request){
         $id=$request->get('id');
        $msg=$this->handle->admin_del($id);
        return response()->json([
           'msg'=>$msg
        ]);
    }
}
