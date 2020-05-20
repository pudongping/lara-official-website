<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\Request;

class ProductBrandRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // 编辑时，当前品牌的 id
        $currentBrandId = $this->brand->id ?? 0;

        $rules = [
            'store' => [
                'name' => 'required|string|max:255|min:2|unique:product_brands',
                'description' => 'string|max:255',
                'sort' => 'integer|min:0',
                'status' => 'integer',
            ],
            'update' => [
                'name' => 'required|string|max:255|min:2|unique:product_brands,name,'.$currentBrandId,
                'description' => 'string|max:255',
                'sort' => 'integer|min:0',
                'status' => 'integer',
            ],
        ];

        return $this->useRule($rules);
    }

    public function attributes()
    {
        return [
            'name' => '品牌名称',
            'description' => '品牌描述',
            'sort' => '排序编号',
            'status' => '状态',
        ];
    }

}
