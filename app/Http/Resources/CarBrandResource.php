<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

class CarBrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $permittedRoutes = [
            'car-brands.index',
            'car-brands.store',
            'car-brands.update',
            'car-brands.show',
            'car-brands.destroy',
        ];

        return [
            'id' => $this->id,
            'title' => $this->title,
            'createdAt' => $this->created_at,
            'models' => $this->when(in_array(Route::currentRouteName(), $permittedRoutes),
                fn() => CarModelResource::collection($this->models)),
        ];
    }
}
