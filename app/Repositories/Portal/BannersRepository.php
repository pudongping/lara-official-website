<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2020-7-5
 * Time: 12:47
 */

namespace App\Repositories\Portal;

use App\Repositories\BaseRepository;
use App\Models\Setting\Banner;

class BannersRepository extends BaseRepository
{

    protected $model;

    public function __construct(Banner $banner)
    {
        $this->model = $banner;
    }

    public function getList()
    {
        return $this->model->allowStatus()->get()->toArray();
    }

}
