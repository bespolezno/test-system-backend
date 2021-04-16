<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestStoreRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|unique:tests',
            'description' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|string|in:single,multiple,text,matching',
            'questions.*.answer' => 'required_if:questions.*.type,text|string',
            'questions.*.answers' => 'exclude_if:questions.*.type,text|required|array|min:1',
            'questions.*.answers.*.key' => 'required_if:questions.*.type,matching|string',
            'questions.*.answers.*.value' => 'required|string',
            'questions.*.answers.*.is_correct' => 'exclude_if:questions.*.type,matching|boolean',
            'ratings' => 'required|array|min:1',
            'ratings.*.text' => 'required|string',
            'ratings.*.min' => 'required|between:0,100',
            'ratings.*.max' => 'required|between:0,100|gt:ratings.*.min',
        ];
    }
}
