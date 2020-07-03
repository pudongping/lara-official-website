<?php
/**
 * 日志记录
 *
 * Created by PhpStorm.
 * User: Alex
 * Date: 2020/2/16
 * Time: 21:42
 */

namespace App\Repositories\Setting;

use App\Repositories\BaseRepository;
use App\Models\Setting\Log;
use App\Support\TempValue;

class LogsRepository extends BaseRepository
{

    protected $model;

    public function __construct(Log $model)
    {
        $this->model = $model;
    }

    /**
     * 用户操作日志列表
     *
     * @param $request
     * @return array
     */
    public function getList($request)
    {

        $search = $request->input('s');
        $model = $this->model->where(function ($query) use ($search) {
            if (!empty($search)) {
                $query->orWhere('description', 'like', '%' . $search . '%');
                $query->orWhere('client_ip', 'like', '%' . $search . '%');
            }
        });

        if (false !== ($between = $this->searchTime($request))) {
            $model = $model->whereBetween('created_at', $between);
        }

        return $this->usePage($model);
    }

}
