<?php

namespace App\Http\Controllers;

use App\Models\PersonalAccessToken as Session;
use App\Http\Requests\StoreSessionRequest;
use App\Http\Resources\SessionCollection;
use App\Http\Resources\SessionResource;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;

class SessionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Session::class, 'session');
        $this->middleware('auth:sanctum')->except(['store']);
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
        //$agent = new Agent();
        //$device = $agent->device() ?: 'Dispositivo desconhecido';
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $device = 'null';

        $accessToken = $user->createToken($device, ['*']);

        $this->updateTokenMeta($accessToken->accessToken, 'access', $device, $ip, $userAgent);

        return response()->json([
            'access_token' => $accessToken->plainTextToken,
            'access_token_expires_at' => config('sanctum.expiration') ? Carbon::now()->addMinutes(config('sanctum.expiration')) : null,
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

    private function updateTokenMeta(Session $session, string $type, string $device, string $ip, string $userAgent): void
    {
        $session->update([
            'type'         => $type,
            'device_name'  => $device,
            'device_ip'    => $ip,
            'device_agent' => $userAgent,
        ]);
    }
}
