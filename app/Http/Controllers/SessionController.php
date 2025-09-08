<?php

namespace App\Http\Controllers;

use App\Models\PersonalAccessToken as Session;
use App\Http\Requests\StoreSessionRequest;
use App\Http\Resources\SessionCollection;
use App\Http\Resources\SessionResource;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SessionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Session::class, 'session');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return new SessionCollection($request->user()->tokens()->paginate($this->resolvePerPage($request)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSessionRequest $request)
    {
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

    /**
     * Display the specified resource.
     */
    public function show(Session $session)
    {
        return new SessionResource($session);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Session $session)
    {
        $session->delete();

        return response()->json(['message' => 'Session deleted']);
    }

    public function revoke(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Session revoked']);
    }
}
