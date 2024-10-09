<?php

namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'ref_number' => $this->ref_number,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'image' => 'https://st2.depositphotos.com/3369547/11438/v/380/depositphotos_114380960-stock-illustration-graduation-cap-and-boy-icon.jpg'
        ];
    }
}
