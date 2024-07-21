<?php

namespace App\Http\Resources;

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
        $gender = $this->gendre_id == 1 ? 'male' : ($this->gendre_id == 2 ? 'female' : 'unknown');

        return [
            'id' => $this->id,
            'user_name' => $this->user_name,
            'email' => $this->email,
            'my_points' => (int) $this->my_points,
            'age' => (int) $this->age,
            'gender' => $gender,
            'gendre_id' => $this->gendre_id,
        ];
    }
}
