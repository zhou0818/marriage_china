<?php

namespace App\Admin\Controllers;

use Admin;
use App\Admin\Controllers\Traits\UsersHelper;
use App\Enums\UserGenderEnum;
use App\Enums\UserStatusEnum;
use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\Province;
use App\Models\User;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

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

            // user表通用列
            $this->users_grid_col($grid, true, false);

            // 禁用操作列
            $grid->disableActions();

            $grid->type('婚姻状态')->display(function ($type) use ($grid) {
                return $type == UserTypeEnum::MARRIED ? '<span class="label label-success">已婚</span>' : '<span class="label label-danger">未婚</span>';
            });
            $grid->column('审核')->display(function () {
                return "<a href=\"/admin/unaudited_users/$this->id/edit\"><i class=\"fa fa-info\" style='padding-right: 5px'></i>详情</a>";
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
            $form->display('profile.province', '省')->with(function ($id) {
                $province = Province::find($id);
                $province ? $name = $province->name : $name = '无数据';
                return $name;
            });
            $form->display('profile.city', '市')->with(function ($id) {
                $city = City::find($id);
                $city ? $name = $city->name : $name = '无数据';
                return $name;
            });
            $form->display('profile.area', '区')->with(function ($id) {
                $area = Area::find($id);
                $area ? $name = $area->name : $name = '无数据';
                return $name;
            });
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
            $form->radio('status', '是否通过')->options([
                UserStatusEnum::UNAUDITED => '待审核',
                UserStatusEnum::AUDITED => '通过',
                UserStatusEnum::FAIL => '不通过'])
                ->stacked()
                ->rules('not_in:0', ['not_in' => '请选择是否通过']);
            $form->textarea('profile.desc', '不通过原因')->rows(3)->help('如果审核不通过需要填写原因')
                ->rules('required_if:status,' . UserStatusEnum::FAIL, ['required_if' => '选择不通过必须填写不通过原因']);
        });
    }

}
