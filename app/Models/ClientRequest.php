<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientRequest extends Model
{
    protected $table = 'requests';

    protected $fillable = [
        'user_id',
        'admin_id',
        'title',
        'description',
        'priority',
        'status',
        'image_path',
        'video_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function solver()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'request_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'request_id');
    }

    public function mediaFiles()
    {
        return $this->hasMany(MediaFile::class, 'request_id');
    }
}
