<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProPlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $pro_player_skills_count = $this->pro_player_skills_count;

        return [
            'id'                        => $this->id,
            'coin'                      => $this->coin,
            'voice'                     => $this->voice,
            'is_pro_player'             => $this->is_pro_player,
            'followers_count'           => $this->followers_count,
            'followings_count'          => $this->following_count,
            'pro_player_skills_count'   => $this->when($pro_player_skills_count, $pro_player_skills_count),
            // 'user'                      => $this->whenLoaded('user', new UserResource($this->user)),
            'pro_player_skills'         => $this->whenLoaded('pro_player_skills', ProPlayerSkillResource::collection($this->pro_player_skills)),
            'created_at'                => $this->created_at,
            'updated_at'                => $this->updated_at,
        ];
    }
}
