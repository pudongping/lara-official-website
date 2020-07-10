<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Common\FilesRepository;
use App\Http\Requests\Common\FileRequest;

class FilesController extends Controller
{

    protected $filesRepository;

    public function __construct(FilesRepository $filesRepository)
    {
        $this->init();
        $this->filesRepository = $filesRepository;
    }

    /**
     * 上传文件
     *
     * @param FileRequest $request
     * @return mixed
     */
    public function store(FileRequest $request)
    {
        $data = $this->filesRepository->storage($request);
        return $this->response->send($data);
    }

}
