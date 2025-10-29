<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $casts = [
        'value' => 'array', // Automatically decode JSON
    ];
    protected $fillable = [
        'key',
        'value'
    ];
}
