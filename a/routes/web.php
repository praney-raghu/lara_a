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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('admin');

Route::get('/site', 'SiteController@index')->name('site');

Route::get('/error', 'HomeController@error')->name('error');

Route::delete('/delete', 'HomeController@destroy_image')->name('del_img')->middleware('admin');
Route::get('/users', 'HomeController@user')->name('users')->middleware('admin');
Route::post('/users/activate', 'HomeController@activate')->name('activate')->middleware('admin');
Route::post('/users/deactivate', 'HomeController@deactivate')->name('deactivate')->middleware('admin');
Route::get('/product', 'HomeController@show')->name('product')->middleware('admin');
Route::post('/store', 'HomeController@store')->name('store')->middleware('admin');
Route::get('/edit', 'HomeController@edit')->name('edit')->middleware('admin');
Route::put('/update', 'HomeController@update')->name('update')->middleware('admin');
Route::delete('/destroy', 'HomeController@destroy')->name('destroy')->middleware('admin');
Route::get('/home/search', 'HomeController@search')->name('search')->middleware('admin');
Route::get('/getVehicles', 'HomeController@getVehicles');

Route::get('/site/search', 'SiteController@search')->name('user_search');
Route::get('/site/description/{id}', 'SiteController@show')->name('pro_show');

Route::get('logout', '\Autovilla\Http\Controllers\Auth\LoginController@logout');

//Route::get('my-datatables', 'MyDatatablesController@index');

//Route::get('get-data-my-datatables', ['as'=>'get.data','uses'=>'HomeController@getData']);