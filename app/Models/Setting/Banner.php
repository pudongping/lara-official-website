<?php

namespace App\Models\Setting;

use App\Models\Model;

class Banner extends Model
{

    const STATUS_SHOW = 1;
    const STATUS_HIDDEN = 0;

    public static $statusMsg = [
        self::STATUS_SHOW => '显示',
        self::STATUS_HIDDEN => '隐藏'
    ];

    protected $fillable = ['title', 'img', 'status', 'jump_type', 'type', 'jump_url', 'sort'];


}
