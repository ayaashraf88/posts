<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PlatformUser extends Pivot
{
    //
    protected $fillable = ['user_id','platform_id','is_active'];
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
