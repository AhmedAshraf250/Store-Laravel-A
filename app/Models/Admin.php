<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

class Admin extends User
{
    /*
     * This is a second user provider.
     * That’s why we need to use Multi-Guard Authentication.
     *
     * Multi-guard is used when the application has more than one user provider,
     * for example:
     * - Users stored in the `users` table
     * - Admins stored in a separate `admins` table
     * In this case, we have two different providers.
     */

    /*
     * This is NOT a regular model.
     * This model is used for authentication and can receive notifications.
     *
     * That’s why we extend   `Illuminate\Foundation\Auth\User`   instead of   `Illuminate\Database\Eloquent\Model`.
     * The `User` class already extends `Model`
     * and adds authentication-related features.
     */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'user_name',
        'password',
        'phone_number',
        'super_admin',
        'status'
    ];
}
