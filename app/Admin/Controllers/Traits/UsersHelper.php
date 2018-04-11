<?php
/**
 * Created by PhpStorm.
 * User: zhou
 * Date: 2018/4/11
 * Time: 11:32
 */

namespace App\Admin\Controllers\Traits;


trait UsersHelper
{
    public function users_grid_col($grid)
    {
        $grid->name('用户名')->sortable();
        $grid->phone('电话');
        $grid->created_at('创建时间');
    }
}