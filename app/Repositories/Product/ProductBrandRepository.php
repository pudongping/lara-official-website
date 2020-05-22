<?php
/**
 * 商品品牌相关
 *
 * Created by PhpStorm.
 * User: Alex
 * Date: 2020/3/2
 * Time: 22:38
 */

namespace App\Repositories\Product;

use App\Repositories\BaseRepository;
use App\Models\Product\ProductBrand;
use App\Exceptions\ApiException;
use App\Models\Product\ProductCategory;

class ProductBrandRepository extends BaseRepository
{

    protected $model;
    protected $productCategoryRepository;
    protected $productCategoryModel;

    public function __construct(
        ProductBrand $productBrandModel,
        ProductCategoryRepository $productCategoryRepository,
        ProductCategory $productCategoryModel
    ) {
        $this->model = $productBrandModel;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->productCategoryModel = $productCategoryModel;
    }

    /**
     * 品牌列表
     *
     * @param $request
     * @return mixed
     */
    public function getList($request)
    {
         $search = $request->input('s');

         $model = $this->model->where(function ($query) use ($search) {
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

        $model = $model->with('categories');

        return $this->usePage($model);
    }

    /**
     * 所有可见的品牌
     *
     * @return mixed
     */
    public function allBrands()
    {
        return $this->model->select(['id', 'name', 'description', 'log_url', 'img'])->allowStatus()->get()->toArray();
    }

    /**
     * 新建品牌
     *
     * @param $request
     * @return mixed
     * @throws ApiException
     */
    public function storage($request)
    {
        $input = $request->only('name', 'description', 'log_url', 'status', 'sort', 'img');
        $brand = $this->store($input);
        return $brand;
    }

    /**
     *  品牌编辑-数据提交
     *
     * @param $request
     * @return bool|mixed
     * @throws ApiException
     */
    public function modify($request)
    {
        $input = $request->only('name', 'description', 'log_url', 'status', 'sort', 'img');
        $brand = $this->update($request->brand->id, $input);
        return $brand;
    }

}
