<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Exceptions\CredentialsNotCorrectException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
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
        $user = $this->where('email', strtolower($email))->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw new CredentialsNotCorrectException();
        }

        return $user;
    }

    public function store() : self
    {
        return $this->create([
            'name' => 'waiter name goes here',
            'email' => 'waiter@mail.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
