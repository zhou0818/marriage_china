<?php
/**
 * Created by PhpStorm.
 * User: zhou
 * Date: 2018/4/11
 * Time: 11:32
 */

namespace App\Admin\Controllers\Traits;

use App\Enums\UserGenderEnum;


trait UsersHelper
{
    public function users_grid_col($grid)
    {
        $grid->name('用户名')->sortable();
        $grid->phone('电话');
        $grid->profile()->name('姓名');
        $grid->profile()->gender('性别')->display(function ($gender) {
            return $gender == UserGenderEnum::MALE ? '男' : '女';
        });
        $grid->profile()->birthday('生日');
        $grid->profile()->ethnic('民族');
        $grid->profile()->province('省');
        $grid->profile()->city('市');
        $grid->created_at('注册时间');
    }
}