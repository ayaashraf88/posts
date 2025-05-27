<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
        'is_admin'
    ];
    protected static $logAttributes = ['name', 'email'];
    protected static $logOnlyDirty = true;
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
         'is_admin' => 'boolean',
    ];
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
    public function platforms(): BelongsToMany
    {
        return $this->belongsToMany(Platform::class,'platform_user')->using(PlatformUser::class) 
        ->withPivot(['is_active']);
        ;
   
    }
   
    public function getActivitylogOptions(): LogOptions
    {
        // return $this->hasMany(Post::class);
        return LogOptions::defaults();

    }
 
    
}
