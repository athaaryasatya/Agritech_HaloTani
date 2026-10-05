<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Petani extends Authenticatable
{
    use HasUuids, Notifiable;

    const UPDATED_AT = null;

    protected $table = 'petani';
    protected $primaryKey = 'id_petani';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['nama', 'email', 'password', 'kontak', 'alamat'];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed', 
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function keluhan(): HasMany
    {
        return $this->hasMany(Keluhan::class, 'id_petani', 'id_petani');
    }
}