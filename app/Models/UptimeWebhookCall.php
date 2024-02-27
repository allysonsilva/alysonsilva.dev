<?php

namespace App\Models;

use App\Support\ORM\BaseModel;

class UptimeWebhookCall extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'msg',
        'heartbeat',
        'monitor',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'msg' => 'array',
        'heartbeat' => 'array',
        'monitor' => 'array',
    ];
}
