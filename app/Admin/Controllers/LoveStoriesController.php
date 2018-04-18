<?php

namespace App\Admin\Controllers;

use Admin;
use App\Admin\Controllers\Traits\ArticlesHelper;
use App\Handlers\GeoHashHandler;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class LoveStoriesController extends Controller
{
    use ModelForm;
    use ArticlesHelper;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('爱情故事');
            $content->description('会员自己的爱情故事');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('审核');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Article::class, function (Grid $grid) {
            $grid->model()->whereHas('category', function ($query) {
                $query->where('name', '爱情故事');
            });

            $this->articles_grid_col($grid);
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Article::class, function (Form $form) {

            // 文章通用form
            $this->articles_form($form);
        });
    }
}
