<?php

namespace App\Http\Resources;

use App\Helpers\StorageHelper;
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
            'voice'                     => StorageHelper::url($this->voice),
            'activity'                  => $this->activity,
            'activity_name'             => $this->activity_name,
            'is_pro_player'             => $this->is_pro_player,
            'referral_code'             => $this->referral_code,
            'followed'                  => $this->followed,
            'followers_count'           => $this->followers_count,
            'followings_count'          => $this->followings_count,
            'user'                      => UserResource::make($this->whenLoaded('user')),
            'pro_player_skills'         => ProPlayerSkillResource::collection($this->whenLoaded('proPlayerSkills'))
        ];
    }
}
