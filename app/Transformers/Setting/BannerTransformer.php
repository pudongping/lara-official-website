<?php

namespace App\Transformers\Setting;

use App\Transformers\BaseTransformer;

class BannerTransformer extends BaseTransformer
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * Transform object into a generic array
     *
     * @var $resource
     * @return array
     */
    public function transform($resource)
    {
        return [
            'id' => $resource->id,
            'title' => $resource->title,
            'img' => $resource->img,
            'status' => (int)$resource->status,
            'jump_type' => (int)$resource->jump_type,
            'type' => (int)$resource->type,
            'jump_url' => $resource->jump_url,
            'sort' => (int)$resource->sort,
            'created_at' => $resource->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $resource->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
