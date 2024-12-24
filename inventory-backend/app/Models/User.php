<?php

namespace App\Models;

use App\Observers\UserObserver;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


#[ObservedBy([UserObserver::class])]
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'google_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => ucwords($value),
            set: fn (?string $value) => strtolower($value)
        );
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => strtolower($value),
            set: fn (string $value) => strtolower($value)
        );
    }

     /**
     * Get all the collaborations the user is part of.
     */
    public function inventoryCollaborations()
    {
        return $this->hasMany(InventoryCollaborator::class, 'user_id');
    }

    public function inbounds()
    {
        return $this->hasMany(Inbound::class, "user_id");
    }
    
    public function outbounds()
    {
        return $this->hasMany(Outbound::class, "user_id");
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class, "inviter_id");
    }

    public function logs()
    {
        return $this->hasMany(Log::class, "user_id");
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, "user_id");
    }

}
