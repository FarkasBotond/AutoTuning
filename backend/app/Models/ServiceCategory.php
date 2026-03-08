<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    public function companies()
    {
        return $this->belongsToMany(
            TuningCompany::class,
            'company_service',
            'category_id',
            'company_id'
        );
    }
}