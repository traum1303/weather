<?php
declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Services\TemperatureService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class TemperatureController extends Controller
{
    public function getTemperatureByDay(Request $request, TemperatureService $temperatureService): JsonResponse|AnonymousResourceCollection
    {
        try {
            $validated = $request->validate(['day' => 'required|date|date_format:Y-m-d']);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $temperatureService->getTemperatureCollectionByDay(day:$validated['day']);
    }
}
