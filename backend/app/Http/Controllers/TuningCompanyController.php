<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTuningCompanyRequest;
use App\Http\Requests\UpdateTuningCompanyRequest;
use App\Http\Resources\TuningCompanyResource;
use App\Models\TuningCompany;

class TuningCompanyController extends Controller
{
    public function index()
    {
        return TuningCompanyResource::collection(TuningCompany::all());
    }

    public function store(StoreTuningCompanyRequest $request)
    {
        return new TuningCompanyResource(
            TuningCompany::create($request->validated())
        );
    }

    public function show(TuningCompany $tuningCompany)
    {
        return new TuningCompanyResource($tuningCompany);
    }

    public function update(UpdateTuningCompanyRequest $request, TuningCompany $tuningCompany)
    {
        $tuningCompany->update($request->validated());
        return new TuningCompanyResource($tuningCompany);
    }

    public function destroy(TuningCompany $tuningCompany)
    {
        return $tuningCompany->delete() ? response()->noContent() : abort(500);
    }
}