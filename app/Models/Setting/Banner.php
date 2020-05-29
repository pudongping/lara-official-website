<?php

namespace App\Models\Setting;

use App\Models\Model;

class Banner extends Model
{
    protected $fillable = ['title', 'img', 'status', 'jump_type', 'type', 'jump_url', 'sort'];
}
