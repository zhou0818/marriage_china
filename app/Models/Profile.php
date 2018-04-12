<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{

    protected $fillable = [
        'user_id', 'name', 'gender', 'ethnic', 'province', 'city', 'area', 'address', 'id_card', 'marriage_cert', 'desc'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
