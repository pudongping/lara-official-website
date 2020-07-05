<?php

namespace App\Transformers\Article;

use App\Transformers\BaseTransformer;

class ArticleTransformer extends BaseTransformer
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
            'cover' => $resource->cover,
            'cover_type' => (int)$resource->cover_type,
            'excerpt' => $resource->excerpt,
            'body' => $resource->body,
            'order' => (int)$resource->order,
            'type' => (int)$resource->type,
            'status' => (int)$resource->status,
            'created_at' => $resource->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $resource->updated_at->format('Y-m-d H:i:s'),

        ];
    }
}
