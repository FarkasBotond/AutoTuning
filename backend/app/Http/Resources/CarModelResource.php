<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarModelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $name = $this->name ?? $this->model;
        $gen = $this->gen ?? null;
        $mod = $this->mod ?? null;
        $startyear = $this->startyear ?? $this->start_year;
        $endyear = $this->endyear ?? $this->end_year;

        return [
            'id' => $this->id,
            'brand_id' => $this->brand_id,
            'name' => $name,
            'gen' => $gen,
            'mod' => $mod,
            'startyear' => $startyear,
            'endyear' => $endyear,
            'model' => $this->model ?? $name,
            'start_year' => $this->start_year ?? $startyear,
            'end_year' => $this->end_year ?? $endyear,
            'brand' => new CarBrandResource($this->brand)
        ];
    }
}