<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProPlayerServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $playerLoaded   = $this->relationLoaded('player');
        $userLoaded     = $playerLoaded ? $this->player->relationLoaded('user') : false;

        return [
            'id'                => $this->id,
            'rate'              => $this->rate,
            'bio'               => $this->bio,
            'status'            => $this->status,
            'status_name'       => $this->status_name,
            'activity'          => $this->activity,
            'activity_name'     => $this->activity_name,
            'price_permatch'    => $this->price_permatch,
            'service'           => ServiceResource::make($this->whenLoaded('service')),
            'user'              => UserResource::make($this->when($userLoaded, $this->player->user->load('player'))),
        ];
    }
}
