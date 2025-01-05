<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\User as UserResource;

class Call extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'call_sid' => $this->call_sid,
            'status' => $this->status,
            'duration' => $this->duration,
            'from_user' => new UserResource($this->fromUser),
            'to_user' => new UserResource($this->toUser),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
