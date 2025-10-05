<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
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
        'contact_phone',
        'user_type',
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
    ];

    /**
     * Checks if user is admin
     */
    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    /**
     * Checks if user is hr-representative
     */
    public function isHrRepresentative()
    {
        return $this->user_type === 'hr-representative';
    }

    public function isRecruiter()
    {
        return $this->user_type === 'recruiter';
    }

    public function isCandidate()
    {
        return $this->user_type === 'candidate';
    }


    /**
     * Relationship to Jobs
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
