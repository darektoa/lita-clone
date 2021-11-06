<?php

namespace App\Http\Resources;

use App\Helpers\StorageHelper;
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
        $userLoaded = $this->player->relationLoaded('user');

        return [
            'id'                            => $this->id,
            'game_user_id'                  => $this->game_user_id,
            'game_tier'                     => $this->game_tier,
            'game_roles'                    => $this->game_roles,
            'game_level'                    => $this->game_level,
            'rate'                          => $this->rate,
            'bio'                           => $this->bio,
            'voice'                         => StorageHelper::url($this->voice),
            'status'                        => $this->status,
            'status_name'                   => $this->status_name,
            'price_permatch'                => $this->price_permatch,
            'game'                          => GameResource::make($this->whenLoaded('game')),
            'tier'                          => TierResource::make($this->whenLoaded('tier')),
            'user'                          => UserResource::make($this->when($userLoaded, $this->player->user)),
            'pro_player_skill_screenshots'  => ProPlayerSkillScreenshotResource::collection($this->whenLoaded('proPlayerSkillScreenshots'))
        ];
    }
}
