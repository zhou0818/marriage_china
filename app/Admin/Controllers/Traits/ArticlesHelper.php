<?php
/**
 * Created by PhpStorm.
 * User: zhou
 * Date: 2018/4/17
 * Time: 16:00
 */

namespace App\Admin\Controllers\Traits;

use App\Models\Category;


trait ArticlesHelper
{
    public function articles_grid_col($grid)
    {
        $grid->title('标题')->limit(80);
//            $grid->title('标题')->limit(30)->display(function ($title) {
//                return "<a href=\"/admin/unaudited_users/$this->id/edit\"><i class=\"fa fa-info\" style='padding-right: 5px'></i>$title</a>";
//            });
        $grid->category()->name('类别');
        $states = [
            'on' => ['value' => 1, 'text' => '是', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'danger'],
        ];
        $grid->is_carousel('是否轮播')->switch($states);
        $grid->is_audited('是否发布')->switch($states);

        $grid->created_at('创建时间');
        $grid->updated_at('修改时间');
    }

    public function articles_form($form)
    {
        // 去掉重置按钮
        $form->disableReset();

        $form->text('title', '标题');

        $dir = "images/articles/cover/" . date("Ym", time()) . '/' . date("d", time()) . '/';
        $form->image('cover_picture', '封面图')
            ->rules('mimes:jpeg,bmp,png,gif|dimensions:min_width=400,min_height=400', ['dimensions' => '图片的清晰度不够，宽和高需要 400px 以上'])
            ->resize(800, null, function ($constraint) {

                // 设定宽度是 $max_width，高度等比例双方缩放
                $constraint->aspectRatio();

                // 防止裁图时图片尺寸变大
                $constraint->upsize();
            })
            ->move($dir);
        $form->editor('body', '内容');
        $states = [
            'on' => ['value' => 1, 'text' => '是', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'danger'],
        ];
        $form->switch('is_carousel', '是否轮播')->states($states);
        $form->switch('is_audited', '是否发布')->states($states);


        $form->display('created_at', '创建时间');
        $form->display('updated_at', '修改时间');
    }
}