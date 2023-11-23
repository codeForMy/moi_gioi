<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObjectLanguage extends Model
{
    // ghi đè tên bảng nếu không nó sẽ thêm s đằng cuối
    public $table = 'object_language';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'object_id',
        'language_id',
        'object_type',
    ];
}
