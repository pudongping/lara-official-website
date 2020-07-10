<?php
/**
 * 上传图片
 *
 * Created by PhpStorm.
 * User: Alex
 * Date: 2020/2/9
 * Time: 23:34
 */

namespace App\Repositories\Common;

use App\Repositories\BaseRepository;
use App\Models\Common\Image;
use App\Handlers\ImageUploadHandler;

class ImageRepository extends BaseRepository
{

    protected $model;
    protected $imageUploadHandler;

    public function __construct(
        Image $image,
        ImageUploadHandler $imageUploadHandler
    ) {
        $this->model = $image;
        $this->imageUploadHandler = $imageUploadHandler;
    }

    /**
     * 上传图片
     *
     * @param $request
     * @return mixed
     */
    public function storage($request)
    {
        $user = $request->user();
        $size = 'avatar' == $request->type ? 416 : 1024;
        $types = \Str::plural($request->type);  // 单词转成复数形式

        if ('base64' == $request->type) {
            $folderName = "uploads/images/{$types}/" . date("Ym/d", time());
            // 文件具体存储的物理路径，`public_path()` 获取的是 `public` 文件夹的物理路径。
            // 值如：/home/vagrant/Code/larablog/public/uploads/images/avatars/201709/21/
            $uploadPath = public_path() . '/' . $folderName;
            mkdirs($uploadPath);
            $filename = $user->id . '_' . time() . '_' . \Str::random(10) . '.png';
            $base64ImageContent = $request->image;
            img_base64_decode($base64ImageContent, $uploadPath . '/' . $filename);
            $result['relativePath'] = '/' . $folderName . '/' . $filename;
        } else {
            $result = $this->imageUploadHandler->save($request->image, $types, $user->id, 'image', $size);
        }

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
