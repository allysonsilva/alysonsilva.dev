<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
        $resource = new class([]) extends JsonResource {
            public function toArray($request)
            {
                return [
                    'key' => $this->getKey(),
                    'title' => $this->title,
                    'slug' => $this->slug,
                    'created_at' => $this->created_at,
                ];
            }
        };

        return [
            'key' => $this->getKey(),
            'title' => $this->title,
            'summary' => $this->summary,
            'slug' => $this->slug,
            'image' => $this->image_url,
            'likes' => $this->likes,
            'visits' => $this->visits,
            'color' => $this->color,
            'category' => new $resource($this->getRelationValue('category')),
            'tags' => $resource::collection($this->getRelationValue('tags')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
