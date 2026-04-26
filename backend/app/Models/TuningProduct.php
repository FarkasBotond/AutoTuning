<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TuningProduct extends Model
{
    protected $fillable = [
        'car_model_id',
        'service_category_id',
        'name',
        'tuning_company',
        'price',
        'old_price',
        'is_in_stock',
        'badge',
        'image',
    ];

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class);
    }
}
