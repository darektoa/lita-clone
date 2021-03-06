<?php

namespace App\Http\Resources;

use App\Helpers\StorageHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
            'reporter'      => UserResource::make($this->whenLoaded('reporter')),
            'reportable'    => $this->reportable,
            'report'        => $this->report,
            'proof'         => StorageHelper::url($this->proof),
            'status'        => $this->status,
            'created_at'    => $this->created_at,
        ];
    }
}
