<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $this->resource->loadMissing('articles')->loadCount('articles');

        $articleResource = new class([]) extends JsonResource {
            public function toArray($request)
            {
                return [
                    'key' => $this->getKey(),
                    'title' => $this->title,
                    'summary' => $this->summary,
                ];
            }
        };

        return [
            'key' => $this->getKey(),
            'title' => $this->title,
            'slug' => $this->slug,
            // Total number of articles containing the tag
            // Number of articles that have a relationship with the tag
            'articles_count' => $this->articles_count ?: null,
            'articles' => $articleResource::collection($this->articles ?: []),
            'created_at' => $this->created_at,
        ];
    }
}
