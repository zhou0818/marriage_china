<?php
/**
 * Created by PhpStorm.
 * User: zhou
 * Date: 2018/4/11
 * Time: 11:32
 */

namespace App\Admin\Controllers\Traits;

use App\Admin\Extensions\Tools\UserStatus;
use App\Admin\Extensions\Tools\UserType;
use App\Enums\UserGenderEnum;
use App\Enums\UserStatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\Area;
use App\Models\City;
use App\Models\Province;
use Request;


trait UsersHelper
{
    public function users_grid_col($grid, $need_type = null, $need_status = null)
    {
        // 去除功能按钮
        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableRowSelector();

        $grid->name('用户名')->sortable();
        $grid->phone('电话');
        $grid->profile()->name('姓名');
        $grid->profile()->gender('性别')->display(function ($gender) {
            return $gender == UserGenderEnum::MALE ? '男' : '女';
        });
        $grid->profile()->birthday('生日');
        $grid->profile()->ethnic('民族');
        $grid->profile()->province('省')->display(function ($id) {
            $province = Province::find($id);
            $province ? $name = $province->name : $name = '无数据';
            return $name;
        });
        $grid->profile()->city('市')->display(function ($id) {
            $city = City::find($id);
            $city ? $name = $city->name : $name = '无数据';
            return $name;
        });
        $grid->profile()->area('区')->display(function ($id) {
            $area = Area::find($id);
            $area ? $name = $area->name : $name = '无数据';
            return $name;
        });
        $grid->created_at('注册时间');

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

        if ($need_type) {
            // 单身已婚快捷查询
            $grid->tools(function ($tools) {
                $tools->append(new UserType());
            });

            $type = Request::get('type');
            if (in_array($type, ['unmarried', 'married'])) {
                $type == 'unmarried' ?
                    $grid->model()->where('type', UserTypeEnum::UNMARRIED) :
                    $grid->model()->where('type', UserTypeEnum::MARRIED);
            }
        }

        if ($need_status) {
            // 会员状态快捷查询
            $grid->tools(function ($tools) {
                $tools->append(new UserStatus());
            });

            $status = Request::get('status');
            if (in_array($status, ['init', 'unaudited', 'audited', 'fail'])) {
                switch ($status) {
                    case 'init':
                        $grid->model()->where('status', '=', UserStatusEnum::INIT);
                        break;
                    case 'unaudited':
                        $grid->model()->where('status', '=', UserStatusEnum::UNAUDITED);
                        break;
                    case 'audited':
                        $grid->model()->where('status', '=', UserStatusEnum::AUDITED);
                        break;
                    case 'fail':
                        $grid->model()->where('status', '=', UserStatusEnum::FAIL);
                        break;
                }
            }

            $status_array = [
                UserStatusEnum::INIT => '<span class="label label-info">刚注册</span>',
                UserStatusEnum::UNAUDITED => '<span class="label label-primary">待审核</span>',
                UserStatusEnum::AUDITED => '<span class="label label-success">已通过</span>',
                UserStatusEnum::FAIL => '<span class="label label-danger">未通过</span>'
            ];
            $grid->status('状态')->display(function ($type) use ($status_array) {
                return $status_array[$type];
            });
        }
    }

    public function users_edit_form($form)
    {
        // 去掉重置按钮
        $form->disableReset();

        $form->tab('基础信息', function ($form) {

            $form->display('id', 'ID');
            $form->text('name', '用户名');
            $form->mobile('phone', '电话')->options(['mask' => '999 9999 9999'])->attribute(['style' => 'width:100%']);
            $form->text('profile.name', '姓名');
            $form->select('profile.gender', '性别')->options([UserGenderEnum::MALE => '男', UserGenderEnum::FEMALE => '女']);
            $form->datetime('profile.birthday', '生日')->format('YYYY-MM-DD')->attribute(['style' => 'width:100%']);
            $form->text('profile.ethnic', '民族');
            $form->select('profile.province', '省')->options(Province::all()->pluck('name', 'id'))->load('profile.city', '/cities');
            $form->select('profile.city', '市')->options(function ($id) {
                $city = City::find($id);
                return City::where('province_id', $city->province_id)->pluck('name', 'id');
            })->load('profile.area', '/areas');
            $form->select('profile.area', '区')->options(function ($id) {
                $area = Area::find($id);
                return Area::where('city_id', $area->city_id)->pluck('name', 'id');
            });
            $form->text('profile.address', '地址');

        })->tab('证件信息', function ($form) {
            $form->image('profile.id_card', '身份证')
                ->rules('mimes:jpeg,bmp,png,gif|dimensions:min_width=200,min_height=200', ['dimensions' => '图片的清晰度不够，宽和高需要 200px 以上'])
                ->resize(800, null, function ($constraint) {

                    // 设定宽度是 $max_width，高度等比例双方缩放
                    $constraint->aspectRatio();

                    // 防止裁图时图片尺寸变大
                    $constraint->upsize();
                });;
            $form->image('profile.marriage_cert', '结婚证')
                ->rules('mimes:jpeg,bmp,png,gif|dimensions:min_width=200,min_height=200', ['dimensions' => '图片的清晰度不够，宽和高需要 200px 以上'])
                ->resize(800, null, function ($constraint) {

                    // 设定宽度是 $max_width，高度等比例双方缩放
                    $constraint->aspectRatio();

                    // 防止裁图时图片尺寸变大
                    $constraint->upsize();
                });;;
        });

    }
}