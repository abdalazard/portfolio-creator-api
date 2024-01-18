<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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

    public function status() {
        return $this->hasOne(Status::class, 'id_user');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'id_user');
    }

    public function projects()
    {
        return $this->hasMany(Projects::class, 'id_user');
    }

    public function skills()
    {
        return $this->hasMany(Skills::class, 'id_user');
    }

    public function others()
    {
        return $this->hasMany(Others::class, 'id_user');
    }

    public function contacts()
    {
        return $this->hasOne(Contacts::class, 'id_user');
    }

}
