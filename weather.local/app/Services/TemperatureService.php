<?php
declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\TemperatureResource;
use App\Models\Temperature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class TemperatureService
{
    public function getTemperatureCollectionByDay(string $day): AnonymousResourceCollection|JsonResponse
    {
        $temperatures = Temperature::query()
            ->whereDate('created_at', '=', $day)
            ->get();

        return $temperatures->isEmpty() ?
            response()->json(status: Response::HTTP_NO_CONTENT) :
            TemperatureResource::collection($temperatures);
    }
}
