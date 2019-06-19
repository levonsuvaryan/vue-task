<?php

Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');

Route::prefix('/blog')->group(function () {
    Route::get('/posts', 'PostsController@blogIndex');
    Route::get('/posts/{post}', 'PostsController@blogView');
});

Route::middleware('auth:api')->group(function () {

    Route::get('/user', 'AuthController@user');
    Route::post('/logout', 'AuthController@logout');

    Route::apiResource('posts', 'PostsController');

    Route::prefix('/posts/{post}')->group(function () {
        Route::post('/comments', 'CommentsController@store');
        Route::get('/images', 'ImagesController@index');
        Route::post('/images', 'ImagesController@upload');
    });

    Route::apiResource('comments', 'CommentsController')->only('update', 'destroy');

    Route::prefix('/images/{image}')->group(function () {
        Route::delete('/', 'ImagesController@delete');
        Route::post('/main', 'ImagesController@setMain');
    });

    Route::apiResource('users', 'UsersController')->except('store');

    Route::prefix('/users/{user}')->group(function () {
        Route::post('/admin', 'UsersController@setAdmin');
        Route::post('/user', 'UsersController@setUser');
    });

    

});

