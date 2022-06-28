<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    Route::get('slide-show/search', 'SlideShowController@search')->name('slide-show.search');
    Route::resource('slide-show', 'SlideShowController');

    Route::get('partner/search', 'PartnerController@search')->name('partner.search');
    Route::resource('partner', 'PartnerController');

    Route::get('support/search', 'SupportController@search')->name('support.search');
    Route::resource('support', 'SupportController');

    Route::get('manufacturer/search', 'ManufacturerController@search')->name('manufacturer.search');
    Route::resource('manufacturer', 'ManufacturerController');

    Route::get('product-category/search', 'ProductCategoryController@search')->name('product-category.search');
    Route::get('product-category/ajax-get-category-by-manufacturer/{manufacturer_id}', 'ProductCategoryController@ajaxGetCategoryByManufacturer')->name('product-category.ajax_get_category_by_manufacturer');
    Route::resource('product-category', 'ProductCategoryController');

    Route::get('product/import', 'ProductController@import')->name('product.import');
    Route::post('product/import', 'ProductController@store_import');
    Route::get('product/search', 'ProductController@search')->name('product.search');
    Route::post('product/ajax-active', 'ProductController@ajaxActive')->name('product.ajax_active');
    Route::post('product/ajax-inactive', 'ProductController@ajaxInactive')->name('product.ajax_inactive');
    Route::post('product/ajax-delete', 'ProductController@ajaxDelete')->name('product.ajax_delete');
    Route::resource('product', 'ProductController');

    Route::get('warehouse/import', 'WarehouseController@import')->name('warehouse.import');
    Route::post('warehouse/import', 'WarehouseController@store_import');
    Route::get('warehouse/search', 'WarehouseController@search')->name('warehouse.search');
    Route::resource('warehouse', 'WarehouseController');

    Route::get('article/search', 'ArticleController@search')->name('article.search');
    Route::post('article/ajax-active', 'ArticleController@ajaxActive')->name('article.ajax_active');
    Route::post('article/ajax-inactive', 'ArticleController@ajaxInactive')->name('article.ajax_inactive');
    Route::post('article/ajax-delete', 'ArticleController@ajaxDelete')->name('article.ajax_delete');
    Route::resource('article', 'ArticleController');
    
    Route::get('product-home/search', 'ProductHomeController@search')->name('product-home.search');
    Route::resource('product-home', 'ProductHomeController');
    Route::post('product-home/ajax-active', 'ProductHomeController@ajaxActive')->name('product-home.ajax_active');
    Route::post('product-home/ajax-inactive', 'ProductHomeController@ajaxInactive')->name('product-home.ajax_inactive');
    Route::post('product-home/ajax-delete', 'ProductHomeController@ajaxDelete')->name('product-home.ajax_delete');

    CRUD::resource('menu-item', 'MenuItemCrudController');

    Route::post('upload', 'UploadController@index')->name('upload');
}); // this should be the absolute last line of this file
