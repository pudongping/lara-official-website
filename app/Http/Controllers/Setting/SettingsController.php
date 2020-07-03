<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Support\Code;
use Illuminate\Http\Request;
use App\Models\Setting\Setting;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\ApiException;

class SettingsController extends Controller
{

    public function __construct()
    {
        $this->init();
    }

    /**
     * 站点设置列表
     *
     * @return mixed
     */
    public function index()
    {

        if (\Cache::has(config('api.cache_key.site'))) {
            $data = \Cache::get(config('api.cache_key.site'));
        } else {
            $data = Setting::get()->toArray();
            \Cache::put(config('api.cache_key.site'), $data);
        }

        return $this->response->send($data);
    }


    /**
     * 更新站点设置
     *
     * @param Request $request
     * @param Setting $setting
     * @return mixed
     * @throws ApiException
     */
    public function update(Request $request, Setting $setting)
    {
        $validator = Validator::make($request->all(), [
            'contact_email' => 'email'
        ], [
            'contact_email.email' => '联系人邮箱格式错误'
        ]);
        if ($validator->fails()) throw new ApiException(Code::ERR_PARAMS, [], $validator->errors()->first());
        $setting->fill($request->input());
        $setting->save();
        \Cache::forget(config('api.cache_key.site'));
        return $this->response->send();
    }


    /**
     * 清空所有缓存
     *
     * @return mixed
     */
    public function clearCache()
    {
        \Cache::flush();
        return $this->response->send();
    }

}
