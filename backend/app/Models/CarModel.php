<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarModel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'brand_id',
        'model',
        'start_year',
        'end_year',
    ];

    public function brand()
    {
        return $this->belongsTo(CarBrand::class);
    }

    public function companies()
    {
        return $this->belongsToMany(
            TuningCompany::class,
            'company_car_model',
            'model_id',
            'company_id'
        );
    }
}
