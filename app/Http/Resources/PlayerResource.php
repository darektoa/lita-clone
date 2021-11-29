<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
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
            'id'                        => $this->id,
            'coin'                      => $this->coin,
            'balance'                   => $this->balance,
            'rate'                      => $this->rate,
            'voice'                     => $this->voice,
            'is_pro_player'             => $this->is_pro_player,
            'followers_count'           => $this->followers_count,
            'followings_count'          => $this->following_count,
            'user'                      => UserResource::make($this->whenLoaded('user'))
        ];
    }
}
