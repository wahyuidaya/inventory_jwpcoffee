<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';

    // Sesuai kolom di DB (typo aslinya creat_at / update_at)
    const CREATED_AT = 'creat_at';
    const UPDATED_AT = 'update_at';

    protected $fillable = [
        'name', 'email', 'password', 'role', 'remember_token',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }
}