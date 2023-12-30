<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected function stack(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => json_decode(str_replace(['{', '}'], ['[', ']'], $value)),
            set: fn (array $value) => str_replace(['[', ']'], ['{', '}'], json_encode($value)),
        );
    }

    protected $casts = [
        'stack' => 'array',
    ];
}
