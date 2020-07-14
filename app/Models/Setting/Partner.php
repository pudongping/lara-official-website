<?php

namespace App\Models\Setting;

use App\Models\Model;

class Partner extends Model
{

    const TYPE_SUPPLIER = 1;  // 供应商入驻
    const TYPE_SALE = 2;  // 销售渠道
    const TYPE_LOGISTICS = 3;  // 物流渠道
    const TYPE_OTHER = 4;  // 其他

    protected $fillable = ['name', 'phone', 'type', 'is_read'];

}
