<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'desc'      => $this->description,
            'price'     => $this->price,
            'qty'       => $this->qty,
            'image'     => $this->image,
            'slug'      => $this->slug,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
