<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Portal\PartnerRepository;

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

}
