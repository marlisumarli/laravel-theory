<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeView extends Model
{
    use HasFactory;

    protected $table = 'recipe_views';

    protected $fillable = [
        'email',
        'date',
        'recipe_id',
    ];
}
