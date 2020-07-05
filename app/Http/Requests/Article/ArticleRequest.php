<?php

namespace App\Http\Requests\Article;

use App\Http\Requests\Request;

class ArticleRequest extends Request
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
                'title' => 'required|string',
                'excerpt' => 'required|string',
                'body' => 'required|string',
                'type' => 'required|integer',
            ],
        ];

        return $this->useRule($rules);
    }

    public function attributes()
    {
        return [
            'title' => '标题',
            'excerpt' => '摘要',
            'body' => '正文内容',
            'type' => '文章类型',
        ];
    }



}
