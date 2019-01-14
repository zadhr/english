<?php
/**
 * Created by PhpStorm.
 * User: zeng
 * Date: 2018/5/29
 * Time: 下午2:52
 */

/**
 * 返回json响应
 */
if (!function_exists('jsonResponse')){
    function jsonResponse($param,$code=200){
        return response()->json($param,$code);
    }
}
/**
 * 返回视图响应
 */
if (!function_exists('viewResponse')){
    function viewResponse($view,$param){
        return view($view,$param);
    }
}
/**
 * 返回随机字符串
 */
if (!function_exists('createNonceStr')){
    function CreateNonceStr($length = 10){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}

/**
 * 图片压缩
 */
if(!function_exists('ImageCompression')) {
    function ImageCompression($photo, $ext, $radio)
    {

        $name = uniqid() . '.' . $ext;
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $dst_im = imagecreatefromjpeg($photo);
                imagejpeg($dst_im, '../public/uploads/pic/' . $name, $radio);
                break;
            case 'png':
                $dst_im = imagecreatefrompng($photo);
                imagepng($dst_im, '../public/uploads/pic/' . $name, $radio);
                break;
        }
        imagedestroy($dst_im);
        return 'uploads/pic/' . $name;
    }
}