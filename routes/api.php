<?php

Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');

Route::get('/posts', 'BlogController@index');
Route::get('/posts/{post}', 'BlogController@view');

Route::middleware('auth:api')->group(function () {

    Route::get('/user', 'AuthController@user');
    Route::post('/logout', 'AuthController@logout');

    Route::post('/posts/{post}/comments', 'BlogController@writeComment');

    Route::group(['prefix' => '/profile', 'namespace' => 'Profile'], function () {

        Route::apiResource('posts', 'PostController');

        Route::prefix('/posts/{post}')->group(function () {
            Route::get('/images', 'PostController@images');
            Route::post('/images', 'PostController@uploadImage');
        });

        Route::prefix('/images/{image}')->group(function () {
            Route::delete('/', 'PostController@deleteImage');
            Route::post('/main', 'PostController@setImageMain');
        });

        Route::apiResource('users', 'UserController')->except('store');

        Route::prefix('/users/{user}')->group(function () {
            Route::post('/admin', 'UserController@setAdmin');
            Route::post('/user', 'UserController@setUser');
        });
        
    });

});

