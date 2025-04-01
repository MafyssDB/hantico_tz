<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

class CarModelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $permittedRoutes = [
            'car-models.index',
            'car-models.store',
            'car-models.update',
            'car-models.show',
            'car-models.destroy',
        ];

        return [
            'id' => $this->id,
            'title' => $this->title,
            'createdAt' => $this->created_at,
            'brand' => $this->when(in_array(Route::currentRouteName(), $permittedRoutes),
                fn() => new CarBrandResource($this->brand)),
        ];
    }
}
