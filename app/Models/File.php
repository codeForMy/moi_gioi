<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'link',
        'type',
    ];

    public $timestamps = false;

    protected static function booted(): void
    {
        // thêm vào lúc đang tạo
        static::creating(function ($object) {
            $object->user_id = user()->id;
        });
    }
}
