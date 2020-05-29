<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Setting\BannersRepository;
use App\Http\Requests\Setting\BannerRequest;
use App\Models\Setting\Banner;

class BannersController extends Controller
{

    protected $bannersRepository;

    public function __construct(BannersRepository $bannersRepository)
    {
        $this->init();
        $this->bannersRepository = $bannersRepository;
    }

    /**
     * banner 列表
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $data = $this->bannersRepository->getList($request);
        return $this->response->send($data);
    }

    /**
     * 保存 banner
     *
     * @param BannerRequest $request
     * @return mixed
     */
    public function store(BannerRequest $request)
    {
        $data = $this->bannersRepository->storage($request);
        return $this->response->send($data);
    }

    /**
     * 更新 banner
     *
     * @param BannerRequest $request
     * @param Banner $banner
     * @return mixed
     */
    public function update(BannerRequest $request, Banner $banner)
    {
        $data = $this->bannersRepository->modify($request);
        return $this->response->send($data);
    }

    /**
     * 删除 banner
     *
     * @param Banner $banner
     * @return mixed
     * @throws \Exception
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();
        return $this->response->send();
    }


}
