<?php

namespace App\Admin\Controllers;

use Admin;
use App\Admin\Controllers\Traits\ArticlesHelper;
use App\Handlers\ImageUploadHandler;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class ArticlesController extends Controller
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

            $content->header('文章');
            $content->description('所有类别的文章');

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

            $content->header('文章修改');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('文章新建');

            $content->body($this->form());
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
                $query->where('name', '!=', '爱情故事');
            });

            //文章通用grid
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
            $form->select('category_id', '类别')->options(Category::where('name', '!=', '爱情故事')->pluck('name', 'id'));
            // 文章通用form
            $this->articles_form($form);
        });
    }

    public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        $info = [];
        // 判断是否有上传文件，并赋值给 $files
        if ($files = $request->file('image')) {
            foreach ($files as $index => $file) {
                // 保存图片到本地
                $result = $uploader->save($file, 'articles', Admin::user()->id, 1024);

                // 图片保存成功的话
                if ($result) {
                    $data['file_path'] = $result['path'];
                    $data['msg'] = "上传成功!";
                    $data['success'] = true;
                    array_push($info, $data);
                } else {
                    $file_index = $index + 1;
                    $data['file_path'] = '';
                    $data['msg'] = "第" . $file_index . "张图片上传失败!";
                    $data['success'] = false;
                    array_push($info, $data);
                }
            }
        } else {
            $data = [
                'success' => false,
                'msg' => '上传失败!',
                'file_path' => ''
            ];
            array_push($info, $data);
        }
        return $info;
    }
}
