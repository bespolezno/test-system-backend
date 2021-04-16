<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $total = $this->test->questions()->count();
        $correct = round($this->correct_answers / $total * 100);
        return [
            'id' => $this->id,
            'ip' => $this->ip,
            'name' => $this->name,
            'time' => $this->time,
            'created_at' => Carbon::make($this->created_at)->format('d.m.Y H:i:s'),
            'correct_answers' => $this->correct_answers,
            'total_questions' => $total,
            'correct' => $correct,
            'rating' => $this->test->getRating($correct),
            'data' => json_decode($this->data),
        ];
    }
}
