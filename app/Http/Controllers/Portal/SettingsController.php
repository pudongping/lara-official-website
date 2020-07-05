<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Portal\SettingsRepository;

class SettingsController extends Controller
{

    protected $settingsRepository;

    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->init();
        $this->settingsRepository = $settingsRepository;
    }

    public function index()
    {
        $data = $this->settingsRepository->getList();
        return $this->response->send($data);
    }

}
