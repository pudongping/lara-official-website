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
use App\Support\Code;
use App\Models\Product\ProductCategory;

class ProductBrandRepository extends BaseRepository
{

    protected $model;
    protected $productCategoryRepository;
    protected $productCategoryModel;
    public $cateData = false;

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
        return $this->model->select(['id', 'name', 'description', 'log_url'])->allowStatus()->get()->toArray();
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
        $input = $request->only('name', 'description', 'status', 'sort');

        $validateCateIds = $this->productCategoryRepository->checkCateIds($request->category_ids);
        if (empty($validateCateIds)) {
            Code::setCode(Code::ERR_PARAMS, '类目参数不合法');
            return false;
        }

        \DB::beginTransaction();
        try {
            $brand = $this->store($input);
            // 多对多插入关联表
            $brand->categories()->attach($validateCateIds);
            \DB::commit();
        } catch (\Exception $exception) {
            throw new ApiException(Code::ERR_QUERY);
            \DB::rollBack();
        }

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
        $input = $request->only('name', 'description', 'status', 'sort');

        $validateCateIds = $this->productCategoryRepository->checkCateIds($request->category_ids);
        if (empty($validateCateIds)) {
            Code::setCode(Code::ERR_PARAMS, '类目参数不合法');
            return false;
        }

        \DB::beginTransaction();
        try {
            $brand = $this->update($request->brand->id, $input);
            // 多对多插入关联表（先删除关联数据，后写入）
            $brand->categories()->sync($validateCateIds);
            \DB::commit();
        } catch (\Exception $exception) {
            throw new ApiException(Code::ERR_QUERY);
            \DB::rollBack();
        }

        return $brand;
    }

    /**
     * 根据品牌找寻品牌所对应的分类层级结构
     *
     * @return array
     */
    public function brandCateCombine()
    {
        $fields = [
            'pcpb.category_id as pcpb_category_id',
            'pcpb.brand_id as pcpb_brand_id',
            'pc.id as pc_id',
            'pc.pid as pc_pid',
            'pc.name as pc_name',
            'pc.level as pc_level',
            'pc.path as pc_path',
            'pb.id as pb_id',
            'pb.name as pb_name'
        ];
        $data = \DB::table('product_categories_pivot_brands as pcpb')
            ->leftJoin('product_categories as pc', 'pcpb.category_id', '=', 'pc.id')
            ->leftJoin('product_brands as pb', 'pcpb.brand_id', '=', 'pb.id')
            ->select($fields)
            ->where('pc.status', '=', ProductCategory::STATUS_ENABLE)
            ->where('pb.status', '=', ProductBrand::STATUS_ENABLE)
            ->get();

        if ($data->isEmpty()) return [];

        $brandArr = $data->pluck('pb_name', 'pb_id')->toArray();
        $dataGroup = $data->groupBy('pb_id')->toArray();

        $arr = [];
        $treeData = [];
        foreach ($dataGroup as $k => $v) {
            if (isset($brandArr[$k])) {
                $item = [];
                $item['label'] = $k;  // 品牌 id
                $item['value'] = $brandArr[$k];  // 品牌名称
            }
            if (is_array($v)) {
                foreach ($v as $kk => $vv) {
                    $pcPathArr = str_explode($vv->pc_path, '-');
                    $arr[] = $this->makeCateTree($pcPathArr);
                }
            }
            $item['categories'] = $arr;
            $treeData[] = $item;
        }

        return $treeData;
    }

    /**
     * 将分类数据作为变量缓存使用，减少 sql 查询次数
     *
     * @return array|bool|false
     */
    protected function getCacheCateData()
    {
        if (!$this->cateData) {
            return $this->cateData = $this->getAllCateData();
        }
        return $this->cateData;
    }

    /**
     * 获取所有的分类数据
     *
     * @return array|false
     */
    protected function getAllCateData()
    {
        $cateFields = [
            'id',
            'pid',
            'name',
            'id as label',
            'name as value'
        ];
        $allCate = $this->productCategoryModel->select($cateFields)->allowStatus()->get()->toArray();
        $allCateIds = array_column($allCate, 'id');  // 分类所有的 id 数组
        $allCateArr = array_combine($allCateIds, $allCate);  // 以分类 id 为 key，分类单条数据为值的二维数组
        return $allCateArr;
    }

    /**
     * 根据指定父级获取层级结构
     *
     * @param array $idsArr 父级数组
     * @return array|mixed
     */
    protected function makeCateTree($idsArr = [])
    {
        // 从变量缓存中获取所有的分类数据
        $allCateArr = $this->getCacheCateData();

        if (!isset($idsArr[0])) return [];

        $firstId = $idsArr[0];  // 当前数据的顶级父级
        $lastId = array_reverse($idsArr)[0];  // 当前数据的 id

        $tree = make_tree_data($allCateArr);  // 所有的分类层级结构
        $currentDataTree = [];
        foreach ($tree as $k => $v) {
            if ($v['id'] == $firstId) {
                $currentDataTree = $v;  // 当前分类的层级结构
            }
        }
        return $currentDataTree;
    }



}
