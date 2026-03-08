<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceCategoryRequest;
use App\Http\Requests\UpdateServiceCategoryRequest;
use App\Http\Resources\ServiceCategoryResource;
use App\Models\ServiceCategory;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        return ServiceCategoryResource::collection(ServiceCategory::all());
    }

    public function store(StoreServiceCategoryRequest $request)
    {
        return new ServiceCategoryResource(
            ServiceCategory::create($request->validated())
        );
    }

    public function show(ServiceCategory $serviceCategory)
    {
        return new ServiceCategoryResource($serviceCategory);
    }

    public function update(UpdateServiceCategoryRequest $request, ServiceCategory $serviceCategory)
    {
        $serviceCategory->update($request->validated());
        return new ServiceCategoryResource($serviceCategory);
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        return $serviceCategory->delete() ? response()->noContent() : abort(500);
    }
}