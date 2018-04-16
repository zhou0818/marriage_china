<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    //单身会员
    $router->resource('unmarried_users', 'UnmarriedUsersController')->except('create');
    //已婚会员
    $router->resource('married_users', 'MarriedUsersController')->except('create');
    //未审核会员
    $router->resource('unaudited_users', 'UnauditedUsersController')->except('create');

});
