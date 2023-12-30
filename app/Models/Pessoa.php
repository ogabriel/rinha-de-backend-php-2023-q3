<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $casts = [
        'education' => 'array',
    ];
}
