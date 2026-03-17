<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'brand_id',
        'name',
        'gen',
        'mod',
        'startyear',
        'endyear',
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
