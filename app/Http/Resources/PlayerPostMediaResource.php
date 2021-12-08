<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayerPostMediaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'url'   => $this->url,
            'alt'   => $this->alt,
        ];
    }
}
