<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayerPostResource extends JsonResource
{
    public function toArray($request)
    {
        $playerLoaded   = $this->relationLoaded('player');
        $userLoaded     = $playerLoaded ? $this->player->relationLoaded('user') : false;

        return [
            'id'            => $this->id,
            'text'          => $this->text,
            'likes_count'   => $this->player_post_likes_count,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'post_media'    => PlayerPostMediaResource::collection($this->whenLoaded('postMedia')),
            'user'          => UserResource::make($this->when($userLoaded, $this->player->user)),
        ];
    }
}
