<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2020-7-5
 * Time: 13:07
 */

namespace App\Repositories\Portal;

use App\Repositories\BaseRepository;
use App\Models\Setting\Setting;

class SettingsRepository extends BaseRepository
{

    protected $model;

    public function __construct(Setting $setting)
    {
        $this->model = $setting;
    }

    public function getList()
    {
        return $this->model->first()->toArray();
    }



}
