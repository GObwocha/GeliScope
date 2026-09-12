<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * User model for the GeliScope application.
 *
 * Represents an authenticated user who may hold one of several roles:
 * student, teacher, or parent. Each role links to a dedicated profile model.
 *
 * @property int         $id
 * @property string      $first_name
 * @property string      $last_name
 * @property string      $email
 * @property string      $password
 * @property string      $role
 * @property string|null $profile_photo_url
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
        'role',
        'profile_photo_url',
        'otp_code',
        'otp_expires_at',
        'email_verification_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the student profile associated with this user.
     */
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get the teacher profile associated with this user.
     */
    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * Get the parent profile associated with this user.
     */
    public function parentProfile(): HasOne
    {
        return $this->hasOne(ParentProfile::class);
    }
}
