<?php

namespace App\Http\Resources;

use App\Helpers\StorageHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class AppBannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'image' => StorageHelper::url($this->image),
            'alt'   => $this->alt,
            'link'  => $this->link
        ];
    }
}
