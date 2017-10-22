<?php

use Illuminate\Http\Request;

// USERS ROUTES
Route::group(['prefix' => 'users'], function () {

    Route::post('/authenticate', [
        'uses' => 'ApiAuthController@authenticate'
    ]);
    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::get('/me', [
        'uses' => 'ApiAuthController@me'
        ]);
    });
});
// Categories ROUTES
Route::group(['prefix' => 'category'], function () {


    Route::get('', [
        'uses' => 'CategoriesController@index'
    ]);
    Route::get('/markers', [
        'uses' => 'CategoriesController@markers'
    ]);
    Route::get('/list', [
        'uses' => 'CategoriesController@list'
    ]);
    Route::get('/listwithestablishments', [
        'uses' => 'CategoriesController@listWithEstablishments'
    ]);
    Route::get('/listonlycategories', [
        'uses' => 'CategoriesController@listOnlyCategories'
    ]);
    Route::get('/{id}', [
        'uses' => 'CategoriesController@show'
    ]);
    Route::post('', [
        'uses' => 'CategoriesController@create'
    ]);
    Route::put('/{id}', [
        'uses' => 'CategoriesController@update'
    ]);
    Route::delete('/{id}', [
        'uses' => 'CategoriesController@delete'
    ]);


});
// Establishments ROUTES
Route::group(['prefix' => 'establishment'], function () {

    Route::get('', [
        'uses' => 'EstablishmentController@index'
    ]);
    Route::get('/listwithcategory', [
        'uses' => 'EstablishmentController@listWithCategory'
    ]);
    Route::post('/photo', [
        'uses' => 'EstablishmentController@photo'
    ]);
    Route::get('/list', [
        'uses' => 'EstablishmentController@list'
    ]);
    Route::get('/ad/{address}', [
        'uses' => 'EstablishmentController@geocode'
    ]);
    Route::get('/{id}', [
        'uses' => 'EstablishmentController@show'
    ]);
    Route::post('', [
        'uses' => 'EstablishmentController@create'
    ]);
    Route::put('/{id}', [
        'uses' => 'EstablishmentController@update'
    ]);
    Route::delete('/{id}', [
        'uses' => 'EstablishmentController@delete'
    ]);


});
// Options ROUTES
Route::group(['prefix' => 'options'], function () {

    Route::get('', [
        'uses' => 'OptionsController@index'
    ]);
    Route::get('/list', [
        'uses' => 'OptionsController@list'
    ]);
    Route::get('/{id}', [
        'uses' => 'OptionsController@show'
    ]);
    Route::post('', [
        'uses' => 'OptionsController@create'
    ]);
    Route::put('/{id}', [
        'uses' => 'OptionsController@update'
    ]);
    Route::delete('/{id}', [
        'uses' => 'OptionsController@delete'
    ]);


});
// LeftMenu ROUTES
Route::group(['prefix' => 'leftmenu'], function () {

    Route::get('', [
        'uses' => 'LeftMenuController@index'
    ]);
    Route::get('/list', [
        'uses' => 'LeftMenuController@list'
    ]);
    Route::get('/{id}', [
        'uses' => 'LeftMenuController@show'
    ]);
    Route::post('', [
        'uses' => 'LeftMenuController@create'
    ]);
    Route::put('/{id}', [
        'uses' => 'LeftMenuController@update'
    ]);
    Route::delete('/{id}', [
        'uses' => 'LeftMenuController@delete'
    ]);


});