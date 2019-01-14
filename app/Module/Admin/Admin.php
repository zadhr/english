<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5 0005
 * Time: 下午 3:47
 */

namespace App\Module\Admin;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait AdminHandle
{
    public function login($username,$password){
        if(Auth::attempt(['username'=>$username,'password'=>$password],false)){
            $msg='success';
            return $msg;
        }else{
            $msg='帐号或密码错误';
            return $msg;
        }
    }

    public function logout(){
        Auth::logout();
    }

    public function admin_list(){
        $data = DB::table('users')
            ->select('id', 'username')
            ->get();

        return $data;
    }

    public function admin_check($username,$id){
        $check = DB::table('users')
            ->where('username', $username)
            ->where(function ($query) use ($id){
                if(!empty($id)) {
                    $query->where('id', '<>', $id);
                }
            })
            ->value('id');
        if ($check) {
            $msg = '已存在用户名';
            return $msg;
        }
    }

    public function admin_add($username,$password)
    {
        $check = DB::table('users')->where('username', $username)->value('id');
        if ($check) {
            $msg = '已存在用户名';
            return $msg;
        }

        $len = strlen($password);
        if ($len < 6) {
            $msg = '密码长度不能小于6位';
            return $msg;
        }
        $password = bcrypt($password);
        $result = DB::table('users')->insert(['username' => $username, 'password' => $password]);
        $msg=$result==true?'success':'fail';
        return $msg;
    }

    public function admin_edit($id,$username,$password){
        $password = bcrypt($password);
        $res=DB::table('users')
            ->where('id',$id)
            ->update([
                'id'=>$id,'username'=>$username,'password'=>$password
            ]);
        $msg=$res==true?'success':'fail';
        return $msg;
    }

    public function admin_del($id){
        $res=DB::table('users')
            ->where('id',$id)
            ->delete();
        $msg=$res==true?'success':'fail';
        return $msg;
    }
}