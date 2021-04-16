<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestCheckRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'time' => 'required|date_format:H:i:s',
            'data' => 'required|array',
            'data.*.question_id' => 'required|exists:questions,id',
            'data.*.answer' => 'nullable|string',
            'data.*.id' => 'nullable|integer|exists:answers,id',
            'data.*.ids' => 'nullable|array|min:1',
            'data.*.ids.*' => 'integer|exists:answers,id',
            'data.*.answers.*.id' => 'required|exists:answers,id',
            'data.*.answers.*.value' => 'required|string',
        ];
    }
}
