<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function check($answers)
    {
        $answers = collect($answers);

        if ($this->type === 'text') {
            return [
                'id' => $this->id,
                'correct_answer' => $this->answer,
                'is_correct' => $answers
                        ->where('id', '=', $this->id)
                        ->where('value', '=', $this->answer)
                        ->count() === 1
            ];
        }

        $answer = $answers->where('id', '=', $this->id)->first();

        if (!$answer) {
            return  [
                'id' => $this->id,
                'correct_answer' => null,
                'is_correct' => false
            ];
        }

        switch ($this->type) {
            case 'single':
                $correctAnswer = $this->answers()->where(['is_correct' => true])->first();
                return  [
                    'id' => $this->id,
                    'correct_answer' => @$correctAnswer->id,
                    'is_correct' => @$correctAnswer->id === $answer->value
                ];
            case 'multiple':
                $ans = $this->answers;
                $correctCount = $ans->filter(fn($a) => $a->is_correct)->count();
                return [
                    'id' => $this->id,
                    'correct_answer' => $ans->map->id,
                    'is_correct' => $correctCount === count($answer->value) &&
                        collect($answer->value)->every(fn($id) => @$ans->firstWhere('id', '=', $id)->is_correct)
                ];
            case 'matching':
                return [
                    'id' => $this->id,
                    'correct_answer' => $this->answers->map(fn($answer) => ['id' => $answer->id, 'value' => $answer->value]),
                    'is_correct' => $this->answers->every(fn($a) => collect($answer->value)
                            ->where('id', '=', $a->id)
                            ->where('value', '=', $a->value)
                            ->count() === 1
                    )
                ];
            default:
                return false;
        }
    }
}
