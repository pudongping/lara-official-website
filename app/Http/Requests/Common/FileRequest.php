<?php

namespace App\Http\Requests\Common;

use App\Http\Requests\Request;

class FileRequest extends Request
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
                'type' => 'required|string',
                'file' => 'required|file'
            ],
        ];

        return $this->useRule($rules);
    }

    public function attributes()
    {
        return [
            'type' => '文件资源类型',
            'file' => '文件资源'
        ];
    }

    public function messages()
    {
        $messages = [
            'file.file' => '当前文件资源未上传成功'
        ];

        $messages = array_merge(parent::messages(), $messages);

        return $messages;
    }

}
