<?php
/**
 * 上传文件
 *
 * Created by PhpStorm.
 * User: Alex
 * Date: 2020-7-10
 * Time: 14:06
 */

namespace App\Repositories\Common;

use App\Repositories\BaseRepository;
use App\Models\Common\Image;
use App\Handlers\FileUploadHandler;

class FilesRepository extends BaseRepository
{

    protected $model;
    protected $fileUploadHandler;

    public function __construct(
        Image $image,
        FileUploadHandler $fileUploadHandler
    ) {
        $this->model = $image;
        $this->fileUploadHandler = $fileUploadHandler;
    }

    /**
     * 上传文件
     *
     * @param $request
     * @return mixed
     */
    public function storage($request)
    {
        $user = $request->user();
        $types = \Str::plural($request->type);  // 单词转成复数形式
        $file = $request->file;  // 文件实例
        $result = $this->fileUploadHandler->save($file, $types, $user->id, 'file');

        $guard = \Auth::getDefaultDriver() ?? config('api.default_guard_name');  // 获取默认的守卫名称

        $input = [
            'user_id' => $user->id,
            'guard_name' => $guard,
            'type' => $request->type,
            'path' => $result['relativePath']
        ];

        return $this->store($input);
    }

}
