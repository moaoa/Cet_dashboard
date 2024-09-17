<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeworkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'subject_name' => $this->subject->name,
            'start_time' => $this->groups->first()->pivot->start_time,
            'end_time' => $this->groups->first()->pivot->end_time,
            'note' => $this->note,
            'group_name' => $this->groups->first()->name,
            'group_id' => $this->groups->first()->id,
        ];
    }
}
