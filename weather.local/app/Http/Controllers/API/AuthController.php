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

class AuthController extends Controller
{
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
