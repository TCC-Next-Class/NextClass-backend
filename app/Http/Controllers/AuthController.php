<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Laravel\Sanctum\PersonalAccessToken;
use Jenssegers\Agent\Agent;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function token(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $request->merge(['email' => strtolower($request->input('email'))]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $user = $request->user();

        $accessToken = $user->createToken('access', ['*']);
        $accessTokenExpiresAt = Carbon::now()->addMinutes(config('auth.config.token_lifetime', 15));

        $refreshToken = $user->createToken('refresh', ['refresh']);
        $refreshTokenExpiresAt = Carbon::now()->addMinutes(config('auth.config.refresh_token_lifetime', 10080));

        $accessToken->accessToken->update([
            'type' => 'access',
            'expires_at' => $accessTokenExpiresAt,
            'device_name' => (new Agent())->device(),
            'device_ip' => $request->ip(),
            'device_agent' => $request->userAgent()
        ]);
        $refreshToken->accessToken->update([
            'type' => 'refresh',
            'expires_at' => $refreshTokenExpiresAt,
            'device_name' => (new Agent())->device(),
            'device_ip' => $request->ip(),
            'device_agent' => $request->userAgent()
        ]);

        return response()->json([
            'access_token' => $accessToken->plainTextToken,
            'access_token_expires_at' => $accessTokenExpiresAt,
            'refresh_token' => $refreshToken->plainTextToken,
            'refresh_token_expires_at' => $refreshTokenExpiresAt,
        ]);
    }

    public function refresh(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string',
        ]);

        $refreshToken = $request->input('refresh_token');
        $tokenModel = PersonalAccessToken::findToken($refreshToken);

        if (!$tokenModel || $tokenModel->type !== 'refresh') {
            return response()->json(['message' => 'Refresh token inválido'], 401);
        }

        if ($tokenModel->expires_at && $tokenModel->expires_at->isPast()) {
            $tokenModel->delete();
            return response()->json(['message' => 'Refresh token expirado'], 401);
        }

        $user = $tokenModel->tokenable;

        $accessToken = $user->createToken('access', ['*'])->plainTextToken;
        $accessTokenExpiresAt = Carbon::now()->addMinutes(config('auth.config.token_lifetime', 15));

        $accessToken->update([
            'type' => 'access',
            'expires_at' => $accessTokenExpiresAt,
            'device_name' => (new Agent())->device(),
            'device_ip' => $request->ip(),
            'device_agent' => $request->userAgent()
        ]);

        return response()->json([
            'access_token' => $accessToken,
            'access_token_expires_at' => $accessTokenExpiresAt,
        ]);
    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }
}
