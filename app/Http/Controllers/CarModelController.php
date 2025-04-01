<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarModel\FilterRequest;
use App\Http\Requests\CarModel\StoreRequest;
use App\Http\Requests\CarModel\UpdateRequest;
use App\Http\Resources\CarModelResource;
use App\Models\CarModel;

class CarModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $brands = CarModel::filterByTitle($request->title)->paginate(10);
        if ($brands->isEmpty()) {
            abort(404);
        }
        return CarModelResource::collection($brands);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $carModel = CarModel::create($data);
        return new CarModelResource($carModel);
    }

    /**
     * Display the specified resource.
     */
    public function show(CarModel $carModel)
    {
        return new CarModelResource($carModel);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, CarModel $carModel)
    {
        $data = $request->validated();
        $carModel->update($data);
        return new CarModelResource($carModel);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CarModel $carModel)
    {
        $carModel->delete();
        return response()->json(['message' => 'Car model has been deleted!']);
    }
}
