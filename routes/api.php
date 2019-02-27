<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login','UserinfoController@wx_login');
Route::post('loginCheck','UserinfoController@login_check');

Route::post('sign','UserinfoController@sign');
Route::post('signGet','UserinfoController@signGet');

Route::get('posters','SystemController@postersList');

Route::post('info','UserinfoController@info');
Route::put('info','UserinfoController@info_edit');

Route::get('grade','BookController@grade_choice');

Route::get('doc','DocumentController@doc_app_list');
Route::get('doc/detail','DocumentController@doc_app_detail');

Route::post('gift','GiftController@gift_list');
Route::get('gift/detail','GiftController@gift_detail');
Route::post('order','GiftController@user_orders');
Route::post('order/detail','GiftController@order_detail');
Route::post('exchange','GiftController@exchange');

Route::post('mistake','UserinfoController@mistake_list');
Route::delete('mistake','UserinfoController@mistakeDel');
Route::get('mistake','UserinfoController@mistake_find');
Route::post('mistake/add','UserinfoController@mistake_add');
Route::post('mistake/Add','UserinfoController@user_mistake_add');

Route::post('mistake/find','BookController@answerFind');

Route::get('figure','SystemController@figure');

Route::post('share','SystemController@share');

Route::post('random','BookController@random');
Route::post('question','BookController@questionGet');
Route::post('finish','BookController@finish');
//Route::post('check','BookController@answer_check');

Route::post('rank','RankController@rank');
Route::get('rank','RankController@RankGradeGet');
Route::get('rankTask','RankController@RankTask');

Route::get('GradeGet','BookController@gradeGet');
Route::get('BookGet','BookController@bookGet_New');
//Route::get('UnitGet','BookController@unitGet');

Route::post('taskRandom','BookController@taskRandom');
Route::post('taskGet','BookController@taskQuestionGet');
Route::post('taskFinish','BookController@taskFinish');
Route::get('taskCheck','BookController@taskCheck');

Route::post('formidSave','SystemController@FormIdSave');
Route::get('mesSend','SystemController@MesSend');
Route::get('noticeSend','SystemController@NoticeSend');


