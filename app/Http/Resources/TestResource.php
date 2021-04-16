<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TestResource extends JsonResource
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
            'uuid' => $this->uuid,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => Carbon::make($this->created_at)->format('d.m.Y H:i:s'),
            'questions' => QuestionResource::collection($this->questions),
            'ratings' => $this->ratings,
        ];
    }
}
