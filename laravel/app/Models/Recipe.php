<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'servings',
        'cooking_time',
        'calories',
        'protein',
        'fat',
        'carbs',
        'fiber',
        'sodium',
        'sugar',
    ];

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    public function steps()
    {
        return $this->hasMany(Step::class)->orderBy('order');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_recipe');
    }
}

