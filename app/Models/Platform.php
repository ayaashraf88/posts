<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Platform extends Model
{
    use HasFactory;
    protected $fillable = ['type','name'];
    public function post()
    {
        return $this->belongsToMany(Platform::class, 'post_platforms')
        ->withPivot('platform_status')
        ->withTimestamps();   
     }
    public function users()
{
    return $this->hasMany(PlatformUser::class);
    ;
}
}
