<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'username',
        'nik',
        'password',
        'role',
    ];

    public function pelakuUmkm()
    {
        return $this->hasOne(PelakuUmkm::class, 'users_id');
    }
    
}
