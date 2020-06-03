<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\Request;
use App\Models\Product\ProductSpu;

class ProductSpuRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'store' => [
                'category_ids' => 'required|array',
                'brand_id' => 'required|integer|min:0|exists:product_brands,id',
                'title' => 'required|string|max:255|min:2',
                'unit' => 'required|string|max:255|min:1',
                'sketch' => 'nullable|string|max:255',
                'keywords' => 'nullable|string|max:255',
                'tags' => 'nullable|string|max:255',
                'barcode' => 'nullable|string|max:255',
                'market_price' => 'required|numeric|min:0',
                'rating' => 'integer|min:0|max:5',
                'virtual_retail_num' => 'integer|min:0',
                'warning_stock' => 'integer|min:0',
                'main_image' => 'required',
                'slider_image' => 'required|array',
                'status' => 'integer|min:0',
                'sort' => 'integer|min:0',
                'description' => 'nullable|string',
            ],
            'update' => [
                'category_ids' => 'required|array',
                'brand_id' => 'required|integer|min:0|exists:product_brands,id',
                'title' => 'required|string|max:255|min:2',
                'unit' => 'required|string|max:255|min:1',
                'sketch' => 'nullable|string|max:255',
                'keywords' => 'nullable|string|max:255',
                'tags' => 'nullable|string|max:255',
                'barcode' => 'nullable|string|max:255',
                'market_price' => 'required|numeric|min:0',
                'rating' => 'integer|min:0|max:5',
                'virtual_retail_num' => 'integer|min:0',
                'warning_stock' => 'integer|min:0',
                'main_image' => 'required',
                'slider_image' => 'required|array',
                'status' => 'integer|min:0',
                'sort' => 'integer|min:0',
                'description' => 'nullable|string',
            ],
            'modifyDescription' => [
                'description' => 'nullable|string',
            ],
            'changeHot' => [
                'spu_ids' => 'required|array',
                'spu_ids.*' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (!$spu = ProductSpu::find($value)) {
                            return $fail('该商品不存在');
                        }
                    }
                ],
                'is_hot' => 'required|integer',
            ],
        ];

        return $this->useRule($rules);
    }

    public function attributes()
    {
        return [
            'category_ids' => '当前选择的类目',
            'brand_id' => '品牌',
            'title' => '商品标题',
            'unit' => '商品单位',
            'sketch' => '商品简介',
            'keywords' => '商品关键字',
            'tags' => '商品标签',
            'barcode' => '商品条码',
            'market_price' => '市场价格',
            'rating' => '商品平均评分',
            'virtual_retail_num' => '虚拟销量',
            'warning_stock' => '库存警告值',
            'main_image' => '商品主图',
            'slider_image' => '商品轮播图',
            'status' => '状态',
            'sort' => '排序编号',
            'description' => '商品描述',
            'spu_ids' => '商品id',
            'is_hot' => '爆款状态'
        ];
    }

    public function messages()
    {
        $messages = [
            'category_ids.array' => '提交的类目数据格式错误',
            'price.numeric' => '商品价格必须为数字',
            'market_price.numeric' => '商品市场价格必须为数字',
            'slider_image.array' => '提交的轮播图数据格式错误',
            'spu_ids.array' => '提交的商品 id 数据格式错误',
        ];

        $messages = array_merge(parent::messages(), $messages);

        return $messages;
    }

}
