<?php

namespace App\Models\Setting;

use App\Models\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'name', 'content', 'sort'];
}
