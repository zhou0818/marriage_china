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

class UserStatus extends AbstractTool
{
    protected function script()
    {
        $url = Request::fullUrlWithQuery(['status' => '_status_']);

        return <<<EOT

        $('input:radio.user-status').change(function () {
        
            var url = "$url".replace('_status_', $(this).val());
        
            $.pjax({container:'#pjax-container', url: url });
        
        });

EOT;
    }

    public function render()
    {
        Admin::script($this->script());

        $options = [
            'all' => '全部',
            'init' => '刚注册',
            'unaudited' => '待审核',
            'audited' => '已通过',
            'fail' => '未通过'
        ];

        return view('admin.tools.userStatus', compact('options'));
    }
}
