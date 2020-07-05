<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Portal\PartnerRepository;
use App\Http\Requests\Portal\PartnerRequest;

class PartnerController extends Controller
{

    protected $partnerRepository;

    public function __construct(PartnerRepository $partnerRepository)
    {
        $this->init();
        $this->partnerRepository = $partnerRepository;
    }

    public function store(PartnerRequest $request)
    {
        $data = $this->partnerRepository->storage($request);
        return $this->response->send($data);
    }

}
