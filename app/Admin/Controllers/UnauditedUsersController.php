<?php

namespace App\Admin\Controllers;

use Admin;
use App\Admin\Controllers\Traits\UsersHelper;
use App\Admin\Extensions\Tools\UserType;
use App\Enums\UserGenderEnum;
use App\Enums\UserStatusEnum;
use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Request;

class UnauditedUsersController extends Controller
{
    use ModelForm;
    use UsersHelper;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('待审核会员');
            $content->description('已补全资料等待审核');

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

            $content->header('注册审核');

            $content->body($this->form($id)->edit($id));
        });
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(User::class, function (Grid $grid) {
            $grid->disableActions();
            $grid->disableCreateButton();
            $grid->disableExport();
            $grid->disableRowSelector();
            $grid->model()->where('status', '=', UserStatusEnum::UNAUDITED);
            // 单身已婚快捷查询
            $grid->tools(function ($tools) {
                $tools->append(new UserType());
            });

            $type = Request::get('type');
            if (in_array($type, ['unmarried', 'married'])) {
                $type == 'unmarried' ?
                    $grid->model()->where('status', '=', UserStatusEnum::UNAUDITED)->where('type', UserTypeEnum::UNMARRIED) :
                    $grid->model()->where('status', '=', UserStatusEnum::UNAUDITED)->where('type', UserTypeEnum::MARRIED);
            }

            // user表通用列
            $this->users_grid_col($grid);

            $grid->type('婚姻状态')->display(function ($type) use ($grid) {
                return $type == UserTypeEnum::MARRIED ? '<span class="label label-success">已婚</span>' : '<span class="label label-danger">未婚</span>';
            });
            $grid->column('审核')->display(function () {
                return "<a href=\"/admin/unaudited_users/$this->id/edit\"><i class=\"fa fa-info\" style='padding-right: 5px'></i>详情</a>";
            });

            //数据查询过滤
            $grid->filter(function ($filter) {

                // 去掉默认的id过滤器
                $filter->disableIdFilter();

                // 在这里添加字段过滤器
                $filter->like('name', '用户名');
                $filter->like('phone', '电话')->mobile();
                $filter->like('profile.name', '姓名');
                $filter->equal('profile.gender', '性别')->select([UserGenderEnum::MALE => '男', UserGenderEnum::FEMALE => '女']);

            });
        });
    }

    /**
     * Make a form builder.
     *
     * @param null $id
     * @return Form
     */
    protected function form($id = null)
    {
        // 查询婚姻状态，控制是否显示结婚照
        if ($id) {
            $user = User::find($id);
            $type = $user->type;
        } else {
            $type = UserTypeEnum::MARRIED;
        }
        return Admin::form(User::class, function (Form $form) use ($type) {
            $form->display('id', 'ID');
            $form->display('name', '用户名');
            $form->display('phone', '电话');
            $form->display('profile.name', '姓名');
            $form->display('profile.gender', '性别')->with(function ($gender) {
                return $gender == UserGenderEnum::MALE ? '男' : '女';
            });
            $form->display('profile.birthday', '生日');
            $form->display('profile.ethnic', '民族');
            $form->display('profile.province', '省');
            $form->display('profile.city', '市');
            $form->display('profile.area', '区');
            $form->display('profile.address', '地址');
            $form->display('type', '婚姻状态')->with(function ($type) {
                return $type == UserTypeEnum::MARRIED ? '<span class="label label-success">已婚</span>' : '<span class="label label-danger">未婚</span>';
            });
            $form->display('profile.id_card', '身份证')->with(function ($value) {
                return "<img src=\"$value\" style='width: 400px' />";
            });
            if ($type == UserTypeEnum::MARRIED) {
                $form->display('profile.marriage_cert', '结婚证')->with(function ($value) {
                    return "<img src=\"$value\" style='width: 400px' />";
                });
            }
            $form->display('created_at', '注册时间');
            $form->radio('status', '是否通过')->options([0 => '待审核', 1 => '通过', -1 => '不通过'])->stacked()
                ->rules('not_in:0', ['not_in' => '请选择是否通过']);
            $form->textarea('profile.desc', '不通过原因')->rows(3)->help('如果审核不通过需要填写原因')
                ->rules('required_if:status,-1', ['required_if' => '选择不通过必须填写不通过原因']);
        });
    }

}
