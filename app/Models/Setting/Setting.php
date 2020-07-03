<?php

namespace App\Models\Setting;

use App\Models\Model;

class Setting extends Model
{
    protected $fillable = ['contact_name', 'contact_phone', 'contact_email', 'contact_address', 'copy_right',
        'record_n_varchar', 'seo_description', 'seo_keyword', 'site_name', 'website'];
}
