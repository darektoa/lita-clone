<?php

namespace App\Http\Resources;

use App\Helpers\StorageHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMediaResource extends JsonResource
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
            'sender'        => UserResource::make($this->whenLoaded('sender')),
            'receiver'      => UserResource::make($this->whenLoaded('receiver')),
            'url'           => StorageHelper::url($this->url),
            'alt'           => $this->alt,
            'created_at'    => $this->created_at,
        ];
    }
}
