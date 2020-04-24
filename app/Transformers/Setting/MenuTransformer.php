<?php

namespace App\Transformers\Setting;

use App\Transformers\BaseTransformer;
use App\Models\Setting\Menu;

class MenuTransformer extends BaseTransformer
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
            'pid' => $resource->pid ?? 0,
            'route_name' => $resource->route_name,
            'cn_name' => $resource->cn_name,
            'permission' => $resource->permission,
            'icon' => $resource->icon,
            'extra' => $resource->extra,
            'description' => $resource->description,
            'sort' => $resource->sort,
            'state' => $resource->state ?? Menu::STATE_NORMAL,
            'type' => $resource->type ?? Menu::TYPE_FRONT,
            'file_url' => $resource->file_url,
            'created_at' => $resource->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $resource->updated_at->format('Y-m-d H:i:s'),

        ];
    }
}
