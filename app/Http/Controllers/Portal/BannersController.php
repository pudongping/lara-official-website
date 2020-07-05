<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Portal\BannersRepository;

class BannersController extends Controller
{

    protected $bannersRepository;

    public function __construct(BannersRepository $bannersRepository)
    {
        $this->init();
        $this->bannersRepository = $bannersRepository;
    }

    public function index()
    {
        $data = $this->bannersRepository->getList();
        return $this->response->send($data);
    }

}
