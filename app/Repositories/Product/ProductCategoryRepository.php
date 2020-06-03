<?php
/**
 * 商品类目
 *
 * Created by PhpStorm.
 * User: Alex
 * Date: 2020/3/2
 * Time: 9:38
 */

namespace App\Repositories\Product;

use App\Repositories\BaseRepository;
use App\Models\Product\ProductCategory;
use App\Support\Code;

class ProductCategoryRepository extends BaseRepository
{

    protected $model;

    public function __construct(ProductCategory $productCategoryModel)
    {
        $this->model = $productCategoryModel;
    }

    public function getList($request)
    {
        $search = $request->input('s');
        $fields = [
            'id', 'pid', 'name', 'description', 'sort', 'status', 'level', 'created_at', 'updated_at', 'id as value', 'name as label', 'img'
        ];
        $model = $this->model->select($fields);
        $model = $model->where(function ($query) use ($search) {
            if (!empty($search)) {
                $query->orWhere('name', 'like', '%' . $search . '%');
                $query->orWhere('description', 'like', '%' . $search . '%');
            }
        });

        if (false !== ($between = $this->searchTime($request))) {
            $model = $model->whereBetween('created_at', $between);
        }

        if (!is_null($request->status)) {
            $model = $model->where('status', intval(boolval($request->status)));
        }

        $data = $model->get()->toArray();

        foreach ($data as &$item) {
            $item['disabled'] = ($item['status'] == ProductCategory::STATUS_ENABLE) ? false : true;
        }

        return make_tree_data($data);
    }

    /**
     * 所有可见的类目树型结构
     *
     * @return array
     */
    public function allCateTree()
    {
        $allCate = $this->model->select('id', 'pid', 'name')->allowStatus()->get()->toArray();
        return make_tree_data($allCate);
    }

    /**
     * 添加类目
     *
     * @param $request
     * @return mixed
     */
    public function storage($request)
    {
        $input = $request->only(['pid', 'name', 'description', 'sort', 'status', 'img']);
        return $this->store($input);
    }

    /**
     * 编辑类目-数据处理
     *
     * @param $request
     * @return bool|mixed
     */
    public function modify($request)
    {
        $input = $request->only(['pid', 'name', 'description', 'sort', 'status', 'img']);
        if ($request->category->id == $request->pid) {
            Code::setCode(Code::ERR_PARAMS, '不可以将自身添加成父级类目');
            return false;
        }
        return $this->update($request->category->id, $input);
    }

    /**
     * 检查类目 id 的有效性
     *
     * @param array $cateIds
     * @return array
     */
    public function checkCateIds(array $cateIds) : array
    {
       return $this->model->whereIn('id', $cateIds)->pluck('id')->toArray();
    }

    /**
     * 通过分类级别查询分类
     *
     * @param $request
     * @return mixed
     */
    public function fetchByLevel($request)
    {
        $level = 0;
        if (!is_null($request->level)) {
            $level = $request->level;
        }

        $model = $this->model->where('level', $level)->allowStatus();

        return $this->usePage($model);
    }

    /**
     * 改变分类是否首页显示
     *
     * @param $request
     * @return mixed
     */
    public function changeIndexShow($request)
    {
        $hasIsIndexShow = $this->model->where('is_index_show', ProductCategory::INDEX_SHOW_YES)->count();
        if ($hasIsIndexShow >= config('api.other.category_show_index_count')) {
            Code::setCode(Code::ERR_PARAMS, '在首页显示的分类数超过限制');
            return false;
        }
        return $this->model->whereIn('id', $request->cate_ids)->update(['is_index_show' => $request->is_index_show]);
    }


}
