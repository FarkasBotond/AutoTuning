<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarBrandRequest;
use App\Http\Requests\UpdateCarBrandRequest;
use App\Http\Resources\CarBrandResource;
use App\Models\CarBrand;

class CarBrandController extends Controller
{
    public function index()
    {
        return CarBrandResource::collection(CarBrand::all());
    }

    public function store(StoreCarBrandRequest $request)
    {
        return new CarBrandResource(CarBrand::create($request->validated()));
    }

    public function show(CarBrand $carBrand)
    {
        return new CarBrandResource($carBrand);
    }

    public function update(UpdateCarBrandRequest $request, CarBrand $carBrand)
    {
        $carBrand->update($request->validated());
        return new CarBrandResource($carBrand);
    }

    public function destroy(CarBrand $carBrand)
    {
        return $carBrand->delete() ? response()->noContent() : abort(500);
    }
}