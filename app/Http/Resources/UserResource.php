<?php

namespace App\Http\Resources;

use App\Helpers\StorageHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'            => $this->id,
            'token'         => $this->when($this->token, $this->token),
            'name'          => $this->name,
            'username'      => $this->username,
            'email'         => $this->email,
            'profile_photo' => StorageHelper::url($this->profile_photo),
            'cover_photo'   => StorageHelper::url($this->cover_photo),
            'birthday'      => $this->birthday,
            'bio'           => $this->bio,
            'phone'         => $this->phone,
            'gender'        => GenderResource::make($this->whenLoaded('gender')),
            'player'        => PlayerResource::make($this->whenLoaded('player')),
            'admin'         => AdminResource::make($this->whenLoaded('admin'))
        ];
    }
}
