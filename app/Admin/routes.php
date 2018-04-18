<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    // 单身会员
    $router->resource('unmarried_users', 'UnmarriedUsersController')->except(['create', 'destroy', 'store']);
    // 已婚会员
    $router->resource('married_users', 'MarriedUsersController')->except(['create', 'destroy', 'store']);
    // 未审核会员
    $router->resource('unaudited_users', 'UnauditedUsersController')->except(['create', 'destroy', 'store']);

    // 文章类型
    $router->resource('categories', 'CategoriesController');
    // 文章
    $router->resource('articles', 'ArticlesController');
    // 爱情故事
    $router->resource('love_stories', 'LoveStoriesController')->except(['create', 'destroy', 'store']);

    // 文章图片上传
    Route::post('upload_image', 'ArticlesController@uploadImage')->name('articles.upload_image');

});
