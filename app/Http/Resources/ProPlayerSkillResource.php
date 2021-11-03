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
        $user = $this->player->user;

        return [
            'id'                            => $this->id,
            'game_user_id'                  => $this->game_user_id,
            'game_tier'                     => $this->game_tier,
            'game_roles'                    => $this->game_roles,
            'game_level'                    => $this->game_level,
            'rate'                          => $this->rate,
            'bio'                           => $this->bio,
            'voice'                         => $this->voice,
            'status'                        => $this->status,
            'status_name'                   => $this->status_name,
            'game'                          => GameResource::make($this->whenLoaded('game')),
            'tier'                          => TierResource::make($this->whenLoaded('tier')),
            'user'                          => UserResource::make($this->when($user, $user)),
            'pro_player_skill_screenshots'  => ProPlayerSkillScreenshotResource::collection($this->whenLoaded('proPlayerSkillScreenshots'))
        ];
    }
}
