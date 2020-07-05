<?php

namespace App\Http\Requests\Portal;

use App\Http\Requests\Request;

class PartnerRequest extends Request
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
                'name' => 'required|string|min:1',
                'phone' => 'required',
                'type' => 'required'
            ],
        ];

        return $this->useRule($rules);
    }

    public function attributes()
    {
        return [
            'name' => '姓名',
            'phone' => '电话号码',
            'type' => '合作类型'
        ];
    }

}
