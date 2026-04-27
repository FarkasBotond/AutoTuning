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

        if ($request->filled('service_category_id')) {
            $query->where('service_category_id', $request->service_category_id);
        }

        if ($request->filled('car_model_id')) {
            $query->where('car_model_id', $request->car_model_id);
        }

        if ($request->filled('brand_id')) {
            $query->whereHas('carModel', function ($modelQuery) use ($request) {
                $modelQuery->where('brand_id', $request->brand_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($searchQuery) use ($search) {
                $searchQuery
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('tuning_company', 'like', "%{$search}%")
                    ->orWhereHas('carModel', function ($modelQuery) use ($search) {
                        $modelQuery->where('model', 'like', "%{$search}%");
                    })
                    ->orWhereHas('carModel.brand', function ($brandQuery) use ($search) {
                        $brandQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('is_in_stock')) {
            $query->where('is_in_stock', filter_var($request->is_in_stock, FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->filled('only_discounted')) {
            if (filter_var($request->only_discounted, FILTER_VALIDATE_BOOLEAN)) {
                $query->whereNotNull('old_price');
            }
        }

        match ($request->get('sort')) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderByDesc('name'),
            default => $query->orderBy('id'),
        };

        return TuningProductResource::collection(
            $query->get()
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