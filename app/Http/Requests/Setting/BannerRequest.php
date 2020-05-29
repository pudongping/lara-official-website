<?php

namespace App\Http\Requests\Setting;

use App\Http\Requests\Request;

class BannerRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'store' => [
                'title' => 'max:200',
                'img' => 'max:200',
                'status' => 'integer',
                'jump_type' => 'integer',
                'type' => 'integer',
                'sort' => 'integer',
            ],
            'update' => [
                'title' => 'max:200',
                'img' => 'max:200',
                'status' => 'integer',
                'jump_type' => 'integer',
                'type' => 'integer',
                'sort' => 'integer',
            ],
        ];

        return $this->useRule($rules);
    }

    public function attributes()
    {
        return [
            'title' => '标题',
            'img' => '图片地址',
            'status' => '状态',
            'jump_type' => '跳转状态',
            'type' => '图片类型',
            'sort' => '排序编号',
        ];
    }

    public function messages()
    {
        $messages = [
            'title.max' => '标题不能超过 200 个字符',
            'img.max' => '图片地址不能超过 200 个字符',
        ];

        $messages = array_merge(parent::messages(), $messages);

        return $messages;
    }

}
