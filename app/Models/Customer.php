<?php

namespace App\Models;

use App\Exceptions\CredentialsNotCorrectException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $guarded = ['id'];

    public function register(string $name, string $email, string $password, string|null $phone) : self
    {
        return $this->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'phone' => $phone ?? null
        ]);
    }

    public function login(string $email, string $password)
    {
        if (!auth()->guard('customer')->attempt([
            'email' => strtolower($email),
            'password' => $password,
        ])) {
            throw new CredentialsNotCorrectException();
        }

        return auth()->guard('customer')->user();
    }

    public function reservation() : HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function orders() : HasMany
    {
        return $this->hasMany(Order::class);
    }
}
