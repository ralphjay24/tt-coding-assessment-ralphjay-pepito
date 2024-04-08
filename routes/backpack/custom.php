<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

// API
Route::group([
    'prefix' => 'api',
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web')
    ),
    'namespace' => 'App\Http\Controllers',
], function () {
    Route::post('register', 'RegisterController@register');
    Route::post('login', 'LoginController@login');
});

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes


    Route::crud('user', 'UserCrudController');
    Route::crud('tag', 'TagCrudController');
    Route::crud('post', 'PostCrudController');
}); // this should be the absolute last line of this file