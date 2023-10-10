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
        'firstname',
        'sirname',
        'gender',
        'dob',
        'email',
        'profile',
        'password',
        'role',
        'otp_code',

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

    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'user_id');
    }

    public function pledges()
    {
        return $this->hasMany(Pledge::class, 'user_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id', 'id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id', 'id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'user_id');
    }
    public function comments() {
        return $this->hasMany(Comment::class);
    }
    
}
