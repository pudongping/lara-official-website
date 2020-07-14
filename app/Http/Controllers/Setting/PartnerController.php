<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Portal\PartnerRepository;
use App\Models\Setting\Partner;

class PartnerController extends Controller
{

    protected $partnerRepository;

    public function __construct(PartnerRepository $partnerRepository)
    {
        $this->init();
        $this->partnerRepository = $partnerRepository;
    }

    public function index(Request $request)
    {
        $data = $this->partnerRepository->getList($request);
        return $this->response->send($data);
    }

    /**
     * 更新洽谈消息内容
     *
     * @param Request $request
     * @param Partner $partner
     * @return mixed
     */
    public function update(Request $request, Partner $partner)
    {
        $partner->update($request->all());
        return $this->response->send();
    }

}
