<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'registration_date' => $this->created_at->format('j M, Y - G:i'),
            'role' => $this->roleTypeWithName(),
            'statistics' => $this->getStatistics(),
        ];
    }
}
