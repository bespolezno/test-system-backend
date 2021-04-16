<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function getRating($value)
    {
        return $this->ratings()
            ->where('min', '<=', $value)
            ->where('max', '>=', $value)
            ->first();
    }
}
