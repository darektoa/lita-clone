<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProPlayerSkillResource extends JsonResource
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
            "id"                            => 47,
            "game_id"                       => 1,
            "game_user_id"                  => "22668",
            "game_tier"                     => "Mythic",
            "game_roles"                    => "Assasin",
            "game_level"                    => 30,
            "tier"                          => 0,
            "rate"                          => 0,
            "bio"                           => null,
            "voice"                         => null,
            "status"                        => 0,
            "status_name"                   => "Pending",
            "pro_player_skill_screenshots"  => ProPlayerSkillScreenshotResource::collection($this->proPlayerSkillScreenshots)
        ];
    }
}
