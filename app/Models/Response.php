<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $checkData = null;

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
