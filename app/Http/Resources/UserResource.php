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
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'username'      => $this->username,
            'email'         => $this->email,
            'password'      => $this->password,
            'profile_photo' => StorageHelper::url($this->profile_photo),
            'cover_photo'   => StorageHelper::url($this->cover_photo),
            'birthday'      => $this->birthday,
            'bio'           => $this->bio,
            'player'        => $this->whenLoaded('player'),
            'admin'         => $this->whenLoaded('admin')
        ];
    }
}
