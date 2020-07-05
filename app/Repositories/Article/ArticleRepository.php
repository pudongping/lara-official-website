<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2020-7-5
 * Time: 17:26
 */

namespace App\Repositories\Article;

use App\Repositories\BaseRepository;
use App\Models\Article\Article;

class ArticleRepository extends BaseRepository
{

    protected $model;

    public function __construct(Article $article)
    {
        $this->model = $article;
    }

    /**
     * 管理后台文章列表
     *
     * @param $request
     * @return mixed
     */
    public function getList($request)
    {
        $search = $request->input('s');
        $model = $this->model->where(function ($query) use ($search) {
            if (!empty($search)) {
                $query->orWhere('title', 'like', '%' . $search . '%');  // 文章标题
                $query->orWhere('excerpt', 'like', '%' . $search . '%');  // 文章摘要
                $query->orWhere('body', 'like', '%' . $search . '%');  // 文章正文
            }
        });

        if (false !== ($between = $this->searchTime($request))) {
            $model = $model->whereBetween('created_at', $between);
        }

        if (!is_null($request->type)) {
            $model = $model->where('type', intval($request->type));
        }

        if (!is_null($request->status)) {
            $model = $model->where('status', intval($request->status));
        }

        return $this->usePage($model);
    }

    /**
     * 门户-文章列表
     *
     * @param $request
     * @return mixed
     */
    public function getPortalList($request)
    {
        $search = $request->input('s');
        $model = $this->model->where(function ($query) use ($search) {
            if (!empty($search)) {
                $query->orWhere('title', 'like', '%' . $search . '%');  // 文章标题
                $query->orWhere('excerpt', 'like', '%' . $search . '%');  // 文章摘要
                $query->orWhere('body', 'like', '%' . $search . '%');  // 文章正文
            }
        });

        if (false !== ($between = $this->searchTime($request))) {
            $model = $model->whereBetween('created_at', $between);
        }

        if (!is_null($request->type)) {
            $model = $model->where('type', intval($request->type));
        }

        if (!is_null($request->status)) {
            $model = $model->where('status', intval($request->status));
        }

        $model = $model->allowStatus();

        return $this->usePage($model);
    }

    public function storage($request)
    {
        return $this->store($request->all());
    }

    public function modify($request)
    {
        return $this->update($request->article->id, $request->all());
    }

}
