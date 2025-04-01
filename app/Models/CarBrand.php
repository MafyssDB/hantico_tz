<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarBrand extends Model
{
    use HasFactory;
    protected $table = 'car_brands';
    protected $fillable = [
        'title'
    ];

    public function scopeFilterByTitle(Builder $query, ?string $title): Builder
    {
        return $title ? $query->where('title', 'like', '%' . $title . '%') : $query;
    }

    public function models(): HasMany
    {
        return $this->hasMany(CarModel::class, 'brand_id');
    }
}
