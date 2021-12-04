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
        $skillLoaded    = $orderLoaded ? $this->proPlayerOrder->relationLoaded('proPlayerSkill'): false;
        $playerLoaded   = $skillLoaded ? $this->proPlayerOrder->proPlayerSkill->relationLoaded('player') : false;
        $userLoaded     = $playerLoaded ? $this->proPlayerOrder->proPlayerSkill->player->relationLoaded('user') : false;

        return [
            'id'            => $this->id,
            'star'          => $this->star,
            'review'        => $this->review,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'user'          => $this->when($userLoaded, $this->proPlayerOrder->proPlayerSkill->player->user),
        ];
    }
}
