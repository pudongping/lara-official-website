<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2020-5-29
 * Time: 8:34
 */

namespace App\Repositories\Setting;

use App\Repositories\BaseRepository;
use App\Models\Setting\Banner;

class BannersRepository extends BaseRepository
{

    protected $model;

    public function __construct(Banner $banner)
    {
        $this->model = $banner;
    }

    /**
     * banner 列表
     *
     * @param $request
     * @return mixed
     */
    public function getList($request)
    {
        $model = $this->model;

        if (!is_null($request->status)) {
            $model = $model->where('status', intval($request->status));
        }

        if (!is_null($request->jump_type)) {
            $model = $model->where('jump_type', intval($request->jump_type));
        }

        if (!is_null($request->type)) {
            $model = $model->where('type', intval($request->type));
        }

        return $this->usePage($model);
    }

    /**
     * 保存 banner
     *
     * @param $request
     * @return mixed
     */
    public function storage($request)
    {
        $input = $request->only(['title', 'img', 'status', 'jump_type', 'type', 'jump_url', 'sort']);
        return $this->store($input);
    }

    /**
     * 更新 banner
     *
     * @param $request
     * @return mixed
     */
    public function modify($request)
    {
        $input = $request->only(['title', 'img', 'status', 'jump_type', 'type', 'jump_url', 'sort']);
        return $this->update($request->banner->id, $input);
    }

}
