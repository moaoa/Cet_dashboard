<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            // 'quiz_id' => $this->quiz_id,
            'question' => $this->question,
            // 'answer' => $this->answer,
            'options' => $this->options,
        ];
    }
}
