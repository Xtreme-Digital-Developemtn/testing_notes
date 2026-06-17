<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    protected $fillable = [
        'user_id',
        'request_id',
        'type',
        'path',
        'original_name',
        'size',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function request()
    {
        return $this->belongsTo(ClientRequest::class, 'request_id');
    }
}
