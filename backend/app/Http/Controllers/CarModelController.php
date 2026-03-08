<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarModelRequest;
use App\Http\Requests\UpdateCarModelRequest;
use App\Http\Resources\CarModelResource;
use App\Models\CarModel;

class CarModelController extends Controller
{
    public function index()
    {
        return CarModelResource::collection(CarModel::with('brand')->get());
    }

    public function store(StoreCarModelRequest $request)
    {
        return new CarModelResource(
            CarModel::create($request->validated())->load('brand')
        );
    }

    public function show(CarModel $carModel)
    {
        return new CarModelResource($carModel->load('brand'));
    }

    public function update(UpdateCarModelRequest $request, CarModel $carModel)
    {
        $carModel->update($request->validated());
        return new CarModelResource($carModel->load('brand'));
    }

    public function destroy(CarModel $carModel)
    {
        return $carModel->delete() ? response()->noContent() : abort(500);
    }
}