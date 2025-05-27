<?php

namespace App\Models;

use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\belongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model 
{
    use HasFactory;
    protected $fillable = ['title', 'content', 'image_url', 'scheduled_time', 'status', 'user_id'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function platforms(): BelongsToMany
    {
        return $this->belongsToMany(Platform::class,'post_platforms')->using(PostPlatform::class) 
        ->withPivot(['platform_status']);
        ;
   
    }
    protected $casts = [
        'scheduled_time' => 'datetime', 
    ];
}
