<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use App\Policies\SessionPolicy;

#[UsePolicy(SessionPolicy::class)]
class PersonalAccessToken extends SanctumPersonalAccessToken
{   
    use HasUuids;

    protected $fillable = [
        'name',
        'token',
        'type',
        'expires_at',
        'device_name',
        'device_ip',
        'device_agent',
    ];
}
