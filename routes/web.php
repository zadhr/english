<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('upload','SystemController@upload');

Route::post('login','AdminController@login');
Route::post('logout','AdminController@logout');

Route::get('admin','AdminController@admin_list');
Route::post('admin','AdminController@admin_add');
Route::put('admin','AdminController@admin_edit');
Route::delete('admin','AdminController@admin_del');
Route::post('check','AdminController@admin_check');

Route::get('infoList','UserinfoController@info_list');
//Route::get('infoList/detail','UserinfoController@info_detail');

Route::get('order','GiftController@gift_order');
Route::put('order','GiftController@gift_order_edit');
Route::get('gift','GiftController@giftList');
Route::post('gift','GiftController@gift_add');
Route::put('gift','GiftController@gift_edit');
Route::delete('gift','GiftController@gift_del');

Route::get('doc','DocumentController@doc_list');
Route::get('doc/detail','DocumentController@doc_detail');
Route::post('doc','DocumentController@doc_add');
Route::put('doc','DocumentController@doc_edit');
Route::delete('doc','DocumentController@doc_del');

Route::get('sign','SystemController@sign_list');
//Route::post('sign','SystemController@sign_add');
Route::put('sign','SystemController@sign_edit');
//Route::delete('sign','SystemController@sign_del');

Route::get('pointGet','SystemController@pointGet_list');
Route::put('pointGet','SystemController@pointGet_edit');

Route::get('title','BookController@book_title_list');
Route::post('title','BookController@book_title_add');
Route::put('title','BookController@book_title_edit');
Route::delete('title','BookController@book_title_del');

Route::get('grade','BookController@grade');

Route::get('title/unitAll','BookController@book_unit_all');

Route::get('title/unit','BookController@book_unit_list');
Route::post('title/unit','BookController@book_unit_add');
Route::put('title/unit','BookController@book_unit_edit');
Route::delete('title/unit','BookController@book_unit_del');

Route::get('title/unit/choice','BookController@choice_list');
Route::post('title/unit/choice','BookController@choice_add');
Route::put('title/unit/choice','BookController@choice_edit');
Route::delete('title/unit/choice','BookController@choice_del');

Route::get('title/unit/blank','BookController@blank_list');
Route::post('title/unit/blank','BookController@blank_add');
Route::put('title/unit/blank','BookController@blank_edit');
Route::delete('title/unit/blank','BookController@blank_del');

Route::get('task','BookController@task_list');
Route::post('task','BookController@task_add');
Route::delete('task','BookController@task_del');

Route::get('figure','SystemController@figure_list');
Route::post('figure','SystemController@figure_add');
Route::delete('figure','SystemController@figure_del');

Route::get('posters','SystemController@postersList');
Route::put('posters','SystemController@postersEdit');