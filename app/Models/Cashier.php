<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Cashier extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Automatically hash the password when setting it
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
         $this->attributes['password'] = \Hash::needsRehash($value) ? bcrypt($value) : $value;
        }
    }

    /**
     * Verify old password
     */
    public function checkPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }
}
