<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarBrand\FilterRequest;
use App\Http\Requests\CarBrand\StoreRequest;
use App\Http\Requests\CarBrand\UpdateRequest;
use App\Http\Resources\CarBrandResource;
use App\Models\CarBrand;

class CarBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $brands = CarBrand::filterByTitle($request->title)->paginate(5);
        if ($brands->isEmpty()) {
            abort(404);
        }
        return CarBrandResource::collection($brands);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $carBrand = CarBrand::create($data);
        return new CarBrandResource($carBrand);
    }

    /**
     * Display the specified resource.
     */
    public function show(CarBrand $carBrand)
    {
        return new CarBrandResource($carBrand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, CarBrand $carBrand)
    {
        $data = $request->validated();
        $carBrand->update($data);
        return new CarBrandResource($carBrand);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CarBrand $carBrand)
    {
        $carBrand->delete();
        return response()->json(['message' => 'Car brand deleted!']);
    }
}
