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

Route::get('/', function () {
    return view('welcome');
});
//Регистрация с подтверждением на email ---------------------------------------------------
Route::get('register', ['as' => 'register', 'uses' => 'AuthController@create']);
Route::post('register','AuthController@createSave');
Route::get('register/confirm/{token}','AuthController@confirm');
Route::get('register_repeat', 'AuthController@repeat');
Route::post('register_repeat','AuthController@repeatPost');
Route::get('login', ['as' => 'login', 'uses' => 'AuthController@login']);
Route::post('login', ['uses' => 'AuthController@loginPost']);
Route::get('logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
//-----------------------------------------------------------

//Роуты для вебмастера---------------------------------------
Route::post('add_pads', ['middleware' => 'auth','uses' =>'PartnerPadController@addPads']);
//------------------------------------------------

//Тест для Максима-----------------------------------
Route::get('test_graph', ['uses' =>'VideoClickController@index']);
Route::get('test_deep', ['uses' =>'VideoClickController@deep']);
//-------------------------------

//Тестовый редактор виджета-------------------------------------
Route::get('widget/editor/{id?}', ['as'=>'admin.widget.editor','middleware' => 'auth', 'uses' => 'WidgetController@edit']);
Route::get('widget/render/', ['middleware' => 'auth', 'uses' => 'WidgetController@render']);
Route::post('widget/save', ['middleware' => 'auth', 'uses' => 'WidgetController@saveWidget']);
//--------------------------------

Route::get('/home', 'HomeController@index')->name('home');
//-----------------------------Роли
Route::get('/roles', 'RolesController@Roles');
//--------------------------------------------
