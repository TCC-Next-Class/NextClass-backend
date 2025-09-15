<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class Ticket extends Model
{
    use HasUuids;

    protected $fillable = ['user_id', 'type', 'used', 'expires_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isValid()
    {
        return !$this->used && (!$this->expires_at || now()->lt($this->expires_at));
    }
}
