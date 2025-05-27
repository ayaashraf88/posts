<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PostPlatform extends Pivot
{
    protected $table = 'post_platforms';

    protected $fillable = [ 'post_id', 'platform_id', 'platform_status'];
    public static function booted(): void
    {
        static::creating(function ($record) {
            $record->platform_status = 0;
        });
    }
}
