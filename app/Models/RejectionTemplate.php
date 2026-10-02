<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RejectionTemplate extends Model
{
    protected $fillable = [
        'title',
        'command',
        'message',
    ];
}
