<?php
declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Enum\Abilities;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Enum;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      description="Authentication enpoint description",
 *      @OA\Contact(
 *          email=""
 *      ),
 *     @OA\License(
 *         name="",
 *         url=""
 *     )
 * )
 *
 * @OA\Tag(
 *     name="Authentication",
 *     description="Everything about Authentication",
 *     @OA\ExternalDocumentation(
 *         description="Find out more",
 *         url=""
 *     )
 * )
 *
 * @OA\ExternalDocumentation(
 *     description="Find out more about Swagger and OpenApi",
 *     url="https://swagger.io"
 * )
 *
 * @OA\Schema(
 *  schema="Message",
 *  title="Message Schema",
 * 	@OA\Property(
 * 		property="message",
 * 		type="string"
 * 	)
 * )
 *
 * @OA\Schema(
 *  schema="Token",
 *  title="Token Schema",
 * 	@OA\Property(
 *   property="data",
 *   type="object",
 *     @OA\Property(
 *          property="token",
 *          type="string"
 *     )
 * 	)
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/auth",
     *     tags={"Authentication"},
     *     summary="Autentificate a new user and return (mb create) user tocken- with oneOf examples",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="ability",
     *                     type="string"
     *                 ),
     *                 example={"email": "test@gmail.com", "password": "password", "ability": "GET_TEMPERATURE"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/AuthSucceeded"),
     *                 @OA\Schema(ref="#/components/schemas/AuthFailed"),
     *             },
     *                @OA\Examples(example="AuthSucceeded", value={
     *                    "access_token": "ey76Fdt5pRsOMoJ27f7AOiEjj66HYEZB"
     *                }, summary="An result token."),
     *             @OA\Examples(example="AuthFailed", value={ "message": "Invalid Credentials" }, summary="Login failed"),
     *         )
     *     )
     * )
     *
     * @OA\Schema(
     *  schema="AuthSucceeded",
     *  title="Sample schema for using references",
     * 	@OA\Property(
     *   property="data",
     *   type="object"
     * 	)
     * )
     *
     * @OA\Schema(
     *  schema="AuthFailed",
     *  title="Sample schema for using references",
     * 	@OA\Property(
     * 		property="message",
     * 		type="string"
     * 	)
     * )
     */
    public function auth(Request $request): JsonResponse
    {
        $loginUserData = $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|min:8',
            'ability' => new Enum(Abilities::class)
        ]);

        $user = User::query()->where('email', $loginUserData['email'])->first();

        if(!$user || !Hash::check($loginUserData['password'], $user->password)){
            return response()->json(['message' => 'Invalid Credentials'],Response::HTTP_UNAUTHORIZED);
        }

        /** @var User $user */

        if ($user->currentAccessToken()?->can($loginUserData['ability'] ?? '*'))
        {
            return response()->json(['access_token' => $user->currentAccessToken()->plainTextToken]);
        }

        $token = $user->createToken(
            name: $user->name.'-AuthToken',
            abilities: [$loginUserData['ability'] ?? '*'],
            expiresAt: now()->addHours(4)
        )->plainTextToken;

        return response()->json(['access_token' => $token]);
    }
}
