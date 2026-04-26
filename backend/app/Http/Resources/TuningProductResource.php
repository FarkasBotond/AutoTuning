<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TuningProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'car_model_id' => $this->car_model_id,
            'service_category_id' => $this->service_category_id,
            'name' => $this->name,
            'tuning_company' => $this->tuning_company,
            'price' => $this->price,
            'old_price' => $this->old_price,
            'is_in_stock' => $this->is_in_stock,
            'badge' => $this->badge,
            'image' => $this->image,
            'car_model' => new CarModelResource($this->whenLoaded('carModel')),
            'service_category' => new ServiceCategoryResource($this->whenLoaded('serviceCategory')),
        ];
    }
}
