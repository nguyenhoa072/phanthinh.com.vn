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
//
//Route::get('/', function () {
//    return view('welcome');
//});
//
//Route::name('home')->group(function () {
//    Route::get('/', 'Home\IndexController@index')->name('index');
//});
// Auth::routes();
// Route::get('/home', 'HomeController@index')->name('home');

// Trang chủ
Route::get('/', ['as' => 'home', 'uses' => 'Home\IndexController@index']);
Route::get('/lien-he.html', ['as' => 'contact', 'uses' => 'Home\IndexController@contact']);
Route::get('/kho-hang.html', ['as' => 'warehouse', 'uses' => 'Home\IndexController@warehouse']);
Route::get('/ajax-warehouse', ['as' => 'ajax-warehouse', 'uses' => 'Home\IndexController@ajax_warehouse']);
Route::get('/tim-kiem', ['as' => 'search', 'uses' => 'Home\IndexController@search']);
Route::get('/san-pham', ['as' => 'products',
    'uses' => 'Home\IndexController@products']);
Route::get('/{manufacturerName}-m{id}.html', ['as' => 'category_manufacturer',
    'uses' => 'Home\IndexController@category_manufacturer'])->where(['manufacturerName' => '[a-z\-0-9]+', 'id' => '[0-9]+']);
Route::get('/{categoryName}-c{id}.html', ['as' => 'product_category', 'uses' => 'Home\IndexController@product_category'])->where(['categoryName' => '[a-z\-0-9]+', 'id' => '[0-9]+']);;
Route::get('/{productName}-p{id}.html', ['as' => 'product_detail', 'uses' => 'Home\IndexController@product_detail'])->where(['productName' => '[a-z\-0-9]+', 'id' => '[0-9]+']);

Route::get('/{slug_article}-a{id}.html', ['as' => 'article_detail', 'uses' => 'Home\IndexController@article_detail'])
    ->where(['slug_article' => '[a-z\-0-9]+', 'id' => '[0-9]+']);

Route::get('/{slug_product_home}-s{id}.html', ['as' => 'product_home_detail', 'uses' => 'Home\IndexController@product_home_detail'])
    ->where(['slug_product_home' => '[a-z\-0-9]+', 'id' => '[0-9]+']);

Route::group(['prefix' => 'cache'], function () {
    Route::get('/update-js-data', 'CacheController@update_js_data')->name('cache.update-js-data');
    Route::get('/update-img', 'CacheController@update_img')->name('cache.update-img');
    Route::get('/update-js', 'CacheController@update_js')->name('cache.update-js');
    Route::get('/update-css', 'CacheController@update_css')->name('cache.update-css');
    Route::get('/update-all', 'CacheController@update_all')->name('cache.update-all');
});

/** CATCH-ALL ROUTE for Backpack/PageManager - needs to be at the end of your routes.php file  **/
Route::get('{page}/{subs?}', ['uses' => 'PageController@index'])
    ->where(['page' => '^((?!admin).)*$', 'subs' => '.*']);