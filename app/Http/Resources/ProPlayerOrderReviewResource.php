<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProPlayerOrderReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $orderLoaded    = $this->relationLoaded('proPlayerOrder');
        $playerLoaded   = $orderLoaded ? $this->proPlayerOrder->relationLoaded('player') : false;
        $userLoaded     = $playerLoaded ? $this->proPlayerOrder->player->relationLoaded('user') : false;

        return [
            'id'            => $this->id,
            'star'          => $this->star,
            'review'        => $this->review,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'user'          => UserResource::make($this->when($userLoaded, $this->proPlayerOrder->player->user)),
        ];
    }
}
