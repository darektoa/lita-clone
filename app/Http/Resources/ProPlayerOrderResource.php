<?php

namespace App\Http\Resources;

use App\Http\Resources\ProPlayerSkillResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProPlayerOrderResource extends JsonResource
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
            "id"                    => $this->id,
            "coin"                  => $this->coin,
            "balance"               => $this->balance,
            "status"                => $this->status,
            "expiry_duration"       => $this->expiry_duration,
            "play_duration"         => $this->play_duration,
            "ended_at"              => $this->ended_at,
            "expired_at"            => $this->expired_at,
            "created_at"            => $this->created_at,
            "updated_at"            => $this->updated_at,
            "status_name"           => $this->status_name,
            "player"                => PlayerResource::make($this->whenLoaded('player')),
            "pro_player_skill"      => ProPlayerSkillResource::make($this->whenLoaded('proPlayerSkill')),
            "pro_player_service"    => ProPlayerServiceResource::make($this->whenLoaded('proPlayerService')),
            "review"                => ProPlayerOrderReviewResource::make($this->whenLoaded('review')),
        ];
    }
}
