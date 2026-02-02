<?php

namespace App\Models;

use App\Concerns\HasRoles;
use App\Notifications\CustomVerifyEmail;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'type',
        'provider',
        'provider_id',
        'provider_token',
        'otp_code',
        'otp_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'provider_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'provider_token' => 'encrypted',
        'otp_expires_at' => 'datetime',

    ];

    // ------
    // public function setProviderTokenAttribute($value)
    // {
    //     // $this->attributes['provider_token'] = encrypt($value);
    //     $this->attributes['provider_token'] = Crypt::encrypt($value);
    // }
    // ------
    // public function getProviderTokenAttribute($value)
    // {
    //     // return decrypt($value);
    //     return Crypt::decrypt($value);
    // }

    // protected function provider_token(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn($value) => Crypt::decrypt($value),
    //         set: fn($value) => Crypt::encrypt($value),
    //     );
    // }
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }


    // User ==[calls]-[morphToMany]==> 'role_user' table ==[return|retrieve]==> Role
    // public function roles()
    // {
    //     // {role_user} >>>> ['authorizable_type','authorizable_id','role_id']
    //     return $this->morphToMany(Role::class, 'authorizable', 'role_user', 'authorizable_id', 'role_id', 'id', 'id');
    // }


    public function profile()
    {
        // withDefault() ensures that if this relation is requested and no result is found,it returns a default model instance instead of "null".
        // Note: withDefault is only used with "BelongsTo" and "HasOne" relationships.
        return $this->hasOne(Profile::class, 'user_id', 'id')->withDefault();
    }


    // ================================
    //      For OTP Verification
    // ================================
    /**
     * Send the email verification notification with OTP.
     */
    public function sendEmailVerificationNotification()
    {
        // توليد OTP code
        $otpCode = $this->generateAndSaveOTP();

        // إرسال الإيميل المخصص
        $this->notify(new CustomVerifyEmail($otpCode));
    }

    /**
     * Generate and save OTP code
     */
    public function generateAndSaveOTP()
    {
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $this->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        return $otpCode;
    }

    /**
     * Check if OTP is valid
     */
    public function isOtpValid($otp)
    {
        return $this->otp_code === $otp
            && $this->otp_expires_at
            && Carbon::now()->isBefore($this->otp_expires_at);
    }

    /**
     * Clear OTP after verification
     */
    public function clearOtp()
    {
        $this->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);
    }
}
