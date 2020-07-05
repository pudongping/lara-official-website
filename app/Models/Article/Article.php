<?php

namespace App\Models\Article;

use App\Models\Model;

class Article extends Model
{

    const STATUS_SHOW = 1;  // 显示
    const STATUS_HIDDEN = 0;  // 隐藏

    const TYPE_NEWS = 1;  // 新闻中心
    const TYPE_ABOUT_US = 2;  // 关于我们
    const TYPE_USER_AGREEMENT = 3;  // 用户协议
    const TYPE_PRIVACY = 4;  // 隐私政策

    const COVER_TYPE_IMG = 1;  // 图片
    const COVER_TYPE_VIDEO = 2;  // 视频

    protected $fillable = ['title', 'cover', 'cover_type', 'excerpt', 'body', 'order', 'type', 'status'];

    public function scopeAllowStatus($query)
    {
        return $query->where('status', self::STATUS_SHOW);
    }

}
