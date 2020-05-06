<?php

namespace App\Transformers\Product;

use App\Transformers\BaseTransformer;


class ProductBrandTransformer extends BaseTransformer
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * Transform object into a generic array
     *
     * @var $resource
     * @return array
     */
    public function transform($resource)
    {
        // 将所有的分类数据转为 id 父子级数组
        $categories = $resource->categories;
        $cateArr = [];
        if (!$categories->isEmpty()) {
            $cate = $categories->toArray();
            foreach ($cate as $item) {
                $pathArr = str_explode($item['path'], '-');
                array_push($pathArr, $item['id']);
                $cateArr[] = array_map('intval', $pathArr);
            }
        }

        return [

            'id' => $resource->id,
            'name' => $resource->name,
            'description' => $resource->description,
            'sort' => $resource->sort,
            'status' => $resource->status,
            'created_at' => (string)$resource->created_at,
            'updated_at' => (string)$resource->updated_at,
            'categories' => $cateArr,

        ];
    }
}
