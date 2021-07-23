<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'PagesController@index')->name('index');

Route::group(['prefix' => 'user'], function () {
    Route::get('list', 'UserController@index')->name('user.list');
    Route::get('add', 'UserController@add')->name('user.add');
    Route::post('add', 'UserController@store')->name('user.store');
    Route::get('edit/{user}', 'UserController@edit')->name('user.edit');
    Route::post('edit/{user}', 'UserController@update')->name('user.update');
    Route::get('delete/{user}', 'UserController@delete')->name('user.delete');

    Route::get('roles', 'UserController@roles')->name('user.roles');
    Route::post('roles', 'UserController@addRole')->name('user.add-role');
    Route::get('permissions/{role_id}', 'UserController@permissions')->name('user.permissions');
    Route::post('permissions/{role_id}', 'UserController@updatePermissions')->name('user.update-permissions');
});

Route::group(['prefix' => 'log'], function () {
    Route::get('/', 'LogController@index')->name('log.index');
    Route::get('show/{type}/{id}', 'LogController@show')->name('log');
    Route::get('details/{log}', 'LogController@details')->name('log.details');
});

Route::group(['prefix' => 'license'], function () {
    Route::get('/', 'LicenseController@index')->name('license.list');
    Route::get('add', 'LicenseController@add')->name('license.add');
    Route::post('add', 'LicenseController@store')->name('license.store');
    Route::get('edit/{license}', 'LicenseController@edit')->name('license.edit');
    Route::post('edit/{license}', 'LicenseController@update')->name('license.update');
    Route::get('export', 'LicenseController@export')->name('license.export');
    Route::get('multi-update', 'LicenseController@multiUpdate')->name('license.multi-update');
    Route::get('delete/{license}', 'LicenseController@delete')->name('license.delete');
});

Route::group(['prefix' => 'product'], function () {
    Route::get('/', 'ProductController@index')->name('product.list');
    Route::get('add', 'ProductController@add')->name('product.add');
    Route::post('add', 'ProductController@store')->name('product.store');
    Route::get('edit/{product}', 'ProductController@edit')->name('product.edit');
    Route::post('edit/{product}', 'ProductController@update')->name('product.update');
    Route::get('delete/{product}', 'ProductController@delete')->name('product.delete');
});
