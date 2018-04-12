<?php
/**
 * Created by PhpStorm.
 * User: zhou
 * Date: 2018/4/12
 * Time: 11:50
 */

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Request;
use App\Enums\UserTypeEnum;

class UserType extends AbstractTool
{
    protected function script()
    {
        $url = Request::fullUrlWithQuery(['type' => '_type_']);

        return <<<EOT

        $('input:radio.user-type').change(function () {
        
            var url = "$url".replace('_type_', $(this).val());
        
            $.pjax({container:'#pjax-container', url: url });
        
        });

EOT;
    }

    public function render()
    {
        Admin::script($this->script());

        $options = [
            'all' => '全部',
            'unmarried' => '单身',
            'married' => '已婚',
        ];

        return view('admin.tools.userType', compact('options'));
    }
}
