<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $table = 'recipes';

    protected $fillable = [
        'title',
        'image',
        'description',
        'video',
        'user_email',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_email', 'email');
    }
}
