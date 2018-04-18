<?php
/**
 * Created by PhpStorm.
 * User: zhou
 * Date: 2018/4/16
 * Time: 15:41
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug', 'is_carousel'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}