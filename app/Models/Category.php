<?php
/**
 * Created by PhpStorm.
 * User: zhou
 * Date: 2018/4/16
 * Time: 15:41
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'description',
    ];
}