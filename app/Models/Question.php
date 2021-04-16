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
            return $answers
                    ->where('question_id', '=', $this->id)
                    ->where('answer', '=', $this->answer)
                    ->count() === 1;
        }

        $answer = $answers->where('question_id', '=', $this->id)->first();

        if (!$answer) {
            return false;
        }

        switch ($this->type) {
            case 'single':
                $correctAnswer = $this->answers()->where(['is_correct' => true])->first();
                return @$correctAnswer->id === $answer['id'];
            case 'multiple':
                $ans = $this->answers;
                $correctCount = $ans->filter(fn($a) => $a->is_correct)->count();
                return $correctCount === count($answer['ids']) &&
                    collect($answer['ids'])->every(fn($id) => @$ans->firstWhere('id', '=', $id)->is_correct);
            case 'matching':
                return $this->answers->every(fn($a) => collect($answer['answers'])
                    ->where('id', '=', $a->id)
                    ->where('value', '=', $a->value)
                    ->count() === 1
                );
            default:
                return false;
        }
    }
}
