<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
        return [
            'key' => $this->getKey(),
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'icon_url' => $this->whenNotNull($this->icon_url),
            'icon_class' => $this->whenNotNull($this->icon_class),
            'color' => $this->color,
            'order' => $this->order,
            'created_at' => $this->created_at,
        ];
    }
}
