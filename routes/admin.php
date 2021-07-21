<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'PagesController@index')->name('index');

Route::group(['prefix' => 'user'], function () {
    Route::get('list', 'UserController@index')->name('user.list');

    Route::get('roles', 'UserController@roles')->name('user.roles');
    Route::post('roles', 'UserController@addRole')->name('user.add-role');

    Route::get('permissions/{role_id}', 'UserController@permissions')->name('user.permissions');
    Route::post('permissions/{role_id}', 'UserController@updatePermissions')->name('user.update-permissions');

    Route::get('edit/{user}', 'UserController@edit')->name('user.edit');
    Route::post('edit/{user}', 'UserController@update')->name('user.update');
});

Route::group(['prefix' => 'log'], function () {
    Route::get('/', 'LogController@index')->name('log.index');
    Route::get('show/{type}/{id}', 'LogController@show')->name('log');
    Route::get('details/{log}', 'LogController@details')->name('log.details');
});

Route::group(['prefix' => 'product'], function () {
    Route::get('/', 'ProductController@index')->name('product.list');
    Route::get('edit/{product}', 'ProductController@edit')->name('product.edit');
    Route::post('edit/{product}', 'ProductController@update')->name('product.update');
});
