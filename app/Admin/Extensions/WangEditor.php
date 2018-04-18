<?php
/**
 * Created by PhpStorm.
 * User: zhou
 * Date: 2018/4/16
 * Time: 16:30
 */

namespace App\Admin\Extensions;

use Encore\Admin\Form\Field;

class WangEditor extends Field
{
    protected $view = 'admin.wang-editor';

    protected static $css = [
        '/vendor/wangEditor/article_body.css',
        '/vendor/wangEditor/wangEditor-fullscreen-plugin.css',
    ];

    protected static $js = [
        '/vendor/wangEditor/wangEditor.min.js',
        '/vendor/wangEditor/wangEditor-fullscreen-plugin.js',
    ];

    public function render()
    {
        $name = $this->formatName($this->column);
        $url = route('articles.upload_image');
        $this->script = <<<EOT
        

var E = window.wangEditor
var editor = new E('#{$this->id}');
editor.customConfig.zIndex = 0
editor.customConfig.uploadFileName = 'image[]';
editor.customConfig.uploadImgServer = '{$url}'
editor.customConfig.uploadImgHeaders = {
    'X-CSRF-TOKEN': $('input[name="_token"]').val()
}
editor.customConfig.onchange = function (html) {
    $('input[name=\'$name\']').val(html);
}
editor.customConfig.uploadImgHooks = {
    customInsert: function (insertImg, results, editor) {
        for(var result of results){
           if(result.success) {
              insertImg(result.file_path);
              toastr.success(result.msg);
           } else {
              toastr.error(result.msg);
           }
        }
    }
}
editor.create()
E.fullscreen.init('#{$this->id}')

EOT;
        return parent::render();
    }

}