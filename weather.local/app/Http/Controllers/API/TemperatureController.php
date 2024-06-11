<?php
declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Services\TemperatureService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Temperature",
 *     description="",
 *     @OA\ExternalDocumentation(
 *         description="",
 *         url=""
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *       securityScheme="bearerAuth",
 *       in="header",
 *       name="bearerAuth",
 *       type="http",
 *       scheme="bearer",
 *       bearerFormat="JWT",
 *  ),
 */
class TemperatureController extends Controller
{
    /**
     * @OA\Schema(
     *  schema="Temperature",
     *  title="Temperature Schema",
     * 	@OA\Property(
     *   property="data",
     *   type="array",
     *    @OA\Items(
     *     @OA\Property(
     *      property="id",
     *      type="integer"
     *     ),
     *     @OA\Property(
     *      property="temperature",
     *      type="string"
     *     ),
     *     @OA\Property(
     *      property="time",
     *      type="string"
     *     )
     *    )
     *   )
     * 	)
     * )
     *
     * @OA\Get(
     *     path="/api/v1/temperature?day={day}",
     *     summary="Show Temperature by given day",
     *     operationId="show",
     *     tags={"Temperature"},
     *     security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="day",
     *          description="Requested Day",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             oneOf={
     *               @OA\Schema(ref="#/components/schemas/Temperature"),
     *                 @OA\Schema(ref="#/components/schemas/Message"),
     *             },
     *                @OA\Examples(example="Temperature", value={
     *                    "data": {
     *                      {
     *                       "id": 14,
     *                       "temperature": "20.83",
     *                       "time":"2024-06-10 00:00:21"
     *                      },
     *                      {
     *                        "id": 15,
     *                        "temperature": "18.43",
     *                        "time":"2024-06-10 00:01:14"
     *                       }
     *                    }
     *                }, summary="Temperature"),
     *             @OA\Examples(example="Message", value={ "message": "Bad credentials" }, summary="Login failed"),
     *         )
     *     )
     * )
     */
    public function getTemperatureByDay(
        Request $request,
        TemperatureService $temperatureService
    ): JsonResponse|AnonymousResourceCollection {
        try {
            $validated = $request->validate(['day' => 'required|date|date_format:Y-m-d']);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $temperatureService->getTemperatureCollectionByDay(day:$validated['day']);
    }
}
