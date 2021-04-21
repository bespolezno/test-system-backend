<?php

namespace App\Http\Requests;

use App\Models\Question;

class TestCheckRequest extends ApiRequest
{
    const RULES = [
        'text' => ['data' => 'string'],
        'single' => ['data' => 'exists:answers,id'],
        'multiple' => ['data' => 'array|min:1', 'data.*' => 'integer|exists:answers,id'],
        'matching' => ['data' => 'array|min:1', 'data.*.id' => 'required|exists:answers,id', 'data.*.value' => 'required|string'],
    ];

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
            'data.*.id' => 'required|exists:questions,id',
            'data.*.value' => [
                'required',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $item = $this->request->get('data')[$index];
                    $type = @Question::find($item['id'])->type ?? null;
                    $validator = validator(['data' => $value], static::RULES[$type]);
                    if ($validator->fails()) {
                        $fail($validator->errors()->first());
                    }
                },
            ]
        ];
    }
}
