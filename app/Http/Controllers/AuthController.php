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
    public function index(Request $request)
    {
        return new UserResource($request->user());
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $user = $request->user();
        $agent = new Agent();
        $device = $agent->device() ?: 'Dispositivo desconhecido';
        $ip = $request->ip();
        $userAgent = $request->userAgent();

        $accessTokenExpiresAt = Carbon::now()->addMinutes(config('auth.config.token_lifetime', 15));
        $refreshTokenExpiresAt = Carbon::now()->addMinutes(config('auth.config.refresh_token_lifetime', 10080));

        $accessToken = $user->createToken('access', ['*']);
        $refreshToken = $user->createToken('refresh', ['refresh']);

        $this->updateTokenMeta($accessToken->accessToken, 'access', $accessTokenExpiresAt, $device, $ip, $userAgent);
        $this->updateTokenMeta($refreshToken->accessToken, 'refresh', $refreshTokenExpiresAt, $device, $ip, $userAgent);

        return response()->json([
            'access_token' => $accessToken->plainTextToken,
            'access_token_expires_at' => $accessTokenExpiresAt,
            'refresh_token' => $refreshToken->plainTextToken,
            'refresh_token_expires_at' => $refreshTokenExpiresAt,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string',
        ]);

        $tokenModel = PersonalAccessToken::findToken($request->input('refresh_token'));

        if (!$tokenModel || $tokenModel->type !== 'refresh') {
            return response()->json(['message' => 'Refresh token inválido'], 401);
        }

        if ($tokenModel->expires_at && $tokenModel->expires_at->isPast()) {
            $tokenModel->delete();
            return response()->json(['message' => 'Refresh token expirado'], 401);
        }

        $user = $tokenModel->tokenable;
        $agent = new Agent();
        $device = $agent->device() ?: 'Dispositivo desconhecido';
        $ip = $request->ip();
        $userAgent = $request->userAgent();

        $accessTokenExpiresAt = Carbon::now()->addMinutes(config('auth.config.token_lifetime', 15));
        $accessToken = $user->createToken('access', ['*']);
        $this->updateTokenMeta($accessToken->accessToken, 'access', $accessTokenExpiresAt, $device, $ip, $userAgent);

        return response()->json([
            'access_token' => $accessToken->plainTextToken,
            'access_token_expires_at' => $accessTokenExpiresAt,
        ]);
    }

    public function destroy(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    private function updateTokenMeta(PersonalAccessToken $token, string $type, Carbon $expiresAt, string $device, string $ip, string $userAgent): void
    {
        $token->update([
            'type'         => $type,
            'expires_at'   => $expiresAt,
            'device_name'  => $device,
            'device_ip'    => $ip,
            'device_agent' => $userAgent,
        ]);
    }
}
