<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2020-7-5
 * Time: 15:51
 */

namespace App\Repositories\Portal;

use App\Repositories\BaseRepository;
use App\Models\Setting\Partner;

class PartnerRepository extends BaseRepository
{

    protected $model;

    public function __construct(Partner $partner)
    {
        $this->model = $partner;
    }

    public function getList($request)
    {
        $search = $request->input('s');
        $model = $this->model->where(function ($query) use ($search) {
            if (!empty($search)) {
                $query->orWhere('name', 'like', '%' . $search . '%');
                $query->orWhere('phone', 'like', '%' . $search . '%');
            }
        });

        if (!is_null($request->is_read)) {
            $model = $model->where('is_read', intval($request->is_read));
        }

        if (false !== ($between = $this->searchTime($request))) {
            $model = $model->whereBetween('created_at', $between);
        }

        return $this->usePage($model);
    }

    public function storage($request)
    {
        return $this->store($request->all());
    }

}
