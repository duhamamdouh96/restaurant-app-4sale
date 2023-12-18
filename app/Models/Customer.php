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
    use HasApiTokens, HasFactory;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function register(string $name, string $email, string $password, string|null $phone) : self
    {
        return $this->create([
            'name' => $name,
            'email' => strtolower($email),
            'password' => Hash::make($password),
            'phone' => $phone ?? null
        ]);
    }

    public function login(string $email, string $password)
    {
        $customer = $this->where('email', strtolower($email))->first();

        if (! $customer || ! Hash::check($password, $customer->password)) {
            throw new CredentialsNotCorrectException();
        }

        return $customer;
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
