<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarModel extends Model
{
    use HasFactory;
    protected $table = 'car_models';
    protected $fillable = [
        'title',
        'brand_id'
    ];

    public function scopeFilterByTitle(Builder $query, ?string $title): Builder
    {
        return $title ? $query->where('title', 'like', '%' . $title . '%') : $query;
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class, 'brand_id');
    }
}
