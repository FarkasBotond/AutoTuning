<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TuningCompany extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'website_url'
    ];

    public function models()
    {
        return $this->belongsToMany(
            CarModel::class,
            'company_car_model',
            'company_id',
            'model_id'
        );
    }

    public function services()
    {
        return $this->belongsToMany(
            ServiceCategory::class,
            'company_service',
            'company_id',
            'category_id'
        );
    }
}