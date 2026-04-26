<?php

namespace App\Http\Controllers;

use App\Http\Resources\TuningProductResource;
use App\Models\TuningProduct;
use Illuminate\Http\Request;

class TuningProductController extends Controller
{
    public function index(Request $request)
    {
        $query = TuningProduct::query()
            ->with([
                'carModel.brand',
                'serviceCategory',
            ]);

        if ($request->filled('car_model_id')) {
            $query->where('car_model_id', $request->car_model_id);
        }

        if ($request->filled('service_category_id')) {
            $query->where('service_category_id', $request->service_category_id);
        }

        return TuningProductResource::collection(
            $query->orderBy('id')->get()
        );
    }

    public function show(TuningProduct $tuningProduct)
    {
        return new TuningProductResource(
            $tuningProduct->load([
                'carModel.brand',
                'serviceCategory',
            ])
        );
    }
}